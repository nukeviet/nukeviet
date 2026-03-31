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

/*
 * Các biến sẵn có ngoài biến hệ thống:
 * - $mail_tpl full_path của tệp tpl
 * - $gconfigs tương đương $global_config của ngôn ngữ dùng để gửi email
 * - $subject tiêu đề email
 * - $body nội dung email
 * - $nv_Lang
 *
 * Tệp này không bắt buộc trong giao diện, nếu bạn không phát triển thì hệ thống nạp từ themes/default/theme_email.php
 */
$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(dirname($mail_tpl));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('TEMPLATE', $template_tpl);

$tpl->assign('GCONFIG', $gconfigs);
$tpl->assign('MESSAGE_TITLE', $subject);
$tpl->assign('MESSAGE_CONTENT', $body);

return $tpl->fetch(basename($mail_tpl));
