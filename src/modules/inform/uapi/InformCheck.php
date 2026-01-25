<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\inform\uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\inform\uapi\InformCheck
 * API dùng để kiểm tra thông báo mới
 *
 * @package NukeViet\Module\inform\uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class InformCheck implements UiApi
{
    private $result;

    /**
     * @return string
     */
    public static function getCat()
    {
        return 'Check';
    }

    /**
     * setResultHander()
     *
     * @return mixed
     */
    public function setResultHander(UapiResult $result)
    {
        $this->result = $result;
    }

    /**
     * execute()
     *
     * @return mixed
     */
    public function execute()
    {
        global $db, $nv_Request;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $user_id = Uapi::getUserId();

        $count = 0;
        $groups = $nv_Request->get_title('groups', 'post', '');
        $groups = preg_replace('/[^0-9\,]+/', '', $groups);
        $where = [];
        $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
        if (!empty($groups)) {
            $where[] = "(mtb.receiver_grs != '' AND (CONCAT(',', mtb.receiver_grs, ',') REGEXP ',(" . str_replace(',', '|', $groups) . "),'))";
        }
        $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $user_id . ', mtb.receiver_ids))';
        $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
        if (!empty($groups)) {
            $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . $groups . ')))';
        } else {
            $where .= " AND (mtb.sender_role != 'group')";
        }

        $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE (exc.pid = mtb.id AND exc.userid = ' . $user_id . ') AND (exc.shown_time != 0 OR exc.hidden_time != 0))';
        $sql = 'SELECT mtb.id FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . $where;
        $result = $db->query($sql);
        if ($result) {
            $count = $result->rowCount();
        }

        $this->result->set('count', $count);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
