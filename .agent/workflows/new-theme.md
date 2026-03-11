---
description: Build hoặc tạo mới CSS Theme NukeViet 5
---

Tất cả các theme trong NukeViet 5 đều sử dụng biến của Bootstrap cùng với LESS/SCSS thông qua cấu hình NPM. Lệnh này tự động copy và cài đặt Dependency mới nhất.

## 1. Thu thập tên Theme
Hỏi User xem muốn sử dụng tên gì cho thư mục Theme mới. (Ví dụ: `my-theme`).

## 2. Copy folder default sang thư mục mới
// turbo
```bash
# LƯU Ý CHO AI: Thay đổi THEME_NAME sang tên user yêu cầu trước khi chạy
THEME_NAME="my-theme"
cp -r "themes/default" "themes/$THEME_NAME"
```

## 3. Chỉnh sửa cấu hình theme
Sử dụng `multi_replace_file_content` để dọn dẹp các tệp:
- `themes/$THEME_NAME/config.ini`: Đổi tên hiển thị, xóa các position ko cần thiết.
- Khai báo lại thẻ đầu của file `config.json` nếu có.

## 4. Compile Assets
Nếu user yêu cầu build css, gọi lệnh npm install & compile:
// turbo
```bash
npm install
npm run watch-core
```
