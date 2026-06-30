#!/bin/bash

# Tool audit composer

# Trước khi chạy scp cần chạy đoạn sau để copy SSH key
# ssh-copy-id -i ~/.ssh/id_rsa php82@192.168.0.3

# Chặn lưu lịch sử các lệnh trong này
set +o history

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  TARGET="$(readlink "$SOURCE")"
  if [[ $TARGET == /* ]]; then
    SOURCE="$TARGET"
  else
    DIR="$( dirname "$SOURCE" )"
    SOURCE="$DIR/$TARGET"
  fi
done
RDIR="$( dirname "$SOURCE" )"
DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
cd "$DIR/../"
DIR_PATH=$PWD

# --- Cấu hình ---
REMOTE_HOST="php82@192.168.0.3"
REMOTE_DIR="/home/php82/composer"
AUDIT_CMD_FILE="$DIR/composer-audit.txt"
TIMESTAMP=$$
LOCAL_UPLOAD_ZIP="/tmp/composer_upload_${TIMESTAMP}.zip"
LOCAL_RESULT_ZIP="/tmp/composer_result_${TIMESTAMP}.zip"
REMOTE_UPLOAD_ZIP="/tmp/composer_upload_${TIMESTAMP}.zip"
REMOTE_RESULT_ZIP="/tmp/composer_result_${TIMESTAMP}.zip"

# --- Hàm tiện ích ---
error_exit() {
    echo "Lỗi: $1" >&2
    exit 1
}

cleanup() {
    rm -f "$LOCAL_UPLOAD_ZIP" "$LOCAL_RESULT_ZIP"
    ssh -o BatchMode=yes -o ConnectTimeout=5 "$REMOTE_HOST" \
        "rm -f '$REMOTE_UPLOAD_ZIP' '$REMOTE_RESULT_ZIP'" 2>/dev/null || true
}
trap cleanup EXIT

# --- Kiểm tra tệp composer-audit.txt trước khi làm gì ---
echo "Kiểm tra tệp composer-audit.txt..."
if [ ! -f "$AUDIT_CMD_FILE" ]; then
    echo "Tệp composer-audit.txt không tồn tại, bỏ qua."
    exit 0
fi
if [ ! -s "$AUDIT_CMD_FILE" ]; then
    echo "Tệp composer-audit.txt rỗng, bỏ qua."
    exit 0
fi
echo "  OK: tệp tồn tại và có nội dung."

# --- Bước 1: Kiểm tra kết nối SSH ---
echo ""
echo "Bước 1: Kiểm tra kết nối SSH đến $REMOTE_HOST..."
ssh -o BatchMode=yes -o ConnectTimeout=10 "$REMOTE_HOST" "echo '  SSH OK'" \
    || error_exit "Không thể kết nối SSH đến $REMOTE_HOST"

# --- Bước 2: Xóa và tạo lại thư mục trên server ---
echo ""
echo "Bước 2: Xóa và tạo lại thư mục $REMOTE_DIR trên server..."
ssh "$REMOTE_HOST" "rm -rf '$REMOTE_DIR' && mkdir -p '$REMOTE_DIR'" \
    || error_exit "Không thể tạo thư mục $REMOTE_DIR trên server"
echo "  OK"

# --- Bước 3: Nén src/includes/vendor + src/includes/composer.json + src/includes/composer.lock ---
echo ""
echo "Bước 3: Nén src/includes/vendor + src/includes/composer.json + src/includes/composer.lock..."
(cd "$DIR_PATH/src/includes" && zip -qr "$LOCAL_UPLOAD_ZIP" vendor composer.json composer.lock) \
    || error_exit "Không thể tạo file zip từ src/includes/"
echo "  OK: $(du -h "$LOCAL_UPLOAD_ZIP" | cut -f1)"

# --- Bước 4: Upload và giải nén trên server ---
echo ""
echo "Bước 4: Upload lên server $REMOTE_HOST..."
scp "$LOCAL_UPLOAD_ZIP" "$REMOTE_HOST:$REMOTE_UPLOAD_ZIP" \
    || error_exit "Không thể upload file zip lên server"
echo "  Upload OK, đang giải nén..."
ssh "$REMOTE_HOST" "unzip -q '$REMOTE_UPLOAD_ZIP' -d '$REMOTE_DIR' && rm -f '$REMOTE_UPLOAD_ZIP'" \
    || error_exit "Không thể giải nén trên server"
REMOTE_UPLOAD_ZIP=""   # đã xóa trên server, không cần cleanup
echo "  Giải nén OK"

# --- Bước 5: Chạy từng lệnh trong composer-audit.txt trên server ---
echo ""
echo "Bước 5: Chạy các lệnh trong composer-audit.txt trên server..."
while IFS= read -r cmd || [ -n "$cmd" ]; do
    # Bỏ qua dòng trống và comment
    [[ -z "${cmd//[[:space:]]/}" || "$cmd" == \#* ]] && continue
    echo "  Thực thi: $cmd"
    ssh -n "$REMOTE_HOST" "cd '$REMOTE_DIR' && $cmd" \
        || error_exit "Lệnh thất bại: $cmd"
done < "$AUDIT_CMD_FILE"
echo "  Tất cả lệnh OK"

# --- Bước 6: Chạy composer audit trên server ---
echo ""
echo "Bước 6: Chạy composer audit trên server..."
ssh "$REMOTE_HOST" "cd '$REMOTE_DIR' && composer audit" \
    || error_exit "composer audit phát hiện lỗi hoặc cảnh báo bảo mật, dừng lại."
echo "  composer audit OK, không có vấn đề bảo mật."

# --- Bước 7: Xóa local, lấy kết quả từ server về ---
echo ""
echo "Bước 7: Xóa local và lấy kết quả từ server về..."
echo "  Xóa src/includes/vendor, src/includes/composer.json, src/includes/composer.lock..."
rm -rf "$DIR_PATH/src/includes/vendor" \
    || error_exit "Không thể xóa src/includes/vendor"
rm -f "$DIR_PATH/src/includes/composer.json" "$DIR_PATH/src/includes/composer.lock" \
    || error_exit "Không thể xóa src/includes/composer.json / src/includes/composer.lock"

echo "  Nén kết quả trên server..."
ssh "$REMOTE_HOST" "cd '$REMOTE_DIR' && zip -qr '$REMOTE_RESULT_ZIP' vendor composer.json composer.lock" \
    || error_exit "Không thể nén kết quả trên server"

echo "  Tải về local..."
scp "$REMOTE_HOST:$REMOTE_RESULT_ZIP" "$LOCAL_RESULT_ZIP" \
    || error_exit "Không thể tải file kết quả về"
ssh "$REMOTE_HOST" "rm -f '$REMOTE_RESULT_ZIP'" 2>/dev/null || true
REMOTE_RESULT_ZIP=""   # đã xóa trên server

echo "  Giải nén vào src/includes/..."
(cd "$DIR_PATH/src/includes" && unzip -q "$LOCAL_RESULT_ZIP") \
    || error_exit "Không thể giải nén kết quả vào src/includes/"

# --- Bước 8: Dọn dẹp vendor bằng clean-vendor.sh ---
echo ""
echo "Bước 8: Kiểm tra và chạy clean-vendor.sh..."
CLEAN_VENDOR_SCRIPT="$DIR_PATH/scripts/clean-vendor.sh"
if [ ! -f "$CLEAN_VENDOR_SCRIPT" ]; then
    error_exit "Không tìm thấy scripts/clean-vendor.sh"
fi
CLEAN_VENDOR_DEST="$DIR_PATH/src/includes/clean-vendor.sh"
cp "$CLEAN_VENDOR_SCRIPT" "$CLEAN_VENDOR_DEST" \
    || error_exit "Không thể copy clean-vendor.sh vào src/includes/"
chmod +x "$CLEAN_VENDOR_DEST"
"$CLEAN_VENDOR_DEST" \
    || { rm -f "$CLEAN_VENDOR_DEST"; error_exit "clean-vendor.sh thất bại"; }
rm -f "$CLEAN_VENDOR_DEST"
echo "  Dọn dẹp vendor OK"

echo ""
echo "Hoàn thành! composer audit thành công, kết quả đã được cập nhật vào src/includes/."

