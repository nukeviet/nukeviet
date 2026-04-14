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
use NukeViet\Module\Content\Content\ContentRepository;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class ContentGetDetail implements IApi
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
        global $db, $nv_Cache, $nv_Request, $nv_Lang, $module_config;

        $module_name = Api::getModuleName();
        $config = $module_config[$module_name];

        $repo = new ContentRepository(
            $db,
            $config['table_row'],
            $nv_Cache,
            $module_name
        );

        $id = $nv_Request->get_int('id', 'post', 0);
        if ($id <= 0) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_data'));
            return $this->result->getResult();
        }

        $entity = $repo->findById($id);
        if (empty($entity)) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
