<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 03 Jul 2014 04:35:32 GMT
 */

if (!defined('NV_ADMIN')) die('Stop!!!');

global $module_config;

$submenu['signer'] = $lang_module['signer'];
$submenu['scontent'] = $lang_module['scontent_add'];
$submenu['area'] = $lang_module['area'];
$submenu['cat'] = $lang_module['cat'];
$submenu['subject'] = $lang_module['subject'];
if($module_config[$module_name]['activecomm']){
	$submenu['examine'] = $lang_module['examine'];
}
$submenu['config'] = $lang_module['config'];