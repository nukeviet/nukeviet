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

class ContentGetList implements IApi
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
        global $db, $nv_Cache, $nv_Request;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];

        $repo = new ContentRepository(
            $db,
            NV_PREFIXLANG . '_' . $module_data,
            $nv_Cache,
            $module_name
        );

        $page = $nv_Request->get_int('page', 'post', 1);
        $per_page = $nv_Request->get_int('per_page', 'post', 20);

        $entities = $repo->getContentList(0, 1, $page, $per_page);
        $items = array_map(fn($e) => $e->toArray(), $entities);
        $total = $repo->countActive();

        $this->result->set('total', $total);
        $this->result->set('items', $items);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
