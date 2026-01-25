<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\news\Log;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Log
{
    private $array = [];

    /**
     * @param array $array
     */
    public function __construct($array)
    {
        $this->array = $array;

        return $this;
    }

    /**
     * @param int $sid
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setSid($sid)
    {
        $this->array['sid'] = (int) $sid;

        return $this;
    }

    /**
     * @param int $status
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setStatus($status)
    {
        $this->array['status'] = (int) $status;

        return $this;
    }

    /**
     * @param int $userid
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setUserid($userid)
    {
        $this->array['userid'] = (int) $userid;

        return $this;
    }

    /**
     * @return number
     */
    public function save()
    {
        global $db, $module_data;

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_logs (
            sid, userid, log_key, status, note, set_time
        ) VALUES (
            ' . $this->array['sid'] . ', ' . $this->array['userid'] . ",
            '" . $this->array['log_key'] . "', " . $this->array['status'] . ',
            ' . $db->quote($this->array['note']) . ', ' . $this->array['set_time'] . '
        )';

        return $db->exec($sql);
    }
}
