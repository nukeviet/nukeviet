# Security Policy / Chính sách bảo mật

This document explains how to report security vulnerabilities in NukeViet CMS and what reporters can expect from our response process.

Tài liệu này mô tả cách báo cáo lỗ hổng bảo mật trong NukeViet CMS và các bước xử lý mà bạn có thể kỳ vọng.

## Supported Versions / Phiên bản được hỗ trợ

We currently prioritize security fixes for active maintained lines.

Hiện tại, chúng tôi ưu tiên bản vá bảo mật cho các nhánh đang được bảo trì.

| Version line | Status | Notes |
| --- | --- | --- |
| 5.x | Supported | Primary maintained line |
| 4.5.x | Limited | Best-effort security backports when feasible |
| < 4.5 | Not supported | Please upgrade to a supported line |

## Reporting a Vulnerability / Cách báo cáo lỗ hổng

Please use private channels first. Do not open public issues for unpatched vulnerabilities.

Vui lòng sử dụng kênh riêng tư trước. Không tạo issue công khai cho lỗ hổng chưa được vá.

### Preferred Channel / Kênh ưu tiên

1. GitHub Security Advisories
   - Go to the repository Security and quality tab, open Advisories, then choose Report a vulnerability.
   - Truy cập tab Security and quality của repository, vào Advisories, sau đó chọn Report a vulnerability.

### Alternative Channel / Kênh thay thế

1. WhiteHub Bug Bounty
   - https://whitehub.net/programs/nukeviet/hacktivity

### What to include / Nội dung cần cung cấp

1. Affected version(s) and deployment context.
1. Steps to reproduce (clear and minimal).
1. Proof of concept, logs, screenshots, or video.
1. Impact assessment (confidentiality, integrity, availability).
1. Suggested fix or mitigation if available.

1. Phiên bản bị ảnh hưởng và bối cảnh triển khai.
1. Các bước tái hiện lỗi (rõ ràng, tối giản).
1. Proof of concept, log, ảnh chụp màn hình, hoặc video.
1. Đánh giá tác động (bảo mật, toàn vẹn, sẵn sàng).
1. Gợi ý cách sửa nếu có.

## Response Process and SLA / Quy trình xử lý và thời hạn

We follow Coordinated Vulnerability Disclosure (CVD).

Chúng tôi áp dụng mô hình Coordinated Vulnerability Disclosure (CVD).

| Step | Target time | Details |
| --- | --- | --- |
| Acknowledge report | Within 2 business days | Assign internal tracking ID (example: NVSEC-YYYY-NNN) |
| Reproduce and validate | Within 7 days | Confirm whether issue is a valid security vulnerability |
| Severity classification | During triage | CWE + CVSS 3.1 + exploitability and impact |
| Patch development | By severity SLA | Private branch, peer review, regression testing |

### Patch SLA by severity / Thời hạn phát hành bản vá theo mức độ

| Severity | CVSS | Patch target |
| --- | --- | --- |
| Critical | 9.0-10.0 | 7 days |
| High | 7.0-8.9 | 14 days |
| Medium | 4.0-6.9 | 30 days |
| Low | 0.1-3.9 | Next regular release (or up to 90 days) |

## CVE Policy / Chính sách CVE

We request CVE IDs when impact and exploitation conditions justify formal public tracking.

Chúng tôi đăng ký CVE khi mức độ ảnh hưởng và điều kiện khai thác cần theo dõi công khai chính thức.

| Case | CVE decision |
| --- | --- |
| Critical and High | Required |
| Medium with wide impact | Recommended |
| Medium with strong mitigating factors | Optional |
| Low | Usually not required |

Common mitigating factors include: disabled by default, admin-only access, required non-default hard configuration, multiple barriers, and explicit warnings.

Các yếu tố giảm nhẹ phổ biến gồm: tắt mặc định, chỉ admin truy cập, yêu cầu cấu hình không mặc định, nhiều lớp cản trở, và cảnh báo rõ ràng.

## Embargo and Disclosure / Embargo và công bố

Default embargo period is 90 days from report receipt. We may disclose earlier when a patch is ready and coordinated with the reporter.

Thời hạn embargo mặc định là 90 ngày kể từ khi tiếp nhận báo cáo. Có thể công bố sớm hơn khi đã có bản vá và thống nhất với người báo cáo.

If active exploitation is detected in the wild, we may accelerate release and advisory publication.

Nếu phát hiện khai thác thực tế (in the wild), chúng tôi có thể đẩy nhanh bản vá và công bố cảnh báo.

## Researcher Expectations / Kỳ vọng đối với nhà nghiên cứu

1. Act in good faith and avoid privacy violations, data destruction, or service disruption.
1. Do not access, modify, or delete data that does not belong to you.
1. Do not publicly disclose details before coordinated release or embargo expiration.

1. Hành động thiện chí, tránh vi phạm quyền riêng tư, phá hủy dữ liệu, hoặc gây gián đoạn dịch vụ.
1. Không truy cập, sửa, xóa dữ liệu không thuộc quyền của bạn.
1. Không công bố công khai trước khi phát hành phối hợp hoặc hết embargo.

## Recognition / Ghi nhận

We appreciate responsible disclosure and may credit reporters in advisories or release notes unless anonymity is requested.

Chúng tôi trân trọng việc công bố có trách nhiệm và có thể ghi nhận người báo cáo trong advisory hoặc release notes, trừ khi bạn yêu cầu ẩn danh.

## Notes / Lưu ý

Security reports sent through public issue trackers may be hidden, transferred, or closed to protect users until fixes are available.

Báo cáo bảo mật gửi qua issue công khai có thể bị ẩn, chuyển kênh, hoặc đóng tạm thời để bảo vệ người dùng trước khi có bản vá.
