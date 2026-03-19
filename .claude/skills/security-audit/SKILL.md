---
name: security-audit
description: Quét bảo mật toàn diện (Syntax, PSR-12, Security) cho một module NukeViet 5.
argument-hint: <tên-module|đường-dẫn-file>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Bash
---

Quét bảo mật toàn diện (Syntax, PSR-12, Security) cho một module NukeViet 5.

**Mục tiêu:** $ARGUMENTS

Ví dụ:
- `/security-audit news` — quét toàn bộ module news
- `/security-audit src/modules/contact/blocks/global.contact_form.php` — quét file cụ thể

## Các bước thực hiện

### 1. Xác định mục tiêu
Nếu không có `$ARGUMENTS`, hỏi user. Xác định đường dẫn đầy đủ cần quét.

### 2. Đọc skill bắt buộc (không bỏ qua)
Đọc **cả hai** file sau trước khi bắt đầu:
- `docs/knowledge/security.md`
- `docs/knowledge/module.md`

### 3. Kiểm tra Syntax PHP
```bash
# Nếu $ARGUMENTS là tên module (không chứa /):
find src/modules/$ARGUMENTS src/admin/modules/$ARGUMENTS -name "*.php" -type f 2>/dev/null | xargs -n1 php -l | grep -v "No syntax errors"

# Nếu $ARGUMENTS là đường dẫn file cụ thể:
php -l src/$ARGUMENTS 2>&1 | grep -v "No syntax errors"
```

### 4. Kiểm tra Coding Standard
```bash
if [ -f "vendor/bin/phpcs" ]; then
    PHPCS_CONFIG="tests/phpcs.xml"
    if [ -f "$PHPCS_CONFIG" ]; then
        php vendor/bin/phpcs --standard="$PHPCS_CONFIG" src/modules/$ARGUMENTS
    else
        php vendor/bin/phpcs --standard=PSR12 src/modules/$ARGUMENTS
    fi
fi
```

### 5. Quét bảo mật tự động
```bash
MODULE="$ARGUMENTS"
DIRS=()
for P in "src/modules/$MODULE" "src/admin/modules/$MODULE" "src/admin/$MODULE"; do
    [ -d "$P" ] && DIRS+=("$P")
done
for THEME in default mobile_default admin_default admin_future; do
    D="src/themes/$THEME/modules/$MODULE"
    [ -d "$D" ] && DIRS+=("$D")
done

echo "=== [1] Input không qua \$nv_Request ==="
grep -EIrn '\$_(GET|POST|REQUEST)\b' "${DIRS[@]}" --include="*.php" | grep -vEi 'nv_Request|\(int\)|\(float\)' || echo "Sạch"

echo "=== [2] Output chưa escape (XSS) ==="
grep -EIrn '(echo|print)\s+\$' "${DIRS[@]}" --include="*.php" | grep -vEi 'htmlspecialchars|nv_html|intval|NVSmarty|\$lang_|\$module_info\[|\$site_config\[' || echo "Sạch"

echo "=== [3] File operations rủi ro ==="
grep -EIrn '\b(is_file|file_exists|unlink|file_get_contents|fopen|copy|rename)\s*\(' "${DIRS[@]}" --include="*.php" | grep -vEi 'nv_is_file|NV_ROOTDIR|NV_UPLOADS_DIR' || echo "Sạch"

echo "=== [4] Object Injection (unserialize) ==="
grep -EIrn 'unserialize\s*\(' "${DIRS[@]}" --include="*.php" || echo "Sạch"

echo "=== [5] CSRF — POST handler thiếu csrf_check ==="
grep -rl "\$nv_Request->isset_request\|'post'" "${DIRS[@]}" --include="*.php" 2>/dev/null | xargs -r grep -L 'csrf_check' || echo "Sạch"

echo "=== [B1] Template dùng NV_CHECK_SESSION trực tiếp ==="
grep -EIrn 'NV_CHECK_SESSION' "${DIRS[@]}" --include="*.tpl" || echo "Sạch"

echo "=== [B2] AJAX POST thiếu checkss ==="
grep -EIrn '\$\.ajax|\$\.post|fetch\(' "${DIRS[@]}" --include="*.js" | grep -vEi 'checkss' || echo "Sạch"
```

### 6. Báo cáo kết quả

**🔴 LỖI BẢO MẬT NGHIÊM TRỌNG:**
- Mô tả lỗ hổng, rủi ro cụ thể
- Code fix chuẩn NukeViet 5

**🟡 CODE CHƯA TỐT:**
- Vi phạm PSR-12, N+1 Query, bỏ qua `$db_slave`/`$nv_Cache`

**💡 GỢI Ý CẢI THIỆN:**
- Đề xuất refactor với code mẫu
