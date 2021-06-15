<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'News', // Tieu de module
    'modfuncs' => 'main,viewcat,topic,groups,author,detail,search,content,tag,rss', // Cac function co block
    'change_alias' => 'topic,groups,content,rss',
    'submenu' => 'content,rss,search',
    'is_sysmod' => 0, // 1:0 => Co phai la module he thong hay khong
    'virtual' => 1, // 1:0 => Co cho phep ao hao module hay khong
    'version' => '4.5.00', // Phien ban cua modle
    'date' => 'Tuesday, June 22, 2021 16:00:00 GMT+07:00', // Ngay phat hanh phien ban
    'author' => 'VINADES <contact@vinades.vn>', // Tac gia
    'note' => '', // Ghi chu
    'uploads_dir' => [
        $module_upload,
        $module_upload . '/source',
        $module_upload . '/temp_pic',
        $module_upload . '/topics'
    ],
    'files_dir' => [
        $module_upload . '/topics'
    ]
];
