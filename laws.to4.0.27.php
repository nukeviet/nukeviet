<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 02 Dec 2015 08:26:04 GMT
 */

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Duyệt tất cả các ngôn ngữ
$language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
	// Duyet laws va module ao
	$mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'laws'");
	while (list($mod, $mod_data) = $mquery->fetch(3)) {
	    try {
	    	
	        $db->query("CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row_area ( row_id INT(10) UNSIGNED NOT NULL , area_id SMALLINT(4) UNSIGNED NOT NULL ) ENGINE = MyISAM;");
			
			$db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row_area ADD UNIQUE( row_id, area_id)");
				
			$db->query("TRUNCATE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row_area");
			 
			// Di chuyen du lieu
			$result = $db->query( "SELECT id, aid FROM " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row" );
			while( list( $id, $aid ) = $result->fetch( 3 ) )
			{
				$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row_area(row_id, area_id) VALUES (" . $id . ", " . $aid . ")");
			}

			// Xoa truong aid trong bang _row
			$db->query("ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_row DROP aid");

			die('OK');

	    } catch (PDOException $e) {
	    	die('NO');
	    }
	}
}