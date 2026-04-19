<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\inform\Uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;
use PDO;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\inform\Uapi\InformGetList
 * API dùng để lấy danh sách thông báo gửi đến người dùng hiện tại
 *
 * @package NukeViet\Module\inform\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
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

        $page = $nv_Request->get_page('page', 'post', 1);
        $per_page = $nv_Request->get_page('per_page', 'post', 20);
        $filter = $nv_Request->get_title('filter', 'post', '');
        !in_array($filter, ['unviewed', 'favorite', 'hidden'], true) && $filter = '';

        $where_arr = [];
        $params = [];
        $where_arr[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
        if (!empty($u_groups)) {
            $where_arr[] = "(mtb.receiver_grs != '' AND (CONCAT(',', mtb.receiver_grs, ',') REGEXP :u_groups_regexp))";
            $params[':u_groups_regexp'] = [',(' . implode('|', $u_groups) . '),', PDO::PARAM_STR];
        }
        $where_arr[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(:userid, mtb.receiver_ids))";
        $params[':userid'] = [$user_id, PDO::PARAM_INT];

        $where_str = '(' . implode(' OR ', $where_arr) . ') AND (mtb.add_time <= :current_time1) AND (mtb.exp_time = 0 OR mtb.exp_time > :current_time2)';
        $params[':current_time1'] = [NV_CURRENTTIME, PDO::PARAM_INT];
        $params[':current_time2'] = [NV_CURRENTTIME, PDO::PARAM_INT];

        if (!empty($u_groups)) {
            $where_str .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . implode(',', array_map('intval', $u_groups)) . ")))";
        } else {
            $where_str .= " AND (mtb.sender_role != 'group')";
        }

        if ($filter == 'unviewed') {
            $where_str .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.userid = :userid AND (exc.viewed_time != 0 OR exc.hidden_time != 0))';
        } elseif ($filter == 'favorite') {
            $where_str .= ' AND mtb.id IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.userid = :userid AND (exc.favorite_time != 0 AND exc.hidden_time = 0))';
        } elseif ($filter == 'hidden') {
            $where_str .= ' AND mtb.id IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.userid = :userid AND exc.hidden_time != 0)';
        } else {
            $where_str .= ' AND mtb.id NOT IN (SELECT exc.pid FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS exc WHERE exc.userid = :userid AND exc.hidden_time != 0)';
        }

        $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . $where_str);
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->execute();
        $num_items = $sth->fetchColumn();
        $this->result->set('total', $num_items);

        $sth = $db->prepare('SELECT mtb.id, mtb.sender_role, mtb.sender_group, mtb.sender_admin, mtb.message, mtb.link, mtb.add_time, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time
            FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb
            LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = :userid)
            WHERE ' . $where_str . '
            ORDER BY mtb.add_time DESC
            LIMIT :limit OFFSET :offset');
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->bindValue(':limit', $per_page, PDO::PARAM_INT);
        $sth->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
        $sth->bindValue(':userid', $user_id, PDO::PARAM_INT);
        $sth->execute();

        $items = [];
        while ($row = $sth->fetch()) {
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
