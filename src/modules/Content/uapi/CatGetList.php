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
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Shared\Tables;

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
        global $db, $nv_Cache, $module_config, $site_mods;

        $module_name = Uapi::getModuleName();
        $tables = new Tables(NV_PREFIXLANG, $site_mods[$module_name]['module_data']);

        $repo = new CatRepository(
            $db,
            $tables,
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
