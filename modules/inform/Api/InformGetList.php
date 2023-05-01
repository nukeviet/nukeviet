<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\inform\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\inform\Api\InformGetList
 * API dùng để lấy danh sách thông báo
 *
 * @package NukeViet\Module\inform\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
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
        global $db, $nv_Request, $lang_module;

        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];
        $module_file = $module_info['module_file'];
        $admin_id = Api::getAdminId();
        $admin_lev = Api::getAdminLev();

        include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';

        $postdata = [];
        $postdata['page'] = $nv_Request->get_int('page', 'post', 1);
        $postdata['per_page'] = $nv_Request->get_int('per_page', 'post', 20);
        $postdata['filter'] = $nv_Request->get_title('filter', 'post', '');

        $where = [];
        if ($admin_lev > Api::ADMIN_LEV_SP) {
            $where[] = '(mtb.sender_admin=' . $admin_id . ')';
        } else {
            !in_array($postdata['filter'], ['system', 'group', 'admins', 'admin', 'active', 'waiting', 'expired'], true) && $postdata['filter'] = '';
            if ($postdata['filter'] == 'system') {
                $where[] = "(mtb.sender_role = 'system')";
            } elseif ($postdata['filter'] == 'group') {
                $where[] = "(mtb.sender_role = 'group')";
            } elseif ($postdata['filter'] == 'admins') {
                $where[] = "(mtb.sender_role = 'admin')";
            } elseif ($postdata['filter'] == 'admin') {
                $where[] = "(mtb.sender_role = 'admin' AND mtb.sender_admin=" . $admin_id . ')';
            } elseif ($postdata['filter'] == 'active') {
                $where[] = '(mtb.add_time <= ' . NV_CURRENTTIME . ' AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . '))';
            } elseif ($postdata['filter'] == 'waiting') {
                $where[] = '(mtb.add_time > ' . NV_CURRENTTIME . ')';
            } elseif ($postdata['filter'] == 'expired') {
                $where[] = '(mtb.exp_time != 0 AND mtb.exp_time < ' . NV_CURRENTTIME . ')';
            }
        }

        $where = implode(' AND ', $where);
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->where($where);
        $num_items = $db->query($db->sql())
            ->fetchColumn();
        $this->result->set('total', $num_items);

        $db->select('mtb.*, (SELECT COUNT(*) FROM ' . NV_INFORM_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views')
            ->order('mtb.add_time DESC')
            ->limit($postdata['per_page'])
            ->offset(($postdata['page'] - 1) * $postdata['per_page']);
        $result = $db->query($db->sql());
        $items = [];
        while ($row = $result->fetch()) {
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
