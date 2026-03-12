<?php
 
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

use NukeViet\Api\DoApi;

// Khởi tạo bộ gọi
$apiurl = 'https://site.com/api.php';
$apikey = 'TAO_TRONG_ADMIN';
$apisecret = 'CUNG_TAO_TRONG_ADMIN';

$api = new DoApi($apiurl, $apikey, $apisecret, false);

// Gọi module news, action GetList
$response = $api->setLang('vi')
                ->setModule('news')
                ->setAction('GetList')
                ->setData(['limit' => 5])
                ->execute();

if (empty($response)) {
    echo $api->getError();
} else {
    print_r($response);
}
