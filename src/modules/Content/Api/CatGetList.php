<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Api;

use NukeViet\Api\Api;
use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Shared\BaseApi;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatGetList extends BaseApi
{
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    public static function getCat()
    {
        return 'content';
    }

    public function execute()
    {
        $this->bootstrap();

        $repo = new CatRepository(
            $this->db,
            $this->tables,
            $this->cache,
            $this->module_name
        );

        $entities = $repo->getAll();
        $items = array_map(fn($e) => $e->toArray(), $entities);

        $this->result->set('items', $items);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
