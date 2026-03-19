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

use NukeViet\Module\TenModule\Shared\Emails;
use NukeViet\Template\Email\Emf;

$module_emails[Emails::WELCOME] = [
    'pids' => Emf::P_ALL,
    't'    => 'Mô tả loại email này',
    's'    => 'Tiêu đề email: {$ten_bien}',
    'c'    => 'Nội dung HTML email, dùng cú pháp Smarty: {$site_name} ...'
];
