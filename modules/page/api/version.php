<?php

namespace NukeViet\Module\Page\Api;

use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

class Version implements IApi
{
    public function __construct()
    {
        die('API Version Here');
    }

    public function setModule()
    {
        //
    }
    public static function getAdminLev()
    {}

    public static function getCat()
    {}

    public static function getCmd()
    {}
    public function setResultHander(ApiResult $result)
    {}

    public function execute()
    {}
}
