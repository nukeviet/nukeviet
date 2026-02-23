<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

define('NV_MODULE_SETUP_DEFAULT', 'users,inform,statistics,banners,zalo,seek,news,contact,about,siteterms,voting,feeds,menu,page,comment,freecontent,two-step-verification,myapi');

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

    $sql_drop_table = [];
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modules';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_groups';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_blocks_weight';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modfuncs';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_searchkeys';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_referer_stats';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modthemes';
    $sql_drop_table[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_modblocks';
    $sql_drop_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_cronjobs DROP ' . $lang . '_cron_name';

    // Xóa các trường theo ngôn ngữ email template
    $sql_drop_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_emailtemplates
      DROP ' . $lang . '_title,
      DROP ' . $lang . '_subject,
      DROP ' . $lang . '_content
    ';
    $sql_drop_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_emailtemplates_categories
      DROP ' . $lang . '_title
    ';

    return $sql_drop_table;
}

/**
 * @param string $lang
 * @param array $init
 * @return string[]
 */
function nv_create_table_sys($lang, $init = [])
{
    global $db_config, $global_config, $db, $crypt;

    $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini');
    $layoutdefault = (string) $xml->layoutdefault;

    $sql_create_table = [];
    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_modules (
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
         icon varchar(100) NOT NULL DEFAULT '' COMMENT 'Icon',
         groups_view varchar(255) NOT NULL,
         weight tinyint(3) unsigned NOT NULL DEFAULT '1',
         act tinyint(1) unsigned NOT NULL DEFAULT '0',
         admins varchar(4000) DEFAULT '',
         rss tinyint(4) NOT NULL DEFAULT '1',
         sitemap tinyint(4) NOT NULL DEFAULT '1',
         PRIMARY KEY (title)
    ) ENGINE=InnoDB COMMENT 'Module ngoài site theo ngôn ngữ'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_blocks_groups (
         bid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
         theme varchar(55) NOT NULL,
         module varchar(55) NOT NULL,
         file_name varchar(55) DEFAULT NULL,
         title varchar(255) DEFAULT NULL,
         link varchar(255) DEFAULT NULL,
         template varchar(55) DEFAULT NULL,
         heading tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Thẻ heading mong muốn',
         position varchar(55) DEFAULT NULL,
         dtime_type CHAR(50) NOT NULL DEFAULT 'regular',
         dtime_details TEXT NULL DEFAULT NULL,
         active varchar(10) DEFAULT '1',
         bot_visible tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Hiển thị với bot',
         act tinyint(1) unsigned NOT NULL DEFAULT '1',
         groups_view varchar(255) DEFAULT '',
         all_func tinyint(4) NOT NULL DEFAULT '0',
         weight int(11) NOT NULL DEFAULT '0',
         config text,
         PRIMARY KEY (bid),
         KEY theme (theme),
         KEY module (module),
         KEY position (position)
    ) ENGINE=InnoDB COMMENT 'Danh sách block theo ngôn ngữ, giao diện'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_blocks_weight (
         bid mediumint(8) NOT NULL DEFAULT '0',
         func_id mediumint(8) NOT NULL DEFAULT '0',
         weight mediumint(8) NOT NULL DEFAULT '0',
         UNIQUE KEY bid (bid,func_id)
    ) ENGINE=InnoDB COMMENT 'Vị trí đặt các block'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_modfuncs (
         func_id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
         func_name varchar(55) NOT NULL,
         alias varchar(55) NOT NULL DEFAULT '',
         func_custom_name varchar(255) NOT NULL,
         func_site_title varchar(255) NOT NULL DEFAULT '',
         description VARCHAR(255) NOT NULL DEFAULT '',
         in_module varchar(50) NOT NULL,
         show_func tinyint(4) NOT NULL DEFAULT '0',
         in_submenu tinyint(1) unsigned NOT NULL DEFAULT '0',
         subweight smallint(2) unsigned NOT NULL DEFAULT '1',
         setting varchar(255) NOT NULL DEFAULT '',
         PRIMARY KEY (func_id),
         UNIQUE KEY func_name (func_name,in_module),
         UNIQUE KEY alias (alias,in_module)
    ) ENGINE=InnoDB COMMENT 'Func của module'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_searchkeys (
         id varchar(32) NOT NULL DEFAULT '',
         skey varchar(250) NOT NULL,
         total int(11) NOT NULL DEFAULT '0',
         search_engine varchar(50) NOT NULL,
         KEY (id),
         KEY skey (skey),
         KEY search_engine (search_engine)
    ) ENGINE=InnoDB COMMENT 'Từ khóa tìm kiếm'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . "_referer_stats (
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
         UNIQUE KEY host (host),
         KEY total (total)
    ) ENGINE=InnoDB COMMENT 'Thống kê đường dẫn đến site'";

    $sql_create_table[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_modthemes (
         func_id mediumint(8) DEFAULT NULL,
         layout varchar(100) DEFAULT NULL,
         theme varchar(100) DEFAULT NULL,
         UNIQUE KEY func_id (func_id,layout,theme)
     ) ENGINE=InnoDB COMMENT "Layout của giao diện theo từng khu vực"';

    $sql_create_table[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_modblocks (
      module_name varchar(50) NOT NULL COMMENT 'Tên module',
      tag varchar(100) NOT NULL COMMENT 'Tag của khối block',
      ini_tag varchar(160) NOT NULL COMMENT 'Tag tương đương trong config ini',
      title varchar(250) NOT NULL DEFAULT '' COMMENT 'Tên gọi nếu có',
      UNIQUE KEY modblock (module_name, tag),
      KEY module_name (module_name),
      KEY tag (tag)
    ) ENGINE=InnoDB COMMENT 'Vị trí block tùy chỉnh theo từng module'";

    $sql_create_table[] = 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang . "_modules (
        title, module_file, module_data, module_upload, module_theme, custom_title, admin_title, set_time, main_file, admin_file,
        theme, mobile, description, keywords, groups_view, weight, act, admins, rss, sitemap, icon
    ) VALUES
         ('about', 'page', 'about', 'about', 'page', 'About', '', 1626512400, 1, 1, '', '', '', '', '0', 1, 1, '', 1, 1, 'fa-solid fa-campground'),
         ('zalo', 'zalo', 'zalo', 'zalo', 'zalo', 'Zalo', 'Zalo', 1626512400, 0, 1, '', '', '', '', '0', 2, 1, '', 1, 1, 'fa-solid fa-z'),
         ('news', 'news', 'news', 'news', 'news', 'News', '', 1626512400, 1, 1, '', '', '', '', '0', 3, 1, '', 1, 1, 'fa-solid fa-newspaper'),
         ('users', 'users', 'users', 'users', 'users', 'Users', 'Users', 1626512400, 1, 1, '', '', '', '', '0', 4, 1, '', 0, 1, 'fa-solid fa-users'),
         ('inform', 'inform', 'inform', 'inform', 'inform', 'Inform', 'Inform', 1626512400, 1, 1, '', '', '', '', '0', 5, 1, '', 0, 1, 'fa-solid fa-bell'),
         ('contact', 'contact', 'contact', 'contact', 'contact', 'Contact', '', 1626512400, 1, 1, '', '', '', '', '0', 6, 1, '', 0, 1, 'fa-solid fa-phone'),
         ('statistics', 'statistics', 'statistics', 'statistics', 'statistics', 'Statistics', '', 1626512400, 1, 0, '', '', '', '', '0', 7, 1, '', 0, 1, 'fa-solid fa-chart-simple'),
         ('voting', 'voting', 'voting', 'voting', 'voting', 'Voting', '', 1626512400, 1, 1, '', '', '', '', '0', 8, 1, '', 1, 1, 'fa-solid fa-square-poll-vertical'),
         ('banners', 'banners', 'banners', 'banners', 'banners', 'Banners', '', 1626512400, 1, 1, '', '', '', '', '0', 9, 1, '', 0, 1, 'fa-solid fa-rectangle-ad'),
         ('seek', 'seek', 'seek', 'seek', 'seek', 'Search', '', 1626512400, 1, 0, '', '', '', '', '0', 10, 1, '', 0, 1, 'fa-solid fa-magnifying-glass'),
         ('menu', 'menu', 'menu', 'menu', 'menu', 'Menu Site', '', 1626512400, 0, 1, '', '', '', '', '0', 11, 1, '', 0, 1, 'fa-solid fa-network-wired'),
         ('feeds', 'feeds', 'feeds', 'feeds', 'feeds', 'Rss Feeds', '', 1626512400, 1, 1, '', '', '', '', '0', 12, 1, '', 0, 1, 'fa-solid fa-rss'),
         ('page', 'page', 'page', 'page', 'page', 'Page', '', 1626512400, 1, 1, '', '', '', '', '0', 13, 1, '', 1, 0, 'fa-solid fa-file-pen'),
         ('comment', 'comment', 'comment', 'comment', 'comment', 'Comment', '', 1626512400, 1, 1, '', '', '', '', '0', 14, 1, '', 0, 1, 'fa-solid fa-comments'),
         ('siteterms', 'page', 'siteterms', 'siteterms', 'page', 'Siteterms', '', 1626512400, 1, 1, '', '', '', '', '0', 15, 1, '', 1, 1, 'fa-solid fa-gavel'),
         ('freecontent', 'freecontent', 'freecontent', 'freecontent', 'freecontent', 'Free Content', '', 1626512400, 0, 1, '', '', '', '', '0', 16, 1, '', 0, 1, 'fa-solid fa-cube'),
         ('two-step-verification', 'two-step-verification', 'two_step_verification', 'two-step-verification', 'two_step_verification', 'Two-Step Verification', '', 1626512400, 1, 0, '', '', '', '', '0', 17, 1, '', 0, 1, 'fa-solid fa-shield-halved')";

    $sql_create_table[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
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
         ('" . $lang . "', 'global', 'user_allowed_theme', ''),
         ('" . $lang . "', 'global', 'mobile_theme', 'mobile_default'),
         ('" . $lang . "', 'global', 'site_home_module', 'users'),
         ('" . $lang . "', 'global', 'switch_mobi_des', '1'),
         ('" . $lang . "', 'global', 'upload_logo', ''),
         ('" . $lang . "', 'global', 'upload_logo_pos', 'bottomRight'),
         ('" . $lang . "', 'global', 'autologosize1', '50'),
         ('" . $lang . "', 'global', 'autologosize2', '40'),
         ('" . $lang . "', 'global', 'autologosize3', '30'),
         ('" . $lang . "', 'global', 'autologomod', ''),
         ('" . $lang . "', 'global', 'tinify_active', '0'),
         ('" . $lang . "', 'global', 'tinify_api', ''),
         ('" . $lang . "', 'global', 'name_show', '" . ($lang != 'vi' ? 1 : 0) . "'),
         ('" . $lang . "', 'global', 'disable_site_content', 'For technical reasons Web site temporary not available. we are very sorry for any inconvenience!'),
         ('" . $lang . "', 'global', 'opensearch_link', ''),
         ('" . $lang . "', 'global', 'data_warning', '0'),
         ('" . $lang . "', 'global', 'antispam_warning', '0'),
         ('" . $lang . "', 'global', 'data_warning_content', ''),
         ('" . $lang . "', 'global', 'antispam_warning_content', ''),
         ('" . $lang . "', 'global', 'mailer_mode', " . $db->quote($init['mailer_mode'] ?? 'mail') . "),
         ('" . $lang . "', 'global', 'smtp_host', 'smtp.gmail.com'),
         ('" . $lang . "', 'global', 'smtp_port', '465'),
         ('" . $lang . "', 'global', 'smtp_username', " . $db->quote($init['smtp_username'] ?? 'user@gmail.com') . "),
         ('" . $lang . "', 'global', 'smtp_password', " . $db->quote($crypt->encrypt($init['smtp_password'] ?? 'user@gmail.com')) . "),
         ('" . $lang . "', 'global', 'smtp_ssl', '1'),
         ('" . $lang . "', 'global', 'verify_peer_ssl', " . intval($init['verify_peer_ssl'] ?? 1) . "),
         ('" . $lang . "', 'global', 'verify_peer_name_ssl', " . intval($init['verify_peer_name_ssl'] ?? 1) . "),
         ('" . $lang . "', 'global', 'sender_name', ''),
         ('" . $lang . "', 'global', 'sender_email', ''),
         ('" . $lang . "', 'global', 'reply_name', ''),
         ('" . $lang . "', 'global', 'reply_email', ''),
         ('" . $lang . "', 'global', 'force_sender', '0'),
         ('" . $lang . "', 'global', 'force_reply', '0'),
         ('" . $lang . "', 'global', 'notify_email_error', '0'),
         ('" . $lang . "', 'global', 'dkim_included', 'sendmail,mail'),
         ('" . $lang . "', 'global', 'smime_included', 'sendmail,mail'),
         ('" . $lang . "', 'global', 'mail_tpl', ''),
         ('" . $lang . "', 'seotools', 'prcservice', '')";

    $lang_weight = $db->query('SELECT MAX(weight) FROM ' . $db_config['prefix'] . '_setup_language')->fetchColumn() + 1;

    $sql_create_table[] = 'INSERT INTO ' . $db_config['prefix'] . "_setup_language (lang, setup, weight) VALUES('" . $lang . "', 1, " . $lang_weight . ')';

    $sql_create_table[] = 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang . "_modthemes (func_id, layout, theme) VALUES ('0', '" . $layoutdefault . "', '" . $global_config['site_theme'] . "')";
    $sql_create_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_cronjobs ADD ' . $lang . "_cron_name VARCHAR( 255 ) NOT NULL DEFAULT ''";

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

    $sql_create_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_emailtemplates
        ADD ' . $lang . "_title varchar(250) NOT NULL DEFAULT '',
        ADD " . $lang . "_subject varchar(250) NOT NULL DEFAULT '',
        ADD " . $lang . '_content mediumtext NOT NULL,
        ADD INDEX ' . $lang . '_title (' . $lang . '_title)
    ';
    $sql_create_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_emailtemplates_categories
        ADD ' . $lang . '_title varchar(250) NOT NULL
    ';

    if (!empty($default_lang)) {
        $sql_create_table[] = 'UPDATE ' . $db_config['prefix'] . '_emailtemplates SET
            ' . $lang . '_title = ' . $default_lang . '_title
        ';
        $sql_create_table[] = 'UPDATE ' . $db_config['prefix'] . '_emailtemplates_categories SET
            ' . $lang . '_title = ' . $default_lang . '_title
        ';
    }
    $sql_create_table[] = 'ALTER TABLE ' . $db_config['prefix'] . '_emailtemplates_categories
        ADD UNIQUE ' . $lang . '_title (' . $lang . '_title(191))
    ';

    return $sql_create_table;
}
