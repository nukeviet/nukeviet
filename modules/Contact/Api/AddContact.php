<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jun 20, 2010 8:59:32 PM
 */

namespace NukeViet\Module\Contact\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

class AddContact implements IApi
{
    private $result;

    /**
     *
     */
    public function __construct()
    {
        // @TODO làm gì ở đây thì viết sau giờ chưa nghĩ ra :D
    }

    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::setModule()
     */
    public function setModule()
    {
        // @TODO làm gì ở đây thì viết sau giờ chưa nghĩ ra :D
    }

    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * @return string
     */
    public static function getCat()
    {
        return '';
    }

    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->$result = $result;
    }

    /**
     * {@inheritDoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $nv_Request;

        // @TODO code xử lý đặt ở đây, muốn viết gì viết thoải mái

        $asdasd = $nv_Request->get_title('asdasd', 'post', 'KHONG CO DU LIEU DAU VAO');

        return  $this->$result->getResult();
    }
}
