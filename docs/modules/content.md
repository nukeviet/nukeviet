# Hướng Dẫn Xây Dựng Module NukeViet 5

> Dựa trên code thực tế `src/modules/content/`

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

**8 quy tắc bắt buộc:** (1) Cấm hardcode tên bảng — dùng `NV_PREFIXLANG . '_' . $module_data` (2) Input qua `$nv_Request` (3) CSRF cho mọi write: `csrf_check()` / `csrf_create()` (4) Cache: gọi `invalidateCache()` sau mọi CUD (5) Log: `nv_insert_logs()` cho mọi CUD (6) Hook: `nv_apply_hook()` phát event (7) JS file riêng `themes/[theme]/js/[module].js` — cấm `<script>` trong `.tpl` (8) Frontend render qua `theme.php`

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
│   │   ├── Shared/                  # PSR-4: NukeViet\Module\{mymod}\Shared\
│   │   │   ├── ConfigRepositoryTrait.php  # Trait cấu hình dùng chung
│   │   │   ├── {Item}Entity.php
│   │   │   ├── {Item}Repository.php
│   │   │   ├── {Item}Validator.php
│   │   │   └── {Item}Service.php
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

use NukeViet\Module\{mymod}\Shared\{Item}Repository;

// CHỈ load repo tối thiểu — file này chạy MỌI request tới module
$repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);
${mymod}_config = $repo->getConfig();

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA
    . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
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

use NukeViet\Module\{mymod}\Shared\{Item}Repository;
$repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_data, $nv_Cache, $module_name);
${mymod}_config = $repo->getConfig();
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

Quy ước: DROP trước → CREATE. Tên bảng = `prefix_lang_moduledata[_suffix]`. Luôn có bảng `_config`.

```php
<?php
if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . ';';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_config;';

$sql_create_module = $sql_drop_module;

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . " (
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

$sql_create_module[] = 'CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_config (
    config_name varchar(30) NOT NULL,
    config_value varchar(255) NOT NULL,
    UNIQUE KEY config_name (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

$sql_create_module[] = 'INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_config VALUES
    ('per_page', '20'),
    ('alias_lower', '1')
";
```

> 📎 Mẫu có nhiều bảng (thêm `_cat`): `src/modules/content/action_mysql.php`

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

// Delete
$lang_module['{item}_delete_confirm'] = 'Bạn có chắc chắn muốn xóa?';
$lang_module['{item}_delete_unsuccess'] = 'Xóa không thành công';
```

---

## Bước 3 — Entity

Mỗi bảng DB = 1 Entity. Typed Properties với giá trị mặc định.

```php
<?php
namespace NukeViet\Module\{mymod}\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

class {Item}Entity
{
    /**
     * Danh sách các thuộc tính chỉ dùng cho hiển thị (không có trong DB).
     * Mọi thuộc tính public khác mặc định được coi là cột Database.
     */
    /**
     * Danh sách các thuộc tính KHÔNG phải cột DB (view-only + khóa chính).
     * Gộp luôn PK ('id' hoặc 'catid') vào đây để getDbColumns() chỉ cần 1 array_diff.
     */
    private const VIEW_FIELDS = ['id', 'link', 'url_edit', 'url_copy', 'checkss'];

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

    /**
     * Lấy danh sách các cột thực tế trong Database.
     * Tự động lọc bỏ các trường View và Khóa chính (đã khai báo trong VIEW_FIELDS).
     */
    public static function getDbColumns(): array
    {
        $allFields = array_keys(get_class_vars(self::class));
        return array_values(array_diff($allFields, self::VIEW_FIELDS));
    }

    // ── Relationship (nếu cần) ──
    // public ?CatEntity $category = null;

    /**
     * Entity → Array cho Smarty/Hook
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        // Relationship lồng nhau:
        // if ($this->category instanceof CatEntity) {
        //     $arr['category'] = $this->category->toArray();
        // }
        return $arr;
    }

    /**
     * Array (FETCH_ASSOC) → Entity. Tự bỏ qua NULL.
     */
    public static function fromArray(array $data): self
    {
        $entity = new self();
        foreach ($data as $key => $value) {
            if (property_exists($entity, $key) && $value !== null) {
                $entity->$key = $value;
            }
        }
        return $entity;
    }
}
```

**4 lưu ý:** (1) Typed Properties BẮT BUỘC có `= ''` hoặc `= 0` (2) Không dùng `?string` trừ Relationship (3) `fromArray()` lọc NULL an toàn — không cần `?string` cho cột DB (4) **Khóa chính linh hoạt:** PK mặc định là `id`, nhưng bảng phụ có thể dùng PK khác (VD: `catid` cho bảng `_cat`). Gộp PK vào `VIEW_FIELDS` luôn để `getDbColumns()` chỉ cần 1 lần `array_diff`.

> 📎 Entity có Relationship: `src/modules/content/Shared/ContentEntity.php`
> 📎 Entity đơn giản: `src/modules/content/Shared/CatEntity.php`

---

## Bước 4 — Repository

Tập trung **toàn bộ SQL**. Constructor nhận 4 tham số: `$db`, `$table`, `$cache`, `$module_name`.

### 4.1 — ConfigRepositoryTrait (dùng chung)

Logic đọc/ghi bảng `_config` **hoàn toàn giống nhau** giữa các Repository trong cùng module. Thay vì copy-paste, dùng Trait:

```php
<?php
namespace NukeViet\Module\{mymod}\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;

/**
 * ConfigRepositoryTrait — Trait dùng chung cho các Repository cần quản lý cấu hình module.
 *
 * Yêu cầu class sử dụng phải có các property: $db (PDO), $table (string), $cache, $module_name (string).
 * Bảng config có dạng: {$table}_config (config_name, config_value).
 */
trait ConfigRepositoryTrait
{
    /**
     * Đọc cấu hình module từ DB (có cache)
     */
    public function getConfig(): array
    {
        $sql = 'SELECT config_name, config_value FROM ' . $this->table . '_config';
        $list = $this->cache->db($sql, '', $this->module_name);
        $config = [];
        foreach ($list as $values) {
            $config[$values['config_name']] = $values['config_value'];
        }
        return $config;
    }

    /**
     * Lưu cấu hình module
     */
    public function saveConfig(array $config): void
    {
        $sth = $this->db->prepare('UPDATE ' . $this->table . '_config SET config_value = :config_value WHERE config_name = :config_name');
        foreach ($config as $config_name => $config_value) {
            $sth->bindValue(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindValue(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }
    }
}
```

> 📎 Trait thực tế: `src/modules/content/Shared/ConfigRepositoryTrait.php`

### 4.2 — Repository Skeleton

```php
<?php
namespace NukeViet\Module\{mymod}\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use PDO;

class {Item}Repository
{
    use ConfigRepositoryTrait;

    private PDO $db;
    private string $table;
    private $cache;
    private string $module_name;

    public function __construct(PDO $db, string $table, $cache, string $module_name)
    {
        $this->db = $db;
        $this->table = $table;
        $this->cache = $cache;
        $this->module_name = $module_name;
    }

    /**
     * Helper: fetchAll + map thành Entity[]
     * Gom logic FETCH_ASSOC + fromArray() vào 1 chỗ duy nhất
     */
    private function fetchEntities(\PDOStatement $stmt): array
    {
        return array_map([{Item}Entity::class, 'fromArray'], $stmt->fetchAll(PDO::FETCH_ASSOC));
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
        $stmt->closeCursor();
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
                $params[':' . $key] = [$value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR];
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
            $stmt->bindValue(':' . $key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
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
     * Sắp xếp lại weight (copy y nguyên cho mọi module có cột weight)
     */
    public function reorderWeight(int $movedId = 0, int $newWeight = 0): void
    {
        $sql = 'SELECT id FROM ' . $this->table;
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

        $weight = 0;
        $stmtUpdate = $this->db->prepare('UPDATE ' . $this->table . ' SET weight = :weight WHERE id = :id');
        while ($row = $stmt->fetch()) {
            ++$weight;
            if ($movedId > 0 && $weight == $newWeight) {
                ++$weight;
            }
            $stmtUpdate->bindValue(':weight', $weight, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':id', $row['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
        $stmt->closeCursor();

        if ($movedId > 0 && $newWeight > 0) {
            $stmtUpdate->bindValue(':weight', $newWeight, PDO::PARAM_INT);
            $stmtUpdate->bindValue(':id', $movedId, PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
    }

    /**
     * Tự sửa weight sai lệch. Trả true nếu có thay đổi.
     */
    public function autoCorrectWeight(array &$entities): bool
    {
        $iw = 0;
        $is_updated = false;
        $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET weight = :weight WHERE id = :id');
        foreach ($entities as $entity) {
            ++$iw;
            if ($iw != $entity->weight) {
                $entity->weight = $iw;
                $stmt->bindValue(':weight', $iw, PDO::PARAM_INT);
                $stmt->bindValue(':id', $entity->id, PDO::PARAM_INT);
                $stmt->execute();
                $is_updated = true;
            }
        }
        return $is_updated;
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

> **Lưu ý:** `use ConfigRepositoryTrait;` thay thế cho việc copy-paste `getConfig()` / `saveConfig()`. Module có nhiều Repository (VD: `CatRepository` + `ContentRepository`) chỉ cần khai báo `use ConfigRepositoryTrait;` — tất cả đều dùng chung bảng `{$table}_config`.

> 📎 Repository đầy đủ hơn (có `getRelated`, `incrementHits`, `countByCatid`): `src/modules/content/Shared/ContentRepository.php`
> 📎 Repository cho bảng phụ (PK là `catid`): `src/modules/content/Shared/CatRepository.php`
> 📎 Trait config: `src/modules/content/Shared/ConfigRepositoryTrait.php`

---

## Bước 5 — Service

Business logic thuần. Hai trách nhiệm chính:

1. **`prepareSaveData()`** — Chuẩn hóa dữ liệu (alias, keywords, image) trước khi validate. Gom logic chung để tránh lặp code giữa admin controller, API, và bất kỳ entry point nào khác.
2. **`saveItem()` / `saveCat()`** — Lưu dữ liệu đã validate: gắn weight + timestamps → save → clear cache → phát hook.

```php
<?php
namespace NukeViet\Module\{mymod}\Shared;

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
        }

        $data = nv_apply_hook($module_name, 'before_{item}_save', [$data], $data);
        $savedId = $this->repo->save($data, $id);
        $this->repo->invalidateCache();

        nv_apply_hook($module_name, '{item}_saved', [
            'id' => $savedId, 'title' => $data['title'],
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

> 📎 Service phức tạp hơn (có `resolveRoute` với `viewtype`, config, duplicate): `src/modules/content/Shared/ContentService.php`
> 📎 Service ngắn gọn: `src/modules/content/Shared/CatService.php`

---

## Bước 6 — Validator

Ném `InvalidArgumentException` kèm **error code** → Controller map về đúng field lỗi trên UI.

```php
<?php
namespace NukeViet\Module\{mymod}\Shared;

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
     * @throws \InvalidArgumentException code=1 title, code=2 bodytext, code=3 alias
     */
    public function validateSave(array $data, int $excludeId = 0): void
    {
        if (empty($data['title'])) {
            throw new \InvalidArgumentException('empty_title', 1);
        }

        if (trim($data['bodytext'] ?? '') === '') {
            throw new \InvalidArgumentException('empty_bodytext', 2);
        }

        if (!empty($data['alias']) && $this->repo->isAliasExists($data['alias'], $excludeId)) {
            throw new \InvalidArgumentException('erroralias', 3);
        }
    }
}
```

**Quy ước error code:** `1`=title, `2`=bodytext, `3`=alias. Mở rộng tùy module. Message (`empty_title`) phải khớp key trong `language/vi.php`.

> 📎 `src/modules/content/Shared/ContentValidator.php` · `src/modules/content/Shared/CatValidator.php`

---

## Bước 7 — Controller

### 7A. Admin — Form Thêm/Sửa (`admin/{item}.php`)

File lớn nhất. Luồng: **CSRF → Parse Input → `prepareSaveData()` → Validator → `saveItem()` → Log → JSON response**.

```php
<?php
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

use NukeViet\Module\{mymod}\Shared\{Item}Service;

$service = new {Item}Service($repo);  // $repo từ admin.functions.php

$id = $nv_Request->get_int('id', 'post,get', 0);
$entity = null;

if ($id) {
    $entity = $repo->findById($id);
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
    $row = $service->prepareSaveData($row, ${mymod}_config, $module_upload);

    // 3. Validate → Save → Log
    try {
        $saveId = $id ?: 0;
        $validator = new \NukeViet\Module\{mymod}\Shared\{Item}Validator($repo);
        $validator->validateSave($row, $saveId);
        $savedId = $service->save{Item}($row, $saveId, $module_name, ${mymod}_config, $admin_info['admin_id']);
        nv_insert_logs(NV_LANG_DATA, $module_name, $saveId ? 'Edit' : 'Add', 'ID: ' . $savedId, $admin_info['userid']);

    } catch (\InvalidArgumentException $e) {
        $fieldMap = [1 => 'title', 2 => 'bodytext', 3 => 'alias'];
        $respon['input'] = $fieldMap[$e->getCode()] ?? 'title';
        $respon['mess'] = $nv_Lang->getModule($e->getMessage());
        nv_jsonOutput($respon);
    } catch (\Exception $e) {
        $respon['mess'] = $e->getMessage();
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

> 📎 Form đầy đủ (có image upload, editor, schema, layout): `src/modules/content/admin/content.php`
> 📎 Form + danh sách cùng file: `src/modules/content/admin/cat.php`

### 7B. Admin — Danh Sách (`admin/main.php`)

```
getAllAdmin() → autoCorrectWeight() → gắn CSRF per-row → toArray() → render
```

> 📎 Copy nguyên mẫu: `src/modules/content/admin/main.php` (76 dòng)

### 7C. Admin — AJAX Handlers ({item}-del, {item}-change-status, {item}-change-weight)

Mỗi file là 1 AJAX handler nhỏ (~50 dòng). Pattern giống nhau:

```php
<?php
if (!defined('NV_IS_FILE_ADMIN')) { exit('Stop!!!'); }

$id = $nv_Request->get_int('id', 'post', 0);

// CSRF per-row
if (!csrf_check($nv_Request->get_string('checkss', 'post'),
    $admin_info['admin_id'] . '_' . $module_name . '_' . $id)) {
    nv_jsonOutput(['success' => 0, 'text' => $nv_Lang->getGlobal('error_checkss')]);
}

if ($id > 0) {
    $service = new \NukeViet\Module\{mymod}\Shared\{Item}Service($repo);

    // Cho delete:
    nv_insert_logs(NV_LANG_DATA, $module_name, 'Del', 'id ' . $id, $admin_info['userid']);
    if ($service->deleteItem($id, $module_name)) {
        nv_jsonOutput(['success' => 1]);
    }

    // Cho change-status:
    // $newStatus = $service->changeStatus($id, $module_name);
    // if ($newStatus >= 0) { nv_jsonOutput(['success' => 1]); }

    // Cho change-weight:
    // $newWeight = $nv_Request->get_int('new_weight', 'post', 0);
    // if ($service->changeWeight($id, $newWeight, $module_name)) { nv_jsonOutput(['success' => 1]); }
}

nv_jsonOutput(['success' => 0, 'text' => 'Error']);
```

> 📎 Mẫu xóa: `src/modules/content/admin/content-del.php`
> 📎 Mẫu status: `src/modules/content/admin/cat-change-status.php`
> 📎 Mẫu xóa có ràng buộc: `src/modules/content/admin/cat-del.php`

### 7D. Frontend — `funcs/main.php`

```php
<?php
if (!defined('NV_IS_MOD_{MYMOD}')) { exit('Stop!!!'); }

use NukeViet\Module\{mymod}\Shared\{Item}Service;

$service = new {Item}Service($repo);

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
        $per_page = (int) (${mymod}_config['per_page'] ?? 20);
        $result = $service->getList($page, $per_page);
        $array_data = $service->buildItemLinks($result['items'], $base_url, $global_config['rewrite_exturl']);
        $generate_page = nv_alias_page($page_title, $base_url, $result['total'], $per_page, $page);

        $contents = nv_{mymod}_list($array_data, $generate_page);
    }
} catch (\Exception $e) {
    if ($e->getCode() == 404) { nv_error404(); }
    else { trigger_error($e->getMessage()); }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
```

> 📎 Frontend đầy đủ (SEO, Schema.org, comment, lượt xem, ảnh responsive): `src/modules/content/funcs/main.php` (259 dòng)

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

> 📎 Theme đầy đủ (xử lý thumbnail, description tự động): `src/modules/content/theme.php`

---

## Bước 8 — API

### Chiến lược giảm code trùng lặp giữa Admin API và Public API

Admin API (`Api/`, implements `IApi`) và Public API (`uapi/`, implements `UiApi`) có **hai interface khác type** nên không thể kế thừa class. Nhưng business logic bên trong lại gần như giống nhau.

**Nguyên tắc:** Đưa mọi business logic vào **Service** (Bước 6). API file chỉ là **thin wrapper** ~30 dòng: init repo → gọi Service → trả result.

```
Admin API  ──┐
             ├──▶ Service (chứa toàn bộ logic)  ──▶ Repository ──▶ DB
Public API ──┘
```

### Quy trình triển khai

**Bước 1 — Service trước:** Viết method trong Service cho mỗi nghiệp vụ.

```php
// Shared/{Item}Service.php
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
```

**Bước 2 — Admin API:** Thin wrapper gọi Service, bắt Exception, set result.

```php
<?php
namespace NukeViet\Module\{mymod}\Api;

use NukeViet\Api\Api;
use NukeViet\Api\ApiResult;
use NukeViet\Api\IApi;
use NukeViet\Module\{mymod}\Shared\{Item}Repository;
use NukeViet\Module\{mymod}\Shared\{Item}Service;

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) { exit('Stop!!!'); }

class {Item}GetDetail implements IApi
{
    private $result;

    public static function getAdminLev() { return Api::ADMIN_LEV_MOD; }
    public static function getCat() { return '{mymod}'; }
    public function setResultHander(ApiResult $result) { $this->result = $result; }

    public function execute()
    {
        global $db, $nv_Cache, $nv_Request, $nv_Lang;

        $module_info = Api::getModuleInfo();
        $repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_info['module_data'], $nv_Cache, Api::getModuleName());
        $service = new {Item}Service($repo);

        $id = $nv_Request->get_int('id', 'post', 0);

        // ── Gọi Service — logic chung duy nhất 1 chỗ ──
        try {
            $entity = $service->getDetail($id);
        } catch (\Exception $e) {
            $this->result->setCode(ApiResult::CODE_UNKONW)
                ->setMessage($nv_Lang->getGlobal('error_no_data'));
            return $this->result->getResult();
        }

        // Admin: trả cả item inactive
        $this->result->set('item', $entity->toArray());
        $this->result->setSuccess();
        return $this->result->getResult();
    }
}
```

**Bước 3 — Public API:** Copy boilerplate, đổi 5 điểm cố định, thêm logic riêng (nếu có).

```php
<?php
namespace NukeViet\Module\{mymod}\uapi;                     // ① namespace

use NukeViet\Uapi\Uapi;                                    // ② Uapi thay Api
use NukeViet\Uapi\UapiResult;                               // ③ UapiResult
use NukeViet\Uapi\UiApi;                                    // ④ UiApi
use NukeViet\Module\{mymod}\Shared\{Item}Repository;
use NukeViet\Module\{mymod}\Shared\{Item}Service;

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
        $repo = new {Item}Repository($db, NV_PREFIXLANG . '_' . $module_info['module_data'], $nv_Cache, Uapi::getModuleName());
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

> 📎 `src/modules/content/Api/CatGetDetail.php` · `src/modules/content/uapi/CatGetDetail.php`
> 📎 `src/modules/content/Api/CatGetList.php` · `src/modules/content/uapi/ContentGetList.php`

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

## Tham Khảo Nhanh — File trong `src/modules/content/`

| Cần gì | File | Dòng |
|--------|------|------|
| Entity đơn giản | `Shared/CatEntity.php` | 80 dòng |
| Entity + Relationship | `Shared/ContentEntity.php` | 94 dòng |
| Repository đầy đủ | `Shared/ContentRepository.php` | 368 dòng |
| Validator | `Shared/ContentValidator.php` | 51 dòng |
| Exception gom nhiều lỗi | `Shared/ValidationException.php` | — |
| Service đầy đủ (config, duplicate, viewtype) | `Shared/ContentService.php` | 377 dòng |
| Service ngắn gọn | `Shared/CatService.php` | 209 dòng |
| Admin form phức tạp | `admin/content.php` | 218 dòng |
| Admin list + form cùng file | `admin/cat.php` | 133 dòng |
| Admin danh sách đơn giản | `admin/main.php` | 76 dòng |
| AJAX xóa | `admin/content-del.php` | 54 dòng |
| AJAX xóa có ràng buộc | `admin/cat-del.php` | 58 dòng |
| AJAX status | `admin/cat-change-status.php` | 46 dòng |
| Frontend controller | `funcs/main.php` | 259 dòng |
| Theme render | `theme.php` | 99 dòng |
| Admin API | `Api/CatGetList.php` | 64 dòng |
| Public API | `uapi/ContentGetList.php` | 64 dòng |
| SQL schema | `action_mysql.php` | 83 dòng |
| Hằng số dùng chung | `Shared/SchemaHelper.php` | 43 dòng |
