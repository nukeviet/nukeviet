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
 * NukeViet\Module\inform\Uapi\InformGroupGetList
 * API dùng để lấy danh sách thông báo do nhóm gửi đi
 *
 * @package NukeViet\Module\inform\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class InformGroupGetList implements UiApi
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
        global $db, $nv_Request, $nv_Lang;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $user_id = Uapi::getUserId();
        $user_groups = Uapi::getUserGroups();
        $u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $user_groups))));

        $group_id = $nv_Request->get_int('group_id', 'post', 0);
        if (empty($group_id) or !in_array($group_id, $u_groups, true)) {
            return $this->result->setError()
                ->setCode('5014')
                ->setMessage($nv_Lang->getModule('group_not_defined'))
                ->getResult();
        }

        $sth = $db->prepare("SELECT COUNT(*) FROM " . NV_USERS_GLOBALTABLE . "_groups_users WHERE group_id = :group_id AND is_leader = 1 AND userid = :userid");
        $sth->bindValue(':group_id', $group_id, PDO::PARAM_INT);
        $sth->bindValue(':userid', $user_id, PDO::PARAM_INT);
        $sth->execute();
        $count = $sth->fetchColumn();

        if (!$count) {
            return $this->result->setError()
                ->setCode('5015')
                ->setMessage($nv_Lang->getModule('not_group_manager'))
                ->getResult();
        }

        $params = [
            ':group_id' => [$group_id, PDO::PARAM_INT]
        ];
        $where_arr = [
            "mtb.sender_role = 'group'",
            "mtb.sender_group = :group_id"
        ];

        $postdata = [];
        $postdata['page'] = $nv_Request->get_page('page', 'post', 1);
        $postdata['per_page'] = $nv_Request->get_page('per_page', 'post', 20);
        $postdata['filter'] = $nv_Request->get_title('filter', 'post', '');

        if ($postdata['filter'] == 'active') {
            $where_arr[] = '(mtb.add_time <= :current_time1 AND (mtb.exp_time = 0 OR mtb.exp_time > :current_time2))';
            $params[':current_time1'] = [NV_CURRENTTIME, PDO::PARAM_INT];
            $params[':current_time2'] = [NV_CURRENTTIME, PDO::PARAM_INT];
        } elseif ($postdata['filter'] == 'waiting') {
            $where_arr[] = '(mtb.add_time > :current_time)';
            $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
        } elseif ($postdata['filter'] == 'expired') {
            $where_arr[] = '(mtb.exp_time != 0 AND mtb.exp_time < :current_time)';
            $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
        }

        $where_str = ' WHERE ' . implode(' AND ', $where_arr);

        $sth = $db->prepare('SELECT COUNT(*) FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb' . $where_str);
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->execute();
        $num_items = $sth->fetchColumn();
        $this->result->set('total', $num_items);

        $sth = $db->prepare('SELECT mtb.*, (SELECT COUNT(*) FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views
            FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb' . $where_str . '
            ORDER BY mtb.add_time DESC
            LIMIT :limit OFFSET :offset');
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->bindValue(':limit', $postdata['per_page'], PDO::PARAM_INT);
        $sth->bindValue(':offset', ($postdata['page'] - 1) * $postdata['per_page'], PDO::PARAM_INT);
        $sth->execute();

        $items = [];
        while ($row = $sth->fetch()) {
            $messages = json_decode($row['message'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $row['message'] = $messages;
            }
            $items[$row['id']] = $row;
        }
        $this->result->set('items', $items);

        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
