<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use NukeViet\Module\Content\Cat\CatRepository;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatGetList implements IApi
{
    private $result;

    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    public static function getCat()
    {
        return 'content';
    }

    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    public function execute()
    {
        global $db, $nv_Cache, $module_config;

        $module_name = Api::getModuleName();
        $config = $module_config[$module_name];

        $repo = new CatRepository(
            $db,
            $config['table_row'],
            $nv_Cache,
            $module_name
        );

        $entities = $repo->getAll();
        $items = array_map(fn($e) => $e->toArray(), $entities);

        $this->result->set('items', $items);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
