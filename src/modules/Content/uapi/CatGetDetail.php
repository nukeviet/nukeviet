<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Uapi;

use NukeViet\Uapi\UapiResult;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Shared\BaseUapi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatGetDetail extends BaseUapi
{
    public static function getCat()
    {
        return 'content';
    }

    public function execute()
    {
        global $nv_Request, $nv_Lang;
        $this->bootstrap();

        $repo = new CatRepository(
            $this->db,
            $this->tables,
            $this->cache,
            $this->module_name
        );

        $catid = $nv_Request->get_int('catid', 'post', 0);
        if ($catid <= 0) {
            $this->result->setCode(UapiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_data'));
            return $this->result->getResult();
        }

        $entity = $repo->findById($catid);
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
