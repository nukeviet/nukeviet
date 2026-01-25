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

use NukeViet\Module\news\Shared\Emails;
use NukeViet\Template\Email\Emf;

$module_emails[Emails::SENDMAIL] = [
    'pids' => Emf::P_ALL,
    't' => 'Gửi email giới thiệu bài viết cho bạn bè tại module news',
    's' => 'Tin nhắn từ {$from_name}',
    'c' => 'Xin chào!<br />Bạn của bạn là {$from_name} mong muốn giới thiệu với bạn bài viết “{$post_name}” trên website {$site_name}{if not empty($message)} với lời nhắn:<br />{$message}{/if}.<br/>----------<br/><strong>{$post_name}</strong><br/>{$hometext}<br/><br/>Bạn có thể xem đầy đủ bài viết bằng cách click vào link bên dưới:<br /><a href="{$link}" title="{$post_name}">{$link}</a>'
];
$module_emails[Emails::REPORT_THANKS] = [
    'pids' => Emf::P_ALL,
    't' => 'Email cảm ơn người báo lỗi tại module news',
    's' => 'Cảm ơn bạn đã báo lỗi',
    'c' => 'Xin chào!<br />Ban quản trị website {$site_name} cảm ơn bạn đã gửi đến chúng tôi báo cáo lỗi trong nội dung bài viết. Lỗi mà bạn thông báo đã được chúng tôi sửa lại.<br />Hy vọng sẽ nhận được sự giúp đỡ tiếp theo của bạn trong tương lai.<br />Chúc bạn luôn mạnh khỏe, hạnh phúc và thành công!'
];
