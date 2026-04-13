<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;
use NukeViet\Module\Content\Cat\CatRepository;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatGetList implements UiApi
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
        global $db, $nv_Cache;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];

        $repo = new CatRepository(
            $db,
            NV_PREFIXLANG . '_' . $module_data,
            $nv_Cache,
            $module_name
        );

        // Uapi should only get active categories
        $entities = $repo->getAll();
        $items = [];
        foreach ($entities as $entity) {
            if ($entity->status == 1) {
                $items[] = $entity->toArray();
            }
        }

        $this->result->set('items', $items);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
