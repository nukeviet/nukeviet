---
name: lang-translator
description: "Dịch các dòng ngôn ngữ còn thiếu giữa các file ngôn ngữ của NukeViet 5.0"
argument-hint: <module_name> <source_lang> <target_lang>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

# Skill: NukeViet Language Translator

Dùng skill này để tự động tìm các khóa ngôn ngữ (keys) có trong file nguồn source_lang (ví dụ `vi.php`) nhưng thiếu trong file đích target_lang (ví dụ `en.php`), sau đó dịch chúng sang ngôn ngữ đích và cập nhật vào file.

## ⚠️ Quan trọng
- Skill này chỉ dành cho NukeViet 5.x.
- File ngôn ngữ phải nằm trong thư mục `language/` của module hoặc system.
- Cấu trúc file phải tuân thủ chuẩn NukeViet (`$lang_module['key'] = 'value'`).

## Quy trình thực hiện

### Bước 1: Xác định file
- Xác định thư mục chứa ngôn ngữ của module: `src/modules/{module_name}/language/`.
- File nguồn: `{source_lang}.php` (ví dụ `vi.php`).
- File đích: `{target_lang}.php` (ví dụ `en.php`).

### Bước 2: So sánh và Dịch
- Đọc nội dung cả hai file.
- Trích xuất toàn bộ các cặp khóa-giá trị từ cả hai file.
- Sử dụng khả năng của LLM để dịch các khóa thiếu từ tiếng nguồn sang tiếng đích.
- **Quan trọng:** Sắp xếp lại file đích để thứ tự các khóa (`$lang_module['key']`) hoàn toàn khớp với thứ tự trong file nguồn. Điều này giúp người dùng dễ dàng so sánh hai file side-by-side.

### Bước 3: Cập nhật file đích
- Ghi đè hoặc cập nhật file đích với danh sách khóa đã được sắp xếp theo đúng trật tự của file nguồn.
- Giữ nguyên định dạng thụt lề và dấu ngoặc đơn/kép của file gốc.
- Nếu file đích chưa tồn tại, hãy tạo mới dựa trên cấu trúc header của file nguồn.

## Các lệnh kích hoạt ví dụ
- `/lang-translator two-step-verification vi en`
- "Dịch các ngôn ngữ thiếu của module two-step-verification từ vi sang en"
