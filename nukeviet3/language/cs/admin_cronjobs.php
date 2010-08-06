<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language česky
* @Createdate Aug 06, 2010, 09:58:35 AM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="http://datviet.cz";
$lang_translator['createdate'] ="01/08/2010, 21:40";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC.. All rights reserved";
$lang_translator['info'] ="YM: datvietinfo2010 ";
$lang_translator['langtype'] ="lang_module";

$lang_module['main'] = "Zaměstnání seznam";
/*
	 vietnam:	  Danh sách công việc
	 english:	  Jobs list
*/

$lang_module['nv_admin_add'] = "Přidat zaměstnání";
/*
	 vietnam:	  Thêm công việc
	 english:	  Add job
*/

$lang_module['nv_admin_edit'] = "Upravit zaměstnání";
/*
	 vietnam:	  Sửa công việc
	 english:	  Edit job
*/

$lang_module['nv_admin_del'] = "Odstranit zaměstnání";
/*
	 vietnam:	  Xóa công việc
	 english:	  Delete job
*/

$lang_module['cron_name_empty'] = "Nemáte prohlásit název práci";
/*
	 vietnam:	  Bạn chưa khai báo tên của công việc
	 english:	  You do not declare the name of the job
*/

$lang_module['file_not_exist'] = "Soubor neexistuje";
/*
	 vietnam:	  File mà bạn khai báo không tồn tại
	 english:	  File does not exist
*/

$lang_module['func_name_invalid'] = "Nemáte deklarovat název funkce, jméno nebo jméno funkce je neplatný";
/*
	 vietnam:	  Bạn chưa khai báo tên hàm hoặc tên hàm không đúng quy định
	 english:	  You do not declare function's name or function's name is invalid
*/

$lang_module['nv_admin_add_title'] = "Chcete-li přidat práci, musíte doplnit všechny pole";
/*
	 vietnam:	  Để thêm công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây
	 english:	  To add job, you need to declare fully the box below
*/

$lang_module['nv_admin_edit_title'] = "Chcete-li upravit úlohu, je třeba, abyste prohlásili v těch polích";
/*
	 vietnam:	  Để sửa công việc, bạn cần khai báo đầy đủ vào các ô trống dưới đây
	 english:	  To edit job, you need to declare fully the box below
*/

$lang_module['cron_name'] = "Zaměstnání název";
/*
	 vietnam:	  Tên công việc
	 english:	  Job name
*/

$lang_module['file_none'] = "Nelze připojit";
/*
	 vietnam:	  Không kết nối
	 english:	  Not conected
*/

$lang_module['run_file'] = "Lze připojit soubor";
/*
	 vietnam:	  Kết nối với file thực thi
	 english:	  Conected file
*/

$lang_module['run_file_info'] = "Spustitelný soubor je obsazen v adresáři \"<strong> includes / cronjobs / </ strong>\"";
/*
	 vietnam:	  File thực thi phải là một trong những file được chứa trong thư mục &ldquo;<strong>includes/cronjobs/</strong>&rdquo;
	 english:	  Executable file is  contained in the directory &ldquo;<strong>includes/cronjobs/</strong>&rdquo;
*/

$lang_module['run_func'] = "Připojit funkce";
/*
	 vietnam:	  Kết nối với hàm thực thi
	 english:	  Conect function
*/

$lang_module['run_func_info'] = "Funkce musí být začínající \"<strong> cron_ </ strong>\"";
/*
	 vietnam:	  Hàm thực thi phải được bắt đầu bằng &ldquo;<strong>cron_</strong>&rdquo;
	 english:	  Function must be beginning with &ldquo;<strong>cron_</strong>&rdquo;
*/

$lang_module['params'] = "Parametr";
/*
	 vietnam:	  Thông số
	 english:	  Parameter
*/

$lang_module['params_info'] = "Oddělené čárkami";
/*
	 vietnam:	  Phân cách bởi dấu phẩy
	 english:	  Separated by commas
*/

$lang_module['interval'] = "Opakujte práci";
/*
	 vietnam:	  Lặp lại công việc sau
	 english:	  Repeat following jobs
*/

$lang_module['interval_info'] = "Pokud si vybere \"<strong> 0 </ strong>\", bude práce provádět pouze jednou";
/*
	 vietnam:	  Nếu chọn &ldquo;<strong>0</strong>&rdquo;, công việc sẽ được thực hiện 1 lần duy nhất
	 english:	  If choice &ldquo;<strong>0</strong>&rdquo;, Job will be done one time only
*/

$lang_module['start_time'] = "Start čas";
/*
	 vietnam:	  Thời gian bắt đầu
	 english:	  Start time
*/

$lang_module['min'] = "minuta";
/*
	 vietnam:	  phút
	 english:	  minute
*/

$lang_module['hour'] = "hodina";
/*
	 vietnam:	  giờ
	 english:	  hours
*/

$lang_module['day'] = "den";
/*
	 vietnam:	  ngày
	 english:	  day
*/

$lang_module['month'] = "měsíc";
/*
	 vietnam:	  tháng
	 english:	  month
*/

$lang_module['year'] = "rok";
/*
	 vietnam:	  năm
	 english:	  year
*/

$lang_module['is_del'] = "Smazat po dokončení";
/*
	 vietnam:	  Xóa sau khi thực hiện xong
	 english:	  Delete after you're done
*/

$lang_module['isdel'] = "Vymazat";
/*
	 vietnam:	  Xóa
	 english:	  Delete
*/

$lang_module['notdel'] = "Nelze odstranit";
/*
	 vietnam:	  Không
	 english:	  Not delete
*/

$lang_module['is_sys'] = "Práce je vytvořeno";
/*
	 vietnam:	  Công việc được tạo bởi
	 english:	  Jobs is created by
*/

$lang_module['system'] = "Systém";
/*
	 vietnam:	  Hệ thống
	 english:	  System
*/

$lang_module['client'] = "Admin";
/*
	 vietnam:	  Admin
	 english:	  Admin
*/

$lang_module['act'] = "Stav";
/*
	 vietnam:	  Tình trạng
	 english:	  Status
*/

$lang_module['act0'] = "Pozastavit";
/*
	 vietnam:	  Vô hiệu lực
	 english:	  Suspend
*/

$lang_module['act1'] = "Aktivovat";
/*
	 vietnam:	  Hiệu lực
	 english:	  Active
*/

$lang_module['last_time'] = "Poslední vytvoření";
/*
	 vietnam:	  Lần thực hiện gần đây
	 english:	  Last time
*/

$lang_module['next_time'] = "Další vytvoření";
/*
	 vietnam:	  Lần thực hiện sắp tới
	 english:	  Next time
*/

$lang_module['last_time0'] = "Nespustitelný";
/*
	 vietnam:	  Chưa thực hiện lần nào
	 english:	  None executable
*/

$lang_module['last_result'] = "Poslední výsledek";
/*
	 vietnam:	  Kết quả của lần thực hiện gần đây
	 english:	  Last result
*/

$lang_module['last_result_empty'] = "n / a";
/*
	 vietnam:	  n/a
	 english:	  n/a
*/

$lang_module['last_result0'] = "Špatný";
/*
	 vietnam:	  Tồi
	 english:	  Bad
*/

$lang_module['last_result1'] = "Hotový";
/*
	 vietnam:	  Đã hoàn thành
	 english:	  Finished
*/


?>