# NukeViet Security Vulnerability Response — Quick Summary / Tóm tắt nhanh

**Dành cho:** Người báo lỗi bảo mật / For: Security Researchers  
**Phiên bản:** 1.0  
**Cập nhật:** 2026-05-18

---

## Câu hỏi thường gặp / FAQ

### Câu 1: Lỗ hổng của tôi có cần CVE không?

**Trả lời: Phụ thuộc vào mức độ nghiêm trọng (CVSS) và các "barriers":**

| Scenario | CVSS | CVE cần thiết? | Thời gian fix |
|---|---|---|---|
| **RCE, SQLi (no auth), Authentication Bypass** | 9.0–10.0 | ✅ **Bắt buộc** | 7 ngày |
| **Stored XSS (admin), Privilege escalation, Path traversal (sensitive)** | 7.0–8.9 | ✅ **Bắt buộc** | 14 ngày |
| **Lỗ hổng ảnh hưởng rộng, dễ khai thác** (e.g., Reflected XSS, CSRF) | 4.0–6.9 | ⚠️ **Khuyến nghị** | 30 ngày |
| **Lỗ hổng có nhiều barriers** (disabled, admin-only, hardcoded config, warning) | 4.0–6.9 | ⚠️ **Tuỳ chọn** | Phiên bản tiếp theo |
| **Self-only, missing header, low risk** | 0.1–3.9 | ❌ **Không cần** | Phiên bản tiếp theo |

**Ví dụ cụ thể:**
- ✅ **Cần CVE:** Unvalidated file upload → RCE (không có barrier)
- ❌ **Không cần CVE:** Module upload/unzip (disabled by default, admin-only, needs config edit, has warning) → Changelog entry đủ

---

### Q2: Tôi yêu cầu CVE nhưng không cần thiết, có được không?

**Trả lời:** Không khuyến khích.

Nếu lỗ hổng không đáp ứng CVE criteria, chúng tôi sẽ:
1. Giải thích rõ tại sao không cần CVE
2. Ghi rõ các "mitigating factors" (barriers)
3. Commit vẫn sẽ fix, nhưng trong phiên bản thường (không release khẩn cấp)

Lý do:
- CVE dành cho các lỗ hổng **thực sự ảnh hưởng người dùng**
- Nếu lỗ hổng có nhiều barriers, người dùng bình thường không bị ảnh hưởng
- Phát hành CVE cho tất cả mọi lỗ hổng → **CVE fatigue** (mất tín dụng)

---

### Câu 3: Nếu lỗ hổng của tôi bị từ chối CVE, tôi có thể làm gì?

**Trả lời:** Bạn có các tuỳ chọn:

1. **Chấp nhận** kết luận của chúng tôi
   - Lỗ hổng sẽ được fix trong phiên bản tiếp theo
   - Bạn sẽ được ghi nhận trong changelog (nếu muốn)
   
2. **Yêu cầu xem xét lại** (appeal)
   - Cung cấp thêm chi tiết chứng minh lỗ hổng ảnh hưởng rộng hơn
   - Chúng tôi sẽ xem xét lại trong 7 ngày

3. **Công bố độc lập** (uncoordinated disclosure)
   - Nếu bạn không đồng ý, bạn có quyền công bố lỗ hổng sau embargo period (90 ngày)
   - Tuy nhiên, điều này **không khuyến khích** vì:
     - ✅ CVD (Coordinated Disclosure) là tiêu chuẩn ngành
     - ✅ Nó bảo vệ người dùng tốt hơn
     - ✅ Nó xây dựng tin tưởng dài hạn

---

### Câu 4: Thời gian embargo là bao lâu?

**Trả lời:** **90 ngày** mặc định.

- Nếu bản vá sẵn sàng sớm → Công bố sớm hơn (sau khi thỏa thuận)
- Nếu lỗ hổng đang bị exploit trong thực tế (0-day) → Công bố ngay khi có bản vá

---

### Câu 5: Tôi sẽ nhận được gì khi báo lỗ hổng?

**Trả lời:**

✅ **Khi báo cáo:**
- Tracking ID nội bộ (VD: `NVSEC-2026-001`)
- Xác nhận đã nhận báo cáo (trong 2 ngày làm việc)

✅ **Khi fix sẵn sàng:**
- CVSS Score, CWE, severity classification
- Thông báo ngày phát hành bản vá

✅ **Khi công bố:**
- Credit trong Security Advisory (trừ khi yêu cầu ẩn danh)
- Link đến Security Hall of Fame (nếu áp dụng)

---

### Câu 6: Liên hệ ai nếu có câu hỏi?

**Trả lời:**

- **GitHub:** Report qua [GitHub Security Advisories](https://github.com/nukeviet/nukeviet/security/advisories) (khuyến nghị)
- **WhiteHub:** <https://whitehub.net/programs/nukeviet/hacktivity>

---

## "Mitigating Factors" là gì?

Các yếu tố **làm giảm rủi ro** của một lỗ hổng:

| Factor | Ý nghĩa | Ví dụ |
|---|---|---|
| **Disabled by default** | Tính năng tắt mặc định, user không bị ảnh hưởng trừ khi chủ động bật | Module upload, advanced feature |
| **Admin-only** | Chỉ administrator mới dùng được (không phải user thường) | Admin panel feature |
| **Hardcoded config** | Cần sửa file config cứng (không thể từ UI) | IP whitelist, feature flag |
| **Multiple steps** | Không phải one-click exploit, cần kết hợp nhiều bước | Upload → unzip → execute |
| **Clear warning** | Có cảnh báo rõ ràng "Unsafe", "Dev-only", "Trusted network only" | UI message, documentation |

**Nếu lỗ hổng có 3+ factors trên → CVE thường KHÔNG bắt buộc.**

---

## Case Study: Module Upload/Unzip

**Báo cáo lỗi:** Module upload cho phép unzip các tệp không đúng mong muốn

### Phân tích:

| Yếu tố | Có? | Ảnh hưởng |
|---|---|---|
| Disabled by default | ✅ | User bình thường không dùng |
| Admin-only | ✅ | Chỉ admin mới truy cập |
| Hardcoded config + IP whitelist | ✅ | Phải sửa config file, không thể exploit từ UI |
| Multiple steps + warning | ✅ | Có cảnh báo "NOT for production" |
| Worst-case impact (RCE) | ✅ | Nếu exploit thành công |

### Kết luận:

```
CVSS: ~5.5 (Medium)
Severity: Medium (constrained)
CVE: ❌ KHÔNG cần CVE

Hành động:
- Fix code: tuổi validation, tăng cường warning
- Release: phiên bản tiếp theo (không cần phát hành khẩn cấp)
- Changelog: ghi rõ security improvement
- Credit: ghi tên người báo nếu muốn
```

**Phản hồi với người báo:**

> Cảm ơn báo cáo. Chúng tôi xác nhận đây là một lỗ hổng hợp lệ, nhưng do các yếu tố giảm rủi ro (disabled, admin-only, requires config change, has warning), chúng tôi phân loại này là **Medium constrained** và **không cần CVE riêng**.
>
> Fix sẽ được merge vào phiên bản tiếp theo và ghi trong changelog.
>
> Cảm ơn nỗ lực của bạn trong phát hiện bảo mật có trách nhiệm!

---

## Tài liệu đầy đủ

Xem [security-vulnerability-process.md](security-vulnerability-process.md) để chi tiết quy trình, mẫu phản hồi, và các template khác.

---

**Bất kỳ câu hỏi nào? Liên hệ qua GitHub Security Advisories hoặc WhiteHub.**
