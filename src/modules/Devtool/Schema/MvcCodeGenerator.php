<?php

/**
 * @Project NUKEVIET 5.0
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\Schema;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * MvcCodeGenerator — Sinh mã MVC từ SchemaEntity.
 *
 * Dựa theo kiến trúc docs/modules/Content.md:
 *   Entity → Repository → Service → Validator → Controller → Template
 *
 * Cách dùng:
 *   $gen = new MvcCodeGenerator();
 *   $files = $gen->generate($entity, NV_ROOTDIR);
 *   foreach ($files as $file) {
 *       file_put_contents($file['full_path'], $file['content']);
 *   }
 */
class MvcCodeGenerator
{
    /**
     * Ánh xạ kiểu SQL cơ bản → kiểu PHP.
     */
    private const BASE_TYPE_MAP = [
        'int'       => 'int',
        'bigint'    => 'int',
        'tinyint'   => 'int',
        'smallint'  => 'int',
        'mediumint' => 'int',
        'year'      => 'int',
        'float'     => 'float',
        'double'    => 'float',
        'decimal'   => 'float',
        // Tất cả còn lại → string
    ];

    /**
     * Sinh tất cả file MVC cho một SchemaEntity.
     *
     * @param SchemaEntity $entity  Schema đã parse
     * @param string       $nvRootDir  NV_ROOTDIR (đường dẫn tuyệt đối)
     * @return array<array{path:string, full_path:string, content:string, exists:bool}>
     */
    public function generate(SchemaEntity $entity, string $nvRootDir): array
    {
        $mod    = $entity->module;                      // VD: "Content"
        $item   = ucfirst($entity->function_name);      // VD: "Detail"
        $itemLc = strtolower($entity->function_name);   // VD: "detail"

        $files = [];

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Entity.php",
            $this->buildEntity($entity, $mod, $item), $nvRootDir);

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Repository.php",
            $this->buildRepository($entity, $mod, $item), $nvRootDir);

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Service.php",
            $this->buildService($entity, $mod, $item, $itemLc), $nvRootDir);

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Validator.php",
            $this->buildValidator($entity, $mod, $item), $nvRootDir);

        if ($entity->layout_type !== 'mvc_only') {
            $this->addFile($files, "modules/{$mod}/admin/{$itemLc}.php",
                $this->buildController($entity, $mod, $item, $itemLc), $nvRootDir);

            $this->addFile($files, "themes/admin_future/modules/{$mod}/{$itemLc}.tpl",
                $this->buildTemplate($entity, $mod, $item, $itemLc), $nvRootDir);

            // Tự động cập nhật các file config/menu/ngôn ngữ
            $this->addMetadataFiles($files, $entity, $mod, $item, $itemLc, $nvRootDir);
        }

        return $files;
    }

    /**
     * Cập nhật các file admin.functions.php, admin.menu.php và language/vi.php.
     */
    private function addMetadataFiles(array &$files, SchemaEntity $entity, string $mod, string $item, string $itemLc, string $nvRootDir): void
    {
        // 1. admin.functions.php
        $funcFile = "modules/{$mod}/admin.functions.php";
        $fullFuncPath = rtrim($nvRootDir, '/\\') . '/' . $funcFile;
        if (file_exists($fullFuncPath)) {
            $content = file_get_contents($fullFuncPath);
            // Regex kiểm tra xem 'func' đã có trong mảng chưa (chính xác từng ký tự)
            if (!preg_match("/['\"]" . preg_quote($itemLc) . "['\"]/i", $content)) {
                // Thêm vào mảng $allow_func
                $newContent = preg_replace('/(\$allow_func\s*=\s*\[)/i', "$1\n    '{$itemLc}',", $content);
                if ($newContent !== $content) {
                    $this->addFile($files, $funcFile, $newContent, $nvRootDir);
                }
            }
        }

        // 2. admin.menu.php
        $menuFile = "modules/{$mod}/admin.menu.php";
        $fullMenuPath = rtrim($nvRootDir, '/\\') . '/' . $menuFile;
        if (file_exists($fullMenuPath)) {
            $content = file_get_contents($fullMenuPath);
            // Kiểm tra chính xác key của submenu [ 'op' ] hoặc ["op"]
            if (!preg_match("/\\\$submenu\s*\[\s*['\"]" . preg_quote($itemLc) . "['\"]\s*\]/i", $content)) {
                // Thêm vào cuối các dòng $submenu
                $inject = "\$submenu['{$itemLc}'] = \$nv_Lang->getModule('{$itemLc}');\n";
                // Tìm dòng $submenu cuối cùng hoặc sau tags defined(NV_ADMIN)
                if (preg_match('/(\$submenu\[[^\]]+\]\s*=\s*[^;]+;)/', $content)) {
                    // Chèn sau dòng submenu cuối cùng
                    $newContent = preg_replace('/(\$submenu\[[^\]]+\]\s*=\s*[^;]+;)(?!\s*\$submenu)/', "$1\n$inject", $content);
                } else {
                    $newContent = $content . "\n" . $inject;
                }
                if ($newContent !== $content) {
                    $this->addFile($files, $menuFile, $newContent, $nvRootDir);
                }
            }
        }

        // 3. language/vi.php
        $langFile = "modules/{$mod}/language/vi.php";
        $fullLangPath = rtrim($nvRootDir, '/\\') . '/' . $langFile;
        if (file_exists($fullLangPath)) {
            $content = file_get_contents($fullLangPath);
            // Kiểm tra chính xác key ngôn ngữ
            if (!preg_match("/\\\$lang_module\s*\[\s*['\"]" . preg_quote($itemLc) . "['\"]\s*\]/i", $content)) {
                $inject = "\$lang_module['{$itemLc}'] = '{$item}';\n";
                // Chèn vào sau $lang_module['cat'] hoặc ở cuối
                if (strpos($content, '$lang_module') !== false) {
                    $newContent = preg_replace('/(\$lang_module\[[^\]]+\]\s*=\s*[^;]+;)(?!\s*\$lang_module)/', "$1\n$inject", $content);
                } else {
                    $newContent = $content . "\n" . $inject;
                }
                if ($newContent !== $content) {
                    $this->addFile($files, $langFile, $newContent, $nvRootDir);
                }
            }
        }
    }

    // ─────────────────────────────────────────────────
    // Helper nội bộ
    // ─────────────────────────────────────────────────

    private function addFile(array &$files, string $path, string $content, string $nvRootDir): void
    {
        $fullPath = str_replace('\\', '/', rtrim($nvRootDir, '/\\') . '/' . $path);
        $files[] = [
            'path'      => $path,
            'full_path' => $fullPath,
            'content'   => $content,
            'exists'    => file_exists($fullPath),
        ];
    }

    /** Lấy kiểu SQL cơ bản từ chuỗi như "int(11) unsigned", "varchar(255)". */
    private function getBaseType(string $sqlType): string
    {
        return strtolower((string) preg_replace('/[\s(].*$/', '', $sqlType));
    }

    /** Chuyển kiểu SQL → kiểu PHP. */
    private function phpType(string $sqlType): string
    {
        $base = $this->getBaseType($sqlType);
        return self::BASE_TYPE_MAP[$base] ?? 'string';
    }

    /** Giá trị mặc định cho property PHP. */
    private function phpDefault(string $phpType): string
    {
        return match ($phpType) {
            'int'   => '0',
            'float' => '0.0',
            default => "''",
        };
    }

    /**
     * Chọn phương thức $nv_Request phù hợp theo view_type và sql_type.
     * Trả về tên method (get_title | get_int | get_float | get_textarea | get_editor).
     */
    private function requestMethod(array $colConfig): string
    {
        $vt      = $colConfig['view_type'] ?? 'textbox';
        $phpType = $this->phpType($colConfig['sql_type'] ?? 'varchar(255)');

        return match ($vt) {
            'number_int', 'checkbox'         => 'get_int',
            'number_float'                   => 'get_float',
            'textarea'                       => 'get_textarea',
            'editor'                         => 'get_editor',
            'select', 'radio'                => $phpType === 'int' ? 'get_int' : 'get_title',
            default                          => 'get_title',
        };
    }

    /** Tiêu đề bản quyền chuẩn NukeViet 5. */
    private function copyright(): string
    {
        return <<<'PHP'
        /**
         * @Project NUKEVIET 5.0
         * @Author VINADES.,JSC <contact@vinades.vn>
         * @Copyright (C) 2024 VINADES.,JSC. All rights reserved
         * @License: GNU/GPL version 2 or any later version
         */
        PHP;
    }

    // ─────────────────────────────────────────────────
    // Sinh từng file
    // ─────────────────────────────────────────────────

    /** Kiểm tra xem một field có phải là field đặc biệt (hệ thống/tự động) cần ẩn khỏi form/request hay không. */
    private function isSpecialField(string $field, SchemaEntity $entity, array $col): bool
    {
        if ($field === $entity->active_field || $field === $entity->weight_field) {
            return true;
        }
        $note = $col['note'] ?? '';
        if (str_contains($note, 'Trạng thái (Active)') || str_contains($note, 'Sắp xếp (Weight)') || str_contains($note, 'Ẩn để schemas-mvc tự động sinh')) {
            return true;
        }
        return false;
    }

    /**
     * Sinh {Item}Entity.php
     */
    private function buildEntity(SchemaEntity $entity, string $mod, string $item): string
    {
        $ns    = "NukeViet\\Module\\{$mod}\\{$item}";
        $props = '';

        foreach ($entity->columns as $field => $col) {
            if ($field === 'id') {
                continue; // PK khai báo riêng bên dưới
            }
            $phpType = $this->phpType($col['sql_type'] ?? 'varchar(255)');
            $default = $this->phpDefault($phpType);
            $label   = $col['label_vi'] ?? $field;
            $props  .= "    /** {$label} */\n";
            $props  .= "    public {$phpType} \${$field} = {$default};\n";
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "use NukeViet\\Module\\Content\\Shared\\AbstractEntity;\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "/**\n * {$item}Entity — Đại diện cho 1 bản ghi bảng {$entity->table}\n */\n";
        $c .= "class {$item}Entity extends AbstractEntity\n{\n";
        $c .= "    protected const VIEW_FIELDS = ['link', 'url_edit', 'checkss'];\n";
        $c .= "    protected const PRIMARY_KEY = 'id';\n\n";
        $c .= "    /** Khóa chính */\n";
        $c .= "    public int \$id = 0;\n";
        $c .= $props;
        $c .= "\n    // ── Thuộc tính View (không có trong DB) ──\n";
        $c .= "    public string \$link = '';\n";
        $c .= "    public string \$url_edit = '';\n";
        $c .= "    public string \$checkss = '';\n\n";
        $c .= "    public function toArray(): array\n    {\n";
        $c .= "        return get_object_vars(\$this);\n";
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh {Item}Repository.php
     */
    private function buildRepository(SchemaEntity $entity, string $mod, string $item): string
    {
        $ns          = "NukeViet\\Module\\{$mod}\\{$item}";
        $activeField = $entity->active_field ?: 'id';

        // Tính toán suffix của bảng dựa vào module name
        $tableSuffix = '';
        $modLc = strtolower($mod);
        if (preg_match('/_' . $modLc . '(_.*)$/i', $entity->table, $matches)) {
            $tableSuffix = $matches[1];
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "use PDO;\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "class {$item}Repository\n{\n";
        $c .= "    private PDO \$db;\n";
        $c .= "    private string \$table;\n";
        $c .= "    private \$cache;\n";
        $c .= "    private string \$module_name;\n\n";
        $c .= "    public function __construct(PDO \$db, string \$baseTable, \$cache, string \$module_name)\n    {\n";
        $c .= "        \$this->db          = \$db;\n";
        $suffixStr = !empty($tableSuffix) ? " . '{$tableSuffix}'" : '';
        $c .= "        \$this->table       = \$baseTable{$suffixStr};\n";
        $c .= "        \$this->cache       = \$cache;\n";
        $c .= "        \$this->module_name = \$module_name;\n";
        $c .= "    }\n\n";

        // fetchEntities helper
        $c .= "    /** Helper: fetchAll → Entity[] */\n";
        $c .= "    private function fetchEntities(\\PDOStatement \$stmt): array\n    {\n";
        $c .= "        return array_map([{$item}Entity::class, 'fromArray'], \$stmt->fetchAll(PDO::FETCH_ASSOC));\n";
        $c .= "    }\n\n";

        // ═══ READ ═══
        $c .= "    // ═══ READ ═══\n\n";
        $c .= "    public function findById(int \$id): ?{$item}Entity\n    {\n";
        $c .= "        \$stmt = \$this->db->prepare('SELECT * FROM ' . \$this->table . ' WHERE id = :id');\n";
        $c .= "        \$stmt->bindValue(':id', \$id, PDO::PARAM_INT);\n";
        $c .= "        \$stmt->execute();\n";
        $c .= "        \$data = \$stmt->fetch(PDO::FETCH_ASSOC);\n";
        $c .= "        \$stmt->closeCursor();\n";
        $c .= "        return \$data ? {$item}Entity::fromArray(\$data) : null;\n";
        $c .= "    }\n\n";

        // getList
        $orderBy = !empty($entity->weight_field) ? $entity->weight_field . ' ASC' : 'id DESC';
        $c .= "    /**\n     * @param int \$status  -1 = tất cả, 0 = ẩn, 1 = hiển thị\n     */\n";
        $c .= "    public function getList(int \$page = 1, int \$perPage = 20, int \$status = -1): array\n    {\n";
        $c .= "        \$sql = 'SELECT * FROM ' . \$this->table;\n";
        if (!empty($entity->active_field)) {
            $c .= "        if (\$status >= 0) {\n";
            $c .= "            \$sql .= ' WHERE {$activeField} = ' . \$status;\n";
            $c .= "        }\n";
        }
        $c .= "        \$sql .= ' ORDER BY {$orderBy}';\n";
        $c .= "        if (\$perPage > 0) {\n";
        $c .= "            \$sql .= ' LIMIT ' . ((\$page - 1) * \$perPage) . ', ' . \$perPage;\n";
        $c .= "        }\n";
        $c .= "        \$stmt = \$this->db->query(\$sql);\n";
        $c .= "        return \$this->fetchEntities(\$stmt);\n";
        $c .= "    }\n\n";

        // getNewWeight
        $weightCol = $entity->weight_field;
        if (empty($weightCol)) {
            foreach ($entity->columns as $f => $col) {
                if (str_contains($col['note'] ?? '', 'Sắp xếp (Weight)')) {
                    $weightCol = $f;
                    break;
                }
            }
        }
        if (!empty($weightCol)) {
            $c .= "    public function getNewWeight(): int\n    {\n";
            $c .= "        \$sql = 'SELECT MAX({$weightCol}) FROM ' . \$this->table;\n";
            $c .= "        return (int) \$this->db->query(\$sql)->fetchColumn() + 1;\n";
            $c .= "    }\n\n";
        }

        // count
        $c .= "    public function count(int \$status = -1): int\n    {\n";
        $c .= "        \$sql = 'SELECT COUNT(*) FROM ' . \$this->table;\n";
        if (!empty($entity->active_field)) {
            $c .= "        if (\$status >= 0) {\n";
            $c .= "            \$sql .= ' WHERE {$activeField} = ' . \$status;\n";
            $c .= "        }\n";
        }
        $c .= "        return (int) \$this->db->query(\$sql)->fetchColumn();\n";
        $c .= "    }\n\n";

        // ═══ WRITE ═══
        $c .= "    // ═══ WRITE ═══\n\n";
        $c .= "    /**\n     * INSERT (\$id=0) hoặc UPDATE (\$id>0).\n     * @return int ID bản ghi\n     */\n";
        $c .= "    public function save(array \$data, int \$id = 0): int\n    {\n";
        $c .= "        \$data = array_intersect_key(\$data, array_flip({$item}Entity::getDbColumns()));\n\n";
        $c .= "        if (\$id > 0) {\n";
        $c .= "            \$fields = [];\n";
        $c .= "            \$params = [':id' => [\$id, PDO::PARAM_INT]];\n";
        $c .= "            foreach (\$data as \$key => \$value) {\n";
        $c .= "                \$fields[] = \$key . ' = :' . \$key;\n";
        $c .= "                \$params[':' . \$key] = [\$value, is_int(\$value) ? PDO::PARAM_INT : PDO::PARAM_STR];\n";
        $c .= "            }\n";
        $c .= "            \$stmt = \$this->db->prepare('UPDATE ' . \$this->table . ' SET ' . implode(', ', \$fields) . ' WHERE id = :id');\n";
        $c .= "            foreach (\$params as \$k => \$v) {\n";
        $c .= "                \$stmt->bindValue(\$k, \$v[0], \$v[1]);\n";
        $c .= "            }\n";
        $c .= "            \$stmt->execute();\n";
        $c .= "            return \$id;\n";
        $c .= "        }\n\n";
        $c .= "        \$columns      = array_keys(\$data);\n";
        $c .= "        \$placeholders = array_map(fn(\$k) => ':' . \$k, \$columns);\n";
        $c .= "        \$stmt = \$this->db->prepare(\n";
        $c .= "            'INSERT INTO ' . \$this->table . ' (' . implode(', ', \$columns) . ') VALUES (' . implode(', ', \$placeholders) . ')'\n";
        $c .= "        );\n";
        $c .= "        foreach (\$data as \$key => \$value) {\n";
        $c .= "            \$stmt->bindValue(':' . \$key, \$value, is_int(\$value) ? PDO::PARAM_INT : PDO::PARAM_STR);\n";
        $c .= "        }\n";
        $c .= "        \$stmt->execute();\n";
        $c .= "        return (int) \$this->db->lastInsertId();\n";
        $c .= "    }\n\n";

        // delete
        $c .= "    public function delete(int \$id): bool\n    {\n";
        $c .= "        \$stmt = \$this->db->prepare('DELETE FROM ' . \$this->table . ' WHERE id = :id');\n";
        $c .= "        \$stmt->bindValue(':id', \$id, PDO::PARAM_INT);\n";
        $c .= "        return \$stmt->execute();\n";
        $c .= "    }\n\n";

        // toggleStatus nếu có active_field
        if (!empty($entity->active_field)) {
            $c .= "    public function toggleStatus(int \$id): int\n    {\n";
            $c .= "        \$row = \$this->findById(\$id);\n";
            $c .= "        if (!\$row) { return -1; }\n";
            $c .= "        \$newStatus = \$row->{$activeField} ? 0 : 1;\n";
            $c .= "        \$stmt = \$this->db->prepare('UPDATE ' . \$this->table . ' SET {$activeField} = :s WHERE id = :id');\n";
            $c .= "        \$stmt->bindValue(':s', \$newStatus, PDO::PARAM_INT);\n";
            $c .= "        \$stmt->bindValue(':id', \$id, PDO::PARAM_INT);\n";
            $c .= "        \$stmt->execute();\n";
            $c .= "        return \$newStatus;\n";
            $c .= "    }\n\n";
        }

        // invalidateCache
        $c .= "    public function invalidateCache(): void\n    {\n";
        $c .= "        \$this->cache->delMod(\$this->module_name);\n";
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh {Item}Service.php
     */
    private function buildService(SchemaEntity $entity, string $mod, string $item, string $itemLc): string
    {
        $ns = "NukeViet\\Module\\{$mod}\\{$item}";

        // Sinh các dòng collectRequestData()
        $collectLines = '';
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || $this->isSpecialField((string) $field, $entity, $col)) {
                continue;
            }
            $method = $this->requestMethod($col);
            $collectLines .= match ($method) {
                'get_int'      => "        \$row['{$field}'] = \$nv_Request->get_int('{$field}', 'post', 0);\n",
                'get_float'    => "        \$row['{$field}'] = (float) \$nv_Request->get_string('{$field}', 'post', '0');\n",
                'get_textarea' => "        \$row['{$field}'] = \$nv_Request->get_textarea('{$field}', '', 'br', 1);\n",
                'get_editor'   => "        \$row['{$field}'] = \$nv_Request->get_editor('{$field}', '', NV_ALLOWED_HTML_TAGS);\n",
                default        => "        \$row['{$field}'] = \$nv_Request->get_title('{$field}', 'post', '');\n",
            };
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "class {$item}Service\n{\n";
        $c .= "    public function __construct(private {$item}Repository \$repo) {}\n\n";

        // collectRequestData
        $c .= "    /**\n     * Thu thập dữ liệu từ Request (Admin & API dùng chung)\n     */\n";
        $c .= "    public function collectRequestData(\$nv_Request): array\n    {\n";
        $c .= "        \$row = [];\n";
        $c .= $collectLines;
        $c .= "        return \$row;\n";
        $c .= "    }\n\n";

        // Auto-set AI fields
        $autoSetLines = "";
        foreach ($entity->columns as $field => $col) {
            $note = $col['note'] ?? '';
            if ($field === $entity->active_field || str_contains($note, 'Trạng thái (Active)')) {
                $autoSetLines .= "        if (empty(\$id)) {\n            \$data['{$field}'] = 1;\n        }\n";
            } elseif ($field === $entity->weight_field || str_contains($note, 'Sắp xếp (Weight)')) {
                $autoSetLines .= "        if (empty(\$id)) {\n            \$data['{$field}'] = \$this->repo->getNewWeight();\n        }\n";
            } elseif (str_contains($note, 'Ẩn để schemas-mvc tự động sinh')) {
                if ($field === 'admin_id') {
                    $autoSetLines .= "        if (empty(\$id)) {\n            \$data['{$field}'] = \$admin_id;\n        }\n";
                } elseif ($field === 'add_time') {
                    $autoSetLines .= "        if (empty(\$id)) {\n            \$data['{$field}'] = NV_CURRENTTIME;\n        }\n";
                } elseif ($field === 'edit_time') {
                    $autoSetLines .= "        \$data['{$field}'] = NV_CURRENTTIME;\n";
                } elseif ($field === 'hitstotal') {
                    $autoSetLines .= "        if (empty(\$id)) {\n            \$data['{$field}'] = 0;\n        }\n";
                }
            }
        }

        // save{Item}
        $c .= "    /**\n     * Lưu item (INSERT hoặc UPDATE). Data đã được Validator kiểm tra.\n     */\n";
        $c .= "    public function save{$item}(array \$data, int \$id, string \$module_name, int \$admin_id = 0): int\n    {\n";
        if (!empty($autoSetLines)) {
            $c .= $autoSetLines;
        }
        $c .= "        \$data = nv_apply_hook(\$module_name, 'before_{$itemLc}_save', [\$data], \$data);\n";
        $c .= "        \$savedId = \$this->repo->save(\$data, \$id);\n";
        $c .= "        \$this->repo->invalidateCache();\n";
        $c .= "        nv_apply_hook(\$module_name, '{$itemLc}_saved', ['id' => \$savedId, 'action' => \$id ? 'edit' : 'add']);\n";
        $c .= "        return \$savedId;\n";
        $c .= "    }\n\n";

        // delete{Item}
        $c .= "    /**\n     * Xóa item.\n     */\n";
        $c .= "    public function delete{$item}(int \$id, string \$module_name): bool\n    {\n";
        $c .= "        \$row = \$this->repo->findById(\$id);\n";
        $c .= "        if (!\$row) { return false; }\n";
        $c .= "        \$result = \$this->repo->delete(\$id);\n";
        $c .= "        if (\$result) {\n";
        $c .= "            \$this->repo->invalidateCache();\n";
        $c .= "            nv_apply_hook(\$module_name, '{$itemLc}_deleted', ['id' => \$id]);\n";
        $c .= "        }\n";
        $c .= "        return \$result;\n";
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh {Item}Validator.php
     */
    private function buildValidator(SchemaEntity $entity, string $mod, string $item): string
    {
        $ns = "NukeViet\\Module\\{$mod}\\{$item}";

        $checks    = '';
        $errorCode = 0;
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || $this->isSpecialField((string) $field, $entity, $col)) {
                continue;
            }
            if (empty($col['required'])) {
                continue;
            }
            $errorCode++;
            $phpType = $this->phpType($col['sql_type'] ?? 'varchar(255)');
            $langKey = "empty_{$field}";
            if ($phpType === 'int') {
                $checks .= "        if ((\$data['{$field}'] ?? 0) <= 0) {\n";
                $checks .= "            throw new \\InvalidArgumentException('{$langKey}', {$errorCode});\n";
                $checks .= "        }\n";
            } else {
                $checks .= "        if (empty(\$data['{$field}'])) {\n";
                $checks .= "            throw new \\InvalidArgumentException('{$langKey}', {$errorCode});\n";
                $checks .= "        }\n";
            }
        }

        if (empty($checks)) {
            $checks = "        // TODO: Thêm các kiểm tra validation tại đây\n";
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "/**\n * {$item}Validator — Ném \\InvalidArgumentException kèm error code.\n";
        $c .= " * Error code → field map phải khớp với Controller.\n */\n";
        $c .= "class {$item}Validator\n{\n";
        $c .= "    public function __construct(private {$item}Repository \$repo) {}\n\n";
        $c .= "    /**\n     * @throws \\InvalidArgumentException với message = lang key, code = số thứ tự field\n     */\n";
        $c .= "    public function validateSave(array \$data, int \$excludeId = 0): void\n    {\n";
        $c .= $checks;
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh admin/{item}.php (Controller)
     */
    private function buildController(SchemaEntity $entity, string $mod, string $item, string $itemLc): string
    {
        // Sinh mảng $row mặc định cho form thêm mới
        $defaultRowLines = "    \$row = [\n";
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || $this->isSpecialField((string) $field, $entity, $col)) {
                continue;
            }
            $phpType = $this->phpType($col['sql_type'] ?? 'varchar(255)');
            $default = $phpType === 'int' ? '0' : "''";
            $defaultRowLines .= "        '{$field}' => {$default},\n";
        }
        $defaultRowLines .= "    ];";

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "if (!defined('NV_IS_FILE_ADMIN')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "use NukeViet\\Module\\{$mod}\\{$item}\\{$item}Repository;\n";
        $c .= "use NukeViet\\Module\\{$mod}\\{$item}\\{$item}Service;\n";
        $c .= "use NukeViet\\Module\\{$mod}\\{$item}\\{$item}Validator;\n\n";
        $c .= "\$itemRepo = new {$item}Repository(\$db, NV_PREFIXLANG . '_' . \$module_data, \$nv_Cache, \$module_name);\n";
        $c .= "\$service  = new {$item}Service(\$itemRepo);\n\n";
        $c .= "\$id     = \$nv_Request->get_int('id', 'post,get', 0);\n";
        $c .= "\$entity = null;\n\n";
        $c .= "if (\$id) {\n";
        $c .= "    \$entity = \$itemRepo->findById(\$id);\n";
        $c .= "    if (empty(\$entity)) {\n";
        $c .= "        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA\n";
        $c .= "            . '&' . NV_NAME_VARIABLE . '=' . \$module_name);\n";
        $c .= "    }\n";
        $c .= "    \$page_title = \$nv_Lang->getModule('edit');\n";
        $c .= "} else {\n";
        $c .= "    \$page_title = \$nv_Lang->getModule('add');\n";
        $c .= "}\n\n";
        $c .= "// ══════ XỬ LÝ POST (AJAX) ══════\n";
        $c .= "if (\$nv_Request->isset_request('checkss', 'post')) {\n";
        $c .= "    if (!csrf_check(\$nv_Request->get_string('checkss', 'post'), \$csrf_key)) {\n";
        $c .= "        nv_jsonOutput(['status' => 'error', 'mess' => \$nv_Lang->getGlobal('error_checkss')]);\n";
        $c .= "    }\n\n";
        $c .= "    \$respon = ['status' => 'error', 'mess' => ''];\n";
        $c .= "    \$row    = \$service->collectRequestData(\$nv_Request);\n\n";
        $c .= "    try {\n";
        $c .= "        \$saveId    = \$id ?: 0;\n";
        $c .= "        \$validator = new {$item}Validator(\$itemRepo);\n";
        $c .= "        \$validator->validateSave(\$row, \$saveId);\n";
        $c .= "        \$savedId = \$service->save{$item}(\$row, \$saveId, \$module_name, \$admin_info['admin_id']);\n";
        $c .= "        nv_insert_logs(NV_LANG_DATA, \$module_name, \$saveId ? 'Edit' : 'Add', 'ID: ' . \$savedId, \$admin_info['userid']);\n";
        $c .= "    } catch (\\InvalidArgumentException \$e) {\n";
        $c .= "        \$respon['mess'] = \$nv_Lang->getModule(\$e->getMessage());\n";
        $c .= "        nv_jsonOutput(\$respon);\n";
        $c .= "    } catch (\\Throwable \$e) {\n";
        $c .= "        trigger_error(\$e);\n";
        $c .= "        \$respon['mess'] = \$nv_Lang->getGlobal('error_system');\n";
        $c .= "        nv_jsonOutput(\$respon);\n";
        $c .= "    }\n\n";
        $c .= "    \$respon['status']   = 'success';\n";
        $c .= "    \$respon['mess']     = \$nv_Lang->getGlobal('save_success');\n";
        $c .= "    \$respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA\n";
        $c .= "        . '&' . NV_NAME_VARIABLE . '=' . \$module_name;\n";
        $c .= "    nv_jsonOutput(\$respon);\n\n";
        $c .= "} elseif (empty(\$id)) {\n";
        $c .= "    // Dữ liệu mặc định form thêm mới\n";
        $c .= $defaultRowLines . "\n";
        $c .= "}\n\n";
        $c .= "// ══════ RENDER FORM ══════\n";
        $c .= "\$row_data = isset(\$entity) ? \$entity->toArray() : \$row;\n\n";
        $c .= "\$tpl = new \\NukeViet\\Template\\NVSmarty();\n";
        $c .= "\$tpl->setTemplateDir(get_module_tpl_dir('{$itemLc}.tpl'));\n";
        $c .= "\$tpl->assign('LANG', \$nv_Lang);\n";
        $c .= "\$tpl->assign('MODULE_NAME', \$module_name);\n";
        $c .= "\$tpl->assign('OP', \$op);\n";
        $c .= "\$tpl->assign('ID', \$id);\n";
        $c .= "\$tpl->assign('DATA', \$row_data);\n";
        $c .= "\$tpl->assign('CHECKSS', csrf_create(\$csrf_key));\n";
        $c .= "\$tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);\n";
        $c .= "\$tpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);\n";
        $c .= "\$tpl->assign('NV_LANG_DATA', NV_LANG_DATA);\n";
        $c .= "\$tpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);\n";
        $c .= "\$tpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);\n\n";
        $c .= "\$contents = \$tpl->fetch('{$itemLc}.tpl');\n\n";
        $c .= "include NV_ROOTDIR . '/includes/header.php';\n";
        $c .= "echo nv_admin_theme(\$contents);\n";
        $c .= "include NV_ROOTDIR . '/includes/footer.php';\n";

        return $c;
    }

    /**
     * Sinh admin template {item}.tpl (Smarty)
     */
    private function buildTemplate(SchemaEntity $entity, string $mod, string $item, string $itemLc): string
    {
        $formFields = '';
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || !empty($col['hidden']) || $this->isSpecialField((string) $field, $entity, $col)) {
                continue; // ID, Cột ẩn hoặc Cột AI tự động sinh thì ẩn khỏi Form
            }

            $label     = htmlspecialchars($col['label_vi'] ?? $field, ENT_QUOTES);
            $req       = !empty($col['required']) ? ' <span class="text-danger">*</span>' : '';
            $fieldHtml = $this->buildFormField($field, $col);

            $formFields .= "            <div class=\"mb-3\">\n";
            $formFields .= "                <label class=\"form-label fw-semibold\">{$label}{$req}</label>\n";
            $formFields .= "                {$fieldHtml}\n";
            $formFields .= "            </div>\n";
        }

        $c  = "<form action=\"{\$NV_BASE_ADMINURL}index.php\" method=\"post\" class=\"ajax-submit\">\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_LANG_VARIABLE}\" value=\"{\$NV_LANG_DATA}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_NAME_VARIABLE}\" value=\"{\$MODULE_NAME}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_OP_VARIABLE}\" value=\"{$itemLc}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"id\" value=\"{\$ID}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"checkss\" value=\"{\$CHECKSS}\" />\n\n";
        $c .= "    <div class=\"card shadow-sm border-0 mb-4\">\n";
        $c .= "        <div class=\"card-header bg-primary text-white\">\n";
        $c .= "            <h5 class=\"mb-0\">{if \$ID}{\$LANG->getModule('edit')}{else}{\$LANG->getModule('add')}{/if}</h5>\n";
        $c .= "        </div>\n";
        $c .= "        <div class=\"card-body\">\n";
        $c .= $formFields;
        $c .= "        </div>\n";
        $c .= "        <div class=\"card-footer d-flex gap-3\">\n";
        $c .= "            <button type=\"submit\" class=\"btn btn-primary\">\n";
        $c .= "                <i class=\"fa-solid fa-save me-1\"></i>{\$LANG->getGlobal('save')}\n";
        $c .= "            </button>\n";
        $c .= "            <a href=\"{\$NV_BASE_ADMINURL}index.php?{\$NV_LANG_VARIABLE}={\$NV_LANG_DATA}&{\$NV_NAME_VARIABLE}={\$MODULE_NAME}\" class=\"btn btn-outline-secondary\">\n";
        $c .= "                <i class=\"fa-solid fa-arrow-left me-1\"></i>{\$LANG->getModule('back')}\n";
        $c .= "            </a>\n";
        $c .= "        </div>\n";
        $c .= "    </div>\n";
        $c .= "</form>\n";

        return $c;
    }

    /**
     * Sinh HTML cho 1 form field theo view_type.
     */
    private function buildFormField(string $field, array $col): string
    {
        $vt = $col['view_type'] ?? 'textbox';

        return match ($vt) {
            'textarea'     => "<textarea name=\"{$field}\" class=\"form-control\" rows=\"5\">{\$DATA.{$field}}</textarea>",
            'editor'       => "<textarea name=\"{$field}\" class=\"form-control nv-editor\" rows=\"10\">{\$DATA.{$field}}</textarea>",
            'number_int'   => "<input type=\"number\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'number_float' => "<input type=\"number\" name=\"{$field}\" class=\"form-control\" step=\"0.01\" value=\"{\$DATA.{$field}}\">",
            'checkbox'     => "<div class=\"form-check\"><input type=\"checkbox\" name=\"{$field}\" class=\"form-check-input\" value=\"1\" {if \$DATA.{$field}}checked{/if}></div>",
            'date'         => "<input type=\"date\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'time'         => "<input type=\"datetime-local\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'email'        => "<input type=\"email\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'url'          => "<input type=\"url\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'password'     => "<input type=\"password\" name=\"{$field}\" class=\"form-control\">",
            'select'       => $this->buildSelectField($field, $col),
            'radio'        => $this->buildRadioField($field, $col),
            default        => "<input type=\"text\" name=\"{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
        };
    }

    private function buildSelectField(string $field, array $col): string
    {
        $choiceType = $col['choice_type'] ?? 'static';

        if ($choiceType === 'sql') {
            $table   = htmlspecialchars($col['choice_table'] ?? '', ENT_QUOTES);
            $idCol   = htmlspecialchars($col['choice_id_col'] ?? 'id', ENT_QUOTES);
            $textCol = htmlspecialchars($col['choice_text_col'] ?? 'name', ENT_QUOTES);
            return "{* TODO: Gán \$CHOICES_{$field} trong Controller từ bảng {$table} (id={$idCol}, text={$textCol}) *}\n"
                . "                <select name=\"{$field}\" class=\"form-select\">\n"
                . "                    <option value=\"\">-- Chọn --</option>\n"
                . "                    {foreach from=\$CHOICES_{$field} item=opt}\n"
                . "                    <option value=\"{\$opt.{$idCol}}\" {if \$DATA.{$field}==\$opt.{$idCol}}selected{/if}>{\$opt.{$textCol}}</option>\n"
                . "                    {/foreach}\n"
                . "                </select>";
        }

        // Static: parse choice_values (mỗi dòng: key:Nhãn)
        $values  = $col['choice_values'] ?? '';
        $options = "<option value=\"\">-- Chọn --</option>\n";
        foreach (explode("\n", $values) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = explode(':', $line, 2);
            $k     = htmlspecialchars(trim($parts[0]), ENT_QUOTES);
            $v     = htmlspecialchars(trim($parts[1] ?? $k), ENT_QUOTES);
            $options .= "                    <option value=\"{$k}\" {if \$DATA.{$field}=={$k}}selected{/if}>{$v}</option>\n";
        }

        return "<select name=\"{$field}\" class=\"form-select\">\n"
            . "                    {$options}"
            . "                </select>";
    }

    private function buildRadioField(string $field, array $col): string
    {
        $choiceType = $col['choice_type'] ?? 'static';

        if ($choiceType === 'sql') {
            $table   = htmlspecialchars($col['choice_table'] ?? '', ENT_QUOTES);
            $idCol   = htmlspecialchars($col['choice_id_col'] ?? 'id', ENT_QUOTES);
            $textCol = htmlspecialchars($col['choice_text_col'] ?? 'name', ENT_QUOTES);
            return "{* TODO: Gán \$CHOICES_{$field} trong Controller từ bảng {$table} (id={$idCol}, text={$textCol}) *}\n"
                . "                {foreach from=\$CHOICES_{$field} item=opt}\n"
                . "                <div class=\"form-check form-check-inline\">\n"
                . "                    <input type=\"radio\" name=\"{$field}\" class=\"form-check-input\" value=\"{\$opt.{$idCol}}\" {if \$DATA.{$field}==\$opt.{$idCol}}checked{/if}>\n"
                . "                    <label class=\"form-check-label\">{\$opt.{$textCol}}</label>\n"
                . "                </div>\n"
                . "                {/foreach}";
        }

        $values = $col['choice_values'] ?? '';
        $radios = '';
        foreach (explode("\n", $values) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts  = explode(':', $line, 2);
            $k      = htmlspecialchars(trim($parts[0]), ENT_QUOTES);
            $v      = htmlspecialchars(trim($parts[1] ?? $k), ENT_QUOTES);
            $radios .= "                <div class=\"form-check form-check-inline\">\n";
            $radios .= "                    <input type=\"radio\" name=\"{$field}\" class=\"form-check-input\" value=\"{$k}\" {if \$DATA.{$field}=={$k}}checked{/if}>\n";
            $radios .= "                    <label class=\"form-check-label\">{$v}</label>\n";
            $radios .= "                </div>\n";
        }

        return $radios ?: "<div class=\"text-muted small\">Chưa cấu hình giá trị cho {$field}</div>";
    }
}
