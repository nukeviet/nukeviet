<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// ❌ Sai
// $id = $_GET['id'];

// ✅ Đúng
$id    = $nv_Request->get_int('id', 'get', 0);
$title = $nv_Request->get_title('title', 'post', '');
$title = nv_substr($title, 0, 255);
$body  = $nv_Request->get_editor('body', '', NV_ALLOWED_HTML_TAGS);
$desc  = $nv_Request->get_textarea('desc', '', NV_ALLOWED_HTML_TAGS);
