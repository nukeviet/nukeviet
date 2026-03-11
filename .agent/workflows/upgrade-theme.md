---
description: Nâng cấp File Theme NukeViet 5
---

Quá trình nâng cấp Theme từ chuẩn cũ (Bootstrap 3/4) lên chuẩn cài đặt NPM CSS của NukeViet 5 sử dụng Node.js & config.json.

Yêu cầu tham số: Thư mục target theme. ($ARGUMENTS VD: `themes/my-theme`)

## 1. Quét tìm cấu hình cũ
// turbo
```bash
# LƯU Ý CHO AI: Sửa biến TARGET theo yêu cầu user (VD: themes/my-theme)
TARGET="themes/my-theme"
cat $TARGET/config.ini
```

## 2. Tạo file cấu hình Node
Tạo một file `config.json` mô phỏng khai báo biến cho NPM Compiler nếu chưa có.

## 3. Review Template Override
Chạy script so sánh xem Theme đang Override file tpl hệ thống nào:
// turbo
```bash
diff -r themes/default/layout $TARGET/layout
```

## 4. Xóa Cache CSS
Quét tự động xóa cache theme từ thư mục `assets/` nếu phát hiện có lệnh sửa đổi cấu hình theme:
// turbo
```bash
rm -rf assets/css/$TARGET*.css
```
