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
 * NukeViet\Module\inform\Uapi\InformAction
 * API dùng để Thay đổi trạng thái của thông báo: đã xem/chưa xem, đã ẩn/chưa ẩn, yêu thích/Hủy yêu thích
 *
 * @package NukeViet\Module\inform\Uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
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
        global $db, $nv_Request, $nv_Lang;

        $module_name = Uapi::getModuleName();
        $module_info = Uapi::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $user_id = Uapi::getUserId();
        $user_groups = Uapi::getUserGroups();
        $u_groups = array_unique(array_filter(array_map(function ($gr) {
            return $gr >= 10 ? (int) $gr : 0;
        }, $user_groups)));

        $id = $nv_Request->get_int('id', 'post', 0);
        $status = $nv_Request->get_title('setStatus', 'post', '');

        if (empty($id)) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($nv_Lang->getModule('notification_not_exist'))
                ->getResult();
        }

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

        $sth = $db->prepare('SELECT mtb.id, IFNULL(jtb.shown_time, 0) AS shown_time, IFNULL(jtb.viewed_time, 0) AS viewed_time, IFNULL(jtb.favorite_time, 0) AS favorite_time, IFNULL(jtb.hidden_time, 0) AS hidden_time
            FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb
            LEFT JOIN ' . NV_INFORM_STATUS_GLOBALTABLE . ' AS jtb ON (jtb.pid = mtb.id AND jtb.userid = :userid)
            WHERE ' . $where_str . ' AND mtb.id = :id');
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        foreach ($params as $key => $val) {
            $sth->bindValue($key, $val[0], $val[1]);
        }
        $sth->execute();
        $row = $sth->fetch();
        $sth->closeCursor();

        if (empty($row['id'])) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($nv_Lang->getModule('notification_not_exist'))
                ->getResult();
        }

        if (!in_array($status, ['viewed', 'unviewed', 'favorite', 'unfavorite', 'hidden', 'unhidden'], true)) {
            return $this->result->setError()
                ->setCode('5013')
                ->setMessage($nv_Lang->getModule('unknown_new_status'))
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
            $sth = $db->prepare('INSERT IGNORE INTO ' . NV_INFORM_STATUS_GLOBALTABLE . ' (pid, userid, ' . $field_name . ') VALUES (:id, :userid, :field_value)');
        } else {
            $sth = $db->prepare('UPDATE ' . NV_INFORM_STATUS_GLOBALTABLE . ' SET ' . $field_name . ' = :field_value WHERE pid = :id AND userid = :userid');
        }
        $sth->bindValue(':id', $id, PDO::PARAM_INT);
        $sth->bindValue(':userid', $user_id, PDO::PARAM_INT);
        $sth->bindValue(':field_value', $field_value, PDO::PARAM_INT);
        $sth->execute();

        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
