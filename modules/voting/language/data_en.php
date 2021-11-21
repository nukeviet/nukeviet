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
$sth->bindValue(':vid', 2, PDO::PARAM_INT);
$sth->bindValue(':question', 'Do you know about Nukeviet 3?', PDO::PARAM_STR);
$sth->execute();

$sth->bindValue(':vid', 3, PDO::PARAM_INT);
$sth->bindValue(':question', 'What are you interested in open source?', PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows VALUES (?, ?, ?, ?, ?)');
$sth->execute([5, 2, 'A whole new sourcecode for the web.', '', 0]);
$sth->execute([6, 2, 'Open source, free to use.', '', 0]);
$sth->execute([7, 2, 'Use of xHTML, CSS and Ajax support', '', 0]);
$sth->execute([8, 2, 'All the comments on', '', 0]);
$sth->execute([9, 3, 'constantly improved, modified by the whole world.', '', 0]);
$sth->execute([10, 3, 'To use the free of charge.', '', 0]);
$sth->execute([11, 3, 'The freedom to explore, modify at will.', '', 0]);
$sth->execute([12, 3, 'Match to learning and research because the freedom to modify at will.', '', 0]);
$sth->execute([13, 3, 'All comments on', '', 0]);
