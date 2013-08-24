<?php

/**
* @Project NUKEVIET 3.x
* @Author VINADES.,JSC (contact@vinades.vn)
* @Copyright (C) 2012 VINADES.,JSC. All rights reserved
* @Language 日本語
* @Createdate Apr 15, 2011, 08:22:00 AM
*/

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_translator['author'] = 'VINADES.,JSC (contact@vinades.vn)';
$lang_translator['createdate'] = '15/04/2011, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = 'Language translated from http://translate.nukeviet.vn';
$lang_translator['langtype'] = 'lang_module';

$lang_global['mod_authors'] = '管理者';
$lang_global['mod_groups'] = 'グループ';
$lang_global['mod_database'] = 'データベース';
$lang_global['mod_settings'] = '設定';
$lang_global['mod_cronjobs'] = '自動的なプロセス';
$lang_global['mod_modules'] = 'モジュール管理';
$lang_global['mod_themes'] = 'テーマ';
$lang_global['mod_siteinfo'] = '情報';
$lang_global['mod_language'] = '言語';
$lang_global['mod_upload'] = 'ファイル管理';
$lang_global['mod_webtools'] = 'ウェブツール';
$lang_global['go_clientsector'] = 'サイトのホームページ';
$lang_global['go_clientmod'] = 'プリビュー';
$lang_global['please_select'] = '選択してください。';
$lang_global['admin_password_empty'] = '管理者のパスワードを入力していません。';
$lang_global['adminpassincorrect'] = 'Administrator password &ldquo;<strong>%s</strong>&rdquo; is inaccurate. Try again';
$lang_global['admin_password'] = 'パスワード';
$lang_global['admin_no_allow_func'] = 'この機能にアクセスできません。';
$lang_global['who_view'] = 'View right';
$lang_global['who_view0'] = '全て見る';
$lang_global['who_view1'] = '会員';
$lang_global['who_view2'] = '管理者';
$lang_global['who_view3'] = '会員グループ';
$lang_global['level1'] = '最高権限';
$lang_global['level2'] = '一般的な管理者';
$lang_global['level3'] = 'モジュール管理者';
$lang_global['groups_view'] = '見ることが出来るグループ';
$lang_global['block_modules'] = 'モジュールのブロック';
$lang_global['hello_admin1'] = '%1$sようこそ！%2$sに管理者アカウントでログインしました。';
$lang_global['hello_admin2'] = 'これは%1$sアカウントです！あなたのセッションは%2$s前開きました。';
$lang_global['hello_admin3'] = '%1$sへようこそ！これは初めて管理システムにログインすることです。';
$lang_global['ftp_error_account'] = 'エラー：FTPサーバーに接続できません。FTP設定情報をチェックしてください。';
$lang_global['ftp_error_path'] = 'エラー：リモートパスが間違っています。';
$lang_global['login_error_account'] = 'エラー：Adminのユーザ名が設定されていない、もしくは、正しく設定されていません。（文字と数とアンダーバーの記号のみ使って下さい。最短%1$s文字、かつ最長%2$s文字にしてください。）';
$lang_global['login_error_password'] = 'エラー：Adminのユーザ名が設定されていない、もしくは、正しく設定されていません。（文字と数とアンダーバーの記号のみ使って下さい。最短%1$s文字、かつ最長%2$s文字にしてください。）';
$lang_global['login_error_security'] = 'エラー：セキュリティCodeは、有効でありません。（%1$sラテン文字のみ使ってください。）';
$lang_global['error_zlib_support'] = 'エラー：あなたのサーバーはzlib拡張モジュールがありません。zlib拡張モジュールを使用できるようにホスティング提供者に連絡してください。';
$lang_global['error_zip_extension'] = 'エラー：あなたのサーバーはZIP拡張モジュールがありません。ZIP拡張モジュールを使用できるようにホスティング提供者に連絡してください。';
$lang_global['error_uploadNameEmpty'] = 'エラー：アップロードされたファイル名が未定義です。';
$lang_global['error_uploadSizeEmpty'] = 'エラー：アップロードされたファイルサイズが未定義です。';
$lang_global['error_upload_ini_size'] = 'エラー：アップロードファイルは php.ini 内の upload_max_filesize の制限を超えています。';
$lang_global['error_upload_form_size'] = 'エラー：アップロードファイルはHTMLフォームで指定された MAX_FILE_SIZE の制限を超えています。';
$lang_global['error_upload_partial'] = 'エラー：アップロードファイルは一部分だけアップロードされました。';
$lang_global['error_upload_no_file'] = 'エラー：ファイルがアップロードされませんでした。';
$lang_global['error_upload_no_tmp_dir'] = 'エラー：一時保存フォルダが見つかりません。';
$lang_global['error_upload_cant_write'] = 'エラー：アップロードしたファイルを書き込みできません。';
$lang_global['error_upload_extension'] = 'エラー：無効なファイル拡張子なのでアップロードしたファイルはブロックされました。';
$lang_global['error_upload_unknown'] = 'ファイルがアップロードされていません。不明なエラー';
$lang_global['error_upload_type_not_allowed'] = 'エラー：この種類のファイルをアップロードすることは出来ません。';
$lang_global['error_upload_mime_not_recognize'] = 'エラー：アップロードしたファイル形式は未定義です。';
$lang_global['error_upload_max_user_size'] = 'エラー：アップロードしたファイルは最大サイズを超えました。最大サイズは %dバイトです。';
$lang_global['error_upload_not_image'] = 'エラー：アップロードされた画像ﾌｧｲﾙ形式は未定義です。';
$lang_global['error_upload_image_failed'] = 'エラー：アップロード画像は無効です。';
$lang_global['error_upload_image_width'] = 'エラー：画像サイズは最大サイズを超えました。最大横幅は %dピクセルです。';
$lang_global['error_upload_image_height'] = 'エラー：画像サイズは最大サイズを超えました。最大縦幅は %dピクセルです。';
$lang_global['error_upload_forbidden'] = 'エラー：アップロードしたファイルがあるフォルダは未定義です。';
$lang_global['error_upload_writable'] = 'エラー：ディレクトリ %sに書き込みできません。0777にCHMODしてください。';
$lang_global['error_upload_urlfile'] = 'エラー：URLは無効でロードできません。';
$lang_global['error_upload_url_notfound'] = 'エラー：URLが見つかりません。';

?>