---
description: Quét Security Audit thủ công trên 1 module NukeViet 5.
---

Quy trình sử dụng các lệnh của AI quét nhanh một module hoặc một file/block cụ thể để kiểm tra tính an toàn của các input và logic so chuẩn NukeViet 5.
Chú ý: Cần truyền tham số khi gọi lệnh.
- Quét 1 module (frontend): `/security-audit news`
- Quét 1 module (admin): `/security-audit authors` (sẽ tự tìm trong src/admin/modules/ hoặc src/admin/)
- Quét 1 file/block cụ thể: `/security-audit src/modules/contact/blocks/global.contact_form.php`

// turbo-all
1. Thực hiện quét Audit Security mở rộng trên Module/File:
```bash
TARGET="$ARGUMENTS"
if [ -z "$TARGET" ]; then
    echo "LỖI: Bạn chưa truyền tham số. Hãy gọi lệnh kèm tên module hoặc đường dẫn file."
    echo "Ví dụ: /security-audit contact"
    exit 1
fi

# Tự động nhận diện đường dẫn ưu tiên cho NukeViet 5
if [[ "$TARGET" != *"/"* ]] && [ ! -f "$TARGET" ] && [ ! -d "$TARGET" ]; then
    PATHS=("src/modules/$TARGET" "src/admin/modules/$TARGET" "src/admin/$TARGET")
    for P in "${PATHS[@]}"; do
        if [ -d "$P" ]; then
            TARGET="$P/"
            break
        fi
    done
fi

if [ ! -f "$TARGET" ] && [ ! -d "$TARGET" ]; then
    echo "LỖI: Không tìm thấy mục tiêu: $TARGET"
    exit 1
fi

echo "========================================================="
echo "🔍 ĐANG QUÉT SECURITY AUDIT: $TARGET"
echo "========================================================="

# Regex cấu hình cho các lỗi phổ biến
patterns=(
    "1. Bypassing NukeViet requests (\$_(GET|POST|REQUEST)):\$_(GET|POST|REQUEST)|dbescape|\(int\)|\(float\)|nv_Request|get_int|get_title|get_string"
    "2. Potential Unescaped Output (XSS):(echo|print)\s+\\\$\\\w+|htmlspecialchars|nv_html|intval|NVSmarty|nv_editor"
    "3. Risky File Operations (Path Traversal):is_file|file_exists|unlink|nv_is_file|NV_ROOTDIR|NV_UPLOADS_DIR"
    "4. Object Injection via unserialize:unserialize\s*\\\(|allowed_classes|json_decode"
    "5. CSRF Missing Check (POST calls):isset_request\(.*'post'|nv_check_formtoken|checkss|NV_CHECK_SESSION|hash_equals"
    "6. Secret Keys / Passwords leakage:password|passwd|secret|api_key|//|#|\\\$_POST|\\\$config|lang_|nv_Lang"
)

for item in "${patterns[@]}"; do
    TITLE="${item%%:*}"
    GREP_PAT="${item#*:}"
    BASE_PAT="${GREP_PAT%%|*}"
    EXCLUDE_PAT="${GREP_PAT#*|}"

    echo -e "\n[$TITLE]"

    if [ -f "$TARGET" ]; then
        grep -EIn "$BASE_PAT" "$TARGET" | grep -vEi "$EXCLUDE_PAT" || echo "- Sạch"
    else
        grep -EIrn "$BASE_PAT" "$TARGET" --include="*.php" | grep -vEi "$EXCLUDE_PAT" || echo "- Sạch"
    fi
done

echo -e "\n========================================================="
echo "✅ QUÉT HOÀN TẤT! Dựa vào kết quả trên để lập báo cáo Audit."
echo "========================================================="
```

2. Phân tích kết quả trong Terminal và đề xuất giải pháp sửa lỗi theo chuẩn NukeViet 5.
