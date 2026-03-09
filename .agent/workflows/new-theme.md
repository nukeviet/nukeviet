---
description: Build hoặc tạo mới CSS Theme NukeViet 5
---

Tất cả các theme trong NukeViet 5 đều sử dụng biến của Bootstrap cùng với LESS/SCSS thông qua cấu hình NPM. Lệnh này tự động copy và cài đặt Dependency mới nhất.

1. Bạn có muốn tạo Theme mới hay nâng cấp Theme Cũ? Nếu là mới hãy cung cấp tên (VD: `my-theme`).
// turbo
2. Copy thư mục default sang thư mục mới
```bash
cp -r themes/default themes/[ten_theme]
```

3. Yêu cầu AI tự động xóa bớt các file css rác, dọn dẹp nội dung `themes/[ten_theme]/config.ini` và khai báo thẻ mở đầu cho `config.json`.
4. Hướng dẫn chạy cài đặt hệ thống Build Assets Node.js của cổng giao diện thông qua Node:
// turbo
```bash
npm install
npm run watch-core
```
