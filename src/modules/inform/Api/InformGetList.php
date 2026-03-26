<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\inform\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use PDO;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\inform\Api\InformGetList
 * API dùng để lấy danh sách thông báo
 *
 * @package NukeViet\Module\inform\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
class InformGetList implements IApi
{
    private $result;

    /**
     * @return number
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * @return string
     */
    public static function getCat()
    {
        return 'Get';
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::setResultHander()
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     * @see \NukeViet\Api\IApi::execute()
     */
    public function execute()
    {
        global $db, $nv_Request;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $admin_id = Api::getAdminId();
        $admin_lev = Api::getAdminLev();

        $postdata = [];
        $postdata['page'] = $nv_Request->get_page('page', 'post', 1);
        $postdata['per_page'] = $nv_Request->get_page('per_page', 'post', 20);
        $postdata['filter'] = $nv_Request->get_title('filter', 'post', '');

        $params = [];
        if ($admin_lev > Api::ADMIN_LEV_SP) {
            $where[] = '(mtb.sender_admin = :sender_admin)';
            $params[':sender_admin'] = [$admin_id, PDO::PARAM_INT];
        } else {
            !in_array($postdata['filter'], ['system', 'group', 'admins', 'admin', 'active', 'waiting', 'expired'], true) && $postdata['filter'] = '';
            if ($postdata['filter'] == 'system') {
                $where[] = "(mtb.sender_role = 'system')";
            } elseif ($postdata['filter'] == 'group') {
                $where[] = "(mtb.sender_role = 'group')";
            } elseif ($postdata['filter'] == 'admins') {
                $where[] = "(mtb.sender_role = 'admin')";
            } elseif ($postdata['filter'] == 'admin') {
                $where[] = "(mtb.sender_role = 'admin' AND mtb.sender_admin = :sender_admin)";
                $params[':sender_admin'] = [$admin_id, PDO::PARAM_INT];
            } elseif ($postdata['filter'] == 'active') {
                $where[] = '(mtb.add_time <= :current_time AND (mtb.exp_time = 0 OR mtb.exp_time > :current_time))';
                $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
            } elseif ($postdata['filter'] == 'waiting') {
                $where[] = '(mtb.add_time > :current_time)';
                $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
            } elseif ($postdata['filter'] == 'expired') {
                $where[] = '(mtb.exp_time != 0 AND mtb.exp_time < :current_time)';
                $params[':current_time'] = [NV_CURRENTTIME, PDO::PARAM_INT];
            }
        }

        $where_str = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

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
