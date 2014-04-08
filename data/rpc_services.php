<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12-02-2013 14:43
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * Chu y:
 * Khi them link vao danh sach, can dam bao cau truc nhu sau:
 * $services[] = array( 'method', 'abc.com', 'link_abc.com' );
 * trong do: gia tri dau tien co the la weblogUpdates.ping hoac weblogUpdates.extendedPing;
 * Gia tri thu 2 la ten hien thi cua link
 * Gia tri thu 3 la link
 */
$services = array();
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com', 'http://blogsearch.google.com/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.vn', 'http://blogsearch.google.com.vn/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.de', 'http://blogsearch.google.de/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.es', 'http://blogsearch.google.es/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.fi', 'http://blogsearch.google.fi/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.fr', 'http://blogsearch.google.fr/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.gr', 'http://blogsearch.google.gr/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.hr', 'http://blogsearch.google.hr/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ie', 'http://blogsearch.google.ie/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.it', 'http://blogsearch.google.it/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.jp', 'http://blogsearch.google.jp/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.lt', 'http://blogsearch.google.lt/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.nl', 'http://blogsearch.google.nl/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.pl', 'http://blogsearch.google.pl/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.pt', 'http://blogsearch.google.pt/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ro', 'http://blogsearch.google.ro/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ru', 'http://blogsearch.google.ru/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.se', 'http://blogsearch.google.se/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.sk', 'http://blogsearch.google.sk/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.us', 'http://blogsearch.google.us/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ae', 'http://blogsearch.google.ae/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.at', 'http://blogsearch.google.at/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.bg', 'http://blogsearch.google.bg/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ch', 'http://blogsearch.google.ch/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.be', 'http://blogsearch.google.be/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.ca', 'http://blogsearch.google.ca/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.cl', 'http://blogsearch.google.cl/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.cr', 'http://blogsearch.google.co.cr/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.hu', 'http://blogsearch.google.co.hu/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.id', 'http://blogsearch.google.co.id/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.il', 'http://blogsearch.google.co.il/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.in', 'http://blogsearch.google.co.in/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.jp', 'http://blogsearch.google.co.jp/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.ma', 'http://blogsearch.google.co.ma/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.nz', 'http://blogsearch.google.co.nz/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.th', 'http://blogsearch.google.co.th/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.uk', 'http://blogsearch.google.co.uk/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.ve', 'http://blogsearch.google.co.ve/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.co.za', 'http://blogsearch.google.co.za/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.au', 'http://blogsearch.google.com.au/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.co', 'http://blogsearch.google.com.co/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.ar', 'http://blogsearch.google.com.ar/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.br', 'http://blogsearch.google.com.br/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.do', 'http://blogsearch.google.com.do/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.mx', 'http://blogsearch.google.com.mx/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.my', 'http://blogsearch.google.com.my/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.pe', 'http://blogsearch.google.com.pe/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.sa', 'http://blogsearch.google.com.sa/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.sg', 'http://blogsearch.google.com.sg/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.tr', 'http://blogsearch.google.com.tr/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.tw', 'http://blogsearch.google.com.tw/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.ua', 'http://blogsearch.google.com.ua/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Google.com.uy', 'http://blogsearch.google.com.uy/ping/RPC2', 'google.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Pingomatic', 'http://rpc.pingomatic.com', 'pingomatic.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Weblogs.com 1', 'http://rpc.weblogs.com/RPC2', 'weblogs.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Weblogs.com 2', 'http://audiorpc.weblogs.com/RPC2', 'weblogs.png' );
$services[] = array( 'weblogUpdates.extendedPing', 'Blo.gs', 'http://ping.blo.gs/' );
$services[] = array( 'weblogUpdates.ping', 'Pubsub.com', 'http://xping.pubsub.com/ping/', 'pubsub.png' );
$services[] = array( 'weblogUpdates.ping', 'Yandex.ru', 'http://ping.blogs.yandex.ru/RPC2', 'yandex.png' );
$services[] = array( 'weblogUpdates.ping', 'Feedsky.com', 'http://www.feedsky.com/api/RPC2', 'feedsky.png' );
$services[] = array( 'weblogUpdates.ping', 'Fc2.com', 'http://ping.fc2.com', 'fc2.png' );
$services[] = array( 'weblogUpdates.ping', 'Bloggers.jp', 'http://ping.bloggers.jp/rpc/' );
$services[] = array( 'weblogUpdates.ping', 'Myblog.jp', 'http://ping.myblog.jp' );
$services[] = array( 'weblogUpdates.ping', 'Blogpeople 1', 'http://blogpeople.net/servlet/weblogUpdates' );
$services[] = array( 'weblogUpdates.ping', 'Blogpeople 2', 'http://blogpeople.net/ping' );
$services[] = array( 'weblogUpdates.ping', 'Twingly.com', 'http://rpc.twingly.com/' );
$services[] = array( 'weblogUpdates.ping', 'Bloggnytt.se', 'http://ping.bloggnytt.se' );
$services[] = array( 'weblogUpdates.ping', 'Wordblog.de', 'http://ping.wordblog.de' );
$services[] = array( 'weblogUpdates.ping', 'Aitellu.com', 'http://rpc.aitellu.com' );
$services[] = array( 'weblogUpdates.ping', 'Livedoor.com', 'http://rpc.reader.livedoor.com/ping' );
$services[] = array( 'weblogUpdates.ping', 'Newsgator.com', 'http://services.newsgator.com/ngws/xmlrpcping.aspx' );
$services[] = array( 'weblogUpdates.ping', 'Feedblitz.com', 'http://www.feedblitz.com/f/f.fbz?XmlPing' );
$services[] = array( 'weblogUpdates.ping', 'Bloglines.com', 'http://www.bloglines.com/ping' );