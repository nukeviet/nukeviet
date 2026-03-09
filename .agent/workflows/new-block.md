---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Block (Module Block hoặc Theme Block)
---

# Workflow: `/new-block`

Tính năng này giúp bạn tạo nhanh cụm tệp lệnh Block (PHP, JSON, TPL) dựa trên cấu trúc chuẩn của NukeViet 5. 

<!-- Cảnh báo cấu trúc: NukeViet 5 sử dụng thư mục `blocks/` ở module hoặc theme.
Kèm file `global.[name].php` và `global.[name].json`. -->

## 1. Yêu cầu nhập liệu
Hỏi người dùng các thông tin sau:
1. **Loại Block**:
   - `1` = Module Block (Block thuộc module, render bằng XTemplate lấy giao diện từ Theme).
   - `2` = Theme Block (Block thuộc theme, render bằng Smarty nằm trong `blocks/smarty/`).
2. **Tên Module / Tên Theme đích**: Tuỳ theo loại 1 hay 2 (Ví dụ: `news`, `default`).
3. **Tên Block**: (Chỉ đuôi tên, ví dụ `about`, `qr_code`). Tệp sinh ra sẽ có dạng `global.[tên_block].php`.

## 2. Tạo logic thư mục (Tự động chạy)
Sử dụng công cụ `run_command` để tạo cấu trúc tệp rỗng dựa trên loại Block:

// turbo
```bash
# Sửa biến dưới đây thành giá trị User nhập (1 hoặc 2)
BLOCK_TYPE="1" 
# Sửa biến chứa tên module hoặc tên theme
DESTINATION="news"
# Sửa tên Block
BLOCK_NAME="about"

if [ "$BLOCK_TYPE" = "1" ]; then
  # Dành cho Module Block
  mkdir -p "modules/$DESTINATION/blocks"
  mkdir -p "themes/default/modules/$DESTINATION"
  
  echo "{}" > "modules/$DESTINATION/blocks/global.$BLOCK_NAME.json"
  touch "modules/$DESTINATION/blocks/global.$BLOCK_NAME.php"
  touch "themes/default/modules/$DESTINATION/block.$BLOCK_NAME.tpl"
else
  # Dành cho Theme Block
  mkdir -p "themes/$DESTINATION/blocks/smarty"
  
  echo "{}" > "themes/$DESTINATION/blocks/global.$BLOCK_NAME.json"
  touch "themes/$DESTINATION/blocks/global.$BLOCK_NAME.php"
  touch "themes/$DESTINATION/blocks/smarty/global.$BLOCK_NAME.tpl"
fi
```

## 3. Tạo File Nội Dung Mã Nguồn
Ngay sau khi Script bash trên chạy xong, Agent sẽ load `.agent/skills/nukeviet-block/SKILL.md` để lấy mẫu code chuẩn (Boilerplate) cho:
- Tệp `.json` (điền i18n cơ bản cho file JSON rỗng vừa được bash echo).
- Tệp `.php` (Copy y chang hàm mẫu `nv_block_*` và đổi tên hàm sao cho khớp biến tên).
- Trọng điểm: Nạp mẫu **XTemplate** vào tệp `.tpl` nếu là Model Block, VÀ nạp mẫu **Smarty** vào tệp `.tpl` nếu là Theme Block.
Agent TỰ ĐỘNG dùng `write_to_file` để đắp da đắp thịt cho các tệp lệnh vừa `touch`.

## 4. Kết thúc
- Gửi thông báo đến người dùng quá trình hoàn tất.
- Lưu ý họ kích hoạt/cài đặt Block tại **Khu vực Admin > Giao diện > Quản lý Block**.
