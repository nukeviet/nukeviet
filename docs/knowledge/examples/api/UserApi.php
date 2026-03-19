<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Không gian Uapi
namespace NukeViet\Module\TenModule\Uapi;

use NukeViet\Uapi\Uapi;
use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Class GetInfo
 * API công khai (User API)
 */
class GetInfo implements UiApi
{
    private $result;

    /**
     * Danh mục cấu hình quyền API
     * UiApi KHÔNG CÓ getAdminLev()
     */
    public static function getCat()
    {
        return '';
    }

    /**
     * Nhận đối tượng xử lý
     */
    public function setResultHander(UapiResult $result)
    {
        $this->result = $result;
    }

    /**
     * Logic chính
     */
    public function execute()
    {
        global $nv_Request, $db_slave;

        // Lấy thông tin user nếu có
        $userid = Uapi::getUserId();

        // Logic xử lý
        $items = [];

        // Dùng set($key, $data) để thêm dữ liệu vào kết quả
        $this->result->set('items', $items);
        $this->result->set('userid', $userid);

        // Thành công
        $this->result->setSuccess();

        return $this->result->getResult();
    }
}
