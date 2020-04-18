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

$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " VALUES (:vid, :question, '', 1, 0, 1, '6', 1275318563, 0, 1)");
$sth->bindValue(':vid', 2, PDO::PARAM_INT);
$sth->bindValue(':question', 'Do you know about Nukeviet 3?', PDO::PARAM_STR);
$sth->execute();

$sth->bindValue(':vid', 3, PDO::PARAM_INT);
$sth->bindValue(':question', 'What are you interested in open source?', PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows VALUES (?, ?, ?, ?, ?)");
$sth->execute(array(5, 2, 'A whole new sourcecode for the web.', '', 0));
$sth->execute(array(6, 2, 'Open source, free to use.', '', 0));
$sth->execute(array(7, 2, 'Use of xHTML, CSS and Ajax support', '', 0));
$sth->execute(array(8, 2, 'All the comments on', '', 0));
$sth->execute(array(9, 3, 'constantly improved, modified by the whole world.', '', 0));
$sth->execute(array(10, 3, 'To use the free of charge.', '', 0));
$sth->execute(array(11, 3, 'The freedom to explore, modify at will.', '', 0));
$sth->execute(array(12, 3, 'Match to learning and research because the freedom to modify at will.', '', 0));
$sth->execute(array(13, 3, 'All comments on', '', 0));
