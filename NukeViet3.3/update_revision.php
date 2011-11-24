<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES ., JSC. All rights reserved
 * @Createdate Feb 15, 2011  3:37:23 PM
 */

if (!defined('NV_AUTOUPDATE'))
    die('Stop!!!');

function nv_func_update_data()
{
    global $global_config, $db_config, $db, $error_contents, $language_array;
    
    $delete_all_cache = false;
    
    // Update data
    if ($global_config['revision'] < 902)
    {
        $sql = "ALTER TABLE `" . NV_USERS_GLOBALTABLE . "_reg` CHANGE `userid` `userid` MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT";
        $result = $db->sql_query($sql);
        if (!$result)
        {
            $error_contents[] = 'error update sql revision: 902';
        }
    }
    if ($global_config['revision'] < 988)
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'rewrite_endurl', '/')");
    }

    if ($global_config['revision'] < 1004)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='menu'";
            $result_mod = $db->sql_query($sql);
            while (list($mod) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("DELETE FROM `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` WHERE `func_id` in (SELECT `func_id` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='" . $mod . "')");
                $db->sql_query("DELETE FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='" . $mod . "'");
            }
        }
        $delete_all_cache = true;
    }
    if ($global_config['revision'] < 1042)
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autocheckupdate', '1')");
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'autoupdatetime', '24')");
    }

    if ($global_config['revision'] < 1071)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='faq'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_config` (`config_name` varchar(30) NOT NULL,  `config_value` varchar(255) NOT NULL,  UNIQUE KEY `config_name` (`config_name`))ENGINE=MyISAM");
                $db->sql_query("INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_config` VALUES ('type_main', '0')");
            }
        }
        $delete_all_cache = true;
    }

    if ($global_config['revision'] < 1150)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "__menu`");
            $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "__rows`");

            $sql = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_menu_rows` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `parentid` int(11) unsigned NOT NULL,
			  `mid` int(11) NOT NULL DEFAULT '0',  
			  `title` varchar(255) NOT NULL,
			  `link` text NOT NULL,
			  `note` varchar(255) NOT NULL DEFAULT '',
			  `weight` int(11) NOT NULL,
			  `order` int(11) NOT NULL DEFAULT '0',
			  `lev` int(11) NOT NULL DEFAULT '0',
			  `subitem` mediumtext NOT NULL,
			  `who_view` tinyint(2) NOT NULL DEFAULT '0',
			  `groups_view` varchar(255) NOT NULL,  
			  `module_name` varchar(255) NOT NULL DEFAULT '',
			  `op` varchar(255) NOT NULL DEFAULT '', 
			  `target` tinyint(4) NOT NULL DEFAULT '0',  
			  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
			   PRIMARY KEY (`id`)
			) ENGINE=MyISAM";

            $db->sql_query($sql);

            $sql = "CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_menu_menu` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(50) NOT NULL,
			  `menu_item` mediumtext NOT NULL,
			  `description` varchar(255) NOT NULL DEFAULT '',
			   PRIMARY KEY (`id`),
			  UNIQUE KEY `title` (`title`)
			) ENGINE=MyISAM";
            $db->sql_query($sql);
        }
        $delete_all_cache = true;
    }

    if ($global_config['revision'] < 1123)
    {
        $db->sql_query("INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'getloadavg', '0')");
    }

    if ($global_config['revision'] < 1157)
    {
        $db->sql_query("INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'allowquestion', '1')");
        $db->sql_query("INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'allowuserpublic', '0')");
        $db->sql_query("ALTER TABLE `" . NV_GROUPS_GLOBALTABLE . "` ADD `weight` smallint(4) unsigned NOT NULL DEFAULT '0' AFTER `public`");
    }
    if ($global_config['revision'] < 1174)
    {
        $db->sql_query("ALTER TABLE `" . NV_GROUPS_GLOBALTABLE . "` ADD `weight` int(11) unsigned NOT NULL DEFAULT '0' AFTER `public`");

        $sql = "SELECT `group_id` FROM `" . NV_GROUPS_GLOBALTABLE . "` ORDER BY `group_id`";
        $result = $db->sql_query($sql);
        $weight = 0;
        while ($row = $db->sql_fetchrow($result))
        {
            ++$weight;
            $db->sql_query("UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `weight` =" . $weight . " WHERE `group_id`= " . $row['group_id']);
        }

        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $regroups_func_id = $db->sql_query_insert_id("INSERT INTO `" . $db_config['prefix'] . "_modfuncs` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `layout`, `setting`) VALUES(NULL, 'regroups', 'Regroups', 'users', 1, 0, 1, 'left-body-right', '')");

            list($user_main_func_id) = $db->sql_fetchrow($db->sql_query("SELECT `func_id` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` WHERE `in_module`='users' AND `func_name`='main'"));

            $result_blocks_weight = $db->sql_query("SELECT * FROM `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` WHERE `func_id`= " . $user_main_func_id);
            while ($row = $db->sql_fetchrow($result_blocks_weight))
            {
                $db->sql_query("INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_blocks_weight` (`bid`, `func_id`, `weight`) VALUES ('" . $row['bid'] . "', '" . $regroups_func_id . "', '" . $row['weight'] . "')");
            }
        }

        $db->sql_query("ALTER TABLE `" . NV_LANGUAGE_GLOBALTABLE . "_file` CHANGE `admin_file` `admin_file` VARCHAR( 255 ) NOT NULL DEFAULT '0'");

        $delete_all_cache = true;
    }

    if ($global_config['revision'] < 1209)
    {
        $result = $db->sql_query("SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_%\_modules'");
        $num_table = intval($db->sql_numrows($result));

        $array_update_lang = array();

        if ($num_table > 0)
        {
            while ($item = $db->sql_fetch_assoc($result))
            {
                $item['Name'] = explode("_", $item['Name']);

                if (isset($item['Name'][1]))
                {
                    if (in_array($item['Name'][1], array_keys($language_array)))
                    {
                        $array_update_lang[] = $item['Name'][1];
                    }
                }
            }
        }

        foreach ($array_update_lang as $langupdate)
        {
            $sql = "SELECT `title` FROM `" . $db_config['prefix'] . "_" . $langupdate . "_modules` WHERE `module_file`='download'";
            $resultq = $db->sql_query($sql);

            while (list($module_update) = $db->sql_fetchrow($resultq))
            {
                $array_table = array("", "_tmp");
                foreach ($array_table as $table)
                {
                    $sql = "SELECT `id`, `fileupload`, `fileimage` FROM `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "`";
                    $result = $db->sql_query($sql);
                    while (list($id, $fileupload, $fileimage) = $db->sql_fetchrow($result))
                    {
                        if (!empty($fileimage))
                        {
                            if (preg_match("/^" . str_replace("/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR) . "\//", $fileimage))
                            {
                                $fileimage = substr($fileimage, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR));

                                $db->sql_query("UPDATE `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "` SET `fileimage`=" . $db->dbescape($fileimage) . " WHERE `id`=" . $id);
                            }
                        }

                        if (!empty($fileupload))
                        {
                            $fileupload = explode("[NV]", $fileupload);
                            $array_fileupload = array();
                            foreach ($fileupload as $file)
                            {
                                if (preg_match("/^" . str_replace("/", "\/", NV_BASE_SITEURL . NV_UPLOADS_DIR) . "\//", $file))
                                {
                                    $file = substr($file, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR));
                                }
                                $array_fileupload[] = $file;
                            }

                            $fileupload = implode("[NV]", $array_fileupload);
                            $db->sql_query("UPDATE `" . $db_config['prefix'] . "_" . $langupdate . "_" . $module_update . $table . "` SET `fileupload`=" . $db->dbescape($fileupload) . " WHERE `id`=" . $id);
                        }
                    }
                }
            }
        }
    }

    if ($global_config['revision'] < 1231)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='shops'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs` (`func_id`, `func_name`, `func_custom_name`, `in_module`, `show_func`, `in_submenu`, `subweight`, `layout`, `setting`) VALUES(NULL, 'Sitemap', 'Sitemap', '" . $mod . "', 0, 0, 0, '', '')");
            }
        }
    }

    if ($global_config['revision'] < 1288)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='news'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang_i . "', '" . $mod . "', 'timecheckstatus', '0')");
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_cat`  DROP `del_cache_time`");
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` ADD `sourcetext` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `bodytext`");
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` ADD `catid` mediumint( 8 ) NOT NULL DEFAULT '0' AFTER `id`");
                $resultcatid = $db->sql_query("SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_cat` ORDER BY `order` ASC");
                while (list($catid_i) = $db->sql_fetchrow($resultcatid))
                {
                    $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "` ADD `sourcetext` VARCHAR( 255 ) NOT NULL DEFAULT '' AFTER `bodytext`");
                    $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "` ADD `catid` mediumint( 8 ) NOT NULL DEFAULT '0' AFTER `id`");
                }
                $result_rows = $db->sql_query("SELECT `id`, `listcatid` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` WHERE  `catid`=0 ORDER BY `id` ASC");
                while (list($id, $listcatid) = $db->sql_fetchrow($result_rows))
                {
                    $array_catid = explode(",", $listcatid);
                    $catid = intval($array_catid[0]);
                    $query = "UPDATE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` SET `catid`='" . $catid . "' WHERE `id`=" . $id;
                    $db->sql_query($query);
                    foreach ($array_catid as $catid_i)
                    {
                        $query = "UPDATE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "` SET `catid`='" . $catid . "' WHERE `id`=" . $id;
                        $db->sql_query($query);
                    }
                }
                $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_log`");
            }
        }
    }

    if ($global_config['revision'] < 1292)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='news'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("INSERT INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang_i . "', '" . $mod . "', 'timecheckstatus', '0')");
            }
        }
        nv_save_file_config_global();
    }

    if ($global_config['revision'] < 1305)
    {
        $db->sql_query("DELETE FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='sys' AND `module`='global' AND `config_name`='update_revision_lang_mode'");
        nv_deletefile(NV_ROOTDIR . '/includes/phpsvnclient', true);
        nv_save_file_config_global();
    }

    if ($global_config['revision'] < 1325)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='news'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $result_config = $db->sql_query("SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `lang`='" . $lang_i . "' AND `module`='" . $mod . "'");
                $mod_config = array();
                while (list($config_name, $config_value) = $db->sql_fetchrow($result_config, 1))
                {
                    $mod_config[$config_name] = $config_value;
                }

                $homeheight = $mod_config['homeheight'];
                $homewidth = $mod_config['homewidth'];
                if ($homewidth > $homeheight)
                {
                    $homeheight = round($homewidth * 1.5);
                    $blockheight = round($mod_config['blockwidth'] * 1.5);
                    $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang_i . "', '" . $mod . "', 'homeheight', " . $homeheight . ")");
                    $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('" . $lang_i . "', '" . $mod . "', 'blockheight', " . $blockheight . ")");
                }

                $result_cat = $db->sql_query("SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_cat`");
                while (list($catid_i) = $db->sql_fetchrow($result_cat))
                {
                    $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "`  DROP `bodytext`");
                    $db->sql_query("OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "`");
                }

                $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodytext`");
                $db->sql_query("CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodytext` (
					  `id` int(11) unsigned NOT NULL,
					  `bodytext` mediumtext NOT NULL,
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM");

                list($maxid) = $db->sql_fetchrow($db->sql_query("SELECT max(`id`) FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`"));
                $i1 = 1;
                while ($i1 <= $maxid)
                {
                    $tb = ceil($i1 / 2000);
                    $i2 = $i1 + 1999;

                    $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . $tb . "`");
                    $db->sql_query("CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . $tb . "` (
					  `id` int(11) unsigned NOT NULL,
					  `bodyhtml` mediumtext NOT NULL,
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM");

                    $db->sql_query("INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . $tb . "` SELECT `id`, `bodytext` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` WHERE `id` BETWEEN " . $i1 . " AND " . $i2 . "	ORDER BY `id` ASC");

                    $result_rows = $db->sql_query("SELECT `id`, `bodyhtml` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . $tb . "`");
                    while (list($id, $bodyhtml) = $db->sql_fetchrow($result_rows))
                    {
                        $bodytext = $bodyhtml;
                        // Get image tags
                        if (preg_match_all("/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $bodytext, $match))
                        {
                            foreach ($match[0] as $key => $_m)
                            {
                                $textimg = " " . $match[1][$key];
                                if (preg_match_all("/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt))
                                {
                                    $textimg .= " " . $m_alt[1][0];
                                }
                                $bodytext = str_replace($_m, $textimg, $bodytext);
                            }
                        }
                        // Get link tags
                        if (preg_match_all("/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $bodytext, $match))
                        {
                            foreach ($match[0] as $key => $_m)
                            {
                                $bodytext = str_replace($_m, $match[1][$key] . " " . $match[2][$key], $bodytext);
                            }
                        }
                        $bodytext = nv_unhtmlspecialchars(strip_tags($bodytext));
                        $bodytext = strip_punctuation(str_replace("&nbsp;", " ", $bodytext));
                        $bodytext = preg_replace("/[ ]+/", " ", $bodytext);
                        $db->sql_query("INSERT INTO `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodytext` VALUES ('" . $id . "', " . $db->dbescape($bodytext) . ")");
                    }
                    $i1 = $i2 + 1;
                }

                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`  DROP `bodytext`");
                $db->sql_query("OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`");
            }
        }
    }

    if ($global_config['revision'] < 1336)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='news'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                list($maxid) = $db->sql_fetchrow($db->sql_query("SELECT max(`id`) FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`"));
                $i1 = 1;
                while ($i1 <= $maxid)
                {
                    $tb = ceil($i1 / 2000);
                    $i2 = $i1 + 1999;

                    $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . $tb . "`  
						ADD `sourcetext` VARCHAR(255) NOT NULL DEFAULT '' AFTER `bodyhtml`,
						ADD `imgposition` TINYINT(1) NOT NULL DEFAULT '1' AFTER `sourcetext`,  
						ADD `copyright` TINYINT(1) NOT NULL DEFAULT '0' AFTER `imgposition`,  
						ADD `allowed_send` TINYINT(1) NOT NULL DEFAULT '0' AFTER `copyright`,  
						ADD `allowed_print` TINYINT(1) NOT NULL DEFAULT '0' AFTER `allowed_send`,  
						ADD `allowed_save` TINYINT(1) NOT NULL DEFAULT '0' AFTER `allowed_print`
					");
                    $i1 = $i2 + 1;
                }
                $result_rows = $db->sql_query("SELECT `id`, `sourcetext`, `imgposition`, `copyright`, `allowed_send`, `allowed_print`, `allowed_save` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`");
                while ($row = $db->sql_fetchrow($result_rows))
                {
                    $db->sql_query("UPDATE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_bodyhtml_" . ceil($row['id'] / 2000) . "` SET 
					`sourcetext` = " . $db->dbescape($row['sourcetext']) . ", 
					`imgposition` = '" . $row['imgposition'] . "', 
					`copyright` = '" . $row['copyright'] . "', 
					`allowed_send` = '" . $row['allowed_send'] . "', 
					`allowed_print` = '" . $row['allowed_print'] . "', 
					`allowed_save` = '" . $row['allowed_save'] . "' 
					WHERE `id` = " . $row['id']);
                }

                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows` 
					DROP `imgposition`, 
					DROP `sourcetext`, 
					DROP `copyright`, 
					DROP `allowed_send`, 
					DROP `allowed_print`, 
					DROP `allowed_save`
				");
                $db->sql_query("OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_rows`");

                $result_cat = $db->sql_query("SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_cat`");
                while (list($catid_i) = $db->sql_fetchrow($result_cat))
                {
                    $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "` 
						DROP `imgposition`, 
						DROP `sourcetext`, 
						DROP `copyright`, 
						DROP `allowed_send`, 
						DROP `allowed_print`, 
						DROP `allowed_save`
					");
                    $db->sql_query("OPTIMIZE TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_" . $catid_i . "`");
                }
            }
        }
    }

    if ($global_config['revision'] < 1363)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_modules`  ADD `mobile` VARCHAR(100) NOT NULL DEFAULT '' AFTER `theme`");
            $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_modfuncs`  DROP `layout`");
        }
    }

    if ($global_config['revision'] < 1366)
    {
        $db->sql_query("CREATE TABLE IF NOT EXISTS `" . $db_config['prefix'] . "_ipcountry` (
			  `ip_from` int(11) unsigned NOT NULL,
			  `ip_to` int(11) unsigned NOT NULL,
			  `country` char(2) NOT NULL,
			  `ip_file` smallint(5) unsigned NOT NULL,
			  `time` int(11) NOT NULL DEFAULT '0',
			  UNIQUE KEY `ip_from` (`ip_from`,`ip_to`),
			  KEY `ip_file` (`ip_file`),
			  KEY `country` (`country`),
			  KEY `time` (`time`)
        	) ENGINE=MyISAM");
    }
    if ($global_config['revision'] < 1373)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='shops'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $mod_data . "_money_" . $lang_i . "` CHANGE `exchange` `exchange` DOUBLE NOT NULL DEFAULT '0'");
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $mod_data . "_rows` ADD `product_code` VARCHAR( 32 ) NOT NULL DEFAULT '' AFTER `archive`");
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $mod_data . "_rows` DROP `topic_id`");
                $db->sql_query("DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $mod_data . "_topics`");
            }
        }
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/addtotopics.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/change_topic.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/del_topic.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/list_topic.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/topicajax.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/opicdelnews.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/topics.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/admin/opicsnews.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/funcs/myinfo.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/funcs/myproduct.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/funcs/profile.php');
        nv_deletefile(NV_ROOTDIR . '/modules/shops/funcs/post.php');
        nv_deletefile(NV_ROOTDIR . '/themes/admin_default/modules/shops/topics.tpl');

        $themes = nv_scandir(NV_ROOTDIR . "/themes/", $global_config['check_theme']);
        foreach ($themes as $theme)
        {
            if (file_exists(NV_ROOTDIR . '/themes/' . $theme . '/modules/shops/my_product.tpl'))
            {
                nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/modules/shops/my_product.tpl');
                nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/modules/shops/profile.tpl');
                nv_deletefile(NV_ROOTDIR . '/themes/' . $theme . '/modules/shops/post.tpl');
            }
        }
    }
    if ($global_config['revision'] < 1395)
    {
        $sql = "SELECT lang FROM `" . $db_config['prefix'] . "_setup_language` WHERE `setup`=1";
        $result = $db->sql_query($sql);
        while (list($lang_i) = $db->sql_fetchrow($result))
        {
            $sql = "SELECT title, module_data FROM `" . $db_config['prefix'] . "_" . $lang_i . "_modules` WHERE `module_file`='news'";
            $result_mod = $db->sql_query($sql);
            while (list($mod, $mod_data) = $db->sql_fetchrow($result_mod))
            {
                $db->sql_query("ALTER TABLE `" . $db_config['prefix'] . "_" . $lang_i . "_" . $mod_data . "_cat`  ADD `titlesite` VARCHAR(255) NOT NULL DEFAULT '' AFTER `title`");
            }
        }
    }

    if (!isset($global_config['rewrite_endurl']))
    {
        $global_config['rewrite_endurl'] = '/';
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'rewrite_endurl', " . $db->dbescape($global_config['rewrite_endurl']) . ")");
    }
    if (!isset($global_config['rewrite_exturl']))
    {
        $global_config['rewrite_exturl'] = '/';
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'rewrite_exturl', " . $db->dbescape($global_config['rewrite_exturl']) . ")");
    }
    if ($global_config['revision'] < 1412)
    {
        $array_config_rewrite = array('rewrite_optional' => $global_config['rewrite_optional'], 'rewrite_endurl' => $global_config['rewrite_endurl'], 'rewrite_exturl' => $global_config['rewrite_exturl']);
        nv_rewrite_change($array_config_rewrite);
    }

    if (!isset($global_config['lang_geo']))
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'lang_geo', 0");
    }
    
    if (!isset($global_config['searchEngineUniqueID']))
    {
        $db->sql_query("REPLACE INTO `" . NV_CONFIG_GLOBALTABLE . "` (`lang`, `module`, `config_name`, `config_value`) VALUES ('sys', 'global', 'searchEngineUniqueID', ''");
    }

    nv_save_file_config_global();
    
    if($delete_all_cache)
    {
        nv_delete_all_cache();
    }
    // End date data
    if (empty($error_contents))
    {
        return true;
    }
    return false;
}
?>