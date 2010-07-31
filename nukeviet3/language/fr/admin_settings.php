<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Jul 31, 2010, 01:13:03 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="Phạm Chí Quang";
$lang_translator['createdate'] ="21/6/2010, 19:30";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.";
$lang_translator['info'] ="Language translated by http://translate.google.com";
$lang_translator['langtype'] ="lang_module";

$lang_module['global_config'] = "Config. générale";
/*
	 vietnam:	  Cấu hình chung
	 english:	  Systen Config
*/

$lang_module['lang_site_config'] = "Config. selon langue";
/*
	 vietnam:	  Theo ngôn ngữ
	 english:	  Site language config
*/

$lang_module['bots_config'] = "Moteurs de recherche";
/*
	 vietnam:	  Máy chủ tìm kiếm
	 english:	  Server search
*/

$lang_module['checkupdate'] = "Vérif. de version";
/*
	 vietnam:	  Kiểm tra phiên bản
	 english:	  Check version
*/

$lang_module['sitename'] = "Nom du site";
/*
	 vietnam:	  Tên gọi của site
	 english:	  Site name
*/

$lang_module['theme'] = "Thème par défaut";
/*
	 vietnam:	  Giao diện mặc định
	 english:	  Default theme
*/

$lang_module['themeadmin'] = "Thème de l'Administration";
/*
	 vietnam:	  Giao diện người quản trị
	 english:	  Administrator theme
*/

$lang_module['default_module'] = "Module par défaut à l'Acceuil";
/*
	 vietnam:	  Module mặc định trên trang chủ
	 english:	  Default module
*/

$lang_module['description'] = "Description du site";
/*
	 vietnam:	  Mô tả của site
	 english:	  Site's description
*/

$lang_module['rewrite'] = "Activer rewrite";
/*
	 vietnam:	  Bật chức năng rewrite
	 english:	  Rewrite Configuration
*/

$lang_module['rewrite_optional'] = "Au cas d'activer rewrite, filtrer les accents sur url";
/*
	 vietnam:	  Nếu bật chức năng rewrite thì loại bỏ kí tự ngôn ngữ trên url
	 english:	  If enable Rewrite function then remove language characters on URL
*/

$lang_module['site_disable'] = "Site en maintenance";
/*
	 vietnam:	  Site ngưng hoạt động
	 english:	  Site is disable
*/

$lang_module['disable_content'] = "Notification";
/*
	 vietnam:	  Nội dung thông báo
	 english:	  Content
*/

$lang_module['submit'] = "Sauvegarder";
/*
	 vietnam:	  Lưu
	 english:	  Submit
*/

$lang_module['err_writable'] = "Erreur: impossible d'entregister le fichier: %s merci de ve1rifier les permissions (chmod) de ce fichier.";
/*
	 vietnam:	  Lỗi hệ thống không ghi được file: %s bạn cần cấu hình server cho phép ghi file này.
	 english:	  Error system can't write file %s. Please chmod or check server config!
*/

$lang_module['err_supports_rewrite'] = "Erreur: le serveur ne supporte pas le module rewrite";
/*
	 vietnam:	  Lỗi, Máy chủ của bạn không hỗ trợ module rewrite
	 english:	  Error, server doesn't support rewrite.
*/

$lang_module['captcha'] = "Configuration de captcha";
/*
	 vietnam:	  Cấu hình hiển thị captcha
	 english:	  Captcha config
*/

$lang_module['captcha_0'] = "Masqué";
/*
	 vietnam:	  Không hiển thị
	 english:	  Hide
*/

$lang_module['captcha_1'] = "Lors de l'identification de l'admin";
/*
	 vietnam:	  Khi admin đăng nhập
	 english:	  When admin login
*/

$lang_module['captcha_2'] = "Lors de l'identification de membre";
/*
	 vietnam:	  Khi thành viên đăng nhập
	 english:	  When user login
*/

$lang_module['captcha_3'] = "Lors de l'inscription de membre";
/*
	 vietnam:	  Khi khách đăng ký
	 english:	  When guest register
*/

$lang_module['captcha_4'] = "Lors de l'identification de membre ou à l'inscription";
/*
	 vietnam:	  Khi thành viên đăng nhập hoặc khách đăng ký
	 english:	  When user login or guest register
*/

$lang_module['captcha_5'] = "Lors de l'identification de l'admin ou de membre";
/*
	 vietnam:	  Khi admin hoặc thành viên đăng nhập
	 english:	  When admin or user login
*/

$lang_module['captcha_6'] = "Lors de l'identification de l'admin ou l'inscription du membre";
/*
	 vietnam:	  Khi admin đăng nhập hoặc khách đăng ký
	 english:	  When admin login or guest register
*/

$lang_module['captcha_7'] = "Toujours";
/*
	 vietnam:	  Hiển thị trong mọi trường hợp
	 english:	  Display at all
*/

$lang_module['ftp_config'] = "Config. de FTP";
/*
	 vietnam:	  Cấu hình FTP
	 english:	  FTP Config
*/

$lang_module['smtp_config'] = "Config. de SMTP";
/*
	 vietnam:	  Cấu hình SMTP
	 english:	  SMTP Config
*/

$lang_module['server'] = "Serveur ou Lien";
/*
	 vietnam:	  Server or Url
	 english:	  Server or Url
*/

$lang_module['port'] = "Porte";
/*
	 vietnam:	  Port
	 english:	  Port
*/

$lang_module['username'] = "Identifiant";
/*
	 vietnam:	  User name
	 english:	  Username
*/

$lang_module['password'] = "Mot de passe";
/*
	 vietnam:	  Password
	 english:	  Password
*/

$lang_module['ftp_path'] = "Chemin d'accès";
/*
	 vietnam:	  Remote path
	 english:	  Remote path
*/

$lang_module['mail_config'] = "Sélection de méthode";
/*
	 vietnam:	  Lựa chọn cấu hình
	 english:	  Select mail server type
*/

$lang_module['type_smtp'] = "SMTP";
/*
	 vietnam:	  SMTP
	 english:	  SMTP
*/

$lang_module['type_linux'] = "Linux Mail";
/*
	 vietnam:	  Linux Mail
	 english:	  Linux Mail
*/

$lang_module['type_phpmail'] = "PHPmail";
/*
	 vietnam:	  PHPmail
	 english:	  PHPmail
*/

$lang_module['smtp_server'] = "Information du serveur";
/*
	 vietnam:	  Server Infomation
	 english:	  Server Information
*/

$lang_module['incoming_ssl'] = "Ce serveur demande une connexion sécurisée (SSL)";
/*
	 vietnam:	  This server requires an encrypted connection (SSL)
	 english:	  This server requires an encrypted connection (SSL)
*/

$lang_module['outgoing'] = "Outgoing mail server (SMTP)";
/*
	 vietnam:	  Outgoing mail server (SMTP)
	 english:	  Outgoing mail server (SMTP)
*/

$lang_module['outgoing_port'] = "Outgoing server(SMTP)";
/*
	 vietnam:	  Outgoing server(SMTP)
	 english:	  Outgoing port server(SMTP)
*/

$lang_module['smtp_username'] = "Infomation du compte";
/*
	 vietnam:	  Logon infomation
	 english:	  Logon information
*/

$lang_module['smtp_login'] = "Nom d'utilisateur";
/*
	 vietnam:	  User Name
	 english:	  User Name
*/

$lang_module['smtp_pass'] = "Mot de passe";
/*
	 vietnam:	  Password
	 english:	  Password
*/

$lang_module['update_error'] = "Erreur: Impossible de vérifier les informations, merci de vérifier plus tard";
/*
	 vietnam:	  Lỗi: hệ thống không check được thông tin, Bạn vui lòng kiểm tra lại vào thời gian khác
	 english:	  Error: The system does not check the information, Please check back at another time
*/

$lang_module['version_latest'] = "Félicitation, Vous avez la dernière version";
/*
	 vietnam:	  Phiên bản hiện tại của bạn đang là mới nhất
	 english:	  The current version is your latest
*/

$lang_module['version_no_latest'] = "Vous n'avez pas la dernière version";
/*
	 vietnam:	  Phiên bản của bạn chưa mới nhất
	 english:	  Your version is not latest
*/

$lang_module['version_info'] = "Information de la nouvelle version";
/*
	 vietnam:	  Thông tin phiên bản mới nhất
	 english:	  Latest information
*/

$lang_module['version_name'] = "Nom du système:";
/*
	 vietnam:	  Tên hệ thống
	 english:	  System Name
*/

$lang_module['version_number'] = "Version:";
/*
	 vietnam:	  Số phiên bản
	 english:	  Version number
*/

$lang_module['version_date'] = "Date de publication:";
/*
	 vietnam:	  Ngày phát hành
	 english:	  Release date
*/

$lang_module['version_note'] = "Notes sur la nouvelle version";
/*
	 vietnam:	  Ghi chú về phiên bản mới
	 english:	  Notes on the new version
*/

$lang_module['version_download'] = "Vous pouvez télécharger la nouvelle version";
/*
	 vietnam:	  bạn có thể download phiên bản mới
	 english:	  you can download the new version
*/

$lang_module['version_updatenew'] = "Mettre à jour";
/*
	 vietnam:	  update phiên bản mới
	 english:	  update new version
*/

$lang_module['bot_name'] = "Moteur de recherche";
/*
	 vietnam:	  Tên máy chủ
	 english:	  Server's name
*/

$lang_module['bot_agent'] = "Agent du serveur";
/*
	 vietnam:	  UserAgent của máy chủ
	 english:	  UserAgent
*/

$lang_module['bot_ips'] = "IP du serveur";
/*
	 vietnam:	  IP của máy chủ
	 english:	  Server's IP
*/

$lang_module['bot_allowed'] = "Autorisé";
/*
	 vietnam:	  Quyền xem
	 english:	  Permission
*/

$lang_module['site_keywords'] = "Mots clés pour les moteurs de recherche";
/*
	 vietnam:	  Từ khóa cho máy chủ tìm kiếm
	 english:	  Keywords
*/

$lang_module['site_logo'] = "Nom du fichier de logo du site";
/*
	 vietnam:	  Tên file logo của site
	 english:	  Site's logo
*/

$lang_module['site_email'] = "E-mail du site";
/*
	 vietnam:	  Email của site
	 english:	  Site's email
*/

$lang_module['error_send_email'] = "E-mail recevant les notifications d'erreurs";
/*
	 vietnam:	  Email nhận thông báo lỗi
	 english:	  Error send mail
*/

$lang_module['site_phone'] = "Téléphone du site";
/*
	 vietnam:	  Điện thoại liên hệ site
	 english:	  Site's phone
*/

$lang_module['lang_multi'] = "Activer le multi-language";
/*
	 vietnam:	  Kích hoạt đa ngôn ngữ
	 english:	  Activate multi-language
*/

$lang_module['site_lang'] = "Langue par défaut";
/*
	 vietnam:	  Ngôn ngữ mặc định
	 english:	  Default language
*/

$lang_module['site_timezone'] = "Fuseau horaire";
/*
	 vietnam:	  Múi giờ của site
	 english:	  Site's timezone
*/

$lang_module['date_pattern'] = "Type d'affichage: Date Mois An";
/*
	 vietnam:	  Kiểu hiển thị ngày tháng năm
	 english:	  Date display format
*/

$lang_module['time_pattern'] = "Type d'affichage: Heure Minute";
/*
	 vietnam:	  Kiểu hiển thị giờ phút
	 english:	  Time display format
*/

$lang_module['online_upd'] = "Activer le compteur de visiteurs en ligne";
/*
	 vietnam:	  Kích hoạt tiện ích đếm số người online
	 english:	  Activate monitoring online users
*/

$lang_module['gzip_method'] = "Activer gzip";
/*
	 vietnam:	  Bật chế độ gzip
	 english:	  Activate gzip
*/

$lang_module['statistic'] = "Activer  les statistiques";
/*
	 vietnam:	  Kích hoạt tiện ích thống kê
	 english:	  Activate statistics
*/

$lang_module['proxy_blocker'] = "Contrôler et bloquer les ordiateurs utilisant le proxy";
/*
	 vietnam:	  Kiểm tra và chặn các máy tình dùng proxy
	 english:	  Block proxy
*/

$lang_module['proxy_blocker_0'] = "Sans contrôle";
/*
	 vietnam:	  Không kiểm tra
	 english:	  Don't check
*/

$lang_module['proxy_blocker_1'] = "Contrôle léger";
/*
	 vietnam:	  Kiểm tra nhẹ
	 english:	  Low
*/

$lang_module['proxy_blocker_2'] = "Contrôle moyen";
/*
	 vietnam:	  Kểm tra vừa
	 english:	  Medium
*/

$lang_module['proxy_blocker_3'] = "Contrôle strict";
/*
	 vietnam:	  Kiểm tra tuyệt đối
	 english:	  High
*/

$lang_module['str_referer_blocker'] = "Activer le contrôle des liens vers le site depuis l'exterieur";
/*
	 vietnam:	  Kích hoạt tiện ích kiểm tra và chuyển hướng các REFERER bên ngoài đến trang chủ
	 english:	  Activate block referers
*/

$lang_module['my_domains'] = "Les domaines du site";
/*
	 vietnam:	  Các domain chạy site, cách nhau bỏi dấu phảy
	 english:	  Domains
*/

$lang_module['cookie_prefix'] = "Préfixe de cookie";
/*
	 vietnam:	  Tiến tố cookie
	 english:	  Cookie prefix
*/

$lang_module['session_prefix'] = "Préfixe de session";
/*
	 vietnam:	  Tiền tố session
	 english:	  Session's prefix
*/


?>