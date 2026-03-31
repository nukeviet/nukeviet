<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$default_config_theme = [
    'css' => '',
    'gfont' => [
        'family' => '',
        'styles' => []
    ],
    'color' => [
        'mode' => '',
        'light' => '',
    ],
    'variables' => [
        'colors' => [
            'primary' => ['type' => 'color', 'rgb' => true],
            'secondary' => ['type' => 'color', 'rgb' => true],
            'success' => ['type' => 'color', 'rgb' => true],
            'info' => ['type' => 'color', 'rgb' => true],
            'warning' => ['type' => 'color', 'rgb' => true],
            'danger' => ['type' => 'color', 'rgb' => true],
            'light' => ['type' => 'color', 'rgb' => true],
            'dark' => ['type' => 'color', 'rgb' => true],
        ],
        'text_emphasis' => [
            'primary' => ['type' => 'color', 'rgb' => false],
            'secondary' => ['type' => 'color', 'rgb' => false],
            'success' => ['type' => 'color', 'rgb' => false],
            'info' => ['type' => 'color', 'rgb' => false],
            'warning' => ['type' => 'color', 'rgb' => false],
            'danger' => ['type' => 'color', 'rgb' => false],
            'light' => ['type' => 'color', 'rgb' => false],
            'dark' => ['type' => 'color', 'rgb' => false],
        ],
        'bg_subtle' => [
            'primary' => ['type' => 'color', 'rgb' => false],
            'secondary' => ['type' => 'color', 'rgb' => false],
            'success' => ['type' => 'color', 'rgb' => false],
            'info' => ['type' => 'color', 'rgb' => false],
            'warning' => ['type' => 'color', 'rgb' => false],
            'danger' => ['type' => 'color', 'rgb' => false],
            'light' => ['type' => 'color', 'rgb' => false],
            'dark' => ['type' => 'color', 'rgb' => false],
        ],
        'border_subtle' => [
            'primary' => ['type' => 'color', 'rgb' => false],
            'secondary' => ['type' => 'color', 'rgb' => false],
            'success' => ['type' => 'color', 'rgb' => false],
            'info' => ['type' => 'color', 'rgb' => false],
            'warning' => ['type' => 'color', 'rgb' => false],
            'danger' => ['type' => 'color', 'rgb' => false],
            'light' => ['type' => 'color', 'rgb' => false],
            'dark' => ['type' => 'color', 'rgb' => false],
        ],
        'body' => [
            'font_family' => ['type' => 'text'],
            'font_size' => ['type' => 'size'],
            'font_weight' => ['type' => 'font_weight'],
            'line_height' => ['type' => 'number'],
            'color' => ['type' => 'color', 'rgb' => true],
            'bg' => ['type' => 'color', 'rgb' => true],
        ],
        'emphasis' => [
            'color' => ['type' => 'color', 'rgb' => true],
        ],
        'secondary' => [
            'color' => ['type' => 'color', 'rgb' => true],
            'bg' => ['type' => 'color', 'rgb' => true],
        ],
        'tertiary' => [
            'color' => ['type' => 'color', 'rgb' => true],
            'bg' => ['type' => 'color', 'rgb' => true],
        ],
        'link' => [
            'color' => ['type' => 'color', 'rgb' => true],
            'hover_color' => ['type' => 'color', 'rgb' => true],
            'decoration' => ['type' => 'text_decoration'],
        ],
        'code' => [
            'color' => ['type' => 'color', 'rgb' => false],
        ],
        'highlight' => [
            'color' => ['type' => 'color', 'rgb' => false],
            'bg' => ['type' => 'color', 'rgb' => false],
        ],
        'border' => [
            'width' => ['type' => 'size'],
            'style' => ['type' => 'border_style'],
            'color' => ['type' => 'color', 'rgb' => false],
            'radius' => ['type' => 'size'],
            'radius_sm' => ['type' => 'size'],
            'radius_lg' => ['type' => 'size'],
            'radius_xl' => ['type' => 'size'],
            'radius_xxl' => ['type' => 'size'],
            'radius_pill' => ['type' => 'size'],
        ],
        'form' => [
            'valid_color' => ['type' => 'color', 'rgb' => false],
            'valid_border_color' => ['type' => 'color', 'rgb' => false],
            'invalid_color' => ['type' => 'color', 'rgb' => false],
            'invalid_border_color' => ['type' => 'color', 'rgb' => false],
        ]
    ],
];
