<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_COMMENT')) {
    exit('Stop!!!');
}

$nv_BotManager->setPrivate()->printToHeaders();
$difftimeout = 360;

$cid = $nv_Request->get_int('cid', 'post');
$checkss = $nv_Request->get_string('checkss', 'post');

if ($cid > 0 and $checkss == md5($cid . '_' . NV_CHECK_SESSION)) {
    if ($nv_Request->isset_request($module_data . '_like_' . $cid, 'cookie')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => $nv_Lang->getModule('like_unsuccess')
        ]);
    }

    $nv_Request->set_Cookie($module_data . '_like_' . $cid, 1, 86400);

    $_sql = 'SELECT cid, likes, dislikes FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE cid=' . $cid;
    $row = $db->query($_sql)->fetch();
    if (isset($row['cid'])) {
        $like = $nv_Request->get_int('like', 'post');
        $query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET';

        if ($like > 0) {
            $count = nv_number_format($row['likes'] + 1);
            $query .= ' likes=likes+1';
        } else {
            $count = nv_number_format($row['dislikes'] + 1);
            $query .= ' dislikes=dislikes+1';
        }
        $query .= ' WHERE cid=' . $cid;
        $db->query($query);

        nv_jsonOutput([
            'status' => 'success',
            'mess' => '',
            'mode' => $like > 0 ? 'like' : 'dislike',
            'count' => $count,
            'cid' => $cid
        ]);
    }
}

nv_jsonOutput([
    'status' => 'error',
    'mess' => $nv_Lang->getModule('comment_unsuccess')
]);
