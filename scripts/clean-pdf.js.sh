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
cd "$DIR/../src/assets/js/pdf.js"
DIR_ROOT=$PWD

# ── Kiểm tra python3 ──────────────────────────────────────────────────────────
if ! command -v python3 &>/dev/null; then
    echo "Lỗi: python3 không tìm thấy. Hãy cài đặt Python 3 trước khi chạy script này." >&2
    exit 1
fi

# ── 1. Tạo index.html rỗng trong DIR_ROOT và tất cả thư mục con ──────────────
find "$DIR_ROOT" -type d | while IFS= read -r dir; do
    > "$dir/index.html"
done

# ── 2. Xóa file .pdf trong web/ ───────────────────────────────────────────────
find "$DIR_ROOT/web" -maxdepth 1 -name "*.pdf" -delete

# ── 3. Xóa debugger.css và debugger.mjs trong web/ ───────────────────────────
rm -f "$DIR_ROOT/web/debugger.css" "$DIR_ROOT/web/debugger.mjs"

# ── 4. Dọn web/locale: chỉ giữ en-US (→ đổi tên en), fr, vi, ru ─────────────
LOCALE_DIR="$DIR_ROOT/web/locale"
KEEP_LOCALES=("en-US" "en" "fr" "vi" "ru")

# Xóa các thư mục con không nằm trong danh sách giữ lại
for dir in "$LOCALE_DIR"/*/; do
    [ -d "$dir" ] || continue
    dirname=$(basename "$dir")
    keep=false
    for k in "${KEEP_LOCALES[@]}"; do
        if [ "$dirname" = "$k" ]; then
            keep=true
            break
        fi
    done
    if [ "$keep" = false ]; then
        rm -rf "$dir"
    fi
done

# Đổi tên en-US → en
if [ -d "$LOCALE_DIR/en-US" ]; then
    mv "$LOCALE_DIR/en-US" "$LOCALE_DIR/en"
fi

# Lọc locale.json: chỉ giữ en (từ en-us), fr, vi, ru
python3 - "$LOCALE_DIR/locale.json" <<'PYEOF'
import json, sys

locale_json = sys.argv[1]
keep = {"en-us", "en", "fr", "vi", "ru"}

with open(locale_json, encoding="utf-8") as f:
    data = json.load(f)

filtered = {}
for k, v in data.items():
    if k.lower() not in keep:
        continue
    if k.lower() == "en-us":
        filtered["en"] = "en/viewer.ftl"
    else:
        filtered[k] = v

with open(locale_json, "w", encoding="utf-8") as f:
    json.dump(filtered, f, separators=(",", ":"), ensure_ascii=False)
PYEOF

# ── 5. Patch web/viewer.mjs ───────────────────────────────────────────────────
VIEWER="$DIR_ROOT/web/viewer.mjs"

# 5a. Thay navigator.language || "en-US" → PDJS_LOCALE || "en-US"
if grep -qF 'PDJS_LOCALE' "$VIEWER"; then
    echo "viewer.mjs: PDJS_LOCALE đã tồn tại, bỏ qua."
else
    count=$(grep -cF 'navigator.language || "en-US"' "$VIEWER")
    if [ "$count" -ne 1 ]; then
        echo "Lỗi: tìm thấy $count lần 'navigator.language || \"en-US\"' trong viewer.mjs (cần đúng 1)." >&2
        exit 1
    fi
    sed -i 's/navigator\.language || "en-US"/PDJS_LOCALE || "en-US"/' "$VIEWER"
    echo "viewer.mjs: đã thay thế navigator.language || \"en-US\" → PDJS_LOCALE || \"en-US\"."
fi

# 5b. Thay "compressed.tracemonkey-pldi-09.pdf" → PDJS_PDF_URL
if grep -qF 'PDJS_PDF_URL' "$VIEWER"; then
    echo "viewer.mjs: PDJS_PDF_URL đã tồn tại, bỏ qua."
else
    count=$(grep -cF '"compressed.tracemonkey-pldi-09.pdf"' "$VIEWER")
    if [ "$count" -ne 1 ]; then
        echo "Lỗi: tìm thấy $count lần '\"compressed.tracemonkey-pldi-09.pdf\"' trong viewer.mjs (cần đúng 1)." >&2
        exit 1
    fi
    sed -i 's/"compressed\.tracemonkey-pldi-09\.pdf"/PDJS_PDF_URL/' "$VIEWER"
    echo "viewer.mjs: đã thay thế \"compressed.tracemonkey-pldi-09.pdf\" → PDJS_PDF_URL."
fi

# ── 5c. Chèn file = PDJS_PDF_URL; trước validateFileURL(file); ───────────────
if grep -qF 'file = PDJS_PDF_URL;' "$VIEWER"; then
    echo "viewer.mjs: 'file = PDJS_PDF_URL;' đã tồn tại, bỏ qua."
else
    count=$(grep -cF 'validateFileURL(file);' "$VIEWER")
    if [ "$count" -ne 1 ]; then
        echo "Lỗi: tìm thấy $count lần 'validateFileURL(file);' trong viewer.mjs (cần đúng 1)." >&2
        exit 1
    fi
    sed -i 's/validateFileURL(file);/file = PDJS_PDF_URL;\n    validateFileURL(file);/' "$VIEWER"
    echo "viewer.mjs: đã chèn 'file = PDJS_PDF_URL;' trước validateFileURL."
fi

# ── 5d. Thay 7 path hardcoded → PDJS_DIR trong viewer.mjs ────────────────────
if grep -qF 'PDJS_DIR' "$VIEWER"; then
    echo "viewer.mjs: paths PDJS_DIR đã được thay thế, bỏ qua."
else
    _patterns=(
        'value: "./images/",'
        'value: "../web/cmaps/",'
        'value: "../web/iccs/",'
        'value: "../web/standard_fonts/",'
        'value: "../web/wasm/",'
        'value: "../build/pdf.worker.mjs",'
        'value: "../build/pdf.sandbox.mjs",'
    )
    for p in "${_patterns[@]}"; do
        count=$(grep -cF "$p" "$VIEWER")
        if [ "$count" -ne 1 ]; then
            echo "Lỗi: tìm thấy $count lần '$p' trong viewer.mjs (cần đúng 1)." >&2
            exit 1
        fi
    done
    sed -i \
        -e 's|value: "./images/",|value: PDJS_DIR + "web/images/",|' \
        -e 's|value: "../web/cmaps/",|value: PDJS_DIR + "web/cmaps/",|' \
        -e 's|value: "../web/iccs/",|value: PDJS_DIR + "web/iccs/",|' \
        -e 's|value: "../web/standard_fonts/",|value: PDJS_DIR + "web/standard_fonts/",|' \
        -e 's|value: "../web/wasm/",|value: PDJS_DIR + "web/wasm/",|' \
        -e 's|value: "../build/pdf.worker.mjs",|value: PDJS_DIR + "build/pdf.worker.mjs",|' \
        -e 's|value: "../build/pdf.sandbox.mjs",|value: PDJS_DIR + "build/pdf.sandbox.mjs",|' \
        "$VIEWER"
    echo "viewer.mjs: đã thay thế 7 path hardcoded → PDJS_DIR."
fi

# ── 6. Đổi tên viewer.html → viewer.php và patch href/src → $pdf_js_dir ──────
VIEWER_HTML="$DIR_ROOT/web/viewer.html"
VIEWER_PHP="$DIR_ROOT/web/viewer.php"

# Đổi tên nếu chưa đổi
if [ -f "$VIEWER_HTML" ]; then
    mv "$VIEWER_HTML" "$VIEWER_PHP"
fi

if [ ! -f "$VIEWER_PHP" ]; then
    echo "Lỗi: không tìm thấy viewer.php (cũng không có viewer.html)." >&2
    exit 1
fi

# Patch paths nếu chưa được thay thế
if grep -qF '$pdf_js_dir' "$VIEWER_PHP"; then
    echo "viewer.php: đã được patch trước đó, bỏ qua."
else
    _check_count() {
        local pattern="$1" file="$2" label="$3"
        local count
        count=$(grep -cF "$pattern" "$file")
        if [ "$count" -ne 1 ]; then
            echo "Lỗi: tìm thấy $count lần '$label' trong viewer.php (cần đúng 1)." >&2
            exit 1
        fi
    }
    _check_count 'src="../build/pdf.mjs"'   "$VIEWER_PHP" 'src="../build/pdf.mjs"'
    _check_count 'href="locale/locale.json"' "$VIEWER_PHP" 'href="locale/locale.json"'
    _check_count 'href="viewer.css"'         "$VIEWER_PHP" 'href="viewer.css"'
    _check_count 'src="viewer.mjs"'          "$VIEWER_PHP" 'src="viewer.mjs"'

    sed -i \
        -e 's|src="../build/pdf.mjs"|src="<?= $pdf_js_dir ?>build/pdf.mjs"|' \
        -e 's|href="locale/locale.json"|href="<?= $pdf_js_dir ?>web/locale/locale.json"|' \
        -e 's|href="viewer.css"|href="<?= $pdf_js_dir ?>web/viewer.css"|' \
        -e 's|src="viewer.mjs"|src="<?= $pdf_js_dir ?>web/viewer.mjs"|' \
        "$VIEWER_PHP"
    echo "viewer.php: đã patch tất cả href/src → \$pdf_js_dir."
fi

# 6b. Thay dir="ltr" → <?= $nv_html_dir ?>
if grep -qF '$nv_html_dir' "$VIEWER_PHP"; then
    echo "viewer.php: dir đã được patch, bỏ qua."
else
    count=$(grep -cF 'dir="ltr"' "$VIEWER_PHP")
    if [ "$count" -ne 1 ]; then
        echo "Lỗi: tìm thấy $count lần 'dir=\"ltr\"' trong viewer.php (cần đúng 1)." >&2
        exit 1
    fi
    sed -i 's|dir="ltr"|dir="<?= $nv_html_dir ?>"|' "$VIEWER_PHP"
    echo "viewer.php: đã thay dir=\"ltr\" → \$nv_html_dir."
fi

# 6c. Thay charset="utf-8" → <?= $global_config['site_charset'] ?>
if grep -qF 'site_charset' "$VIEWER_PHP"; then
    echo "viewer.php: charset đã được patch, bỏ qua."
else
    count=$(grep -cF 'charset="utf-8"' "$VIEWER_PHP")
    if [ "$count" -ne 1 ]; then
        echo "Lỗi: tìm thấy $count lần 'charset=\"utf-8\"' trong viewer.php (cần đúng 1)." >&2
        exit 1
    fi
    sed -i 's|charset="utf-8"|charset="<?= $global_config['"'"'site_charset'"'"'] ?>"|' "$VIEWER_PHP"
    echo "viewer.php: đã thay charset=\"utf-8\" → \$global_config['site_charset']."
fi

# ── 7. Chèn script PDJS_LOCALE / PDJS_PDF_URL / PDJS_DIR trước pdf.mjs ───────
if grep -qF 'PDJS_DIR' "$VIEWER_PHP"; then
    echo "viewer.php: script PDJS (incl. PDJS_DIR) đã tồn tại, bỏ qua."
elif grep -qF 'PDJS_PDF_URL' "$VIEWER_PHP"; then
    # Block cũ chưa có PDJS_DIR — chèn thêm vào sau dòng PDJS_PDF_URL
    sed -i \
        's|const PDJS_PDF_URL = <?= json_encode($pdf_url) ?>;|const PDJS_PDF_URL = <?= json_encode($pdf_url) ?>;\nconst PDJS_DIR = <?= json_encode($pdf_js_dir) ?>;|' \
        "$VIEWER_PHP"
    echo "viewer.php: đã bổ sung PDJS_DIR vào script PDJS hiện có."
else
    python3 - "$VIEWER_PHP" <<'PYEOF'
import sys

viewer_php = sys.argv[1]
marker = 'src="<?= $pdf_js_dir ?>build/pdf.mjs"'
inject = (
    '<script>\n'
    'const PDJS_LOCALE = <?= json_encode($nv_lang_interface) ?>;\n'
    'const PDJS_PDF_URL = <?= json_encode($pdf_url) ?>;\n'
    'const PDJS_DIR = <?= json_encode($pdf_js_dir) ?>;\n'
    '</script>\n'
)

with open(viewer_php, encoding='utf-8') as f:
    content = f.read()

if marker not in content:
    print("Lỗi: không tìm thấy điểm chèn trong viewer.php", file=sys.stderr)
    sys.exit(1)

lines = content.splitlines(keepends=True)
result = []
for line in lines:
    if marker in line:
        result.append(inject)
    result.append(line)

with open(viewer_php, 'w', encoding='utf-8') as f:
    f.write(''.join(result))
PYEOF
    echo "viewer.php: đã thêm script PDJS_LOCALE / PDJS_PDF_URL / PDJS_DIR."
fi

echo "Đã hoàn thành dọn dẹp PDF.js."
