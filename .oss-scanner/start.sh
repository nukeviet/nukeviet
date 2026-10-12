#!/bin/bash
# Khởi động MariaDB, PHP-FPM và Nginx trong image OSS Scanner. Chạy lại nhiều lần không lỗi.
set -e

if ! mariadb-admin ping --silent 2>/dev/null; then
    service mariadb start >/dev/null
fi
for i in $(seq 1 30); do
    mariadb-admin ping --silent 2>/dev/null && break
    sleep 1
done

if ! pgrep -x php-fpm >/dev/null; then
    php-fpm -D
fi

if ! pgrep -x nginx >/dev/null; then
    nginx
fi

echo "NukeViet: http://localhost/ (admin: http://localhost/admin/)"
