# Hướng Dẫn Bắt Đầu Với AI — NukeViet 5.x

## Tổng quan

Dự án NukeViet đã được tích hợp sẵn bộ **skills và agents chuyên biệt** giúp AI hiểu sâu về cấu trúc, quy tắc coding và best practices của framework. Thay vì giải thích lại từ đầu, bạn có thể sử dụng trực tiếp các lệnh skill để thực hiện các tác vụ phổ biến.

## Cấu trúc AI Skills

```
.claude/                          # Claude Code AI configuration
├── settings.json                 # Permissions & tools allowed
└── skills/                       # Specialized skills
    ├── migrate2adminfuture/      # XTemplate → Smarty migration
    ├── security-admin/           # Security audit for admin functions
    ├── security-audit/           # Module-wide security analysis
    ├── new-module/               # Generate new module structure
    ├── new-theme/                # Create new theme from template
    ├── new-block/                # Block development helper
    ├── new-hook/                 # Hook system integration
    ├── new-api/                  # API endpoint generator
    ├── add-func/                 # Add new function to existing module
    ├── upgrade-module/           # Version upgrade automation
    └── upgrade-theme/            # Theme compatibility updates

.github/agents/                   # GitHub Copilot agents
└── migrate2adminfuture.agent.md  # Copilot agent referencing Claude skill

docs/knowledge/                   # Deep knowledge base
├── module.md                     # Module development guide
├── theme.md                      # Theme development guide
├── security.md                   # Security best practices
├── mysql.md                      # Database patterns
├── cache.md                      # Caching system
├── hook.md                       # Hook system
├── language.md                   # Multi-language system
├── api.md                        # API development
├── testing.md                    # Testing framework
├── xtemplate-to-smarty.md        # Migration guide
└── examples/                     # Code templates and patterns
```

---

## 1. Claude Code AI (Khuyến nghị - hoàn thiện nhất)

### Thiết lập

Claude Code AI đã được cấu hình sẵn với bộ **skills chuyên biệt** cho NukeViet. Không cần cài đặt thêm.

### Cách sử dụng Skills

**Cú pháp:** `/{skill-name} [arguments]`

#### Skills chính

| Skill | Mô tả | Cú pháp |
|-------|--------|---------|
| **`/migrate2adminfuture`** | Chuyển admin_default → admin_future | `/migrate2adminfuture news/authors` |
| **`/security-audit`** | Quét bảo mật toàn module | `/security-audit news` |
| **`/security-admin`** | Audit function admin cụ thể | `/security-admin news authors` |
| **`/new-module`** | Tạo module mới hoàn chỉnh | `/new-module mymodule` |
| **`/new-theme`** | Tạo theme từ template | `/new-theme mytheme` |
| **`/new-block`** | Tạo block cho module/theme | `/new-block news latest` |
| **`/new-hook`** | Tạo hook integration | `/new-hook users user_delete` |
| **`/new-api`** | Tạo API endpoint | `/new-api news GetList` |
| **`/add-func`** | Thêm function vào module | `/add-func news export` |
| **`/upgrade-module`** | Nâng cấp module lên phiên bản mới | `/upgrade-module news 5.0.00` |
| **`/upgrade-theme`** | Cập nhật theme tương thích | `/upgrade-theme default 5.0.00` |

### Ví dụ sử dụng

```
# Chuyển giao diện admin từ XTemplate sang Smarty
/migrate2adminfuture news/content

# Quét bảo mật module users
/security-audit users

# Tạo module mới tên "portfolio"
/new-module portfolio

# Thêm function "export" vào module news
/add-func news export
```

### Khi nào dùng Skills vs Chat thường

| Tình huống | Dùng Skills | Chat thường |
|------------|-------------|-------------|
| Tác vụ có sẵn skill tương ứng | ✅ Ưu tiên | ❌ |
| Tác vụ phức tạp, theo quy trình chuẩn | ✅ | ❌ |
| Câu hỏi đơn giản, giải thích code | ❌ | ✅ |
| Debug lỗi nhanh | ❌ | ✅ |
| Thay đổi nhỏ (1-2 dòng) | ❌ | ✅ |

---

## 2. Google Antigravity (Gemini)

### Thiết lập

Antigravity sử dụng `.agents` folder. Tạo symlink/junction từ `.claude`:

#### Windows
```cmd
cd d:\nukeviet\
mklink /J ".agents" ".claude"
mklink /H "ANTIGRAVITY.md" "CLAUDE.md"
```

#### Linux/macOS
```bash
cd /path/to/nukeviet
ln -sf ".claude" ".agents"
ln "CLAUDE.md" "ANTIGRAVITY.md"
```

### Cách sử dụng

Sau khi tạo symlink, Antigravity sẽ tự động nhận diện các skills từ thư mục `.agents/skills/`.

**Lưu ý:** Thay đổi path theo đường dẫn thực tế của dự án trên máy bạn.

---


## 2. GitHub Copilot

Thiết lập như Google Antigravity bên trên

---

## 4. Quy trình làm việc với AI

### Quy trình chuẩn (Khuyến nghị cho tác vụ lớn)

1. **Xác định tác vụ** → kiểm tra có skill tương ứng không
2. **Đọc tài liệu** trong `docs/knowledge/` liên quan
3. **Chạy skill** với tham số phù hợp
4. **Review code** được tạo ra
5. **Test chức năng** và fix lỗi nếu có

### Quy trình nhanh (cho thay đổi nhỏ)

1. **Chat trực tiếp** với AI về vấn đề
2. **Cung cấp context** bằng cách paste code hiện tại
3. **Áp dụng gợi ý** từ AI
4. **Verify** bằng cách chạy thử

### Best Practices

- **Luôn backup** code trước khi chạy skill lớn
- **Xóa cache** sau khi thay đổi cấu trúc: `rm -rf src/data/cache/*/*.cache`
- **Kiểm tra syntax:** `php -l filename.php`
- **Test bảo mật:** Dùng `/security-audit` trước khi commit
- **Đọc changelog:** Khi upgrade module/theme

---

## 5. Tùy chỉnh và Mở rộng

### Tạo skill mới cho Claude

```bash
# Tạo thư mục skill
mkdir .claude/skills/my-new-skill

# Tạo file SKILL.md với YAML frontmatter
cat > .claude/skills/my-new-skill/SKILL.md << 'EOF'
---
name: my-new-skill
description: Mô tả skill này làm gì
argument-hint: <arguments>
disable-model-invocation: false
allowed-tools: Read, Grep, Glob, Edit, Write, Bash
---

# Nội dung hướng dẫn chi tiết skill

## Bước 1 - Phân tích yêu cầu
...

## Bước 2 - Thu thập thông tin
...

## Bước 3 - Thực hiện
...
EOF
```

### Cấu trúc SKILL.md

**YAML Frontmatter bắt buộc:**
- `name`: Tên skill (dùng trong `/skill-name`)
- `description`: Mô tả ngắn
- `argument-hint`: Gợi ý tham số cho user
- `allowed-tools`: Tools AI được phép dùng

**Nội dung Markdown:**
- Hướng dẫn chi tiết từng bước
- Code examples và patterns
- Error handling và edge cases

### Permissions và Security

File `.claude/settings.json` kiểm soát quyền hạn của AI:

```json
{
  "permissions": {
    "allow": [
      "Bash(php -l *)",
      "Bash(php vendor/bin/codecept *)",
      "Bash(composer *)"
    ]
  }
}
```

**Chỉ cho phép:**
- Syntax check PHP
- Chạy tests Codeception
- Các lệnh Composer an toàn

---

## 6. Troubleshooting

### Lỗi thường gặp

| Lỗi | Nguyên nhân | Giải pháp |
|-----|-------------|-----------|
| Skill không tìm thấy | Sai tên hoặc chưa tạo file | Kiểm tra `.claude/skills/{name}/SKILL.md` |
| Agent Copilot không hoạt động | Chưa tạo `.github/agents/{name}.agent.md` | Tạo file agent tham chiếu skill |
| Antigravity không nhận diện | Chưa tạo symlink | Tạo lại symlink từ `.claude` → `.agents` |
| AI từ chối thực hiện | Không có permission | Thêm vào `.claude/settings.json` |

### Debug Skills

```bash
# Kiểm tra cấu trúc skills
ls -la .claude/skills/

# Validate YAML frontmatter
head -n 20 .claude/skills/skill-name/SKILL.md

# Test permissions
grep -A 10 '"allow"' .claude/settings.json
```

---

## 7. Tài liệu tham khảo

- **Kiến thức sâu:** `docs/knowledge/` — đọc trước khi phát triển
- **Code patterns:** `docs/knowledge/examples/` — templates và patterns chuẩn
- **Security guide:** `docs/knowledge/security.md` — bảo mật bắt buộc
- **Migration guide:** `docs/knowledge/xtemplate-to-smarty.md` — nâng cấp giao diện
- **API reference:** `docs/knowledge/api.md` — phát triển API endpoint

---

## Kết luận

Hệ thống AI skills của NukeViet giúp tự động hóa các tác vụ phổ biến và đảm bảo code tuân thủ chuẩn an toàn. Ưu tiên sử dụng skills có sẵn trước khi chat tự do để có kết quả tốt nhất.

**Next steps:**
1. Thử nghiệm với skill đơn giản như `/security-audit`
2. Đọc `docs/knowledge/module.md` để hiểu cấu trúc
3. Tạo skill tùy chỉnh cho workflow riêng của team
