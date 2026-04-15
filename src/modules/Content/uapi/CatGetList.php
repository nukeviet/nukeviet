<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Uapi;

use NukeViet\Module\Content\Cat\CatRepository;
use NukeViet\Module\Content\Shared\BaseUapi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class CatGetList extends BaseUapi
{
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
