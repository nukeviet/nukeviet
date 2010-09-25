<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Sep 25, 2010, 06:50:12 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="Phạm Chí Quang";
$lang_translator['createdate'] ="16/08/2010";
$lang_translator['copyright'] ="Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.";
$lang_translator['info'] ="Langue française pour NukeViet 3";
$lang_translator['langtype'] ="lang_module";

$lang_module['download_config'] = "Configuration";
/*
	 vietnam:	  Cấu hình module
	 english:	  Configure module
*/

$lang_module['config_is_addfile'] = "Autoriser l'ajout des fichiers";
/*
	 vietnam:	  Cho phép thêm file
	 english:	  Allow add file
*/

$lang_module['config_is_uploadfile'] = "Autoriser le transfert de fichiers sur le serveur";
/*
	 vietnam:	  Cho phép upload file lên server
	 english:	  Allow upload file
*/

$lang_module['config_allowfiletype'] = "Types de fichiers autorisés";
/*
	 vietnam:	  Loại file được cho phép tải lên
	 english:	  Allow file type
*/

$lang_module['config_maxfilesize'] = "Taille maximum de fichier";
/*
	 vietnam:	  Dung lượng tối đa của file
	 english:	  Maximum file size
*/

$lang_module['config_maxfilebyte'] = "bytes";
/*
	 vietnam:	  byte
	 english:	  byte
*/

$lang_module['config_maxfilesizesys'] = "Votre limite de transfert au serveur:";
/*
	 vietnam:	  Giới hạn tải lên hệ thống của bạn là
	 english:	  Upload limit
*/

$lang_module['config_uploadedfolder'] = "Répertoire contenant les fichiers approuvés";
/*
	 vietnam:	  Thư mục chứa những file đã được kiểm duyệt
	 english:	  Processed file's folder
*/

$lang_module['config_queuefolder'] = "Répertoire contenant les fichiers en suspens";
/*
	 vietnam:	  Thư mục chứa những file chờ kiểm duyệt
	 english:	  Preprocess file's folder
*/

$lang_module['config_whouploadfile'] = "Qui peut transférer";
/*
	 vietnam:	  Ai được upload file
	 english:	  Upload file
*/

$lang_module['config_whoaddfile'] = "Qui peut ajouter les fichiers";
/*
	 vietnam:	  Ai được thêm file
	 english:	  Add file
*/

$lang_module['groups_upload'] = "Cocher les grouppes autorisés pour les sélectionner";
/*
	 vietnam:	  Nếu chọn nhóm, hãy đánh dấu vào các nhóm cho phép
	 english:	  Group upload
*/

$lang_module['config_confirm'] = "Confirmer";
/*
	 vietnam:	  Chấp nhận
	 english:	  Confirm
*/

$lang_module['error_cat1'] = "Erreur: Catégorie existante !";
/*
	 vietnam:	  Lỗi: Chủ đề này đã có !
	 english:	  Error: Category was used!
*/

$lang_module['error_cat2'] = "Erreur: Catégorie non déclarée !";
/*
	 vietnam:	  Lỗi: Chủ đề chưa được khai báo !
	 english:	  Error: Empty category!
*/

$lang_module['error_cat3'] = "Erreur: Catégorie inexistante !";
/*
	 vietnam:	  Lỗi: Chủ đề mẹ mà bạn chọn không tồn tại !
	 english:	  Error: Not exists category!
*/

$lang_module['error_cat4'] = "Impossible de créer la nouvelle catégorie par un raison inconnu !";
/*
	 vietnam:	  Vì một lý do nào đó, chủ đề mới đã không được tạo !
	 english:	  Error: Category can't create!
*/

$lang_module['error_cat5'] = "Impossible d'enregistrer les données par un raison inconnu !";
/*
	 vietnam:	  Vì một lý do nào đó, các thay đổi mà bạn vừa khai báo đã không được lưu !
	 english:	  Error: Can't update!
*/

$lang_module['addcat_titlebox'] = "Ajouter une Catégorie";
/*
	 vietnam:	  Thêm chủ đề
	 english:	  Add category
*/

$lang_module['category_cat_name'] = "Nom de la Catégorie";
/*
	 vietnam:	  Tên chủ đề
	 english:	  Name
*/

$lang_module['category_cat_parent'] = "Catégorie";
/*
	 vietnam:	  Thuộc chủ đề
	 english:	  In category
*/

$lang_module['category_cat_maincat'] = "Catégorie principale";
/*
	 vietnam:	  Chủ đề chính
	 english:	  Main category
*/

$lang_module['who_view'] = "Qui peut voir?";
/*
	 vietnam:	  Quyền xem
	 english:	  View
*/

$lang_module['who_download'] = "Qui peut télécharger";
/*
	 vietnam:	  Quyền tải file
	 english:	  Download
*/

$lang_module['description'] = "Description";
/*
	 vietnam:	  Mô tả
	 english:	  Description
*/

$lang_module['cat_save'] = "Sauver";
/*
	 vietnam:	  Lưu lại
	 english:	  Save
*/

$lang_module['download_catmanager'] = "Catégories";
/*
	 vietnam:	  Quản lý chủ đề
	 english:	  Manage category
*/

$lang_module['category_cat_sub'] = "Sous-catégorie";
/*
	 vietnam:	  chủ đề con
	 english:	  Sub category
*/

$lang_module['category_cat_active'] = "Active";
/*
	 vietnam:	  Hoạt động
	 english:	  Active
*/

$lang_module['category_cat_feature'] = "Actions";
/*
	 vietnam:	  Chức năng
	 english:	  Action
*/

$lang_module['table_caption1'] = "Liste de Catégories principales";
/*
	 vietnam:	  Danh sách các chủ đề là chủ đề chính
	 english:	  Main category
*/

$lang_module['table_caption2'] = "Liste de Sous-catégories de &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Danh sách các chủ đề con của chủ đề &ldquo;<strong>%s</strong>&rdquo;
	 english:	  Sub category of &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['category_cat_sort'] = "Position";
/*
	 vietnam:	  Vị trí
	 english:	  Order
*/

$lang_module['file_error_fileupload'] = "Sélectionez un fichier pour transférer ou donnez le lien direct!";
/*
	 vietnam:	  Hãy chọn file để upload hoặc điền vào link trực tiếp!
	 english:	  Upload file or paste direct URL!
*/

$lang_module['file_error_author_url'] = "Erreur: Site de l'auteur invalide!";
/*
	 vietnam:	  Lỗi: URL trang cá nhân của tác giả không hợp lệ!
	 english:	  Error: Invalid URL!
*/

$lang_module['file_error1'] = "Impossible d'enregistrer les données par un raison inconnu!";
/*
	 vietnam:	  Vì một lý do nào đó, các thay đổi mà bạn vừa khai báo đã không được lưu !
	 english:	  Error: Can't update!
*/

$lang_module['file_error2'] = "Impossible d'ajouter le fichier par un raison inconnu !";
/*
	 vietnam:	  Vì một lý do nào đó, file mới đã không được lưu vào CSDL !
	 english:	  Error: Can't save file!
*/

$lang_module['file_title'] = "Nom du fichier";
/*
	 vietnam:	  Tên file
	 english:	  Name
*/

$lang_module['file_author_name'] = "Auteur";
/*
	 vietnam:	  Tên tác giả
	 english:	  Author
*/

$lang_module['file_author_email'] = "E-mail de l'auteur";
/*
	 vietnam:	  Email tác giả
	 english:	  Email
*/

$lang_module['file_author_homepage'] = "Site web de l'auteur";
/*
	 vietnam:	  Trang cá nhân của tác giả
	 english:	  Author site
*/

$lang_module['file_selectfile'] = "Parcourir...";
/*
	 vietnam:	  Chọn file
	 english:	  Select file
*/

$lang_module['file_myfile'] = "Fichier à télécharger";
/*
	 vietnam:	  File tải lên
	 english:	  Upload file
*/

$lang_module['file_linkdirect'] = "Source externe";
/*
	 vietnam:	  Nguồn bên ngoài
	 english:	  File link
*/

$lang_module['file_linkdirect_note'] = "S'il y a plusieurs liens, listez-les un par ligne - utilisez la touche ENTER (ENTRER) pour sauter la ligne";
/*
	 vietnam:	  Nếu nguồn gồm nhiều file nhỏ, link đến các file này được phân cách bằng dấu xuống dòng - phím ENTER
	 english:	  Only one link per line
*/

$lang_module['file_version'] = "Version";
/*
	 vietnam:	  Thông tin phiên bản
	 english:	  Version
*/

$lang_module['file_image'] = "Image";
/*
	 vietnam:	  Hình minh họa
	 english:	  Illustration
*/

$lang_module['file_copyright'] = "Infos du droit d'auteur";
/*
	 vietnam:	  Thông tin bản quyền
	 english:	  Copyright
*/

$lang_module['file_allowcomment'] = "Autoriser les commentaires";
/*
	 vietnam:	  Cho phép thảo luận
	 english:	  Allow comment
*/

$lang_module['file_whocomment'] = "Qui peut commenter";
/*
	 vietnam:	  Ai được quyền thảo luận
	 english:	  Comment
*/

$lang_module['intro_title'] = "Introduction";
/*
	 vietnam:	  Tóm tắt
	 english:	  Brief
*/

$lang_module['file_description'] = "Description";
/*
	 vietnam:	  Mô tả file
	 english:	  Description
*/

$lang_module['confirm'] = "Confirmer";
/*
	 vietnam:	  Thực hiện
	 english:	  Confirm
*/

$lang_module['file_error_title'] = "Erreur: le titre ne peut pas être vide !";
/*
	 vietnam:	  Tiêu đề không được để trống !
	 english:	  Error: Empty title!
*/

$lang_module['file_size'] = "Taille";
/*
	 vietnam:	  Dung lượng
	 english:	  Size
*/

$lang_module['file_list_by_cat'] = "Liste de fichiers de Catégorie &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Danh sách các file thuộc chủ đề &ldquo;<strong>%s</strong>&rdquo;
	 english:	  Files in category &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['file_update'] = "Publier";
/*
	 vietnam:	  Thời gian đăng
	 english:	  Upload
*/

$lang_module['file_view_hits'] = "Consulter";
/*
	 vietnam:	  Xem
	 english:	  Views
*/

$lang_module['file_download_hits'] = "Télécharger";
/*
	 vietnam:	  Tải
	 english:	  Downloads
*/

$lang_module['file_comment_hits'] = "Commenter";
/*
	 vietnam:	  Bình
	 english:	  Comments
*/

$lang_module['file_feature'] = "Fonctionalité";
/*
	 vietnam:	  Chức năng
	 english:	  Actions
*/

$lang_module['file_active'] = "Activer";
/*
	 vietnam:	  Hoạt động
	 english:	  Active
*/

$lang_module['file_addfile'] = "Ajouter";
/*
	 vietnam:	  Thêm file mới
	 english:	  Add new file
*/

$lang_module['download_filequeue'] = "Fichiers en suspens";
/*
	 vietnam:	  File chờ kiểm duyệt
	 english:	  Pre process file
*/

$lang_module['file_title_exists'] = "Erreur: ce nom été utilisé. Choisissez un autre nom";
/*
	 vietnam:	  Lỗi: Tên này đã được sử dụng. Hãy chọn một tên khác
	 english:	  Error: File's name was used.
*/

$lang_module['download_filequeue_del'] = "Supprimer";
/*
	 vietnam:	  Xóa file
	 english:	  Delete
*/

$lang_module['download_alldel'] = "Supprimer tout";
/*
	 vietnam:	  Xóa tất cả
	 english:	  Delete all
*/

$lang_module['filequeue_empty'] = "Désolé, aucun fichier envoyé!";
/*
	 vietnam:	  Rất tiếc là chưa có file nào được gửi đến!
	 english:	  Empty!
*/

$lang_module['file_checkUrl'] = "Vérifier";
/*
	 vietnam:	  Kiểm tra
	 english:	  Check
*/

$lang_module['file_checkUrl_error'] = "Erreur: lien inexistant!";
/*
	 vietnam:	  Lỗi: URL không tồn tại!
	 english:	  Error: URL doesn't exists!
*/

$lang_module['file_checkUrl_ok'] = "Lien accepté!";
/*
	 vietnam:	  URL được chấp nhận!
	 english:	  Valid URL!
*/

$lang_module['report_empty'] = "Aucun rapport d'erreur!";
/*
	 vietnam:	  Chưa có báo cáo lỗi!
	 english:	  Empty!
*/

$lang_module['report_post_time'] = "Date de rapport";
/*
	 vietnam:	  Thời gian báo cáo
	 english:	  Report at
*/

$lang_module['report_check_ok'] = "Le système a vérifié le fichier et n'a pas trouvé l'erreur. Supprimer ce rapport?";
/*
	 vietnam:	  Hệ thống đã kiểm tra file và không phát hiện ra lỗi. Xóa báo cáo lỗi này?
	 english:	  No error found. Delele this report?
*/

$lang_module['report_check_error'] = "Le système a trouvé le lien cassé. Cliquez OK pour corriger ou ANNULER pour aborder";
/*
	 vietnam:	  Hệ thống phát hiện link hỏng đối với file này. Hãy click OK để sửa hoặc CANCEL để thôi
	 english:	  Broken link found. Press OK to fix
*/

$lang_module['report_check_error2'] = "Erreur: Fichier inexistant. Cliquez OK pour supprimer le rapport ou ANNULER pour aborder";
/*
	 vietnam:	  Lỗi: File không tồn tại. Hãy click OK để xóa báo cáo này hoặc CANCEL để thôi
	 english:	  Error: File not exists. Press OK to delete this report
*/

$lang_module['report_delete'] = "Supprimer le Rapport d'erreur";
/*
	 vietnam:	  Xóa báo cáo lỗi
	 english:	  Delete
*/

$lang_module['file_gourl'] = "Connecter";
/*
	 vietnam:	  Truy cập
	 english:	  Access
*/

$lang_module['comment'] = "Commentaires";
/*
	 vietnam:	  Quản lý bình luận
	 english:	  Manage comment
*/

$lang_module['comment_of_file'] = "Liste de commentaires du fichier &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Danh sách các bình luận cho file &ldquo;<strong>%s</strong>&rdquo;
	 english:	  Comment on file &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['comment_of_file2'] = "Commenter ce fichier";
/*
	 vietnam:	  Bình luận cho file
	 english:	  Comment on file
*/

$lang_module['comment_of_file3'] = "Tous les commentaires";
/*
	 vietnam:	  Tất cả bình luận
	 english:	  All comments
*/

$lang_module['comment_st0'] = "Commentaires en suspens";
/*
	 vietnam:	  Bình luận đang đợi duyệt
	 english:	  Pre process comments
*/

$lang_module['comment_st1'] = "Commentaires actifs";
/*
	 vietnam:	  Bình luận đang hiệu lực
	 english:	  Active comments
*/

$lang_module['comment_st2'] = "Commentaires suspens";
/*
	 vietnam:	  Bình luận đang bị đình chỉ
	 english:	  Inactive comments
*/

$lang_module['comment_empty'] = "Aucun commentaire de ce fichier";
/*
	 vietnam:	  Rất tiếc là chưa có bình luận nào thuộc file này
	 english:	  Empty
*/

$lang_module['comment_empty0'] = "Aucun commentaire en suspens";
/*
	 vietnam:	  Rất tiếc là chưa có bình luận nào đang đợi duyệt
	 english:	  Empty
*/

$lang_module['comment_empty2'] = "Aucun commentaire en suspens";
/*
	 vietnam:	  Không có bình luận nào bị đình chỉ
	 english:	  Empty
*/

$lang_module['comment_status0'] = "À approuver";
/*
	 vietnam:	  Đợi duyệt
	 english:	  Preprocess
*/

$lang_module['comment_status1'] = "Activer";
/*
	 vietnam:	  Hiệu lực
	 english:	  Active
*/

$lang_module['comment_status2'] = "Suspens";
/*
	 vietnam:	  Đình chỉ
	 english:	  Inactive
*/

$lang_module['comment_post_ip'] = "IP";
/*
	 vietnam:	  IP
	 english:	  IP
*/

$lang_module['comment_post_email'] = "E-mail";
/*
	 vietnam:	  Email
	 english:	  Email
*/

$lang_module['comment_post_name'] = "Nom";
/*
	 vietnam:	  Tên
	 english:	  Name
*/

$lang_module['comment_edit'] = "Éditer";
/*
	 vietnam:	  Sửa bình luận
	 english:	  Edit comment
*/

$lang_module['comment_edit_error1'] = "Erreur: Manque de sujet";
/*
	 vietnam:	  Lỗi: Bình luận chưa có tiêu đề
	 english:	  Error: Empty title
*/

$lang_module['comment_edit_error2'] = "Erreur: Manque de contenu";
/*
	 vietnam:	  Lỗi: Bình luận chưa có nội dung
	 english:	  Error: Empty content
*/

$lang_module['comment_subject'] = "Sujet";
/*
	 vietnam:	  Tiêu đề
	 english:	  Title
*/

$lang_module['comment_content'] = "Contenu";
/*
	 vietnam:	  Nội dung
	 english:	  Content
*/

$lang_module['comment_admin_reply'] = "Note de l'administrateur";
/*
	 vietnam:	  Ghi chú của admin
	 english:	  Admin note
*/

$lang_module['file_who_autocomment'] = "Qui peut commenter sans censure";
/*
	 vietnam:	  Ai được tự động đăng thảo luận
	 english:	  Auto comment
*/

$lang_module['download_comment'] = "Gestion des commentaires";
/*
	 vietnam:	  Quản lý comment
	 english:	  Manage comment
*/

$lang_module['download_report'] = "Rapports d'erreur";
/*
	 vietnam:	  Báo cáo lỗi
	 english:	  Error report
*/

$lang_module['download_filemanager'] = "Gestion des fichiers";
/*
	 vietnam:	  Quản lý file
	 english:	  Manage file
*/

$lang_module['download_editfile'] = "Éditer le Fichier";
/*
	 vietnam:	  Sửa file
	 english:	  Edit file
*/

$lang_module['editcat_cat'] = "Éditer le Sujet";
/*
	 vietnam:	  Sửa chủ đề
	 english:	  Edit category
*/

$lang_module['add_file_items'] = "Ajouter un fichier";
/*
	 vietnam:	  Thêm file tải lên
	 english:	  Add file
*/

$lang_module['add_file_items_note'] = "Si ce fichier a plusieurs parties";
/*
	 vietnam:	  Nếu file có nhiều phần nhỏ
	 english:	  If file has many part
*/

$lang_module['add_linkdirect_items'] = "Ajouter un source externe";
/*
	 vietnam:	  Thêm nguồn bên ngoài
	 english:	  File link
*/

$lang_module['add_linkdirect_items_note'] = "Lien doit être différent que les liens existant";
/*
	 vietnam:	  Không được trùng với các nguồn đã liệt kê
	 english:	  File link is unique
*/

$lang_module['add_fileupload'] = "Changer le fichier";
/*
	 vietnam:	  Thay file tải lên mới
	 english:	  Replace file upload
*/

$lang_module['add_new_img'] = "Changer l'image";
/*
	 vietnam:	  Thay hình minh họa mới
	 english:	  Replace illustration
*/

$lang_module['is_zip'] = "Compresser les fichiers à télécharger";
/*
	 vietnam:	  ZIP file khi download
	 english:	  ZIP file when download
*/

$lang_module['zip_readme'] = "Contenu du fichier README.txt inclus dans le fichier ZIP";
/*
	 vietnam:	  Nội dung file README.txt kèm theo file ZIP
	 english:	  Add README.txt in ZIP file
*/

$lang_module['is_resume'] = "Supporter le resume de téléchargement";
/*
	 vietnam:	  Hỗ trợ chế độ resume khi download
	 english:	  Support download resume
*/

$lang_module['max_speed'] = "Limiter la vitesse de téléchargement";
/*
	 vietnam:	  Hạn chế tốc độ tải file
	 english:	  Download speed limit
*/

$lang_module['kb_sec'] = "KB/sec (0 = sans limite)";
/*
	 vietnam:	  KB/sec (0 = không hạn chế)
	 english:	  KB/sec (0 = Unlimited)
*/

$lang_module['alias'] = "Alias";
/*
	 vietnam:	  Liên kết tĩnh
	 english:	  
*/

$lang_module['siteinfo_publtime'] = "Fichiers actifs";
/*
	 vietnam:	  Số file hiệu lực
	 english:	  
*/

$lang_module['siteinfo_expired'] = "Fichiers expirés";
/*
	 vietnam:	  Số file hết hạn
	 english:	  
*/

$lang_module['siteinfo_users_send'] = "Fichiers suspens";
/*
	 vietnam:	  Số file chờ kiểm duyệt
	 english:	  
*/

$lang_module['siteinfo_eror'] = "Rapports d'erreurs";
/*
	 vietnam:	  Số báo cáo lỗi được gửi tới
	 english:	  
*/

$lang_module['siteinfo_comment'] = "Commentaires";
/*
	 vietnam:	  Số bình luận được gửi tới
	 english:	  
*/

$lang_module['siteinfo_comment_pending'] = "Commentaires suspens";
/*
	 vietnam:	  Số bình luận chờ kiểm duyệt
	 english:	  
*/


?>