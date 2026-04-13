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
use NukeViet\Module\Content\Shared\ContentRepository;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class ContentGetDetail implements UiApi
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
        global $db, $nv_Cache, $nv_Request, $nv_Lang;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];

        $repo = new ContentRepository(
            $db,
            NV_PREFIXLANG . '_' . $module_data,
            $nv_Cache,
            $module_name
        );

        $id = $nv_Request->get_int('id', 'post', 0);
        if ($id <= 0) {
            $this->result->setCode(UapiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_data'));
            return $this->result->getResult();
        }

        $entity = $repo->findById($id);
        if (empty($entity) || !$entity->status) {
            $this->result->setCode(UapiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
