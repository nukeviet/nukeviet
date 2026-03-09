#!/bin/bash
# Hook: Chạy tự động sau khi Claude viết/sửa file
# Mục đích: kiểm tra coding standard và bảo mật ngay lập tức

# Lấy file vừa được sửa từ biến môi trường Claude Code
EDITED_FILE="${CLAUDE_TOOL_INPUT_FILE_PATH:-}"

# Chỉ xử lý file PHP
if [[ "$EDITED_FILE" != *.php ]]; then
    exit 0
fi

# Chỉ xử lý file trong thư mục modules/ hoặc themes/
if [[ "$EDITED_FILE" != modules/* ]] && [[ "$EDITED_FILE" != themes/* ]]; then
    exit 0
fi

echo "🔍 Đang kiểm tra: $EDITED_FILE"

# ─────────────────────────────────────────────
# 1. Kiểm tra syntax PHP
# ─────────────────────────────────────────────
php -l "$EDITED_FILE" 2>&1
if [ $? -ne 0 ]; then
    echo "❌ LỖI SYNTAX PHP — Claude cần sửa trước khi tiếp tục"
    exit 1
fi

# ─────────────────────────────────────────────
# 2. Kiểm tra coding standard PSR-12
# ─────────────────────────────────────────────
if command -v phpcs &> /dev/null; then
    PHPCS_OUTPUT=$(phpcs --standard=PSR12 --report=summary "$EDITED_FILE" 2>&1)
    PHPCS_EXIT=$?

    if [ $PHPCS_EXIT -ne 0 ]; then
        echo ""
        echo "⚠️  CODING STANDARD (PSR-12):"
        echo "$PHPCS_OUTPUT"
        echo ""
        echo "💡 Tự động fix bằng: phpcbf --standard=PSR12 $EDITED_FILE"
        # Ghi log tự động fix nếu cần: echo "💡 Tự động fix bằng: phpcbf --standard=PSR12 $EDITED_FILE" | tee -a .agent/logs/edits.log
        mkdir -p .agent/logs
    else
        echo "✅ PSR-12: OK"
    fi
fi

# ─────────────────────────────────────────────
# 3. Cảnh báo bảo mật nhanh
# ─────────────────────────────────────────────
SECURITY_ISSUES=0

# Phát hiện dùng $_GET/$_POST trực tiếp (nên qua $nv_Request)
INPUT_ISSUES=$(grep -n "\$_GET\|\$_POST\|\$_REQUEST" "$EDITED_FILE" 2>/dev/null | grep -v "nv_Request\|dbescape\|(int)\|(float)")
if [ -n "$INPUT_ISSUES" ]; then
    echo ""
    echo "🔴 CẢNH BÁO BẢO MẬT — Dùng \$_GET/\$_POST trực tiếp (nên qua \$nv_Request):"
    echo "$INPUT_ISSUES"
    SECURITY_ISSUES=1
fi

# Phát hiện echo trực tiếp biến superglobal
XSS_ISSUES=$(grep -n "echo \$_\|print \$_" "$EDITED_FILE" 2>/dev/null)
if [ -n "$XSS_ISSUES" ]; then
    echo ""
    echo "🔴 CẢNH BÁO XSS — Output \$_ không qua nv_htmlspecialchars:"
    echo "$XSS_ISSUES"
    SECURITY_ISSUES=1
fi

# Phát hiện debug code còn sót
DEBUG_ISSUES=$(grep -n "var_dump\|print_r\|dd(" "$EDITED_FILE" 2>/dev/null)
if [ -n "$DEBUG_ISSUES" ]; then
    echo ""
    echo "⚠️  DEBUG CODE còn sót — cần xóa trước khi commit:"
    echo "$DEBUG_ISSUES"
fi

if [ $SECURITY_ISSUES -eq 0 ]; then
    echo "✅ Bảo mật: Không phát hiện vấn đề rõ ràng"
fi

echo ""
echo "─────────────────────────────────"
