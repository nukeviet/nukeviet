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

$id = $nv_Request->get_int('id', 'post', 0);
$bid = $nv_Request->get_int('bid', 'post', 0);
$mod = $nv_Request->get_string('mod', 'post', '');
$new_vid = $nv_Request->get_int('new_vid', 'post', 0);
$del_list = $nv_Request->get_string('del_list', 'post', '');
$content = 'NO_' . $bid;

if ($bid > 0) {
    if ($del_list != '') {
        $array_id = array_map('intval', explode(',', $del_list));
        foreach ($array_id as $id) {
            if ($id > 0) {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . $id);
            }
        }
        nv_news_fix_block($bid);
        $content = 'OK_' . $bid;
    } elseif ($id > 0) {
        list($bid, $id) = $db->query('SELECT bid, id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . (int) $bid . ' AND id=' . (int) $id)->fetch(3);
        if ($bid > 0 and $id > 0) {
            if ($mod == 'weight' and $new_vid > 0) {
                $query = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id!=' . $id . ' ORDER BY weight ASC';
                $result = $db->query($query);

                $weight = 0;
                while ($row = $result->fetch()) {
                    ++$weight;
                    if ($weight == $new_vid) {
                        ++$weight;
                    }
                    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $weight . ' WHERE bid=' . $bid . ' AND id=' . (int) ($row['id']);
                    $db->query($sql);
                }

                $result->closeCursor();
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_block SET weight=' . $new_vid . ' WHERE bid=' . $bid . ' AND id=' . (int) $id;
                $db->query($sql);

                $content = 'OK_' . $bid;
            } elseif ($mod == 'delete') {
                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_block WHERE bid=' . $bid . ' AND id=' . (int) $id);
                $content = 'OK_' . $bid;
            }
        }
    }

    nv_news_fix_block($bid);
    $nv_Cache->delMod($module_name);
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';
