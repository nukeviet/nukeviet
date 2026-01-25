<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * Chu y:
 * Khi them link vao danh sach, can dam bao cau truc nhu sau:
 * $services[] = array( 'method', 'abc.com', 'link_abc.com' );
 * trong do: gia tri dau tien co the la weblogUpdates.ping hoac weblogUpdates.extendedPing;
 * Gia tri thu 2 la ten hien thi cua link
 * Gia tri thu 3 la link
 */
$services = array();
$services[] = array( 'weblogUpdates.extendedPing', 'Pingomatic', 'http://rpc.pingomatic.com', 'pingomatic.png' );
$services[] = array( 'weblogUpdates.ping', 'Fc2.com', 'http://ping.fc2.com', 'fc2.png' );
$services[] = array( 'weblogUpdates.ping', 'Bloggers.jp', 'http://ping.bloggers.jp/rpc/' );
$services[] = array( 'weblogUpdates.ping', 'Blogpeople 1', 'http://blogpeople.net/servlet/weblogUpdates' );
$services[] = array( 'weblogUpdates.ping', 'Blogpeople 2', 'http://blogpeople.net/ping' );
$services[] = array( 'weblogUpdates.ping', 'Twingly.com', 'http://rpc.twingly.com/' );
