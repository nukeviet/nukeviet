<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Chú ý namespace PSR-4 chuẩn NukeViet 5: NukeViet\Module\[tên_module]\Api
namespace NukeViet\Module\TenModule\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Class GetList
 * API dùng để lấy danh sách
 */
class GetList implements IApi
{
    private $result;

    /**
     * Mức quyền Admin tối thiểu cần thiết để gọi API này
     * Api::ADMIN_LEV_GOD (1), Api::ADMIN_LEV_SP (2), Api::ADMIN_LEV_MOD (3)
     */
    public static function getAdminLev()
    {
        return Api::ADMIN_LEV_MOD;
    }

    /**
     * Danh mục cấu hình quyền API
     */
    public static function getCat()
    {
        return 'Get'; // Hoặc rỗng ''
    }

    /**
     * Nhận đối tượng xử lý kết quả
     */
    public function setResultHander(ApiResult $result)
    {
        $this->result = $result;
    }

    /**
     * Logic chính
     */
    public function execute()
    {
        global $db, $nv_Request;

        // Lấy thông tin module & admin đang execute
        $module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $module_data = $module_info['module_data'];
        $admin_id = Api::getAdminId();
        $admin_lev = Api::getAdminLev();

        // Bắt buộc dùng $nv_Request, cấm $_POST/$_GET trực tiếp
        $page = $nv_Request->get_page('page', 'post', 1);
        $per_page = $nv_Request->get_page('per_page', 'post', 20);

        // Xử lý Logic (ví dụ lấy danh sách)
        $db->sqlreset()
            ->select('COUNT(*)')
            ->from($module_data . '_main AS mtb');
        $num_items = $db->query($db->sql())->fetchColumn();
        $this->result->set('total', $num_items);

        $db->select('mtb.*')
            ->order('mtb.id DESC')
            ->limit($per_page)
            ->offset(($page - 1) * $per_page);
        $result = $db->query($db->sql());
        $items = [];
        while ($row = $result->fetch()) {
            $items[$row['id']] = $row;
        }
        $this->result->set('items', $items);

        // Thành công: setSuccess() rồi getResult()
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
