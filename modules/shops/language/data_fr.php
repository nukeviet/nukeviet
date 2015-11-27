<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

global $op;

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange, round, number_format) VALUES (840, 'USD', 'US Dollar', 21000, '0.01', ',||.')" );
$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_money_" . $lang . " (id, code, currency, exchange, round, number_format) VALUES (704, 'VND', 'Vietnam Dong', 1, '100', ',||.')" );

$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " (code, title, exchange, round) VALUES ('g', 'Gram', 1, '0.1')" );
$db->query( "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_weight_" . $lang . " (code, title, exchange, round) VALUES ('kg', 'Kilogam', 1000, '0.1')" );