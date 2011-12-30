<?php

/**
 * @Project NUKEVIET 3.3
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Feb 15, 2011  3:37:23 PM
 */

define('NV_ADMIN', true);
require_once (str_replace('\\\\', '/', dirname(__file__)) . '/mainfile.php');
require_once (NV_ROOTDIR . "/includes/core/admin_functions.php");
require_once (NV_ROOTDIR . "/includes/rewrite.php");
if (defined("NV_IS_GODADMIN"))
{
    $step = $nv_Request->get_int('step', 'post,get', 1);
    if ($step == 1)
    {
        if ($global_config['revision'] < 1491)
        {
            $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'statistics_timezone', '" . NV_SITE_TIMEZONE_NAME . "')");
        }

        if ($global_config['revision'] < 1501)
        {
            $db->sql_query("ALTER TABLE `" . NV_USERS_GLOBALTABLE . "` CHANGE `birthday` `birthday` INT(11) NOT NULL");
        }

        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '1501')");
        nv_save_file_config_global();
        $link = "<br><br><a href=\"" . NV_BASE_SITEURL . "update_revision.php?step=2\"><font color=\"#616161\">Nâng cấp bước 2</font></a>";
        nv_info_die("Nâng cấp hệ thống", "Thông báo nâng cấp", "Thực hiện nâng cấp bước 1 thành công, hãy cài đặt module tags cho tất cả các ngôn ngữ sau đó qay lại đây để thực hiện bước 2" . $link);
    }
    if ($step == 2)
    {
        $array_lang = array();
        $array_lang_error = array();
        $language_query = $db->sql_query("SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1");
        while (list($lang) = $db->sql_fetchrow($query))
        {
            list($check) = $db->sql_fetchrow($db->sql_query("SELECT count(*) FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `title`='tags' AND `act`=1"));
            if ($check)
            {
                $array_mod = array();

                $mquery = $db->sql_query("SELECT `title`, module_data FROM `" . $db_config['prefix'] . "_" . $lang . "_modules` WHERE `module_file`='news'");
                while (list($mod, $mod_data) = $db->sql_fetchrow($mquery))
                {
                    list($maxid) = $db->sql_fetchrow($db->sql_query("SELECT max(id) FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_rows`"));
                    if ($maxid)
                    {
                        $array_mod[$mod] = $maxid;
                    }
                }
                $array_lang[$lang] = $array_mod;
            }
            else
            {
                $array_lang_error[] = $lang;
            }
        }
        if (empty($array_lang_error))
        {
            $nv_Request->set_Session('langmodserialize', serialize($array_lang));
            $lang = array_keys($array_lang);
            $lang = $lang[0];

            $mod = array_keys($array_lang[$lang]);
            $mod = $mod[0];
            $refresh = "<meta http-equiv=\"refresh\" content=\"3;URL=" . NV_BASE_SITEURL . "update_revision.php?" . NV_LANG_VARIABLE . "=" . $lang . "&amp;" . NV_NAME_VARIABLE . "=" . $mod . "&amp;step=3\" />";
            die("Hệ thống đang thực hiện cập nhật dữ liệu cho module tags: " . $lang . "_" . $mod . $refresh);
        }
        else
        {
            nv_info_die("Nâng cấp hệ thống", "Thông báo nâng cấp", "Lỗi: bạn cần cài module tags cho các ngôn ngữ:" . implode(", ", $array_lang_error));
        }
    }
    elseif ($step == 3)
    {
        $array_lang = unserialize($nv_Request->get_string('langmodserialize', 'session'));

        $module_name = $nv_Request->get_string(NV_NAME_VARIABLE, 'post,get');
        $lang = $nv_Request->get_string(NV_LANG_VARIABLE, 'post,get');
        list($module_file, $module_data) = $db->sql_fetchrow($db->sql_query("SELECT module_file, module_data FROM `" . NV_PREFIXLANG . "_modules` WHERE `title`='" . $module_name . "'"));

        $lm = $nv_Request->get_int('lm', 'get', 0);

        $sql = "SELECT catid, parentid, title, titlesite, alias, viewcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
        $global_array_cat = nv_db_cache($sql, 'catid', $module_name);

        $sql = "SELECT `id`, `catid`, `title`, `alias`, `hometext`, `homeimgthumb`, `homeimgfile`, `keywords` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 AND `id` > " . $lm . " ORDER BY `id` ASC LIMIT 0, 100";
        $result = $db->sql_query($sql);
        while ($rowcontent = $db->sql_fetch_assoc($result))
        {
            $lm = $rowcontent['id'];

            //nv_update_tags
            $array_img = (!empty($rowcontent['homeimgthumb'])) ? explode("|", $rowcontent['homeimgthumb']) : $array_img = array("", "");
            if ($array_img[0] != "" and file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0]))
            {
                $image = NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
            }
            elseif (nv_is_url($rowcontent['homeimgfile']))
            {
                $image = $rowcontent['homeimgfile'];
            }
            elseif ($rowcontent['homeimgfile'] != "" and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile']))
            {
                $image = NV_UPLOADS_DIR . '/' . $module_name . '/' . $rowcontent['homeimgfile'];
            }
            else
            {
                $image = "";
            }
            nv_update_tags($module_name, $rowcontent['id'], $rowcontent['keywords'], $global_array_cat[$rowcontent['catid']]['alias'] . "/" . $rowcontent['alias'] . "-" . $rowcontent['id'], $rowcontent['title'], $rowcontent['hometext'], $image, $rowcontent['publtime']);
            //end nv_update_tags
        }
        if ($lm < $array_lang[$lang][$module_name])
        {
            $refresh = "<meta http-equiv=\"refresh\" content=\"3;URL=" . NV_BASE_SITEURL . "update_revision.php?" . NV_LANG_VARIABLE . "=" . $lang . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;step=3&lm=" . $lm . "\" />";
            die("Hệ thống đang thực hiện cập nhật dữ liệu cho module tags: " . $lang . "_" . $module_name . $refresh);
        }
        else
        {
            unset($array_lang[$lang][$module_name]);
            if (empty($array_lang[$lang]))
            {
                unset($array_lang[$lang]);
            }
            if (empty($array_lang))
            {
                $refresh = "<meta http-equiv=\"refresh\" content=\"3;URL=" . NV_BASE_SITEURL . "update_revision.php?step=4\" />";
                die("Cập nhật dữ liệu cho module tags thành công hệ thống sẽ chuyển sang bước kế tiếp " . $refresh);
            }
            else
            {
                $nv_Request->set_Session('langmodserialize', serialize($array_lang));
                $lang = array_keys($array_lang);
                $lang = $lang[0];

                $mod = array_keys($array_lang[$lang]);
                $mod = $mod[0];
                $refresh = "<meta http-equiv=\"refresh\" content=\"3;URL=" . NV_BASE_SITEURL . "update_revision.php?" . NV_LANG_VARIABLE . "=" . $lang . "&amp;" . NV_NAME_VARIABLE . "=" . $mod . "&amp;step=3\" />";
                die("Hệ thống đang thực hiện cập nhật dữ liệu cho module tags, vui lòng đợi đến khi có thông báo thực hiện xong<br> " . $lang . "_" . $mod . $refresh);
            }
        }
    }
    else
    {
        if ($global_config['revision'] < 1530)
        {
            $language_query = $db->sql_query("SELECT `lang` FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1");
            while (list($lang) = $db->sql_fetchrow($query))
            {
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang . "_voting_rows` ADD `url` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `title`");
                $db->sql_query("UPDATE `" . $db_config['prefix'] . "_" . $lang . "_modfuncs` SET `show_func` = '1' WHERE `in_module`='voting' AND `func_name`='main'");
            }
        }
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'version', '3.4.00')");
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'revision', '1530')");
        nv_save_file_config_global();

        $array_config_rewrite = array('rewrite_optional' => $global_config['rewrite_optional'], 'rewrite_endurl' => $global_config['rewrite_endurl'], 'rewrite_exturl' => $global_config['rewrite_exturl']);
        nv_rewrite_change($array_config_rewrite);
        nv_deletefile(NV_ROOTDIR . '/' . NV_DATADIR . '/searchEngines.xml');

        die("Update successfully, you should immediately delete this file.");
    }
}
else
{
    die("You need login with god administrator");
}
?>