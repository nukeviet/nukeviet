<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$list = $nv_Request->get_title('list', 'post,get', '');
if (empty($list) or !csrf_check($nv_Request->get_string('checkss', 'post,get'), $csrf_key)) {
    exit('Stop!!!');
}
$arr_id = array_map('intval', array_unique(array_filter(explode(',', $list))));

foreach ($arr_id as $id) {
    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET topicid=0 WHERE id = ' . $id);
}

nv_htmlOutput($nv_Lang->getModule('topic_delete_success'));
