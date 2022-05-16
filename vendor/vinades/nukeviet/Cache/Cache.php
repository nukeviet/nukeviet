<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Cache;

/**
 * NukeViet\Cache\Cache
 *
 * @package NukeViet\Cache
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Cache
{
    protected $_Lang = 'vi';

    protected $_Cache_Prefix = '';

    protected $_Db;

    /**
     * __construct()
     * 
     * @param mixed $Lang 
     * @param mixed $Cache_Prefix 
     */
    public function __construct($Lang, $Cache_Prefix)
    {
        $this->_Lang = $Lang;
        $this->_Cache_Prefix = $Cache_Prefix;
    }

    /**
     * setDb()
     * 
     * @param mixed $db 
     */
    public function setDb($db)
    {
        $this->_Db = $db;
    }

    /**
     * getList()
     * 
     * @param mixed $sql 
     * @param mixed $key 
     * @return array|false 
     */
    protected function getList($sql, $key)
    {
        $list = false;

        if (($result = $this->_Db->query($sql)) !== false) {
            $list = [];
            $a = 0;
            while ($row = $result->fetch()) {
                $key2 = (!empty($key) and isset($row[$key])) ? $row[$key] : $a;
                $list[$key2] = $row;
                ++$a;
            }
            $result->closeCursor();
        }

        return $list;
    }
}
