<?php

/**
* @Project NUKEVIET 3.x
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2012 VINADES.,JSC. All rights reserved
* @Language česky
* @Createdate Aug 01, 2010, 02:40:00 PM
*/

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'http://datviet.cz';
$lang_translator['createdate'] = '01/08/2010, 21:40';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC.. All rights reserved';
$lang_translator['info'] = 'YM: datvietinfo2010 ';
$lang_translator['langtype'] = 'lang_global';

$lang_global['mod_authors'] = 'Administrator';
$lang_global['mod_groups'] = 'Skupina';
$lang_global['mod_database'] = 'Databáze';
$lang_global['mod_settings'] = 'Konfigurace';
$lang_global['mod_cronjobs'] = 'Automatický proces';
$lang_global['mod_modules'] = 'Management moduly';
$lang_global['mod_themes'] = 'Témata';
$lang_global['mod_siteinfo'] = 'Informace';
$lang_global['mod_language'] = 'Jazyk';
$lang_global['mod_upload'] = 'Upload';
$lang_global['mod_webtools'] = 'Webtools';
$lang_global['go_clientsector'] = 'Domovská stránka';
$lang_global['go_clientmod'] = 'Viz naše webové stránky';
$lang_global['please_select'] = 'Prosím, vyberte';
$lang_global['admin_password_empty'] = 'Heslo správce nebylo prohlášeno';
$lang_global['adminpassincorrect'] = 'Heslo správce "<strong>%s </ strong>" je nepřesné. Zkuste to znovu';
$lang_global['admin_password'] = 'Heslo';
$lang_global['admin_no_allow_func'] = 'Nemůžete přístup k této funkci';
$lang_global['who_view'] = 'Zobrazit právo';
$lang_global['who_view0'] = 'Zobrazit všechny';
$lang_global['who_view1'] = 'Člen';
$lang_global['who_view2'] = 'Správa';
$lang_global['who_view3'] = 'Skupina členů';
$lang_global['groups_view'] = 'Skupina zobrazeno';
$lang_global['block_modules'] = 'Blok v modulech';
$lang_global['hello_admin1'] = 'Vítejte%1$s! Poslední přihlášení  Administrator v:%2$s';
$lang_global['hello_admin2'] = 'Účet:%1$s! Jste přihlášen správy,%2$s';
$lang_global['hello_admin3'] = 'Vítejte%1$s. Toto je první pokus na přihlášení správy';
$lang_global['ftp_error_account'] = 'Chyba: Nelze se připojit k FTP serveru, zkontrolujte nastavení FTP';
$lang_global['ftp_error_path'] = 'Chyba: Špatná konfigurace na vzdálenou cestu';
$lang_global['login_error_account'] = 'Chyba: Uživatelské jméno nebylo správné nebo prohlášeno za neplatné. (Pouze písmena, číslice a podtržítka latinské abecedy. Minimum%1$s znaky, maximálně%1$s znaků)';
$lang_global['login_error_password'] = 'Chyba: Heslo nebylo správné nebo prohlášeno za neplatné. (Pouze písmena, číslice a podtržítka latinské abecedy. Minimum%1$s znaky, maximálně%1$s znaků)';
$lang_global['login_error_security'] = 'Chyba: Kód ověrění nebyla prohlášena nebo  neplatná! (Pouze latinské abecedy. Musí mít%1$s znaků)';
$lang_global['error_zlib_support'] = 'Chyba: Váš server nepodporuje zlib,kontaktuje vašeho poskytovatele hostingových služeb,aby umožnil zlib mohou využít funkce.';
$lang_global['error_zip_extension'] = 'Chyba: Váš server nepodporuje extension ZIP ,kontaktujte vašeho hostingových služeb,aby umožnil extension ZIP.';
$lang_global['error_uploadNameEmpty'] = 'Chyba: název souboru nesprávně';
$lang_global['error_uploadSizeEmpty'] = 'Chyba: Velikost souboru nesprávně';
$lang_global['error_upload_ini_size'] = 'Chyba: Velikost  soubor větší než je povoleno v php.ini';
$lang_global['error_upload_form_size'] = 'Chyba: Velikost  soubor větší než je povoleno v MAX_FILE_SIZE na HTML';
$lang_global['error_upload_partial'] = 'Chyba: pouze část souboru je nahrán';
$lang_global['error_upload_no_file'] = 'Chyba: žadná nahraný soubor';
$lang_global['error_upload_no_tmp_dir'] = 'Chyba:žádná adresa na souboru';
$lang_global['error_upload_cant_write'] = 'Chyba: Nelze zapisovat soubor nahrát';
$lang_global['error_upload_extension'] = 'Chyba: Soubor  byl blokován protože rozšíření není platný';
$lang_global['error_upload_unknown'] = 'Došlo k chybě při nahrávání';
$lang_global['error_upload_type_not_allowed'] = 'Chyba: formát souboru nahrát nepovolit';
$lang_global['error_upload_mime_not_recognize'] = 'Chyba: Systém nemůže určit formát nahraných souborů';
$lang_global['error_upload_max_user_size'] = 'Chyba: Velikost soubor  větší než je povoleno.Maximal %d bytes';
$lang_global['error_upload_not_image'] = 'Chyba: Systém nemůže určit formát souboru';
$lang_global['error_upload_image_failed'] = 'Chyba: Neplatný obrázek';
$lang_global['error_upload_image_width'] = 'Chyba: Obrázek  šířce větší než povolené.Maximal %d pixels %d pixels';
$lang_global['error_upload_image_height'] = 'Chyba: obrázek nahrát do výšky větší než je povoleno.Maximal';
$lang_global['error_upload_forbidden'] = 'Chyba: žádné adresář soubory';
$lang_global['error_upload_writable'] = 'Chyba:soubor %s neumožňuje nahrávání souborů obsahujících.Musíte zapisovat soubor na 0777';
$lang_global['error_upload_urlfile'] = 'Chyba: URL nesprávě';
$lang_global['error_upload_url_notfound'] = 'Chyba: Nelze nahrávat soubor z URL, kterou poskytují';

?>