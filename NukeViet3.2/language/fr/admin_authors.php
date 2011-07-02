<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language Français
* @Createdate Jun 21, 2011, 08:33:35 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="Phạm Chí Quang";
$lang_translator['createdate'] ="21/6/2010, 19:30";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC. Tous droits réservés.";
$lang_translator['info'] ="Langue française pour NukeViet 3";
$lang_translator['langtype'] ="lang_module";

$lang_module['level1'] = "Administrateur suprême";
/*
	 vietnam:	  Tối cao
	 english:	  Super
*/

$lang_module['level2'] = "Administrateur général";
/*
	 vietnam:	  Điều hành chung
	 english:	  General administration
*/

$lang_module['level3'] = "Administrateur de module";
/*
	 vietnam:	  Quản lý module
	 english:	  Module management
*/

$lang_module['is_suspend0'] = "Actif";
/*
	 vietnam:	  Hoạt động
	 english:	  Active
*/

$lang_module['is_suspend1'] = "Suspendu au &ldquo;%1\$s&rdquo; par &ldquo;%2\$s&rdquo; en raison &ldquo;%3\$s&rdquo;";
/*
	 vietnam:	  Bị đình chỉ vào &ldquo;%1\$s&rdquo; bởi &ldquo;%2\$s&rdquo; với lý do &ldquo;%3\$s&rdquo;
	 english:	  Suspend &ldquo;%1\$s&rdquo; bởi &ldquo;%2\$s&rdquo; reason &ldquo;%3\$s&rdquo;
*/

$lang_module['last_login0'] = "Jamais";
/*
	 vietnam:	  Chưa bao giờ
	 english:	  Never
*/

$lang_module['login'] = "Identifiant";
/*
	 vietnam:	  Tên tài khoản
	 english:	  Username
*/

$lang_module['email'] = "E-mail";
/*
	 vietnam:	  Email
	 english:	  Email
*/

$lang_module['full_name'] = "Nom complet";
/*
	 vietnam:	  Tên gọi trên site
	 english:	  Site name
*/

$lang_module['sig'] = "Signature";
/*
	 vietnam:	  Chữ ký
	 english:	  Signature
*/

$lang_module['editor'] = "Éditeur";
/*
	 vietnam:	  Trình soạn thảo
	 english:	  Editor
*/

$lang_module['lev'] = "Attributions";
/*
	 vietnam:	  Quyền hạn
	 english:	  Right
*/

$lang_module['position'] = "Fonction";
/*
	 vietnam:	  Chức danh
	 english:	  Position
*/

$lang_module['regtime'] = "Date de participation";
/*
	 vietnam:	  Ngày tham gia
	 english:	  Registration date time
*/

$lang_module['is_suspend'] = "Status actuel";
/*
	 vietnam:	  Tình trạng hiện tại
	 english:	  Status
*/

$lang_module['last_login'] = "Dernière session";
/*
	 vietnam:	  Lần đăng nhập gần đây
	 english:	  Last login
*/

$lang_module['last_ip'] = "IP";
/*
	 vietnam:	  Bằng IP
	 english:	  From IP
*/

$lang_module['browser'] = "Navigateur";
/*
	 vietnam:	  Bằng trình duyệt
	 english:	  Browser
*/

$lang_module['os'] = "Système d'exploitation";
/*
	 vietnam:	  Bằng hệ điều hành
	 english:	  Operate system
*/

$lang_module['admin_info_title1'] = "Infos du compte: %s";
/*
	 vietnam:	  Thông tin tài khoản: %s
	 english:	  Account information: %s
*/

$lang_module['admin_info_title2'] = "Infos du compte: %s (c'est vous)";
/*
	 vietnam:	  Thông tin tài khoản: %s (là bạn)
	 english:	  Account information: %s (you)
*/

$lang_module['menulist'] = "Administrateurs";
/*
	 vietnam:	  Danh sách Quản trị
	 english:	  List Administrator
*/

$lang_module['menuadd'] = "Ajouter un Administrateur";
/*
	 vietnam:	  Thêm Quản trị
	 english:	  Add an Administrator
*/

$lang_module['main'] = "Liste des Administrateurs";
/*
	 vietnam:	  Danh sách Quản trị website
	 english:	  Website adminsistrators list
*/

$lang_module['nv_admin_edit'] = "Mofifier les infos de l'administrateur";
/*
	 vietnam:	  Sửa thông tin Quản trị website
	 english:	  Edit website administrator's information
*/

$lang_module['nv_admin_add'] = "Ajouter l'administrateur";
/*
	 vietnam:	  Thêm Quản trị website
	 english:	  Add website administrator's information
*/

$lang_module['nv_admin_del'] = "Supprimer l'administrateur";
/*
	 vietnam:	  Xóa Quản trị website
	 english:	  Delete website administrator's information
*/

$lang_module['username_incorrect'] = "Erreur: impossible de trouver le compte:%s";
/*
	 vietnam:	  Lỗi: không tìm thấy thành viên có tài khoản: %s
	 english:	  Error: Don't found this member account:% s
*/

$lang_module['full_name_incorrect'] = "Vous n'avez pas encore entré le nom de cet administateur";
/*
	 vietnam:	  Bạn chưa khai báo tên gọi của người quản trị này
	 english:	  You do not declare the name of this administrator
*/

$lang_module['position_incorrect'] = "Vous n'avez pas encore entré la fonction de cet administateur";
/*
	 vietnam:	  Bạn chưa khai báo chức danh của người quản trị này
	 english:	  You do not declare the position of this administrator
*/

$lang_module['nv_admin_add_info'] = "Pour créer un nouveau compte d'administration, remplissez tous les champs ci-dessous. Vous ne pouvez créer qu'un administrateur inférieur de votre privilège";
/*
	 vietnam:	  Để tạo một tài khoản Quản trị website mới, bạn cần khai báo đầy đủ vào các ô trống dưới đây. Bạn chỉ có quyền tạo tài khoản Quản trị dưới cấp của mình
	 english:	  To add new website administrator account, you need to declare fully in the box below. You can create an account below your level management
*/

$lang_module['if_level3_selected'] = "Cochez les modules à gérer";
/*
	 vietnam:	  Hãy đánh dấu tích vào những module mà bạn cho phép quản lý
	 english:	  Please tick on the module that you allows to manage
*/

$lang_module['login_info'] = "de &ldquo;<strong>%1\$d</strong>&rdquo; à &ldquo;<strong>%2\$d</strong>&rdquo; caractères. Utilisez uniquement les lettres latines, chiffres et tiret";
/*
	 vietnam:	  Bạn cần nhập tên thành viên, nếu chưa có thành viên bạn cần tạo thành viên trước.
	 english:	  You need to enter the user name, if not a member you need to create first member.
*/

$lang_module['nv_admin_add_result'] = "Infos du nouveau administrateur";
/*
	 vietnam:	  Thông tin về Quản trị website mới
	 english:	  New administrator's information
*/

$lang_module['nv_admin_add_title'] = "Le système a été créé un nouveau compte d'administration avec les infos ci-dessous";
/*
	 vietnam:	  Hệ thống đã tạo thành công tài khoản Quản trị website mới với những thông tin dưới đây
	 english:	  System has successfully created a new website account administrator with the information below
*/

$lang_module['nv_admin_modules'] = "Gestion de module";
/*
	 vietnam:	  Quản lý các module
	 english:	  Modules management
*/

$lang_module['admin_account_info'] = "Information de l'administrateur %s";
/*
	 vietnam:	  Thông tin tài khoản Quản trị website %s
	 english:	  Administrator information %s
*/

$lang_module['nv_admin_add_download'] = "Télécharger";
/*
	 vietnam:	  Tải về
	 english:	  Download
*/

$lang_module['nv_admin_add_sendmail'] = "Envoyer la notification";
/*
	 vietnam:	  Gửi thông báo
	 english:	  Send mail
*/

$lang_module['nv_admin_login_address'] = "Lien vers la section d'administration";
/*
	 vietnam:	  URL trang quản lý website
	 english:	  URL management page
*/

$lang_module['nv_admin_edit_info'] = "Modifier les infos du compte &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Thay đổi thông tin tài khoản &ldquo;<strong>%s</strong>&rdquo;
	 english:	  Edit account information &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['show_mail'] = "Afficher l'e-mail";
/*
	 vietnam:	  Hiển thị email
	 english:	  Show email
*/

$lang_module['sig_info'] = "Cette signature sera insérée à la fin de chaque réponse, e-mail... envoyé par l'administrateur &ldquo;<strong>%s</strong>&rdquo;. Utilisez le texte brut sans mise en forme";
/*
	 vietnam:	  Chữ ký được chèn vào cuối mỗi bài trả lời, thư... được gửi đi từ tài khoản Quản trị &ldquo;<strong>%s</strong>&rdquo;. Chỉ chấp nhận dạng text đơn thuần
	 english:	  Signature is inserted at the end of each reply, email... sent from the Administrator account &ldquo;<strong>%s</strong>&rdquo;. Only accept simple text
*/

$lang_module['not_use'] = "non utilisé";
/*
	 vietnam:	  Không sử dụng
	 english:	  Not in use
*/

$lang_module['nv_admin_edit_result'] = "Changer les infos de l'administrateur: %s";
/*
	 vietnam:	  Thay đổi thông tin tài khoản Quản trị: %s
	 english:	  Edit website administrator's information: %s
*/

$lang_module['nv_admin_edit_result_title'] = "Les changements effectués pour le compte de l'administrateur %s";
/*
	 vietnam:	  Những thay đổi vừa được thực hiện đối với tài khoản Quản trị %s
	 english:	  Administrator account's changes: %s
*/

$lang_module['show_mail0'] = "Masquer";
/*
	 vietnam:	  Không hiển thị
	 english:	  Not show
*/

$lang_module['show_mail1'] = "Afficher";
/*
	 vietnam:	  Hiển thị
	 english:	  Show
*/

$lang_module['field'] = "Domaine";
/*
	 vietnam:	  tiêu chí
	 english:	  Criteria
*/

$lang_module['old_value'] = "Ancien";
/*
	 vietnam:	  Cũ
	 english:	  Old
*/

$lang_module['new_value'] = "Nouveau";
/*
	 vietnam:	  Mới
	 english:	  New
*/

$lang_module['chg_is_suspend0'] = "Le status actuel: suspendu. Pour Rétablir l'activité de cet administrateur, Remplissez les champs ci-dessous";
/*
	 vietnam:	  Tình trạng hiện tại: Đang bị đình chỉ. Để Khôi phục hoạt động của tài khoản quản trị này, bạn hãy khai báo vào các ô trống dưới đây
	 english:	  Status: suspend. To be active administrator account, Please you declare in the box below
*/

$lang_module['chg_is_suspend1'] = "Le status actuel: actif. Pour suspendre l'activité de cet administrateur, remplissez les champs ci-dessous";
/*
	 vietnam:	  Tình trạng hiện tại: Đang hoạt động. Để Đình chỉ hoạt động của tài khoản quản trị này, bạn hãy khai báo vào các ô trống dưới đây
	 english:	  Status: active. To suspend this administrator account, Please declare the box below
*/

$lang_module['chg_is_suspend2'] = "Rétablir/Suspendre l'activité";
/*
	 vietnam:	  Khôi phục/Đình chỉ hoạt động
	 english:	  Re-active/Suspend
*/

$lang_module['nv_admin_chg_suspend'] = "Changer le status d'activité de l'administrateur &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Thay đổi trạng thái hoạt động của tài khoản Quản trị &ldquo;<strong>%s</strong>&rdquo;
	 english:	  Change status administrator account &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['position_info'] = "Le titre de Fonction est utilisé dans la communication des e-mails, des commentaires...";
/*
	 vietnam:	  Chức danh dùng trong các hoạt động đối ngoại như trao đổi thư từ, viết lời bình...
	 english:	  Position is used for external activities such as mail exchange , written comments...
*/

$lang_module['susp_reason_empty'] = "Vous n'avez pas donné la raison de la suspension de l'administrateur &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Bạn chưa khai báo lý do đình chỉ hoạt động của tài khoản Quản trị &ldquo;<strong>%s</strong>&rdquo;
	 english:	  You do not declare the reason for suspending Administrator account&ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['suspend_info_empty'] = "Cet administrateur &ldquo;<strong>%s</strong>&rdquo; n'est jamais suspendu";
/*
	 vietnam:	  Tài khoản quản trị &ldquo;<strong>%s</strong>&rdquo; chưa bị đình chỉ hoạt động lần nào
	 english:	  Administrator account &ldquo;<strong>%s</strong>&rdquo; not be suspended any time
*/

$lang_module['suspend_info_yes'] = "La liste des suspensions d'activité &ldquo;<strong>%s</strong>&rdquo;";
/*
	 vietnam:	  Danh sách các lần đình chỉ hoạt động của tài khoản quản trị &ldquo;<strong>%s</strong>&rdquo;
	 english:	  List of times to suspend the operation of the Administrator Account &ldquo;<strong>%s</strong>&rdquo;
*/

$lang_module['suspend_start'] = "Commencer";
/*
	 vietnam:	  Bắt đầu
	 english:	  Start
*/

$lang_module['suspend_end'] = "Terminer";
/*
	 vietnam:	  Kết thúc
	 english:	  Finish
*/

$lang_module['suspend_reason'] = "Raison de suspension";
/*
	 vietnam:	  Lý do đình chỉ
	 english:	  Suspending reason
*/

$lang_module['suspend_info'] = "À: %1\$s<br />Par: %2\$s";
/*
	 vietnam:	  Vào: %1\$s<br />Bởi: %2\$s
	 english:	  At: %1\$s<br />By: %2\$s
*/

$lang_module['suspend0'] = "Rétablir l'activité";
/*
	 vietnam:	  Khôi phục hoạt động
	 english:	  Active
*/

$lang_module['suspend1'] = "Suspendre l'activité";
/*
	 vietnam:	  Đình chỉ hoạt động
	 english:	  Suspend
*/

$lang_module['clean_history'] = "Supprimer l'historique";
/*
	 vietnam:	  Xóa lịch sử
	 english:	  Clear history
*/

$lang_module['suspend_sendmail'] = "Envoyer la notification";
/*
	 vietnam:	  Gửi thông báo
	 english:	  Send notify
*/

$lang_module['suspend_sendmail_mess1'] = "L'administrateur du site %1\$s informe:<br />Votre compte d'administration sur le site %1\$s est suspendu au %2\$s en raison: %3\$s.<br />Toute proposition, question... merci d'envoyer à l'adresse %4\$s";
/*
	 vietnam:	  Ban quản trị website %1\$s xin thông báo:<br />Tài khoản quản trị của bạn tại website %1\$s đã bị đình chỉ hoạt động vào %2\$s vì lý do: %3\$s.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ %4\$s
	 english:	  Information from %1\$s Aministrators:<br />Your administrator account %1\$s is suspended %2\$s reason: %3\$s.<br />If you have any questions... Email: %4\$s
*/

$lang_module['suspend_sendmail_mess0'] = "L'administration du site %1\$s informe:<br />Votre compte d'administration sur le site %1\$s est rétabli au %2\$s.<br />Ce compte avait été suspendu en raison: %3\$s";
/*
	 vietnam:	  Ban quản trị website %1\$s xin thông báo:<br />Tài khoản quản trị của bạn tại website %1\$s đã hoạt động trở lại vào %2\$s.<br />Trước đó tài khoản này đã bị đình chỉ hoạt động vì lý do: %3\$s
	 english:	  Information from %1\$s Aministrators:<br />Your administrator account %1\$s is active at%2\$s.<br />Your account has been suspended because:: %3\$s
*/

$lang_module['suspend_sendmail_title'] = "Notification du site %s";
/*
	 vietnam:	  Thông báo từ website %s
	 english:	  Website notify %s
*/

$lang_module['delete_sendmail_mess0'] = "L'administrateur du site %1\$s informe:<br />Votre compte d'administration sur le site %1\$s est supprimé au %2\$s.<br />Toute proposition, question... merci d'envoyer à l'adresse %3\$s";
/*
	 vietnam:	  Ban quản trị website %1\$s xin thông báo:<br />Tài khoản quản trị của bạn tại website %1\$s đã bị xóa vào %2\$s.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ %3\$s
	 english:	  Administrator %1\$s notify:<br />Your administrator account in %1\$s website deleted  %2\$s.<br />If you have any questions... Email %3\$s
*/

$lang_module['delete_sendmail_mess1'] = "L'administrateur du site %1\$s informe:<br />Votre compte d'administration sur le site %1\$s est supprimé au %2\$s en raison de: %3\$s.<br />Toute proposition, question... merci d'envoyer à l'adresse %4\$s";
/*
	 vietnam:	  Ban quản trị website %1\$s xin thông báo:<br />Tài khoản quản trị của bạn tại website %1\$s đã bị xóa vào %2\$s vì lý do: %3\$s.<br />Mọi đề nghị, thắc mắc... xin gửi đến địa chỉ %4\$s
	 english:	  Administrator %1\$s website notify:<br />Your administrator account in %1\$s website deleted %2\$s Reason: %3\$s.<br />If you have any questions... Email %4\$s
*/

$lang_module['delete_sendmail_title'] = "Notification du site %s";
/*
	 vietnam:	  Thông báo từ website %s
	 english:	  Information from %s website
*/

$lang_module['delete_sendmail_info'] = "Êtes vous sûr de vouloir supprimer le compte d'administration &ldquo;<strong>%s</strong>&rdquo;? Remplissez les infos aux champs ci-dessous pour confirmer cette opération";
/*
	 vietnam:	  Bạn thực sự muốn xóa tài khoản quản trị &ldquo;<strong>%s</strong>&rdquo;? Hãy điền các thông tin vào các ô trống dưới đây để khẳng định thao tác này
	 english:	  Do you really want to delete the administrator account &ldquo;<strong>%s</strong>&rdquo;? Please fill in box below to confirm
*/

$lang_module['admin_del_sendmail'] = "Envoyer la notification";
/*
	 vietnam:	  Gửi thông báo
	 english:	  Send notify
*/

$lang_module['admin_del_reason'] = "Raison de la suppression";
/*
	 vietnam:	  Lý do xóa
	 english:	  Reason
*/

$lang_module['allow_files_type'] = "Les types de fichiers authorisés";
/*
	 vietnam:	  Các kiểu file được phép tải lên
	 english:	  The file types are allowed to upload
*/

$lang_module['allow_modify_files'] = "Authoriser la modification, la suppression des fichiers";
/*
	 vietnam:	  Được phép sửa, xóa files
	 english:	  Allow to edit,delete
*/

$lang_module['allow_create_subdirectories'] = "Authoriser la création des fichiers";
/*
	 vietnam:	  Được phép tạo thư mục
	 english:	  Allow to create directory
*/

$lang_module['allow_modify_subdirectories'] = "Authoriser la modification, la suppression des répertoires";
/*
	 vietnam:	  Được phép đổi tên, xóa thư mục
	 english:	  Allow to change name, delete folder
*/

$lang_module['admin_login_incorrect'] = "Le compte &ldquo;<strong>%s</strong>&rdquo; a été utilisé. Veuillez utiliser un autre nom";
/*
	 vietnam:	  Tài khoản &ldquo;<strong>%s</strong>&rdquo; đã có trong danh sách quản trị. Hãy sử dụng một tài khoản khác
	 english:	  Account &ldquo;<strong>%s</strong>&rdquo; already exist. Please use other account
*/

$lang_module['config'] = "Configuration";
/*
	 vietnam:	  Cấu hình
	 english:	  Configuration
*/

$lang_module['adminip'] = "Gestion de IP connecté à l'administration";
/*
	 vietnam:	  Quản lý IP truy cập khu vực quản trị
	 english:	  Admin IP
*/

$lang_module['adminip_ip'] = "IP";
/*
	 vietnam:	  Ip
	 english:	  IP
*/

$lang_module['adminip_timeban'] = "Commencer";
/*
	 vietnam:	  Thời gian bắt đầu
	 english:	  Start
*/

$lang_module['adminip_timeendban'] = "Terminer";
/*
	 vietnam:	  Thời gian kết thúc
	 english:	  Finish
*/

$lang_module['adminip_funcs'] = "Fonctionalités";
/*
	 vietnam:	  Chức năng
	 english:	  Functions
*/

$lang_module['adminip_checkall'] = "Sélectionner tout";
/*
	 vietnam:	  Chọn tất cả
	 english:	  Check all
*/

$lang_module['adminip_uncheckall'] = "Désélectionner tout";
/*
	 vietnam:	  Bỏ chọn tất cả
	 english:	  Uncheck all
*/

$lang_module['adminip_add'] = "Ajouter IP";
/*
	 vietnam:	  Thêm địa chỉ IP
	 english:	  Add IP
*/

$lang_module['adminip_address'] = "Adresse";
/*
	 vietnam:	  Địa chỉ
	 english:	  Adresss
*/

$lang_module['adminip_begintime'] = "Commencer";
/*
	 vietnam:	  Thời gian bắt đầu
	 english:	  Start
*/

$lang_module['adminip_endtime'] = "Terminer";
/*
	 vietnam:	  Thời gian bắt kết thúc
	 english:	  Finish
*/

$lang_module['adminip_notice'] = "Note";
/*
	 vietnam:	  Ghi chú
	 english:	  Note
*/

$lang_module['save'] = "Sauver";
/*
	 vietnam:	  Lưu thay đổi
	 english:	  Save
*/

$lang_module['adminip_mask_select'] = "Sélectionner";
/*
	 vietnam:	  Hãy chọn
	 english:	  Please select
*/

$lang_module['adminip_nolimit'] = "Perpétuel";
/*
	 vietnam:	  Vô thời hạn
	 english:	  Unlimited
*/

$lang_module['adminip_del_success'] = "Suppression avec succès!";
/*
	 vietnam:	  Đã xóa thành công !
	 english:	  Successfully
*/

$lang_module['adminip_delete_confirm'] = "Êtes-vous sûr de vouloir supprimer cet IP de la liste?";
/*
	 vietnam:	  Bạn có chắc muốn xóa ip này ra khỏi danh sách?
	 english:	  Are you sure you want to remove this IP?
*/

$lang_module['adminip_mask'] = "Masque IP";
/*
	 vietnam:	  Mask IP
	 english:	  IP Mask
*/

$lang_module['adminip_edit'] = "Éditer";
/*
	 vietnam:	  Sửa địa chỉ IP
	 english:	  Edit
*/

$lang_module['adminip_delete'] = "Supprimer";
/*
	 vietnam:	  Xóa
	 english:	  Delete
*/

$lang_module['adminip_error_ip'] = "Saisissez IP autorisé de connecter à l'administration";
/*
	 vietnam:	  Hãy nhập Ip được truy cập khu vực quản trị 
	 english:	  Please enter admin IP
*/

$lang_module['adminip_error_validip'] = "Erreur: IP invalide";
/*
	 vietnam:	  Lỗi: Bạn cần nhập IP đúng chuẩn
	 english:	  Invalid IP
*/

$lang_module['adminip_note'] = "Attention: si vous activez le contrôle de IP, vous devez connaitre la structure des adresses IP: un IP se compose de 4 nombres sous forme A.B.C.D. Si tous ces nombres sont constants, sélectionnez le Masque 255.255.255.255. Si A, B  et C sont invariables, il faut choisir 255.255.255.xxx etc...";
/*
	 vietnam:	  Chú ý: nếu chọn chế độ Kiểm tra IP, bạn cần nắm được cấu trúc sau: Mỗi số IP được xác định bằng 4 đại lượng A.B.C.D. Nếu số IP của bạn có cả 4 đại lượng A, B, C, D bất biến, hãy chọn giá trị của dòng 'Mask IP' là 255.255.255.255. Nếu chỉ có A, B, C cố định - chọn 255.255.255.xxx v.v...
	 english:	  Note: You need basic knowledge about networking to use check IP features!
*/

$lang_module['title_username'] = "Gestion de compte Parefeu de l'Administration";
/*
	 vietnam:	  Quản lý tài khoản tường lửa khu vực admin
	 english:	  Manage firewall account
*/

$lang_module['admfirewall'] = "Contrôler le Parefeu de l'Administration";
/*
	 vietnam:	  Kiểm tra tường lửa cho khu vực admin
	 english:	  Check firewall
*/

$lang_module['block_admin_ip'] = "Vérifier IP lors de la connexion de l'Administration";
/*
	 vietnam:	  Kiểm tra IP khi truy cập khu vực admin
	 english:	  Check IP
*/

$lang_module['username_add'] = "Ajouter un compte";
/*
	 vietnam:	  Thêm tài khoản
	 english:	  Add
*/

$lang_module['username_edit'] = "Éditer";
/*
	 vietnam:	  Sửa tài khoản
	 english:	  Edit
*/

$lang_module['nicknam_delete_confirm'] = "Êtes-vous sûr de vouloir supprimer ce compte de la liste?";
/*
	 vietnam:	  Bạn có chắc muốn xóa tài khoản này ra khỏi danh sách?
	 english:	  Are you sure you want to remove this account?
*/

$lang_module['passwordsincorrect'] = "Vous avez entré 2 mot de passe différents";
/*
	 vietnam:	  Mật khẩu bạn nhập hai lần không giống nhau.
	 english:	  Password does not match
*/

$lang_module['nochangepass'] = "Laisser vide 2 champs de mot de passe si vous ne voulez pas changer le mot de passe";
/*
	 vietnam:	  Nếu không thay đổi mật khẩu bạn không nhập hai trường mật khẩu
	 english:	  Leave password fields blank if you don't want to change password
*/

$lang_module['rule_user'] = "Utiliser uniquement les caractères a-zA-Z0-9_- pour le compte";
/*
	 vietnam:	  Tài khoản chỉ dùng các ký tự a-zA-Z0-9_-
	 english:	  Account only accept letters, numbers and underscore
*/

$lang_module['rule_pass'] = "Utiliser uniquement les caractères a-zA-Z0-9_- pour le mot de passe";
/*
	 vietnam:	  Mật khẩu chỉ dùng các ký tự a-zA-Z0-9_-
	 english:	  Password only accept letters, numbers and underscore
*/

$lang_module['spadmin_add_admin'] = "Autoriser l'administrateur général de changer les droits des administrateurs de module";
/*
	 vietnam:	  Cho phép người điều hành chung tạo và thay đổi quyền hạn người điều hành modules
	 english:	  Allowing operators to create and change the general powers of administration modules
*/

$lang_module['authors_detail_main'] = "Afficher les détails du compte de l'administrateur";
/*
	 vietnam:	  Hiển thị chi tiết các thông tin tài khoản của người quản trị
	 english:	  Displays detailed information of the administrator account
*/


?>