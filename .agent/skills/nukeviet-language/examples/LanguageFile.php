<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// ── Metadata bắt buộc ──────────────────────────────────────────────────────
$lang_translator['author']     = 'Tên tác giả <email>';
$lang_translator['createdate'] = 'dd/mm/yyyy, HH:MM';
$lang_translator['copyright']  = '@Copyright (C) 2025 ... All rights reserved';
$lang_translator['info']       = '';
$lang_translator['langtype']   = 'lang_module'; // giá trị cố định

// ── Chuỗi giao diện ───────────────────────────────────────────────────────
$lang_module['hello']       = 'Xin chào';
$lang_module['error_msg']   = 'Lỗi: %s';            // dùng với sprintf()
$lang_module['item_count']  = 'Tổng cộng %d mục';   // dùng với sprintf()

// ── Menu admin ─────────────────────────────────────────────────────────────
$lang_module['menu_content'] = 'Quản lý nội dung';
$lang_module['menu_config']  = 'Cấu hình';
