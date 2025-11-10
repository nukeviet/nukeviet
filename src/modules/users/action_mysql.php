<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

if (empty($install_lang['groups'])) {
    $install_lang = [];
    $lang_data = $lang;
    if (file_exists(NV_ROOTDIR . '/install/data_' . $lang . '.php')) {
        include NV_ROOTDIR . '/install/data_' . $lang . '.php';
    } elseif (file_exists(NV_ROOTDIR . '/install/data_en.php')) {
        include NV_ROOTDIR . '/install/data_en.php';
    }
}

$sql_drop_module = [];

global $op, $db, $global_config, $db_config, $nv_Lang;

$array_lang_module_setup = []; // Những ngôn ngữ mà module này đã cài đặt vào (Bao gồm cả ngôn ngữ đang thao tác)
$num_module_exists = 0; // Số ngôn ngữ đã cài (Bao gồm cả ngôn ngữ đang thao tác)
$set_lang_data = ''; // Ngôn ngữ mặc định sẽ copy các field lang qua

// Xác định các ngôn ngữ đã cài đặt
$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_setup_language WHERE setup=1';
$_result = $db->query($_sql);
$array_lang_setup = [];
while ($_row = $_result->fetch()) {
    $array_lang_setup[$_row['lang']] = $_row['lang'];
}

// Xác định các ngôn ngữ đã cài module
foreach ($array_lang_setup as $_lang) {
    $is_setup = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $_lang . '_modules WHERE module_data=' . $db->quote($module_data))->fetchColumn();
    if ($is_setup and $op != 'setup') {
        $array_lang_module_setup[$_lang] = $_lang;
    }
}

// Xác định ngôn ngữ mặc định sẽ copy các field lang qua
if ($lang != $global_config['site_lang'] and in_array($global_config['site_lang'], $array_lang_module_setup, true)) {
    $set_lang_data = $global_config['site_lang'];
} else {
    foreach ($array_lang_module_setup as $_lang) {
        if ($lang != $_lang) {
            $set_lang_data = $_lang;
            break;
        }
    }
}

// Tính toán số module đã cài (Bao gồm cả ngôn ngữ đang thao tác)
$num_module_exists = count($array_lang_module_setup);

// Xóa các langkey của trường dữ liệu khi xóa module ở ngôn ngữ đã cài (có từ 2 module đã cài trở lên)
if (in_array($lang, $array_lang_module_setup, true) and $num_module_exists > 1) {
    // Không xóa khi cài lại module users
    if ($module_data != 'users' or $op != 'recreate_mod') {
        if (empty($global_config['idsite'])) {
            $sql = 'SELECT fid, language FROM ' . $db_config['prefix'] . '_' . $module_data . '_field';
            $_result = $db->query($sql);
            while ($_row = $_result->fetch()) {
                $_row['language'] = unserialize($_row['language']);
                if (isset($_row['language'][$lang])) {
                    unset($_row['language'][$lang]);
                    $_row['language'] = empty($_row['language']) ? '' : serialize($_row['language']);
                    $sql_drop_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote($_row['language']) . ' WHERE fid=' . $_row['fid'];
                }
            }
        }
        $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . "_question WHERE lang='" . $lang . "'";
        $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . "_groups_detail WHERE lang='" . $lang . "'";
        $sql_drop_module[] = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . "_config WHERE config='siteterms_" . $lang . "'";
    }
} elseif ($op != 'setup' and $module_data != 'users') {
    // Xóa hết bảng dữ liệu nếu không phải module users
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data;
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_backupcodes';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_config';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_field';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_groups';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_groups_detail';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_groups_users';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_info';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_oldpass';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_openid';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_passkey';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_question';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_reg';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_edit';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_login';
    $sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $module_data . '_deleted';
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . " (
    userid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    group_id smallint(5) unsigned NOT NULL DEFAULT '0',
    username varchar(100) NOT NULL DEFAULT '',
    md5username char(32) NOT NULL DEFAULT '',
    password varchar(150) NOT NULL DEFAULT '',
    email varchar(100) NOT NULL DEFAULT '',
    first_name varchar(100) NOT NULL DEFAULT '',
    last_name varchar(100) NOT NULL DEFAULT '',
    gender char(1) DEFAULT '',
    photo varchar(255) DEFAULT '',
    birthday int(11) NOT NULL,
    sig text,
    regdate int(11) NOT NULL DEFAULT '0',
    question varchar(255) NOT NULL,
    answer varchar(255) NOT NULL DEFAULT '',
    passlostkey varchar(50) DEFAULT '',
    view_mail tinyint(1) unsigned NOT NULL DEFAULT '0',
    remember tinyint(1) unsigned NOT NULL DEFAULT '0',
    in_groups varchar(255) DEFAULT '',
    active tinyint(1) unsigned NOT NULL DEFAULT '0',
    active2step tinyint(1) unsigned NOT NULL DEFAULT '0',
    secretkey varchar(20) DEFAULT '',
    pref_2fa tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Xác thực 2 bước ưu tiên: 0 chưa chọn để hệ thống tự xác định, 1 mã ứng dụng, 2 khóa truy cập',
    sec_keys smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số khóa bảo mật hoặc passkey',
    checknum varchar(40) DEFAULT '',
    last_login int(11) unsigned NOT NULL DEFAULT '0',
    last_ip varchar(45) DEFAULT '',
    last_agent varchar(255) DEFAULT '',
    last_openid varchar(255) DEFAULT '',
    last_passkey varchar(100) NOT NULL DEFAULT '' COMMENT 'Nickname của passkey cuối cùng',
    last_update int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời điểm cập nhật thông tin lần cuối',
    idsite int(11) NOT NULL DEFAULT '0',
    safemode tinyint(1) unsigned NOT NULL DEFAULT '0',
    safekey varchar(40) DEFAULT '',
    pass_creation_time INT(11) NOT NULL DEFAULT '0',
    pass_reset_request TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Yêu cầu thay đổi mật khẩu: 0: không; 1: Bắt buộc; 2: Khuyến khích',
    email_creation_time INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Thời gian cập nhật email',
    email_reset_request TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Yêu cầu thay đổi email: 0: không; 1: Bắt buộc; 2: Khuyến khích',
    email_verification_time INT(11) NOT NULL DEFAULT '-1' COMMENT '-3: Tài khoản sys, -2: Admin kích hoạt, -1 không cần kích hoạt, 0: Chưa xác minh, > 0 thời gian xác minh',
    active_obj varchar(50) NOT NULL DEFAULT 'SYSTEM' COMMENT 'SYSTEM, EMAIL, OAUTH:xxxx, quản trị kích hoạt thì lưu userid',
    language char(2) NOT NULL DEFAULT '' COMMENT 'Ngôn ngữ giao diện',
    PRIMARY KEY (userid),
    UNIQUE KEY username (username),
    UNIQUE KEY md5username (md5username),
    UNIQUE KEY email (email),
    KEY idsite (idsite)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_config (
    config varchar(100) NOT NULL,
    content text,
    edit_time int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (config)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_question (
    qid smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(240) NOT NULL DEFAULT '',
    lang char(2) NOT NULL DEFAULT '',
    weight mediumint(8) unsigned NOT NULL DEFAULT '0',
    add_time int(11) unsigned NOT NULL DEFAULT '0',
    edit_time int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (qid),
    UNIQUE KEY title (title,lang)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_backupcodes (
    userid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    code varchar(20) NOT NULL,
    is_used tinyint(1) unsigned NOT NULL DEFAULT '0',
    time_used int(11) unsigned NOT NULL DEFAULT '0',
    time_creat int(11) unsigned NOT NULL DEFAULT '0',
    UNIQUE KEY userid (userid, code)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_groups (
    group_id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    alias varchar(240) NOT NULL,
    email varchar(100) DEFAULT '',
    group_type tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0:Sys, 1:approval, 2:public',
    group_color varchar(10) NOT NULL,
    group_avatar varchar(255) NOT NULL,
    require_2step_admin tinyint(1) unsigned NOT NULL DEFAULT '0',
    require_2step_site tinyint(1) unsigned NOT NULL DEFAULT '0',
    is_default tinyint(1) unsigned NOT NULL DEFAULT '0',
    add_time int(11) NOT NULL,
    exp_time int(11) NOT NULL,
    weight int(11) unsigned NOT NULL DEFAULT '0',
    act tinyint(1) unsigned NOT NULL,
    idsite int(11) unsigned NOT NULL DEFAULT '0',
    numbers mediumint(9) unsigned NOT NULL DEFAULT '0',
    siteus tinyint(4) unsigned NOT NULL DEFAULT '0',
    config varchar(250) DEFAULT '',
    PRIMARY KEY (group_id),
    UNIQUE KEY kalias (alias,idsite),
    KEY exp_time (exp_time)
) ENGINE=MyISAM AUTO_INCREMENT=10";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_groups_detail (
    group_id SMALLINT(5) unsigned NOT NULL DEFAULT '0',
    lang CHAR(2) NOT NULL DEFAULT '',
    title VARCHAR(240) NOT NULL,
    description VARCHAR(240) NOT NULL DEFAULT '',
    content TEXT,
    UNIQUE KEY group_id_lang (lang,group_id)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_groups_users (
    group_id smallint(5) unsigned NOT NULL DEFAULT '0',
    userid mediumint(8) unsigned NOT NULL DEFAULT '0',
    is_leader tinyint(1) unsigned NOT NULL DEFAULT '0',
    approved tinyint(1) unsigned NOT NULL DEFAULT '0',
    data text NOT NULL,
    time_requested int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian yêu cầu tham gia',
    time_approved int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Thời gian duyệt yêu cầu tham gia',
    PRIMARY KEY (group_id,userid)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_oldpass (
    userid mediumint(8) unsigned NOT NULL DEFAULT '0',
    password varchar(150) NOT NULL DEFAULT '',
    pass_creation_time INT(11) NOT NULL DEFAULT '0',
    UNIQUE KEY pass_creation_time (userid, pass_creation_time)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_reg (
    userid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(100) NOT NULL DEFAULT '',
    md5username char(32) NOT NULL DEFAULT '',
    password varchar(150) NOT NULL DEFAULT '',
    email varchar(100) NOT NULL DEFAULT '',
    first_name varchar(255) NOT NULL DEFAULT '',
    last_name varchar(255) NOT NULL DEFAULT '',
    gender CHAR(1) NOT NULL DEFAULT '',
    birthday INT(11) NOT NULL,
    sig TEXT NULL DEFAULT NULL,
    regdate int(11) unsigned NOT NULL DEFAULT '0',
    question varchar(255) NOT NULL,
    answer varchar(255) NOT NULL DEFAULT '',
    checknum varchar(50) NOT NULL DEFAULT '',
    users_info text,
    openid_info text,
    idsite mediumint(8) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (userid),
    UNIQUE KEY login (username),
    UNIQUE KEY md5username (md5username),
    UNIQUE KEY email (email)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_openid (
    userid mediumint(8) unsigned NOT NULL DEFAULT '0',
    openid char(50) NOT NULL DEFAULT '',
    opid char(50) NOT NULL DEFAULT '',
    id char(50) NOT NULL DEFAULT '',
    email varchar(100) NOT NULL DEFAULT '',
    UNIQUE KEY opid (openid, opid),
    KEY userid (userid),
    KEY email (email)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_passkey (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    userid mediumint(8) unsigned NOT NULL DEFAULT '0',
    keyid varchar(180) NOT NULL DEFAULT '' COMMENT 'Key ID',
    publickey text NOT NULL COMMENT 'Public key',
    userhandle varchar(100) NOT NULL DEFAULT '' COMMENT 'User handle',
    counter int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Bộ đếm phát hiện thiết bị fake',
    aaguid varchar(50) NOT NULL DEFAULT '' COMMENT 'GUID thiết bị',
    type varchar(50) NOT NULL DEFAULT '' COMMENT 'Loại, thường chỉ là public-key',
    created_at int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Tạo',
    last_used_at int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Lần cuối sử dụng',
    clid varchar(32) NOT NULL DEFAULT '' COMMENT 'ID trình duyệt tạo ra nó',
    enable_login tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Cho phép đăng nhập hay không',
    nickname varchar(100) NOT NULL DEFAULT '' COMMENT 'Đặt tên gợi nhớ',
    PRIMARY KEY (id),
    UNIQUE KEY uid (userid, keyid),
    UNIQUE KEY ukeyid (userhandle(40), keyid(151)),
    KEY userhandle (userhandle)
) ENGINE=MyISAM COMMENT 'Passkey của thành viên để đăng nhập/xác thực 2 bước'";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_field (
    fid mediumint(8) NOT NULL AUTO_INCREMENT,
    field varchar(25) NOT NULL,
    weight int(10) unsigned NOT NULL DEFAULT '1',
    field_type enum('number','date','textbox','textarea','editor','select','radio','checkbox','multiselect', 'file') NOT NULL DEFAULT 'textbox',
    field_choices text NOT NULL,
    sql_choices text NOT NULL,
    match_type enum('none','alphanumeric','unicodename','email','url','regex','callback') NOT NULL DEFAULT 'none',
    match_regex varchar(250) NOT NULL DEFAULT '',
    func_callback varchar(75) NOT NULL DEFAULT '',
    min_length int(11) NOT NULL DEFAULT '0',
    max_length bigint(20) unsigned NOT NULL DEFAULT '0',
    limited_values TEXT NULL DEFAULT NULL,
    for_admin TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    required tinyint(3) unsigned NOT NULL DEFAULT '0',
    show_register tinyint(3) unsigned NOT NULL DEFAULT '0',
    user_editable tinyint(3) unsigned NOT NULL DEFAULT '0',
    show_profile tinyint(4) NOT NULL DEFAULT '1',
    class varchar(50) NOT NULL,
    language text NOT NULL,
    default_value varchar(255) NOT NULL DEFAULT '',
    is_system TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY (fid),
    UNIQUE KEY field (field)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_info (
    userid mediumint(8) unsigned NOT NULL,
    inform CHAR(30) NOT NULL DEFAULT '',
    PRIMARY KEY (userid)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_edit (
    userid mediumint(8) unsigned NOT NULL,
    lastedit int(11) unsigned NOT NULL DEFAULT '0',
    info_basic text NOT NULL,
    info_custom text NOT NULL,
    PRIMARY KEY (userid)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_login (
    userid mediumint(8) unsigned NOT NULL,
    clid char(32) NOT NULL,
    logtime int(11) unsigned NOT NULL,
    mode tinyint(1) unsigned NOT NULL DEFAULT '0',
    agent varchar(255) NOT NULL,
    ip char(50) NOT NULL,
    mode_extra varchar(255) NOT NULL DEFAULT '',
    UNIQUE KEY userid (userid, clid)
) ENGINE=MyISAM";

$sql_create_module[] = 'CREATE TABLE IF NOT EXISTS ' . $db_config['prefix'] . '_' . $module_data . "_deleted (
    userid mediumint(8) unsigned NOT NULL,
    md5username varchar(32) NOT NULL DEFAULT '',
    md5email varchar(32) NOT NULL DEFAULT '',
    request_source varchar(50) NOT NULL DEFAULT '' COMMENT 'Nguồn yêu cầu ví dụ facebook',
    opid char(50) NOT NULL DEFAULT '',
    confirmation_code varchar(36) NOT NULL DEFAULT '',
    request_time int(11) unsigned NOT NULL COMMENT 'Thời điểm nhận yêu cầu',
    issued_at int(11) unsigned NOT NULL COMMENT 'Thời điểm yêu cầu bên nguồn',
    PRIMARY KEY (userid),
    UNIQUE KEY openid (request_source, opid),
    UNIQUE KEY confirmation_code (confirmation_code),
    KEY request_time (request_time),
    KEY md5username (md5username)
) ENGINE=MyISAM COMMENT 'Lưu trữ thông tin thành viên đã yêu cầu xóa dữ liệu cá nhân'";

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_config (config, content, edit_time) VALUES
('access_admin', 'a:8:{s:15:\"access_viewlist\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_addus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:14:\"access_waiting\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:17:\"access_editcensor\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_editus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:12:\"access_delus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_passus\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}s:13:\"access_groups\";a:3:{i:1;b:1;i:2;b:1;i:3;b:1;}}', " . NV_CURRENTTIME . "),
('password_simple', '000000|1234|2000|12345|111111|123123|123456|11223344|654321|696969|1234567|12345678|87654321|123456789|23456789|1234567890|66666666|68686868|66668888|88888888|99999999|999999999|1234569|12345679|aaaaaa|abc123|abc123@|abc@123|admin123|admin123@|admin@123|nuke123|nuke123@|nuke@123|adobe1|adobe123|azerty|baseball|dragon|football|harley|iloveyou|jennifer|jordan|letmein|macromedia|master|michael|monkey|mustang|password|photoshop|pussy|qwerty|shadow|superman|hoilamgi|khongbiet|khongco|khongcopass', " . NV_CURRENTTIME . "),
('deny_email', 'yoursite.com|mysite.com|localhost|xxx', " . NV_CURRENTTIME . "),
('deny_name', 'anonimo|anonymous|god|linux|nobody|operator|root', " . NV_CURRENTTIME . "),
('avatar_width', 80, " . NV_CURRENTTIME . "),
('avatar_height', 80, " . NV_CURRENTTIME . "),
('active_group_newusers', '0', " . NV_CURRENTTIME . "),
('active_editinfo_censor', '0', " . NV_CURRENTTIME . "),
('active_user_logs', '1', " . NV_CURRENTTIME . "),
('min_old_user', '16', " . NV_CURRENTTIME . "),
('hold_deleted_username', '365', " . NV_CURRENTTIME . "),
('register_active_time', '86400', " . NV_CURRENTTIME . "),
('auto_assign_oauthuser', '0', " . NV_CURRENTTIME . "),
('admin_email', '0', " . NV_CURRENTTIME . ")";

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_field (field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, limited_values, required, show_register, user_editable, show_profile, class, language, default_value, is_system) VALUES
('first_name', 1, 'textbox', '', '', 'none', '', '', 0, 100, '', 1, 1, 1, 1, 'input', '', '', 1),
('last_name', 2, 'textbox', '', '', 'none', '', '', 0, 100, '', 0, 1, 1, 1, 'input', '', '', 1),
('gender', 3, 'select', 'a:3:{s:1:\"N\";s:0:\"\";s:1:\"M\";s:0:\"\";s:1:\"F\";s:0:\"\";}', '', 'none', '', '', 0, 1, '', 0, 1, 1, 1, 'input', '', '2', 1),
('birthday', 4, 'date', 'a:1:{s:12:\"current_date\";i:0;}', '', 'none', '', '', 0, 0, '', 1, 1, 1, 1, 'input', '', '0', 1),
('sig', 5, 'textarea', '', '', 'none', '', '', 0, 1000, '', 0, 1, 1, 1, 'input', '', '', 1),
('question', 6, 'textbox', '', '', 'none', '', '', 3, 255, '', 1, 1, 1, 1, 'input', '', '', 1),
('answer', 7, 'textbox', '', '', 'none', '', '', 3, 255, '', 1, 1, 1, 1, 'input', '', '', 1)";

$a = 0;
if ($module_data == 'users') {
    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_groups (group_id, alias, email, group_type, group_color, group_avatar, is_default, add_time, exp_time, weight, act, idsite, numbers, siteus, config) VALUES
    (1, 'Super-Admin', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 1, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (2, 'General-Admin', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (3, 'Module-Admin', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (4, 'Users', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 1, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (7, 'New-Users', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (5, 'Guest', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
    (6, 'All', '', 0, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}')";
}

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_groups (group_id, alias, email, group_type, group_color, group_avatar, is_default, add_time, exp_time, weight, act, idsite, numbers, siteus, config) VALUES
(10, 'NukeViet-Fans', '', 2, '', '', 1, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
(11, 'NukeViet-Admins', '', 2, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}'),
(12, 'NukeViet-Programmers', '', 1, '', '', 0, " . NV_CURRENTTIME . ', 0, ' . ++$a . ", 1, 0, 0, 0, 'a:7:{s:17:\"access_groups_add\";i:1;s:17:\"access_groups_del\";i:1;s:12:\"access_addus\";i:0;s:14:\"access_waiting\";i:0;s:13:\"access_editus\";i:0;s:12:\"access_delus\";i:0;s:13:\"access_passus\";i:0;}')";

$sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_groups_detail (group_id, lang, title, description, content) VALUES
(1, '" . $lang . "', 'Super Admin', '', ''),
(2, '" . $lang . "', 'General Admin', '', ''),
(3, '" . $lang . "', 'Module Admin', '', ''),
(4, '" . $lang . "', 'Users', '', ''),
(7, '" . $lang . "', 'New Users', '', ''),
(5, '" . $lang . "', 'Guest', '', ''),
(6, '" . $lang . "', 'All', '', ''),
(10, '" . $lang . "', '" . $install_lang['groups']['NukeViet-Fans'] . "', '" . $install_lang['groups']['NukeViet-Fans-desc'] . "', ''),
(11, '" . $lang . "', '" . $install_lang['groups']['NukeViet-Admins'] . "', '" . $install_lang['groups']['NukeViet-Admins-desc'] . "', ''),
(12, '" . $lang . "', '" . $install_lang['groups']['NukeViet-Programmers'] . "', '" . $install_lang['groups']['NukeViet-Programmers-desc'] . "', '')";

if ($lang == 'vi') {
    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_question (title, lang, weight, add_time, edit_time) VALUES
    ('Bạn thích môn thể thao nào nhất', '" . $lang . "', 1, 1274840238, 1274840238),
    ('Món ăn mà bạn yêu thích', '" . $lang . "', 2, 1274840250, 1274840250),
    ('Thần tượng điện ảnh của bạn', '" . $lang . "', 3, 1274840257, 1274840257),
    ('Bạn thích nhạc sỹ nào nhất', '" . $lang . "', 4, 1274840264, 1274840264),
    ('Quê ngoại của bạn ở đâu', '" . $lang . "', 5, 1274840270, 1274840270),
    ('Tên cuốn sách &quot;gối đầu giường&quot;', '" . $lang . "', 6, 1274840278, 1274840278),
    ('Ngày lễ mà bạn luôn mong đợi', '" . $lang . "', 7, 1274840285, 1274840285)";

    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_config (config, content, edit_time) VALUES ('siteterms_" . $lang . "', '<p> Để đăng ký Tài khoản người dùng, bạn phải cam kết đồng ý với các điều khoản dưới đây. Chúng tôi có thể thay đổi lại những điều khoản này vào bất cứ lúc nào và chúng tôi sẽ cố gắng thông báo đến bạn kịp thời.<br /> <br /> Bạn cam kết không gửi bất cứ bài viết có nội dung lừa đảo, thô tục, thiếu văn hoá; vu khống, khiêu khích, đe doạ người khác; liên quan đến các vấn đề tình dục hay bất cứ nội dung nào vi phạm luật pháp của quốc gia mà bạn đang sống, luật pháp của quốc gia nơi đặt máy chủ của website này hay luật pháp quốc tế. Nếu vẫn cố tình vi phạm, ngay lập tức bạn sẽ bị cấm tham gia vào website. Địa chỉ IP của tất cả các bài viết đều được ghi nhận lại để bảo vệ các điều khoản cam kết này trong trường hợp bạn không tuân thủ.<br /> <br /> Bạn đồng ý rằng website có quyền gỡ bỏ, sửa, di chuyển hoặc khoá bất kỳ bài viết nào trong website vào bất cứ lúc nào tuỳ theo nhu cầu công việc.<br /> <br />Đăng ký Tài khoản người dùng của chúng tôi, bạn cũng phải đồng ý rằng, bất kỳ thông tin cá nhân nào mà bạn cung cấp đều được lưu trữ trong cơ sở dữ liệu của hệ thống. Mặc dù những thông tin này sẽ không được cung cấp cho bất kỳ người thứ ba nào khác mà không được sự đồng ý của bạn, chúng tôi không chịu trách nhiệm về việc những thông tin cá nhân này của bạn bị lộ ra bên ngoài từ những kẻ phá hoại có ý đồ xấu tấn công vào cơ sở dữ liệu của hệ thống.</p>', 1274757129)";
} elseif ($lang == 'fr') {
    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_question (title, lang, weight, add_time, edit_time) VALUES
    ('Votre lieu de naissance?', '" . $lang . "', 1, 1274841115, 1274841115),
    ('Votre anniversaire?', '" . $lang . "', 2, 1274841123, 1274841123),
    ('Votre livre préféré?', '" . $lang . "', 3, 1274841131, 1274841131),
    ('Votre prof préféré?', '" . $lang . "', 4, 1274841142, 1274841142),
    ('Votre chaine télé préférée?', '" . $lang . "', 5, 1274841150, 1274841150)";

    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_config (config, content, edit_time) VALUES ('siteterms_" . $lang . "', '<p align=\"center\"> <strong><u>TERMES ET CONDITIONS GÉNÉRALES</u></strong></p><p> <strong>I. OBJET</strong></p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp; Les présentes conditions générales ont pour objet de fixer les règles d’utilisation de notre site par les utilisateurs enregistrés ou non.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp; Tout utilisateur s’engage à respecter ces conditions lors de chacune de ses visites sur notre site.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Les utilisateurs enregistrés ou non reconnaissent avoir pris connaissance des présentes conditions générales et déclarent les accepter sans réserve.</p><p> </p><p> <strong>II. CODE DE CONDUITE</strong></p><p> <strong>Utilisation du code confidentiel</strong></p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Les éléments d’identification (login et mot de passe) permettant à l’utilisateur enregistré de s’identifier et de se connecter à la partie privée du site sont personnels et confidentiels. Le mot de passe est modifiable en ligne par l&#039;utilisateur enregistré, notamment en cas de perte ou de vol du mot de passe.</p><p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; L’utilisateur enregistré est entièrement responsable de l’utilisation des éléments d’identification le concernant. Il s’engage à conserver secret ses éléments d’identification et à ne pas les divulguer sous quelque forme que ce soit.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; En cas de perte ou de vol du login, l&#039;utilisateur enregistré devra se réinscrire.</p><p> <strong>Utilisation des rubriques</strong></p><p> L’utilisateur enregistré ou non s’engage à ne pas se servir de notre site pour :</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Téléch@rger, envoyer, transmettre par e-mail ou de toute autre manière tout contenu qui soit illégal, nuisible, menaçant, abusif, constitutif de harcèlement, diffamatoire, vulgaire, obscène, menaçant pour la vie privée d’autrui, haineux, raciste ou autrement répréhensible.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Porter atteinte d’une quelconque manière aux utilisateurs mineurs.</p><p> Nous nous réservons le droit de supprimer ou modifier tout contenu disponible via son site d’administration.</p><p> </p><p> <strong>III. PARTICIPATION EDITORIALE</strong></p><p> Tout utilisateur du site, enregistré ou non peut participer à la partie éditoriale du site en en remplissant les formulaire disponibles en ligne.</p><p> En remplissant ces formulaires, l’utilisateur garantit :</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qu’il est bien l’auteur de la contribution proposée et qu’il est titulaire des droits d’auteur y afférent, ou qu&#039;il a l&#039;autorisation explicite de l&#039;auteur de la contribution de la diffuser sur notre site.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp; Qu’il nous autorise à diffuser, publier, reproduire cette contribution sur notre site et à l’intégrer dans sa base de données gratuitement.</p><p> </p><p> <strong>V. REGLES D’USAGE D’INTERNET</strong></p><p> Tout utilisateur du site déclare accepter les caractéristiques et les limites de l’internet et en particulier reconnaître :</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qu’il est seul responsable de l’utilisation qu’il fait des informations présentes sur le site. En conséquence, nous ne saurions être tenue responsable de quelconques dommages directs ou indirects découlant de l’utilisation de ces informations.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qu’il est seul responsable de l’utilisation du contenu des sites ayant un lien hypertexte avec notre site . Nous déclinons toute responsabilité quant à leur contenu.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Que la communication de ses éléments d’identification et d’une manière générale de toute information jugée par lui comme sensible ou confidentielle est faite à ses risques et périls.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qu’il a connaissance de la nature de l’internet et en particulier de ses performances techniques et des temps de réponse pour consulter, interroger ou transférer des informations. Nous ne saurions être tenue responsable des dysfonctionnements du réseau internet.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Qu’il lui appartient de prendre toutes mesures appropriées de façon à protéger ses propres données et logiciels de la contamination par d’éventuels virus circulant à travers notre site. Nous ne pourrons être tenue responsable des dégâts éventuels subis.</p><p> </p><p> <strong>VI. RESPONSABILITE</strong></p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Alors même que nous s’efforcons que le contenu de notre site soit le plus fiable possible, nous ne pouvons donner aucune garantie quant à l’ensemble des informations présentes sur le site, qu’elles soient fournies par notre site, par ses partenaires ou par tout tiers, par l’envoi d’e-mails ou de toute autre forme de communication. De même, nous &nbsp;n’apportons aucune garantie quant à l’utilisation desdites informations.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nous ne saurions en conséquence être tenu pour responsable du préjudice ou dommage pouvant résulter de l’utilisation des informations présentes sur notre site, ni d’erreurs ou omissions dans celles-ci. ous déclinons toute responsabilité en ce qui concerne les contenus des sites web édités par des tiers et accessibles depuis notre site par des liens hypertextes.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Concernant les sites marchands mentionnés dans les publicités affichées sur notre site ou dans n&#039;importe quelle rubrique du site, nous ne sommes nullement responsable des litiges qui pourraient survenir entre un site marchand et un utilisateur.</p><p> &nbsp;&nbsp;&nbsp; *&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; De même, si l’utilisateur contracte avec des annonceurs présents sur notre site, nous n’assumons aucune obligation ou responsabilité concernant la délivrance des produits et services proposés par ses partenaires commerciaux.</p><p> </p><p> <strong>VII. INFORMATIQUE ET LIBERTES</strong></p><p> L’utilisateur est informé que des informations nominatives le concernant sont collectée lors de sa consultation de notre site . Ces informations, destinées à notre site , pourront être communiquées à des tiers à des fins commerciales, sauf opposition de l’utilisateur.</p><p> L’utilisateur dispose d’un droit d’accès, de modification, de rectification et de suppression des données personnelles le concernant. Il doit exercer ce droit en adressant un courrier précisant les modifications demandées à l’administrateur de notre site.</p><p> Par l’accès à notre site, nous consons à l’utilisateur qui l’accepte, une licence d’utilisation pour les informations consultées sur notre site.</p><p> La licence confère à l’utilisateur un droit d’usage privé, non collectif et non exclusif sur les informations consultées. Elle comprend le droit de reproduire et/ou de stocker à des fins strictement personnelles.</p><p> Toute mise en réseau, toute rediffusion, sous quelque forme, même partielle, sont interdites.</p><p> Ce droit est personnel, il est réservé à l’usage exclusif et non collectif de l’utilisateur. Il n’est transmissible en aucune manière.</p><p> La violation de ces dispositions impératives soumet le contrevenant et toute personne responsable, aux peines civiles et pénales prévues par la loi.</p><p> <strong>IX. RECLAMATIONS</strong></p><p> Les réclamations afférentes à l&#039;inscription à notre site et à leurs conditions d’utilisation peuvent être formulées soit directement en ligne par courrier électronique, soit par courrier adressé à l’administrateur du site.</p><p> <strong>X. MODIFICATIONS</strong></p><p> Toute modification de ces conditions générales doit être réalisée par écrit entre les deux parties, par votre acceptation en ligne des termes mis à jour, ou, si vous poursuivez votre participation à notre site, après mise à jour de ces termes par nous.</p><p> <strong>XI. DISPOSITIONS GENERALES</strong></p><p> En cas de non respect ou de non acceptation de ces conditions générales, nous nous réservons le droit d&#039;exclure un utilisateur enregistré ou non de notre site .</p><p> Si l’une quelconque des stipulations des présentes conditions est tenue pour nulle et sans objet, elle sera réputée non écrite et n’entraînera pas la nullité des autres stipulations.</p>', 1274757617)";
} else {
    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_question (title, lang, weight, add_time, edit_time) VALUES
    ('What is the first name of your favorite uncle?', '" . $lang . "', 1, 1274841115, 1274841115),
    ('whe-re did you meet your spouse', '" . $lang . "', 2, 1274841123, 1274841123),
    ('What is your oldest cousin&#039;s name?', '" . $lang . "', 3, 1274841131, 1274841131),
    ('What is your youngest child&#039;s username?', '" . $lang . "', 4, 1274841142, 1274841142),
    ('What is your oldest child&#039;s username?', '" . $lang . "', 5, 1274841150, 1274841150),
    ('What is the first name of your oldest niece?', '" . $lang . "', 6, 1274841158, 1274841158),
    ('What is the first name of your oldest nephew?', '" . $lang . "', 7, 1274841167, 1274841167),
    ('What is the first name of your favorite aunt?', '" . $lang . "', 8, 1274841175, 1274841175),
    ('whe-re did you spend your honeymoon?', '" . $lang . "', 9, 1274841183, 1274841183)";

    $sql_create_module[] = 'INSERT IGNORE INTO ' . $db_config['prefix'] . '_' . $module_data . "_config (config, content, edit_time) VALUES ('siteterms_" . $lang . "', '<p style=\"text-align:center;\"> <strong>Website usage terms and conditions – sample template</strong></p><p> Welcome to our website. If you continue to browse and use this website you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern [business name]’s relationship with you in relation to this website.<br /> The term ‘[business name]’ or ‘us’ or ‘we’ refers to the owner of the website whose registered office is [address]. Our company registration number is [company registration number and place of registration]. The term ‘you’ refers to the user or viewer of our website.<br /> The use of this website is subject to the following terms of use:<br /> • The content of the pages of this website is for your general information and use only. It is subject to change without notice.<br /> • Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.<br /> • Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.<br /> • This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.<br /> • All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.<br /> • Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.<br /> • fr0m time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).<br /> • You may not crea-te a link to this website fr0m another website or document without [business name]’s prior written consent.<br /> • Your use of this website and any dispute arising out of such use of the website is subject to the laws of England, Scotland and Wales.</p>', 1274757617)";
}

// Cài lại module users thì không thao tác gì tới CSDL
if ($module_data != 'users' or $op != 'recreate_mod') {
    $nv_Lang->loadFile(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang . '.php', true);

    // Build lại lang bảng field
    try {
        $sql = 'SELECT fid, field, language, is_system FROM ' . $db_config['prefix'] . '_' . $module_data . '_field';
        $_result = $db->query($sql);
        while ($_row = $_result->fetch()) {
            $_row['language'] = unserialize($_row['language']);
            if (!isset($_row['language'][$lang])) {
                if (!empty($_row['is_system'])) {
                    $_row['language'][$lang] = [
                        0 => $nv_Lang->getModule($_row['field']),
                        1 => ''
                    ];
                } elseif (isset($_row['language'][$set_lang_data])) {
                    $_row['language'][$lang] = [
                        0 => ucfirst(nv_EncString($_row['language'][$set_lang_data][0])),
                        1 => ucfirst(nv_EncString($_row['language'][$set_lang_data][1]))
                    ];
                } else {
                    $_copy_lang = current($_row['language']);
                    $_row['language'][$lang] = [
                        0 => ucfirst(nv_EncString($_copy_lang[0])),
                        1 => ucfirst(nv_EncString($_copy_lang[1]))
                    ];
                }
                $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize($_row['language'])) . ' WHERE fid=' . $_row['fid'];
            }
        }
    } catch (PDOException $e) {
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('first_name'), 1 => '']])) . " WHERE field='first_name'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('last_name'), 1 => '']])) . " WHERE field='last_name'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('gender'), 1 => '']])) . " WHERE field='gender'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('question'), 1 => '']])) . " WHERE field='question'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('answer'), 1 => '']])) . " WHERE field='answer'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('birthday'), 1 => '']])) . " WHERE field='birthday'";
        $sql_create_module[] = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_field SET language=' . $db->quote(serialize([$lang => [0 => $nv_Lang->getModule('sig'), 1 => '']])) . " WHERE field='sig'";
    }

    $nv_Lang->changeLang();
}
