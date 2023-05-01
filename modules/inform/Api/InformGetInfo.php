<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
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
 * NukeViet\Module\inform\Api\InformGetInfo
 * API dùng để lấy thông tin của thông báo theo ID
 *
 * @package NukeViet\Module\inform\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class InformGetInfo implements IApi
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
        $where = [];
        if ($admin_lev > Api::ADMIN_LEV_SP) {
            $where[] = '(mtb.sender_admin=' . $admin_id . ')';
        }

        $postdata = [];
        $postdata['id'] = $nv_Request->get_int('id', 'post', 0);
        // Nếu thông báo chưa được xác định
        if (empty($postdata['id'])) {
            return $this->result->setError()
                ->setCode('5003')
                ->setMessage($lang_module['notification_not_exist'])
                ->getResult();
        }

        $where[] = '(mtb.id = ' . $postdata['id'] . ')';
        $sql = 'SELECT * FROM ' . NV_INFORM_GLOBALTABLE . ' AS mtb WHERE ' . implode(' AND ', $where);
        $data = $db->query($sql)->fetch();
        if (empty($data)) {
            return $this->result->setError()
                ->setCode('5004')
                ->setMessage($lang_module['notification_not_exist'])
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
