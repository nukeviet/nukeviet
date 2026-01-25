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
    't' => 'Send an email introducing the article to friend at the news module',
    's' => 'Message from {$from_name}',
    'c' => 'Hello!<br />Your friend {$from_name} would like to introduce to you the article “{$post_name}” on website {$site_name}{if not empty($message)} with the message:<br />{$message}{/if}.<br/>----------<br/><strong>{$post_name}</strong><br/>{$hometext}<br/><br/>You can view the full article by clicking on the link below:<br /><a href="{$link}" title="{$post_name}">{$link}</a>'
];
$module_emails[Emails::REPORT_THANKS] = [
    'pids' => Emf::P_ALL,
    't' => 'Email thanking the person who reported the error at module news',
    's' => 'Thank you for submitting an error report',
    'c' => 'Hello!<br />{$site_name} website administration thank you very much for submitting an error report in the content of the article of our website. We fixed the error you reported.<br />Hope to receive your next help in the future. Wish you always healthy, happy and successful!'
];
