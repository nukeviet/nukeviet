#!/bin/bash
# Cài NukeViet qua trình cài đặt web (src/install/index.php) bằng curl
set -euo pipefail

SITE="http://localhost"
INSTALL="$SITE/install/index.php?language=vi"
JAR="$(mktemp)"
trap 'rm -f "$JAR"' EXIT

# Thông tin dùng để test, không phải thông tin thật
DB_NAME=nukeviet
DB_USER=nukeviet
DB_PASS=nukeviet
DB_PREFIX=nv5
ADMIN_USER=admin
ADMIN_PASS='Nukeviet@Scan2026'
ADMIN_EMAIL=admin@example.com

# File .env cho Codeception (tests/codeception/bootstrap.php)
cat > /src/.env <<ENV
HTTP_HOST=localhost
HTTPS=off
NV_LANG="Tiếng Việt"
DB_UNAME=${DB_USER}
DB_UPASS=${DB_PASS}
DB_NAME=${DB_NAME}
NV_SITE_NAME="NukeViet CMS"
NV_USERNAME=${ADMIN_USER}
NV_EMAIL=${ADMIN_EMAIL}
NV_PASSWORD=${ADMIN_PASS}
NV_QUESTION=NukeViet
NV_ANSWER="NukeViet CMS"
LANG_MULTI=1
ENV

/src/.oss-scanner/start.sh

mariadb -uroot <<SQL
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON *.* TO '${DB_USER}'@'localhost';
GRANT ALL PRIVILEGES ON *.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL

# Thiết lập đủ same Origin/Referer để qua được class Request
req() {
    curl -sS -f -b "$JAR" -c "$JAR" -H "Origin: $SITE" -H "Referer: $INSTALL" "$@"
}

# Bước 1-4: ngôn ngữ, kiểm tra thư mục, giấy phép, kiểm tra máy chủ. Mỗi bước mở khóa bước sau qua session
for step in 1 2 3 4; do
    req -o /dev/null "$INSTALL&step=$step"
done

# Bước 5: CSDL, gửi AJAX theo thứ tự system -> từng module -> finish như install.js
DB_FORM="dbtype=mysql&dbhost=127.0.0.1&dbport=&dbuname=${DB_USER}&dbpass=${DB_PASS}&dbname=${DB_NAME}&prefix=${DB_PREFIX}&db_detete=1"

res=$(req --data "${DB_FORM}&ajax_action=system" "$INSTALL&step=5")
if [ "$(jq -r .status <<<"$res")" != "success" ]; then
    echo "Install step 5 (system) failed: $res" >&2
    exit 1
fi

while IFS=$'\t' read -r title file; do
    mres=$(req --data "${DB_FORM}&ajax_action=module" \
        --data-urlencode "module_title=${title}" \
        --data-urlencode "module_file=${file}" \
        "$INSTALL&step=5")
    if [ "$(jq -r .status <<<"$mres")" != "success" ]; then
        echo "Install module ${title} failed: $mres" >&2
        exit 1
    fi
done < <(jq -r '.modules[] | [.title, .module_file] | @tsv' <<<"$res")

res=$(req --data "${DB_FORM}&ajax_action=finish" "$INSTALL&step=5")
if [ "$(jq -r .status <<<"$res")" != "success" ]; then
    echo "Install step 5 (finish) failed: $res" >&2
    exit 1
fi

# Bước 6: thông tin site và tài khoản quản trị. Không bật dev_mode để site không ở chế độ riêng tư
req -o /dev/null -L \
    --data-urlencode "site_name=NukeViet CMS" \
    --data-urlencode "nv_login=${ADMIN_USER}" \
    --data-urlencode "nv_email=${ADMIN_EMAIL}" \
    --data-urlencode "nv_password=${ADMIN_PASS}" \
    --data-urlencode "re_password=${ADMIN_PASS}" \
    --data-urlencode "question=NukeViet" \
    --data-urlencode "answer_question=NukeViet CMS" \
    --data "lang_multi=1" \
    "$INSTALL&step=6"

# Bước 7 (dữ liệu mẫu) tự chuyển sang bước 8 khi không có gói mẫu. Bước 8 ghi config.php
req -o /dev/null -L "$INSTALL&step=7"

if [ ! -f /src/src/config.php ]; then
    echo "Install not completed: src/config.php not found" >&2
    exit 1
fi

# Kiểm tra trang chủ phản hồi
code=$(curl -s -o /dev/null -w '%{http_code}' "$SITE/")
echo "NukeViet installed, home page returned HTTP ${code}"
