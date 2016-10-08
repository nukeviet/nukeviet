<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 20:59
 */

if (! defined('NV_ADMIN')) {
    die('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " VALUES (1, 'Qu&#039;est ce que NukeViet 3.0?', '', 1, 0, 1, '6', 1275318563, 0, 1)");

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows VALUES (?, ?, ?, ?, ?)');
$sth->execute(array(1, 1, 'Une code source de web tout neuve', '', 0));
$sth->execute(array(2, 1, 'Open source, libre et gratuit', '', 0));
$sth->execute(array(3, 1, 'Utilise xHTML, CSS et supporte Ajax', '', 0));
$sth->execute(array(4, 1, 'Toutes ces réponses', '', 1));
