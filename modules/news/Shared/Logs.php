<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\news\Shared;

use NukeViet\Module\news\Log\Log;

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 *
 */
class Logs
{
    const KEY_CHANGE_STATUS = 'change_status';

    /**
     * @param integer $row_id
     * @param integer $status
     * @param number $userid
     * @return number
     */
    public static function saveLogStatusPost($row_id, $status, $userid = 0)
    {
        $log = new Log([
            'log_key' => Logs::KEY_CHANGE_STATUS,
            'note' => '',
            'set_time' => NV_CURRENTTIME
        ]);
        $log->setSid($row_id);
        $log->setStatus($status);
        if (empty($userid)) {
            global $admin_info, $user_info;

            if (defined('NV_IS_ADMIN')) {
                $userid = $admin_info['admin_id'];
            } elseif (defined('NV_IS_USER')) {
                $userid = $user_info['userid'];
            }
        }
        $log->setUserid($userid);
        return $log->save();
    }
}
