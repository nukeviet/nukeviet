<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Jun 21, 2011, 08:33:33 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="Phạm Chí Quang";
$lang_translator['createdate'] ="21/6/2010, 19:30";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.";
$lang_translator['info'] ="Langue française pour NukeViet 3";
$lang_translator['langtype'] ="lang_global";

$lang_global['mod_authors'] = "Administrateurs";
/*
	 vietnam:	  Quản trị
	 english:	  Administrators
*/

$lang_global['mod_groups'] = "Groupe";
/*
	 vietnam:	  Nhóm thành viên
	 english:	  Group
*/

$lang_global['mod_database'] = "Base de données";
/*
	 vietnam:	  CSDL
	 english:	  Database
*/

$lang_global['mod_settings'] = "Configuration";
/*
	 vietnam:	  Cấu hình
	 english:	  Configuration
*/

$lang_global['mod_cronjobs'] = "Procès automatiques";
/*
	 vietnam:	  Tiến trình tự động
	 english:	  Automatic process
*/

$lang_global['mod_modules'] = "Modules";
/*
	 vietnam:	  Quản lý Modules
	 english:	  Modules
*/

$lang_global['mod_themes'] = "Thèmes";
/*
	 vietnam:	  Quản lý giao diện
	 english:	  Themes
*/

$lang_global['mod_siteinfo'] = "Informations";
/*
	 vietnam:	  Thông tin
	 english:	  Information
*/

$lang_global['mod_language'] = "Langues";
/*
	 vietnam:	  Ngôn ngữ
	 english:	  Language
*/

$lang_global['mod_upload'] = "Médias";
/*
	 vietnam:	  Quản lý File
	 english:	  Upload
*/

$lang_global['mod_webtools'] = "Utilitaire Web";
/*
	 vietnam:	  Công cụ web
	 english:	  Webtools
*/

$lang_global['go_clientsector'] = "Page d'Accueil";
/*
	 vietnam:	  Trang chủ site
	 english:	  Home page
*/

$lang_global['go_clientmod'] = "Prévisualiser";
/*
	 vietnam:	  Xem ngoài site
	 english:	  See out website
*/

$lang_global['please_select'] = "Sélectionnez";
/*
	 vietnam:	  Hãy lựa chọn
	 english:	  Please select
*/

$lang_global['admin_password_empty'] = "Manque de Mot de passe";
/*
	 vietnam:	  Mật khẩu quản trị của bạn chưa được khai báo
	 english:	  Administrator password has not been declared
*/

$lang_global['adminpassincorrect'] = "Mot de passe &ldquo;<strong>%s</strong>&rdquo; incorrect. Essayez de nouveau";
/*
	 vietnam:	  Mật khẩu quản trị &ldquo;<strong>%s</strong>&rdquo; không chính xác. Hãy thử lại lần nữa
	 english:	  Administrator password &ldquo;<strong>%s</strong>&rdquo; is inaccurate. Try again
*/

$lang_global['admin_password'] = "Votre mot de passe";
/*
	 vietnam:	  Mật khẩu của bạn
	 english:	  Password
*/

$lang_global['admin_no_allow_func'] = "Vous n'êtes pas authorisé d'accéder à cette fonction";
/*
	 vietnam:	  Bạn không có quyền truy cập chức năng này
	 english:	  You can't access this function
*/

$lang_global['who_view'] = "Afficher pour";
/*
	 vietnam:	  Quyền xem
	 english:	  View right
*/

$lang_global['who_view0'] = "Tous";
/*
	 vietnam:	  Tất cả
	 english:	  View all
*/

$lang_global['who_view1'] = "Membres";
/*
	 vietnam:	  Thành viên
	 english:	  Member
*/

$lang_global['who_view2'] = "Administrateurs";
/*
	 vietnam:	  Quản trị
	 english:	  Administrators
*/

$lang_global['who_view3'] = "Groupe de membres";
/*
	 vietnam:	  Nhóm Thành viên
	 english:	  Group Member
*/

$lang_global['groups_view'] = "Les groupes autorisés";
/*
	 vietnam:	  Các nhóm được xem
	 english:	  Group viewed
*/

$lang_global['block_modules'] = "Blocks de modules";
/*
	 vietnam:	  Block của modules
	 english:	  Block in modules
*/

$lang_global['hello_admin1'] = "Bonjour %1\$s ! Votre dernière session était à %2\$s";
/*
	 vietnam:	  Xin chào %1\$s ! Lần đăng nhập Quản trị trước vào %2\$s
	 english:	  Wellcome %1\$s ! Last login to administration at: %2\$s
*/

$lang_global['hello_admin2'] = "Compte: %1\$s ! Votre session est ouverte depuis %2\$s";
/*
	 vietnam:	  Tài khoản Quản trị: %1\$s ! Bạn đã đăng nhập Quản trị cách đây %2\$s
	 english:	  Account: %1\$s ! You are logged in administration, %2\$s
*/

$lang_global['hello_admin3'] = "Bonjour %1\$s. C'est votre première session d'administration";
/*
	 vietnam:	  Xin chào mừng %1\$s. Đây là lần đăng nhập Quản trị đầu tiên của bạn
	 english:	  Wellcome %1\$s. This is the first time to login administration
*/

$lang_global['ftp_error_account'] = "Erreur: Impossible de se connecter au serveur FTP, merci de vérifier la configuration de FTP";
/*
	 vietnam:	  Lỗi: hệ thống không kết nối được FTP server vui lòng kiểm tra lại các thông số FTP
	 english:	  Error: Can't connect to FTP server, please check FTP configuration
*/

$lang_global['ftp_error_path'] = "Erreur: Chemin d'accès incorrect";
/*
	 vietnam:	  Lỗi: thông số Remote path không đúng
	 english:	  Error: Wrong configuration in Remote path
*/

$lang_global['login_error_account'] = "Erreur: Compte d'Administrateur manquant ou invalide (pas moins de %1\$s caractères, ni plus de  %2\$s caractères. Utilisez uniquement les lettres latines, chiffres et tiret)";
/*
	 vietnam:	  Lỗi: Tài khoản Admin chưa được khai báo hoặc khai báo không hợp lệ! (Không ít hơn %1\$s ký tự, không nhiều hơn %2\$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin, số và dấu gạch dưới)
	 english:	  Error: Username was not announced or declared invalid. (Only letters, numbers and underscores the Latin alphabet. Minimum %1\$s characters, maximum %1\$s characters)
*/

$lang_global['login_error_password'] = "Erreur: Mot de passe manquant ou invalide! (pas moins de %1\$s caractères, ni plus de %2\$s caractères combinés de lettres latines et chiffres)";
/*
	 vietnam:	  Lỗi: Password của Admin chưa được khai báo hoặc khai báo không hợp lệ! (Không ít hơn %1\$s ký tự, không nhiều hơn %2\$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin, số và dấu gạch dưới)
	 english:	  Error: Password has not announced or declared invalid. (Only letters, numbers and underscores the Latin alphabet. Minimum %1\$s characters, maximum %1\$s characters)
*/

$lang_global['login_error_security'] = "Erreur: Code de sécurité manquant ou invalide! (il faut %1\$s caractères combinés de lettres latines et chiffres)";
/*
	 vietnam:	  Lỗi: Mã kiểm tra chưa được khai báo hoặc khai báo không hợp lệ! (Phải có %1\$s ký tự. Chỉ chứa các ký tự có trong bảng chữ cái latin và số)
	 english:	  Error: Security Code not valid ! (Only Latin alphabet. Must have %1\$s characters)
*/

$lang_global['error_zlib_support'] = "Erreur: votre serveur ne supporte pas l'extension zlib, veuillez demander votre hébergeur de l'activer pour utiliser cette fonction.";
/*
	 vietnam:	  Lỗi: Máy chủ của bạn không hỗ trợ thư viện zlib, bạn cần liên hệ với nhà cung cấp dịch vụ hosting bật thư viện zlib để có thể sử dụng tính năng này.
	 english:	  Error: Your server does not support zlib extension, You need contact your hosting provider to enable the zlib extension.
*/

$lang_global['error_zip_extension'] = "Erreur: votre serveur ne supporte pas l'extension ZIP, veuillez demander votre hébergeur de l'activer pour utiliser cette fonction.";
/*
	 vietnam:	  Lỗi: Máy chủ của bạn không hỗ trợ extension ZIP, bạn cần liên hệ với nhà cung cấp dịch vụ hosting bật extension ZIP để có thể sử dụng tính năng này.
	 english:	  Error: Your server does not support ZIP extension, You need contact your hosting provider to enable the ZIP extension.
*/

$lang_global['error_uploadNameEmpty'] = "Erreur: nom dufichier indéterminé";
/*
	 vietnam:	  Lỗi: Tên file tải lên không xác định
	 english:	  UserFile Name is empty
*/

$lang_global['error_uploadSizeEmpty'] = "Erreur: taille indéterminé";
/*
	 vietnam:	  Lỗi: Dung lượng file tải lên không xác định
	 english:	  UserFile Size is empty
*/

$lang_global['error_upload_ini_size'] = "Erreur: taille du fichier dépasse le maximum déterminé dans php.ini";
/*
	 vietnam:	  Lỗi: Dung lượng file tải lên lớn hơn mức cho phép được xác định trong php.ini
	 english:	  The uploaded file exceeds the upload_max_filesize directive in php.ini
*/

$lang_global['error_upload_form_size'] = "Erreur: taille du fichier dépasse le maximum déterminé par MAX_FILE_SIZE du code HTML:";
/*
	 vietnam:	  Lỗi: Dung lượng file tải lên lớn hơn mức cho phép được xác định qua biến MAX_FILE_SIZE trong mã HTML
	 english:	  The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form
*/

$lang_global['error_upload_partial'] = "Erreur: fichier transféré partielement";
/*
	 vietnam:	  Lỗi: Chỉ một phần của file được tải lên
	 english:	  The uploaded file was only partially uploaded
*/

$lang_global['error_upload_no_file'] = "Erreur: aucun fichier envoyé";
/*
	 vietnam:	  Lỗi: Chưa có file tải lên
	 english:	  No file was uploaded
*/

$lang_global['error_upload_no_tmp_dir'] = "Erreur: dossier temporaire contenant les fichiers envoyés n'est pas déterminé";
/*
	 vietnam:	  Lỗi: Thư mục tạm thời chứa file tải lên không được xác định
	 english:	  Missing a temporary folder
*/

$lang_global['error_upload_cant_write'] = "Erreur: impossible d'ajouter les fichiers";
/*
	 vietnam:	  Lỗi: Không thể ghi file tải lên
	 english:	  Failed to write file to disk
*/

$lang_global['error_upload_extension'] = "Erreur: extension n'est pas permis";
/*
	 vietnam:	  Lỗi: File tải lên bị chặn vì thành phần mở rộng không hợp lệ
	 english:	  File upload stopped by extension
*/

$lang_global['error_upload_unknown'] = "Erreur inconnu";
/*
	 vietnam:	  Đã xảy ra lỗi không xác định khi tải lên
	 english:	  Unknown upload error
*/

$lang_global['error_upload_type_not_allowed'] = "Erreur: type du fichier n'est pas permis";
/*
	 vietnam:	  Lỗi: loại file không được phép tải lên
	 english:	  Files of this type are not allowed
*/

$lang_global['error_upload_mime_not_recognize'] = "Erreur: type du fichier inconnu";
/*
	 vietnam:	  Lỗi: Hệ thống không thể xác định được định dạng của file tải lên
	 english:	  System does not recognize the mime type of uploaded file
*/

$lang_global['error_upload_max_user_size'] = "Erreur: taille du fichier supérieur au niveau autorisé. Le maximum est de %d bytes";
/*
	 vietnam:	  Lỗi: Dung lượng file tải lên lớn hơn mức cho phép. Dung lượng lớn nhất được tải lên là %d bytes
	 english:	  The file exceeds the maximum size allowed. Maximum size is %d bytes
*/

$lang_global['error_upload_not_image'] = "Erreur: type de l'image inconnu";
/*
	 vietnam:	  Lỗi: Hệ thống không thể xác định được định dạng hình tải lên
	 english:	  The file is not a known image format
*/

$lang_global['error_upload_image_failed'] = "Erreur: image invalide";
/*
	 vietnam:	  Lỗi: Hình tải lên không hợp lệ
	 english:	  Image Content is failed
*/

$lang_global['error_upload_image_width'] = "Erreur: taille de l'image supérieure au niveau autorisé. Le maximum est de %d pixels";
/*
	 vietnam:	  Lỗi: Hình tải lên có chiều rộng lớn hơn mức cho phép. Chiều rộng lớn nhất cho phép là %d pixels
	 english:	  The image is not allowed because the width is greater than the maximum of %d pixels
*/

$lang_global['error_upload_image_height'] = "Erreur: taille supérieure au niveau autorisé. Hauteur maximale est de %d pixels";
/*
	 vietnam:	  Lỗi: Hình tải lên có chiều cao lớn hơn mức cho phép. Chiều cao lớn nhất cho phép lag %d pixels
	 english:	  The image is not allowed because the height is greater than the maximum of %d pixels
*/

$lang_global['error_upload_forbidden'] = "Erreur: dossier contenant fichier envoyé indéterminé.";
/*
	 vietnam:	  Lỗi: Thư mục chứa file tải lên không được xác định
	 english:	  Upload forbidden
*/

$lang_global['error_upload_writable'] = "Erreur: impossible d'ajouter le fichier. Veuillez vérifier les permissions (chmod 777)";
/*
	 vietnam:	  Lỗi: Thư mục %s không cho phép chứa file tải lên. Có thể bạn cần CHMOD lại thư mục này ở dạng 0777
	 english:	  Directory %s is not writable
*/

$lang_global['error_upload_urlfile'] = "Erreur: lien invalide";
/*
	 vietnam:	  Lỗi: URL mà bạn đưa ra không đúng
	 english:	  The URL is not valid and cannot be loaded
*/

$lang_global['error_upload_url_notfound'] = "Erreur: impossible de prendre le fichier depuis votre lien";
/*
	 vietnam:	  Lỗi: Không thể tải file từ URL mà bạn đưa ra
	 english:	  The url was not found
*/


?>