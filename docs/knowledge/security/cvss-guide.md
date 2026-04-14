# CVSS v3.1 Calculator Guide (Song ngữ / Bilingual)

## 1. CVSS là gì? / What is CVSS?

CVSS (Common Vulnerability Scoring System) là hệ thống tiêu chuẩn để
đánh giá mức độ nghiêm trọng của lỗ hổng bảo mật (0--10).

CVSS is a standard framework used to assess the severity of security
vulnerabilities (0--10).

------------------------------------------------------------------------

## 2. Thang điểm / Score Range

-   0.0--3.9: Low
-   4.0--6.9: Medium
-   7.0--8.9: High
-   9.0--10: Critical

------------------------------------------------------------------------

## 3. Base Metrics (Quan trọng nhất / Most important)

### 3.1 Attack Vector (AV)

-   Network (N): Qua internet / Over the internet
-   Adjacent (A): Cùng mạng LAN / Same network
-   Local (L): Cần truy cập máy / Local access required
-   Physical (P): Cần tiếp xúc vật lý / Physical access

------------------------------------------------------------------------

### 3.2 Attack Complexity (AC)

-   Low (L): Dễ khai thác / Easy to exploit
-   High (H): Khó khai thác / Requires special conditions

------------------------------------------------------------------------

### 3.3 Privileges Required (PR)

-   None (N): Không cần login / No authentication
-   Low (L): User thường / Basic user
-   High (H): Admin

------------------------------------------------------------------------

### 3.4 User Interaction (UI)

-   None (N): Không cần user / No user interaction
-   Required (R): Cần user click / Requires user action

------------------------------------------------------------------------

### 3.5 Scope (S)

-   Unchanged (U): Không lan sang hệ khác / Same system
-   Changed (C): Ảnh hưởng hệ khác / Cross-system impact

------------------------------------------------------------------------

### 3.6 Confidentiality (C)

Mức độ lộ lọt thông tin bí mật khi lỗ hổng bị khai thác.  
*How much confidential data can be read by the attacker.*

-   **None (N):** Không rò rỉ dữ liệu nào / No data exposed.  
    → Ví dụ: lỗi chỉ gây crash, không để lộ nội dung.
-   **Low (L):** Đọc được một phần dữ liệu, nhưng không kiểm soát được *dữ liệu gì* bị lộ / Partial, uncontrolled data exposure.  
    → Ví dụ: xem được một số dòng log, metadata không nhạy cảm.
-   **High (H):** Đọc toàn bộ hoặc dữ liệu nhạy cảm trọng yếu (mật khẩu, token, toàn bộ DB) / Full or highly sensitive data exposed.  
    → Ví dụ: SQL Injection dump toàn bộ bảng `users`, lộ private key.

> **Gợi ý chọn:** Nếu kẻ tấn công *có thể đọc dữ liệu nhạy cảm bất kỳ* → High. Chỉ đọc được dữ liệu công khai / không nhạy cảm → Low. Không đọc được gì → None.

------------------------------------------------------------------------

### 3.7 Integrity (I)

Mức độ kẻ tấn công có thể *ghi / sửa / xóa* dữ liệu trên hệ thống.  
*Ability of the attacker to modify or corrupt data.*

-   **None (N):** Không thể thay đổi dữ liệu / No modification possible.  
    → Ví dụ: lỗ hổng chỉ đọc, không ghi.
-   **Low (L):** Sửa được một phần dữ liệu nhưng không kiểm soát hoàn toàn phạm vi / Limited, uncontrolled modifications.  
    → Ví dụ: CSRF thực hiện một hành động nhỏ với quyền user, thay đổi một trường profile.
-   **High (H):** Toàn quyền ghi / xóa / thay thế dữ liệu quan trọng / Full or critical modification.  
    → Ví dụ: ghi đè file cấu hình, xóa bảng DB, chèn mã độc vào code nguồn.

> **Gợi ý chọn:** Nếu kẻ tấn công *kiểm soát hoàn toàn những gì bị sửa* hoặc dữ liệu bị sửa là quan trọng → High. Sửa được nhưng hạn chế phạm vi → Low. Chỉ đọc → None.

> **Lưu ý với XSS:** Script chạy trong session của *nạn nhân*. Nếu nạn nhân là user thường → **Low**. Nếu nạn nhân có thể là admin (payload lưu ở nơi admin xem) → **High**.

------------------------------------------------------------------------

### 3.8 Availability (A)

Mức độ ảnh hưởng đến khả năng hoạt động của hệ thống.  
*Impact on system uptime and performance.*

-   **None (N):** Hệ thống vẫn hoạt động bình thường / No availability impact.  
    → Ví dụ: lỗ hổng chỉ đọc/ghi dữ liệu, không ảnh hưởng uptime.
-   **Low (L):** Hiệu suất giảm hoặc gián đoạn không liên tục, có thể tự phục hồi / Reduced performance or intermittent disruption.  
    → Ví dụ: query nặng làm chậm hệ thống, timeout ngắn hạn.
-   **High (H):** Hệ thống ngừng hoạt động hoàn toàn hoặc kéo dài / Full or sustained outage.  
    → Ví dụ: DoS khiến web sập, crash tiến trình server, xóa toàn bộ dữ liệu.

> **Gợi ý chọn:** Nếu lỗ hổng có thể bị dùng để *làm sập dịch vụ* (crash, DoS) → High. Chỉ làm chậm / gián đoạn nhỏ → Low. Không ảnh hưởng gì → None.

------------------------------------------------------------------------

## 4. Ví dụ / Example

### SQL Injection

-   AV: N
-   AC: L
-   PR: N
-   UI: N
-   S: C
-   C: H
-   I: H
-   A: H

Vector: CVSS:3.1/AV:N/AC:L/PR:N/UI:N/S:C/C:H/I:H/A:H

→ Score \~ 9.8 (Critical)

------------------------------------------------------------------------

### Stored XSS

Script độc được lưu vào DB, tự động chạy trong trình duyệt nạn nhân khi họ xem trang.

-   AV: N — khai thác qua internet
-   AC: L — chỉ cần gửi payload một lần, không cần điều kiện đặc biệt
-   PR: L — thường cần tài khoản user để đăng comment / nội dung
-   UI: R — cần nạn nhân mở trang chứa payload
-   S: C — script chạy trong trình duyệt nạn nhân (scope khác với server)
-   C: H — đánh cắp cookie / session token, exfiltrate dữ liệu hiển thị trên UI
-   I: **L hoặc H** — xem bảng bên dưới
-   A: N — không ảnh hưởng uptime server

| Nạn nhân có thể là | I | Lý do |
|---|---|---|
| User thường | **L** | Chỉ thực hiện được hành động trong quyền user (sửa profile, comment…) |
| Admin (payload ở nơi admin xem) | **H** | Có thể tạo tài khoản, xóa dữ liệu, inject code qua session admin |

> **Câu hỏi quyết định:** Payload có thể kích hoạt trong session admin không?  
> Có → I = **H** · Không → I = **L**

**Trường hợp phổ biến — nạn nhân là admin:**

Vector: CVSS:3.1/AV:N/AC:L/PR:L/UI:R/S:C/C:H/I:H/A:N

→ Score \~ 8.7 (High)

**Trường hợp nạn nhân chỉ là user thường:**

Vector: CVSS:3.1/AV:N/AC:L/PR:L/UI:R/S:C/C:H/I:L/A:N

→ Score \~ 7.6 (High)

------------------------------------------------------------------------

## 5. Vector String là gì? / What is Vector String?

Là chuỗi mô tả toàn bộ cấu hình CVSS.

It encodes all selected metrics.

------------------------------------------------------------------------

## 6. Temporal & Environmental (Tuỳ chọn / Optional)

-   Temporal: exploit có public chưa / exploit maturity
-   Environmental: môi trường thực tế / real-world environment

------------------------------------------------------------------------

## 7. Best Practices

-   Không đoán bừa / Do not guess values
-   Luôn lưu vector string / Always store vector
-   CVSS ≠ Risk / CVSS is not full risk assessment

------------------------------------------------------------------------

## 8. Cách sử dụng nhanh / Quick Usage

1.  Chọn đủ 8 Base Metrics
2.  Kiểm tra điểm tự động
3.  Copy vector string
4.  Lưu vào báo cáo

------------------------------------------------------------------------

## 9. Ghi chú / Notes

CVSS chỉ đo severity, không đo business impact.

CVSS measures technical severity, not business risk.
