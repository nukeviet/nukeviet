<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;
use NukeViet\Module\Content\Content\ContentRepository;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class ContentGetList implements UiApi
{
    private $result;

    public static function getCat()
    {
        return 'content';
    }

    public function setResultHander(UapiResult $result)
    {
        $this->result = $result;
    }

    public function execute()
    {
        global $db, $nv_Cache, $nv_Request, $module_config;

        $module_name = Uapi::getModuleName();
        $config = $module_config[$module_name];

        $repo = new ContentRepository(
            $db,
            $config['table_row'],
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
