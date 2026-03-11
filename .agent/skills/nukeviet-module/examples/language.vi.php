<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Metadata bắt buộc đầu file
$lang_translator['author']     = 'Tên tác giả <email>';
$lang_translator['createdate'] = 'dd/mm/yyyy, HH:MM';
$lang_translator['copyright']  = '@Copyright (C) 2025 ... All rights reserved';
$lang_translator['info']       = '';
$lang_translator['langtype']   = 'lang_module'; // Luôn là 'lang_module'

// Các chuỗi hiển thị giao diện
$lang_module['hello']     = 'Xin chào';
$lang_module['error_msg'] = 'Lỗi: %s'; // Dùng với sprintf()
