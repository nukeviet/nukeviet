#!/bin/bash

# Resolve script directory và cd về project root
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
    TARGET="$(readlink "$SOURCE")"
    if [[ $TARGET == /* ]]; then
        SOURCE="$TARGET"
    else
        DIR="$(dirname "$SOURCE")"
        SOURCE="$DIR/$TARGET"
    fi
done
DIR="$(cd -P "$(dirname "$SOURCE")" >/dev/null 2>&1 && pwd)"
cd "$DIR/.."
PROJECT_ROOT=$PWD

# ── Kiểm tra .env ────────────────────────────────────────────────────────────
ENV_FILE="$PROJECT_ROOT/.env"
if [ ! -f "$ENV_FILE" ]; then
    echo "[LỖI] Không tìm thấy file .env tại: $ENV_FILE" >&2
    echo "       Hãy copy .env.example thành .env và điền các giá trị cần thiết." >&2
    exit 1
fi
source "$ENV_FILE"

# ── Kiểm tra Node.js ─────────────────────────────────────────────────────────
NODE_MIN_MAJOR=18
NODE_MIN_MINOR=17

if ! command -v node &>/dev/null; then
    echo "[LỖI] Không tìm thấy Node.js. Yêu cầu tối thiểu: v${NODE_MIN_MAJOR}.${NODE_MIN_MINOR}.0" >&2
    exit 1
fi

NODE_VERSION=$(node -e "const [maj,min]=process.versions.node.split('.');process.stdout.write(maj+'.'+min)")
NODE_MAJOR=$(echo "$NODE_VERSION" | cut -d. -f1)
NODE_MINOR=$(echo "$NODE_VERSION" | cut -d. -f2)

if [ "$NODE_MAJOR" -lt "$NODE_MIN_MAJOR" ] || \
   { [ "$NODE_MAJOR" -eq "$NODE_MIN_MAJOR" ] && [ "$NODE_MINOR" -lt "$NODE_MIN_MINOR" ]; }; then
    echo "[LỖI] Node.js v$(node -v | tr -d 'v') không đủ yêu cầu. Cần tối thiểu v${NODE_MIN_MAJOR}.${NODE_MIN_MINOR}.0" >&2
    exit 1
fi
echo "[OK] Node.js $(node -v)"

# ── Kiểm tra npx ─────────────────────────────────────────────────────────────
if ! command -v npx &>/dev/null; then
    echo "[LỖI] Không tìm thấy npx. Hãy cài lại Node.js (npx đi kèm từ v5.2+)." >&2
    exit 1
fi
echo "[OK] npx $(npx -v)"

# ── Sinh .mcp.json từ giá trị .env ───────────────────────────────────────────
MCP_FILE="$PROJECT_ROOT/.mcp.json"
cat > "$MCP_FILE" <<EOF
{
    "mcpServers": {
        "mcp_server_mysql": {
            "command": "npx",
            "args": [
                "-y",
                "@benborla29/mcp-server-mysql"
            ],
            "env": {
                "MYSQL_HOST": "127.0.0.1",
                "MYSQL_PORT": "3306",
                "MYSQL_USER": "${DB_UNAME}",
                "MYSQL_PASS": "${DB_UPASS}",
                "MYSQL_DB": "${DB_NAME}",
                "ALLOW_INSERT_OPERATION": "true",
                "ALLOW_UPDATE_OPERATION": "true",
                "ALLOW_DELETE_OPERATION": "true"
            }
        }
    }
}
EOF
echo "[OK] Đã tạo .mcp.json (DB: ${DB_NAME}@127.0.0.1 user=${DB_UNAME})"
