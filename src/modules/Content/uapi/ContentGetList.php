<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @license GNU/GPL version 2 or any later version
 */

namespace NukeViet\Module\Content\Uapi;

use NukeViet\Module\Content\Content\ContentRepository;
use NukeViet\Module\Content\Shared\BaseUapi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class ContentGetList extends BaseUapi
{
    public static function getCat()
    {
        return 'content';
    }

    public function execute()
    {
        global $nv_Request;
        $this->bootstrap();

        $repo = new ContentRepository(
            $this->db,
            $this->tables,
            $this->cache,
            $this->module_name
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
