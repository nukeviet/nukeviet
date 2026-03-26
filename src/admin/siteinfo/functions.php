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

    $stmt = $db->prepare("SELECT config_name, config_value FROM " . NV_AUTHORS_GLOBALTABLE . "_vars WHERE admin_id = :admin_id AND theme = :theme AND (lang = 'all' OR lang = :lang)");
    $stmt->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
    $stmt->bindValue(':theme', $admin_info['admin_theme'], PDO::PARAM_STR);
    $stmt->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
    $stmt->execute();
    $theme_config = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

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

    $sql_select = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_vars WHERE admin_id = :admin_id AND theme = :theme AND config_name = :config_name';
    if ($lang) {
        $sql_select .= ' AND lang = :lang';
    }
    $stmt_select = $db->prepare($sql_select);

    $sql_insert = 'INSERT INTO ' . NV_AUTHORS_GLOBALTABLE . '_vars (admin_id' . ($lang ? ', lang' : '') . ', theme, config_name, config_value) VALUES (:admin_id' . ($lang ? ', :lang' : '') . ', :theme, :config_name, :config_value)';
    $stmt_insert = $db->prepare($sql_insert);

    $stmt_update = $db->prepare('UPDATE ' . NV_AUTHORS_GLOBALTABLE . '_vars SET config_value = :config_value WHERE id = :id');

    foreach ($config_name as $key => $config_name_i) {
        $config_value_i = $config_value[$key];
        if (is_array($config_value_i)) {
            $config_value_i = json_encode($config_value_i, NV_JSON_ENCODE);
        }

        $stmt_select->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
        $stmt_select->bindValue(':theme', $admin_info['admin_theme'], PDO::PARAM_STR);
        $stmt_select->bindValue(':config_name', $config_name_i, PDO::PARAM_STR);
        if ($lang) {
            $stmt_select->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
        }
        $stmt_select->execute();
        $row = $stmt_select->fetch();
        $stmt_select->closeCursor();

        if (empty($row)) {
            $stmt_insert->bindValue(':admin_id', $admin_info['admin_id'], PDO::PARAM_INT);
            if ($lang) {
                $stmt_insert->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
            }
            $stmt_insert->bindValue(':theme', $admin_info['admin_theme'], PDO::PARAM_STR);
            $stmt_insert->bindValue(':config_name', $config_name_i, PDO::PARAM_STR);
            $stmt_insert->bindValue(':config_value', $config_value_i, PDO::PARAM_STR);
            $stmt_insert->execute();
        } else {
            $stmt_update->bindValue(':config_value', $config_value_i, PDO::PARAM_STR);
            $stmt_update->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $stmt_update->execute();
        }
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
