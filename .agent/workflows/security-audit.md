---
description: Quét Security Audit thủ công trên 1 module NukeViet 5.
---

Quy trình sử dụng các lệnh của AI quét nhanh một module hoặc một file/block cụ thể để kiểm tra tính an toàn của các input và logic so chuẩn NukeViet 5.
Chú ý: Cần truyền tham số khi gọi lệnh.
- Quét 1 module (frontend): `/security-audit news`
- Quét 1 module (admin): `/security-audit authors` (sẽ tự tìm trong src/admin/modules/ hoặc src/admin/)
- Quét 1 file/block cụ thể: `/security-audit src/modules/contact/blocks/global.contact_form.php`

## 1. Yêu cầu nhập liệu
Xác định mục tiêu cần quét (Ví dụ: `news`, `authors`, hoặc đường dẫn cụ thể `src/modules/...`).
Nếu người dùng chưa cung cấp tham số, hãy hỏi lại.

## 2. Chuẩn bị — Load kiến thức bảo mật
Trước khi phân tích kết quả, AI **BẮT BUỘC** phải dùng `view_file` đọc:
- `.agent/skills/nukeviet-security/SKILL.md` — Quy tắc bảo mật, cách fix, code mẫu.
- `.agent/skills/nukeviet-module/SKILL.md` — Cấu trúc file module, CSRF token pattern, `$nv_Request`.

> [!IMPORTANT]
> Nếu AI bỏ qua bước này, người dùng có quyền yêu cầu chạy lại. AI nên tóm tắt 2-3 điểm quan trọng từ mỗi file này trong báo cáo để xác nhận đã đọc.

## 3. Kiểm tra Cú pháp & Chuẩn Coding
Sử dụng công cụ `run_command` kiểm tra trước khi quét bảo mật:

// turbo
```bash
# LƯU Ý CHO AI: Sửa biến MODULE thành chuỗi User yêu cầu (VD: user gõ /security-audit myapi -> MODULE="myapi")
MODULE="TÊN_HOẶC_ĐƯỜNG_DẪN_MODULE_Ở_ĐÂY"

if [ -z "$MODULE" ]; then
    echo "LỖI: Bạn chưa truyền tham số. Hãy truyền tên module hoặc đường dẫn file."
    exit 1
fi

# Xây dựng danh sách đường dẫn cần quét
SCAN_DIRS=()

# 1. Trường hợp quét file/thư mục cụ thể (có chứa dấu /)
if [[ "$MODULE" == *"/"* ]]; then
    if [ -f "$MODULE" ] || [ -d "$MODULE" ]; then
        SCAN_DIRS+=("$MODULE")
    else
        echo "LỖI: Không tìm thấy mục tiêu: $MODULE"
        exit 1
    fi
else
    # 2. Trường hợp quét tên module
    # Tìm tất cả nơi chứa PHP của module đó (frontend, admin, api, hooks...)
    for P in "src/modules/$MODULE" "src/admin/modules/$MODULE" "src/admin/$MODULE"; do
        if [ -d "$P" ]; then
            SCAN_DIRS+=("$P")
        fi
    done

    # Ghép thêm thư mục giao diện (themes tpl, css, js)
    for THEME in "default" "mobile_default" "admin_default" "admin_future"; do
        THEME_DIR="src/themes/$THEME/modules/$MODULE"
        [ -d "$THEME_DIR" ] && SCAN_DIRS+=("$THEME_DIR")

        [ -f "src/themes/$THEME/css/$MODULE.css" ] && SCAN_DIRS+=("src/themes/$THEME/css/$MODULE.css")
        [ -f "src/themes/$THEME/js/$MODULE.js" ] && SCAN_DIRS+=("src/themes/$THEME/js/$MODULE.js")

        # JS phụ cho admin (VD: myapi_detail.js)
        for JS in src/themes/$THEME/js/${MODULE}_*.js; do
            [ -f "$JS" ] && SCAN_DIRS+=("$JS")
        done
    done
fi

if [ ${#SCAN_DIRS[@]} -eq 0 ]; then
    echo "LỖI: Không tìm thấy mục tiêu nào cho: $MODULE"
    exit 1
fi

echo "========================================================="
echo "📁 DANH SÁCH QUÉT:"
printf "   → %s\n" "${SCAN_DIRS[@]}"
echo "========================================================="

echo -e "\n1. Checking PHP Syntax (Quick check)..."
# Thay vì find -exec (chậm trên Windows), ta dùng find + xargs hoặc liệt kê thư mục.
for DIR in "${SCAN_DIRS[@]}"; do
    if [ -d "$DIR" ]; then
        echo "   → Linting: $DIR"
        find "$DIR" -name "*.php" -type f -print0 | xargs -0 -n 20 php -l | grep -v "No syntax errors" || true
    elif [ -f "$DIR" ] && [[ "$DIR" == *.php ]]; then
        php -l "$DIR" | grep -v "No syntax errors" || true
    fi
done

echo -e "\n2. Checking Coding Standard (PHPCS)..."
if command -v phpcs >/dev/null 2>&1; then
    PHPCS="phpcs"
elif [ -f "vendor/bin/phpcs" ]; then
    PHPCS="php vendor/bin/phpcs"
else
    echo -e "⚠️  CẢNH BÁO: Không tìm thấy phpcs. Bỏ qua check PSR-12."
    PHPCS=""
fi

if [ ! -z "$PHPCS" ]; then
    PHPCS_CONFIG="tests/phpcs.xml"
    if [ -f "$PHPCS_CONFIG" ]; then
        $PHPCS --standard="$PHPCS_CONFIG" "${SCAN_DIRS[@]}"
    else
        $PHPCS --standard=PSR12 "${SCAN_DIRS[@]}"
    fi
fi
```

## 4. Chạy Auto Scan Security
Sử dụng công cụ `run_command` quét Audit Security mở rộng:

// turbo
```bash
# LƯU Ý CHO AI: Dùng đúng biến MODULE và sao chép lại mảng SCAN_DIRS từ Script bước 3
MODULE="TÊN_HOẶC_ĐƯỜNG_DẪN_MODULE_Ở_ĐÂY"
# Mảng SCAN_DIRS (AI copy lại kết quả resolve đường dẫn từ bash trước đưa vào đây cho đồng bộ)
SCAN_DIRS=( "src/modules/$MODULE" )

echo "========================================================="
echo "🔍 ĐANG QUÉT SECURITY AUDIT: $MODULE"
echo "========================================================="

# === PHẦN A: QUÉT FILE PHP ===
echo -e "\n📁 [A] QUÉT FILE PHP (*.php)"

echo -e "\n[1/6] Input không qua \$nv_Request (\$_GET/\$_POST/\$_REQUEST trực tiếp)"
grep -EIrn '\$_(GET|POST|REQUEST)\b' "${SCAN_DIRS[@]}" --include="*.php" | grep -vEi 'nv_Request|\(int\)|\(float\)' || echo "- Sạch"

echo -e "\n[2/6] Output chưa escape (XSS tiềm ẩn)"
grep -EIrn '(echo|print)\s+\$' "${SCAN_DIRS[@]}" --include="*.php" | grep -vEi 'htmlspecialchars|nv_html|intval|NVSmarty|nv_editor' || echo "- Sạch"

echo -e "\n[3/6] File Operations rủi ro (Path Traversal)"
grep -EIrn '\b(is_file|file_exists|unlink|file_get_contents|fopen|copy|rename)\s*\(' "${SCAN_DIRS[@]}" --include="*.php" | grep -vEi 'nv_is_file|NV_ROOTDIR|NV_UPLOADS_DIR' || echo "- Sạch"

echo -e "\n[4/6] Object Injection (unserialize)"
grep -EIrn 'unserialize\s*\(' "${SCAN_DIRS[@]}" --include="*.php" || echo "- Sạch"

echo -e "\n[5/6] CSRF — File POST thiếu checkss/hash_equals"
grep -rl "isset_request(.*'post'" "${SCAN_DIRS[@]}" --include="*.php" 2>/dev/null | xargs -r grep -L 'checkss\|NV_CHECK_SESSION\|hash_equals' || echo "- Sạch"

echo -e "\n[6/6] Secrets / Password leakage"
grep -EIrn 'password|passwd|secret|api_key' "${SCAN_DIRS[@]}" --include="*.php" | grep -vEi '//|#|\$_POST|\$config|lang_module|lang_global|nv_Lang|admin_password|change_pass' || echo "- Sạch"

# === PHẦN B: QUÉT FILE TEMPLATE & JS ===
echo -e "\n📁 [B] QUÉT FILE TEMPLATE (*.tpl) & JAVASCRIPT (*.js)"

echo -e "\n[B1] Template dùng NV_CHECK_SESSION trực tiếp (nên dùng {CHECKSS})"
grep -EIrn 'NV_CHECK_SESSION' "${SCAN_DIRS[@]}" --include="*.tpl" || echo "- Sạch"

echo -e "\n[B2] JS gửi AJAX POST thiếu checkss"
grep -EIrn '\$\.ajax|\$\.post|fetch\(' "${SCAN_DIRS[@]}" --include="*.js" | grep -vEi 'checkss' || echo "- Sạch"

echo -e "\n[B3] Template output không escape (in biến trực tiếp)"
grep -EIrn 'echo\s+\$|print\s+\$|\{\$[a-zA-Z]' "${SCAN_DIRS[@]}" --include="*.tpl" | grep -vEi 'nv_html|htmlspecialchars|LANG\.' || echo "- Sạch"

echo -e "\n========================================================="
echo "✅ QUÉT HOÀN TẤT! AI sẽ tự đọc kết quả và tạo báo cáo rà soát."
echo "========================================================="
```

## 5. Phân tích & Báo cáo kết quả
AI cần tự động đọc kết quả xuất ra trong Terminal, kết hợp với việc xem xét lại mã nguồn (nếu cần) và tạo **Báo cáo rà soát (Audit Report)** tổng hợp.
Trong báo cáo cần chia rõ các chuyên mục:
- 🔴 **LỖI BẢO MẬT NGHIÊM TRỌNG:** Phân tích gốc rễ lỗ hổng (XSS, SQLi, CSRF...) và cung cấp cách sửa chuẩn NukeViet 5 (tham khảo code mẫu trong `nukeviet-security/SKILL.md`).
- 🟡 **CÁC ĐIỂM CODE CHƯA TỐT (Code Smells):** Ghi nhận các điểm code chưa tuân thủ PSR-12, truy vấn SQL chưa tối ưu (vòng lặp chứa SQL, bỏ qua `$db_slave`, `$nv_Cache`), cấu trúc lộn xộn.
- 💡 **GỢI Ý CẢI THIỆN:** Đề xuất giải pháp và đoạn code gợi ý để cấu trúc lại (Refactor), nâng cao chất lượng mã nguồn dài hạn.
