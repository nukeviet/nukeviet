---
name: add-func-mvc
description: Khởi tạo đầy đủ các lớp (Entity, Repository, Service, Validator, Controller) cho một chức năng mới dựa trên bảng CSLD. Dùng khi cần xây dựng nhanh CRUD cho một table mới trong module.
argument-hint: modules/ten-module/admin/op.php --table ten_bang_vach_duoi
---

# Skill: add-func-mvc — Siêu khởi tạo CRUD (Entity + Service Layer)

Skill này dùng để tự động xây dựng toàn bộ các lớp nghiệp vụ và giao diện cho một đối tượng (table) mới trong module NukeViet 5, tuân thủ tuyệt đối chuẩn MVC và kiến trúc Service Layer.

## 1. Phân tích và Thiết kế
- **Input**: Mã SQL `CREATE TABLE` hoặc danh sách cột + Tên Module.
- **Tên Đối Tượng ({Item})**: Chuyển PascalCase từ tên bảng. Ví dụ: `news_tags` -> `Tag`.
- **Cấu trúc thư mục**: 
  - **Logic**: `src/modules/{Module}/{Item}/`
- **Admin Controller**: `src/modules/{Module}/admin/{op}.php`
- **Frontend Controller**: `src/modules/{Module}/funcs/{op}.php` (Dành cho chức năng ngoài site)

## 2. Chi tiết các lớp mã nguồn (Templates)

### A. Entity (`{Item}Entity.php`)
- **Mục tiêu**: Ánh xạ 1 dòng DB thành Object có kiểu dữ liệu.
```php
namespace NukeViet\Module\{Module}\{Item};
class {Item}Entity extends \NukeViet\Module\Content\Shared\AbstractEntity {
    public int $id = 0;
    public string $title = '';
    // ... các thuộc tính khác có Type Hint và giá trị mặc định
    
    public function toArray(): array {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
```

### B. Repository (`{Item}Repository.php`)
- **Mục tiêu**: Tập trung toàn bộ truy vấn SQL.
- **Yêu cầu**: Sử dụng PDO, FETCH_ASSOC, cache, getDbColumns().
```php
namespace NukeViet\Module\{Module}\{Item};
use PDO;

class {Item}Repository {
    private PDO $db;
    private string $table;
    private $cache;
    private string $module;

    public function __construct(PDO $db, string $table, $cache, string $module) {
        $this->db = $db; $this->table = $table; $this->cache = $cache; $this->module = $module;
    }

    public function findById(int $id): ?{Item}Entity {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row ? {Item}Entity::fromArray($row) : null;
    }

    public function save(array $data, int $id = 0): int {
        // Lọc dữ liệu chuẩn trước khi lưu DB
        $data = array_intersect_key($data, array_flip({Item}Entity::getDbColumns()));
        // ... Xử lý INSERT hoặc UPDATE dùng PDO prepare
        $this->invalidateCache();
        return $id ?: (int) $this->db->lastInsertId();
    }

    public function invalidateCache(): void {
        $this->cache->delMod($this->module);
    }
}
```

### C. Validator (`{Item}Validator.php`)
- **Mục tiêu**: Kiểm tra tính hợp lệ, ném Exception kèm Error Code đại diện cho input.
```php
namespace NukeViet\Module\{Module}\{Item};
class {Item}Validator {
    public function validateSave(array $data, int $excludeId = 0): void {
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('empty_title', 1);
        }
    }
}
```

### D. Service (`{Item}Service.php`)
- **Mục tiêu**: Business Logic, chuẩn hóa dữ liệu, phát Hook.
```php
namespace NukeViet\Module\{Module}\{Item};
class {Item}Service {
    private {Item}Repository $repo;
    public function __construct({Item}Repository $repo) { $this->repo = $repo; }

    public function collectRequestData($nv_Request): array {
        return [
            'title' => $nv_Request->get_title('title', 'post', ''),
        ];
    }

    public function prepareSaveData(array $data): array {
        $data['alias'] = change_alias($data['title'] ?? '');
        return $data; // Luôn chạy trước khi Validator nhận dữ liệu
    }

    public function save{Item}(array $data, int $id, string $module_name): int {
        // Xử lý auto set weight, add_time, edit_time...
        $savedId = $this->repo->save($data, $id);
        nv_apply_hook($module_name, '{item}_saved', [
            'id' => $savedId, 
            'title' => $data['title'],
            'action' => $id ? 'edit' : 'add'
        ]);
        return $savedId;
    }
}
```

### E. Giao diện Quản trị (`admin/{op}.php`)
- **Mục tiêu**: Entry point cho admin.
- **Yêu cầu**: Tuân thủ thứ tự `use` và 2 tầng try-catch (`InvalidArgumentException` và `Throwable`).
```php
if (!defined('NV_IS_FILE_ADMIN')) exit('Stop!!!');

use NukeViet\Module\{Module}\{Item}\{Item}Repository;
use NukeViet\Module\{Module}\{Item}\{Item}Service;
use NukeViet\Module\{Module}\{Item}\{Item}Validator;

$repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);
$service = new {Item}Service($repo);

if ($nv_Request->isset_request('submit', 'post')) { // Xử lý submit
    // 1. Thu thập
    $data = $service->collectRequestData($nv_Request);
    // 2. Chuẩn hóa
    $data = $service->prepareSaveData($data);
    
    try {
        $saveId = $id ?: 0;
        // 3. Validate
        $validator = new {Item}Validator($repo);
        $validator->validateSave($data, $saveId);
        
        // 4. Save
        $savedId = $service->save{Item}($data, $saveId, $module_name);
        nv_insert_logs(NV_LANG_DATA, $module_name, $saveId ? 'Edit' : 'Add', 'ID: ' . $savedId, $admin_info['userid']);
        
        nv_jsonOutput(['status' => 'success', 'mess' => $nv_Lang->getGlobal('save_success')]);
    } catch (\InvalidArgumentException $e) {
        $fieldMap = [1 => 'title']; // Map error code to input field
        nv_jsonOutput([
            'status' => 'error', 
            'mess' => $nv_Lang->getModule($e->getMessage()), 
            'input' => $fieldMap[$e->getCode()] ?? ''
        ]);
    } catch (\Throwable $e) {
        trigger_error($e);
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_system')]);
    }
}
```

### F. Giao diện Ngoài site (`funcs/{op}.php`)
- **Mục tiêu**: Phục vụ người dùng cuối. Đăng ký `op` vào `version.php`. Khởi tạo repo dạng local.
```php
if (!defined('NV_IS_MOD_{MODULE}')) exit('Stop!!!');

use NukeViet\Module\{Module}\{Item}\{Item}Repository;
use NukeViet\Module\{Module}\{Item}\{Item}Service;

$repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);
$service = new {Item}Service($repo);

// Xử lý logic...
$contents = nv_theme_{module}_{op}($data); // Gọi hàm render trong theme.php

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```

## 3. Quy trình thực hiện (Checklist)
1. [ ] **DB**: Chốt schema bảng.
2. [ ] **Layers**: Tạo Entity -> Repository -> Validator -> Service.
3. [ ] **Controller**: Tạo file admin hoặc funcs. Áp dụng 2 tầng `try-catch`.
4. [ ] **Menu/Route**:
    - **Admin**: Cập nhật `$submenu` trong `admin.menu.php`.
    - **Frontend**: Thêm tên `op` vào mảng `function` trong `version.php`.
5. [ ] **Language**: Thêm các thông báo lỗi Validation vào `language/vi.php`.
6. [ ] **View**: Tạo `.tpl` Smarty trong theme (Bootstrap 5).
7. [ ] **Testing**: Bổ sung Unit Test cho Validator và Service, Acceptance Test CRUD AdminUI.
8. [ ] **Syntax & Cache**:
    - Kiểm tra lỗi cú pháp: `php -l {đường/dẫn/file}`
    - Xóa cache: `rm -rf src/data/cache/*/*.cache`

## 4. Nguyên tắc "Vàng"
1. **No Hardcode**: Sử dụng `NV_PREFIXLANG . '_' . $module_data` cho tên bảng.
2. **Order of Use**: `use` list sắp xếp theo: `Repository` -> `Service` -> `Validator`.
3. **Local Init**: Khởi tạo Repo/Service ngay trong Controller. Cấm khởi tạo global.
4. **Validation Error Code**: Hàm Validator nén `InvalidArgumentException` kèm Error code tương ứng với field, key mess khớp với `language/vi.php`.
5. **Two-Tier Try Catch**: Controller cần bắt lỗi Validation trả thẳng thông báo cho user, nhưng với những lỗi không báo trước `Throwable` thì phải ghi log `trigger_error($e)` rồi báo lỗi chung.
6. **Data Flow**: `prepareSaveData` TRƯỚC KHI `validateSave` để kiểm tra các field tự sinh (như alias) chính xác hơn.

## 5. Báo cáo kết quả
Sau khi hoàn thành, liệt kê tất cả file mới, các file đã cập nhật và cung cấp URL test để kiểm tra nhanh.
