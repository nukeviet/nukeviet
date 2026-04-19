# Hướng Dẫn Xây Dựng Module NukeViet 5

> Dựa trên code thực tế `src/modules/Content/`

Tài liệu này hướng dẫn tạo module mới theo kiến trúc **Entity + Service Layer + PSR-4**. Mọi mẫu code đều lấy từ module `content` đã triển khai.

**Quy ước placeholder:** `{mymod}` = tên module (lowercase), `{Item}` = tên đối tượng (PascalCase), `{item}` = tên đối tượng (lowercase).

---

## Kiến Trúc Tổng Quan

```
Controller (Thin) ──▶ Service::prepareSaveData() ──▶ Validator ──▶ Service::save() ──▶ Repository ──▶ DB
      │                                                                                  │
      ▼                                                                                  ▼
  View (NVSmarty)                                                                  Entity (Typed)
```

| Tầng | Vai trò | Quy tắc |
|------|---------|---------|
| **Entity** | 1 bản ghi = 1 object. `toArray()` + `fromArray()` | Luôn dùng `FETCH_ASSOC` + `fromArray()`, KHÔNG dùng `FETCH_CLASS` |
| **Repository** | Toàn bộ SQL | Controller/Service KHÔNG viết SQL |
| **Validator** | Kiểm tra input, ném `InvalidArgumentException` kèm error code | Tách riêng khỏi Service |
| **Service** | Business logic + cache + hook | KHÔNG có SQL, **Thu thập dữ liệu từ Request** (DRY) |
| **Controller** | Nhận Request → Validator → Service → View | Thin Controller |
| **View** | Chỉ nhận Array từ `toArray()` | Cấm viết logic/SQL trong `.tpl` |

**8 quy tắc bắt buộc:** (1) Cấm hardcode tên bảng — dùng tên bảng từ cấu hình `$config['table_xxx']` (2) Input qua `$nv_Request` (3) CSRF cho mọi write: `csrf_check()` / `csrf_create()` (4) Cache: gọi `invalidateCache()` sau mọi CUD (5) Log: `nv_insert_logs()` cho mọi CUD (6) Hook: `nv_apply_hook()` phát event (7) JS file riêng `themes/[theme]/js/[module].js` — cấm `<script>` trong `.tpl` (8) Frontend render qua `theme.php`

---

## Bước 1 — Cấu Trúc Thư Mục

```text
.
├── src/
│   ├── modules/{mymod}/
│   │   ├── version.php              # Metadata
│   │   ├── functions.php            # define NV_IS_MOD_{MYMOD}, load config
│   │   ├── admin.functions.php      # $allow_func, define NV_IS_FILE_ADMIN
│   │   ├── admin.menu.php           # $submenu
│   │   ├── action_mysql.php         # SQL tạo/xóa bảng
│   │   ├── theme.php                # Render frontend (NVSmarty)
│   │   ├── funcs/
│   │   │   └── main.php             # Frontend controller
│   │   ├── admin/
│   │   │   ├── main.php             # Danh sách
│   │   │   ├── {item}.php           # Form thêm/sửa
│   │   │   ├── {item}-del.php       # AJAX xóa
│   │   │   ├── {item}-change-status.php
│   │   │   └── {item}-change-weight.php
│   │   ├── {Item}/                  # PSR-4: NukeViet\Module\{mymod}\{Item}\
│   │   │   ├── {Item}Entity.php
│   │   │   ├── {Item}Repository.php
│   │   │   ├── {Item}Validator.php
│   │   │   └── {Item}Service.php
│   │   ├── Shared/                  # Các Class dùng chung của module (Dùng cho kiến trúc Zero-Config)
│   │   │   ├── Tables.php           # Định nghĩa tên bảng DUY NHẤT 1 chỗ
│   │   │   ├── BaseRepository.php   # Lớp cha cho mọi Repository
│   │   │   ├── BaseApi.php          # Lớp cha cho Admin API
│   │   │   ├── BaseUapi.php         # Lớp cha cho Public API
│   │   │   ├── ValidationException.php
│   │   │   └── SchemaHelper.php
│   │   ├── Api/                     # Admin API (implements IApi)
│   │   │   └── {Item}GetList.php
│   │   ├── uapi/                    # Public API (implements UiApi)
│   │   │   └── {Item}GetList.php
│   │   └── language/
│   │       └── vi.php
│   └── themes/
│       ├── admin_default/modules/{mymod}/
│       │   ├── main.tpl             # Giao diện danh sách quản trị
│       │   └── {item}.tpl           # Giao diện form thêm/sửa quản trị
│       └── default/modules/{mymod}/
│           ├── detail.tpl           # Giao diện chi tiết hiển thị
│           └── main_list.tpl        # Giao diện danh sách hiển thị
│
└── tests/
    └── modules/{mymod}/
        ├── Unit/                    # Validator, Service, Entity, Repository Test
        ├── Acceptance/              # Admin UI CRUD (Selenium), PublicViewCest
        └── API/                     # AdminApiCest, PublicUapiCest
```

> Nếu module có nhiều đối tượng (VD: `Cat` + `Content`), mỗi đối tượng cần bộ 4: Entity, Repository, Validator, Service.

---

## Bước 2 — Files Hệ Thống

### `version.php`

```php
<?php
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => '{MyMod}',
    'modfuncs' => 'main',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '5.0.00',
    'date' => 'Friday, April 11, 2026 9:00:00 AM GMT+07:00',
    'author' => 'Your Name <email@example.com>',
    'note' => '',
    'uploads_dir' => [$module_upload],
    'icon' => 'fa-solid fa-cube'
];
```

### `functions.php`

```php
<?php
if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_{MYMOD}', true);

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
    . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Lấy cấu hình module từ biến hệ thống
$config = $module_config[$module_name];

// Khởi tạo danh sách bảng DB cho module — dùng chung cho mọi Repository
use NukeViet\Module\{MyMod}\Shared\Tables;
$tables = new Tables(NV_PREFIXLANG, $module_data);
```

### `admin.functions.php`

```php
<?php
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'main', '{item}', '{item}-del', '{item}-change-status', '{item}-change-weight',
];

define('NV_IS_FILE_ADMIN', true);

if (defined('NV_IS_SPADMIN')) {
    $allow_func[] = 'config';
}

// Lấy cấu hình module từ biến hệ thống
$config = $module_config[$module_name];

// Khởi tạo danh sách bảng DB cho module — dùng chung cho mọi Repository
use NukeViet\Module\{MyMod}\Shared\Tables;
$tables = new Tables(NV_PREFIXLANG, $module_data);
```

### `admin.menu.php`

```php
<?php
if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['{item}'] = $nv_Lang->getModule('add');

if (defined('NV_IS_SPADMIN')) {
    $submenu['config'] = $nv_Lang->getModule('config');
}
```

### `action_mysql.php`

Quy ước: DROP trước → CREATE. Tên bảng = `prefix_lang_moduledata[_suffix]`. Cấu hình module được lưu vào bảng cấu hình dùng chung `NV_CONFIG_GLOBALTABLE`.

```php
<?php
if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_content;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_content (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(250) NOT NULL,
    alias varchar(250) NOT NULL,
    description text,
    bodytext mediumtext NOT NULL,
    keywords text,
    image varchar(255) DEFAULT '',
    weight smallint(4) NOT NULL DEFAULT '0',
    admin_id mediumint(8) unsigned NOT NULL DEFAULT '0',
    add_time int(11) NOT NULL DEFAULT '0',
    edit_time int(11) NOT NULL DEFAULT '0',
    status tinyint(1) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (id),
    UNIQUE KEY alias (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES
    ('" . $lang . "', '" . $module_name . "', 'table_row', '" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_content'),
    ('" . $lang . "', '" . $module_name . "', 'table_cat', '" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat'),
    ('" . $lang . "', '" . $module_name . "', 'per_page', '20'),
    ('" . $lang . "', '" . $module_name . "', 'alias_lower', '1')
";
```

> 📎 Mẫu có nhiều bảng (thêm `_cat`): `src/modules/Content/action_mysql.php`

### `language/vi.php`

```php
<?php
if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Admin
$lang_module['add'] = 'Thêm mới';
$lang_module['edit'] = 'Chỉnh sửa';
$lang_module['list'] = 'Danh sách';
$lang_module['config'] = 'Cấu hình';
$lang_module['title'] = 'Tiêu đề';
$lang_module['alias'] = 'Liên kết tĩnh';
$lang_module['description'] = 'Mô tả';
$lang_module['keywords'] = 'Từ khóa';
$lang_module['status'] = 'Trạng thái';
$lang_module['weight'] = 'Thứ tự';
$lang_module['image'] = 'Hình ảnh';
$lang_module['active'] = 'Hoạt động';
$lang_module['deactive'] = 'Ngưng hoạt động';

// Validation messages (khớp với Validator ném ra)
$lang_module['empty_title'] = 'Vui lòng nhập tiêu đề';
$lang_module['empty_bodytext'] = 'Vui lòng nhập nội dung';
$lang_module['erroralias'] = 'Liên kết tĩnh đã tồn tại';
$lang_module['error_system'] = 'Lỗi hệ thống';

// Delete
$lang_module['{item}_delete_confirm'] = 'Bạn có chắc chắn muốn xóa?';
$lang_module['{item}_delete_unsuccess'] = 'Xóa không thành công';
$lang_module['save_success'] = 'Lưu thành công';
```

---

## Bước 3 — Entity

Mọi Entity nên kế thừa từ `AbstractEntity` để sử dụng các phương thức chung. Mỗi bảng DB = 1 Entity. Typed Properties với giá trị mặc định.

```php
<?php
namespace NukeViet\Module\{mymod}\{Item};

use NukeViet\Module\Content\Shared\AbstractEntity;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * {Item}Entity — Đại diện cho 1 bản ghi
 */
class {Item}Entity extends AbstractEntity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     */
    protected const VIEW_FIELDS = ['link', 'url_edit', 'url_copy', 'checkss'];

    /**
     * Tên cột khóa chính (Override nếu khóa chính không nằm trong VIEW_FIELDS)
     * Mặc định AbstractEntity sẽ dùng PRIMARY_KEY và VIEW_FIELDS để lọc getDbColumns().
     */
    protected const PRIMARY_KEY = 'id';

    /**
     * Khai báo nested Entity (Relationship). toArray() lớp cha tự động expand theo danh sách này.
     * Entity đơn giản (không có Relationship) để mảng rỗng hoặc bỏ qua.
     * VD có Relationship: ['category' => CatEntity::class]
     */
    protected const RELATIONS = [];

    public int $id = 0;
    public string $title = '';
    public string $alias = '';
    public string $description = '';
    public string $bodytext = '';
    public string $keywords = '';
    public string $image = '';
    public int $weight = 0;
    public int $admin_id = 0;
    public int $add_time = 0;
    public int $edit_time = 0;
    public int $status = 0;

    // ── Thuộc tính View (không có trong DB) ──
    public string $link = '';
    public string $url_edit = '';
    public string $url_copy = '';
    public string $checkss = '';
}
```

**Các hằng số và phương thức được kế thừa từ `AbstractEntity`:**
- `VIEW_FIELDS`: Khai báo các thuộc tính chỉ dùng để hiển thị (không gửi vào DB).
- `PRIMARY_KEY`: Tên cột khóa chính (mặc định `''` — nếu PK đã nằm trong `VIEW_FIELDS` thì không cần override).
- `RELATIONS`: Map nested Entity — `toArray()` tự động expand theo danh sách này. VD: `['category' => CatEntity::class]`.
- `toArray()`: **Có sẵn từ AbstractEntity** — tự động expand nested Entity theo `RELATIONS`. Chỉ override nếu có logic đặc biệt ngoài expand nested Entity.
- `getDbColumns()`: Tự động trả về danh sách các cột trong DB bằng cách lấy toàn bộ thuộc tính public trừ `VIEW_FIELDS` và `PRIMARY_KEY`.
- `getIntColumns()`: Tự động trả về danh sách các cột kiểu số (dựa trên giá trị mặc định là `int`).
- `fromArray(array $data)`: Tạo object Entity từ mảng dữ liệu, tự động ép kiểu và bỏ qua các trường không tồn tại hoặc Relationship (nullable).

**4 lưu ý:** (1) Typed Properties BẮT BUỘC có `= ''` hoặc `= 0` (2) Dùng `protected const VIEW_FIELDS` để lớp cha có thể truy cập qua Late Static Binding (3) `PRIMARY_KEY` giúp xác định khóa chính để loại bỏ khi lưu DB (4) **`toArray()` đã có sẵn từ lớp cha** — chỉ cần khai báo `RELATIONS` để tự expand nested Entity, không cần override thủ công.

> 📎 Entity có Relationship: `src/modules/Content/Content/ContentEntity.php`
> 📎 Entity đơn giản: `src/modules/Content/Cat/CatEntity.php`
> 📎 Lớp cha: `src/modules/Content/Shared/AbstractEntity.php`

---

## Bước 4 — Repository

Tập trung **toàn bộ SQL**. Mọi Repository cần kế thừa `BaseRepository` để tận dụng các helper xử lý Entity và Cache. Constructor nhận đối tượng `$tables` thay vì tên bảng thô.

### 4.1 — Tables Value Object (`Shared/Tables.php`)

Nơi duy nhất định nghĩa suffix cho các bảng. Giúp module "Zero-Configuration" — chỉ cần cài đặt là tự nhận diện bảng theo ngôn ngữ và tên module.

```php
namespace NukeViet\Module\{mymod}\Shared;

readonly class Tables
{
    public string $content;
    public string $cat;

    public function __construct(string $tablePrefix, string $moduleData)
    {
        $prefix = $tablePrefix . '_' . $moduleData;
        $this->content = $prefix . '_content';  // nv5_vi_{moduleData}_content
        $this->cat     = $prefix . '_cat';      // nv5_vi_{moduleData}_cat
    }
}
```

### 4.2 — BaseRepository Skeleton (`Shared/BaseRepository.php`)

Gom các logic PDO helper (`pdoType`, `fetchEntities`) và Cache (`invalidateCache`) lên lớp cha.

```php
namespace NukeViet\Module\{mymod}\Shared;

use PDO;

abstract class BaseRepository
{
    protected PDO $db;
    protected Tables $tables;
    protected $cache;
    protected string $module_name;

    public function __construct(PDO $db, Tables $tables, $cache, string $module_name)
    {
        $this->db = $db;
        $this->tables = $tables;
        $this->cache = $cache;
        $this->module_name = $module_name;
    }

    abstract protected function entityClass(): string;

    protected function pdoType(string $field): int { ... }
    protected function fetchEntities(\PDOStatement $stmt): array { ... }
    public function invalidateCache(): void { ... }
}
```

### 4.3 — Repository Implementation

```php
namespace NukeViet\Module\{mymod}\{Item};

use NukeViet\Module\{mymod}\Shared\BaseRepository;
use PDO;

class {Item}Repository extends BaseRepository
{
    protected function entityClass(): string
    {
        return {Item}Entity::class;
    }

    public function findById(int $id): ?{Item}Entity
    {
        // Sử dụng $this->tables->content thay vì hardcode
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->tables->content . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? {Item}Entity::fromArray($data) : null;
    }

    public function save(array $data, int $id = 0): int
    {
        $data = array_intersect_key($data, array_flip({Item}Entity::getDbColumns()));
        if ($id > 0) {
            // ... Logic Update ...
            $stmt = $this->db->prepare('UPDATE ' . $this->tables->content . ' SET ... WHERE id = :id');
            // ...
            return $id;
        }
        // ... Logic Insert ...
        $stmt = $this->db->prepare('INSERT INTO ' . $this->tables->content . ' ...');
        // ...
        return (int) $this->db->lastInsertId();
    }
}
```

    /**
     * Lưu cấu hình module vào bảng dùng chung
     */
    public function saveConfig(array $config): void
    {
        $sth = $this->db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
        $sth->bindValue(':module_name', $this->module_name, PDO::PARAM_STR);
        foreach ($config as $config_name => $config_value) {
            $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $this->cache->delMod('settings');
        $this->cache->delMod($this->module_name);
    }

    /**
     * Helper: fetchAll + map thành Entity[]
     * Gom logic FETCH_ASSOC + fromArray() vào 1 chỗ duy nhất
     */
    private function fetchEntities(\PDOStatement $stmt): array
    {
        return array_map([{Item}Entity::class, 'fromArray'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Xác định PDO type cho 1 cột dựa theo khai báo Entity.
     * Tránh dùng is_int() trực tiếp trên value vì data từ Request có thể là string.
     */
    private function pdoType(string $field): int
    {
        static $intFields = null;
        if ($intFields === null) {
            $intFields = {Item}Entity::getIntColumns();
        }
        return isset($intFields[$field]) ? PDO::PARAM_INT : PDO::PARAM_STR;
    }

    // ═══════════════════════════════════════
    // READ
    // ═══════════════════════════════════════

    public function findById(int $id): ?{Item}Entity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Quan trọng: Query trả về row đơn lẻ cần close cursor
        return $data ? {Item}Entity::fromArray($data) : null;
    }

    public function findByAlias(string $alias): ?{Item}Entity
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE alias = :alias');
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $data ? {Item}Entity::fromArray($data) : null;
    }

    public function countActive(int $catid = 0): int
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE status = 1';
        if ($catid > 0) {
            $sql .= ' AND catid = :catid';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $this->db->query($sql);
        }
        return (int) $stmt->fetchColumn();
    }

    /**
     * Lấy danh sách linh hoạt (dùng cho cả frontend và admin)
     *
     * @param int $catid  Lọc theo chủ đề (0 = tất cả)
     * @param int $status Lọc theo trạng thái (-1 = tất cả, 0 = ẩn, 1 = hiển thị)
     * @param int $page   Trang hiện tại (bắt đầu từ 1)
     * @param int $per_page Số bản ghi/trang (0 = lấy hết, không phân trang)
     * @return {Item}Entity[]
     */
    public function getContentList(int $catid = 0, int $status = 1, int $page = 1, int $per_page = 0): array
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $where = [];

        if ($status >= 0) {
            $where[] = 'status = :status';
        }
        if ($catid > 0) {
            $where[] = 'catid = :catid';
        }
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY weight ASC';

        if ($per_page > 0) {
            $sql .= ' LIMIT :offset, :limit';
        }

        $stmt = $this->db->prepare($sql);
        if ($status >= 0) {
            $stmt->bindValue(':status', $status, PDO::PARAM_INT);
        }
        if ($catid > 0) {
            $stmt->bindValue(':catid', $catid, PDO::PARAM_INT);
        }
        if ($per_page > 0) {
            $stmt->bindValue(':offset', ($page - 1) * $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $this->fetchEntities($stmt);
    }

    public function isAliasExists(string $alias, int $excludeId = 0): bool
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE alias = :alias';
        if ($excludeId > 0) {
            $sql .= ' AND id != :id';
        }
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':alias', $alias, PDO::PARAM_STR);
        if ($excludeId > 0) {
            $stmt->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    // ═══════════════════════════════════════
    // WRITE (copy y nguyên, chỉ đổi PK nếu cần)
    // ═══════════════════════════════════════

    /**
     * INSERT ($id=0) hoặc UPDATE ($id>0). Nhận mảng $data linh hoạt.
     * @return int ID bản ghi
     */
    public function save(array $data, int $id = 0): int
    {
        // Lọc bỏ những trường "ảo" (link, category...) không có trong DB
        $data = array_intersect_key($data, array_flip({Item}Entity::getDbColumns()));

        if ($id > 0) {
            $fields = [];
            $params = [':id' => [$id, PDO::PARAM_INT]];
            foreach ($data as $key => $value) {
                $fields[] = $key . ' = :' . $key;
                $params[':' . $key] = [$value, $this->pdoType($key)];
            }
            $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET ' . implode(', ', $fields) . ' WHERE id = :id');
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v[0], $v[1]);
            }
            $stmt->execute();
            return $id;
        }

        $columns = array_keys($data);
        $placeholders = array_map(fn($k) => ':' . $k, $columns);
        $stmt = $this->db->prepare(
            'INSERT INTO ' . $this->table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')'
        );
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value, $this->pdoType($key));
        }
        $stmt->execute();
        return (int) $this->db->lastInsertId();
    }

    /**
     * Xóa bản ghi + dữ liệu liên quan (nếu có)
     * @param string $relatedTable Bảng liên quan (VD: bảng comment). Rỗng = không xóa kèm.
     */
    public function delete(int $id, string $relatedTable = ''): bool
    {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result && !empty($relatedTable)) {
            $stmt = $this->db->prepare('DELETE FROM ' . $relatedTable . ' WHERE module = :module AND id = :id');
            $stmt->bindValue(':module', $this->module_name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        return $result;
    }

    public function toggleStatus(int $id): int
    {
        $row = $this->findById($id);
        if (!$row) {
            return -1;
        }
        $newStatus = $row->status ? 0 : 1;
        $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET status = :status WHERE id = :id');
        $stmt->bindValue(':status', $newStatus, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $newStatus;
    }

    /**
     * Sắp xếp lại weight dùng Bulk UPDATE (CASE WHEN)
     */
    public function reorderWeight(int $movedId = 0, int $newWeight = 0): void
    {
        $sql = 'SELECT id, weight FROM ' . $this->table;
        $params = [];
        if ($movedId > 0) {
            $sql .= ' WHERE id != :id';
            $params[':id'] = $movedId;
        }
        $sql .= ' ORDER BY weight ASC';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $cases = [];
        $ids = [];
        $calcWeight = 0;

        foreach ($rows as $row) {
            ++$calcWeight;
            if ($movedId > 0 && $calcWeight == $newWeight) {
                ++$calcWeight;
            }
            // Chỉ cập nhật nếu thực sự thay đổi
            if ($calcWeight !== (int) $row['weight']) {
                $cases[] = 'WHEN ' . (int) $row['id'] . ' THEN ' . $calcWeight;
                $ids[] = (int) $row['id'];
            }
        }

        if ($movedId > 0 && $newWeight > 0) {
            $cases[] = 'WHEN ' . $movedId . ' THEN ' . $newWeight;
            $ids[] = $movedId;
        }

        if (empty($ids)) {
            return;
        }

        $this->db->exec(
            'UPDATE ' . $this->table
                . ' SET weight = CASE id ' . implode(' ', $cases) . ' END'
                . ' WHERE id IN (' . implode(',', $ids) . ')'
        );
    }

    /**
     * Tự sửa weight sai lệch hàng loạt (Bulk UPDATE).
     */
    public function autoCorrectWeight(array &$entities): bool
    {
        $cases = [];
        $ids = [];
        $iw = 0;

        foreach ($entities as $entity) {
            ++$iw;
            if ($iw != $entity->weight) {
                $entity->weight = $iw;
                $cases[] = 'WHEN ' . (int) $entity->id . ' THEN ' . $iw;
                $ids[] = (int) $entity->id;
            }
        }

        if (empty($ids)) {
            return false;
        }

        return (bool) $this->db->exec(
            'UPDATE ' . $this->table
                . ' SET weight = CASE id ' . implode(' ', $cases) . ' END'
                . ' WHERE id IN (' . implode(',', $ids) . ')'
        );
    }

    public function getMaxWeight(): int
    {
        return (int) $this->db->query('SELECT MAX(weight) FROM ' . $this->table)->fetchColumn();
    }

    public function incrementOthersWeight(): void
    {
        $this->db->prepare('UPDATE ' . $this->table . ' SET weight = weight + 1')->execute();
    }

    public function invalidateCache(): void
    {
        $this->cache->delMod($this->module_name);
    }
}
```

### 4.3 — Quy tắc PDO trong Repository

**`fetchEntities()` — Helper bắt buộc:** Mỗi Repository cần có 1 method private gom logic `fetchAll(FETCH_ASSOC)` + `array_map(fromArray)`. Lý do không dùng `FETCH_CLASS`: method `fromArray()` chứa logic lọc NULL + ép kiểu mà `FETCH_CLASS` bỏ qua, gây lỗi typed properties.

**`closeCursor()` — Quy tắc sử dụng:**

| Trường hợp | Cần? | Lý do |
|---|---|---|
| `fetchColumn()` | ❌ | Trả 1 giá trị, result set đã hết |
| `fetchAll()` / `fetchEntities()` | ❌ | Đã đọc toàn bộ vào memory |
| `fetch()` 1 lần (`findById`) | ✅ | Query có thể trả >1 row, giữ an toàn |
| `while ($stmt->fetch())` | ✅ | Vòng lặp xen kẽ query khác trên cùng connection |

**`getContentList()` — Hàm truy vấn hợp nhất:** Thay vì viết riêng `getActiveList()` (frontend) và `getAllAdmin()` (admin), gộp thành 1 hàm linh hoạt với 4 tham số:

| Param | Ý nghĩa | Giá trị đặc biệt |
|---|---|---|
| `$catid` | Lọc theo chủ đề | `0` = tất cả |
| `$status` | Lọc theo trạng thái | `-1` = tất cả, `0` = ẩn, `1` = active |
| `$page` | Trang hiện tại | Mặc định `1` |
| `$per_page` | Bản ghi/trang | `0` = lấy hết |

Ví dụ gọi:
```php
// Frontend: bài active, phân trang
$repo->getContentList(0, 1, $page, $per_page);

// Frontend: lọc theo chủ đề
$repo->getContentList($catid, 1, $page, $per_page);

// Admin: tất cả trạng thái, không phân trang
$repo->getContentList($filter_catid, -1);
```

> **Lưu ý:** Việc quản lý cấu hình tập trung giúp đồng bộ dữ liệu với hệ thống NukeViet tốt hơn và giảm số lượng bảng cần quản lý. Để đọc cấu hình, hãy sử dụng biến `$module_config[$module_name]` đã được hệ thống nạp sẵn.

> 📎 Repository đầy đủ hơn (có `getRelated`, `incrementHits`, `countByCatid`): `src/modules/Content/Content/ContentRepository.php`
> 📎 Repository cho bảng phụ (PK là `catid`): `src/modules/Content/Cat/CatRepository.php`

---

## Bước 5 — Service

Business logic thuần. Hai trách nhiệm chính:

1. **`prepareSaveData()`** — Chuẩn hóa dữ liệu (alias, keywords, image) trước khi validate. Gom logic chung để tránh lặp code giữa admin controller, API, và bất kỳ entry point nào khác.
2. **`saveItem()` / `saveCat()`** — Lưu dữ liệu đã validate: gắn weight + timestamps → save → clear cache → phát hook.

```php
<?php
namespace NukeViet\Module\{mymod}\{Item};

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class {Item}Service
{
    private {Item}Repository $repo;

    public function __construct({Item}Repository $repo)
    {
        $this->repo = $repo;
    }

    // ── READ ──

    public function getDetail(int $id): {Item}Entity
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID không hợp lệ');
        }
        $row = $this->repo->findById($id);
        if (!$row) {
            throw new \RuntimeException('Không tìm thấy dữ liệu', 404);
        }
        return $row;
    }

    public function getList(int $page, int $per_page, int $catid = 0): array
    {
        return [
            'total' => $this->repo->countActive($catid),
            'items' => $this->repo->getContentList($catid, 1, $page, $per_page),
        ];
    }

    /**
     * Phân tích URL frontend → detail hoặc list
     *
     * @param array $array_op Mảng URL segments
     * @param int $viewtype Chế độ hiển thị (0 = auto-load bài đầu, 1 = danh sách, 2 = không hiển thị)
     * @return array ['mode' => 'detail'|'list'|'none', 'id' => int, 'alias' => string, 'page' => int, 'row' => ?Entity]
     */
    public function resolveRoute(array $array_op, int $viewtype = 0): array
    {
        // viewtype = 2: không hiển thị gì (chỉ dùng site_title)
        if ($viewtype == 2) {
            return ['mode' => 'none', 'id' => 0, 'alias' => '', 'page' => 1, 'row' => null];
        }

        $alias = (!empty($array_op) && !empty($array_op[0])) ? $array_op[0] : '';

        if (substr($alias, 0, 5) === 'page-') {
            return ['mode' => 'list', 'id' => 0, 'alias' => '', 'page' => max(1, (int) substr($alias, 5)), 'row' => null];
        }

        // viewtype = 0: nếu không có alias, tự động load bài đầu tiên
        if (empty($alias) && $viewtype == 0) {
            $items = $this->repo->getContentList(0, 1, 1, 1);
            if (!empty($items)) {
                $row = $items[0];
                return ['mode' => 'detail', 'id' => $row->id, 'alias' => $row->alias, 'page' => 1, 'row' => $row];
            }
        }

        if (!empty($alias)) {
            $row = $this->repo->findByAlias($alias);
            if ($row) {
                return ['mode' => 'detail', 'id' => $row->id, 'alias' => $alias, 'page' => 1, 'row' => $row];
            }
        }

        return ['mode' => 'list', 'id' => 0, 'alias' => '', 'page' => 1, 'row' => null];
    }

    public function buildItemLinks(array $items, string $base_url, string $rewrite_exturl): array
    {
        $result = [];
        foreach ($items as $entity) {
            $entity->link = $base_url . '&amp;' . NV_OP_VARIABLE . '=' . $entity->alias . $rewrite_exturl;
            $result[$entity->id] = $entity;
        }
        return $result;
    }

    // ── WRITE ──

    /**
     * Thu thập dữ liệu từ Request (Admin & API dùng chung)
     * Gom toàn bộ các hàm nv_Request->get_xxx về một nơi để dễ bảo trì.
     */
    public function collectRequestData($nv_Request, array $defaultData = []): array
    {
        $row = [];
        $row['title'] = $nv_Request->get_title('title', 'post', '', 250);
        $row['alias'] = $nv_Request->get_title('alias', 'post', '');
        $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
        $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', ''));
        $row['status'] = $nv_Request->get_int('status', 'post', 1);
        $row['image'] = $nv_Request->get_string('image', 'post', '');

        return array_merge($defaultData, $row);
    }

    /**
     * Chuẩn hóa dữ liệu trước khi validate/save.
     * Gom logic alias, keywords, image — tránh lặp code giữa controller và API.
     */
    public function prepareSaveData(array $data, array $moduleConfig = [], string $moduleUpload = ''): array
    {
        // Alias: tự sinh từ title nếu rỗng
        $alias = $data['alias'] ?? '';
        $data['alias'] = empty($alias) ? change_alias($data['title']) : change_alias($alias);
        if (!empty($moduleConfig['alias_lower'])) {
            $data['alias'] = strtolower($data['alias']);
        }
        $data['alias'] = nv_substr($data['alias'], 0, 250);

        // Keywords: tự sinh từ title nếu rỗng
        if (empty($data['keywords'])) {
            $data['keywords'] = nv_get_keywords($data['title']);
        }

        // Image: kiểm tra file hợp lệ
        if (!empty($moduleUpload) && isset($data['image'])) {
            $image = $data['image'];
            if (!empty($image) && nv_is_file($image, NV_UPLOADS_DIR . '/' . $moduleUpload)) {
                $data['image'] = substr($image, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $moduleUpload . '/'));
            } else {
                $data['image'] = '';
            }
        }

        return $data;
    }

    /**
     * Lưu item. Data đã được prepareSaveData() + Validator kiểm tra.
     * Tự động gắn weight + timestamps.
     *
     * @param array $data Dữ liệu đã chuẩn hóa
     * @param int $id 0 = thêm mới, >0 = cập nhật
     * @param string $module_name Tên module
     * @param array $config Config module (dùng cho logic weight: 'news_first', ...)
     * @param int $admin_id ID admin thực hiện
     * @return int ID bản ghi
     */
    public function save{Item}(array $data, int $id, string $module_name, array $config = [], int $admin_id = 0): int
    {
        if ($id > 0) {
            $data['edit_time'] = NV_CURRENTTIME;
        } else {
            // Weight: hỗ trợ config 'news_first' (bài mới lên đầu)
            if (!isset($data['weight']) || $data['weight'] <= 0) {
                if (!empty($config['news_first'])) {
                    $data['weight'] = 1;
                    $this->repo->incrementOthersWeight();
                } else {
                    $data['weight'] = $this->repo->getMaxWeight() + 1;
                }
            }
            $data['admin_id'] = $admin_id;
            $data['add_time'] = NV_CURRENTTIME;
            $data['edit_time'] = NV_CURRENTTIME;
            $data['status'] = $data['status'] ?? 1;
        }

        $data = nv_apply_hook($module_name, 'before_{item}_save', [$data], $data);
        $savedId = $this->repo->save($data, $id);
        $this->repo->invalidateCache();

        nv_apply_hook($module_name, '{item}_saved', [
            'id' => $savedId,
            'title' => $data['title'],
            'action' => $id ? 'edit' : 'add',
        ]);

        return $savedId;
    }

    /**
     * Xóa item.
     * @param string $relatedTable Bảng liên quan cần xóa kèm (VD: bảng comment). Để rỗng nếu không có.
     */
    public function delete{Item}(int $id, string $module_name, string $relatedTable = ''): bool
    {
        $row = $this->repo->findById($id);
        if (!$row) { return false; }
        $result = $this->repo->delete($id, $relatedTable);
        if ($result) {
            $this->repo->reorderWeight();
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, '{item}_deleted', ['id' => $id, 'title' => $row->title]);
        }
        return $result;
    }

    public function changeStatus(int $id, string $module_name): int
    {
        $newStatus = $this->repo->toggleStatus($id);
        if ($newStatus >= 0) {
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, '{item}_status_changed', ['id' => $id, 'new_status' => $newStatus]);
        }
        return $newStatus;
    }

    public function changeWeight(int $id, int $newWeight, string $module_name): bool
    {
        $row = $this->repo->findById($id);
        if (!$row) { return false; }
        $this->repo->reorderWeight($id, $newWeight);
        $this->repo->invalidateCache();
        return true;
    }
}
```

**Luồng WRITE chuẩn (DRY):**

```
Controller/API:
  1. $data = $service->collectRequestData($nv_Request)   <── Gom input từ HTTP
  2. $data = $service->prepareSaveData($data, ...)       <── Chuẩn hóa (alias, image)
  3. $validator->validateSave($data, $saveId)            <── Kiểm tra lỗi
  4. $savedId = $service->save{Item}($data, ...)         <── Lưu DB (weight, date, hook)
  5. nv_insert_logs(...)                                  <── Ghi nhật ký
```

> **Quy ước đặt tên method save/delete:** Dùng tên đối tượng cụ thể thay vì generic: `saveContent()`, `saveCat()`, `deleteContent()`, `deleteCat()`. Giúp phân biệt rõ ràng khi module có nhiều đối tượng.

> **Tại sao tách `collectRequestData` và `prepareSaveData`?**
> - `collectRequestData`: Chỉ làm việc với `$nv_Request` (HTTP).
> - `prepareSaveData`: Làm việc với `array` thuần.
> => Việc tách này cho phép bạn tái sử dụng logic chuẩn hóa cho các tính năng không có Request như: **Sao chép bài viết (Copy)**, **Import bài viết từ file**, hoặc **Cronjob**.

> **Sao chép bài viết (Copy):** Service nên có method `duplicate{Item}Data()` để chuẩn bị dữ liệu sao chép từ Entity có sẵn:
> ```php
> public function duplicate{Item}Data({Item}Entity $source): array
> {
>     $data = $source->toArray();
>     $data['id'] = 0;
>     $data['title'] = $data['title'] . ' (Copy)';
>     $data['alias'] = ''; // Để sinh alias mới qua prepareSaveData()
>     $data['status'] = 0; // Tắt mặc định để an toàn
>     return $data;
> }
> ```

> **Tại sao `prepareSaveData()` phải chạy TRƯỚC `Validator`?** Validator kiểm tra alias trùng qua `isAliasExists()`. Nếu validate trước khi chuẩn hóa, alias vẫn **rỗng** (user không nhập) hoặc chưa qua `change_alias()` → `isAliasExists("")` → bỏ sót alias trùng. Phải chuẩn hóa alias trước rồi mới validate mới chính xác.

> **Module có trang Cấu hình (Config)?** Service nên có thêm 3 method: `collectConfigData($nv_Request)` (thu thập), `prepareConfigData($config, ...)` (chuẩn hóa/validate), `formatConfigForView($config)` (định dạng trước khi đẩy ra Smarty). Xem mẫu: `ContentService::collectConfigData()`.

> 📎 Service phức tạp hơn (có `resolveRoute` với `viewtype`, config, duplicate): `src/modules/Content/Content/ContentService.php`
> 📎 Service ngắn gọn: `src/modules/Content/Cat/CatService.php`

---

## Bước 6 — Validator

Ném `InvalidArgumentException` kèm **error code** → Controller map về đúng field lỗi trên UI.

```php
<?php
namespace NukeViet\Module\{mymod}\{Item};

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class {Item}Validator
{
    private {Item}Repository $repo;

    public function __construct({Item}Repository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @throws ValidationException
     */
    public function validateSave(array $data, int $excludeId = 0): void
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[1] = 'empty_title';
        }

        if (trim($data['bodytext'] ?? '') === '') {
            $errors[2] = 'empty_bodytext';
        }

        if (!empty($data['alias']) && $this->repo->isAliasExists($data['alias'], $excludeId)) {
            $errors[3] = 'erroralias';
        }

        if (!empty($errors)) {
            throw new \NukeViet\Module\Content\Shared\ValidationException($errors);
        }
    }
}
```

**Quy ước error code:** `1`=title, `2`=bodytext, `3`=alias. Mở rộng tùy module. Message (`empty_title`) phải khớp key trong `language/vi.php`.

> 📎 `src/modules/Content/Content/ContentValidator.php` · `src/modules/Content/Cat/CatValidator.php`

---

## Bước 7 — Controller

### 7A. Admin — Form Thêm/Sửa (`admin/{item}.php`)

File lớn nhất. Luồng: **CSRF → Parse Input → `prepareSaveData()` → Validator → `saveItem()` → Log → JSON response**.

```php
<?php
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\{mymod}\{Item}\{Item}Repository;
use NukeViet\Module\{mymod}\{Item}\{Item}Service;
use NukeViet\Module\{mymod}\{Item}\{Item}Validator;

$itemRepo = new {Item}Repository($db, $config['table_row'], $nv_Cache, $module_name);

$service = new {Item}Service($itemRepo);

$id = $nv_Request->get_int('id', 'post,get', 0);
$entity = null;

if ($id) {
    $entity = $itemRepo->findById($id);
    if (empty($entity)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
            . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }
    $page_title = $nv_Lang->getModule('edit');
} else {
    $page_title = $nv_Lang->getModule('add');
}

// ══════ XỬ LÝ POST (AJAX) ══════
if ($nv_Request->isset_request('checkss', 'post')) {
    if (!csrf_check($nv_Request->get_string('checkss', 'post'), $csrf_key)) {
        nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);
    }

    $respon = ['status' => 'error', 'mess' => ''];

    // 1. Trích xuất dữ liệu từ Request (DRY)
    $row = $service->collectRequestData($nv_Request);

    // 2. Chuẩn hóa dữ liệu qua Service (Alias, Keywords, Image)
    $row = $service->prepareSaveData($row, $config, $module_upload);

    // 3. Validate → Save → Log
    try {
        $saveId = $id ?: 0;
        $validator = new {Item}Validator($itemRepo);
        $validator->validateSave($row, $saveId);
        $savedId = $service->save{Item}($row, $saveId, $module_name, $config, $admin_info['admin_id']);
        nv_insert_logs(NV_LANG_DATA, $module_name, $saveId ? 'Edit' : 'Add', 'ID: ' . $savedId, $admin_info['userid']);

    } catch (\NukeViet\Module\Content\Shared\ValidationException $e) {
        // Lỗi validation gom nhiều lỗi → trả về mảng cho JS highlight từng field
        $respon['errors'] = $e->getErrors();
        $respon['mess'] = $nv_Lang->getModule(reset($respon['errors'])); // Lấy lỗi đầu tiên làm message
        nv_jsonOutput($respon);
    } catch (\Throwable $e) {
        // Lỗi không mong đợi → ghi log + trả lỗi chung (không lộ chi tiết nội bộ)
        trigger_error($e);
        $respon['mess'] = $nv_Lang->getGlobal('error_system');
        nv_jsonOutput($respon);
    }

    $respon['status'] = 'success';
    $respon['mess'] = $nv_Lang->getGlobal('save_success');
    $respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
        . '&' . NV_NAME_VARIABLE . '=' . $module_name;
    nv_jsonOutput($respon);

} elseif (empty($id)) {
    // Dữ liệu mặc định form thêm mới
    $row = ['title' => '', 'alias' => '', 'description' => '', 'bodytext' => '',
            'keywords' => '', 'image' => '', 'status' => 1];
}

// ══════ RENDER FORM ══════
$row_data = isset($entity) ? $entity->toArray() : $row;

$tpl = new \NukeViet\Template\NVSmarty();
$tpl->setTemplateDir(get_module_tpl_dir('{item}.tpl'));
$tpl->assign('LANG', $nv_Lang);
$tpl->assign('MODULE_NAME', $module_name);
$tpl->assign('OP', $op);
$tpl->assign('ID', $id);
$tpl->assign('DATA', $row_data);
$tpl->assign('CHECKSS', csrf_create($csrf_key));

$contents = $tpl->fetch('{item}.tpl');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```

> 📎 Form đầy đủ (có image upload, editor, schema, layout): `src/modules/Content/admin/content.php`
> 📎 Form + danh sách cùng file: `src/modules/Content/admin/cat.php`

### 7B. Admin — Danh Sách (`admin/main.php`)

```
getAllAdmin() → autoCorrectWeight() → gắn CSRF per-row → toArray() → render
```

> 📎 Copy nguyên mẫu: `src/modules/Content/admin/main.php` (76 dòng)

### 7C. Admin — AJAX Handlers ({item}-del, {item}-change-status, {item}-change-weight)

Mỗi file là 1 AJAX handler nhỏ (~50 dòng). Pattern giống nhau:

```php
<?php
if (!defined('NV_IS_FILE_ADMIN')) { exit('Stop!!!'); }

$id = $nv_Request->get_int('id', 'post', 0);

// CSRF per-row
if (!csrf_check($nv_Request->get_string('checkss', 'post'),
    $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput(['status' => 'error', 'mess' => $nv_Lang->getGlobal('error_checkss')]);
}

if ($id > 0) {
    $itemRepo = new \NukeViet\Module\{mymod}\{Item}\{Item}Repository($db, $config['table_row'], $nv_Cache, $module_name);
    $service = new \NukeViet\Module\{mymod}\{Item}\{Item}Service($itemRepo);

    // Cho delete:
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Del', 'id ' . $id, $admin_info['userid']);
    if ($service->deleteItem($id, $module_name)) {
        nv_jsonOutput(['status' => 'success']);
    }

    // Cho change-status:
    // $newStatus = $service->changeStatus($id, $module_name);
    // if ($newStatus >= 0) { nv_jsonOutput(['status' => 'success']); }

    // Cho change-weight:
    // $newWeight = $nv_Request->get_int('new_weight', 'post', 0);
    // if ($service->changeWeight($id, $newWeight, $module_name)) { nv_jsonOutput(['status' => 'success']); }
}

nv_jsonOutput(['status' => 'error', 'mess' => 'Error']);
```

> 📎 Mẫu xóa: `src/modules/Content/admin/content-del.php`
> 📎 Mẫu status: `src/modules/Content/admin/cat-change-status.php`
> 📎 Mẫu xóa có ràng buộc: `src/modules/Content/admin/cat-del.php`

### 7D. Frontend — `funcs/main.php`

```php
<?php
if (!defined('NV_IS_MOD_{MYMOD}')) { exit('Stop!!!'); }

use NukeViet\Module\{mymod}\{Item}\{Item}Repository;
use NukeViet\Module\{mymod}\{Item}\{Item}Service;

$itemRepo = new {Item}Repository($db, $tables, $nv_Cache, $module_name);
$service = new {Item}Service($itemRepo);

try {
    $route = $service->resolveRoute($array_op);

    if ($route['mode'] === 'detail') {
        $row = $route['row'];
        if (empty($row->status) and !defined('NV_IS_MODADMIN')) {
            throw new \Exception('Not found', 404);
        }

        $page_title = $row->title;
        // ... SEO, Schema, Hook before_detail_theme ...

        $contents = nv_{mymod}_detail($row);
    } else {
        $page = $route['page'];
        $per_page = (int) ($config['per_page'] ?? 20);
        $result = $service->getList($page, $per_page);
        $array_data = $service->buildItemLinks($result['items'], $base_url, $global_config['rewrite_exturl']);
        $generate_page = nv_alias_page($page_title, $base_url, $result['total'], $per_page, $page);

        $contents = nv_{mymod}_list($array_data, $generate_page);
    }
} catch (\Throwable $e) {
    if ($e->getCode() == 404) { nv_error404(); }
    trigger_error($e);
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```

> 📎 Frontend đầy đủ (SEO, Schema.org, comment, lượt xem, ảnh responsive): `src/modules/Content/funcs/main.php` (259 dòng)

### 7E. `theme.php` — Render Functions

```php
<?php
if (!defined('NV_IS_MOD_{MYMOD}')) { exit('Stop!!!'); }

function nv_{mymod}_detail($row): string
{
    $row_array = $row->toArray();

    if (defined('NV_IS_MODADMIN')) {
        $row_array['adminlink'] = NV_BASE_ADMINURL . 'index.php?...' ;
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('detail.tpl'));
    $tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $tpl->assign('CONTENT', $row_array);

    return $tpl->fetch('detail.tpl');
}

function nv_{mymod}_list(array $array_data, string $generate_page): string
{
    $list = [];
    foreach ($array_data as $entity) {
        $list[] = $entity->toArray();
    }

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->setTemplateDir(get_module_tpl_dir('main_list.tpl'));
    $tpl->assign('LANG', \NukeViet\Core\Language::$lang_module);
    $tpl->assign('DATA', $list);
    $tpl->assign('GENERATE_PAGE', $generate_page);

    return $tpl->fetch('main_list.tpl');
}
```

> 📎 Theme đầy đủ (xử lý thumbnail, description tự động): `src/modules/Content/theme.php`

---

### Bước 8 — API

### Chiến lược giảm code trùng lặp giữa Admin API và Public API

Sử dụng `BaseApi` và `BaseUapi` nằm trong thư mục `Shared/` để tập trung logic khởi tạo hệ thống (`bootstrap`).

```php
// Shared/BaseApi.php
abstract class BaseApi implements IApi 
{
    protected function bootstrap(): void
    {
        global $db, $nv_Cache, $module_config;
        $this->db = $db;
        $this->cache = $nv_Cache;
        $this->module_name = Api::getModuleName();
        $module_info = Api::getModuleInfo();
        $this->tables = new Tables(NV_PREFIXLANG, $module_info['module_data']);
        $this->config = $module_config[$this->module_name];
    }
}
```

**Nguyên tắc:** 
1. Đưa mọi business logic vào **Service**. 
2. API file kế thừa `BaseApi`/`BaseUapi` và gọi `$this->bootstrap()` ngay đầu hàm `execute()`.

```php
class {Item}GetDetail extends BaseApi
{
    public function execute()
    {
        $this->bootstrap(); // Tự động nạp $this->db, $this->tables, $this->config...
        
        $repo = new {Item}Repository($this->db, $this->tables, $this->cache, $this->module_name);
        $service = new {Item}Service($repo);
        
        $id = $nv_Request->get_int('id', 'post', 0);

        try {
            $entity = $service->getDetail($id);
        } catch (\Exception $e) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();
        return $this->result->getResult();
    }
}
```

### 5 điểm khác nhau cố định giữa Admin API và Public API

**Bước 3 — Public API:** Copy boilerplate, đổi 5 điểm cố định, thêm logic riêng (nếu có).

```php
<?php
namespace NukeViet\Module\{mymod}\uapi;                     // ① namespace

use NukeViet\Uapi\Uapi;                                    // ② Uapi thay Api
use NukeViet\Uapi\UapiResult;                               // ③ UapiResult
use NukeViet\Uapi\UiApi;                                    // ④ UiApi
use NukeViet\Module\{mymod}\{Item}\{Item}Repository;
use NukeViet\Module\{mymod}\{Item}\{Item}Service;

if (!defined('NV_MAINFILE')) { exit('Stop!!!'); }            // ⑤ Guard

class {Item}GetDetail implements UiApi
{
    private $result;

    public static function getCat() { return '{mymod}'; }   // Không có getAdminLev()
    public function setResultHander(UapiResult $result) { $this->result = $result; }

    public function execute()
    {
        global $db, $nv_Cache, $nv_Request, $nv_Lang;

        $module_info = Uapi::getModuleInfo();               // Uapi thay Api
        $config = $module_config[Uapi::getModuleName()];
        $repo = new {Item}Repository($db, $config['table_row'], $nv_Cache, Uapi::getModuleName());
        $service = new {Item}Service($repo);

        $id = $nv_Request->get_int('id', 'post', 0);

        // ── Gọi Service — cùng logic với Admin API ──
        try {
            $entity = $service->getDetail($id);
        } catch (\Exception $e) {
            $this->result->setCode(UapiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        // Public: chỉ trả item active
        if (!$entity->status) {
            $this->result->setCode(UapiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();
        return $this->result->getResult();
    }
}
```

### 5 điểm khác nhau cố định giữa Admin API và Public API

| # | Admin API (`Api/`) | Public API (`uapi/`) |
|---|-------|-------|
| ① | `namespace ...\Api` | `namespace ...\uapi` |
| ② | `use NukeViet\Api\Api` | `use NukeViet\Uapi\Uapi` |
| ③ | `ApiResult` | `UapiResult` |
| ④ | `implements IApi` + `getAdminLev()` | `implements UiApi` (không có `getAdminLev`) |
| ⑤ | `if (!defined('NV_ADMIN') or !defined('NV_MAINFILE'))` | `if (!defined('NV_MAINFILE'))` |

### Logic khác nhau thường gặp

| Tình huống | Admin API | Public API |
|------------|-----------|------------|
| Item inactive | Trả bình thường | Trả lỗi `error_no_data` |
| Danh sách | `getAll()` (cả inactive) | `getActiveList()` (chỉ active) |
| Write (add/edit/delete) | Có | Thường không có |

> **Tóm lại:** Khi viết Admin API, nếu thấy `execute()` có nhiều hơn 5 dòng logic → chuyển vào Service. Khi viết Public API sau, chỉ cần copy boilerplate + đổi 5 điểm + thêm check `status`. Business logic DUY NHẤT 1 chỗ trong Service.

> 📎 `src/modules/Content/Api/CatGetDetail.php` · `src/modules/Content/uapi/CatGetDetail.php`
> 📎 `src/modules/Content/Api/CatGetList.php` · `src/modules/Content/uapi/ContentGetList.php`

---

## Hook & Cache

### Hook — 5 event chuẩn

| Tag | Khi nào | Loại |
|-----|---------|------|
| `before_{item}_save` | Trước INSERT/UPDATE | Filter (sửa data) |
| `{item}_saved` | Sau INSERT/UPDATE | Action |
| `{item}_deleted` | Sau DELETE | Action |
| `{item}_status_changed` | Sau đổi status | Action |
| `before_detail_theme` | Trước render frontend detail | Filter |

```php
// Phát (trong Service/Controller):
$data = nv_apply_hook($module_name, 'before_{item}_save', [$data], $data);

// Lắng nghe (trong module khác, file hooks/):
nv_add_hook($module_name, '{item}_saved', $priority, function($args, $from, $receive) {
    // $args['id'], $args['title'], $args['action']
    return null;
}, $hook_module, $pid);
```

### Cache — 3 lệnh duy nhất

| Lệnh | Dùng khi |
|-------|---------|
| `$nv_Cache->db($sql, '', $module_name)` | Đọc data ít thay đổi (config) |
| `$nv_Cache->delMod($module_name)` | Sau mọi CUD — **BẮT BUỘC** |
| `$nv_Cache->delAll()` | Đổi config toàn cục |

---

## Testing

### Nguyên tắc Mocking trong Unit Test
- **Repository**: Mock các hàm `findById`, `save`, `delete`, `isAliasExists`, `toggleStatus`...
- **PDO ($db)**: Giả lập DB cho Repo test.
- **Cache**: Kiểm tra `delMod` được gọi khi CUD.
- **Hook**: Đảm bảo `nv_apply_hook` được phát đúng tag.

### Kịch bản Unit Test bắt buộc

| Lớp | Kịch bản | Assert (Kết quả mong đợi) |
|-----|----------|---------------------------|
| **Validator** | Data hợp lệ | Thành công, không ném lỗi |
| **Validator** | `title=''` + `alias` trùng cùng lúc | `ValidationException`, `getErrors()` trả 2 lỗi (code 1 + 3) |
| **Validator** | Chỉ `$data['title'] = ''` | `ValidationException`, code 1 |
| **Validator** | Chỉ `$data['alias']` đã tồn tại | `ValidationException`, code 3 |
| **Service** | `getDetail(id)` - DB có dữ liệu | Trả về chuẩn `{Item}Entity` |
| **Service** | `getDetail(id)` - DB trống | Ném `RuntimeException` (404) |
| **Service** | `resolveRoute(['bai-viet-1'])` | `['mode' => 'detail', 'row' => Entity]` |
| **Service** | `resolveRoute(['page-2'])` | `['mode' => 'list', 'page' => 2]` |
| **Service** | `save{Item}()` - Thêm mới | Repo `save` được gọi, cache xóa, hook phát |
| **Service** | `delete{Item}()` | Repo `delete()` + `reorderWeight()`, cache xóa |
| **Service** | `changeStatus()` | Repo `toggleStatus()`, cache xóa, hook phát |

```php
// Mocking pattern
$repo = $this->createMock({Item}Repository::class);
$repo->method('findById')->willReturn(new {Item}Entity());
$service = new {Item}Service($repo);
$this->assertInstanceOf({Item}Entity::class, $service->getDetail(1));
```

### Checklist File Test
- **Unit**:  `{Item}ValidatorTest.php`, `{Item}ServiceTest.php`, `{Item}EntityTest.php`, `{Item}RepositoryTest.php`
- **Acceptance**: `Admin{Item}Cest.php` (Thêm/Sửa/Xóa UI), `PublicViewCest.php`
- **API**: `AdminApiCest.php`, `PublicUapiCest.php`

> 📎 Setup test environment: `docs/knowledge/testing.md`

---

## Checklist

- [ ] **Bước 1 — Thư mục:** Tạo cấu trúc thư mục
- [ ] **Bước 2 — Hệ thống:** `version.php`, `functions.php`, `admin.functions.php`, `admin.menu.php`, `action_mysql.php`, `language/vi.php`
- [ ] **Bước 3 — Entity:** `{Item}Entity.php` (properties + `toArray` + `fromArray`)
- [ ] **Bước 4 — Repository:** `{Item}Repository.php` (CRUD + weight + config + cache)
- [ ] **Bước 5 — Service:** `{Item}Service.php` (`prepareSaveData` + save/delete/status/weight + hook)
- [ ] **Bước 6 — Validator:** `{Item}Validator.php` (validate + ném `InvalidArgumentException`)
- [ ] **Bước 7 — Controller:** `admin/main.php` · `admin/{item}.php` · 3 AJAX handlers · `funcs/main.php` · `theme.php`
- [ ] **Bước 8 — API:** `Api/{Item}GetList.php` · `uapi/{Item}GetList.php`
- [ ] **Hook:** 5 events · **Cache:** `invalidateCache()` sau mọi CUD · **Log:** `nv_insert_logs()`
- [ ] **Test:** Unit (Validator + Service) · Acceptance · API

---

## Tham Khảo Nhanh — File trong `src/modules/Content/`

| Cần gì | File | Dòng |
|--------|------|------|
| Entity đơn giản | `Cat/CatEntity.php` | 80 dòng |
| Entity + Relationship | `Content/ContentEntity.php` | 94 dòng |
| Repository đầy đủ | `Content/ContentRepository.php` | 423 dòng |
| Validator | `Content/ContentValidator.php` | 61 dòng |
| Exception gom nhiều lỗi | `Shared/ValidationException.php` | 32 dòng |
| Service đầy đủ (config, duplicate, viewtype) | `Content/ContentService.php` | 376 dòng |
| Service ngắn gọn | `Cat/CatService.php` | 209 dòng |
| Admin form phức tạp | `admin/content.php` | 240 dòng |
| Admin list + form cùng file | `admin/cat.php` | 133 dòng |
| Admin danh sách đơn giản | `admin/main.php` | 81 dòng |
| AJAX xóa | `admin/content-del.php` | 54 dòng |
| AJAX xóa có ràng buộc | `admin/cat-del.php` | 58 dòng |
| AJAX status | `admin/cat-change-status.php` | 46 dòng |
| Frontend controller | `funcs/main.php` | 253 dòng |
| Theme render | `theme.php` | 99 dòng |
| Admin API | `Api/CatGetList.php` | 64 dòng |
| Public API | `uapi/ContentGetList.php` | 64 dòng |
| SQL schema | `action_mysql.php` | 83 dòng |
| Hằng số dùng chung | `Shared/SchemaHelper.php` | 43 dòng |
