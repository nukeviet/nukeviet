<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
$_cas_config = [];
if ($nv_Request->isset_request('save', 'post')) {
    $_cas_config['cas_hostname'] = $nv_Request->get_title('cas_hostname', 'post', '');
    $_cas_config['cas_baseuri'] = $nv_Request->get_title('cas_baseuri', 'post', '');
    $_cas_config['cas_port'] = $nv_Request->get_int('cas_port', 'post', '');
    $_cas_config['cas_version'] = $nv_Request->get_title('cas_version', 'post', '');
    $_cas_config['cas_language'] = $nv_Request->get_title('cas_language', 'post', '');
    $_cas_config['cas_proxy'] = (int) $nv_Request->get_bool('cas_proxy', 'post', '');
    $_cas_config['cas_multiauth'] = (int) $nv_Request->get_bool('cas_multiauth', 'post', '');
    $_cas_config['cas_certificate_check'] = (int) $nv_Request->get_bool('cas_certificate_check', 'post', '');
    $_cas_config['cas_certificate_path'] = $nv_Request->get_title('cas_certificate_path', 'post', '');

    $_cas_config['ldap_host_url'] = $nv_Request->get_title('ldap_host_url', 'post', '');
    $_cas_config['ldap_version'] = $nv_Request->get_int('ldap_version', 'post', '');
    $_cas_config['ldap_start_tls'] = (int) $nv_Request->get_bool('ldap_start_tls', 'post', '');
    $_cas_config['ldap_encoding'] = $nv_Request->get_title('ldap_encoding', 'post', '');
    $_cas_config['ldap_pagesize'] = $nv_Request->get_title('ldap_pagesize', 'post', '');
    $_cas_config['ldap_bind_dn'] = $nv_Request->get_title('ldap_bind_dn', 'post', '');
    $_cas_config['ldap_bind_pw'] = $nv_Request->get_title('ldap_bind_pw', 'post', '');
    $_cas_config['user_type'] = $nv_Request->get_title('user_type', 'post', '');
    $_cas_config['user_contexts'] = $nv_Request->get_title('user_contexts', 'post', '');

    $_cas_config['user_search_sub'] = (int) $nv_Request->get_bool('user_search_sub', 'post', '');
    $_cas_config['user_opt_deref'] = (int) $nv_Request->get_bool('user_opt_deref', 'post', '');
    $_cas_config['user_attribute'] = $nv_Request->get_title('user_attribute', 'post', '');
    $_cas_config['member_attribute'] = $nv_Request->get_title('member_attribute', 'post', '');
    $_cas_config['member_attribute_isdn'] = $nv_Request->get_title('member_attribute_isdn', 'post', '');
    $_cas_config['user_objectclass'] = $nv_Request->get_title('user_objectclass', 'post', '');
    $_cas_config['user_contexts'] = $nv_Request->get_title('user_contexts', 'post', '');

    $_cas_config['config_field'] = $nv_Request->get_array('config_field', 'post', '');
    $_cas_config['config_field_lock'] = $nv_Request->get_array('config_field_lock', 'post', '');
    if ($checkss == $nv_Request->get_string('checkss', 'post')) {
        $config_sso = serialize($_cas_config);

        try {
            if (isset($global_config['config_sso'])) {
                $sth = $db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = 'sys' AND module = 'site' AND config_name = :config_name");
            } else {
                $sth = $db->prepare('INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES ('sys', 'site', :config_name, :config_value)");
            }

            $sth->bindValue(':config_name', 'config_sso', PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_sso, PDO::PARAM_STR);
            $sth->execute();
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }

        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('config'), $page_title, $admin_info['userid']);
        $nv_Cache->delAll();
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&oauth_config=' . $oauth_config . '&rand=' . nv_genpass());
}

if (isset($global_config['config_sso'])) {
    $_cas_config = unserialize($global_config['config_sso']);
} else {
    // Thiết lập các giá trị mặc định.
    $_cas_config = [
        'cas_hostname' => 'cas.openroad.vn',
        'cas_baseuri' => 'cas/',
        'cas_port' => 8443,
        'cas_version' => '2.0',
        'cas_language' => 'CAS_Languages_English',
        'cas_proxy' => 0,
        'cas_multiauth' => 1,
        'cas_certificate_check' => 0,
        'cas_certificate_path' => '',
        'ldap_host_url' => '119.17.253.239',
        'ldap_version' => 3,
        'ldap_start_tls' => 0,
        'ldap_encoding' => 'utf-8',
        'ldap_pagesize' => '',
        'ldap_bind_dn' => '',
        'ldap_bind_pw' => '',
        'user_type' => 'default',
        'user_contexts' => 'ou=people,dc=openroad,dc=vn',
        'user_search_sub' => 0,
        'user_opt_deref' => 0,
        'user_attribute' => '',
        'member_attribute' => '',
        'member_attribute_isdn' => '',
        'user_objectclass' => '',
        'config_field' => [
            'first_name' => 'cn',
            'last_name' => 'sn',
            'email' => 'mail',
            'show_email' => ''
        ],
        'config_field_lock' => [
            'first_name' => 'oncreate',
            'last_name' => 'oncreate',
            'email' => 'oncreate',
            'show_email' => 'oncreate'
        ]
    ];
}

$field_lock = [];
foreach ($_cas_config['config_field_lock'] as $key => $value) {
    $field_lock[$key]['oncreate'] = ($value == 'oncreate') ? 'selected="selected"' : '';
    $field_lock[$key]['onlogin'] = ($value == 'onlogin') ? 'selected="selected"' : '';
}
$_cas_config['checkss'] = $checkss;

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('config_single-sign-on.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);

$tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;oauth_config=' . $oauth_config);
$tpl->assign('DATA', $_cas_config);
$tpl->assign('FIELD_LOCK', $field_lock);

$language = [
    'CAS_Languages_English',
    'CAS_Languages_French',
    'CAS_Languages_Greek',
    'CAS_Languages_German',
    'CAS_Languages_Japanese',
    'CAS_Languages_Spanish',
    'CAS_Languages_Catalan'
];
$tpl->assign('CAS_LANGUAGES', $language);

$usertype = [
    0 => [
        'value' => 'default',
        'name' => $nv_Lang->getModule('default')
    ],
    1 => [
        'value' => 'edir',
        'name' => 'Novell Edirectory'
    ],
    2 => [
        'value' => 'rfc2307',
        'name' => 'posixAccount (rfc2307)'
    ],
    3 => [
        'value' => 'rfc2307bis',
        'name' => 'posixAccount (rfc2307bis)'
    ],
    4 => [
        'value' => 'samba',
        'name' => 'sambaSamAccount (v.3.0.7)'
    ],
    5 => [
        'value' => 'ad',
        'name' => 'MS ActiveDirectory'
    ]
];
$tpl->assign('USERTYPE', $usertype);

$sql = 'SELECT * FROM ' . NV_MOD_TABLE . '_field ORDER BY weight ASC';
$_query = $db->query($sql);

$fields = [];
foreach ($_query as $row) {
    $_language = unserialize($row['language']);
    $_field_lock = (isset($_cas_config['config_field_lock'][$row['field']])) ? $_cas_config['config_field_lock'][$row['field']] : '';
    $fields[] = [
        'field' => $row['field'],
        'lang' => (isset($_language[NV_LANG_DATA])) ? $_language[NV_LANG_DATA][0] : '',
        'value' => (isset($_cas_config['config_field'][$row['field']])) ? $_cas_config['config_field'][$row['field']] : '',
        'oncreate' => ($_field_lock == 'oncreate') ? 'selected="selected"' : '',
        'onlogin' => ($_field_lock == 'onlogin') ? 'selected="selected"' : ''
    ];
}
$tpl->assign('FIELDS', $fields);

$contents = $tpl->fetch('config_single-sign-on.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
