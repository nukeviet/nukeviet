<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

define('NV_MODULE_SETUP_DEFAULT', 'users,statistics,banners,seek,news,contact,about,siteterms,voting,feeds,menu,page,comment,freecontent,two-step-verification');

/**
 * @param string $table_des
 * @param string $table_src
 * @return number
 */
function nv_copy_structure_table($table_des, $table_src)
{
    global $db;
    $db->exec('DROP TABLE IF EXISTS ' . $table_des);
    return $db->exec('CREATE TABLE ' . $table_des . ' LIKE ' . $table_src);
}

/**
 * @param string $lang
 * @return string[]
 */
function nv_delete_table_sys($lang)
{
    global $db_config;

    $sql_drop_table = array();
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modules';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_groups';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_weight';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modfuncs';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_searchkeys';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_referer_stats';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modthemes';
    $sql_drop_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_cronjobs DROP ' . $lang . '_cron_name';
    $sql_drop_table[] = 'DELETE FROM ' . $db_config['prefix'] . '_plugin WHERE plugin_lang=\'' . $lang . '\'';

    // Xóa các trường theo ngôn ngữ email template
    $sql_drop_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates
      DROP " . $lang . "_title,
      DROP " . $lang . "_subject,
      DROP " . $lang . "_content
    ";
    $sql_drop_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates_categories
      DROP " . $lang . "_title
    ";

    return $sql_drop_table;
}

/**
 * @param string $lang
 * @return string[]
 */
function nv_create_table_sys($lang)
{
    global $db_config, $global_config, $db;

    $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini');
    $layoutdefault = ( string )$xml->layoutdefault;

    $sql_create_table = array();
    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modules (
         title varchar(50) NOT NULL,
         module_file varchar(50) NOT NULL DEFAULT '',
         module_data varchar(50) NOT NULL DEFAULT '',
         module_upload varchar(50) NOT NULL DEFAULT '',
         module_theme varchar(50) NOT NULL DEFAULT '',
         custom_title varchar(255) NOT NULL,
         site_title varchar(255) NOT NULL DEFAULT '',
         admin_title varchar(255) NOT NULL DEFAULT '',
         set_time int(11) unsigned NOT NULL DEFAULT '0',
         main_file tinyint(1) unsigned NOT NULL DEFAULT '0',
         admin_file tinyint(1) unsigned NOT NULL DEFAULT '0',
         theme varchar(100) DEFAULT '',
         mobile varchar(100) DEFAULT '',
         description varchar(255) DEFAULT '',
         keywords text,
         groups_view varchar(255) NOT NULL,
         weight tinyint(3) unsigned NOT NULL DEFAULT '1',
         act tinyint(1) unsigned NOT NULL DEFAULT '0',
         admins varchar(255) DEFAULT '',
         rss tinyint(4) NOT NULL DEFAULT '1',
         sitemap tinyint(4) NOT NULL DEFAULT '1',
         gid smallint(5) NOT NULL DEFAULT '0',
         icon varchar(100) NOT NULL DEFAULT '',
         PRIMARY KEY (title),
         UNIQUE KEY icon (icon)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_blocks_groups (
         bid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
         theme varchar(55) NOT NULL,
         module varchar(55) NOT NULL,
         file_name varchar(55) DEFAULT NULL,
         title varchar(255) DEFAULT NULL,
         link varchar(255) DEFAULT NULL,
         template varchar(55) DEFAULT NULL,
         position varchar(55) DEFAULT NULL,
         exp_time int(11) DEFAULT '0',
         active varchar(10) DEFAULT '1',
         act tinyint(1) unsigned NOT NULL DEFAULT '1',
         groups_view varchar(255) DEFAULT '',
         all_func tinyint(4) NOT NULL DEFAULT '0',
         weight int(11) NOT NULL DEFAULT '0',
         config text,
         PRIMARY KEY (bid),
         KEY theme (theme),
         KEY module (module),
         KEY position (position),
         KEY exp_time (exp_time)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_blocks_weight (
         bid mediumint(8) NOT NULL DEFAULT '0',
         func_id mediumint(8) NOT NULL DEFAULT '0',
         weight mediumint(8) NOT NULL DEFAULT '0',
         UNIQUE KEY bid (bid,func_id)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modfuncs (
         func_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
         func_name varchar(55) NOT NULL,
         alias varchar(55) NOT NULL DEFAULT '',
         func_custom_name varchar(255) NOT NULL,
         func_site_title varchar(255) NOT NULL DEFAULT '',
         in_module varchar(50) NOT NULL,
         show_func tinyint(4) NOT NULL DEFAULT '0',
         in_submenu tinyint(1) unsigned NOT NULL DEFAULT '0',
         subweight smallint(2) unsigned NOT NULL DEFAULT '1',
         setting varchar(255) NOT NULL DEFAULT '',
         PRIMARY KEY (func_id),
         UNIQUE KEY func_name (func_name,in_module),
         UNIQUE KEY alias (alias,in_module)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_searchkeys (
         id varchar(32) NOT NULL DEFAULT '',
         skey varchar(250) NOT NULL,
         total int(11) NOT NULL DEFAULT '0',
         search_engine varchar(50) NOT NULL,
         KEY (id),
         KEY skey (skey),
         KEY search_engine (search_engine)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_referer_stats (
         host varchar(250) NOT NULL,
         total int(11) NOT NULL DEFAULT '0',
         month01 int(11) NOT NULL DEFAULT '0',
         month02 int(11) NOT NULL DEFAULT '0',
         month03 int(11) NOT NULL DEFAULT '0',
         month04 int(11) NOT NULL DEFAULT '0',
         month05 int(11) NOT NULL DEFAULT '0',
         month06 int(11) NOT NULL DEFAULT '0',
         month07 int(11) NOT NULL DEFAULT '0',
         month08 int(11) NOT NULL DEFAULT '0',
         month09 int(11) NOT NULL DEFAULT '0',
         month10 int(11) NOT NULL DEFAULT '0',
         month11 int(11) NOT NULL DEFAULT '0',
         month12 int(11) NOT NULL DEFAULT '0',
         last_update int(11) NOT NULL DEFAULT '0',
         UNIQUE KEY host (host(191)),
         KEY total (total)
    ) ENGINE=InnoDB";

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modthemes (
         func_id mediumint(8) DEFAULT NULL,
         layout varchar(100) DEFAULT NULL,
         theme varchar(100) DEFAULT NULL,
         UNIQUE KEY func_id (func_id,layout,theme)
     ) ENGINE=InnoDB";

    $sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_modules (
        title, module_file, module_data, module_upload, module_theme, custom_title, admin_title, set_time, main_file, admin_file,
        theme, mobile, description, keywords, groups_view, weight, act, admins, rss, gid, icon
    ) VALUES
        ('about', 'Page', 'about', 'about', 'Page', 'About', '', 1525251600, 1, 1, '', '', '', '', '0', 1, 1, '', 1, 0, 'fas fa-book-reader'),
        ('news', 'News', 'news', 'news', 'News', 'News', '', 1525251600, 1, 1, '', '', '', '', '0', 2, 1, '', 1, 0, 'far fa-newspaper'),
        ('users', 'Users', 'users', 'users', 'users', 'Users', 'Users', 1525251600, 1, 1, '', '', '', '', '0', 3, 1, '', 0, 0, 'fas fa-users'),
        ('contact', 'Contact', 'contact', 'contact', 'Contact', 'Contact', '', 1525251600, 1, 1, '', '', '', '', '0', 4, 1, '', 0, 0, 'fas fa-phone'),
        ('statistics', 'Statistics', 'statistics', 'statistics', 'Statistics', 'Statistics', '', 1525251600, 1, 0, '', '', '', '', '0', 5, 1, '', 0, 0, 'fas fa-filter'),
        ('voting', 'Voting', 'voting', 'voting', 'Voting', 'Voting', '', 1525251600, 1, 1, '', '', '', '', '0', 6, 1, '', 1, 0, 'fas fa-lightbulb'),
        ('banners', 'Banners', 'banners', 'banners', 'Banners', 'Banners', '', 1525251600, 1, 1, '', '', '', '', '0', 7, 1, '', 0, 0, 'far fa-image'),
        ('seek', 'Seek', 'seek', 'seek', 'Seek', 'Search', '', 1525251600, 1, 0, '', '', '', '', '0', 8, 1, '', 0, 0, 'fas fa-search'),
        ('menu', 'Menu', 'menu', 'menu', 'Menu', 'Menu Site', '', 1525251600, 0, 1, '', '', '', '', '0', 9, 1, '', 0, 0, 'fas fa-th-large'),
        ('feeds', 'Feeds', 'feeds', 'feeds', 'Feeds', 'Rss Feeds', '', 1525251600, 1, 1, '', '', '', '', '0', 10, 1, '', 0, 0, 'fas fa-rss'),
        ('page', 'Page', 'page', 'page', 'Page', 'Page', '', 1525251600, 1, 1, '', '', '', '', '0', 11, 1, '', 1, 0, 'fas fa-book'),
        ('comment', 'Comment', 'comment', 'comment', 'Comment', 'Comment', '', 1525251600, 1, 1, '', '', '', '', '0', 12, 1, '', 0, 0, 'fas fa-comments'),
        ('siteterms', 'Page', 'siteterms', 'siteterms', 'Page', 'Siteterms', '', 1525251600, 1, 1, '', '', '', '', '0', 13, 1, '', 1, 0, 'fas fa-asterisk'),
        ('freecontent', 'FreeContent', 'freecontent', 'freecontent', 'FreeContent', 'Free Content', '', 1525251600, 0, 1, '', '', '', '', '0', 14, 1, '', 0, 0, 'far fa-square'),
        ('two-step-verification', 'TwoStepVerification', 'twostepverification', 'twostepverification', 'TwoStepVerification', 'Two-Step Verification', '', 1525251600, 1, 0, '', '', '', '', '0', 15, 1, '', 0, 0, 'fas fa-key')";

    $sql_create_table[] = "INSERT INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
        ('" . $lang . "', 'global', 'site_domain', ''),
        ('" . $lang . "', 'global', 'site_name', 'NukeViet CMS 4.x'),
        ('" . $lang . "', 'global', 'site_logo', ''),
        ('" . $lang . "', 'global', 'site_banner', ''),
        ('" . $lang . "', 'global', 'site_favicon', ''),
        ('" . $lang . "', 'global', 'site_description', 'Sharing success, connect passions'),
        ('" . $lang . "', 'global', 'site_keywords', ''),
        ('" . $lang . "', 'global', 'theme_type', 'r,d,m'),
        ('" . $lang . "', 'global', 'site_theme', '" . $global_config['site_theme'] . "'),
        ('" . $lang . "', 'global', 'preview_theme', ''),
        ('" . $lang . "', 'global', 'mobile_theme', 'mobile_default'),
        ('" . $lang . "', 'global', 'site_home_module', 'users'),
        ('" . $lang . "', 'global', 'switch_mobi_des', '1'),
        ('" . $lang . "', 'global', 'upload_logo', ''),
        ('" . $lang . "', 'global', 'upload_logo_pos', 'bottomRight'),
        ('" . $lang . "', 'global', 'autologosize1', '50'),
        ('" . $lang . "', 'global', 'autologosize2', '40'),
        ('" . $lang . "', 'global', 'autologosize3', '30'),
        ('" . $lang . "', 'global', 'autologomod', ''),
        ('" . $lang . "', 'global', 'name_show', '" . ($lang!='vi' ? 1 : 0) . "'),
        ('" . $lang . "', 'global', 'cronjobs_next_time', '" . NV_CURRENTTIME . "'),
        ('" . $lang . "', 'global', 'disable_site_content', 'For technical reasons Web site temporary not available. we are very sorry for any inconvenience!'),
        ('" . $lang . "', 'seotools', 'prcservice', '')";

    $lang_weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_setup_language')->fetchColumn() + 1;

    $sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_setup_language (lang, setup, weight) VALUES('" . $lang . "', 1, " . $lang_weight . ")";

    $sql_create_table[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_modthemes (func_id, layout, theme) VALUES ('0', '" . $layoutdefault . "', '" . $global_config['site_theme'] . "')";
    $sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_cronjobs ADD " . $lang . "_cron_name VARCHAR( 255 ) NOT NULL DEFAULT ''";

    /*
     * Tạo các trường theo ngôn ngữ email template
     * Copy dữ liệu sang các trường
     * Thêm khóa cho các trường
     */
    $array_columns = $db->columns_array($db_config['prefix'] . '_emailtemplates');
    $default_lang = '';
    foreach ($array_columns as $_colkey => $_coldata) {
        if (preg_match('/^([a-z]{2})\_content$/', $_colkey, $m)) {
            $default_lang = $m[1];
            break;
        }
    }

    $sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates
        ADD " . $lang . "_title varchar(250) NOT NULL DEFAULT '',
        ADD " . $lang . "_subject varchar(250) NOT NULL DEFAULT '',
        ADD " . $lang . "_content mediumtext NOT NULL
    ";
    $sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates_categories
        ADD " . $lang . "_title varchar(250) NOT NULL
    ";

    if (!empty($default_lang)) {
        $sql_create_table[] = "UPDATE " . $db_config['prefix'] . "_emailtemplates SET
            " . $lang . "_title = " . $default_lang . "_title
        ";
        $sql_create_table[] = "UPDATE " . $db_config['prefix'] . "_emailtemplates_categories SET
            " . $lang . "_title = " . $default_lang . "_title
        ";
    }
    $sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates
        ADD UNIQUE " . $lang . "_title (" . $lang . "_title(191))
    ";
    $sql_create_table[] = "ALTER TABLE " . $db_config['prefix'] . "_emailtemplates_categories
        ADD UNIQUE " . $lang . "_title (" . $lang . "_title(191))
    ";

    return $sql_create_table;
}
