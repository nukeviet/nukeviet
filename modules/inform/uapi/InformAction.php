<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
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
 * NukeViet\Module\inform\uapi\InformAction
 * API dùng để Thay đổi trạng thái của thông báo: đã xem/chưa xem, đã ẩn/chưa ẩn, yêu thích/Hủy yêu thích
 *
 * @package NukeViet\Module\inform\uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class InformAction implements UiApi
{
    private $result;

    /**
     * @return string
     */
    public static function getCat()
    {
        return 'Action';
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
        global $db, $nv_Request, $lang_module;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $user_id = Uapi::getUserId();
        $user_groups = Uapi::getUserGroups();
        $u_groups = array_values(array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $user_groups))));

        $id = $nv_Request->get_int('id', 'post', 0);
        $status = $nv_Request->get_title('setStatus', 'post', '');

        if (empty($id)) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($lang_module['notification_not_exist'])
                ->getResult();
        }

        $where = [];
        $where[] = "(mtb.receiver_grs = '' AND mtb.receiver_ids = '')";
        if (!empty($u_groups)) {
            $wh = [];
            foreach ($u_groups as $gr) {
                $wh[] = 'FIND_IN_SET(' . $gr . ', mtb.receiver_grs)';
            }
            $wh = implode(' OR ', $wh);
            $where[] = "(mtb.receiver_grs != '' AND (" . $wh . '))';
        }
        $where[] = "(mtb.receiver_ids != '' AND FIND_IN_SET(" . $user_id . ', mtb.receiver_ids))';
        $where = '(' . implode(' OR ', $where) . ') AND (mtb.add_time <= ' . NV_CURRENTTIME . ') AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . ')';
        if (!empty($u_groups)) {
            $where .= " AND (mtb.sender_role != 'group' OR (mtb.sender_role = 'group' AND mtb.sender_group IN (" . implode(',', $u_groups) . ')))';
        } else {
            $where .= " AND (mtb.sender_role != 'group')";
        }
        $db->select('mtb.id, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time, IFNULL(jtb.hidden_time, 0) AS hidden_time')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->join('LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = ' . $user_id . ')')
            ->where($where . ' AND mtb.id=' . $id);
        $result = $db->query($db->sql());
        $row = $result->fetch();
        if (empty($row['id'])) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($lang_module['notification_not_exist'])
                ->getResult();
        }

        if (!in_array($status, ['viewed', 'unviewed', 'favorite', 'unfavorite', 'hidden', 'unhidden'], true)) {
            return $this->result->setError()
                ->setCode('5013')
                ->setMessage($lang_module['unknown_new_status'])
                ->getResult();
        }

        switch ($status) {
            case 'viewed':
                $field_name = 'viewed_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unviewed':
                $field_name = 'viewed_time';
                $field_value = 0;
                break;
            case 'favorite':
                $field_name = 'favorite_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unfavorite':
                $field_name = 'favorite_time';
                $field_value = 0;
                break;
            case 'hidden':
                $field_name = 'hidden_time';
                $field_value = NV_CURRENTTIME;
                break;
            case 'unhidden':
                $field_name = 'hidden_time';
                $field_value = 0;
                break;
        }

        if (empty($row['shown_time']) and empty($row['viewed_time']) and empty($row['favorite_time']) and empty($row['hidden_time'])) {
            $db->query('INSERT IGNORE INTO ' . NV_INFORM_STATUS_GLOBALTABLE . ' (pid, userid, ' . $field_name . ') VALUES (' . $id . ', ' . $user_id . ', ' . $field_value . ')');
        } else {
            $db->query('UPDATE ' . NV_INFORM_STATUS_GLOBALTABLE . ' SET ' . $field_name . ' = ' . $field_value . ' WHERE pid=' . $id . ' AND userid=' . $user_id);
        }

        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
