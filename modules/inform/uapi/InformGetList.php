<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
 * NukeViet\Module\inform\uapi\InformGetList
 * API dùng để lấy danh sách thông báo gửi đến người dùng hiện tại
 *
 * @package NukeViet\Module\inform\uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class InformGetList implements UiApi
{
    private $result;

    /**
     * @return string
     */
    public static function getCat()
    {
        return 'Get';
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
        $user_groups = Uapi::getUserGroups();
        $u_groups = array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $user_groups)));

        $page = $nv_Request->get_int('page', 'post', 1);
        $per_page = $nv_Request->get_int('per_page', 'post', 20);
        $filter = $nv_Request->get_title('filter', 'post', '');
        !in_array($filter, ['unviewed', 'favorite', 'hidden'], true) && $filter = '';

        $where = [];
        $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
        if (!empty($u_groups)) {
            $where[] = "(mtb.receiver_grs != '' AND (CONCAT(',', mtb.receiver_grs, ',') REGEXP ',(" . implode('|', $u_groups) . "),'))";
        }
        $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $user_id . ', mtb.receiver_ids))';
        $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
        if (!empty($u_groups)) {
            $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . implode(',', $u_groups) . ')))';
        } else {
            $where .= " AND (mtb.sender_role != 'group')";
        }

        if ($filter == 'unviewed') {
            $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_id . ' AND (exc.viewed_time != 0 OR exc.hidden_time != 0))';
        } elseif ($filter == 'favorite') {
            $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_id . ' AND (exc.favorite_time != 0 AND exc.hidden_time = 0))';
        } elseif ($filter == 'hidden') {
            $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_id . ' AND exc.hidden_time != 0)';
        } else {
            $where .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.pid = mtb.id AND exc.userid = ' . $user_id . ' AND exc.hidden_time != 0)';
        }
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->where($where);

        $num_items = $db->query($db->sql())
            ->fetchColumn();
        $this->result->set('total', $num_items);

        $db->select('mtb.id, mtb.sender_role, mtb.sender_group, mtb.sender_admin, mtb.message, mtb.link, mtb.add_time, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time')
            ->join('LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = ' . $user_id . ')')
            ->order('mtb.add_time DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result = $db->query($db->sql());
        $items = [];
        while ($row = $result->fetch()) {
            $messages = json_decode($row['message'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $row['message'] = $messages;
            }
            $links = json_decode($row['link'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $row['link'] = $links;
            }
            $items[$row['id']] = $row;
        }
        $this->result->set('items', $items);

        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
