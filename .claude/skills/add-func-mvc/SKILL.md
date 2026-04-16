---
name: add-func-mvc
description: Scaffold trọn bộ MVC (Entity, Repository, Service, Validator, Controller) cho NukeViet 5.0 dựa trên JSON Schema. Tự động khởi tạo thư mục Shared nếu chưa tồn tại.
argument-hint: src/data/devtool/ten_file_config.json
---

# Skill: add-func-mvc — NukeViet 5.0 MVC Scaffolding

Skill này thực hiện sinh mã nguồn chuẩn MVC + Service Layer cho NukeViet 5.0. AI sử dụng JSON Schema làm cấu hình đầu vào để build Entity, Repository, Service, Validator và Controller.

## 1. Workflow (Quy trình thực thi)

1. **Schema Parsing**: AI phân tích file JSON (Mapping columns, View types, AI Instructions).
2. **Architecture Check**: Kiểm tra thư mục `Shared/` tại module đích. Nếu chưa tồn tại, AI sử dụng mẫu mã nguồn (Core Templates) dưới đây để khởi tạo.
3. **MVC Generation**: Sinh mã nguồn theo thứ tự: Entity -> Repository -> Validator -> Service -> Controller -> Template -> Acceptance Test.

## 2. Core Templates (Lớp cơ sở)

AI sử dụng các mẫu này để thiết lập nền tảng cho Module nếu không có sẵn nguồn tham chiếu:

````carousel
```php
// Shared/Tables.php — Table name resolution
namespace NukeViet\Module\{Module}\Shared;
readonly class Tables {
    public string $main;
    public function __construct(string $tablePrefix, string $moduleData) {
        $this->main = $tablePrefix . '_' . $moduleData . '_{suffix}';
    }
}
```
<!-- slide -->
```php
// Shared/BaseRepository.php — PDO & Cache common logic
namespace NukeViet\Module\{Module}\Shared;
use PDO;
abstract class BaseRepository {
    protected PDO $db; protected Tables $tables; protected $cache; protected string $module_name;
    public function __construct(PDO $db, Tables $tables, $cache, string $module_name) {
        $this->db = $db; $this->tables = $tables; $this->cache = $cache; $this->module_name = $module_name;
    }
    abstract protected function entityClass(): string;
    protected function bindNullable(\PDOStatement $stmt, string $param, $value, ?string $field = null): void {
        if ($value === null || $value === '') { $stmt->bindValue($param, null, PDO::PARAM_NULL); return; }
        $type = $field ? $this->pdoType($field) : PDO::PARAM_STR;
        $stmt->bindValue($param, $value, $type);
    }
    public function invalidateCache(): void { $this->cache->delMod($this->module_name); }
    protected function fetchEntities(\PDOStatement $stmt): array {
        return array_map([$this->entityClass(), 'fromArray'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
```
<!-- slide -->
```php
// Shared/AbstractEntity.php — Data mapping & Hydration
namespace NukeViet\Module\{Module}\Shared;
abstract class AbstractEntity {
    public static function getDbColumns(): array {
        $allFields = array_keys(get_class_vars(static::class));
        return array_values(array_diff($allFields, static::VIEW_FIELDS, [static::PRIMARY_KEY]));
    }
    public static function fromArray(array $data): static {
        $entity = new static();
        foreach ($data as $key => $value) {
            if (!property_exists($entity, $key)) continue;
            if ($value === null) { $entity->$key = null; continue; }
            $entity->$key = is_int($entity->$key) ? (int)$value : (string)$value;
        }
        return $entity;
    }
}
```
````

## 3. Technical Standards (Tiêu chuẩn kỹ thuật)

1. **Zero Configuration**: Tự động xử lý các cột hệ thống (`status`, `weight`, `admin_id`, `add_time`, `edit_time`).
2. **Nullable Types**: Sử dụng `?string` cho dữ liệu ngày tháng không bắt buộc trong Entity.
3. **PDO Binding**: Sử dụng `bindNullable` trong Repository để tương thích MySQL Strict Mode.
4. **Service Layer Workflow**:
   - `collectRequestData`: Chuyển Request sang Mảng thô.
   - `prepareSaveData`: Chuẩn hóa dữ liệu (0/null handling, alias generation).
   - `Validator::validateSave`: Kiểm tra tính hợp lệ.
   - `saveItem`: Thực thi SQL & Dispatch Hooks.
5. **Acceptance Testing**: Sử dụng `scrollIntoView({block: 'center'})` và `wait(0.5)` để đảm bảo độ ổn định của UI tests.

## 4. Prompting Instruction

Dựa trên cấu hình JSON Schema tại @src/data/devtool/nv5_vi_content_demo.json, hãy sử dụng skill add-func-mvc để sinh trọn bộ mã nguồn MVC (Entity, Repository, Service, Validator, Controller, Template và Acceptance Test).
Lưu ý triển khai chi tiết dựa trên các "Ghi chú cho AI" trong schema, đặc biệt là phần giao diện đẹp cho các trường chuyên mục và xử lý đầy đủ các trường ngày tháng (fdate, fdatetime, ftimestamp, ftime, fyear)
