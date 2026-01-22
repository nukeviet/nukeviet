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

/**
 * Danh sach modules
 */
$installMods = [];
$installMods['about'] = [
    'module_file' => 'page',
    'module_theme' => 'page',
    'custom_title' => $install_lang['modules']['about'],
    'admin_title' => $install_lang['modules']['about_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'rss' => 1,
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'sitemap' => [],
        'rss' => []
    ],
    'icon' => 'fa-solid fa-campground'
];

$installMods['zalo'] = [
    'custom_title' => 'Zalo',
    'admin_title' => 'Zalo',
    'admin_file' => 1,
    'groups_view' => '3',
    'icon' => 'fa-solid fa-z'
];

$installMods['news'] = [
    'custom_title' => $install_lang['modules']['news'],
    'admin_title' => $install_lang['modules']['news_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'rss' => 1,
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'viewcat' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'topic' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'content' => [
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'detail' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'tag' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'rss' => [
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main-right'
        ],
        'search' => [
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'groups' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'author' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'sitemap' => [],
        'print' => [],
        'rating' => [],
        'savefile' => [],
        'sendmail' => [],
        'instant-rss' => [
            'func_custom_name' => 'Instant Articles RSS'
        ]
    ],
    'icon' => 'fa-solid fa-newspaper'
];

$installMods['users'] = [
    'custom_title' => $install_lang['modules']['users'],
    'admin_title' => $install_lang['modules']['users_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['main'],
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'login' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['login'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'register' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['register'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'lostpass' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['lostpass'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'active' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['active'],
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'lostactivelink' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['lostactivelink'],
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'r2s' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['r2s'],
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'editinfo' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['editinfo'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'memberlist' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['memberlist'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'groups' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['groups'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'avatar' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['avatar'],
            'show_func' => 1,
            'theme_default' => 'left-main'
        ],
        'logout' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['logout'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'oauth' => [],
        'datadeletion' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['datadeletion'],
            'show_func' => 1,
            'in_submenu' => 0,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'security-privacy' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['security-privacy'],
            'show_func' => 1,
            'in_submenu' => 0,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'verify-password' => [
            'func_custom_name' => $install_lang['modfuncs']['users']['verify-password'],
            'show_func' => 1,
            'in_submenu' => 0,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-users'
];

$installMods['myapi'] = [
    'module_file' => 'myapi',
    'module_theme' => 'myapi',
    'custom_title' => $install_lang['modules']['myapi'],
    'admin_title' => $install_lang['modules']['myapi_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-brands fa-nfc-symbol'
];

$installMods['inform'] = [
    'custom_title' => $install_lang['modules']['inform'],
    'admin_title' => $install_lang['modules']['inform_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '4',
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-bell'
];

$installMods['contact'] = [
    'custom_title' => $install_lang['modules']['contact'],
    'admin_title' => $install_lang['modules']['contact_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-phone'
];

$installMods['statistics'] = [
    'custom_title' => $install_lang['modules']['statistics'],
    'admin_title' => $install_lang['modules']['statistics_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'keywords' => 'online, statistics',
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'allreferers' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['allreferers'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'allcountries' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['allcountries'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'allbrowsers' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['allbrowsers'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'allos' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['allos'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'allbots' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['allbots'],
            'show_func' => 1,
            'in_submenu' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'referer' => [
            'func_custom_name' => $install_lang['modfuncs']['statistics']['referer'],
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-chart-simple'
];

$installMods['voting'] = [
    'custom_title' => $install_lang['modules']['voting'],
    'admin_title' => $install_lang['modules']['voting_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'rss' => 1,
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-square-poll-vertical'
];

$installMods['banners'] = [
    'custom_title' => $install_lang['modules']['banners'],
    'admin_title' => $install_lang['modules']['banners_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'addads' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'clientinfo' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'stats' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'cledit' => [],
        'click' => [],
        'clinfo' => [],
        'logininfo' => [],
        'viewmap' => []
    ],
    'icon' => 'fa-solid fa-rectangle-ad'
];

$installMods['seek'] = [
    'custom_title' => $install_lang['modules']['seek'],
    'admin_title' => $install_lang['modules']['seek_for_acp'],
    'main_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'opensearch' => []
    ],
    'icon' => 'fa-solid fa-magnifying-glass'
];

$installMods['menu'] = [
    'custom_title' => $install_lang['modules']['menu'],
    'admin_title' => $install_lang['modules']['menu_for_acp'],
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'icon' => 'fa-solid fa-network-wired'
];

$installMods['feeds'] = [
    'custom_title' => $install_lang['modules']['feeds'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ]
    ],
    'icon' => 'fa-solid fa-rss'
];

$installMods['page'] = [
    'custom_title' => $install_lang['modules']['Page'],
    'admin_title' => $install_lang['modules']['Page_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'rss' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main',
            'theme_mobile' => 'main'
        ],
        'sitemap' => [],
        'rss' => []
    ],
    'icon' => 'fa-solid fa-file-pen'
];

$installMods['comment'] = [
    'custom_title' => $install_lang['modules']['comment'],
    'admin_title' => $install_lang['modules']['comment_for_acp'],
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'post' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'like' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'delete' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'down' => [
            'show_func' => 1
        ]
    ],
    'icon' => 'fa-solid fa-comments'
];

$installMods['siteterms'] = [
    'module_file' => 'page',
    'module_theme' => 'page',
    'custom_title' => $install_lang['modules']['siteterms'],
    'admin_title' => $install_lang['modules']['siteterms_for_acp'],
    'main_file' => 1,
    'admin_file' => 1,
    'groups_view' => '6',
    'rss' => 1,
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'rss' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'sitemap' => []
    ],
    'icon' => 'fa-solid fa-gavel'
];

$installMods['freecontent'] = [
    'custom_title' => $install_lang['modules']['freecontent'],
    'admin_title' => $install_lang['modules']['freecontent_for_acp'],
    'admin_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'icon' => 'fa-solid fa-cube'
];

$installMods['two-step-verification'] = [
    'module_data' => 'two_step_verification',
    'module_upload' => 'two_step_verification',
    'custom_title' => $install_lang['modules']['two_step_verification'],
    'admin_title' => $install_lang['modules']['two_step_verification_for_acp'],
    'main_file' => 1,
    'groups_view' => '6',
    'sitemap' => 1,
    'funcs' => [
        'main' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'confirm' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'setup' => [
            'show_func' => 1,
            'theme_default' => 'left-main-right',
            'theme_mobile' => 'main'
        ],
        'qrimg' => []
    ],
    'icon' => 'fa-solid fa-shield-halved'
];

/**
 * Company
 */
$company = [];
$company['company_name'] = $install_lang['vinades_fullname'];
$company['company_address'] = $install_lang['vinades_address'];
$company['company_sortname'] = 'VINADES.,JSC';
$company['company_regcode'] = '';
$company['company_regplace'] = '';
$company['company_licensenumber'] = '';
$company['company_responsibility'] = '';
$company['company_showmap'] = 1;
$company['company_mapurl'] = 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d427.01613794022035!2d105.78849184070538!3d20.979995366268337!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac93055e2f2f%3A0x91f4b423089193dd!2zQ8O0bmcgdHkgQ-G7lSBwaOG6p24gUGjDoXQgdHJp4buDbiBOZ3Xhu5NuIG3hu58gVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1701239622249!5m2!1svi!2s';
$company['company_phone'] = '+84-24-85872007[+842485872007]';
$company['company_fax'] = '+84-24-35500914';
$company['company_email'] = 'contact@vinades.vn';
$company['company_website'] = 'https://vinades.vn';
$company = serialize($company);

/**
 * Social
 */
$social = [];
$social['facebook'] = 'http://www.facebook.com/nukeviet';
$social['youtube'] = 'https://www.youtube.com/user/nukeviet';
$social['twitter'] = 'https://twitter.com/nukeviet';
$social = serialize($social);

/**
 * Copyright
 */
$copyright = [];
$copyright['copyright_by'] = '';
$copyright['copyright_url'] = '';
$copyright['design_by'] = 'VINADES.,JSC';
$copyright['design_url'] = 'https://vinades.vn/';
$copyright['siteterms_url'] = NV_BASE_SITEURL . 'index.php?language=' . $lang_data . '&amp;nv=siteterms';
$copyright = serialize($copyright);

/**
 * Blocks Groups
 */
$blockGroups = [
    'default' => [ // Theme Default
        'TOP' => [
            [
                'module' => 'news',
                'file_name' => 'module.block_newscenter.php',
                'title' => $install_lang['blocks_groups']['news']['module.block_newscenter'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'config' => 'a:10:{s:6:"numrow";i:6;s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";s:12:"length_title";i:0;s:15:"length_hometext";i:0;s:17:"length_othertitle";i:60;s:5:"width";i:500;s:6:"height";i:0;s:7:"nocatid";a:0:{}}',
                'funcs' => [
                    'news' => [
                        'main'
                    ]
                ]
            ],
            [
                'module' => 'banners',
                'file_name' => 'global.banners.php',
                'title' => $install_lang['blocks_groups']['banners']['global.banners1'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'config' => 'a:1:{s:12:"idplanbanner";i:1;}',
                'funcs' => [
                    'news' => [
                        'main'
                    ]
                ]
            ]
        ],
        'LEFT' => [
            [
                'module' => 'news',
                'file_name' => 'global.block_category.php',
                'title' => $install_lang['blocks_groups']['news']['global.block_category'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'config' => 'a:2:{s:5:"catid";i:0;s:12:"title_length";i:0;}',
                'funcs' => [
                    'news' => [
                        'main',
                        'viewcat',
                        'topic',
                        'content',
                        'detail',
                        'tag',
                        'rss',
                        'search',
                        'groups'
                    ]
                ]
            ],
            [
                'module' => 'theme',
                'file_name' => 'global.module_menu.php',
                'title' => 'Module Menu',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'funcs' => [
                    'users' => [
                        'main',
                        'login',
                        'register',
                        'lostpass',
                        'active',
                        'lostactivelink',
                        'r2s',
                        'editinfo',
                        'memberlist',
                        'groups',
                        'avatar',
                        'logout'
                    ],
                    'statistics' => [
                        'main',
                        'allreferers',
                        'allcountries',
                        'allbrowsers',
                        'allos',
                        'allbots',
                        'referer'
                    ]
                ]
            ],
            [
                'module' => 'banners',
                'file_name' => 'global.banners.php',
                'title' => $install_lang['blocks_groups']['banners']['global.banners2'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:1:{s:12:"idplanbanner";i:2;}'
            ],
            [
                'module' => 'statistics',
                'file_name' => 'global.counter.php',
                'title' => $install_lang['blocks_groups']['statistics']['global.counter'],
                'template' => 'primary',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'RIGHT' => [
            [
                'module' => 'about',
                'file_name' => 'global.about.php',
                'title' => $install_lang['blocks_groups']['about']['global.about'],
                'template' => 'border',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'banners',
                'file_name' => 'global.banners.php',
                'title' => $install_lang['blocks_groups']['banners']['global.banners3'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:1:{s:12:"idplanbanner";i:3;}'
            ],
            [
                'module' => 'voting',
                'file_name' => 'global.voting_random.php',
                'title' => $install_lang['blocks_groups']['voting']['global.voting_random'],
                'template' => 'primary',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'news',
                'file_name' => 'global.block_tophits.php',
                'title' => $install_lang['blocks_groups']['news']['global.block_tophits'],
                'template' => 'primary',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:6:{s:10:"number_day";i:3650;s:6:"numrow";i:10;s:11:"showtooltip";i:1;s:16:"tooltip_position";s:6:"bottom";s:14:"tooltip_length";s:3:"150";s:7:"nocatid";a:2:{i:0;i:10;i:1;i:11;}}'
            ]
        ],
        'FOOTER_SITE' => [
            [
                'module' => 'theme',
                'file_name' => 'global.copyright.php',
                'title' => 'Copyright',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $copyright
            ],
            [
                'module' => 'contact',
                'file_name' => 'global.contact_form.php',
                'title' => 'Feedback',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'QR_CODE' => [
            [
                'module' => 'theme',
                'file_name' => 'global.QR_code.php',
                'title' => 'QR code',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'statistics',
                'file_name' => 'global.counter_button.php',
                'title' => 'Online button',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'PERSONALAREA' => [
            [
                'module' => 'inform',
                'file_name' => 'global.inform.php',
                'title' => $install_lang['blocks_groups']['inform']['global.inform'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'users',
                'file_name' => 'global.user_button.php',
                'title' => $install_lang['blocks_groups']['users']['global.user_button'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'COMPANY_INFO' => [
            [
                'module' => 'theme',
                'file_name' => 'global.company_info.php',
                'title' => $install_lang['blocks_groups']['theme']['global.company_info'],
                'template' => 'simple',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $company
            ]
        ],
        'MENU_SITE' => [
            [
                'module' => 'menu',
                'file_name' => 'global.bootstrap.php',
                'title' => 'Menu Site',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:0;}'
            ]
        ],
        'CONTACT_DEFAULT' => [
            [
                'module' => 'contact',
                'file_name' => 'global.contact_default.php',
                'title' => 'Contact Default',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'SOCIAL_ICONS' => [
            [
                'module' => 'theme',
                'file_name' => 'global.social.php',
                'title' => 'Social icon',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $social
            ]
        ],
        'MENU_FOOTER' => [
            [
                'module' => 'theme',
                'file_name' => 'global.menu_footer.php',
                'title' => $install_lang['blocks_groups']['theme']['global.menu_footer'],
                'template' => 'simple',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:1:{s:14:"module_in_menu";a:8:{i:0;s:5:"about";i:1;s:4:"news";i:2;s:5:"users";i:3;s:7:"contact";i:4;s:6:"voting";i:5;s:7:"banners";i:6;s:4:"seek";i:7;s:5:"feeds";}}'
            ]
        ],
        'FEATURED_PRODUCT' => [
            [
                'module' => 'freecontent',
                'file_name' => 'global.free_content.php',
                'title' => $install_lang['blocks_groups']['freecontent']['global.free_content'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:2:{s:7:"blockid";i:1;s:7:"numrows";i:2;}'
            ]
        ]
    ],
    'mobile_default' => [ // Theme Mobile_Default
        'MENU_SITE' => [
            [
                'module' => 'menu',
                'file_name' => 'global.metismenu.php',
                'title' => 'Menu Site',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:2:{s:6:"menuid";i:1;s:12:"title_length";i:0;}'
            ],
            [
                'module' => 'inform',
                'file_name' => 'global.inform.php',
                'title' => $install_lang['blocks_groups']['inform']['global.inform'],
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'users',
                'file_name' => 'global.user_button.php',
                'title' => 'Sign In',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'SOCIAL_ICONS' => [
            [
                'module' => 'contact',
                'file_name' => 'global.contact_default.php',
                'title' => 'Contact Default',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'contact',
                'file_name' => 'global.contact_form.php',
                'title' => 'Feedback',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ],
            [
                'module' => 'theme',
                'file_name' => 'global.social.php',
                'title' => 'Social icon',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $social
            ],
            [
                'module' => 'theme',
                'file_name' => 'global.QR_code.php',
                'title' => 'QR code',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1
            ]
        ],
        'FOOTER_SITE' => [
            [
                'module' => 'theme',
                'file_name' => 'global.copyright.php',
                'title' => 'Copyright',
                'template' => 'no_title',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $copyright
            ]
        ],
        'MENU_FOOTER' => [
            [
                'module' => 'theme',
                'file_name' => 'global.menu_footer.php',
                'title' => $install_lang['blocks_groups']['theme']['global.menu_footer'],
                'template' => 'primary',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => 'a:1:{s:14:"module_in_menu";a:9:{i:0;s:5:"about";i:1;s:4:"news";i:2;s:5:"users";i:3;s:7:"contact";i:4;s:6:"voting";i:5;s:7:"banners";i:6;s:4:"seek";i:7;s:5:"feeds";i:8;s:9:"siteterms";}}'
            ]
        ],
        'COMPANY_INFO' => [
            [
                'module' => 'theme',
                'file_name' => 'global.company_info.php',
                'title' => $install_lang['blocks_groups']['theme']['global.company_info'],
                'template' => 'primary',
                'active' => '1',
                'bot_visible' => '1',
                'groups_view' => '6',
                'all_func' => 1,
                'config' => $company
            ]
        ]
    ]
];

/*###########################*/
/*
 * Nhap du lieu cho table: nv4_vi_modules
 */
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modules');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modules (
    title, module_file, module_data, module_upload, module_theme, custom_title, admin_title,
    set_time, main_file, admin_file, theme, mobile, description, keywords, groups_view,
    weight, act, admins, rss, sitemap, icon
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$weight = 0;
foreach ($installMods as $mod_name => $vals) {
    ++$weight;
    $_vals = [
        $mod_name,
        $vals['module_file'] ?? $mod_name,
        $vals['module_data'] ?? $mod_name,
        $vals['module_upload'] ?? $mod_name,
        $vals['module_theme'] ?? $mod_name,
        $vals['custom_title'] ?? ucwords($mod_name),
        $vals['admin_title'] ?? '',
        NV_CURRENTTIME,
        !empty($vals['main_file']) ? 1 : 0,
        !empty($vals['admin_file']) ? 1 : 0,
        '',
        '',
        '',
        $vals['keywords'] ?? '',
        $vals['groups_view'],
        $weight,
        1,
        '',
        !empty($vals['rss']) ? 1 : 0,
        !empty($vals['sitemap']) ? 1 : 0,
        $vals['icon'] ?? ''
    ];
    $sth->execute($_vals);
}

/*
 * Nhap du lieu cho table: nv4_vi_modfuncs
 */
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modfuncs (func_id, func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

$func_id = 0;
$array_funcid = [];
$array_funcid_mod = [];
$theme_default = [];
$theme_mobile = [];
foreach ($installMods as $mod_name => $vals) {
    if (isset($vals['funcs'])) {
        $subweight = 0;
        foreach ($vals['funcs'] as $func_name => $func_vals) {
            ++$func_id;
            $array_funcid[] = $func_id;
            !isset($array_funcid_mod[$mod_name]) && $array_funcid_mod[$mod_name] = [];
            $array_funcid_mod[$mod_name][$func_name] = $func_id;
            if (!empty($func_vals['theme_default'])) {
                $theme_default[$func_id] = $func_vals['theme_default'];
            }
            if (!empty($func_vals['theme_mobile'])) {
                $theme_mobile[$func_id] = $func_vals['theme_mobile'];
            }

            $show_func = !empty($func_vals['show_func']) ? 1 : 0;
            if ($show_func) {
                ++$subweight;
            }

            $_vals = [
                $func_id,
                $func_name,
                $func_vals['alias'] ?? $func_name,
                $func_vals['func_custom_name'] ?? ucwords($func_name),
                $mod_name,
                $show_func,
                !empty($func_vals['in_submenu']) ? 1 : 0,
                $show_func ? $subweight : 0,
                ''
            ];
            $sth->execute($_vals);
        }
    }
}

/*
 * Nhap du lieu cho table: nv4_vi_modthemes
 */
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_modthemes (func_id, layout, theme) VALUES (?, ?, ?)');
$sth->execute([0, 'left-main-right', 'default']);
$sth->execute([0, 'main', 'mobile_default']);

if (!empty($theme_default)) {
    foreach ($theme_default as $funcid => $_key) {
        $sth->execute([$funcid, $_key, 'default']);
    }
}

if (!empty($theme_mobile)) {
    foreach ($theme_mobile as $funcid => $_key) {
        $sth->execute([$funcid, $_key, 'mobile_default']);
    }
}

/*
 * Nhap du lieu cho table: nv4_vi_blocks_groups
 */
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_groups (bid, theme, module, file_name, title, link, template, position, dtime_details, active, bot_visible, groups_view, all_func, weight, config) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

$_bid = 0;
$array_weight_block = [];
$blocks_weight = [];
foreach ($blockGroups as $theme => $vals) {
    foreach ($vals as $pos => $bls) {
        $weight = 0;
        foreach ($bls as $bl) {
            if ($bl['module'] == 'theme' or isset($installMods[$bl['module']])) {
                ++$_bid;
                ++$weight;
                $_bl = [
                    $_bid,
                    $theme,
                    $bl['module'],
                    $bl['file_name'],
                    $bl['title'],
                    !empty($bl['link']) ? $bl['link'] : '',
                    $bl['template'],
                    '[' . $pos . ']',
                    '[]',
                    $bl['active'],
                    $bl['bot_visible'] ?? 1,
                    $bl['groups_view'],
                    !empty($bl['all_func']) ? 1 : 0,
                    $weight,
                    !empty($bl['config']) ? $bl['config'] : ''
                ];

                $array_funcid_i = [];
                if (!empty($bl['all_func'])) {
                    $array_funcid_i = $array_funcid;
                } elseif (!empty($bl['funcs'])) {
                    foreach ($bl['funcs'] as $mod => $fns) {
                        foreach ($fns as $fn) {
                            if (isset($array_funcid_mod[$mod][$fn])) {
                                $array_funcid_i[] = $array_funcid_mod[$mod][$fn];
                            }
                        }
                    }
                } elseif (isset($array_funcid_mod[$bl['module']])) {
                    $array_funcid_i = $array_funcid_mod[$bl['module']];
                }

                if (!empty($array_funcid_i)) {
                    foreach ($array_funcid_i as $func_id) {
                        if (isset($array_weight_block[$theme][$pos][$func_id])) {
                            $_weight = $array_weight_block[$theme][$pos][$func_id] + 1;
                        } else {
                            $_weight = 1;
                        }
                        $array_weight_block[$theme][$pos][$func_id] = $_weight;

                        $blocks_weight[] = [$_bid, $func_id, $_weight];
                    }
                }

                $sth->execute($_bl);
            }
        }
    }
}

/*
 * Nhap du lieu cho table: nv4_vi_blocks_weight
 */
$db->query('TRUNCATE TABLE ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight');
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_blocks_weight (bid, func_id, weight) VALUES (?, ?, ?)');
foreach ($blocks_weight as $block_weight) {
    $sth->execute($block_weight);
}

$db->query('UPDATE ' . $db_config['prefix'] . '_config SET config_value = ' . $db->quote($install_lang['nukeviet_description']) . " WHERE module = 'global' AND config_name = 'site_description' AND lang='" . $lang_data . "'");
$db->query('UPDATE ' . $db_config['prefix'] . '_config SET config_value = ' . $db->quote($install_lang['disable_site_content']) . " WHERE module = 'global' AND config_name = 'disable_site_content' AND lang='" . $lang_data . "'");
file_put_contents(NV_ROOTDIR . '/' . NV_DATADIR . '/disable_site_content.' . $lang_data . '.txt', $install_lang['disable_site_content'], LOCK_EX);

$result = $db->query('SELECT id, run_func FROM ' . $db_config['prefix'] . '_cronjobs ORDER BY id ASC');
while ([$id, $run_func] = $result->fetch(3)) {
    $cron_name = (isset($install_lang['cron'][$run_func])) ? $install_lang['cron'][$run_func] : $run_func;
    $db->query('UPDATE ' . $db_config['prefix'] . '_cronjobs SET ' . $lang_data . '_cron_name = ' . $db->quote($cron_name) . ' WHERE id=' . $id);
}

$db->query('UPDATE ' . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_theme'] . "' WHERE lang = '" . $lang_data . "' AND module = 'global' AND config_name = 'site_theme'");

$result = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $lang_data . "_modules where title='" . $global_config['site_home_module'] . "'");
if ($result->fetchColumn()) {
    $db->query('UPDATE ' . $db_config['prefix'] . "_config SET config_value = '" . $global_config['site_home_module'] . "' WHERE module = 'global' AND config_name = 'site_home_module' AND lang='" . $lang_data . "'");
}

if (!empty($menu_rows_lev0)) {
    $menu_rows_lev0 = array_filter($menu_rows_lev0, function ($k) use ($installMods) {
        return isset($installMods[$k]);
    }, ARRAY_FILTER_USE_KEY);
    $menu_rows_lev1 = array_filter($menu_rows_lev1, function ($k) use ($installMods) {
        return isset($installMods[$k]);
    }, ARRAY_FILTER_USE_KEY);

    $result = $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . "_menu (id, title) VALUES (1, 'Top Menu')");

    $is_yes_sub = !empty($menu_rows_lev1) ? array_unique(array_keys($menu_rows_lev1)) : [];
    $menu_y_sub = [];
    if (!empty($is_yes_sub)) {
        foreach ($is_yes_sub as $mys) {
            $menu_y_sub[$mys] = [];
            $menu_y_sub[$mys]['subsize'] = count($menu_rows_lev1[$mys]);
        }
    }

    $sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang_data . '_menu_rows (id, parentid, mid, title, link, weight, sort, lev, subitem, groups_view, module_name, op, target, active_type, status) VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1, 1)');

    $a = 1;
    $b = 1;
    $d = count($menu_rows_lev0);
    $executes = [];
    $subitem = [];
    foreach ($menu_rows_lev0 as $m => $item) {
        $executes[$a] = [$a, 0, $item['title'], $item['link'], $a, $b, 0, '', $item['groups_view'], $m, $item['op']];
        $subitem[$a] = [];
        if (isset($menu_y_sub[$m])) {
            for ($c = 1; $c <= $menu_y_sub[$m]['subsize']; ++$c) {
                ++$b;
                ++$d;
                $e = $c - 1;
                $executes[$d] = [$d, $a, $menu_rows_lev1[$m][$e]['title'], $menu_rows_lev1[$m][$e]['link'], $c, $b, 1, '', $menu_rows_lev1[$m][$e]['groups_view'], $m, $menu_rows_lev1[$m][$e]['op']];
                $subitem[$a][] = $d;
            }
        }
        ++$a;
        ++$b;
    }

    ksort($executes);
    foreach ($executes as $id => $execute) {
        if (!empty($subitem[$id])) {
            $execute[7] = implode(',', $subitem[$id]);
        }
        $sth->execute($execute);
    }
}

// Email templates
if (!empty($module_data) and $module_data == 'language') {
    /*
     * Trường hợp cài đặt ngôn ngữ mới tại module language
     */

    // Cập nhật tên danh mục của hệ thống
    foreach ($install_lang['emailtemplates']['cats'] as $_catid => $_cattitle) {
        try {
            $db->query('UPDATE ' . $db_config['prefix'] . '_emailtemplates_categories SET ' . $lang_data . '_title=' . $db->quote($_cattitle) . ' WHERE catid=' . $_catid);
        } catch (Throwable $e) {
            trigger_error(print_r($e, true));
        }
    }

    // Cập nhật tên, tiêu đề và nội dung các mẫu email của hệ thống
    foreach ($install_lang['emailtemplates']['emails'] as $_tplid => $_tpldata) {
        $db->query('UPDATE ' . $db_config['prefix'] . '_emailtemplates SET ' . $lang_data . '_subject=' . $db->quote($_tpldata['s']) . ', ' . $lang_data . '_content=' . $db->quote($_tpldata['c']) . ' WHERE emailid=' . $_tplid);
        try {
            $db->query('UPDATE ' . $db_config['prefix'] . '_emailtemplates SET ' . $lang_data . '_title=' . $db->quote($_tpldata['t']) . ' WHERE emailid=' . $_tplid);
        } catch (Throwable $e) {
            trigger_error(print_r($e, true));
        }
    }
} else {
    /*
     * Trường hợp cài site lần đầu
     */

    // Thêm mới danh mục
    $sql_emailtpl = [];
    $weight = 0;
    foreach ($install_lang['emailtemplates']['cats'] as $_catid => $_cattitle) {
        ++$weight;
        $sql_emailtpl[] = '(' . $_catid . ', ' . NV_CURRENTTIME . ', ' . $weight . ', 1, ' . $db->quote($_cattitle) . ')';
    }
    if (!empty($sql_emailtpl)) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_emailtemplates_categories (
            catid, time_add, weight, is_system, ' . $lang_data . '_title
        ) VALUES ' . implode(', ', $sql_emailtpl));
    }

    /*
     * Các mẫu email
     */
    $sql_emailtpl = [];
    foreach ($install_lang['emailtemplates']['emails'] as $_tplid => $_tpldata) {
        $sql_emailtpl[] = '(
            ' . $_tplid . ", " . $_tplid . ", '" . $_tpldata['pids'] . "', " . $_tpldata['catid'] . ', ' . NV_CURRENTTIME . ", '', '', '', 1,
            " . $db->quote($_tpldata['s']) . ', ' . $db->quote($_tpldata['c']) . ', ' . $db->quote($_tpldata['t']) . ", '', ''
        )";
    }
    if (!empty($sql_emailtpl)) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_emailtemplates (
            emailid, id, sys_pids, catid, time_add, send_cc, send_bcc, attachments, is_system, default_subject, default_content,
            ' . $lang_data . '_title, ' . $lang_data . '_subject, ' . $lang_data . '_content
        ) VALUES ' . implode(', ', $sql_emailtpl));
    }
}
