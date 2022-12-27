<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\push\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use PDO;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * NukeViet\Module\push\Api\PushAction
 * API dùng để thêm/sửa/xóa thông báo đẩy
 *
 * @package NukeViet\Module\push\Api
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @version 4.6.00
 * @access public
 */
class PushAction implements IApi
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
        return 'Action';
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
        if ($nv_Request->isset_request('action', 'post')) {
            $postdata['action'] = $nv_Request->get_title('action', 'post', '');
        }
        if ($nv_Request->isset_request('id', 'post')) {
            $postdata['id'] = $nv_Request->get_int('id', 'post', 0);
        }
        if ($nv_Request->isset_request('sender_role', 'post')) {
            $postdata['sender_role'] = $nv_Request->get_title('sender_role', 'post', '');
        }
        if ($nv_Request->isset_request('sender_group', 'post')) {
            $postdata['sender_group'] = $nv_Request->get_int('sender_group', 'post', 0);
        }
        if ($nv_Request->isset_request('sender_admin', 'post')) {
            $postdata['sender_admin'] = $nv_Request->get_int('sender_admin', 'post', 0);
        }
        if ($nv_Request->isset_request('receiver_type', 'post')) {
            $postdata['receiver_type'] = $nv_Request->get_title('receiver_type', 'post', '');
        }
        if ($nv_Request->isset_request('receiver_grs', 'post')) {
            $postdata['receiver_grs'] = $nv_Request->get_typed_array('receiver_grs', 'post', 'int', []);
        }
        if ($nv_Request->isset_request('receiver_ids', 'post')) {
            $postdata['receiver_ids'] = $nv_Request->get_typed_array('receiver_ids', 'post', 'int', []);
        }
        if ($nv_Request->isset_request('message', 'post')) {
            $postdata['message'] = $nv_Request->get_title('message', 'post', '');
        }
        if ($nv_Request->isset_request('link', 'post')) {
            $postdata['link'] = $nv_Request->get_title('link', 'post', '');
        }
        if ($nv_Request->isset_request('add_time', 'post')) {
            $postdata['add_time'] = $nv_Request->get_title('add_time', 'post', '');
        }
        if ($nv_Request->isset_request('add_hour', 'post')) {
            $postdata['add_hour'] = $nv_Request->get_int('add_hour', 'post', 0);
        }
        if ($nv_Request->isset_request('add_min', 'post')) {
            $postdata['add_min'] = $nv_Request->get_int('add_min', 'post', 0);
        }
        if ($nv_Request->isset_request('exp_time', 'post')) {
            $postdata['exp_time'] = $nv_Request->get_title('exp_time', 'post', '');
        }
        if ($nv_Request->isset_request('exp_hour', 'post')) {
            $postdata['exp_hour'] = $nv_Request->get_int('exp_hour', 'post', -1);
        }
        if ($nv_Request->isset_request('exp_min', 'post')) {
            $postdata['exp_min'] = $nv_Request->get_int('exp_min', 'post', -1);
        }

        if ($admin_lev > Api::ADMIN_LEV_SP) {
            $postdata['sender_role'] = 'admin';
            $postdata['sender_admin'] = $admin_id;
        }

        // Không có dữ liệu
        if (empty($postdata)) {
            return $this->result->setCode('5001')->setMessage('No data')->getResult();
        }

        // Nếu không xác định được hành động
        if (!in_array($postdata['action'], ['add', 'edit', 'delete'], true)) {
            return $this->result->setCode('5002')->setMessage($lang_module['unspecified_action'])->getResult();
        }

        $where = [];
        if ($admin_lev > Api::ADMIN_LEV_SP) {
            $where[] = '(mtb.sender_admin=' . $admin_id . ')';
        }

        if ($postdata['action'] == 'edit' or $postdata['action'] == 'delete') {
            // Nếu thông báo đẩy chưa được xác định
            if (empty($postdata['id'])) {
                return $this->result->setCode('5003')->setMessage($lang_module['notification_not_exist'])->getResult();
            }

            $where[] = '(mtb.id = ' . $postdata['id'] . ')';
            $exist = $db->query('SELECT COUNT(*) FROM ' . NV_PUSH_GLOBALTABLE . ' AS mtb WHERE ' . implode(' AND ', $where) . ' LIMIT 1')->fetchColumn();
            // Nếu thông báo đẩy không tồn tại
            if (empty($exist)) {
                return $this->result->setCode('5004')->setMessage($lang_module['notification_not_exist'])->getResult();
            }
        }

        ksort($postdata);
        $checkhash = http_build_query($postdata);
        $checkhash = sha1($checkhash);
        $hashreceive = $nv_Request->get_title('checkhash', 'post', '');
        // Nễu mã hash không đúng
        if (strcasecmp($checkhash, $hashreceive) !== 0) {
            return $this->result->setCode('5005')->setMessage($lang_module['api_error_hash'])->getResult();
        }

        // Nếu là xóa thông báo đẩy
        if ($postdata['action'] == 'delete') {
            $db->query('DELETE FROM ' . NV_PUSH_STATUS_GLOBALTABLE . ' WHERE pid = ' . $postdata['id']);
            $db->query('DELETE FROM ' . NV_PUSH_GLOBALTABLE . ' WHERE id = ' . $postdata['id']);
            $db->query('OPTIMIZE TABLE ' . NV_PUSH_STATUS_GLOBALTABLE);
            $db->query('OPTIMIZE TABLE ' . NV_PUSH_GLOBALTABLE);
        }
        // Nếu là thêm/sửa thông báo
        elseif ($postdata['action'] == 'add' or $postdata['action'] == 'edit') {
            !in_array($postdata['sender_role'], ['system', 'group', 'admin'], true) && $postdata['sender_role'] = 'system';
            if ($postdata['sender_role'] == 'system') {
                $postdata['sender_group'] = 0;
                $postdata['sender_admin'] = 0;
            } elseif ($postdata['sender_role'] == 'group') {
                $postdata['sender_admin'] = 0;
                $postdata['receiver_type'] = 'ids';
            } elseif ($postdata['sender_role'] == 'admin') {
                $postdata['sender_group'] = 0;
            }
            !in_array($postdata['receiver_type'], ['grs', 'ids'], true) && $postdata['receiver_type'] = 'ids';
            if ($postdata['receiver_type'] == 'ids') {
                $postdata['receiver_grs'] = [];
            } elseif ($postdata['receiver_type'] == 'grs') {
                $postdata['receiver_ids'] = [];
            }

            // Nếu gửi từ nhóm mà không xác định được nhóm
            if ($postdata['sender_role'] == 'group' and empty($postdata['sender_group'])) {
                return $this->result->setCode('5006')->setMessage($lang_module['please_select_group'])->getResult();
            }
            // Nếu gửi từ admin mà không xác định được ID của admin
            if ($postdata['sender_role'] == 'admin' and empty($postdata['sender_admin'])) {
                return $this->result->setCode('5007')->setMessage($lang_module['please_select_admin'])->getResult();
            }
            // Nếu đối tượng nhận tin nhắn là nhóm mà không xác định được ID của nhóm
            if ($postdata['receiver_type'] == 'grs' and empty($postdata['receiver_grs'])) {
                return $this->result->setCode('5008')->setMessage($lang_module['please_select_receiver_group'])->getResult();
            }
            // Nếu nội dung của thông báo đẩy chưa được xác định
            if (nv_strlen($postdata['message']) < 3) {
                return $this->result->setCode('5009')->setMessage($lang_module['please_enter_content'])->getResult();
            }
            // Nếu có link, nhưng link không hợp lệ
            if (!empty($postdata['link']) and !nv_is_url($postdata['link'], true)) {
                return $this->result->setCode('5010')->setMessage($lang_module['please_enter_valid_link'])->getResult();
            }

            $add_time_array = [];
            // Nếu thời gian bắt đầu của thông báo đẩy không hợp lệ
            if (!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['add_time'], $add_time_array)) {
                return $this->result->setCode('5010')->setMessage($lang_module['please_enter_valid_add_time'])->getResult();
            }
            $exp_time_array = [];
            // Nếu thời gian kết thúc của thông báo đẩy không hợp lệ
            if (!empty($postdata['exp_time']) and !preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})/', $postdata['exp_time'], $exp_time_array)) {
                return $this->result->setCode('5011')->setMessage($lang_module['please_enter_valid_exp_time'])->getResult();
            }

            empty($postdata['sender_admin']) && $postdata['sender_admin'] = $admin_id;
            $postdata['receiver_grs'] = !empty($postdata['receiver_grs']) ? implode(',', $postdata['receiver_grs']) : '';
            $postdata['receiver_ids'] = !empty($postdata['receiver_ids']) ? implode(',', $postdata['receiver_ids']) : '';
            $postdata['message'] = nv_nl2br($postdata['message'], '<br/>');
            if (!empty($postdata['link']) and !preg_match('#^https?\:\/\/#', $postdata['link'])) {
                str_starts_with($postdata['link'], NV_BASE_SITEURL) && $postdata['link'] = substr($postdata['link'], strlen(NV_BASE_SITEURL));
            }
            $postdata['add_time'] = mktime($postdata['add_hour'], $postdata['add_min'], 0, $add_time_array[2], $add_time_array[1], $add_time_array[3]);
            if (!empty($exp_time_array)) {
                $postdata['exp_hour'] == -1 && $postdata['exp_hour'] = 23;
                $postdata['exp_min'] == -1 && $postdata['exp_min'] = 59;
                $postdata['exp_time'] = mktime($postdata['exp_hour'], $postdata['exp_min'], 0, $exp_time_array[2], $exp_time_array[1], $exp_time_array[3]);
            } else {
                $postdata['exp_time'] = 0;
            }

            if (!empty($postdata['id'])) {
                $sth = $db->prepare('UPDATE ' . NV_PUSH_GLOBALTABLE . ' SET 
                receiver_grs = :receiver_grs, receiver_ids = :receiver_ids, sender_role = :sender_role, 
                sender_group = ' . $postdata['sender_group'] . ', sender_admin = ' . $postdata['sender_admin'] . ',
                message = :message, link = :link, add_time = ' . $postdata['add_time'] . ', exp_time = ' . $postdata['exp_time'] . '
                WHERE id = ' . $postdata['id']);
            } else {
                $sth = $db->prepare('INSERT INTO ' . NV_PUSH_GLOBALTABLE . ' 
                (receiver_grs, receiver_ids, sender_role, sender_group, sender_admin, message, link, add_time, exp_time) VALUES 
                (:receiver_grs, :receiver_ids, :sender_role, ' . $postdata['sender_group'] . ', ' . $postdata['sender_admin'] . ', :message, :link, ' . $postdata['add_time'] . ', ' . $postdata['exp_time'] . ')');
            }

            $sth->bindValue(':receiver_grs', $postdata['receiver_grs'], PDO::PARAM_STR);
            $sth->bindValue(':receiver_ids', $postdata['receiver_ids'], PDO::PARAM_STR);
            $sth->bindValue(':sender_role', $postdata['sender_role'], PDO::PARAM_STR);
            $sth->bindValue(':message', $postdata['message'], PDO::PARAM_STR);
            $sth->bindValue(':link', $postdata['link'], PDO::PARAM_STR);
            $sth->execute();
        }

        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
