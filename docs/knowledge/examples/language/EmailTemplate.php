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

use NukeViet\Template\Email\Emf;

// Định nghĩa email template trong file language/vi/email_vi.php
// Key = tên hằng (tự đặt, ví dụ 'welcome'), value = cấu hình email
$module_emails['welcome'] = [
    'pids' => Emf::P_ALL,               // loại người nhận
    't'    => 'Mô tả loại email này',
    's'    => 'Tiêu đề: {$ten_bien}',   // cú pháp Smarty
    'c'    => 'Nội dung HTML: {$site_name} chào mừng {$username}'
];
