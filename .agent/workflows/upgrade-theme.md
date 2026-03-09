---
description: Nâng cấp File Theme NukeViet 5
---

Quá trình nâng cấp Theme từ chuẩn cũ (Bootstrap 3/4) lên chuẩn cài đặt NPM CSS của NukeViet 5 sử dụng Node.js & config.json.

Yêu cầu tham số: Thư mục target theme. ($ARGUMENTS VD: `themes/my-theme`)

1. Quét tìm các file Config cũ:
// turbo-all
```bash
cat $ARGUMENTS/config.ini
```

2. Tạo một file `config.json` mô phỏng khai báo biến cho NPM Compiler nếu chưa có.
3. Review Tpl: Chạy script so sánh xem Theme đang Override file tpl hệ thống nào:
```bash
diff -r themes/default/layout $ARGUMENTS/layout
```

4. Quét tự động xóa cache theme từ thư mục `assets/` nếu phát hiện có lệnh sửa đổi cấu hình theme:
```bash
rm -rf assets/css/$ARGUMENTS*.css
```
