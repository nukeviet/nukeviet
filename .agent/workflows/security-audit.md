---
description: Quét Security Audit thủ công trên 1 module NukeViet 5.
---

Quy trình sử dụng các lệnh của AI quét nhanh một module hoặc một file/block cụ thể để kiểm tra tính an toàn của các input và logic so chuẩn NukeViet 5.
Chú ý: Cần truyền tham số khi gọi lệnh.
- Quét 1 module (frontend): `/security-audit news`
- Quét 1 module (admin): `/security-audit authors` (sẽ tự tìm trong src/admin/modules/ hoặc src/admin/)
- Quét 1 file/block cụ thể: `/security-audit src/modules/contact/blocks/global.contact_form.php`

// turbo-all
1. Cấu hình đường dẫn và quét các lỗ hổng bảo mật:
```bash
TARGET="$ARGUMENTS"
if [ -z "$TARGET" ]; then
    echo "LỖI: Bạn chưa truyền tham số. Hãy gọi lệnh kèm tên module hoặc đường dẫn file."
    exit 1
fi

# Nếu tham số không chứa '/' và không phải file/dir tồn tại → Tìm theo thứ tự ưu tiên
if [[ "$TARGET" != *"/"* ]] && [ ! -f "$TARGET" ] && [ ! -d "$TARGET" ]; then
    if [ -d "src/modules/$TARGET" ]; then
        TARGET="src/modules/$TARGET/"
    elif [ -d "src/admin/modules/$TARGET" ]; then
        TARGET="src/admin/modules/$TARGET/"
    elif [ -d "src/admin/$TARGET" ]; then
        TARGET="src/admin/$TARGET/"
    else
        echo "LỖI: Không tìm thấy module $TARGET trong src/modules/ hoặc src/admin/"
        exit 1
    fi
fi

# Kiểm tra mục tiêu tồn tại
if [ ! -f "$TARGET" ] && [ ! -d "$TARGET" ]; then
    echo "LỖI: Không tìm thấy thư mục hoặc file $TARGET"
    exit 1
fi

echo "========================================="
echo "BẮT ĐẦU AUDIT MỤC TIÊU: $TARGET"
echo "========================================="

# Helper to run grep consistently
run_audit_grep() {
    TITLE="$1"
    PATTERN="$2"
    EXCLUDE="$3"
    echo -e "\n[$TITLE]:"
    if [ -f "$TARGET" ]; then
        if [ -z "$EXCLUDE" ]; then grep -n "$PATTERN" "$TARGET" || echo "- Không tìm thấy"; else grep -n "$pattern" "$TARGET" | grep -v "$EXCLUDE" || echo "- Không tìm thấy"; fi
    else
        if [ -z "$EXCLUDE" ]; then grep -rn "$PATTERN" "$TARGET" --include="*.php" || echo "- Không tìm thấy"; else grep -rn "$PATTERN" "$TARGET" --include="*.php" | grep -v "$EXCLUDE" || echo "- Không tìm thấy"; fi
    fi
}

# 1. Input bypass
echo -e "\n[1/6] TÌM LỖI XSS & INJECTION (Input có thể bị bypass):"
if [ -f "$TARGET" ]; then grep -n '\$_GET\|\$_POST\|\$_REQUEST' "$TARGET" | grep -v 'dbescape\|(int)\|(float)\|nv_Request' || echo "- Không thấy"; else grep -rn '\$_GET\|\$_POST\|\$_REQUEST' "$TARGET" --include="*.php" | grep -v 'dbescape\|(int)\|(float)\|nv_Request' || echo "- Không thấy"; fi

# 2. Output XSS
echo -e "\n[2/6] TÌM LỖI XSS (Output không escape):"
if [ -f "$TARGET" ]; then grep -n 'echo \$\|print \$' "$TARGET" | grep -v 'htmlspecialchars\|nv_html\|intval\|NVSmarty' || echo "- Không thấy"; else grep -rn 'echo \$\|print \$' "$TARGET" --include="*.php" | grep -v 'htmlspecialchars\|nv_html\|intval\|NVSmarty' || echo "- Không thấy"; fi

# 3. Path Traversal
echo -e "\n[3/6] KIỂM TRA HÀM FILE HỆ THỐNG NGUY HIỂM (Path Traversal):"
if [ -f "$TARGET" ]; then grep -n 'is_file\|file_exists\|unlink' "$TARGET" | grep -v 'nv_is_file\|NV_ROOTDIR' || echo "- Không thấy"; else grep -rn 'is_file\|file_exists\|unlink' "$TARGET" --include="*.php" | grep -v 'nv_is_file\|NV_ROOTDIR' || echo "- Không thấy"; fi

# 4. Object Injection
echo -e "\n[4/6] TÌM unserialize() — Object Injection:"
if [ -f "$TARGET" ]; then grep -n 'unserialize(' "$TARGET" || echo "- Không thấy"; else grep -rn 'unserialize(' "$TARGET" --include="*.php" || echo "- Không thấy"; fi

# 5. CSRF
echo -e "\n[5/6] TÌM FILE XỬ LÝ POST NHƯNG THIẾU KIỂM TRA CSRF:"
if [ -f "$TARGET" ]; then
    grep -l "isset_request(.*'post'" "$TARGET" | xargs -r grep -L "nv_check_formtoken\|checkss\|NV_CHECK_SESSION" || echo "- Không thấy"
else
    grep -rl "isset_request(.*'post'" "$TARGET" --include="*.php" | xargs -r grep -L "nv_check_formtoken\|checkss\|NV_CHECK_SESSION" || echo "- Không thấy"
fi

# 6. Secret Key
echo -e "\n[6/6] TÌM MẬT KHẨU / LỘ SECRET KEY:"
if [ -f "$TARGET" ]; then grep -n 'password\|passwd\|secret\|api_key' "$TARGET" | grep -v '//\|#\|\$_POST\|\$config\|lang_module\|lang_global\|nv_Lang' || echo "- Không thấy"; else grep -rn 'password\|passwd\|secret\|api_key' "$TARGET" --include="*.php" | grep -v '//\|#\|\$_POST\|\$config\|lang_module\|lang_global\|nv_Lang' || echo "- Không thấy"; fi

echo -e "\n========================================="
echo "QUÉT HOÀN TẤT!"
```

2. AI sẽ dựa trên log Terminal này để đưa ra báo cáo chi tiết.
