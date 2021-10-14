<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/**
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . " VALUES (:vid, :question, '', 1, 0, 1, '6', " . NV_CURRENTTIME . ', 0, 1, 0)');
$sth->bindValue(':vid', 1, PDO::PARAM_INT);
$sth->bindValue(':question', 'What do you know about Nukeviet 4?', PDO::PARAM_STR);
$sth->execute();

$sth->bindValue(':vid', 2, PDO::PARAM_INT);
$sth->bindValue(':question', 'What interests you about open source?', PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows VALUES (?, ?, ?, ?, ?)');
$sth->execute([1, 1, 'Brand new source code for the site', '', 0]);
$sth->execute([2, 1, 'Open source, free to use', '', 0]);
$sth->execute([3, 1, 'XHTML, CSS and Ajax support', '', 0]);
$sth->execute([4, 1, 'All of the above', '', 0]);
$sth->execute([5, 2, 'Constantly improving, modified by the whole world', '', 0]);
$sth->execute([6, 2, 'Free to use', '', 0]);
$sth->execute([7, 2, 'Free to explore, change at will', '', 0]);
$sth->execute([8, 2, 'Suitable for study, research', '', 0]);
$sth->execute([9, 2, 'All of the above', '', 0]);
