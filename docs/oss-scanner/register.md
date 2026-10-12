# Đăng ký NukeViet (nhánh nukeviet5.0) vào Anthropic OSS Scanner

Ngày lập: 2026-10-12. Tài liệu gồm: tóm tắt chương trình, cấu hình đã chốt, các file liên quan, hướng dẫn đăng ký từng bước và danh sách việc còn lại.

Chương trình ra mắt ngày 8/10/2026, điều khoản có thể thay đổi. Đối chiếu lại trang chính thức (mục Nguồn) trước khi gửi PR.

## 1. Tóm tắt chương trình

- Anthropic Cyber Mission công bố ngày 8/10/2026, gồm Chương trình Bảo vệ Hạ tầng Trọng yếu (CIDP) và dịch vụ OSS Scanner cho dự án nguồn mở.
- OSS Scanner là dịch vụ miễn phí, tự nguyện đăng ký, dùng các mô hình của Anthropic để quét mã nguồn, lấy cảm hứng từ OSS-Fuzz của Google.
- Báo cáo gửi qua email tới maintainer, kèm cách tái hiện và patch đề xuất (nếu có). Báo cáo do mô hình sinh ra, chưa qua người rà soát, có thể có dương tính giả hoặc sai mức độ nghiêm trọng.
- Anthropic quét lại định kỳ để tìm lỗi mới phát sinh và lỗi bỏ sót.
- Không áp thời hạn công bố 90 ngày lên phát hiện chưa xác thực. Nếu Anthropic xác thực thủ công qua quy trình CVD của họ, thời hạn 90 ngày tính từ lúc dự án được thông báo.
- Điều kiện đăng ký: dự án đã hình thành, có tác động tới hạ tầng và an ninh người dùng (bị tấn công từ xa được, nhiều người dùng hoặc dự án phụ thuộc), tự xử lý được báo cáo high/critical. Người đăng ký phải là core maintainer, nếu bảo trì dự án thay mặt một tổ chức thì phải được tổ chức đó cho phép. Anthropic xác minh thủ công và có thể liên hệ qua kênh khác.
- Lần đầu gửi PR phải ký CLA của Anthropic, bot sẽ hướng dẫn trong PR.

## 2. Cấu hình đã chốt

- Repo và nhánh quét: `https://github.com/nukeviet/nukeviet#nukeviet5.0`.
- Tên thư mục dự án trong repo đăng ký: `projects/nukeviet`.
- `primary_contact`: email phụ trách bảo mật, khai trong `project.yaml`. Địa chỉ này được công khai.
- Dockerfile và threat model đặt trong repo NukeViet tại `.oss-scanner/`, không đặt ở repo đăng ký.

## 3. Các file liên quan

Trong repo NukeViet:

| File | Nội dung |
|---|---|
| `.oss-scanner/Dockerfile` | Dựng image gồm PHP 8.5-FPM, MariaDB, Nginx, cài thư viện Composer, cài site và chạy test Unit |
| `.oss-scanner/install.sh` | Cài NukeViet qua trình cài đặt web bằng curl, chạy lúc build |
| `.oss-scanner/start.sh` | Khởi động MariaDB, PHP-FPM, Nginx trong container |
| `.oss-scanner/nginx.conf` | Cấu hình Nginx theo khuyến nghị của NukeViet |
| `.oss-scanner/php.ini` | Cấu hình PHP cho image |
| `.oss-scanner/threat_model.md` | Mô tả điểm vào, phạm vi, thang mức độ nghiêm trọng cho scanner |
| `docs/oss-scanner/project.yaml` | Bản nháp `projects/nukeviet/project.yaml` |
| `docs/oss-scanner/pr-description.md` | Bản nháp mô tả PR đăng ký |

Thông tin đăng nhập trong `.oss-scanner/` (CSDL `nukeviet`/`nukeviet`, quản trị `admin`) chỉ dùng trong image quét.

Kiểm tra image trên máy, chạy trong một bản clone sạch:

```bash
git clone --depth 1 -b nukeviet5.0 https://github.com/nukeviet/nukeviet.git nukeviet-scan
cd nukeviet-scan
docker build -t oss-scan-nukeviet -f .oss-scanner/Dockerfile .
docker run --rm -it --network none oss-scan-nukeviet
```

Trong container: `curl -I http://localhost/` trả 200, `php vendor/bin/codecept run Unit` chạy được.

## 4. Hướng dẫn đăng ký từng bước

### Bước 1: Cài công cụ

Cần git, Docker, Python 3 kèm PyYAML. `tools/check` chạy trên Linux hoặc WSL.

```bash
pip install pyyaml
git --version && docker --version && python3 -c "import yaml; print('PyYAML', yaml.__version__)"
```

### Bước 2: Đưa `.oss-scanner/` lên nhánh nukeviet5.0

Tạo nhánh tính năng, commit `.oss-scanner/`, mở PR vào `nukeviet5.0` và merge.

Kiểm tra:
```bash
gh api "repos/nukeviet/nukeviet/contents/.oss-scanner?ref=nukeviet5.0" --jq '.[].name'
```

### Bước 3: Fork và clone repo đăng ký

Fork https://github.com/anthropics/oss-scanner trên GitHub, rồi:

```bash
git clone https://github.com/<tài-khoản-github>/oss-scanner.git
cd oss-scanner
git checkout -b enroll-nukeviet
mkdir -p projects/nukeviet
cp <đường-dẫn-repo-nukeviet>/docs/oss-scanner/project.yaml projects/nukeviet/project.yaml
```

### Bước 4: Kiểm tra cấu hình

```bash
python3 tools/validate.py
tools/check nukeviet
```

`validate.py` không báo lỗi. `tools/check` build xong và mở shell không có mạng; trong shell chạy:

```bash
/src/.oss-scanner/start.sh
curl -sI http://localhost/ | head -1
cd /src && php vendor/bin/codecept run Unit
```

### Bước 5: Mở pull request

```bash
git add projects/nukeviet
git commit -m "Enroll nukeviet in OSS Scanner"
git push -u origin enroll-nukeviet
```

Mở PR vào nhánh `main` của `anthropics/oss-scanner`, dán nội dung `docs/oss-scanner/pr-description.md` vào mô tả, đánh dấu các mục trong checklist. Trên trang PR chỉ có file trong `projects/nukeviet/` thay đổi.

### Bước 6: Sau khi merge

1. Scanner build dự án. Build lỗi thì Anthropic gửi email kèm log tới `primary_contact`.
2. Báo cáo quét gửi về `primary_contact`, xử lý theo `docs/knowledge/security-vulnerability-process.md`.
3. Khi vá lỗi do scanner tìm ra, có thể ghi mã báo cáo vào commit, ví dụ: `Discovered by Anthropic's OSS Scanner, as vulnerability ANT-2026-ABCD1234.`
4. Phản hồi về báo cáo: trả lời trực tiếp email báo cáo. Câu hỏi chung: oss-scanner-questions@anthropic.com.
5. Đổi cấu hình: PR sửa `projects/nukeviet/project.yaml`. Tạm dừng: PR thêm `disabled: true`. Rút khỏi chương trình: PR xóa `projects/nukeviet/`.

## 5. Việc còn lại

Cập nhật: 2026-10-12. Đánh dấu `[x]` khi xong.

### 5.1 Đã xác minh

- [x] Nhánh `nukeviet5.0` tồn tại, repo hoạt động.
- [x] Người đăng ký là admin repo `nukeviet/nukeviet` và admin công khai của tổ chức `nukeviet`.
- [x] Chưa có PR hay thư mục `projects/nukeviet` trong `anthropics/oss-scanner`.
- [x] Đã đọc README, CONTRIBUTING, CLA, PR template, điều khoản, FAQ của OSS Scanner.

### 5.2 Xác nhận tổ chức cho phép

- [ ] Có văn bản của VINADES giao đăng ký NukeViet vào OSS Scanner, chấp nhận điều khoản và ký CLA thay mặt công ty.
- [ ] Báo người phụ trách hộp thư contact@vinades.vn và admin@nukeviet.vn: Anthropic có thể gửi thư hỏi xác nhận.
- [ ] Email `primary_contact` đã thêm và xác minh trên tài khoản GitHub của người đăng ký.

### 5.3 Dockerfile và threat model

- [x] Tạo `.oss-scanner/`.
- [x] Build thử image: cài site tự động thành công, chạy được khi không có mạng, trang chủ và admin trả 200, test Unit 125/127 đạt.
- [x] Sửa test lỗi `TriggerErrorTest`: regex khớp nhầm dòng chú thích ở `src/includes/vendor/vinades/nukeviet/Http/HttpException.php:16`.
- [ ] Sửa notice `Undefined array key "show_home"` ở template block menu `global.bootstrap.tpl` khi mở trang chủ lần đầu sau cài đặt (làm `ErrorLogsTest` lỗi).
- [ ] Rà và chốt thang mức độ nghiêm trọng trong `.oss-scanner/threat_model.md`.
- [ ] Merge `.oss-scanner/` vào `nukeviet5.0` (bước 2).

### 5.4 Gửi đăng ký

- [ ] Bước 3, 4, 5 ở mục 4.
- [ ] Ký CLA khi bot bình luận trên PR.
- [ ] Theo dõi email `primary_contact` để nhận báo lỗi build và báo cáo quét.
- [ ] Sau khi PR được merge: xóa `docs/oss-scanner/project.yaml` và `docs/oss-scanner/pr-description.md`.

## 6. Nguồn

- Anthropic, Introducing the Anthropic Cyber Mission: https://www.anthropic.com/news/anthropic-cyber-mission
- Anthropic Frontier Red Team, bài giới thiệu dịch vụ: https://www.anthropic.com/research/launching-opt-in-vuln-finding-service-for-open-source
- OSS Scanner FAQ: https://red.anthropic.com/oss-scanner
- OSS Scanner Terms: https://red.anthropic.com/oss-scanner/terms/
- Repo đăng ký: https://github.com/anthropics/oss-scanner
- Project template: https://github.com/anthropics/oss-scanner/blob/main/templates/project.yaml
