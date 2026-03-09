#!/bin/bash
# Hook: Chạy TRƯỚC khi Claude thực thi lệnh bash
# Mục đích: chặn lệnh nguy hiểm, bảo vệ production

# Lấy lệnh Claude sắp chạy
COMMAND="${CLAUDE_TOOL_INPUT_COMMAND:-}"

# ─────────────────────────────────────────────
# 1. Chặn push thẳng vào nhánh main/develop
# ─────────────────────────────────────────────
if echo "$COMMAND" | grep -qE "git push.*origin.*(main|develop|master)"; then
    echo "🔴 BỊ CHẶN: Không được push thẳng vào nhánh protected!"
    echo "   Dùng Merge Request trên GitLab thay vì push trực tiếp."
    echo "   Lệnh bị chặn: $COMMAND"
    exit 1
fi

# ─────────────────────────────────────────────
# 2. Chặn xóa file/thư mục quan trọng
# ─────────────────────────────────────────────
if echo "$COMMAND" | grep -qE "rm -rf (modules|themes|includes|config)/"; then
    echo "🔴 BỊ CHẶN: Không được xóa thư mục hệ thống NukeViet!"
    echo "   Lệnh bị chặn: $COMMAND"
    exit 1
fi

# Chặn xóa file cấu hình quan trọng
if echo "$COMMAND" | grep -qE "rm.*(config\.php|database\.php|\.env)"; then
    echo "🔴 BỊ CHẶN: Không được xóa file cấu hình!"
    echo "   Lệnh bị chặn: $COMMAND"
    exit 1
fi

# ─────────────────────────────────────────────
# 3. Cảnh báo lệnh nguy hiểm (không chặn, chỉ cảnh báo)
# ─────────────────────────────────────────────

# Lệnh DROP database/table
if echo "$COMMAND" | grep -qiE "DROP (DATABASE|TABLE|SCHEMA)"; then
    echo "⚠️  CẢNH BÁO: Lệnh sắp xóa database/table!"
    echo "   Lệnh: $COMMAND"
    echo "   Đang tiếp tục sau 3 giây... (Ctrl+C để hủy)"
    sleep 3
fi

# Lệnh chmod 777
if echo "$COMMAND" | grep -qE "chmod.*777"; then
    echo "⚠️  CẢNH BÁO: chmod 777 là không an toàn trên production!"
    echo "   Dùng 755 cho thư mục, 644 cho file thay thế."
fi

# Lệnh curl/wget pipe vào bash (supply chain attack)
if echo "$COMMAND" | grep -qE "(curl|wget).*\|.*(bash|sh)"; then
    echo "🔴 BỊ CHẶN: Không được pipe curl/wget vào shell!"
    echo "   Tải file về trước, kiểm tra rồi mới chạy."
    echo "   Lệnh bị chặn: $COMMAND"
    exit 1
fi

# ─────────────────────────────────────────────
# 4. Log lệnh Claude chạy (audit trail)
# ─────────────────────────────────────────────
LOG_FILE=".agent/logs/commands.log"
mkdir -p .agent/logs
echo "[$(date '+%Y-%m-%d %H:%M:%S')] $COMMAND" >> "$LOG_FILE"

exit 0
