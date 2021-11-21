<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Zalo';
$lang_module['settings'] = 'Settings';
$lang_module['zalo_official_account_id'] = 'Zalo Official Account ID (OAID)';
$lang_module['oa_create_note'] = '<ul><li>If you do not have a Zalo Official Account, please <a href="%s" target="_blank">click here</a> to create it.</li><li>Then <a href="%s" target="_blank">visit here</a> to get OAID</li></ul>';
$lang_module['app_id'] = 'App ID';
$lang_module['app_secret_key'] = 'App Secret Key';
$lang_module['app_note'] = '<ul><li>If you don\'t have an app, you can <a href="%s" target="_blank">create it here</a>.</li><li>Go to the <a href="%s" target="_blank">Manage Applications page</a>, click on the desired application to go to the settings page, copy the App ID and App Secret Key into the 2 corresponding boxes on the side (Note: If the app is in an inactive state, you need to click the switch in the upper right corner to switch the application to an active state).</li><li>Click on &ldquo;Official Account&rdquo; => &ldquo;Manage Official Account&rdquo; on the left menu bar to link to the official account you specified above.</li><li>Click on &ldquo;Official Account&rdquo; => &ldquo;General settings&rdquo;in the left menu bar, find the box &ldquo;Official Account Callback Url&rdquo; and click on the button &ldquo;Update&rdquo; to enter the following value:<code>%s</code>, then click the &ldquo;Save&rdquo; button next to it. Continue to &ldquo;Select permissions to request from OA&rdquo;, select all and click &ldquo;Save&rdquo; button</li><li>Click on &ldquo;Login&rdquo;, click on &ldquo;Web&rdquo; on the page that appears, enter in the box &ldquo;Home URL&rdquo; the value: <code>%s</code>, then add the following 2 values in box &ldquo;Callback URL&rdquo;: <code>%s</code> and <code>%s</code>, click on &ldquo;Save change&rdquo;.</li></ul>';
$lang_module['access_token'] = 'Access Token';
$lang_module['refresh_token'] = 'Refresh Token';
$lang_module['submit'] = 'Submit';
$lang_module['access_token_create'] = 'Create Access Token';
$lang_module['oa_id_empty'] = 'Error: Official Account ID not been declared';
$lang_module['redirect_uri_empty'] = 'Error: Callback Url has not been declared';
$lang_module['app_id_empty'] = 'Error: App ID has not been declared';
$lang_module['app_seckey_empty'] = 'Error: App secret key has not been declared';
$lang_module['refresh_token_empty'] = 'Error: Refresh token not defined';
$lang_module['not_response'] = 'Error: No return data';
$lang_module['oa_id_incorrect'] = 'Error: The returned OAID does not match the OAID you declared';
$lang_module['refresh_token_expired'] = 'Error: Refresh token has expired';
