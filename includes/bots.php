<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-28-2010 15:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$bots = array(
	'Alexa' => array(
		'agent' => 'ia_archiver',
		'ips' => '',
		'allowed' => 1
	),
	'AltaVista Scooter' => array(
		'agent' => 'Scooter/',
		'ips' => '',
		'allowed' => 1
	),
	'Altavista Mercator' => array(
		'agent' => 'Mercator',
		'ips' => '',
		'allowed' => 1
	),
	'Altavista Search' => array(
		'agent' => 'Trek17',
		'ips' => '',
		'allowed' => 1
	),
	'Aport.ru Bot' => array(
		'agent' => 'aport',
		'ips' => '',
		'allowed' => 1
	),
	'Ask Jeeves' => array(
		'agent' => 'Ask Jeeves',
		'ips' => '',
		'allowed' => 1
	),
	'Baidu' => array(
		'agent' => 'Baiduspider+(',
		'ips' => '',
		'allowed' => 1
	),
	'Exabot' => array(
		'agent' => 'Exabot',
		'ips' => '',
		'allowed' => 1
	),
	'FAST Enterprise' => array(
		'agent' => 'FAST Enterprise Crawler',
		'ips' => '',
		'allowed' => 1
	),
	'FAST WebCrawler' => array(
		'agent' => 'FAST-WebCrawler',
		'ips' => '',
		'allowed' => 1
	),
	'Francis' => array(
		'agent' => 'http://www.neomo.de/',
		'ips' => '',
		'allowed' => 1
	),
	'Gigablast' => array(
		'agent' => 'Gigabot/',
		'ips' => '',
		'allowed' => 1
	),
	'Google AdsBot' => array(
		'agent' => 'AdsBot-Google',
		'ips' => '',
		'allowed' => 1
	),
	'Google Adsense' => array(
		'agent' => 'Mediapartners-Google',
		'ips' => '',
		'allowed' => 1
	),
	'Google Bot' => array(
		'agent' => 'Googlebot',
		'ips' => '',
		'allowed' => 1
	),
	'Google Desktop' => array(
		'agent' => 'Google Desktop',
		'ips' => '',
		'allowed' => 1
	),
	'Google Feedfetcher' => array(
		'agent' => 'Feedfetcher-Google',
		'ips' => '',
		'allowed' => 1
	),
	'Heise IT-Markt' => array(
		'agent' => 'heise-IT-Markt-Crawler',
		'ips' => '',
		'allowed' => 1
	),
	'Heritrix' => array(
		'agent' => 'heritrix/1.',
		'ips' => '',
		'allowed' => 1
	),
	'IBM Research' => array(
		'agent' => 'ibm.com/cs/crawler',
		'ips' => '',
		'allowed' => 1
	),
	'ICCrawler - ICjobs' => array(
		'agent' => 'ICCrawler - ICjobs',
		'ips' => '',
		'allowed' => 1
	),
	'Ichiro' => array(
		'agent' => 'ichiro/2',
		'ips' => '',
		'allowed' => 1
	),
	'InfoSeek Spider' => array(
		'agent' => 'UltraSeek',
		'ips' => '',
		'allowed' => 1
	),
	'Lycos.com Bot' => array(
		'agent' => 'lycos',
		'ips' => '',
		'allowed' => 1
	),
	'MSN Bot' => array(
		'agent' => 'msnbot/',
		'ips' => '',
		'allowed' => 1
	),
	'MSN Bot Media' => array(
		'agent' => 'msnbot-media/',
		'ips' => '',
		'allowed' => 1
	),
	'MSN Bot News' => array(
		'agent' => 'msnbot-news',
		'ips' => '',
		'allowed' => 1
	),
	'MSN NewsBlogs' => array(
		'agent' => 'msnbot-NewsBlogs/',
		'ips' => '',
		'allowed' => 1
	),
	'Majestic-12' => array(
		'agent' => 'MJ12bot/',
		'ips' => '',
		'allowed' => 1
	),
	'Metager' => array(
		'agent' => 'MetagerBot/',
		'ips' => '',
		'allowed' => 1
	),
	'NG-Search' => array(
		'agent' => 'NG-Search/',
		'ips' => '',
		'allowed' => 1
	),
	'Nutch Bot' => array(
		'agent' => 'http://lucene.apache.org/nutch/',
		'ips' => '',
		'allowed' => 1
	),
	'NutchCVS' => array(
		'agent' => 'NutchCVS/',
		'ips' => '',
		'allowed' => 1
	),
	'OmniExplorer' => array(
		'agent' => 'OmniExplorer_Bot/',
		'ips' => '',
		'allowed' => 1
	),
	'Online Link Validator' => array(
		'agent' => 'online link validator',
		'ips' => '',
		'allowed' => 1
	),
	'Open-source Web Search' => array(
		'agent' => 'Nutch',
		'ips' => '',
		'allowed' => 1
	),
	'Psbot' => array(
		'agent' => 'psbot/0',
		'ips' => '',
		'allowed' => 1
	),
	'Rambler' => array(
		'agent' => 'StackRambler/',
		'ips' => '',
		'allowed' => 1
	),
	'SEO Crawler' => array(
		'agent' => 'SEO search Crawler/',
		'ips' => '',
		'allowed' => 1
	),
	'SEOSearch' => array(
		'agent' => 'SEOsearch/',
		'ips' => '',
		'allowed' => 1
	),
	'Seekport' => array(
		'agent' => 'Seekbot/',
		'ips' => '',
		'allowed' => 1
	),
	'Sensis' => array(
		'agent' => 'Sensis Web Crawler',
		'ips' => '',
		'allowed' => 1
	),
	'Seoma' => array(
		'agent' => 'Seoma [SEO Crawler]',
		'ips' => '',
		'allowed' => 1
	),
	'Snappy' => array(
		'agent' => 'Snappy/1.1 ( http://www.urltrends.com/ )',
		'ips' => '',
		'allowed' => 1
	),
	'Steeler' => array(
		'agent' => 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/',
		'ips' => '',
		'allowed' => 1
	),
	'Synoo' => array(
		'agent' => 'SynooBot/',
		'ips' => '',
		'allowed' => 1
	),
	'Telekom' => array(
		'agent' => 'crawleradmin.t-info@telekom.de',
		'ips' => '',
		'allowed' => 1
	),
	'TurnitinBot' => array(
		'agent' => 'TurnitinBot/',
		'ips' => '',
		'allowed' => 1
	),
	'Vietnamese Search' => array(
		'agent' => 'JCrawler',
		'ips' => '',
		'allowed' => 1
	),
	'Voyager' => array(
		'agent' => 'voyager/1.0',
		'ips' => '',
		'allowed' => 1
	),
	'W3 Sitesearch' => array(
		'agent' => 'W3 SiteSearch Crawler',
		'ips' => '',
		'allowed' => 1
	),
	'W3C Linkcheck' => array(
		'agent' => 'W3C-checklink/',
		'ips' => '',
		'allowed' => 1
	),
	'W3C Validator' => array(
		'agent' => 'W3C_*Validator',
		'ips' => '',
		'allowed' => 1
	),
	'WiseNut' => array(
		'agent' => 'http://www.WISEnutbot.com',
		'ips' => '',
		'allowed' => 1
	),
	'YaCy' => array(
		'agent' => 'yacybot',
		'ips' => '',
		'allowed' => 1
	),
	'Yahoo Bot' => array(
		'agent' => 'Yahoo! Slurp',
		'ips' => '',
		'allowed' => 1
	),
	'Yahoo MMCrawler' => array(
		'agent' => 'Yahoo-MMCrawler/',
		'ips' => '',
		'allowed' => 1
	),
	'Yahoo Slurp' => array(
		'agent' => 'Yahoo! DE Slurp',
		'ips' => '',
		'allowed' => 1
	),
	'YahooSeeker' => array(
		'agent' => 'YahooSeeker/',
		'ips' => '',
		'allowed' => 1
	),
	'Yandex' => array(
		'agent' => 'Yandex/',
		'ips' => '',
		'allowed' => 1
	),
	'Yandex Blog' => array(
		'agent' => 'YandexBlog/',
		'ips' => '',
		'allowed' => 1
	),
	'Yandex Direct Bot' => array(
		'agent' => 'YaDirectBot/',
		'ips' => '',
		'allowed' => 1
	),
	'Yandex Something' => array(
		'agent' => 'YandexSomething/',
		'ips' => '',
		'allowed' => 1
	)
);