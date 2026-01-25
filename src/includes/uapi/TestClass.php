<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Uapi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * @package NukeViet\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class TestClass implements UiApi
{
    private $result;

    /**
     * getCat()
     *
     * @return string
     */
    public static function getCat()
    {
        return 'myCat';
    }

    /**
     * setResultHander()
     */
    public function setResultHander(UapiResult $result)
    {
        $this->result = $result;
    }

    /**
     * execute()
     *
     * @return mixed
     */
    public function execute()
    {
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
