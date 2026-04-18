# Kiến trúc MVC — NukeViet 5

> Tài liệu dành cho developer, bao gồm:
> - **Phần I–V**: Hướng dẫn lập trình MVC (kiến trúc, quy trình, quy tắc)
> - **Phần VI**: Đánh giá hiện trạng & kế hoạch cải tiến
>
> Module mẫu: `src/modules/Content/`

---

# PHẦN I — KIẾN TRÚC TỔNG QUAN

## 1. Thay đổi cách đặt tên module và file

Với PSR-4, Tên Class = Tên File, và Namespace = Cấu trúc Thư mục. Cả hai đều phải dùng PascalCase:

- **Tên Module**: Viết hoa chữ cái đầu của mỗi từ, VD: `Content` (Không dùng gạch ngang, gạch dưới, dấu chấm)
- **Tên file chức năng**: Đặt bình thường, VD: `src/modules/Content/funcs/view-report.php` để tiện làm URL friendly
- Các tiêu chuẩn này áp dụng cho module mới. Module và chức năng cũ giữ nguyên.

## 2. Năm thành phần chính

| Thành phần | Vai trò | Quy tắc |
|---|---|---|
| **Entity** | 1 bản ghi DB = 1 object. `toArray()` + `fromArray()` | Dùng `FETCH_ASSOC` + `fromArray()`, KHÔNG dùng `FETCH_CLASS` |
| **Repository** | Toàn bộ SQL nằm ở đây | Controller/Service KHÔNG viết SQL |
| **Validator** | Kiểm tra input, ném Exception kèm error code | Tách riêng khỏi Service |
| **Service** | Business logic + cache + hook + thu thập dữ liệu từ Request | KHÔNG có SQL. DRY cho Admin & API |
| **Controller** | Nhận Request → Validator → Service → View | Thin Controller (< 200 dòng) |

```
Controller (Thin) ──▶ Service::prepareSaveData() ──▶ Validator ──▶ Service::save() ──▶ Repository ──▶ DB
      │                                                                                  │
      ▼                                                                                  ▼
  View (NVSmarty)                                                                  Entity (Typed)
```

## 3. Cấu trúc thư mục

```text
src/modules/{MyModule}/
├── version.php              # Metadata
├── functions.php            # define NV_IS_MOD_{MYMOD}, load config, khởi tạo Tables
├── admin.functions.php      # $allow_func, define NV_IS_FILE_ADMIN
├── admin.menu.php           # $submenu
├── action_mysql.php         # SQL tạo/xóa bảng
├── theme.php                # Render frontend (NVSmarty)
├── funcs/
│   └── main.php             # Frontend controller
├── admin/
│   ├── main.php             # Danh sách
│   ├── {item}.php           # Form thêm/sửa
│   ├── {item}-del.php       # AJAX xóa
│   ├── {item}-change-status.php
│   └── {item}-change-weight.php
├── {Item}/                  # PSR-4: NukeViet\Module\{MyModule}\{Item}\
│   ├── {Item}Entity.php
│   ├── {Item}Repository.php
│   ├── {Item}Validator.php
│   └── {Item}Service.php
├── Shared/                  # Các Class dùng chung của module
│   ├── Tables.php           # Tên bảng DUY NHẤT 1 chỗ
│   ├── AbstractEntity.php
│   ├── BaseRepository.php
│   ├── BaseApi.php
│   ├── BaseUapi.php
│   ├── ValidationException.php
│   └── SchemaHelper.php
├── Api/                     # Admin API (implements IApi)
│   └── {Item}GetList.php
├── Uapi/                    # Public API (implements UiApi)
│   └── {Item}GetList.php
└── language/
    └── vi.php
```

> Nếu module có nhiều đối tượng (VD: `Cat` + `Content`), mỗi đối tượng cần bộ 4: Entity, Repository, Validator, Service.

---

# PHẦN II — QUY TRÌNH PHÁT TRIỂN

## Bước 1: Database & Entity

1. Định nghĩa bảng trong `action_mysql.php`.
2. Tạo `{Item}Entity.php` kế thừa `AbstractEntity`:
   - Khai báo `VIEW_FIELDS`: Properties chỉ dùng hiển thị, KHÔNG có trong DB (vd: `link`, `url_edit`)
   - Khai báo `PRIMARY_KEY`: Tên cột khóa chính
   - Khai báo `RELATIONS`: Quan hệ với entity khác (property → EntityClass)
   - **Typed Properties BẮT BUỘC** có giá trị mặc định (VD: `public string $title = '';`)
   - Dùng `toArray()` (tự động từ AbstractEntity) để đẩy sang View
   - Dùng `fromArray()`, `getDbColumns()`, `getIntColumns()` (kế thừa từ lớp cha)

```php
class CatEntity extends AbstractEntity implements HasAlias, HasWeight, HasStatus
{
    protected const VIEW_FIELDS = ['link', 'url_edit', 'checkss', 'url_copy'];
    protected const PRIMARY_KEY = 'catid';
    protected const RELATIONS = [];  // Không có quan hệ

    // DB columns
    public int $catid = 0;
    public string $title = '';
    public string $alias = '';
    public string $description = '';
    public string $image = '';
    public int $weight = 0;
    public string $keywords = '';
    public int $add_time = 0;
    public int $edit_time = 0;
    public int $status = 1;

    // View-only (trong VIEW_FIELDS)
    public string $link = '';
    public string $url_edit = '';
    public string $url_copy = '';
    public string $checkss = '';
}
```

## Bước 2: Truy vấn dữ liệu (Repository)

- Khởi tạo `$tables = new Tables(NV_PREFIXLANG, $module_data)` tại `functions.php` / `admin.functions.php`.
- Tạo `{Item}Repository.php` kế thừa `BaseRepository`.
- Khai báo 3 abstract method: `entityClass()`, `tableName()`, `primaryKey()`.
- `use` các Trait phù hợp với interface mà Entity implement.

```php
class CatRepository extends BaseRepository
{
    use AliasRepositoryTrait;    // CatEntity implements HasAlias
    use WeightRepositoryTrait;   // CatEntity implements HasWeight
    use StatusRepositoryTrait;   // CatEntity implements HasStatus

    protected function entityClass(): string { return CatEntity::class; }
    protected function tableName(): string { return $this->tables->cat; }
    protected function primaryKey(): string { return 'catid'; }

    // Core CRUD (findById, save, delete, getList, count) → kế thừa BaseRepository
    // Alias (findByAlias, isAliasExists) → từ AliasRepositoryTrait
    // Weight (getMaxWeight, reorderWeight) → từ WeightRepositoryTrait
    // Status (toggleStatus) → từ StatusRepositoryTrait

    // Chỉ thêm method đặc thù:
    public function getAll(): array { return $this->getList([], 'weight ASC'); }
    public function getAllActive(): array { return $this->getList(['status' => 1], 'weight ASC'); }
}
```

## Bước 3: Nghiệp vụ (Service & Validator)

Mọi dữ liệu đi vào hệ thống phải trải qua **4 giai đoạn**:

1. **Thu thập (`collectRequestData`)**: Service gọi `$nv_Request->get_xxx` để chuyển từ HTTP sang mảng thô. Đây là nơi duy nhất giữ logic lấy dữ liệu từ Request, dùng chung cho cả Admin & API.
2. **Chuẩn hóa (`prepareSaveData`)**: Service xử lý logic định dạng (tự sinh alias, keywords, kiểm tra ảnh). Chạy **trước** Validate.
3. **Kiểm tra (`Validator`)**: Ném `ValidationException` kèm mảng lỗi. Mỗi lỗi có Key là ID field để Controller báo lỗi chính xác trên UI.
4. **Lưu trữ (`saveEntity`)**: Service gán trường hệ thống (`add_time`, `edit_time`, `weight`), gọi Repository, xóa Cache, phát Hook.

```php
class CatService extends BaseCrudService
{
    protected function entityName(): string { return 'cat'; }

    // saveEntity, deleteEntity, changeStatus, changeWeight, getDetail → kế thừa BaseCrudService

    public function collectRequestData($nv_Request, array $defaultData = []): array
    {
        return [
            'title' => $nv_Request->get_title('title', 'post', ''),
            'alias' => $nv_Request->get_title('alias', 'post', ''),
            'description' => $nv_Request->get_string('description', 'post', ''),
            'keywords' => $nv_Request->get_title('keywords', 'post', ''),
            'image' => $nv_Request->get_string('image', 'post', ''),
            'status' => $nv_Request->get_absint('status', 'post', 1),
        ];
    }

    public function prepareSaveData(array $data, array $moduleConfig = [], string $moduleUpload = ''): array
    {
        if (empty($data['alias'])) {
            $data['alias'] = change_alias($data['title']);
        }
        if (!empty($moduleConfig['alias_lower'])) {
            $data['alias'] = strtolower($data['alias']);
        }
        if (empty($data['keywords'])) {
            $data['keywords'] = nv_get_keywords($data['title']);
        }
        if (!empty($data['image']) && !nv_is_file($data['image'], NV_UPLOADS_DIR . '/' . $moduleUpload)) {
            $data['image'] = '';
        }
        return $data;
    }
}
```

Validator:

```php
class CatValidator extends BaseValidator
{
    public function __construct(private CatRepository $repo) {}

    public function validateSave(array $data, int $excludeId = 0): void
    {
        $this->requireNotEmpty($data, 'title', 1, 'cat_empty_title');
        $this->requireUniqueAlias($this->repo, $data['alias'] ?? '', $excludeId, 2, 'erroralias');
        $this->throwIfErrors();
    }
}
```

## Bước 4: Controller & View

- **Controller**: File `admin/{op}.php` hoặc `funcs/{op}.php`. Khởi tạo Service → gọi Validator → quyết định trả về View/JSON.
- **View**: Sử dụng **NVSmarty** (Smarty 4+). Controller truyền dữ liệu qua `$tpl->assign()`.
- Controller chỉ nhận mảng từ `toArray()` — **cấm viết logic/SQL trong `.tpl`**.

```php
// admin/cat.php
$catRepo = new CatRepository($db, $tables, $nv_Cache, $module_name);
$catService = new CatService($catRepo);

if ($nv_Request->isset_request('submit', 'post')) {
    $data = $catService->collectRequestData($nv_Request);
    $data = $catService->prepareSaveData($data, $config, $module_upload);

    try {
        $validator = new CatValidator($catRepo);
        $validator->validateSave($data, $saveId);
        $savedId = $catService->saveEntity($data, $saveId, $module_name);
        nv_insert_logs(/* ... */);
        // redirect hoặc JSON success
    } catch (ValidationException $e) {
        // Trả về mảng lỗi cho UI
        foreach ($e->getErrors() as $code => $langKey) {
            $respon['errors'][] = ['field' => $code, 'msg' => $nv_Lang->getModule($langKey)];
        }
        nv_jsonOutput($respon);
    }
}
```

---

# PHẦN III — HỆ THỐNG API

## Admin API vs Public API

| | Admin API | Public API |
|---|---|---|
| Thư mục | `Api/` | `Uapi/` |
| Interface | `IApi` | `UiApi` |
| Base class | `BaseApi` | `BaseUapi` |
| Xác thực | Yêu cầu admin login | Tùy cấu hình |

> **Quan trọng**: Mọi logic xử lý dữ liệu **phải nằm trong Service**. File API chỉ là "vỏ bọc" gọi Service để tránh lặp code (DRY).

```php
// Api/CatGetList.php — kế thừa ApiGetList, ~15 dòng
class CatGetList extends ApiGetList
{
    public static function getAdminLev() { return Api::ADMIN_LEV_MOD; }
    public static function getCat() { return 'content'; }

    protected function createRepository(): BaseRepository
    {
        return new CatRepository($this->db, $this->tables, $this->cache, $this->module_name);
    }
    protected function createService(): BaseCrudService
    {
        return new CatService($this->createRepository());
    }
    protected function createValidator(): object
    {
        return new CatValidator($this->createRepository());
    }
}
```

---

# PHẦN IV — KIỂM THỬ (TESTING)

Mỗi chức năng mới bắt buộc có testcase trong `tests/modules/{module}/`:

| Loại Test | Mục tiêu | Công cụ |
|---|---|---|
| **Unit Test** | Logic của Validator và Service. Mock Repository. | PHPUnit |
| **Acceptance Test** | Giao diện (click, nhập liệu, lưu trên trình duyệt). | Codeception + Selenium |
| **API Test** | Endpoint trả về đúng JSON và dữ liệu. | Codeception API |

---

# PHẦN V — QUY TẮC BẮT BUỘC

## 14 quy tắc "vàng"

1. **Cấm Hardcode tên bảng**: Dùng đối tượng `$tables` (VD: `$this->tables->content`).
2. **CSRF Protection**: Mọi thao tác Ghi phải qua `csrf_check()`. Ajax xóa dùng query string `checkss`.
3. **Invalidate Cache**: Luôn gọi `$repo->invalidateCache()` trong Service sau khi thay đổi dữ liệu.
4. **Thin Controller**: File controller > 200 dòng → chuyển logic vào Service.
5. **PSR-4**: Tên file và Class trùng khớp tuyệt đối (PascalCase). Sai hoa/thường lỗi trên Linux.
6. **O-R-S**: Mỗi bảng dữ liệu chính có một cặp Repo/Service riêng.
7. **Khởi tạo Tables tập trung**: `$tables = new Tables(...)` DUY NHẤT 1 lần tại functions.
8. **Kế thừa BaseRepository**: Dùng helper `fetchEntities()`, `pdoType()`, `invalidateCache()`.
9. **Kế thừa AbstractEntity**: Tận dụng `getDbColumns`, `getIntColumns`, `fromArray`, `toArray`.
10. **Log hành động**: `nv_insert_logs()` hoặc `nv_apply_hook` cho mọi thay đổi dữ liệu.
11. **toArray() cho View**: Controller chỉ đẩy mảng thuần sang Smarty.
12. **Bắt Throwable & ghi Log**: `ValidationException` → lỗi UI, `\Throwable` → lỗi hệ thống.
13. **Bulk Update**: Dùng `CASE WHEN` cho `reorderWeight` thay vì lặp từng UPDATE.
14. **BaseApi & BaseUapi**: Tập trung logic `bootstrap()`, code API clean.

## 8 quy tắc bảo mật (KHÔNG được vi phạm)

1. **Input**: PHẢI qua `$nv_Request`. KHÔNG dùng `$_GET`/`$_POST`/`$_REQUEST`.
2. **SQL**: Chuỗi user → `prepare()` + `bindParam()`. Số nguyên → cast `(int)`.
3. **Output HTML**: Raw data → `nv_htmlspecialchars()`. Data từ `get_title()` đã escape — KHÔNG escape lại.
4. **CSRF**: `csrf_create($csrf_key)` / `csrf_check($csrf, $csrf_key)`.
5. **File**: Dùng `nv_is_file()`, KHÔNG `is_file()` với path từ user.
6. **Admin**: Luôn kiểm tra `defined('NV_IS_ADMIN')` trước khi ghi dữ liệu.
7. **Ngôn ngữ**: Dùng `$nv_Lang->getModule('key')` / `$nv_Lang->getGlobal('key')`. KHÔNG dùng mảng `$lang_module` (cách cũ NV4).
8. **JS**: File riêng `themes/[theme]/js/[module].js` — cấm `<script>` trong `.tpl`.

## Lợi ích so với cách viết cũ (NukeViet 4)

1. **Tái sử dụng code (DRY)**: Logic trong Service dùng chung cho Admin, API Admin và Public API.
2. **Dễ bảo trì**: Sửa 1 nơi (Service/Repository) thay vì hàng chục file.
3. **Kiểm thử tự động**: Unit Test cho Service/Validator. Cách cũ phụ thuộc Request/DB nên cực khó test.
4. **Bảo mật**: Typed Properties ngăn lỗi dữ liệu. SQL Injection triệt tiêu nhờ PDO tập trung.
5. **Phối hợp nhóm**: Backend viết Service/Repo, Frontend chỉ gọi hàm và render `.tpl`.

---

# PHẦN VI — KẾ HOẠCH CẢI TIẾN

> **Trạng thái**: Module Content là module mới, **chưa có ai sử dụng** → mọi thay đổi không ảnh hưởng site nào. Đây là thời điểm tốt nhất để chuẩn hóa triệt để.

## 1. Đánh giá hiện trạng

Module Content mẫu có 2 entity (`Cat`, `Content`) với cấu trúc tương đồng — cả hai đều có `alias`, `weight`, `status`. Tuy nhiên, module **Tin tức (News)** — module phức tạp nhất NukeViet — có 18 bảng với cấu trúc rất đa dạng:

| Bảng | alias | weight | status | Vai trò |
|---|:---:|:---:|:---:|---|
| `cat` | ✓ | ✓ | ✓ | Danh mục |
| `rows` | ✓ | ✓ | ✓ | Bài viết chính |
| `topics` | ✓ | ✓ | ✗ | Chủ đề |
| `block_cat` | ✓ | ✓ | ✗ | Danh mục block |
| `sources` | ✗ | ✓ | ✗ | Nguồn tin |
| `voices` | ✗ | ✓ | ✓ | Giọng đọc |
| `tags` | ✓ | ✗ | ✗ | Nhãn/Tag |
| `author` | ✓ | ✗ | ✗ | Tác giả |
| `logs` | ✗ | ✗ | ✓ | Nhật ký |
| `detail` | ✗ | ✗ | ✗ | Nội dung chi tiết (bảng phụ) |
| `config_post` | ✗ | ✗ | ✗ | Quyền đăng bài |
| `admins` | ✗ | ✗ | ✗ | Quyền quản trị |
| `tags_id` | ✗ | ✗ | ✗ | Quan hệ N-N (bài ↔ tag) |
| `block` | ✗ | ✓ | ✗ | Quan hệ (block ↔ bài) |
| `tmp` | ✗ | ✗ | ✗ | Bản nháp |
| `row_histories` | ✗ | ✗ | ✗ | Lịch sử chỉnh sửa |
| `report` | ✗ | ✗ | ✗ | Báo lỗi nội dung |
| `authorlist` | ✗ | ✗ | ✗ | Quan hệ N-N (bài ↔ tác giả) |

**Nhận xét**: Chỉ **2/18 bảng** có đủ `alias + weight + status`. Kiến trúc base class phải xử lý được sự đa dạng này.

## 2. Tổng hợp vấn đề → Giải pháp

| # | Vấn đề | Giải pháp | Phase |
|---|---|---|---|
| 1 | Entity cấu trúc khác nhau, base class "one size fits all" | **3 Interface** (HasAlias, HasWeight, HasStatus) + **3 Trait** tương ứng | 1 + 2 |
| 2 | CRUD lặp ~70% giữa Repository | **BaseRepository** core 5 method + Trait opt-in | 2 |
| 3 | Service CRUD lặp | **BaseCrudService** tự detect interface | 3 |
| 4 | Tên bảng chính không theo convention | **Đổi tên bảng** + **BaseTables** magic `__get()` | 0 + 4 |
| 5 | `toArray()` override thủ công cho relationship | **RELATIONS** constant + auto `toArray()` trong AbstractEntity | 1 |
| 6 | Pagination & Filtering lặp code | **`getList(where, orderBy, page, perPage)`** trong BaseRepository | 2 |
| 7 | Validator chỉ 1 entity, không cross-validate | **BaseValidator** helper methods + inject nhiều Repository | 6 |
| 8 | Hook naming thủ công, dễ trùng | **Convention tự động** `{entityName}_{action}` trong BaseCrudService | 3 |
| 9 | API boilerplate ~60 dòng/action | **ApiGetList**, **ApiAdd** generic, con chỉ ~15 dòng | 5 |

## 3. Thứ tự triển khai

| Phase | Nội dung | File thay đổi | File mới |
|---|---|---|---|
| **0** | Đổi tên bảng `content` → `content_content` | `action_mysql.php`, `Tables.php` | — |
| **1** | Interface đánh dấu + RELATIONS + auto toArray | `AbstractEntity.php`, `CatEntity.php`, `ContentEntity.php` | `Contracts/HasAlias.php`, `HasWeight.php`, `HasStatus.php` |
| **2** | BaseRepository core CRUD + 3 Trait | `BaseRepository.php`, `CatRepository.php`, `ContentRepository.php` | `Traits/AliasRepositoryTrait.php`, `WeightRepositoryTrait.php`, `StatusRepositoryTrait.php` |
| **3** | BaseCrudService (tự detect interface) | `CatService.php`, `ContentService.php` | `BaseCrudService.php` |
| **4** | BaseTables convention | `Tables.php` | `BaseTables.php` |
| **5** | BaseCrudApi + generic API actions | Các file `Api/*.php`, `Uapi/*.php` | `BaseCrudApi.php`, `ApiGetList.php`, `ApiAdd.php` |
| **6** | BaseValidator + helper methods | `CatValidator.php`, `ContentValidator.php` | `BaseValidator.php` |

## 4. Chi tiết: Phase 0 — Chuẩn hóa tên bảng

Bảng chính `nv5_vi_content` thiếu suffix `_content`, trong khi bảng phụ `nv5_vi_content_cat` có suffix `_cat`. Module News tuân thủ đúng: `nv5_vi_news_rows`, `nv5_vi_news_cat`...

**Đổi ngay** `nv5_vi_content` → `nv5_vi_content_content`:
- Module Content chưa ai dùng → không ảnh hưởng.
- Sửa 2 file: `action_mysql.php` và `Tables.php`.
- Code dùng `$tables->content` giữ nguyên (property name không đổi, chỉ giá trị đổi).

```php
readonly class Tables
{
    public string $content;
    public string $cat;

    public function __construct(string $tablePrefix, string $moduleData)
    {
        $prefix = $tablePrefix . '_' . $moduleData;
        $this->content = $prefix . '_content';  // nv5_vi_content_content
        $this->cat     = $prefix . '_cat';       // nv5_vi_content_cat
    }
}
```

## 5. Chi tiết: Phase 1 — Interface + AbstractEntity

### 5A. Ba Interface đánh dấu

```php
namespace NukeViet\Module\Content\Shared\Contracts;

interface HasAlias {}   // Entity có cột alias → Repo có findByAlias, isAliasExists
interface HasWeight {}  // Entity có cột weight → Repo có getMaxWeight, reorderWeight
interface HasStatus {}  // Entity có cột status → Repo có toggleStatus
```

### 5B. AbstractEntity nâng cấp

```php
abstract class AbstractEntity
{
    protected const VIEW_FIELDS = [];
    protected const PRIMARY_KEY = '';
    protected const RELATIONS = [];  // MỚI

    // Giữ nguyên: getDbColumns(), getIntColumns(), fromArray()

    // MỚI: Auto convert nested entity
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        foreach (static::RELATIONS as $prop => $entityClass) {
            if (isset($arr[$prop])) {
                $value = $arr[$prop];
                if ($value instanceof self) {
                    $arr[$prop] = $value->toArray();
                } elseif (is_array($value)) {
                    $arr[$prop] = array_map(
                        fn($item) => $item instanceof self ? $item->toArray() : $item,
                        $value
                    );
                }
            }
        }
        return $arr;
    }

    // MỚI: Kiểm tra interface
    public static function supports(string $interface): bool
    {
        return is_subclass_of(static::class, $interface)
            || in_array($interface, class_implements(static::class) ?: []);
    }
}
```

## 6. Chi tiết: Phase 2 — BaseRepository + 3 Trait

### 6A. BaseRepository — 5 method core

```php
abstract class BaseRepository
{
    protected PDO $db;
    protected $tables;
    protected $cache;
    protected string $module_name;

    abstract protected function entityClass(): string;
    abstract protected function tableName(): string;   // MỚI
    abstract protected function primaryKey(): string;   // MỚI

    public function entitySupports(string $interface): bool { /* ... */ }

    public function findById(int $id): ?object { /* ... */ }
    public function save(array $data, int $id = 0): int { /* ... */ }
    public function delete(int $id): bool { /* ... */ }
    public function getList(array $where = [], string $orderBy = '', int $page = 1, int $perPage = 0): array { /* ... */ }
    public function count(array $where = []): int { /* ... */ }

    // Giữ nguyên: pdoType(), fetchEntities(), invalidateCache()
}
```

`getList()` tự detect: entity có `HasWeight` → mặc định `ORDER BY weight ASC`, không có → `ORDER BY {pk} DESC`.

### 6B. Ba Trait

| Trait | Dành cho | Cung cấp method |
|---|---|---|
| `AliasRepositoryTrait` | Entity implements `HasAlias` | `findByAlias()`, `isAliasExists()` |
| `WeightRepositoryTrait` | Entity implements `HasWeight` | `getMaxWeight()`, `reorderWeight()`, `autoCorrectWeight()` |
| `StatusRepositoryTrait` | Entity implements `HasStatus` | `toggleStatus()` |

### 6C. Quy tắc sử dụng

| Entity implements | Repository `use` | Nếu quên `use` |
|---|---|---|
| `HasAlias` | `AliasRepositoryTrait` | `findByAlias()` → method not found |
| `HasWeight` | `WeightRepositoryTrait` | `reorderWeight()` → method not found |
| `HasStatus` | `StatusRepositoryTrait` | `toggleStatus()` → method not found |
| *(không implement)* | *(không use)* | Chỉ core CRUD — đúng ý đồ |

IDE báo lỗi compile-time, không cần chờ runtime.

## 7. Chi tiết: Phase 3 — BaseCrudService

```php
abstract class BaseCrudService
{
    protected BaseRepository $repo;

    public function __construct(BaseRepository $repo) { $this->repo = $repo; }

    abstract protected function entityName(): string; // 'cat', 'content'

    public function getDetail(int $id): object { /* throw nếu không tìm thấy */ }

    public function saveEntity(array $data, int $id, string $module_name): int
    {
        // Auto timestamps + auto weight (nếu HasWeight) + hook + cache
    }

    public function deleteEntity(int $id, string $module_name): bool
    {
        // Delete + auto reorder (nếu HasWeight) + hook + cache
    }

    public function changeStatus(int $id, string $module_name): int
    {
        // Throw LogicException nếu entity không HasStatus
    }

    public function changeWeight(int $id, int $newWeight, string $module_name): bool
    {
        // Throw LogicException nếu entity không HasWeight
    }
}
```

Hook tự đặt tên theo convention: `before_{entityName}_save`, `{entityName}_saved`, `{entityName}_deleted`, `{entityName}_status_changed`.

## 8. Chi tiết: Phase 4 — BaseTables

```php
class BaseTables
{
    protected string $prefix;

    public function __construct(string $tablePrefix, string $moduleData)
    {
        $this->prefix = $tablePrefix . '_' . $moduleData;
    }

    public function __get(string $name): string
    {
        return $this->prefix . '_' . $name;
    }
}

// Module Content
class Tables extends BaseTables
{
    // $tables->content → 'nv5_vi_content_content'
    // $tables->cat     → 'nv5_vi_content_cat'
}

// Module News khi chuyển MVC — 18 bảng, KHÔNG khai báo dòng nào
class Tables extends BaseTables
{
    // $tables->rows    → 'nv5_vi_news_rows'
    // $tables->sources → 'nv5_vi_news_sources'
    // ... tự động!
}
```

## 9. Chi tiết: Phase 5 — Generic API

`BaseCrudApi` + `ApiGetList` + `ApiAdd` — API con chỉ khai báo factory method (~15 dòng thay vì ~60 dòng).

## 10. Chi tiết: Phase 6 — BaseValidator

```php
abstract class BaseValidator
{
    protected function addError(int $code, string $langKey): void { /* ... */ }
    protected function throwIfErrors(): void { /* ... */ }
    protected function requireNotEmpty(array $data, string $field, int $errorCode, string $langKey): void { /* ... */ }
    protected function requireUniqueAlias(BaseRepository $repo, string $alias, int $excludeId, int $errorCode, string $langKey): void { /* ... */ }
}
```

Cross-entity validation: Validator inject nhiều Repository.

## 11. Cấu trúc Shared/ sau khi hoàn thành

```
Shared/
├── Contracts/                    # MỚI
│   ├── HasAlias.php
│   ├── HasWeight.php
│   └── HasStatus.php
├── Traits/                       # MỚI
│   ├── AliasRepositoryTrait.php
│   ├── WeightRepositoryTrait.php
│   └── StatusRepositoryTrait.php
├── AbstractEntity.php            # Nâng cấp: RELATIONS, auto toArray, supports()
├── BaseRepository.php            # Nâng cấp: core CRUD (5 method)
├── BaseCrudService.php           # MỚI
├── BaseTables.php                # MỚI
├── BaseValidator.php             # MỚI
├── BaseCrudApi.php               # MỚI
├── ApiGetList.php                # MỚI
├── ApiAdd.php                    # MỚI
├── BaseApi.php                   # Giữ nguyên
├── BaseUapi.php                  # Giữ nguyên
├── Tables.php                    # Kế thừa BaseTables
├── ValidationException.php       # Giữ nguyên
└── SchemaHelper.php              # Giữ nguyên
```

## 12. So sánh code cho mỗi entity mới

| Thành phần | Trước cải tiến | Sau cải tiến |
|---|---|---|
| Entity | Viết đầy đủ | Viết đầy đủ (+ implement interface + RELATIONS) |
| Repository | ~200 dòng (CRUD lặp) | ~10 dòng (3 abstract + `use` trait) |
| Service | ~250 dòng (CRUD lặp) | ~80 dòng (collect + prepare + logic riêng) |
| Validator | ~40 dòng | ~15 dòng (dùng helper) |
| Admin API | ~60 dòng/action | ~15 dòng/action |
| Admin Controller | Viết đầy đủ | Viết đầy đủ (giữ flexibility) |
| Template | Viết đầy đủ | Viết đầy đủ (giữ flexibility) |

**Giảm ước tính: ~60% code boilerplate** cho mỗi entity mới.
