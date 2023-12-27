<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Api;

/**
 * NukeViet\Api\DoApi
 *
 * @package NukeViet\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class DoApiRewrite extends DoApi
{
    /**
     * @param string $apiurl
     * @param string $apikey
     * @param string $apisecret
     */
    public function __construct($apiurl, $apikey, $apisecret)
    {
        parent::__construct($apiurl, $apikey, $apisecret, true);
    }
}
