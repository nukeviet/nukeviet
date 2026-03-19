<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$sql_create_table = [
    'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_items ('
    . ' `id`         MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,'
    . ' `title`      VARCHAR(255) NOT NULL,'
    . ' `alias`      VARCHAR(255) NOT NULL DEFAULT \'\','
    . ' `content`    MEDIUMTEXT  NOT NULL,'
    . ' `status`     TINYINT(1)  NOT NULL DEFAULT \'1\','
    . ' `order`      SMALLINT(5) UNSIGNED NOT NULL DEFAULT \'0\','
    . ' `created_at` INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
    . ' `updated_at` INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
    . ' `author_id`  INT(11)     UNSIGNED NOT NULL DEFAULT \'0\','
    . ' PRIMARY KEY (`id`),'
    . ' KEY `idx_status` (`status`, `created_at`),'
    . ' UNIQUE KEY `uq_alias` (`alias`)'
    . ') ENGINE=InnoDB COMMENT \'Table description\''
];
