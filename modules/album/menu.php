<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

// $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_cat WHERE status=1 OR status=2 ORDER BY sort ASC';
// $result = $db->query($sql);
// while ($row = $result->fetch()) {
//     $array_item[$row['catid']] = [
//         'parentid' => $row['parentid'],
//         'groups_view' => $row['groups_view'],
//         'key' => $row['catid'],
//         'title' => $row['title'],
//         'alias' => $row['alias']
//     ];
// }

// $sql = 'SELECT id, title, alias FROM ' . NV_PREFIXLANG . '_' . $mod_data . ' WHERE status=1 ORDER BY weight ASC';
// $result = $db->query($sql);
// while ($row = $result->fetch()) {
//     $array_item[$row['id']] = array(
//         'key' => $row['id'],
//         'title' => $row['title'],
//         'alias' => $row['alias'] . $global_config['rewrite_exturl']
//     );
// }
