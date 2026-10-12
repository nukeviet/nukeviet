# Image dựng NukeViet 5 cho Anthropic OSS Scanner (https://github.com/anthropics/oss-scanner).
# Chỉ dùng cho mục đích quét bảo mật, không dùng cho production: MariaDB dùng mật khẩu cố định,
# có cài thư viện dev và site được cài sẵn với tài khoản test.
#
# Build context là thư mục gốc repo, mã nguồn nằm ở /src.
#
# Trong container:
#   /src/.oss-scanner/start.sh              khởi động MariaDB, PHP-FPM, Nginx (http://localhost/)
#   php vendor/bin/codecept run Unit        chạy test Unit (cần chạy start.sh trước)
FROM php:8.5-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates curl git jq procps unzip \
        nginx mariadb-server mariadb-client \
        libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
        libzip-dev libicu-dev libgmp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql zip intl bcmath gmp sockets \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Thư mục làm việc trong image
COPY . /src
WORKDIR /src

# Cài thư viện cho bộ test
RUN composer install --no-interaction --no-progress --prefer-dist

RUN cp /src/.oss-scanner/php.ini /usr/local/etc/php/conf.d/zz-nukeviet.ini \
    && rm -f /etc/nginx/sites-enabled/default \
    && cp /src/.oss-scanner/nginx.conf /etc/nginx/conf.d/nukeviet.conf \
    && chmod +x /src/.oss-scanner/*.sh \
    && chown -R www-data:www-data /src/src

# Cài đặt site
RUN /src/.oss-scanner/install.sh || echo "WARNING: automatic NukeViet install failed"

# Chạy test unit
RUN /src/.oss-scanner/start.sh \
    && (php vendor/bin/codecept run Unit || echo "WARNING: some Unit tests failed")

CMD ["/bin/bash", "-c", "/src/.oss-scanner/start.sh && exec bash"]
