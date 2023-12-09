<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE') or !defined('NV_IS_CRON')) {
    exit('Stop!!!');
}

/**
 * cron_expadmin_handling()
 *
 * @return true
 */
function cron_expadmin_handling()
{
    global $db, $db_config, $nv_Lang, $global_config, $nv_Cache, $language_array;

    $sql = 'SELECT admin_id, lev, after_exp_action, susp_reason FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE lev!=1 AND lev_expired <=' . NV_CURRENTTIME . ' AND is_suspend=0';
    $result = $db->query($sql);
    $suspends = [];
    $downgrades = [];
    while ($row = $result->fetch()) {
        if (!empty($row['after_exp_action']) and $row['lev'] == '2') {
            $downgrades[$row['admin_id']] = $row['after_exp_action'];
        } else {
            $suspends[$row['admin_id']] = $row['susp_reason'];
        }
    }

    if (!empty($suspends)) {
        foreach ($suspends as $admin_id => $susp_reason) {
            $row_user = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id)->fetch();
            $userlang = NV_LANG_DATA;
            if (!empty($row_user['language']) and in_array($row_user['language'], $global_config['setup_langs'], true)) {
                $userlang = $row_user['language'];
            }

            $nv_Lang->loadFile(NV_ROOTDIR . '/includes/language/' . $userlang . '/admin_authors.php', true);

            $susp_reason = !empty($susp_reason) ? unserialize($susp_reason) : [];
            array_unshift($susp_reason, [
                'starttime' => NV_CURRENTTIME,
                'endtime' => 0,
                'start_admin' => 0,
                'end_admin' => '',
                'info' => $nv_Lang->getModule('admin_rights_expired')
            ]);
            $susp_reason = serialize($susp_reason);
            $sth = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . ' SET edittime=' . NV_CURRENTTIME . ', is_suspend=1, susp_reason=:susp_reason WHERE admin_id=' . $admin_id);
            $sth->bindValue(':susp_reason', $susp_reason, PDO::PARAM_STR);
            $is_sendmail = [];
            if ($sth->execute()) {
                nv_insert_logs($userlang, 'authors', $nv_Lang->getModule('suspend1'), $nv_Lang->getModule('lev_expired_suspend', $row_user['username']), 0);

                $gconfigs = [
                    'site_name' => $global_config['site_name'],
                    'site_email' => $global_config['site_email']
                ];
                if ($userlang != NV_LANG_DATA) {
                    $in = "'" . implode("', '", array_keys($gconfigs)) . "'";
                    $result = $db->query('SELECT config_name, config_value FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang='" . $userlang . "' AND module='global' AND config_name IN (" . $in . ')');
                    while ($row = $result->fetch()) {
                        $gconfigs[$row['config_name']] = $row['config_value'];
                    }
                }
                $is_sendmail = [
                    'mail_subject' => $nv_Lang->getModule('suspend_sendmail_title', $gconfigs['site_name']),
                    'mail_message' => $nv_Lang->getModule('suspend_sendmail_mess1', $gconfigs['site_name'], nv_date('d/m/Y H:i', NV_CURRENTTIME), $nv_Lang->getModule('admin_rights_expired'), $gconfigs['site_email'])
                ];
            }

            $nv_Lang->changeLang();

            if (!empty($is_sendmail)) {
                nv_sendmail_async($gconfigs, $row_user['email'], $is_sendmail['mail_subject'], $is_sendmail['mail_message'], '', false, false, [], [], true, [], $userlang);
            }
        }
    }

    if (!empty($downgrades)) {
        $allmods = [];
        foreach ($global_config['setup_langs'] as $l) {
            $allmods[$l] = nv_site_mods($l);
        }

        $nv_Lang->loadFile(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_authors.php', true);

        foreach ($downgrades as $admin_id => $after_exp_action) {
            $db->query('UPDATE ' . NV_AUTHORS_GLOBALTABLE . " SET lev=3, lev_expired=0, after_exp_action='', edittime=" . NV_CURRENTTIME . ' WHERE admin_id = ' . $admin_id);

            nv_groups_add_user(3, $admin_id);
            nv_groups_del_user(2, $admin_id);

            $_modules = json_decode($after_exp_action, true);
            $new = [];
            if (!empty($_modules)) {
                foreach ($_modules as $l => $vs) {
                    $cache_del = false;
                    if (!empty($vs)) {
                        foreach ($vs as $m) {
                            if (isset($allmods[$l][$m])) {
                                $admins = (!empty($allmods[$l][$m]['admins']) ? $allmods[$l][$m]['admins'] . ',' : '') . $admin_id;
                                $admins = array_map('intval', explode(',', $admins));
                                $admins = array_unique($admins);
                                $admins = implode(',', $admins);
                                $sth = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $l . '_modules SET admins= :admins WHERE title= :mod');
                                $sth->bindParam(':admins', $admins, PDO::PARAM_STR);
                                $sth->bindParam(':mod', $m, PDO::PARAM_STR);
                                $sth->execute();
                                $new[] = $m . ' (' . $language_array[$l]['name'] . ')';
                                $cache_del = true;
                            }
                        }
                    }
                    if ($cache_del) {
                        $nv_Cache->delMod('modules', $l);
                    }
                }
            }

            $row_user = $db->query('SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $admin_id)->fetch();

            $log_note = [];
            $log_note[] = 'Username: ' . $row_user['username'];
            $log_note[] = $nv_Lang->getModule('lev') . ': ' . $nv_Lang->getGlobal('level2') . ' =&gt; ' . $nv_Lang->getGlobal('level3');
            if (!empty($new)) {
                $log_note[] = $nv_Lang->getModule('nv_admin_modules') . ':  ' . implode(', ', $new);
            }
            nv_insert_logs(NV_LANG_DATA, 'authors', $nv_Lang->getModule('downgrade_to_modadmin'), implode('<br />', $log_note), 0);
        }

        $nv_Lang->changeLang();
    }

    return true;
}
