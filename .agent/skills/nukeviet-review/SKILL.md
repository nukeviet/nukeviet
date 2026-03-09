---
name: nukeviet-review
description: Chạy Audit MR / Lint Code NukeViet 5. Phân tích bảo mật, code quality, convention.
allowed-tools: Read, Grep, Bash
---

# Hướng Dẫn Review Code NukeViet 5.x

Tài liệu này hướng dẫn quy trình và tiêu chuẩn khi thực hiện review code (Merge Request hoặc Audit) cho các thành phần của NukeViet 5.

## 1. Quy trình Review

Khi được yêu cầu review một module hoặc file cụ thể, hãy thực hiện theo các bước sau:

1. **Phân tích tĩnh (Lint)**: Chạy kiểm tra lỗi cú pháp và chuẩn coding.
2. **Kiểm tra Bảo mật**: Rà soát các lỗ hổng XSS, SQLi, CSRF.
3. **Kiểm tra Convention**: Đối chiếu với chuẩn NukeViet 5 ($nv_Request, Prefix bảng...).
4. **Đánh giá Chất lượng**: Kiểm tra logic, hiệu năng và tính tái sử dụng.

## 2. Tiêu chuẩn đánh giá

### Bảo mật (Ưu tiên cao nhất)
- [ ] Input có qua `$nv_Request` không?
- [ ] SQL có dùng `prepare()` + `bindParam()` cho chuỗi không?
- [ ] Output có dùng `nv_htmlspecialchars()` không?
- [ ] Kiểm tra file có dùng `nv_is_file()` không?
- [ ] Xử lý POST có kiểm tra `nv_check_formtoken()` không?

### Convention NukeViet 5
- [ ] Có hằng số bảo vệ đầu file không (`NV_SYSTEM`, `NV_ADMIN`...)?
- [ ] Prefix bảng có dùng `NV_PREFIXLANG` hoặc `NV_TABLEPREFIX` không?
- [ ] PSR-4: Namespace có đúng chuẩn `NukeViet\Module\TenModule\...` không?
- [ ] Sử dụng `$db_slave` cho lệnh SELECT và `$db` cho INSERT/UPDATE/DELETE.

### Code Quality & Performance
- [ ] Tuân thủ PSR-12 (4 spaces, thụt lề, ngoặc nhọn).
- [ ] Có PHPDoc đầy đủ cho hàm và class.
- [ ] Tránh query SQL trong vòng lặp (lỗi N+1).
- [ ] Sử dụng `$nv_Cache->db()` cho các truy vấn dữ liệu ít thay đổi.

## 3. Cách báo cáo kết quả

Sử dụng các biểu tượng biểu cảm để phân loại mức độ nghiêm trọng:

- 🔴 **Vấn đề nghiêm trọng**: Lỗ hổng bảo mật hoặc lỗi logic gây treo hệ thống. **Bắt buộc fix trước khi merge.**
- 🟡 **Nên cải thiện**: Chưa tối ưu về hiệu năng hoặc chưa hoàn toàn tuân thủ convention. **Khuyến nghị sửa.**
- ✅ **Điểm tốt**: Code sạch, xử lý thông minh, tuân thủ tốt các chuẩn.

## 4. Công cụ hỗ trợ

Sử dụng workflow hỗ trợ review nhanh:
```bash
# Review nhanh một thư mục/file
/review-mr modules/ten-module/
```

> **Lưu ý:** AI (Tôi) chỉ báo cáo và đề xuất, không tự ý sửa code trừ khi được yêu cầu cụ thể sau khi đã trình bày phương án.
