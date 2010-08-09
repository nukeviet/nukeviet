<?php

/**
* @Project NUKEVIET 3.0
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2010 VINADES.,JSC. All rights reserved
* @Language česky
* @Createdate Aug 08, 2010, 11:22:13 PM
*/

 if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){
 die('Stop!!!');
}

$lang_translator['author'] ="http://datviet.cz";
$lang_translator['createdate'] ="01/08/2010, 21:40";
$lang_translator['copyright'] ="@Copyright (C) 2010 VINADES.,JSC.. All rights reserved";
$lang_translator['info'] ="YM: datvietinfo2010 ";
$lang_translator['langtype'] ="lang_module";

$lang_module['blocks'] = "Nastavení blok";
/*
	 vietnam:	  Quản lý block
	 english:	  Setup block
*/

$lang_module['change_func_name'] = "Změnit názvu funkce \"%1\$s\" v modulu \"%2\$s\"";
/*
	 vietnam:	  Thay đổi tên gọi của function &ldquo;%1\$s&rdquo; thuộc module &ldquo;%2\$s&rdquo;
	 english:	  Change the name of the function &ldquo;%1\$s&rdquo; in module &ldquo;%2\$s&rdquo;
*/

$lang_module['bl_list_title'] = "Bloky v \"%1\$s\" je funkce \"%2\$s\"";
/*
	 vietnam:	  Các block ở &ldquo;%1\$s&rdquo; của function &ldquo;%2\$s&rdquo;
	 english:	  Blocks in &ldquo;%1\$s&rdquo; is of function &ldquo;%2\$s&rdquo;
*/

$lang_module['add_block_title'] = "Přidat bloku do \"%1\$s\" funkce \"%2\$s\" v modulu \"%3\$s\"";
/*
	 vietnam:	  Thêm block vào &ldquo;%1\$s&rdquo; của function &ldquo;%2\$s&rdquo; thuộc module &ldquo;%3\$s&rdquo;
	 english:	  Add block &ldquo;%1\$s&rdquo; of function &ldquo;%2\$s&rdquo; in module &ldquo;%3\$s&rdquo;
*/

$lang_module['edit_block_title'] = "Upravit blok \"%1\$s\" na \"%2\$s\" z funkce \"%3\$s\" v modulu \"%4\$s\"";
/*
	 vietnam:	  Sửa block &ldquo;%1\$s&rdquo; tại &ldquo;%2\$s&rdquo; của function &ldquo;%3\$s&rdquo; thuộc module &ldquo;%4\$s&rdquo;
	 english:	  Edit block &ldquo;%1\$s&rdquo; at &ldquo;%2\$s&rdquo; of function &ldquo;%3\$s&rdquo; of module &ldquo;%4\$s&rdquo;
*/

$lang_module['block_add'] = "Přidat bloku";
/*
	 vietnam:	  Thêm block
	 english:	  Add block
*/

$lang_module['block_edit'] = "Upravit blok";
/*
	 vietnam:	  Sửa block
	 english:	  Edit block
*/

$lang_module['block_title'] = "Jméno blocku";
/*
	 vietnam:	  Tên block
	 english:	  Block name
*/

$lang_module['block_link'] = "URL bloku";
/*
	 vietnam:	  URL của tên block
	 english:	  URL of block
*/

$lang_module['block_file_path'] = "Získat obsah ze souboru";
/*
	 vietnam:	  Lấy nội dung từ file
	 english:	  Get contents from file
*/

$lang_module['block_global_apply'] = "Platit pro všechny";
/*
	 vietnam:	  Áp dụng cho tất cả
	 english:	  Apply to all
*/

$lang_module['block_type_global'] = "Globál";
/*
	 vietnam:	  Global
	 english:	  Global
*/

$lang_module['block_select_type'] = "Vyberte formát";
/*
	 vietnam:	  Hãy chọn dạng
	 english:	  Select format
*/

$lang_module['block_tpl'] = "Šablona";
/*
	 vietnam:	  Template
	 english:	  Template
*/

$lang_module['block_pos'] = "Pozice";
/*
	 vietnam:	  Vị trí
	 english:	  Position
*/

$lang_module['block_groupbl'] = "Ve skupině";
/*
	 vietnam:	  Thuộc nhóm
	 english:	  In group
*/

$lang_module['block_leavegroup'] = "Splitovat se ze skupiny a vytvořit nové skupiny";
/*
	 vietnam:	  Tách ra khỏi nhóm và tạo nhóm mới
	 english:	  Split from the group and create a new group
*/

$lang_module['block_group_notice'] = "Poznámka: <br /> Pokud změníte blok skupiny pak změní všechny další bloky v této skupině. <br/> Pokud nechcete měnit další skupiny, ale chtěli rozdělit blok do nové skupiny, zvolte tlačítko Rozdělit a vytvořte novou skupinu.";
/*
	 vietnam:	  Lưu ý: <br />Nếu thay đổi một block thuộc 1 nhóm thì sẽ thay đổi toàn bộ các block khác thuộc nhóm đó. <br/>Nếu không muốn thay đổi các block khác cùng nhóm nhưng muốn tách các block ra thành nhóm mới hãy check vào nút Tách ra khỏi nhóm và tạo nhóm mới.
	 english:	  Note: <br /> If you change a block of a group then you will change all other blocks in that group. <br/> If not want to change the other blocks of group but wanted to split the block into a new group, please check out the group split button and create new group.
*/

$lang_module['block_group_block'] = "Skupina";
/*
	 vietnam:	  Nhóm
	 english:	  Group
*/

$lang_module['block_no_more_func'] = "Pokud nevybrat funkci ze skupiny, pak pouze jedna funkce je vybrána";
/*
	 vietnam:	  Nếu check chọn Bỏ ra khỏi nhóm thì chỉ được chọn 1 function
	 english:	  If not to choose functions from the group  then only one function is selected
*/

$lang_module['block_no_func'] = "Prosím, vyberte alespoň jednu funkci";
/*
	 vietnam:	  Hãy chọn ít nhất là 1 function
	 english:	  Please select at least one function
*/

$lang_module['block_limit_func'] = "Pokud potvrdit nevybírat pak pouze jedna funkce je vybrána pro bloky";
/*
	 vietnam:	  Nếu xác nhận bỏ nhóm thì chỉ được chỉ định 1 function cho cho 1 blocks
	 english:	  If confirmed not choosed then only one function is selected for a blocks
*/

$lang_module['block_func'] = "Zóna";
/*
	 vietnam:	  Khu vực
	 english:	  Zone
*/

$lang_module['block_nums'] = "Počet skupiny bloku";
/*
	 vietnam:	  Số block thuộc nhóm
	 english:	  Number of group block
*/

$lang_module['block_count'] = "blok";
/*
	 vietnam:	  block
	 english:	  block
*/

$lang_module['block_func_list'] = "Funkce";
/*
	 vietnam:	  Các function
	 english:	  Functions
*/

$lang_module['blocks_by_funcs'] = "Management bloky sleduje funkci";
/*
	 vietnam:	  Quản lý block theo function
	 english:	  Management blocks to follow the function
*/

$lang_module['block_yes'] = "Ano";
/*
	 vietnam:	  Có
	 english:	  Yes
*/

$lang_module['block_active'] = "Aktivní";
/*
	 vietnam:	  Kích hoạt
	 english:	  Active
*/

$lang_module['block_group'] = "Kdo se může zobrazit";
/*
	 vietnam:	  Ai có quyền xem
	 english:	  Who can view
*/

$lang_module['block_module'] = "Zobrazit v modulu";
/*
	 vietnam:	  Hiển thị ở module
	 english:	  Display in module
*/

$lang_module['block_all'] = "Všechny moduly";
/*
	 vietnam:	  Tất cả các module
	 english:	  All modules
*/

$lang_module['block_confirm'] = "Přijmout";
/*
	 vietnam:	  Chấp nhận
	 english:	  Accept
*/

$lang_module['block_filename'] = "Vyberte soubor";
/*
	 vietnam:	  Chọn file
	 english:	  Select file
*/

$lang_module['block_default'] = "Prodlení";
/*
	 vietnam:	  Mặc định
	 english:	  Default
*/

$lang_module['block_exp_time'] = "Uplynula doba";
/*
	 vietnam:	  Ngày hết hạn
	 english:	  Expired time
*/

$lang_module['block_content'] = "Nebo obsah";
/*
	 vietnam:	  Hoặc có nội dung sau
	 english:	  Or content
*/

$lang_module['block_sort'] = "Typ";
/*
	 vietnam:	  Sắp xếp
	 english:	  Sort
*/

$lang_module['block_change_pos_warning'] = "Máte-li změnit pozice tohoto bloku bude změnit pozice všech ostatních bloků ve stejné skupině";
/*
	 vietnam:	  Nếu thay đổi vị trí block này sẽ thay đổi toàn bộ vị trí của các block khác thuộc cùng nhóm
	 english:	  If you change the position of this block will change the positions all of the other blocks in the same group
*/

$lang_module['block_change_pos_warning2'] = "Chcete-li změnit pozici?";
/*
	 vietnam:	  Bạn có chắc muốn thay đổi vị trí?
	 english:	  Do you want to change position?
*/

$lang_module['block_error_nogroup'] = "Prosím, vyberte nejméně 1 skupina";
/*
	 vietnam:	  Hãy chọn ít nhất 1 nhóm
	 english:	  Please select at least 1 group
*/

$lang_module['block_error_noblock'] = "Prosím, vyberte nejméně 1 blok";
/*
	 vietnam:	  Hãy chọn ít nhất 1 block
	 english:	  Please select at least 1 block
*/

$lang_module['block_delete_confirm'] = "Jste jisti, že chcete-li odstranit všechny bloky ze skupiny, pokud to ostranite tak nebude možné obnovit?";
/*
	 vietnam:	  Bạn có chắc muốn xóa tất cả block thuộc nhóm không. Nếu xóa việc này sẽ không thể phục hồi được?
	 english:	  Are you sure you want to delete all the groups do not block. If this will not be deleted to recover?
*/

$lang_module['block_delete_per_confirm'] = "Jste si jisti, že chcete odstranit tento blok?";
/*
	 vietnam:	  Bạn có chắc muốn xóa block này không?
	 english:	  Are you sure you want to remove this block?
*/

$lang_module['block_add_success'] = "Přidat úspěšně";
/*
	 vietnam:	  Thêm thành công !
	 english:	  Add successful
*/

$lang_module['block_update_success'] = "Aktualizovat úspěšně";
/*
	 vietnam:	  Cập nhật thành công !
	 english:	  Update successful
*/

$lang_module['block_checkall'] = "Zvolit všechny";
/*
	 vietnam:	  Chọn tất cả
	 english:	  Check all
*/

$lang_module['block_uncheckall'] = "Zrušte zaškrtnutí všech";
/*
	 vietnam:	  Bỏ chọn tất cả
	 english:	  Uncheck all
*/

$lang_module['block_delete_success'] = "Odstranit úspěšně";
/*
	 vietnam:	  Xóa thành công
	 english:	  Delete successful
*/

$lang_module['block_error_nomodule'] = "Prosím, vyberte alespoň 1 modul";
/*
	 vietnam:	  Hãy chọn ít nhất 1 module
	 english:	  Please select at least 1 module
*/

$lang_module['block_error_title'] = "Blok titule je prázdný nebo neexistuje!";
/*
	 vietnam:	  Bạn chưa khai báo tên block hoặc tên block trùng với một block hiện có !
	 english:	  Block title is empty or exists!
*/

$lang_module['error_empty_title'] = "Nemáte deklarovat jméno bloku";
/*
	 vietnam:	  Bạn chưa khai báo tên của block
	 english:	  You do not declare block name
*/

$lang_module['error_invalid_url'] = "jste zadali adresu URL, která nevyřeší";
/*
	 vietnam:	  URL mà bạn đưa vào không đúng
	 english:	  you have entered URL that does not correct
*/

$lang_module['error_empty_content'] = "Blok nebl nepřipojený se souborem, obsah bloku je prázdný";
/*
	 vietnam:	  Block chưa được kết nối với file, khối quảng cáo hoặc chưa có nội dung
	 english:	  Block didn't conect file, Block content is empty
*/

$lang_module['block_type'] = "Typ bloku";
/*
	 vietnam:	  Chọn kiểu block
	 english:	  Block type
*/

$lang_module['block_file'] = "Soubor";
/*
	 vietnam:	  File
	 english:	  File
*/

$lang_module['block_html'] = "HTML";
/*
	 vietnam:	  HTML
	 english:	  HTML
*/

$lang_module['block_typehtml'] = "HTML typ";
/*
	 vietnam:	  Dạng HTML
	 english:	  HTML type
*/

$lang_module['edit_block'] = "Upravit blok";
/*
	 vietnam:	  Sửa block
	 english:	  Edit block
*/

$lang_module['blang_all'] = "Všech jazyků";
/*
	 vietnam:	  Tất cả ngôn ngữ
	 english:	  All language
*/

$lang_module['block_banners_pl'] = "Nebo reklamy bloku";
/*
	 vietnam:	  Hoặc từ khối quảng cáo
	 english:	  Or block advertising
*/

$lang_module['block_b_pl'] = "Reklamy bloku";
/*
	 vietnam:	  Khối quảng cáo
	 english:	  Block advertising
*/

$lang_module['block_function'] = "Zvolte funkci";
/*
	 vietnam:	  Hãy chọn function
	 english:	  Select function
*/

$lang_module['add_block_module'] = "Použít pro moduly";
/*
	 vietnam:	  Áp dụng cho module
	 english:	  Apply for modules
*/

$lang_module['add_block_all_module'] = "všechny moduly";
/*
	 vietnam:	  Tất cả các module
	 english:	  all modules
*/

$lang_module['add_block_select_module'] = "Zvolte modul";
/*
	 vietnam:	  Chọn module
	 english:	  Select module
*/

$lang_module['block_layout'] = "Vybrat rozvržení";
/*
	 vietnam:	  Chọn layout
	 english:	  Select layout
*/

$lang_module['block_check'] = "Kontrolovat";
/*
	 vietnam:	  Check
	 english:	  Check
*/

$lang_module['block_select_module'] = "Zvolte modul";
/*
	 vietnam:	  Chọn module
	 english:	  Select module
*/

$lang_module['block_select_function'] = "Zvolte funkci";
/*
	 vietnam:	  Chọn function
	 english:	  Select function
*/

$lang_module['block_error_fileconfig_title'] = "Chyba: Rozhraní konfigurační soubor";
/*
	 vietnam:	  Lỗi file cấu hình giao diện
	 english:	  Interface configuration file error
*/

$lang_module['block_error_fileconfig_content'] = "Rozhraní konfigurační soubor je chybný nebo neexistuje. Zkontrolujte, zda ve vašem adresáři téma";
/*
	 vietnam:	  File cấu hình của giao diện không đúng hoặc không tồn tại. Hãy kiểm tra lại trong thư mục theme của bạn
	 english:	  Interface configuration file is incorrect or does not exist. Check in your theme directory
*/

$lang_module['autoinstall'] = "Automatické nastavení";
/*
	 vietnam:	  Cài đặt theme
	 english:	  Automatic setup
*/

$lang_module['autoinstall_theme_install'] = "Instalovat témat";
/*
	 vietnam:	  Cài đặt theme
	 english:	  Installing themes
*/

$lang_module['autoinstall_method_none'] = "Prosím vyberte:";
/*
	 vietnam:	  Hãy lựa chọn:
	 english:	  Please select:
*/

$lang_module['autoinstall_method_install'] = "Instalovat témat o systému";
/*
	 vietnam:	  Cài đặt theme lên hệ thống
	 english:	  Installing themes on the system
*/

$lang_module['autoinstall_method_packet'] = "Modul zaobalený";
/*
	 vietnam:	  Đóng gói theo tên theme
	 english:	  Module packeted
*/

$lang_module['autoinstall_method_packet_module'] = "Balení module téma";
/*
	 vietnam:	  Đóng gói theme theo module
	 english:	  Packing the module theme
*/

$lang_module['autoinstall_continue'] = "Další";
/*
	 vietnam:	  Tiếp tục
	 english:	  Next
*/

$lang_module['autoinstall_back'] = "Zpět";
/*
	 vietnam:	  Quay lại
	 english:	  Back
*/

$lang_module['autoinstall_error_nomethod'] = "Prosím, vyberte typ instalace a pokračovat!";
/*
	 vietnam:	  Hãy chọn 1 kiểu cài đặt để tiếp tục !
	 english:	  Empty setup type!
*/

$lang_module['autoinstall_theme_select_file'] = "Prosím, vyberte balíčky pro instalaci:";
/*
	 vietnam:	  Hãy chọn gói để cài đặt:
	 english:	  Please select packages for installation:
*/

$lang_module['autoinstall_theme_error_nofile'] = "Chyba: Prosím vyberte soubor k instalaci";
/*
	 vietnam:	  Lỗi: Hãy chọn file để tiến hành cài đặt
	 english:	  Error: Please select the file to install
*/

$lang_module['autoinstall_theme_error_filetype'] = "Chyba: Soubor musí být umístěn zip nebo gz formátu";
/*
	 vietnam:	  Lỗi: File cài đặt phải là định dạng file zip hoặc gz
	 english:	  Error: File must be installed zip or gz format file
*/

$lang_module['autoinstall_theme_error_createfile'] = "Chyba: Nelze uložit soubor mezi paměti seznamu. Zkontrolujte si tmp adresáře nebo chmod";
/*
	 vietnam:	  Lỗi: không thể lưu đệm danh sách file. Hãy kiểm tra lại hoặc chmod thư mục tmp
	 english:	  Error: Unable to save cache file list. Check your tmp directory or chmod
*/

$lang_module['autoinstall_theme_uploadedfile'] = "Systém Nahraný soubor:";
/*
	 vietnam:	  Hệ thống đã tải lên file:
	 english:	  The system uploaded file:
*/

$lang_module['autoinstall_theme_uploadedfilesize'] = "Velikost souboru";
/*
	 vietnam:	  Dung lượng:
	 english:	  File size
*/

$lang_module['autoinstall_theme_uploaded_filenum'] = "Celkový počet souborů + složek:";
/*
	 vietnam:	  Tổng số file + folder:
	 english:	  Total number of files + folders:
*/

$lang_module['autoinstall_theme_error_warning_fileexist'] = "Seznam je v systému:";
/*
	 vietnam:	  Danh sách hiện có trên hệ thống:
	 english:	  The list is on the system:
*/

$lang_module['autoinstall_theme_checkfile_notice'] = "Chcete-li pokračovat v instalaci, klikněte na CHECK systém bude kontrolovat automaticky kompatibilitu";
/*
	 vietnam:	  Để tiếp tục quá trình cài đặt, click vào KIỂM TRA hệ thống sẽ tự động kiểm tra tính tương thích
	 english:	  To continue the installation , click on CHECK the system will check automatically for compatibility
*/

$lang_module['autoinstall_theme_checkfile'] = "Kontrolovat";
/*
	 vietnam:	  KIỂM TRA !
	 english:	  CHECK !
*/

$lang_module['autoinstall_theme_installdone'] = "Nainstalujte ...";
/*
	 vietnam:	  TIẾN HÀNH CÀI ĐẶT...
	 english:	  Install ...
*/

$lang_module['autoinstall_theme_error_invalidfile'] = "Chyba: Neplatný zip soubor";
/*
	 vietnam:	  Lỗi: File zip không hợp lệ
	 english:	  Error: Invalid zip file
*/

$lang_module['autoinstall_theme_error_invalidfile_back'] = "Zpět";
/*
	 vietnam:	  Quay lại
	 english:	  Back
*/

$lang_module['autoinstall_package_processing'] = "Prosím, vyčkejte na dokončení ...";
/*
	 vietnam:	  xin chờ quá trình thực hiện hoàn thành...
	 english:	  please wait to complete...
*/

$lang_module['autoinstall_theme_error_uploadfile'] = "Chyba: Nelze vkládat. Prosím zkontrolujte adresáře nebo chmod adresáře";
/*
	 vietnam:	  Lỗi: không thể upload file lên hệ thống. Hãy kiểm tra lại hoặc chmod thư mục tmp
	 english:	  Error: Unable to upload files. Please check directory permission or chmod directory
*/

$lang_module['autoinstall_theme_unzip_abort'] = "Instalace nemůže pokračovat automaticky nepodporuje hostitel.";
/*
	 vietnam:	  Việc cài đặt tự động không thể tiếp tục do host không hỗ trợ.
	 english:	  The installation can not continue automatically by the host does not support.
*/

$lang_module['autoinstall_theme_cantunzip'] = "Chyba nelze rozbalit. Zkontrolujte prosím, zda chmod adresáře.";
/*
	 vietnam:	  Lỗi không thể giải nén. Hãy kiểm tra lại chmod các thư mục.
	 english:	  Error can not unpack. Please check the chmod of directory.
*/

$lang_module['autoinstall_theme_unzip_filelist'] = "Seznam souborů k rozbalení";
/*
	 vietnam:	  Danh sách file đã giải nén
	 english:	  Extract files list
*/

$lang_module['autoinstall_theme_unzip_setuppage'] = "Přejít na téma management stránku.";
/*
	 vietnam:	  Đến trang quản lý theme.
	 english:	  Go to the management page theme.
*/

$lang_module['autoinstall_package_select'] = "Zvolte modul do balíčku";
/*
	 vietnam:	  Chọn theme để đóng gói
	 english:	  Select module to package
*/

$lang_module['autoinstall_package_noselect'] = "Źádný modul vybraných";
/*
	 vietnam:	  Hãy chọn 1 theme để đóng gói
	 english:	  No module selected
*/

$lang_module['autoinstall_package_module_select'] = "Vyberte modul do balíčku";
/*
	 vietnam:	  Chọn module để đóng gói
	 english:	  Select the module to package
*/

$lang_module['autoinstall_package_noselect_module'] = "Prosím, vyberte modul pro balík témat";
/*
	 vietnam:	  Hãy chọn 1 module để đóng gói theme
	 english:	  Please select a module to package themes
*/

$lang_module['autoinstall_method_theme_none'] = "Prosím, vyberte téma";
/*
	 vietnam:	  Hãy chọn theme
	 english:	  Please select the theme
*/

$lang_module['autoinstall_method__module_none'] = "Zvolte modul";
/*
	 vietnam:	  Hãy chọn module
	 english:	  Select module
*/

$lang_module['autoinstall_package_noselect_module_theme'] = "Je povinen si zvolit téma a název modulu na balení";
/*
	 vietnam:	  Bắt buộc phải chọn theme và tên module để đóng gói
	 english:	  Required to choose the theme and module name to package
*/

$lang_module['setup_layout'] = "Nastavení rozvržení";
/*
	 vietnam:	  Thiết lập layout
	 english:	  Set layout
*/

$lang_module['setup_module'] = "Modul";
/*
	 vietnam:	  Module
	 english:	  Module
*/

$lang_module['setup_select_layout'] = "Vyberte rozvržení";
/*
	 vietnam:	  Chọn layout
	 english:	  Choose layout
*/

$lang_module['setup_updated_layout'] = "Nat rozvržení uspěšně!";
/*
	 vietnam:	  Thiết lập layout thành công !
	 english:	  Set layout to succeed!
*/

$lang_module['setup_error_layout'] = "Nelze spustit příkaz set layout";
/*
	 vietnam:	  Không thể thực hiện lệnh thiết lập layout
	 english:	  Could not execute command set layout
*/

$lang_module['setup_save_layout'] = "Uložit všechny změny";
/*
	 vietnam:	  Lưu tất cả thay đổi
	 english:	  Save all changes
*/

$lang_module['theme_manager'] = "Management rozhraní";
/*
	 vietnam:	  Quản lý giao diện
	 english:	  Management interface
*/

$lang_module['theme_recent'] = "Seznam rozhraní je připraveno";
/*
	 vietnam:	  Danh sách giao diện hiện có
	 english:	  List interface is ready
*/

$lang_module['theme_created_by'] = "navrhl";
/*
	 vietnam:	  thiết kế bởi
	 english:	  designed by
*/

$lang_module['theme_created_website'] = "navštivte webové stránky autora";
/*
	 vietnam:	  ghé thăm website của tác giả
	 english:	  visit author's website
*/

$lang_module['theme_created_folder'] = "Soubory + adresáře v:";
/*
	 vietnam:	  Các file + thư mục nằm trong:
	 english:	  Files + directory in:
*/

$lang_module['theme_created_position'] = "Pozice v designu tématu:";
/*
	 vietnam:	  Các vị trí thiết kế trong theme:
	 english:	  The position in the design theme:
*/

$lang_module['theme_created_activate'] = "Aktivovat použití";
/*
	 vietnam:	  Kích hoạt sử dụng
	 english:	  Activate uses
*/

$lang_module['theme_created_activate_layout'] = "Chyba: Musíte nastavit šablonu pro toto rozhraní před aktivací";
/*
	 vietnam:	  Lỗi: Bạn cần thiết lập layout cho giao diện này trước khi khích hoạt
	 english:	  Error: You need to set the layout for this interface the firt to active
*/

$lang_module['theme_created_delete'] = "Vymazány ze systému";
/*
	 vietnam:	  Xóa khỏi hệ thống
	 english:	  Deleted from the system
*/

$lang_module['theme_created_current_use'] = "Rozhraní použítí";
/*
	 vietnam:	  Giao diện đang sử dụng
	 english:	  Interface using
*/

$lang_module['theme_created_delete_theme'] = "Chcete-li si smazat všechny téma balíček";
/*
	 vietnam:	  Bạn có chắc muốn xóa toàn bộ gói theme
	 english:	  Do you want to delete the all theme package
*/

$lang_module['theme_created_delete_theme_success'] = "Úspěšně smazána téma systému!";
/*
	 vietnam:	  Đã xóa thành công theme ra khỏi hệ thống !
	 english:	  Successfully deleted the theme go out the system !
*/

$lang_module['theme_created_delete_theme_unsuccess'] = "Tam jsou chyby v procesu mazání souborů!";
/*
	 vietnam:	  Có lỗi trong quá trình xóa file !
	 english:	  There are errors in the process of deleting files !
*/

$lang_module['theme_created_delete_current_theme'] = "Nemůžete smazat současné téma, kdy systém je používány!";
/*
	 vietnam:	  Bạn không thể xóa theme hiện tại hệ thống đang sử dụng !
	 english:	  You can not delete the current theme when the systme is using it!
*/

$lang_module['theme_created_delete_module_theme'] = "Nemůžete mazat témata, protože toto téma je používán pro modul:%s, je potřeba nastavit moduly.";
/*
	 vietnam:	  Bạn không thể xóa theme vì đang sử dụng cho module: %s, bạn cần cấu hình lại các module đó.
	 english:	  You can not delete themes because this theme are using for module:% s, you need to reconfigure modules.
*/

$lang_module['block_front_calendar_format'] = "Formát dd.mm.yyy";
/*
	 vietnam:	  Định dạng dd.mm.yyy
	 english:	  Format dd.mm.yyy
*/

$lang_module['block_front_delete_error'] = "Chyba: Nelze smazat blok, zkontrolujte oprávnění";
/*
	 vietnam:	  Lỗi: không thể xóa block, hãy kiểm tra lại quyền của bạn
	 english:	  Error: Unable to delete the block, check your permission
*/

$lang_module['block_front_outgroup_success'] = "Blok byl úspěšně odstraněn ze skupiny a přidat do skupiny";
/*
	 vietnam:	  Block đã được bỏ ra khỏi nhóm thành công và nằm trong nhóm
	 english:	  Block was successfully removed from the group and add to group
*/

$lang_module['block_front_outgroup_cancel'] = "V současné době existuje pouze jeden blok v této skupině proto není odstraněn ze skupiny";
/*
	 vietnam:	  Hiện tại chỉ có duy nhất 1 block nằm trong nhóm này do đó không cần bỏ ra khỏi nhóm
	 english:	  Currently there is only one block in this group should therefore not removed from group
*/

$lang_module['block_front_outgroup_error_update'] = "Jsou chyby v procesu aktualizace dat";
/*
	 vietnam:	  Có lỗi trong quá trình cập nhật dữ liệu
	 english:	  There are errors in the process of updating data
*/


?>