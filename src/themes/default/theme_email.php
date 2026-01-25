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

use NukeViet\Core\Language;

/*
 * Các biến sẵn có ngoài biến hệ thống:
 * - $mail_tpl full_path của tệp tpl
 * - $gconfigs tương đương $global_config của ngôn ngữ dùng để gửi email
 * - $subject tiêu đề email
 * - $body nội dung email
 *
 * Tệp này không bắt buộc trong giao diện, nếu bạn không phát triển thì hệ thống nạp từ themes/default/theme_email.php
 */
$xtpl = new XTemplate($mail_tpl);
$xtpl->assign('SITE_URL', NV_MY_DOMAIN);
$xtpl->assign('GCONFIG', $gconfigs);
$xtpl->assign('LANG', Language::$tmplang_global ?: Language::$lang_global);
$xtpl->assign('MESSAGE_TITLE', $subject);
$xtpl->assign('MESSAGE_CONTENT', $body);

if (!empty($gconfigs['phonenumber'])) {
    $xtpl->parse('main.phonenumber');
}

$xtpl->parse('main');

return $xtpl->text('main');
