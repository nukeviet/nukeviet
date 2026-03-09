#!/bin/bash
# Hook: Chạy sau khi Claude hoàn thành một task lớn
# Mục đích: tóm tắt những gì đã thay đổi, nhắc reviewer

echo ""
echo "════════════════════════════════════"
echo "  ✅ Claude Code đã hoàn thành task"
echo "════════════════════════════════════"

# Liệt kê file PHP đã thay đổi (chưa commit)
CHANGED_PHP=$(git diff --name-only 2>/dev/null | grep "\.php$")
CHANGED_SQL=$(git diff --name-only 2>/dev/null | grep "\.sql$")

if [ -n "$CHANGED_PHP" ]; then
    echo ""
    echo "📝 File PHP đã thay đổi:"
    echo "$CHANGED_PHP" | while read f; do echo "   - $f"; done

    echo ""
    echo "🔍 Chạy review nhanh..."
    for file in $CHANGED_PHP; do
        if [ -f "$file" ]; then
            php -l "$file" > /dev/null 2>&1 || echo "   ❌ Syntax lỗi: $file"
        fi
    done
fi

if [ -n "$CHANGED_SQL" ]; then
    echo ""
    echo "🗄️  File SQL đã thay đổi (cần review migration):"
    echo "$CHANGED_SQL" | while read f; do echo "   - $f"; done
fi

echo ""
echo "📋 Bước tiếp theo:"
echo "   1. Review code: claude '/review-mr'"
echo "   2. Commit: git add -p && git commit"
echo "   3. Push: git push origin feature/<ten-tinh-nang>"
echo "   4. Tạo Merge Request trên GitLab"
echo ""
