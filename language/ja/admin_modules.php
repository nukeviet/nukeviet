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

$lang_module['modules'] = '新しいモジュールを設定';
$lang_module['blocks'] = 'ブロックの設定';
$lang_module['language'] = '言語を設定';
$lang_module['setup'] = '設定';
$lang_module['main'] = 'モジュールのリスト';
$lang_module['edit'] = '&ldquo;%s&rdquo;モジュールを編集';
$lang_module['caption_actmod'] = '有効なモジュールのリスト';
$lang_module['caption_deactmod'] = '無効なモジュールのリスト';
$lang_module['caption_badmod'] = 'エラーモジュールのリスト';
$lang_module['caption_newmod'] = '設定しないモジュールのリスト';
$lang_module['module_name'] = 'モジュール';
$lang_module['custom_title'] = '名前';
$lang_module['weight'] = '順位';
$lang_module['in_menu'] = 'トップメニュー';
$lang_module['submenu'] = 'サブメニュー';
$lang_module['version'] = 'バージョン';
$lang_module['settime'] = '時間設定';
$lang_module['author'] = '作者';
$lang_module['theme'] = 'テーマ';
$lang_module['theme_default'] = 'デフォルト';
$lang_module['keywords'] = 'キーワード';
$lang_module['keywords_info'] = 'コンマで区切られている';
$lang_module['funcs_list'] = '&ldquo;%s&rdquo;モジュールにある機能のリスト';
$lang_module['funcs_title'] = '機能';
$lang_module['funcs_custom_title'] = '名前';
$lang_module['funcs_layout'] = '使用中のレアウト';
$lang_module['funcs_in_submenu'] = 'メニュー';
$lang_module['funcs_subweight'] = '順位';
$lang_module['activate_rss'] = 'RSSを有効にする';
$lang_module['module_sys'] = 'システムモジュール';
$lang_module['vmodule'] = 'バーチャルモジュール';
$lang_module['vmodule_add'] = 'バーチャルモジュールを追加';
$lang_module['vmodule_name'] = '新しいモジュール名';
$lang_module['vmodule_file'] = '元のモジュール';
$lang_module['vmodule_note'] = '注意';
$lang_module['vmodule_select'] = 'モジュールを選択';
$lang_module['vmodule_blockquote'] = '注意：モジュール名を作るとき、文字と数とアンダーバーの記号のみ使って下さい。';
$lang_module['autoinstall'] = '自動的な設定・パッケージ';
$lang_module['autoinstall_method'] = 'プロセスを選択';
$lang_module['autoinstall_method_none'] = '選択してください。';
$lang_module['autoinstall_method_module'] = 'モジュールとブロックというパケットを設定';
$lang_module['autoinstall_method_block'] = 'ブロックをインストール';
$lang_module['autoinstall_method_packet'] = 'パケットされたモジュール';
$lang_module['autoinstall_continue'] = '次へ';
$lang_module['autoinstall_back'] = '戻る';
$lang_module['autoinstall_error_nomethod'] = '設定タイプを選択してください。';
$lang_module['autoinstall_module_install'] = 'モジュールの設定';
$lang_module['autoinstall_module_select_file'] = 'パケットを選択してください。';
$lang_module['autoinstall_module_error_filetype'] = 'エラー：設定するファイル拡張子はzip又はgzにしてください。';
$lang_module['autoinstall_module_error_nofile'] = 'エラー：ファイルを選択していません。';
$lang_module['autoinstall_module_nomethod'] = '設定方法を選択していません。';
$lang_module['autoinstall_module_uploadedfile'] = 'アップロードされたファイル：';
$lang_module['autoinstall_module_uploadedfilesize'] = 'サイズ';
$lang_module['autoinstall_module_uploaded_filenum'] = 'ファイルとフォルダのトータル数';
$lang_module['autoinstall_module_error_uploadfile'] = 'エラー：アップロード失敗。tmpフォルダをchmodしてください。';
$lang_module['autoinstall_module_error_createfile'] = 'エラー：ファイル作成失敗。tmpフォルダをchmodしてください。';
$lang_module['autoinstall_module_error_invalidfile'] = 'エラー：無効なzipファイル';
$lang_module['autoinstall_module_error_invalidfile_back'] = '戻る';
$lang_module['autoinstall_module_error_warning_overwrite'] = '通知：モジュールの構成はファイルとフォルダが正しくありません。インストールを続けますか？';
$lang_module['autoinstall_module_overwrite'] = 'インストール';
$lang_module['autoinstall_module_error_warning_fileexist'] = 'ファイル一覧';
$lang_module['autoinstall_module_error_warning_invalidfolder'] = '無効なファイル構成！';
$lang_module['autoinstall_module_error_warning_permission_folder'] = 'Safe modeオン。フォルダ作成失敗！';
$lang_module['autoinstall_module_checkfile_notice'] = '設定を続けるために、「CHECK」をクリックすればシステムは適合性をチェックします。';
$lang_module['autoinstall_module_checkfile'] = 'チェック！';
$lang_module['autoinstall_module_installdone'] = '設定中...';
$lang_module['autoinstall_module_cantunzip'] = 'unzip失敗。フォルダをchmodしてください。';
$lang_module['autoinstall_module_unzip_success'] = '設定成功！有効な設定ページに自動的に移動します。';
$lang_module['autoinstall_module_unzip_setuppage'] = 'モジュール管理サイトに移動';
$lang_module['autoinstall_module_unzip_filelist'] = 'unzipファイルのリスト';
$lang_module['autoinstall_module_error_movefile'] = 'ホストはアンパック後、ファイル移動することを対応しませんので、自動的なインストールができません。';
$lang_module['autoinstall_package_select'] = 'パケットするにはモジュールを選択してください。';
$lang_module['autoinstall_package_noselect'] = 'モジュールを選択していません。1つ選択してください。';
$lang_module['autoinstall_package_processing'] = 'お待ち下さい．．．';
$lang_module['mobile'] = '携帯電話のテーマ';
$lang_module['delete_module_info1'] = 'このモジュールは言語　<strong>%s</strong>　で使用された、前にこの言語でそれを削除してください。';
$lang_module['delete_module_info2'] = 'このモジュールで作成される%d仮想モジュールがあるのでそれを削除してください';
$lang_module['admin_title'] = '管理部のタイトル';
$lang_module['change_func_name'] = '機能名前を変更　"％s"　モジュールの　"％s"';
$lang_module['edit_error_update_theme'] = 'モジュールアップデートする時　%s　テーマが適切ではないか、欠陥があるかと発見しました。もう一度確認してください。';
$lang_module['description'] = '説明';

?>