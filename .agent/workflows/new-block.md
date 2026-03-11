---
description: Khởi tạo tệp cơ sở (scaffold) cho một NukeViet Block (Module Block hoặc Theme Block)
---

# Workflow: `/new-block`

Tính năng này giúp bạn tạo nhanh cụm tệp lệnh Block (PHP, JSON, TPL) dựa trên cấu trúc chuẩn của NukeViet 5. 

<!-- Cảnh báo cấu trúc: NukeViet 5 sử dụng thư mục `blocks/` ở module hoặc theme.
Kèm file `global.[name].php` và `global.[name].json`. -->

## 1. Thu thập thông tin từ user
Thu thập 3 thông tin cơ bản:
1. **Loại Block**: `1` (Module Block) hoặc `2` (Theme Block).
2. **Tên Module / Tên Theme đích**.
3. **Tên Block**: (Chỉ đuôi tên, ví dụ `about`, `qr_code`).

## 2. Tạo logic thư mục (Tự động chạy)
Sử dụng công cụ `run_command` để tạo cấu trúc tệp rỗng dựa trên loại Block:

// turbo
```bash
# LƯU Ý CHO AI: Sửa các biến dưới đây thành giá trị User nhập trước khi chạy command
BLOCK_TYPE="1" 
DESTINATION="news"
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
Tải mẫu code chuẩn từ `.agent/skills/nukeviet-block/examples/` (JSON, PHP logic, và TPL).
Sử dụng công cụ `write_to_file` để đắp nội dung cho các tệp lệnh vừa tạo:
- Thay thế tên hàm trong hàm khởi tạo block.
- Đổi tên XTemplate (Module) hoặc NVSmarty (Theme) cho khớp đường dẫn thực tế.

## 4. Kết thúc
Báo cáo hoàn tất và nhắc User kích hoạt block tại: **Admin > Giao diện > Quản lý Block**.
