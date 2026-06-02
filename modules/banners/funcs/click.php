<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_BANNERS')) {
    exit('Stop!!!');
}

$nv_BotManager->setPrivate();

$links = NV_MY_DOMAIN;
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    $row_banner = $db->query('SELECT click_url, exp_time, act, publ_time FROM ' . NV_BANNERS_GLOBALTABLE . '_rows WHERE id=' . $id . ' AND act IN (0, 1, 2)')->fetch();
    if (!empty($row_banner['click_url'])) {
        $exp_time = (int) $row_banner['exp_time'];
        $in_schedule = ($row_banner['act'] === 0 && (int) $row_banner['publ_time'] > NV_CURRENTTIME);
        $is_active = !$in_schedule && ($exp_time === 0 || $exp_time > NV_CURRENTTIME);

        // Cho phép redirect trong vòng 2 giờ sau khi hết hạn nhưng không đếm click
        $in_grace = (!$is_active && $exp_time >= NV_CURRENTTIME - 7200);

        if ($is_active || $in_grace) {
            $links = $row_banner['click_url'];

            if ($is_active) {
                $time_set = $nv_Request->get_int($module_name . '_clickid_' . $id, 'cookie', 0);
                if ($time_set == 0 and $nv_Request->get_string('s', 'get', 0) == md5($id . NV_CHECK_SESSION)) {
                    $nv_Request->set_Cookie($module_name . '_clickid_' . $id, 3600, NV_LIVE_COOKIE_TIME);

                    $br = ($client_info['is_mobile']) ? 'Mobile' : $client_info['browser']['key'];
                    $click_ref = '';
                    if (!empty($client_info['referer'])) {
                        $click_ref = nv_is_url($client_info['referer']) ? nv_substr($client_info['referer'], 0, 250) : '#';
                    }

                    $db->query('UPDATE ' . NV_BANNERS_GLOBALTABLE . '_rows SET hits_total=hits_total+1 WHERE id=' . $id);
                    $sql = 'INSERT INTO ' . NV_BANNERS_GLOBALTABLE . '_click (
                        bid, click_time, click_day, click_ip, click_country, click_browse_key, click_browse_name, click_os_key, click_os_name, click_ref
                    ) VALUES (
                        ' . $id . ', ' . NV_CURRENTTIME . ', 0, ' . $db->quote($client_info['ip']) . ',
                        ' . $db->quote($client_info['country']) . ", '', " . $db->quote($br) . ", '',
                        " . $db->quote($client_info['client_os']['name']) . ',
                        ' . $db->quote($click_ref) . '
                    );';
                    $db->query($sql);
                }
            }

            if (!empty($links) and !nv_is_url($links)) {
                $links = NV_MY_DOMAIN;
            }
        }
    }
}

include NV_ROOTDIR . '/includes/header.php';

echo '<script type="text/javascript">';
echo '		window.location.href = ' . nv_htmlspecialchars($links, 'js') . ';';
echo '</script>';
echo '<noscript>';
echo '		<meta http-equiv="refresh" content="0;url=' . nv_htmlspecialchars($links, 'url') . '" />';
echo '</noscript>';
include NV_ROOTDIR . '/includes/footer.php';
