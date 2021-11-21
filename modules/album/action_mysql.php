<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 27 Jul 2011 14:55:22 GMT
 */

if (! defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_albums";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_image";


$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_albums(
    id_album int(11) NOT NULL AUTO_INCREMENT,
    description varchar(255) NULL,
    status varchar(255) NOT NULL,
    user_ID varchar(255) NOT NULL,
    time datetime NOT NULL,
    PRIMARY KEY (id_album)
) ENGINE=MyISAM";
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_image(
    id_image int(11) NOT NULL AUTO_INCREMENT,
    id_album int(11) NOT NULL,
    description varchar(255) NULL,
    status varchar(255) NOT NULL,
    user_ID varchar(255) NOT NULL,
    time datetime NOT NULL,
    PRIMARY KEY (id_image)
) ENGINE=MyISAM";