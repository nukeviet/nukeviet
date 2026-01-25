<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/*
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . " VALUES (:vid, :question, '', 1, 0, 1, '6', " . NV_CURRENTTIME . ', 0, 1, 0)');
$sth->bindValue(':vid', 1, PDO::PARAM_INT);
$sth->bindValue(':question', 'Que savez-vous de Nukeviet 4?', PDO::PARAM_STR);
$sth->execute();

$sth->bindValue(':vid', 2, PDO::PARAM_INT);
$sth->bindValue(':question', 'Qu\'est-ce qui vous intéresse dans l\'open source ?', PDO::PARAM_STR);
$sth->execute();

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows VALUES (?, ?, ?, ?, ?)');
$sth->execute([1, 1, 'Nouveau code source pour le site', '', 0]);
$sth->execute([2, 1, 'Open source, libre d\'utilisation', '', 0]);
$sth->execute([3, 1, 'Prise en charge de XHTML, CSS et Ajax', '', 0]);
$sth->execute([4, 1, 'Tout ce qui précède', '', 0]);
$sth->execute([5, 2, 'En constante amélioration, modifié par le monde entier', '', 0]);
$sth->execute([6, 2, 'Utilisation gratuite', '', 0]);
$sth->execute([7, 2, 'Libre d\'explorer, de changer à volonté', '', 0]);
$sth->execute([8, 2, 'Convient pour l\'étude, la recherche', '', 0]);
$sth->execute([9, 2, 'Tout ce qui précède', '', 0]);