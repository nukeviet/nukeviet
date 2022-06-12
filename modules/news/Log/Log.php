<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\news\Log;

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * @author VINADES.,JSC <contact@vinades.vn>
 *
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
     * @param integer $sid
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setSid($sid)
    {
        $this->array['sid'] = intval($sid);
        return $this;
    }

    /**
     * @param integer $status
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setStatus($status)
    {
        $this->array['status'] = intval($status);
        return $this;
    }

    /**
     * @param integer $userid
     * @return \NukeViet\Module\news\Log\Log
     */
    public function setUserid($userid)
    {
        $this->array['userid'] = intval($userid);
        return $this;
    }

    /**
     * @return number
     */
    public function save()
    {
        global $db, $module_data;

        $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_logs (
            sid, userid, log_key, status, note, set_time
        ) VALUES (
            " . $this->array['sid'] . ", " . $this->array['userid'] . ",
            '" . $this->array['log_key'] . "', " . $this->array['status'] . ",
            " . $db->quote($this->array['note']) . ", " . $this->array['set_time'] . "
        )";
        return $db->exec($sql);
    }
}
