<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $nv_Lang->getGlobal('mod_siteinfo')
];

//Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo';
$array_url_instruction['system_info'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cấu_hinh_site';
$array_url_instruction['php_info_configuration'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cấu_hinh_php';
$array_url_instruction['php_info_modules'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#tiện_ich_mở_rộng';
$array_url_instruction['php_info_environment'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cac_biến_moi_truờng';
$array_url_instruction['php_info_variables'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#cac_biến_tiền_dịnh';
$array_url_instruction['logs'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:siteinfo#nhật_ky_hệ_thống';

define('NV_IS_FILE_SITEINFO', true);

/**
 * nv_siteinfo_getlang()
 */
function nv_siteinfo_getlang()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT DISTINCT lang FROM ' . $db_config['prefix'] . '_logs';
    $result = $nv_Cache->db($sql, 'lang', 'siteinfo');
    $array_lang = [];

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_lang[] = $row['lang'];
        }
    }

    return $array_lang;
}

/**
 * nv_siteinfo_getuser()
 */
function nv_siteinfo_getuser()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT userid, username FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid IN ( SELECT DISTINCT userid FROM ' . $db_config['prefix'] . '_logs WHERE userid!=0 ) ORDER BY username ASC';
    $result = $nv_Cache->db($sql, 'userid', 'siteinfo');
    $array_user = [];

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_user[] = [
                'userid' => $row['userid'],
                'username' => $row['username']
            ];
        }
    }

    return $array_user;
}

/**
 * nv_siteinfo_getmodules()
 */
function nv_siteinfo_getmodules()
{
    global $db_config, $nv_Cache;
    $sql = 'SELECT DISTINCT module_name FROM ' . $db_config['prefix'] . '_logs';
    $result = $nv_Cache->db($sql, 'module_name', 'siteinfo');
    $array_modules = [];

    if (!empty($result)) {
        foreach ($result as $row) {
            $array_modules[] = $row['module_name'];
        }
    }

    return $array_modules;
}

/**
 * @return mixed
 */
function get_theme_config()
{
    global $db, $admin_info;

    $sql = "SELECT config_name, config_value FROM " . NV_AUTHORS_GLOBALTABLE . "_vars WHERE admin_id=" . $admin_info['admin_id'] . "
    AND theme=" . $db->quote($admin_info['admin_theme']) . " AND (lang='all' OR lang=" . $db->quote(NV_LANG_DATA) . ")";
    $theme_config = $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);

    if (!isset($theme_config['grid_widgets'])) {
        if (defined('NV_IS_SPADMIN')) {
            $theme_config['grid_widgets'] = [
                // Item số 1
                0 => [
                    'widget_id' => '',
                    'sizes' => [
                        'xs' => 12, // <576px
                        'sm' => 12, // ≥576px
                        'md' => 12, // ≥768px
                        'lg' => 12, // ≥992px
                        'xl' => 6, // ≥1200px
                        'xxl' => 6 // ≥1400px
                    ],
                    'subs' => [
                        0 => [
                            'widget_id' => 'usr_news_arttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        1 => [
                            'widget_id' => 'usr_users_usrtotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        2 => [
                            'widget_id' => 'usr_contact_cmttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        3 => [
                            'widget_id' => 'usr_comment_cmttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ]
                    ]
                ],
                // Item số 2
                1 => [
                    'widget_id' => 'adm_siteinfo_statistics',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 6,
                        'xl' => 3,
                        'xxl' => 3
                    ]
                ],
                // Item số 3
                2 => [
                    'widget_id' => 'adm_siteinfo_pendings',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 6,
                        'xl' => 3,
                        'xxl' => 3
                    ]
                ],
                // Item số 4
                3 => [
                    'widget_id' => 'usr_statistics_hour',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 8,
                        'xl' => 8,
                        'xxl' => 8
                    ]
                ],
                // Item số 5
                4 => [
                    'widget_id' => 'adm_siteinfo_version',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 4,
                        'xl' => 4,
                        'xxl' => 4
                    ]
                ]
            ];
            if (!defined('NV_IS_GODADMIN')) {
                $theme_config['grid_widgets'][3]['sizes'] = [
                    'xs' => 12,
                    'sm' => 12,
                    'md' => 12,
                    'lg' => 12,
                    'xl' => 12,
                    'xxl' => 12
                ];
            }
        } else {
            $theme_config['grid_widgets'] = [
                // Item số 1
                0 => [
                    'widget_id' => 'adm_siteinfo_statistics',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 6,
                        'xl' => 3,
                        'xxl' => 3
                    ]
                ],
                // Item số 2
                1 => [
                    'widget_id' => 'adm_siteinfo_pendings',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 6,
                        'xl' => 3,
                        'xxl' => 3
                    ]
                ],
                // Item số 3
                2 => [
                    'widget_id' => '',
                    'sizes' => [
                        'xs' => 12, // <576px
                        'sm' => 12, // ≥576px
                        'md' => 12, // ≥768px
                        'lg' => 12, // ≥992px
                        'xl' => 6, // ≥1200px
                        'xxl' => 6 // ≥1400px
                    ],
                    'subs' => [
                        0 => [
                            'widget_id' => 'usr_news_arttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        1 => [
                            'widget_id' => 'usr_users_usrtotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        2 => [
                            'widget_id' => 'usr_contact_cmttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ],
                        3 => [
                            'widget_id' => 'usr_comment_cmttotal',
                            'sizes' => [
                                'xs' => 12,
                                'sm' => 6,
                                'md' => 6,
                                'lg' => 6,
                                'xl' => 6,
                                'xxl' => 6
                            ]
                        ]
                    ]
                ],
                // Item số 4
                3 => [
                    'widget_id' => 'usr_statistics_hour',
                    'sizes' => [
                        'xs' => 12,
                        'sm' => 12,
                        'md' => 12,
                        'lg' => 12,
                        'xl' => 12,
                        'xxl' => 12
                    ]
                ]
            ];
        }
    } else {
        $theme_config['grid_widgets'] = empty($theme_config['grid_widgets']) ? [] : json_decode($theme_config['grid_widgets'], true);
        if (!is_array($theme_config['grid_widgets'])) {
            $theme_config['grid_widgets'] = [];
        }
    }

    if (!isset($theme_config['widgets'])) {
        // Các widget mặc định
        $theme_config['widgets'] = [
            'usr_news_arttotal',
            'usr_users_usrtotal',
            'usr_contact_cmttotal',
            'usr_comment_cmttotal',
            'usr_statistics_hour',
            'adm_siteinfo_version',
            'adm_siteinfo_statistics',
            'adm_siteinfo_pendings',
        ];
    } else {
        $theme_config['widgets'] = empty($theme_config['widgets']) ? [] : json_decode($theme_config['widgets'], true);
        if (!is_array($theme_config['widgets'])) {
            $theme_config['widgets'] = [];
        }
    }

    return $theme_config;
}

/**
 * @param string|array $config_name
 * @param string|array $config_value
 * @param bool $lang
 * @return number|boolean
 */
function save_theme_config($config_name, $config_value, bool $lang = true)
{
    global $db, $admin_info;

    if (!is_array($config_name)) {
        $config_name = [$config_name];
        $config_value = [$config_value];
    }

    foreach ($config_name as $key => $config_name_i) {
        $config_value_i = $config_value[$key];
        if (is_array($config_value_i)) {
            $config_value_i = json_encode($config_value_i, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        $sql = "SELECT * FROM " . NV_AUTHORS_GLOBALTABLE . "_vars WHERE admin_id=" . $admin_info['admin_id'] . "
        AND theme=" . $db->quote($admin_info['admin_theme']) . " AND config_name=" . $db->quote($config_name_i);
        if ($lang) {
            $sql .= " AND lang=" . $db->quote(NV_LANG_DATA);
        }
        $row = $db->query($sql)->fetch();

        if (empty($row)) {
            $sql = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_vars (
                admin_id" . ($lang ? ', lang' : '') . ", theme, config_name, config_value
            ) VALUES (
                " . $admin_info['admin_id']. ($lang ? (', ' . $db->quote(NV_LANG_DATA)) : '') . ", " . $db->quote($admin_info['admin_theme']) . ",
                " . $db->quote($config_name_i) . ", " . $db->quote($config_value_i) . "
            )";
        } else {
            $sql = "UPDATE " . NV_AUTHORS_GLOBALTABLE . "_vars SET config_value=" . $db->quote($config_value_i) . " WHERE id=" . $row['id'];
        }
        $db->query($sql);
    }
}

/**
 * @param array $grid_widgets
 * @return string[]
 */
function get_list_widgets(array $grid_widgets)
{
    $widgets = [];
    foreach ($grid_widgets as $widget) {
        if (!empty($widget['widget_id'])) {
            $widgets[] = $widget['widget_id'];
        }
        if (!empty($widget['subs'])) {
            foreach ($widget['subs'] as $sub) {
                if (!empty($sub['widget_id'])) {
                    $widgets[] = $sub['widget_id'];
                }
            }
        }
    }
    return array_unique($widgets);
}
