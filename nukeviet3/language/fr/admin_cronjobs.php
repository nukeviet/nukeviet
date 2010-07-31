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

$lang_module['main'] = "Liste des tâches";
/*
	 vietnam:	  Danh sách công việc
	 english:	  Jobs list
*/

$lang_module['nv_admin_add'] = "Ajouter une tâche";
/*
	 vietnam:	  Thêm công việc
	 english:	  Add job
*/

$lang_module['nv_admin_edit'] = "Modifier la tâche";
/*
	 vietnam:	  Sửa công việc
	 english:	  Edit job
*/

$lang_module['nv_admin_del'] = "Supprimer la tâche";
/*
	 vietnam:	  Xóa công việc
	 english:	  Delete job
*/

$lang_module['cron_name_empty'] = "Vous n'avez pas encore donné le nom de la tâche";
/*
	 vietnam:	  Bạn chưa khai báo tên của công việc
	 english:	  You do not declare the name of the job
*/

$lang_module['file_not_exist'] = "Fichier inexistant";
/*
	 vietnam:	  File mà bạn khai báo không tồn tại
	 english:	  File does not exist
*/

$lang_module['func_name_invalid'] = "Vous n'avez pas déclaré la fonction ou le nom de fonction est invalide";
/*
	 vietnam:	  Bạn chưa khai báo tên hàm hoặc tên hàm không đúng quy định
	 english:	  You do not declare function's name or function's name is invalid
*/

$lang_module['nv_admin_add_title'] = "Pour ajouter une tâche, remplissez les champs ci-dessous";
/*
	 vietnam:	  Để thêm công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây
	 english:	  To add job, you need to declare fully the box below
*/

$lang_module['nv_admin_edit_title'] = "Pour modifier la tâche, remplissez les champs ci-dessous";
/*
	 vietnam:	  Để sửa công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây
	 english:	  To edit job, you need to declare fully the box below
*/

$lang_module['cron_name'] = "Nom de la tâche";
/*
	 vietnam:	  Tên công việc
	 english:	  Job name
*/

$lang_module['file_none'] = "Pas d'accès";
/*
	 vietnam:	  Không kết nối
	 english:	  Not conected
*/

$lang_module['run_file'] = "Accès au fichier d'exécution";
/*
	 vietnam:	  Kết nối với file thực thi
	 english:	  Conected file
*/

$lang_module['run_file_info'] = "Fichier d'exécution est un des fichiers dans le répertoire &ldquo;<strong>includes/cronjobs/</strong>&rdquo;";
/*
	 vietnam:	  File thực thi phải là một trong những file được chứa trong thư mục &ldquo;<strong>includes/cronjobs/</strong>&rdquo;
	 english:	  Executable file is  contained in the directory &ldquo;<strong>includes/cronjobs/</strong>&rdquo;
*/

$lang_module['run_func'] = "Accès à la fonction";
/*
	 vietnam:	  Kết nối với hàm thực thi
	 english:	  Conect function
*/

$lang_module['run_func_info'] = "Fonction doit être commencée par &ldquo;<strong>cron_</strong>&rdquo;";
/*
	 vietnam:	  Hàm thực thi phải được bắt đầu bằng &ldquo;<strong>cron_</strong>&rdquo;
	 english:	  Function must be beginning with &ldquo;<strong>cron_</strong>&rdquo;
*/

$lang_module['params'] = "Paramètre";
/*
	 vietnam:	  Thông số
	 english:	  Parameter
*/

$lang_module['params_info'] = "Séparer par la virgule";
/*
	 vietnam:	  Phân cách bởi dấu phẩy
	 english:	  Separated by commas
*/

$lang_module['interval'] = "Répêter la tâche après";
/*
	 vietnam:	  Lặp lại công việc sau
	 english:	  Repeat following jobs
*/

$lang_module['interval_info'] = "Si vous entrez &ldquo;<strong>0</strong>&rdquo;, la tâche est faite une seule fois";
/*
	 vietnam:	  Nếu chọn &ldquo;<strong>0</strong>&rdquo;, công việc sẽ được thực hiện 1 lần duy nhất
	 english:	  If choice &ldquo;<strong>0</strong>&rdquo;, Job will be done one time only
*/

$lang_module['start_time'] = "Commencer à";
/*
	 vietnam:	  Thời gian bắt đầu
	 english:	  Start time
*/

$lang_module['min'] = "minutes";
/*
	 vietnam:	  phút
	 english:	  minute
*/

$lang_module['hour'] = "heures";
/*
	 vietnam:	  giờ
	 english:	  hours
*/

$lang_module['day'] = "jours";
/*
	 vietnam:	  ngày
	 english:	  day
*/

$lang_module['month'] = "mois";
/*
	 vietnam:	  tháng
	 english:	  month
*/

$lang_module['year'] = "an";
/*
	 vietnam:	  năm
	 english:	  year
*/

$lang_module['is_del'] = "Supprimer après avoir terminé";
/*
	 vietnam:	  Xóa sau khi thực hiện xong
	 english:	  Delete after you're done
*/

$lang_module['isdel'] = "Supprimer";
/*
	 vietnam:	  Xóa
	 english:	  Delete
*/

$lang_module['notdel'] = "Non";
/*
	 vietnam:	  Không
	 english:	  Not delete
*/

$lang_module['is_sys'] = "Tâche créée par";
/*
	 vietnam:	  Công việc được tạo bởi
	 english:	  Jobs is created by
*/

$lang_module['system'] = "Système";
/*
	 vietnam:	  Hệ thống
	 english:	  System
*/

$lang_module['client'] = "Administrateur";
/*
	 vietnam:	  Admin
	 english:	  Admin
*/

$lang_module['act'] = "Status";
/*
	 vietnam:	  Tình trạng
	 english:	  Status
*/

$lang_module['act0'] = "Inactif";
/*
	 vietnam:	  Vô hiệu lực
	 english:	  Suspend
*/

$lang_module['act1'] = "Actif";
/*
	 vietnam:	  Hiệu lực
	 english:	  Active
*/

$lang_module['last_time'] = "Dernière exécution";
/*
	 vietnam:	  Lần thực hiện gần đây
	 english:	  Last time
*/

$lang_module['next_time'] = "Prochaine exécution";
/*
	 vietnam:	  Lần thực hiện sắp tới
	 english:	  Next time
*/

$lang_module['last_time0'] = "Jamais exécuté";
/*
	 vietnam:	  Chưa thực hiện lần nào
	 english:	  None executable
*/

$lang_module['last_result'] = "Résultat de la dernière exécution";
/*
	 vietnam:	  Kết quả của lần thực hiện gần đây
	 english:	  Last result
*/

$lang_module['last_result_empty'] = "non déterminé";
/*
	 vietnam:	  n/a
	 english:	  n/a
*/

$lang_module['last_result0'] = "Mauvais";
/*
	 vietnam:	  Tồi
	 english:	  Bad
*/

$lang_module['last_result1'] = "Terminé";
/*
	 vietnam:	  Đã hoàn thành
	 english:	  Finished
*/


?>