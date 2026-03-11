<?php

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
