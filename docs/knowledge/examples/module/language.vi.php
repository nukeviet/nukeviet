<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Metadata bắt buộc đầu file
$lang_translator['author']     = 'Tên tác giả <email>';
$lang_translator['createdate'] = 'dd/mm/yyyy, HH:MM';
$lang_translator['copyright']  = '@Copyright (C) 2026 ... All rights reserved';
$lang_translator['info']       = '';
$lang_translator['langtype']   = 'lang_module'; // Luôn là 'lang_module'

// Các chuỗi hiển thị giao diện
$lang_module['hello']     = 'Xin chào';
$lang_module['error_msg'] = 'Lỗi: %s'; // Dùng với getModule, getGlobal có nhiều hơn 1 tham số
