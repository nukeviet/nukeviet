# Hướng Dẫn Lập Trình MVC trên NukeViet 5

Dành cho developer đã biết chuẩn MVC, tài liệu này tập trung vào các **thay đổi quan trọng** và cách triển khai thực tế trên NukeViet 5 dựa trên kiến trúc **Entity + Service Layer + PSR-4**.

> [!TIP]
> Luôn tham khảo module mẫu: `src/modules/Content/` để xem code thực tế.


---

## 0. Thay đổi cách đặt tên module và file
Với PSR-4, Tên Class = Tên File, và Namespace = Cấu trúc Thư mục. Cả hai đều phải dùng PascalCase (viết hoa chữ cái đầu của mỗi từ, ví dụ: UserRegistration):
- Tên Module : Viết hoa chữ cái đầu của mỗi từ, ví dụ: Content (Không dùng gạch ngang, gạch dưới, dấu chấm)
- Tên file đặt các chức năng của Module vẫn đặt bình thường, ví dụ đường dẫn: src\modules\Content\funcs\view-report.php để tiện làm url friendly
- Các tiêu chuẩn này áp dụng cho module mới, module và các chức năng cũ để nguyên, do sửa sẽ ảnh hưởng đến các site đang chạy

---

## 1. Kiến Trúc Tổng Quan (The Shift)

Thay vì viết logic hỗn hợp trong các file `.php` lẻ, NukeViet 5 tách bạch **5 thành phần** chính trong kiến trúc:

| Thành phần | Vai trò & Thay đổi | Tài liệu gốc |
| :--- | :--- | :--- |
| **Entity** | Ánh xạ 1 dòng DB thành 1 đối tượng có kiểu dữ liệu (Typed). | [Bước 3](modules/Content.md#L248) |
| **Repository** | **Nơi duy nhất chứa SQL.** Kế thừa `BaseRepository` và dùng đối tượng `$tables` để truy xuất tên bảng. | [Bước 4](modules/Content.md#L340) |
| **Service** | Chứa Business Logic + **Thu thập & Chuẩn hóa dữ liệu từ Request**. Đây là nơi duy nhất chịu trách nhiệm **phát Hook** (`nv_apply_hook`) sau khi xử lý nghiệp vụ thành công. | [Bước 5](modules/Content.md#L725) |
| **Validator** | Kiểm tra tính hợp lệ của Input, ném Exception kèm Error Code. | [Bước 6](modules/Content.md#L992) |
| **Controller** | Chính là các file `.php` trong thư mục `admin/` hoặc `funcs/`, Điều phối luồng (Entry Point): Gọi Service để xử lý và trả về View/JSON. | [Bước 7](modules/Content.md#L1039) |

---

## 2. Quy Trình Phát Triển Một Chức Năng

Khi cần thêm một tính năng mới (ví dụ: Quản lý Sản phẩm), hãy làm theo thứ tự sau:

### Bước 1: Database & Entity
1. Định nghĩa bảng trong `action_mysql.php`.
2. Tạo `{Item}Entity.php`: Khai báo các thuộc tính (public properties) tương ứng các cột DB.
   - **Kế thừa**: Nên kế thừa từ `AbstractEntity` để dùng chung các phương thức hệ thống.
   - **Lưu ý**: Typed Properties BẮT BUỘC phải có giá trị mặc định (Ví dụ: `public string $title = '';`).
   - Khai báo `protected const RELATIONS` để liên kết nested Entity (VD: `['category' => CatEntity::class]`). `toArray()` lớp cha tự động expand — không cần override thủ công.
   - Dùng `fromArray()`, `getDbColumns()`, `getIntColumns()` (kế thừa từ lớp cha).
   - 📎 [Mẫu Entity chuẩn](modules/Content.md#L252)

### Bước 2: Truy vấn dữ liệu (Repository)
- Khởi tạo đối tượng `$tables = new Tables(NV_PREFIXLANG, $module_data)` tại `functions.php` hoặc `admin.functions.php`. Đối tượng này chứa toàn bộ tên các bảng thực tế (VD: `$tables->content` = `nv5_vi_content_content`, `$tables->cat` = `nv5_vi_content_cat`).
- Tạo `{Item}Repository.php` kế thừa từ `BaseRepository`.
- Viết các hàm `findById`, `save`, `delete`, `getList`.
- Quản lý cấu hình module tập trung tại `NV_CONFIG_GLOBALTABLE`. Sử dụng `$module_config[$module_name]` để lấy cấu hình và hàm `saveConfig` trong Repository để cập nhật vào DB.
- 📎 [Mẫu Repository Skeleton](modules/Content.md#L399)

### Bước 3: Nghiệp vụ (Service & Validator)
Mọi dữ liệu đi vào hệ thống phải trải qua quy trình 4 giai đoạn nghiêm ngặt để đảm bảo tính (DRY - Don't Repeat Yourself):

1. **Thu thập (`collectRequestData`)**: Service gọi `nv_Request->get_xxx` để chuyển từ dữ liệu HTTP sang Mảng thô. Đây là nơi duy nhất giữ logic lấy dữ liệu từ Request, giúp dùng chung chuyển tiếp cho cả Admin & API.
2. **Chuẩn hóa (`prepareSaveData`)**: Service xử lý logic định dạng dữ liệu (tự sinh alias, keywords, kiểm tra đường dẫn ảnh hợp lệ). Phải chạy hàm này **trước** khi Validate.
3. **Kiểm tra (`Validator`)**: Thực hiện kiểm tra tính hợp lệ (rỗng, trùng, định dạng).
    - **Quy tắc**: Ném `ValidationException` (hoặc `InvalidArgumentException`) kèm mảng lỗi. Mỗi lỗi có Key là ID field (VD: 1=title, 2=bodytext) để Controller báo lỗi chính xác trên UI.
    - 📎 [Mẫu Validator](modules/Content.md#L992)
4. **Lưu trữ (`save{Item}`)**: Service nhận dữ liệu đã "sạch":
    - Gán các trường hệ thống tự động: `admin_id`, `add_time`, `edit_time`, `weight`.
    - Gọi Repository thực thi SQL.
    - Xóa Cache module: `$repo->invalidateCache()`.
    - Phát Hook (`before_{item}_save`, `{item}_saved`).
- 📎 [Mẫu luồng Service chuẩn](modules/Content.md#L665) | [Mẫu hàm Save](modules/Content.md#L284)

### Bước 4: Điều phối luồng (Controller & Smarty)
- **Controller**: Chính là file `admin/{op}.php` (quản trị) hoặc `funcs/{op}.php` (frontend). Đây là nơi khởi tạo Service, gọi Validator và quyết định trả về dữ liệu gì.
- **View**: Sử dụng **NVSmarty** (Smarty 4+). Controller truyền dữ liệu sang View qua hàm `$tpl->assign()`.
- 📎 [Mẫu Admin Controller](modules/Content.md#L1046) | [Mẫu Frontend Controller](modules/Content.md#L1184)

---

## 3. Hệ Thống API (Admin & Public)

Điểm mới trong NukeViet 5 là sự tách biệt nhưng dùng chung Service:
- **Admin API** (`src/modules/{module}/Api/`): Dùng interface `IApi`.
- **Public API** (`src/modules/{module}/uapi/`): Dùng interface `UiApi`.

> [!IMPORTANT]
> Mọi logic xử lý dữ liệu của API **phải nằm trong Service**. File API chỉ là "vỏ bọc" gọi Service để tránh lặp code (DRY).

📎 [So sánh 5 điểm khác biệt giữa Admin & Public API](modules/Content.md#L1407)

---

## 4. Giao Tiếp Giữa Các Module (Cross-module)

Mỗi module có bộ `Shared/Tables.php` riêng. Khi module **A** cần dữ liệu của module **B**, có 3 cơ chế tùy mục đích:

| Kịch bản | Cơ chế | Khi nào dùng |
| :--- | :--- | :--- |
| Đọc dữ liệu realtime | Dùng thẳng `Repository` của module B | Cần join/query trực tiếp |
| Phản ứng khi B thay đổi | **Hook** (`nv_add_hook`) | Event-driven, loose coupling |
| Hiển thị trong Block/Widget | Gọi **Public API** của module B | Không muốn phụ thuộc class |

### Kịch bản 1 — Đọc dữ liệu qua Repository

Module News đọc bài của Content bằng cách khởi tạo `ContentTables` + `ContentRepository`:

```php
// src/modules/News/News/NewsService.php
use NukeViet\Module\Content\Shared\Tables as ContentTables;
use NukeViet\Module\Content\Content\ContentRepository;

$contentTables = new ContentTables(NV_PREFIXLANG, 'content');
// → $contentTables->content = 'nv5_vi_content_content'

$contentRepo = new ContentRepository($this->db, $contentTables, $this->cache, 'content');
$entity = $contentRepo->findById($contentId);
```

> [!TIP]
> Mỗi module có `Tables` và `Repository` của riêng mình. Cross-module chỉ là **khởi tạo thêm** đối tượng của module kia — không hardcode tên bảng, không truy cập `$module_config` của module khác.

### Kịch bản 2 — Phản ứng sự kiện qua Hook

News lắng nghe event của Content — hai module **không biết nhau**, không import class của nhau:

```php
// src/modules/News/hooks/content.php

// Khi bài Content được lưu → News cập nhật index của mình
nv_add_hook('content', 'content_saved', 10, function($args, $from, $receive) {
    // $args['id'], $args['title'], $args['action'] ('add'|'edit')
    return null;
}, 'news', $pid);

// Khi bài Content bị xóa → News dọn liên kết
nv_add_hook('content', 'content_deleted', 10, function($args, $from, $receive) {
    // $args['id']
    return null;
}, 'news', $pid);
```

### Kịch bản 3 — Hiển thị qua Public API

```php
// Gọi API của Content từ News Block
$response = nv_api_call('content', 'ContentGetList', ['per_page' => 5]);
// $response['items'] → danh sách bài Content
```

> [!CAUTION]
> **Tuyệt đối không làm:**
> ```php
> // ❌ Hardcode tên bảng của module khác
> $db->query("SELECT * FROM nv5_vi_content_content WHERE id = 1");
>
> // ❌ Đọc thẳng config của module khác
> $table = $module_config['content']['table_row'];
> ```

---

## 5. Kiểm Thử (Testing)

Mỗi chức năng mới bắt buộc phải có testcase đi kèm trong thư mục `tests/modules/{module}/`:

| Loại Test | Mục tiêu | Công cụ |
| :--- | :--- | :--- |
| **Unit Test** | Kiểm tra logic của Validator và Service. Sử dụng Mock Repository để không chạm vào DB thật. | PHPUnit |
| **Acceptance Test** | Kiểm tra giao diện (Click, nhập liệu, lưu thành công trên trình duyệt). | Codeception + Selenium |
| **API Test** | Kiểm tra các endpoint trả về đúng cấu trúc JSON và dữ liệu. | Codeception API |

📎 [Kịch bản Unit Test bắt buộc](modules/Content.md#L1473)

---

## 6. Quy Tắc "Vàng" & Kinh Nghiệm Thực Tế

1. **Cấm Hardcode**: Tuyệt đối không viết trực tiếp tên bảng vào SQL. Phải dùng đối tượng `$tables` đã được khởi tạo sẵn (VD: `$this->tables->content`).
2. **CSRF Protection**: Mọi thao tác Ghi (Add/Edit/Del) phải qua `csrf_check()`. Với Ajax xóa, dùng query string `checkss`.
3. **Invalidate Cache**: Luôn gọi `$repo->invalidateCache()` trong Service sau khi thay đổi dữ liệu (CUD). Repository không tự xóa cache để đảm bảo tính linh hoạt (ví dụ khi cần bulk update).
4. **Thin Controller**: Nếu file controller của bạn > 200 dòng, hãy chuyển logic vào Service. Controller chỉ nên chứa code điều phối và render.
5. **Tiêu chuẩn PSR-4**: Tên file và Class phải trùng khớp tuyệt đối (PascalCase). Sai hoa/thường sẽ gây lỗi trên hệ điều hành Linux.
6. **Nguyên tắc "O-R-S" (One Repository - One Service)**: Mỗi bảng dữ liệu chính nên có một cặp Repo/Service riêng để dễ bảo trì và phân tách trách nhiệm.
7. **Khởi tạo tập trung Table Name**: Khởi tạo `$tables = new Tables(...)` DUY NHẤT một lần tại functions của module. Toàn bộ Repository nạp chung đối tượng này để đảm bảo tính nhất quán.
8. **Kế thừa BaseRepository**: Luôn kế thừa `BaseRepository` để sử dụng các helper có sẵn như `fetchEntities()`, `pdoType()` (xác định PDO bind type tự động từ Entity) và `invalidateCache()`.
9. **Sử dụng AbstractEntity**: Luôn kế thừa `AbstractEntity` cho các Entity để tận dụng các method tự động hóa (`getDbColumns`, `getIntColumns`, `fromArray`).
10. **Log hành động**: Đừng quên `nv_insert_logs()` hoặc `nv_apply_hook` cho các hành động thay đổi dữ liệu để phục vụ việc audit sau này.
11. **toArray() cho View**: Controller chỉ đẩy mảng thuần (`toArray()`) sang Smarty để đảm bảo hiệu năng và tính đóng gói.
12. **Bắt Throwable & ghi Log**: Mọi khối xử lý quan trọng trong Controller nên dùng try-catch. Bắt `ValidationException` để hiển thị lỗi UI, và bắt `\Throwable` cho các lỗi hệ thống nghiêm trọng.
13. **Bulk Update cho Repository**: Sử dụng cú pháp `CASE WHEN` (Bulk Update) cho các hàm như `reorderWeight` hoặc `autoCorrectWeight` thay vì lặp từng câu lệnh UPDATE để tối ưu hiệu suất Database.
14. **BaseApi & BaseUapi**: Sử dụng các lớp API cha để tập trung logic `bootstrap()` (DB, Cache, Tables, Config), giúp code API Clean và tập trung vào nghiệp vụ.

---

## 7. Lợi ích so với cách viết cũ (NukeViet 4)

Việc áp dụng chuẩn MVC + Service Layer thay vì viết tất cả logic vào một file PHP (kiểu NV4) mang lại những lợi ích chìa khóa:

1. **Tái sử dụng code (DRY)**: Logic thu thập và lưu trữ dữ liệu nằm trong Service. Bạn có thể dùng chung 100% logic này cho cả **Giao diện quản trị, API Admin và Public API** mà không cần viết lại.
2. **Dễ dàng bảo trì**: Khi cần sửa công thức tính toán hoặc thay đổi cấu trúc bảng, bạn chỉ cần sửa tại một nơi duy nhất (Service hoặc Repository) thay vì phải tìm và sửa ở hàng chục file `.php` lẻ.
3. **Kiểm thử tự động (Testing)**: Bạn có thể viết Unit Test cho Service/Validator để đảm bảo logic luôn đúng sau mỗi lần cập nhật. Cách viết cũ phụ thuộc vào Request và Database nên cực kỳ khó test tự động.
4. **Bảo mật & Tin cậy**: Việc dùng Entity với **Typed Properties** ngăn chặn các lỗi dữ liệu không mong muốn (vd: truyền chuỗi vào trường số). Các lỗi bảo mật như SQL Injection cũng được triệt tiêu hoàn toàn nhờ Repository tập trung dùng PDO.
5. **Dễ phối hợp nhóm**: Developer Backend có thể tập trung viết Service/Repo, khi Developer Frontend chỉ cần quan tâm tới việc gọi hàm và render dữ liệu trong file `.tpl`.

---

## 8. Nhược điểm & Cách khắc phục

| Nhược điểm | Cách khắc phục |
| :--- | :--- |
| **Độ phức tạp ban đầu**: Phải tạo nhiều file (Entity, Repo, Service...) dù chỉ là tính năng nhỏ. | Sử dụng các công cụ Generator + AI làm khung để copy-paste nhanh các thành phần cơ bản. |
| **Đường cong học tập (Learning Curve)**: Developer mới sẽ thấy khó hiểu khi luồng dữ liệu đi qua quá nhiều lớp. | Tập trung vào quy trình 4 giai đoạn (Bước 3). Một khi đã hiểu luồng Service, việc viết code sẽ trở nên rất máy móc và nhanh chóng. |
| **Thời gian triển khai lâu hơn**: Viết theo kiểu NV4 "mỳ ăn liền" thường nhanh hơn ở giai đoạn đầu. | Chấp nhận "chậm ở đầu nhưng nhanh ở cuối". Việc bảo trì và nâng cấp sau này sẽ tiết kiệm hàng tuần làm việc so với code cũ. |
| **Quản lý tệp tin**: Một module lớn có thể có hàng chục lớp Repo/Service. | Tuân thủ tuyệt đối quy tắc đặt tên (`{Item}Service.php`, `{Item}Repository.php`) và sắp xếp trong thư mục riêng của đối tượng (VD: `Cat/`, `Content/`) để dễ quản lý. |
