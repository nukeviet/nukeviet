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
 * NukeViet\Module\inform\uapi\InformGroupGetInfo
 * API dùng để lấy thông tin của thông báo theo ID
 *
 * @package NukeViet\Module\inform\uapi
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class InformGroupGetInfo implements UiApi
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

        $count = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id=' . $group_id . ' AND is_leader=1 AND userid=' . $user_id)->fetchColumn();
        if (!$count) {
            return $this->result->setError()
                ->setCode('5015')
                ->setMessage($nv_Lang->getModule('not_group_manager'))
                ->getResult();
        }

        $where = "(mtb.sender_role='group' AND mtb.sender_group=" . $group_id . ')';

        $postdata = [];
        $postdata['id'] = $nv_Request->get_int('id', 'post', 0);
        // Nếu thông báo chưa được xác định
        if (empty($postdata['id'])) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($nv_Lang->getModule('notification_not_exist'))
                ->getResult();
        }
        $where .= ' AND (mtb.id=' . $postdata['id'] . ')';
        $db->sqlreset()
            ->select('*')
            ->from(NV_INFORM_GLOBALTABLE . ' AS mtb')
            ->where($where);
        $result = $db->query($db->sql());
        $data = $result->fetch();
        // Nếu thông báo không tồn tại trong CSDL
        if (empty($data)) {
            return $this->result->setError()
                ->setCode('5004')
                ->setMessage($nv_Lang->getModule('notification_not_exist'))
                ->getResult();
        }

        $messages = json_decode($data['message'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['message'] = $messages;
        }
        $this->result->set('info', $data);
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
