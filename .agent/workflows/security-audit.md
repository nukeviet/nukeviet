---
description: Quét Security Audit thủ công trên các file dự án.
---

Quy trình sử dụng các lệnh của AntiGravity quét nhanh hệ thống để kiểm tra tính an toàn của các input và logic so chuẩn NukeViet 5.

// turbo-all
1. Tìm tất cả các file có liên quan tới Request Input mà không pass qua hệ Filter. (Lỗi XSS & Injection):
```bash
grep -rn "\$_GET\|\$_POST\|\$_REQUEST" $ARGUMENTS --include="*.php" | grep -v "dbescape\|(int)\|(float)\|nv_Request"
```

2. Cảnh báo những file xuất output trực tiếp ra view không được thoát chuỗi (Lỗ hổng XSS):
```bash
grep -rn "echo \$\|print \$" $ARGUMENTS --include="*.php" | grep -v "htmlspecialchars\|nv_html\|intval\|NVSmarty"
```

3. Các hàm dùng thư viện file hệ thống nguy hiểm thay cho bộ của NukeViet `nv_is_file`:
```bash
grep -rn "is_file\|file_exists" $ARGUMENTS --include="*.php" | grep -v "nv_is_file\|NV_ROOTDIR"
```

4. Quét qua lộ Mật Khẩu (Hard Coded Secrets / Key API Test):
```bash
grep -rn "password\|passwd\|secret\|api_key" $ARGUMENTS --include="*.php" | grep -v "//\|#\|\$_POST\|\$config"
```

5. AI (Tôi) sẽ tự động kiểm tra code của các lỗ hổng đã được xuất ra log Terminal để đối chiếu hoặc đề xuất sửa đổi và in báo cáo.
