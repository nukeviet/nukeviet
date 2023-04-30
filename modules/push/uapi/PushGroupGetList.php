<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\push\uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\push\uapi\PushGroupGetList
 * API dùng để lấy danh sách thông báo đẩy do nhóm gửi đi
 *
 * @package NukeViet\Module\push\uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class PushGroupGetList implements UiApi
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

        include NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';

        $group_id = $nv_Request->get_int('group_id', 'post', 0);
        if (empty($group_id) or !in_array($group_id, $u_groups, true)) {
            return $this->result->setError()
                ->setCode('5014')
                ->setMessage($lang_module['group_not_defined'])
                ->getResult();
        }

        $count = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_id)->fetchColumn();
        if (!$count) {
            return $this->result->setError()
                ->setCode('5015')
                ->setMessage($lang_module['not_group_manager'])
                ->getResult();
        }

        $where = "(mtb.sender_role='group' AND mtb.sender_group=" . $group_id . ')';

        $postdata = [];
        $postdata['page'] = $nv_Request->get_int('page', 'post', 1);
        $postdata['per_page'] = $nv_Request->get_int('per_page', 'post', 20);
        $postdata['filter'] = $nv_Request->get_title('filter', 'post', '');

        if ($postdata['filter'] == 'active') {
            $where .= ' AND (mtb.add_time <= ' . NV_CURRENTTIME . ' AND (mtb.exp_time = 0 OR mtb.exp_time > ' . NV_CURRENTTIME . '))';
        } elseif ($postdata['filter'] == 'waiting') {
            $where .= ' AND (mtb.add_time > ' . NV_CURRENTTIME . ')';
        } elseif ($postdata['filter'] == 'expired') {
            $where .= ' AND (mtb.exp_time != 0 AND mtb.exp_time < ' . NV_CURRENTTIME . ')';
        }

        $db->sqlreset()
            ->select('COUNT(*)')
            ->from(NV_PUSH_GLOBALTABLE . ' AS mtb')
            ->where($where);
        $num_items = $db->query($db->sql())
            ->fetchColumn();
        $this->result->set('total', $num_items);

        $db->select('mtb.*, (SELECT COUNT(*) FROM ' . NV_PUSH_STATUS_GLOBALTABLE . ' WHERE pid = mtb.id AND viewed_time != 0) AS views')
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
