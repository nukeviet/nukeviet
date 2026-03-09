---
name: nukeviet-api
description: Tạo và làm việc với NukeViet Remote API. Load khi nhắc đến API, tạo endpoint, kết nối từ xa.
allowed-tools: Read, Write, Bash
---

# Hướng Dẫn NukeViet Remote API (NukeViet 5.x)

NukeViet 5 được tích hợp sẵn hệ thống **Remote API** chạy thông qua file `api.php` ở thư mục gốc. Hệ thống này được chia làm 2 phân vùng quyền rõ rệt:

1. **Admin API (`\NukeViet\Api\IApi`)**: Dành cho quản trị viên, đòi hỏi xác thực chặt chẽ (IP, Lev, Quota). Các file được lưu trong `src/modules/[moduleName]/Api/[Action].php`.
2. **User API (`\NukeViet\Uapi\UiApi`)**: Dành cho public hoặc User bình thường, vẫn kiểm soát Quota nhưng bảo mật lỏng hơn (Ví dụ: Ứng dụng mobile cho khách hàng). Các file được lưu trong `src/modules/[moduleName]/Uapi/[Action].php`.

---

## 1. Flow tạo API Endpoint

Bất cứ class API nào của một module (Ví dụ: `news`, action: `get_list`) cũng **BẮT BUỘC** phải:
1. Nằm trong đúng namespace PSR-4: `NukeViet\Module\[moduleName]\Api` (hoặc `Uapi`).
2. Implements interface `\NukeViet\Api\IApi` (hoặc `\NukeViet\Uapi\UiApi`).
3. Khai báo hàm tĩnh `getCat()` để trả về chuỗi cấu hình danh mục.
4. Triển khai phương thức `setResultHander($result)` để nhận biến kết quả.
5. Triển khai phương thức `execute()` chứa logic chính, cuối cùng trả về chuỗi JSON thông qua biến `$result`.

---

## 2. Admin API Template (Api)

**File lưu tại:** `modules/ten-module/Api/TenAction.php`
Ví dụ: `modules/news/Api/GetList.php`

```php
<?php

/**
 * @Project NukeViet
 * @Author VN (email)
 * @Copyright (C) 2025 VN. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

// Chú ý namespace PSR-4 chuẩn NukeViet 5: NukeViet\Module\[TênModule]\Api
namespace NukeViet\Module\News\Api;

use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use NukeViet\Api\Api;

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/**
 * Class GetList
 */
class GetList implements IApi
{
    /**
     * @var ApiResult
     */
    private $result;

    /**
     * Mức quyền Admin thiểu cần thiết để gọi API này
     * ADMIN_LEV_GOD (1), ADMIN_LEV_SP (2), ADMIN_LEV_MOD (3)
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
        return 'System'; // Hoặc rỗng ''
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
        global $nv_Request, $db_slave, $module_data;

        // Bắt buộc dùng $nv_Request, cấm $_POST/$_GET trực tiếp
        $limit = $nv_Request->get_int('limit', 'post,get', 10);
        $page = $nv_Request->get_int('page', 'post,get', 1);

        // Lấy thông tin module & admin đang execute
        $module_name = Api::getModuleName(); // "news"
        $admin_id    = Api::getAdminId();

        // Xử lý Logic (ví dụ lấy danh sách bài viết)
        // ...

        $data_return = [
            'status' => 'success',
            'data'   => [
                'items' => [],
                'total' => 0
            ]
        ];

        // Trả về JSON thông qua đối tượng result
        return $this->result->setCode(ApiResult::CODE_OK)
            ->setMessage('Lấy dữ liệu thành công')
            ->setData($data_return)
            ->returnResult();
    }
}
```

---

## 3. User API Template (Uapi)

**File lưu tại:** `modules/ten-module/Uapi/TenAction.php`
Ví dụ: `modules/news/Uapi/GetList.php`

```php
<?php

/**
 * @Project NukeViet
 * @Author VN (email)
 * @Copyright (C) 2025 VN. All rights reserved
 * @License GNU/GPL version 2 or any later version
 */

// Không gian Uapi
namespace NukeViet\Module\News\Uapi;

use NukeViet\Uapi\UapiResult;
use NukeViet\Uapi\UiApi;
use NukeViet\Uapi\Uapi;

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

/**
 * Class GetList
 */
class GetList implements UiApi
{
    /**
     * @var UapiResult
     */
    private $result;

    /**
     * Danh mục cấu hình quyền API
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
        $data_return = [
            'items' => []
        ];

        return $this->result->setCode(UapiResult::CODE_OK)
            ->setMessage('OK')
            ->setData($data_return)
            ->returnResult();
    }
}
```

---

## 4. Cấu hình CSDL và Phân quyền API
Để một API hoạt động và không bị cảnh báo "Api Lang Not Found" hay "Api module not found", DEV phải vào khu vực Admin CMS NukeViet 5 thiết lập thủ công:
1. Cấu hình Bật API toàn cục trong cấu hình site.
2. Tại khu vực **Công cụ web > Điều khiển API**:
    - Tạo `Credential` (Key + Secret + Ủy quyền IP).
    - Tạo `Vai trò (Roles)` cấp phát API theo phương thức phân luồng (chỉ chọn đúng action của Module được cấp phép).
    - Quy định hạn ngạch sử dụng (Quota / Rate Limiting) cho Vai trò đó.
    - Cấp Vai trò trên cho Credential đã tạo.

## 5. Test Gọi API (Client Example)
Client có thể dùng Class `\NukeViet\Api\DoApi` trong Core để kết nối đến Remote API qua HTTP:

```php
use NukeViet\Api\DoApi;

// Khởi tạo bộ gọi
$apiurl = 'https://site.com/api.php';
$apikey = 'TAO_TRONG_ADMIN';
$apisecret = 'CUNG_TAO_TRONG_ADMIN';

$api = new DoApi($apiurl, $apikey, $apisecret, false);

// Gọi module news, action GetList
$response = $api->setLang('vi')
                ->setModule('news')
                ->setAction('GetList')
                ->setData(['limit' => 5])
                ->execute();

if (empty($response)) {
    echo $api->getError();
} else {
    print_r($response);
}
```

---

## Đa Ngôn Ngữ trong API Endpoint

Khi API cần trả về nội dung ngôn ngữ hoặc đọc chuỗi `$lang_module`, cần load thủ công trong phương thức `execute()`:

```php
public function execute()
{
    global $nv_Lang;

    // Load ngôn ngữ module trước khi dùng $lang_module
    // (trong luồng API, Core không tự load ngôn ngữ module)
    $nv_Lang->loadModule($module_info['module_file']); // api.php đã gọi khi adminLev+module
    // Nếu Uapi module: cũng đã được load tự động trong src/api.php (dòng 302)
    // Chỉ cần load thủ công khi viết system Api không gắn với module

    // Sau đó dùng bình thường
    $message = $lang_module['hello'];
    // ...
}
```

> **Lưu ý:** Theo `api.php`, khi `!empty($api_request['module'])`, hệ thống đã tự gọi `$nv_Lang->loadModule()` nên DEV không phải load lại. Chỉ cần tự load khi viết API hệ thống (`apidir = 'Uapi'`, không có module).
