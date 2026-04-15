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

        // Đảm bảo có thư mục Shared và các file nền tảng
        $this->addSharedFiles($files, $mod, $nvRootDir);

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Service.php",
            $this->buildService($entity, $mod, $item, $itemLc), $nvRootDir);

        $this->addFile($files, "modules/{$mod}/{$item}/{$item}Validator.php",
            $this->buildValidator($entity, $mod, $item), $nvRootDir);

        if ($entity->layout_type !== 'mvc_only') {
            if ($entity->layout_type === 'list_and_form') {
                // List (op chính)
                $this->addFile($files, "modules/{$mod}/admin/{$itemLc}.php",
                    $this->buildListController($entity, $mod, $item, $itemLc, $itemLc . '-form'), $nvRootDir);
                $this->addFile($files, "themes/admin_future/modules/{$mod}/{$itemLc}.tpl",
                    $this->buildListTemplate($entity, $mod, $item, $itemLc, $itemLc . '-form'), $nvRootDir);
                
                // Form (op phụ)
                $this->addFile($files, "modules/{$mod}/admin/{$itemLc}-form.php",
                    $this->buildController($entity, $mod, $item, $itemLc . '-form', $itemLc), $nvRootDir);
                $this->addFile($files, "themes/admin_future/modules/{$mod}/{$itemLc}-form.tpl",
                    $this->buildTemplate($entity, $mod, $item, $itemLc . '-form', $itemLc), $nvRootDir);

                // Metadata: List có menu, Form thì KHÔNG có menu
                $this->addMetadataFiles($files, $entity, $mod, $item, $itemLc, $nvRootDir, false);
                $this->addMetadataFiles($files, $entity, $mod, $item, $itemLc . '-form', $nvRootDir, true);
            } else {
                $this->addFile($files, "modules/{$mod}/admin/{$itemLc}.php",
                    $this->buildController($entity, $mod, $item, $itemLc, $itemLc), $nvRootDir);
                $this->addFile($files, "themes/admin_future/modules/{$mod}/{$itemLc}.tpl",
                    $this->buildTemplate($entity, $mod, $item, $itemLc, $itemLc), $nvRootDir);

                $this->addMetadataFiles($files, $entity, $mod, $item, $itemLc, $nvRootDir, false);
            }
        }

        return $files;
    }

    /**
     * Cập nhật các file admin.functions.php, admin.menu.php và language/vi.php.
     */
    private function addMetadataFiles(array &$files, SchemaEntity $entity, string $mod, string $item, string $itemLc, string $nvRootDir, bool $skipMenu = false): void
    {
        // 1. admin.functions.php
        $funcFile = "modules/{$mod}/admin.functions.php";
        $fullFuncPath = rtrim($nvRootDir, '/\\') . '/' . $funcFile;
        if (file_exists($fullFuncPath)) {
            $content = file_get_contents($fullFuncPath);
            $changed = false;

            // Regex kiểm tra xem 'func' đã có trong mảng chưa (chính xác từng ký tự)
            if (!preg_match("/['\"]" . preg_quote($itemLc) . "['\"]/i", $content)) {
                // Thêm vào mảng $allow_func
                $content = preg_replace('/(\$allow_func\s*=\s*\[)/i', "$1\n    '{$itemLc}',", $content);
                $changed = true;
            }

            // Kiểm tra và nạp config nếu chưa có
            if (strpos($content, '$module_config') === false && strpos($content, '$config = $module_config[$module_name]') === false) {
                $configLine = "\n// Lấy cấu hình module từ biến hệ thống\n\$config = \$module_config[\$module_name];\n";
                if (strpos($content, 'define(\'NV_IS_FILE_ADMIN\'') !== false) {
                    $content = preg_replace('/(define\(\'NV_IS_FILE_ADMIN\', true\);)/', "$1\n$configLine", $content);
                } else {
                    $content .= $configLine;
                }
                $changed = true;
            }

            if ($changed) {
                $this->addFile($files, $funcFile, $content, $nvRootDir);
            }
        }

        // 2. admin.menu.php
        if (!$skipMenu) {
            $menuFile = "modules/{$mod}/admin.menu.php";
            $fullMenuPath = rtrim($nvRootDir, '/\\') . '/' . $menuFile;
            if (file_exists($fullMenuPath)) {
                $content = file_get_contents($fullMenuPath);
                // Kiểm tra chính xác key của submenu [ 'op' ] hoặc ["op"]
                if (!preg_match("/\\\$submenu\s*\[\s*['\"]" . preg_quote($itemLc) . "['\"]\s*\]/i", $content)) {
                    $inject = "\$submenu['{$itemLc}'] = \$nv_Lang->getModule('{$itemLc}');\n";
                    $lines = explode("\n", $content);
                    $lastSubmenuIndex = -1;
                    $firstIfIndex = -1;

                    foreach ($lines as $i => $line) {
                        // Chỉ tính dòng $submenu ở cấp độ ROOT (không có khoảng trắng phía trước)
                        if (str_starts_with($line, '$submenu[')) {
                            $lastSubmenuIndex = $i;
                        }
                        // Chỉ tính dòng if ở cấp độ ROOT
                        if ($firstIfIndex === -1 && str_starts_with($line, 'if')) {
                            $firstIfIndex = $i;
                        }
                    }

                    if ($lastSubmenuIndex >= 0) {
                        // Chèn sau dòng submenu cuối cùng ở root
                        array_splice($lines, $lastSubmenuIndex + 1, 0, $inject);
                        $newContent = implode("\n", $lines);
                    } elseif ($firstIfIndex >= 0) {
                        // Chèn trước block if đầu tiên
                        array_splice($lines, $firstIfIndex, 0, $inject);
                        $newContent = implode("\n", $lines);
                    } else {
                        // Cuối file
                        $newContent = $content . "\n" . $inject;
                    }
                    if ($newContent !== $content) {
                        $this->addFile($files, $menuFile, $newContent, $nvRootDir);
                    }
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

        // 4. action_mysql.php
        $actionFile = "modules/{$mod}/action_mysql.php";
        $fullActionPath = rtrim($nvRootDir, '/\\') . '/' . $actionFile;
        if (file_exists($fullActionPath)) {
            $content = file_get_contents($fullActionPath);

            $tableSuffix = '';
            $modLc = strtolower($mod);
            if (preg_match('/_' . $modLc . '(_.*)$/i', $entity->table, $matches)) {
                $tableSuffix = $matches[1];
            }
            $configKey = 'table_row';
            if ($tableSuffix === '_cat') {
                $configKey = 'table_cat';
            } elseif (!empty($tableSuffix)) {
                $configKey = 'table' . $tableSuffix;
            }

            // 4.1 Chèn DROP TABLE
            if (!str_contains($content, "DROP TABLE IF EXISTS ' . \$db_config['prefix'] . '_' . \$lang . '_' . \$module_data . '{$tableSuffix};'")) {
                $dropLine = "\$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . \$db_config['prefix'] . '_' . \$lang . '_' . \$module_data . '{$tableSuffix};';";
                $pattern = "/^\\\$sql_drop_module\\[\\]\\s*=\\s*['\"].*?DROP TABLE IF EXISTS.*?['\"];/im";
                if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    $last = end($matches[0]);
                    $pos = $last[1] + strlen($last[0]);
                    $content = substr($content, 0, $pos) . "\n" . $dropLine . substr($content, $pos);
                }
            }

            // 4.2 Chèn CREATE TABLE
            if (!str_contains($content, "CREATE TABLE ' . \$db_config['prefix'] . '_' . \$lang . '_' . \$module_data . '{$tableSuffix}")) {
                $colSql = [];
                $pks = [];
                $uniques = [];
                foreach ($entity->columns as $field => $col) {
                    $line = "    {$field} " . ($col['sql_type'] ?? 'varchar(255)');
                    if (!empty($col['required'])) {
                        $line .= " NOT NULL";
                    }
                    if (isset($col['default']) && (string) $col['default'] !== '') {
                        if (strtoupper((string) $col['default']) === 'AUTO_INCREMENT') {
                            $line .= " AUTO_INCREMENT";
                        } else {
                            $line .= " DEFAULT '" . $col['default'] . "'";
                        }
                    }
                    $colSql[] = $line;
                    if (!empty($col['primary'])) {
                        $pks[] = $field;
                    }
                    if (!empty($col['unique'])) {
                        $uniques[] = "    UNIQUE KEY {$field} ({$field})";
                    }
                }
                if (!empty($pks)) {
                    $colSql[] = "    PRIMARY KEY (" . implode(', ', $pks) . ")";
                }
                $colSql = array_merge($colSql, $uniques);

                $createSql = "\n\$sql_create_module[] = 'CREATE TABLE ' . \$db_config['prefix'] . '_' . \$lang . '_' . \$module_data . \"{$tableSuffix} (\n";
                $createSql .= implode(",\n", $colSql) . "\n";
                $createSql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci\";\n";

                $pattern = "/^\\\$sql_create_module\\[\\]\\s*=\\s*['\"]CREATE TABLE.*?[\"'];/ism";
                if (preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                    $last = end($matches[0]);
                    $pos = $last[1] + strlen($last[0]);
                    $content = substr($content, 0, $pos) . "\n" . $createSql . substr($content, $pos);
                }
            }

            // 4.3 Chèn cấu hình bảng vào INSERT block
            if ($configKey !== 'table_row' && $configKey !== 'table_cat' && !str_contains($content, "'{$configKey}'")) {
                $inject = "    ('\" . \$lang . \"', '\" . \$module_name . \"', '{$configKey}', '\" . \$db_config['prefix'] . \"_\" . \$lang . \"_\" . \$module_data . \"{$tableSuffix}'),";
                if (preg_match("/(['\"]table_row['\"].*?,\n)/i", $content, $m)) {
                    $content = str_replace($m[1], $m[1] . $inject . "\n", $content);
                } elseif (preg_match("/(['\"]table_cat['\"].*?,\n)/i", $content, $m)) {
                    $content = str_replace($m[1], $m[1] . $inject . "\n", $content);
                }
            }
            $this->addFile($files, $actionFile, $content, $nvRootDir);
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
         * @Copyright (C) 2026 VINADES.,JSC. All rights reserved
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
        // Các trường hệ thống quản lý ngầm
        if (in_array($field, ['add_time', 'edit_time', 'admin_id', 'hitstotal'])) {
            return true;
        }
        
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
            $comment = (strtolower((string) $label) !== strtolower((string) $field)) ? " // {$label}" : '';
            $props  .= "    public {$phpType} \${$field} = {$default};{$comment}\n";
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "use NukeViet\\Module\\{$mod}\\Shared\\AbstractEntity;\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "/**\n * {$item}Entity — Đại diện cho 1 bản ghi bảng {$entity->table}\n */\n";
        $c .= "class {$item}Entity extends AbstractEntity\n{\n";
        $c .= "    protected const VIEW_FIELDS = ['link', 'url_edit', 'checkss'];\n";
        $c .= "    protected const PRIMARY_KEY = 'id';\n\n";
        $c .= "    public int \$id = 0; // Khóa chính\n";
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

        // Xác định cột weight
        $weightCol = $entity->weight_field;
        if (empty($weightCol)) {
            foreach ($entity->columns as $f => $col) {
                if (str_contains($col['note'] ?? '', 'Sắp xếp (Weight)')) {
                    $weightCol = $f;
                    break;
                }
            }
        }

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
        $c .= "    private string \$module_name;\n";
        $c .= "    private string \$weightField = '{$weightCol}';\n\n";
        $c .= "    public function __construct(PDO \$db, string \$table, \$cache, string \$module_name)\n    {\n";
        $c .= "        \$this->db          = \$db;\n";
        $c .= "        \$this->table       = \$table;\n";
        $c .= "        \$this->cache       = \$cache;\n";
        $c .= "        \$this->module_name = \$module_name;\n";
        $c .= "    }\n\n";
        $c .= "    /**\n     * Lưu cấu hình module vào bảng dùng chung\n     */\n";
        $c .= "    public function saveConfig(array \$config): void\n    {\n";
        $c .= "        \$sth = \$this->db->prepare('UPDATE ' . NV_CONFIG_GLOBALTABLE . \" SET config_value = :config_value WHERE lang = '\" . NV_LANG_DATA . \"' AND module = :module_name AND config_name = :config_name\");\n";
        $c .= "        \$sth->bindValue(':module_name', \$this->module_name, PDO::PARAM_STR);\n";
        $c .= "        foreach (\$config as \$config_name => \$config_value) {\n";
        $c .= "            \$sth->bindValue(':config_name', \$config_name, PDO::PARAM_STR);\n";
        $c .= "            \$sth->bindValue(':config_value', (string) \$config_value, PDO::PARAM_STR);\n";
        $c .= "            \$sth->execute();\n";
        $c .= "        }\n\n";
        $c .= "        \$this->cache->delMod('settings');\n";
        $c .= "        \$this->cache->delMod(\$this->module_name);\n";
        $c .= "    }\n\n";

        // fetchEntities helper
        $c .= "    /** Helper: fetchAll → Entity[] */\n";
        $c .= "    private function fetchEntities(\\PDOStatement \$stmt): array\n    {\n";
        $c .= "        return array_map([{$item}Entity::class, 'fromArray'], \$stmt->fetchAll(PDO::FETCH_ASSOC));\n";
        $c .= "    }\n\n";

        $c .= "    /**\n     * Xác định PDO type cho 1 cột dựa theo khai báo Entity.\n     */\n";
        $c .= "    private function pdoType(string \$field): int\n    {\n";
        $c .= "        static \$intFields = null;\n";
        $c .= "        if (\$intFields === null) {\n";
        $c .= "            \$intFields = {$item}Entity::getIntColumns();\n";
        $c .= "        }\n";
        $c .= "        return isset(\$intFields[\$field]) ? PDO::PARAM_INT : PDO::PARAM_STR;\n";
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
        $c .= "    /**\n     * @param int \$status  -1 = tất cả, 0 = ẩn, 1 = hiển thị\n     */\n";
        $c .= "    public function getList(int \$page = 1, int \$perPage = 20, int \$status = -1, string \$q = ''): array\n    {\n";
        $c .= "        \$where = [];\n";
        $c .= "        if (!empty(\$this->weightField) && \$status >= 0) {\n";
        $c .= "            \$where[] = '{$activeField} = ' . (int) \$status;\n";
        $c .= "        }\n";
        $c .= "        if (!empty(\$q)) {\n";
        $c .= "            \$where[] = \"(title LIKE \" . \$this->db->quote('%' . \$q . '%') . \" OR alias LIKE \" . \$this->db->quote('%' . \$q . '%') . \")\";\n";
        $c .= "        }\n";
        $c .= "        \$sql = 'SELECT * FROM ' . \$this->table;\n";
        $c .= "        if (!empty(\$where)) {\n";
        $c .= "            \$sql .= ' WHERE ' . implode(' AND ', \$where);\n";
        $c .= "        }\n";
        $c .= "        \$sql .= ' ORDER BY ' . (\$this->weightField ?: 'id DESC');\n";
        $c .= "        if (\$perPage > 0) {\n";
        $c .= "            \$sql .= ' LIMIT ' . ((\$page - 1) * \$perPage) . ', ' . \$perPage;\n";
        $c .= "        }\n";
        $c .= "        \$stmt = \$this->db->query(\$sql);\n";
        $c .= "        return \$this->fetchEntities(\$stmt);\n";
        $c .= "    }\n\n";

        // count
        $c .= "    public function count(int \$status = -1, string \$q = ''): int\n    {\n";
        $c .= "        \$where = [];\n";
        $c .= "        if (!empty(\$this->weightField) && \$status >= 0) {\n";
        $c .= "            \$where[] = '{$activeField} = ' . (int) \$status;\n";
        $c .= "        }\n";
        $c .= "        if (!empty(\$q)) {\n";
        $c .= "            \$where[] = \"(title LIKE \" . \$this->db->quote('%' . \$q . '%') . \" OR alias LIKE \" . \$this->db->quote('%' . \$q . '%') . \")\";\n";
        $c .= "        }\n";
        $c .= "        \$sql = 'SELECT COUNT(*) FROM ' . \$this->table;\n";
        $c .= "        if (!empty(\$where)) {\n";
        $c .= "            \$sql .= ' WHERE ' . implode(' AND ', \$where);\n";
        $c .= "        }\n";
        $c .= "        return (int) \$this->db->query(\$sql)->fetchColumn();\n";
        $c .= "    }\n\n";

        // getNewWeight
        if (!empty($weightCol)) {
            $c .= "    public function getNewWeight(): int\n    {\n";
            $c .= "        \$sql = 'SELECT MAX({$weightCol}) FROM ' . \$this->table;\n";
            $c .= "        return (int) \$this->db->query(\$sql)->fetchColumn() + 1;\n";
            $c .= "    }\n\n";
        }

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
        $c .= "                \$params[':' . \$key] = [\$value, \$this->pdoType(\$key)];\n";
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
        $c .= "            \$stmt->bindValue(':' . \$key, \$value, \$this->pdoType(\$key));\n";
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

        if (!empty($weightCol)) {
            $c .= "    /**\n     * Sắp xếp lại weight dùng Bulk UPDATE (CASE WHEN)\n     */\n";
            $c .= "    public function reorderWeight(int \$movedId = 0, int \$newWeight = 0): void\n    {\n";
            $c .= "        \$sql = 'SELECT id, {$weightCol} FROM ' . \$this->table;\n";
            $c .= "        \$params = [];\n";
            $c .= "        if (\$movedId > 0) {\n";
            $c .= "            \$sql .= ' WHERE id != :id';\n";
            $c .= "            \$params[':id'] = \$movedId;\n";
            $c .= "        }\n";
            $c .= "        \$sql .= ' ORDER BY {$weightCol} ASC';\n\n";
            $c .= "        \$stmt = \$this->db->prepare(\$sql);\n";
            $c .= "        foreach (\$params as \$k => \$v) {\n";
            $c .= "            \$stmt->bindValue(\$k, \$v, PDO::PARAM_INT);\n";
            $c .= "        }\n";
            $c .= "        \$stmt->execute();\n";
            $c .= "        \$rows = \$stmt->fetchAll(PDO::FETCH_ASSOC);\n";
            $c .= "        \$stmt->closeCursor();\n\n";
            $c .= "        \$cases = [];\n";
            $c .= "        \$ids = [];\n";
            $c .= "        \$calcWeight = 0;\n\n";
            $c .= "        foreach (\$rows as \$row) {\n";
            $c .= "            ++\$calcWeight;\n";
            $c .= "            if (\$movedId > 0 && \$calcWeight == \$newWeight) {\n";
            $c .= "                ++\$calcWeight;\n";
            $c .= "            }\n";
            $c .= "            // Chỉ cập nhật nếu thực sự thay đổi\n";
            $c .= "            if (\$calcWeight !== (int) \$row['{$weightCol}']) {\n";
            $c .= "                \$cases[] = 'WHEN ' . (int) \$row['id'] . ' THEN ' . \$calcWeight;\n";
            $c .= "                \$ids[] = (int) \$row['id'];\n";
            $c .= "            }\n";
            $c .= "        }\n\n";
            $c .= "        if (\$movedId > 0 && \$newWeight > 0) {\n";
            $c .= "            \$cases[] = 'WHEN ' . \$movedId . ' THEN ' . \$newWeight;\n";
            $c .= "            \$ids[] = \$movedId;\n";
            $c .= "        }\n\n";
            $c .= "        if (empty(\$ids)) {\n";
            $c .= "            return;\n";
            $c .= "        }\n\n";
            $c .= "        \$this->db->exec(\n";
            $c .= "            'UPDATE ' . \$this->table\n";
            $c .= "                . ' SET {$weightCol} = CASE id ' . implode(' ', \$cases) . ' END'\n";
            $c .= "                . ' WHERE id IN (' . implode(',', \$ids) . ')'\n";
            $c .= "        );\n";
            $c .= "    }\n\n";
        }

        // invalidateCache
        $c .= "    public function invalidateCache(): void\n    {\n";
        $c .= "        \$this->cache->delMod(\$this->module_name);\n";
        $c .= "    }\n\n";

        $hasAlias = in_array('alias', array_keys($entity->columns), true);
        if ($hasAlias) {
            $c .= "    /**\n     * Kiểm tra Alias đã tồn tại chưa\n     */\n";
            $c .= "    public function isAliasExists(string \$alias, int \$excludeId = 0): bool\n    {\n";
            $c .= "        \$sql = 'SELECT COUNT(*) FROM ' . \$this->table . ' WHERE alias = :alias';\n";
            $c .= "        if (\$excludeId > 0) {\n";
            $c .= "            \$sql .= ' AND id != ' . \$excludeId;\n";
            $c .= "        }\n";
            $c .= "        \$stmt = \$this->db->prepare(\$sql);\n";
            $c .= "        \$stmt->execute([':alias' => \$alias]);\n";
            $c .= "        return (int) \$stmt->fetchColumn() > 0;\n";
            $c .= "    }\n\n";
        }

        $c .= "    /**\n     * Lấy weight lớn nhất\n     */\n";
        $c .= "    public function getMaxWeight(): int\n    {\n";
        $c .= "        \$sql = 'SELECT MAX(' . (\$this->weightField ?: 'weight') . ') FROM ' . \$this->table;\n";
        $c .= "        return (int) \$this->db->query(\$sql)->fetchColumn();\n";
        $c .= "    }\n\n";

        $c .= "    /**\n     * Tăng weight của tất cả bản ghi lên 1 (Dùng cho tính năng Tin mới lên đầu)\n     */\n";
        $c .= "    public function incrementOthersWeight(): void\n    {\n";
        $c .= "        \$sql = 'UPDATE ' . \$this->table . ' SET ' . (\$this->weightField ?: 'weight') . ' = ' . (\$this->weightField ?: 'weight') . ' + 1';\n";
        $c .= "        \$this->db->prepare(\$sql)->execute();\n";
        $c .= "    }\n\n";

        $c .= "    /**\n     * Tự động sửa lại weight nếu sai lệch (Bulk UPDATE CASE WHEN)\n     */\n";
        $c .= "    public function autoCorrectWeight(array &\$entities): bool\n    {\n";
        $c .= "        \$weightField = \$this->weightField ?: 'weight';\n";
        $c .= "        \$cases = [];\n";
        $c .= "        \$ids = [];\n";
        $c .= "        \$iw = 0;\n\n";
        $c .= "        foreach (\$entities as \$entity) {\n";
        $c .= "            ++\$iw;\n";
        $c .= "            if (\$iw != \$entity->{\$weightField}) {\n";
        $c .= "                \$entity->{\$weightField} = \$iw;\n";
        $c .= "                \$cases[] = 'WHEN ' . (int) \$entity->id . ' THEN ' . \$iw;\n";
        $c .= "                \$ids[] = (int) \$entity->id;\n";
        $c .= "            }\n";
        $c .= "        }\n\n";
        $c .= "        if (empty(\$ids)) {\n";
        $c .= "            return false;\n";
        $c .= "        }\n\n";
        $c .= "        return (bool) \$this->db->exec(\n";
        $c .= "            'UPDATE ' . \$this->table\n";
        $c .= "                . ' SET ' . \$weightField . ' = CASE id ' . implode(' ', \$cases) . ' END'\n";
        $c .= "                . ' WHERE id IN (' . implode(',', \$ids) . ')'\n";
        $c .= "        );\n";
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

        $c .= "    /**\n     * Chuẩn hóa dữ liệu trước khi validate/save (Alias, Keywords, Image)\n     */\n";
        $c .= "    public function prepareSaveData(array \$data, array \$moduleConfig = [], string \$moduleUpload = ''): array\n    {\n";
        $c .= "        // Alias\n";
        $c .= "        \$aliasSource = '{$entity->alias_source_field}';\n";
        $c .= "        if (!empty(\$aliasSource) && isset(\$data[\$aliasSource])) {\n";
        $c .= "            \$alias = !empty(\$data['alias']) ? \$data['alias'] : \$data[\$aliasSource];\n";
        $c .= "            \$data['alias'] = change_alias(\$alias);\n";
        $c .= "            if (!empty(\$moduleConfig['alias_lower'])) {\n";
        $c .= "                \$data['alias'] = strtolower(\$data['alias']);\n";
        $c .= "            }\n";
        $c .= "        }\n\n";
        $c .= "        // Keywords\n";
        $c .= "        if (isset(\$data['keywords']) && empty(\$data['keywords']) && isset(\$data['title'])) {\n";
        $c .= "            \$data['keywords'] = nv_get_keywords(\$data['title']);\n";
        $c .= "        }\n\n";
        $c .= "        // Image cleanup\n";
        $c .= "        if (!empty(\$moduleUpload) && !empty(\$data['image'])) {\n";
        $c .= "            \$imagePath = NV_UPLOADS_DIR . '/' . \$moduleUpload;\n";
        $c .= "            if (nv_is_file(\$data['image'], \$imagePath)) {\n";
        $c .= "                \$data['image'] = substr(\$data['image'], strlen(NV_BASE_SITEURL . \$imagePath . '/'));\n";
        $c .= "            }\n";
        $c .= "        }\n\n";
        $c .= "        return \$data;\n";
        $c .= "    }\n\n";

        // save{Item}
        $c .= "    /**\n     * Lưu item (INSERT hoặc UPDATE). Data đã được Validator kiểm tra.\n     */\n";
        $c .= "    public function save{$item}(array \$data, int \$id, string \$module_name, array \$config = [], int \$admin_id = 0): int\n    {\n";
        $c .= "        if (\$id > 0) {\n";
        $c .= "            \$data['edit_time'] = NV_CURRENTTIME;\n";
        $c .= "        } else {\n";
        if (!empty($entity->weight_field)) {
            $c .= "            if (!empty(\$config['news_first'])) {\n";
            $c .= "                \$data['{$entity->weight_field}'] = 1;\n";
            $c .= "                \$this->repo->incrementOthersWeight();\n";
            $c .= "            } else {\n";
            $c .= "                \$data['{$entity->weight_field}'] = \$this->repo->getMaxWeight() + 1;\n";
            $c .= "            }\n";
        }
        $c .= "            \$data['admin_id'] = \$admin_id;\n";
        $c .= "            \$data['add_time'] = NV_CURRENTTIME;\n";
        $c .= "            \$data['edit_time'] = NV_CURRENTTIME;\n";
        $c .= "        }\n\n";
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
        if (!empty($entity->weight_field)) {
            $c .= "            \$this->repo->reorderWeight();\n";
        }
        $c .= "            \$this->repo->invalidateCache();\n";
        $c .= "            nv_apply_hook(\$module_name, '{$itemLc}_deleted', ['id' => \$id]);\n";
        $c .= "        }\n";
        $c .= "        return \$result;\n";
        $c .= "    }\n\n";

        $c .= "    public function changeStatus(int \$id, string \$module_name): int\n    {\n";
        $c .= "        \$newStatus = \$this->repo->toggleStatus(\$id);\n";
        $c .= "        if (\$newStatus >= 0) {\n";
        $c .= "            \$this->repo->invalidateCache();\n";
        $c .= "            nv_apply_hook(\$module_name, '{$itemLc}_status_changed', ['id' => \$id, 'new_status' => \$newStatus]);\n";
        $c .= "        }\n";
        $c .= "        return \$newStatus;\n";
        $c .= "    }\n\n";

        $c .= "    public function changeWeight(int \$id, int \$newWeight, string \$module_name): bool\n    {\n";
        $c .= "        \$this->repo->reorderWeight(\$id, \$newWeight);\n";
        $c .= "        \$this->repo->invalidateCache();\n";
        $c .= "        return true;\n";
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh {Item}Validator.php
     */
    private function buildValidator(SchemaEntity $entity, string $mod, string $item): string
    {
        $ns = "NukeViet\\Module\\{$mod}\\{$item}";

        $checks     = '';
        $errorCode  = 0;
        $hasAlias   = false;
        foreach ($entity->columns as $field => $col) {
            if ($field === 'alias') {
                $hasAlias = true;
            }
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
                $checks .= "            \$errors[{$errorCode}] = '{$langKey}';\n";
                $checks .= "        }\n";
            } else {
                $checks .= "        if (empty(\$data['{$field}'])) {\n";
                $checks .= "            \$errors[{$errorCode}] = '{$langKey}';\n";
                $checks .= "        }\n";
            }
        }

        if ($hasAlias) {
            $errorCode++;
            $checks .= "\n        if (!empty(\$data['alias']) && \$this->repo->isAliasExists(\$data['alias'], \$excludeId)) {\n";
            $checks .= "            \$errors[{$errorCode}] = 'erroralias';\n";
            $checks .= "        }\n";
        }

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "declare(strict_types=1);\n\n";
        $c .= "namespace {$ns};\n\n";
        $c .= "use NukeViet\\Module\\{$mod}\\Shared\\ValidationException;\n\n";
        $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "/**\n * {$item}Validator — Request Validator kiểm duyệt dữ liệu.\n";
        $c .= " * Gom nhiều lỗi vào một ValidationException duy nhất.\n */\n";
        $c .= "class {$item}Validator\n{\n";
        $c .= "    public function __construct(private {$item}Repository \$repo) {}\n\n";
        $c .= "    /**\n     * @throws ValidationException\n     */\n";
        $c .= "    public function validateSave(array \$data, int \$excludeId = 0): void\n    {\n";
        $c .= "        \$errors = [];\n\n";
        $c .= $checks;
        $c .= "\n        if (!empty(\$errors)) {\n";
        $c .= "            throw new ValidationException(\$errors);\n";
        $c .= "        }\n";
        $c .= "    }\n}\n";

        return $c;
    }

    /**
     * Sinh admin/{item}.php (Controller)
     */
    private function buildController(SchemaEntity $entity, string $mod, string $item, string $opName, string $listOp = ''): string
    {
        $itemLc = strtolower($item);
        if (empty($listOp)) {
            $listOp = $opName;
        }

        $ns = "NukeViet\\Module\\{$mod}\\{$item}";

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "if (!defined('NV_IS_FILE_ADMIN')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "use {$ns}\\{$item}Repository;\n";
        $c .= "use {$ns}\\{$item}Service;\n";
        $c .= "use {$ns}\\{$item}Validator;\n";
        $c .= "use NukeViet\\Module\\{$mod}\\Shared\\ValidationException;\n\n";

        $c .= "\$itemRepo = new {$item}Repository(\$db, \$config['table_{$itemLc}'], \$nv_Cache, \$module_name);\n";
        $c .= "\$service  = new {$item}Service(\$itemRepo);\n\n";
        
        $c .= "\$id = \$nv_Request->get_int('id', 'get,post', 0);\n";
        $c .= "\$row = [];\n\n";

        $c .= "if (\$id > 0) {\n";
        $c .= "    \$entity = \$itemRepo->findById(\$id);\n";
        $c .= "    if (!\$entity) {\n";
        $c .= "        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '={$listOp}');\n";
        $c .= "    }\n";
        $c .= "    \$page_title = \$nv_Lang->getModule('edit');\n";
        $c .= "} else {\n";
        $c .= "    \$page_title = \$nv_Lang->getModule('add');\n";
        $c .= "}\n\n";

        // Xây dựng map error code -> field name
        $fieldMapLines = "        \$fieldMap = [\n";
        $errorCode     = 0;
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || $this->isSpecialField((string) $field, $entity, $col)) {
                continue;
            }
            if (empty($col['required'])) {
                continue;
            }
            $errorCode++;
            $fieldMapLines .= "            {$errorCode} => '{$field}',\n";
        }
        $hasAlias = in_array('alias', array_keys($entity->columns), true);
        if ($hasAlias) {
            $errorCode++;
            $fieldMapLines .= "            {$errorCode} => 'alias',\n";
        }
        $fieldMapLines .= "        ];";

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

        $c .= "// ══════ XỬ LÝ POST (AJAX) ══════\n";
        $c .= "if (\$nv_Request->isset_request('checkss', 'post')) {\n";
        $c .= "    if (!csrf_check(\$nv_Request->get_string('checkss', 'post'), \$csrf_key)) {\n";
        $c .= "        nv_jsonOutput(['status' => 'error', 'mess' => \$nv_Lang->getGlobal('error_checkss')]);\n";
        $c .= "    }\n\n";

        $c .= "    // Lấy alias qua AJAX\n";
        $c .= "    if (\$nv_Request->isset_request('get_alias', 'post')) {\n";
        $c .= "        \$title = \$nv_Request->get_title('title', 'post', '');\n";
        $c .= "        \$alias = change_alias(\$title);\n";
        $c .= "        if (!empty(\$config['alias_lower'])) {\n";
        $c .= "            \$alias = strtolower(\$alias);\n";
        $c .= "        }\n";
        $c .= "        nv_jsonOutput(['status' => 'success', 'alias' => \$alias]);\n";
        $c .= "    }\n\n";

        $c .= "    // Xóa bản ghi\n";
        $c .= "    if (\$nv_Request->isset_request('delete', 'post')) {\n";
        $c .= "        \$itemRepo->delete(\$id);\n";
        $c .= "        nv_jsonOutput(['status' => 'success']);\n";
        $c .= "    }\n\n";

        $c .= "    \$respon = ['status' => 'error', 'mess' => ''];\n";
        $c .= "    \$row    = \$service->collectRequestData(\$nv_Request);\n\n";
        $c .= "    try {\n";
        $c .= "        \$saveId    = \$id ?: 0;\n";
        $c .= "        \$row = \$service->prepareSaveData(\$row, \$config, \$module_upload);\n\n";
        $c .= "        \$validator = new {$item}Validator(\$itemRepo);\n";
        $c .= "        \$validator->validateSave(\$row, \$saveId);\n\n";
        $c .= "        \$savedId = \$service->save{$item}(\$row, \$saveId, \$module_name, \$config, \$admin_info['admin_id']);\n";
        $c .= "        nv_insert_logs(NV_LANG_DATA, \$module_name, \$saveId ? 'Edit' : 'Add', 'ID: ' . \$savedId, \$admin_info['userid']);\n";
        $c .= "    } catch (ValidationException \$e) {\n";
        $c .= $fieldMapLines . "\n";
        $c .= "        \$respon['input'] = \$fieldMap[\$e->getCode()] ?? array_values(\$fieldMap)[0];\n";
        $c .= "        \$respon['mess']  = \$nv_Lang->getModule(\$e->getMessage());\n";
        $c .= "        \$respon['errors'] = [];\n";
        $c .= "        foreach (\$e->getErrors() as \$code => \$langKey) {\n";
        $c .= "            \$respon['errors'][] = [\n";
        $c .= "                'field'   => \$fieldMap[\$code] ?? 'title',\n";
        $c .= "                'message' => \$nv_Lang->getModule(\$langKey),\n";
        $c .= "            ];\n";
        $c .= "        }\n";
        $c .= "        nv_jsonOutput(\$respon);\n";
        $c .= "    } catch (\\Throwable \$e) {\n";
        $c .= "        trigger_error(\$e);\n";
        $c .= "        \$respon['mess'] = \$nv_Lang->getGlobal('error_system');\n";
        $c .= "        nv_jsonOutput(\$respon);\n";
        $c .= "    }\n\n";
        $c .= "    \$respon['status']   = 'success';\n";
        $c .= "    \$respon['mess']     = \$nv_Lang->getGlobal('save_success');\n";
        $c .= "    \$respon['redirect'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA\n";
        $c .= "        . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '={$listOp}';\n";
        $c .= "    nv_jsonOutput(\$respon);\n\n";
        $c .= "} elseif (empty(\$id)) {\n";
        $c .= "    // Dữ liệu mặc định form thêm mới\n";
        $c .= $defaultRowLines . "\n";
        $c .= "}\n\n";
        $c .= "// ══════ RENDER FORM ══════\n";
        $c .= "\$row_data = isset(\$entity) ? \$entity->toArray() : \$row;\n";

        $hasEditor = false;
        $editorLines = "\nif (defined('NV_EDITOR')) {\n    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';\n}\n";
        foreach ($entity->columns as $field => $col) {
            if (($col['view_type'] ?? '') === 'editor') {
                $hasEditor = true;
                $editorLines .= "\$row_data['{$field}'] = htmlspecialchars(nv_editor_br2nl(\$row_data['{$field}']));\n";
                $editorLines .= "if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {\n";
                $editorLines .= "    \$row_data['{$field}'] = nv_aleditor('{$field}', '100%', '300px', \$row_data['{$field}'], '', NV_UPLOADS_DIR . '/' . \$module_upload, NV_UPLOADS_DIR . '/' . \$module_upload);\n";
                $editorLines .= "} else {\n";
                $editorLines .= "    \$row_data['{$field}'] = '<textarea class=\"form-control\" name=\"{$field}\" id=\"{$field}\" rows=\"15\">' . \$row_data['{$field}'] . '</textarea>';\n";
                $editorLines .= "}\n";
            }
        }
        if ($hasEditor) {
            $c .= $editorLines;
        }

        $c .= "\n";
        $c .= "\$tpl = new \\NukeViet\\Template\\NVSmarty();\n";
        $c .= "\$tpl->setTemplateDir(get_module_tpl_dir('{$opName}.tpl'));\n";
        $c .= "\$tpl->assign('LANG', \$nv_Lang);\n";
        $c .= "\$tpl->assign('MODULE_NAME', \$module_name);\n";
        $c .= "\$tpl->assign('OP', \$op);\n";
        $c .= "\$tpl->assign('OP_NAME', '{$opName}');\n";
        $c .= "\$tpl->assign('DATA', \$row_data);\n";
        $c .= "\$tpl->assign('ID', \$id);\n";
        $c .= "\$tpl->assign('CHECKSS', csrf_create(\$csrf_key));\n";
        $c .= "\$tpl->assign('URL_LIST', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '={$listOp}');\n";
        $c .= "\$tpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . \$module_upload);\n\n";

        // SQL Choices
        foreach ($entity->columns as $field => $col) {
            if (($col['view_type'] ?? '') === 'select' && ($col['choice_type'] ?? '') === 'sql') {
                $table   = $col['choice_table'] ?? '';
                $idCol   = $col['choice_id_col'] ?? 'id';
                $textCol = $col['choice_text_col'] ?? 'name';
                if (!empty($table)) {
                    $c .= "// Lấy danh sách lựa chọn cho {$field} từ bảng {$table}\n";
                    $c .= "\$sql_table = (strpos('{$table}', \$db_config['prefix'] . '_') === 0) ? '{$table}' : \$db_config['prefix'] . '_{$table}';\n";
                    $c .= "\$choices_{$field} = \$db->query('SELECT {$idCol}, {$textCol} FROM ' . \$sql_table)->fetchAll(PDO::FETCH_ASSOC);\n";
                    $c .= "\$tpl->assign('CHOICES_{$field}', \$choices_{$field});\n\n";
                }
            }
        }

        $c .= "\$contents = \$tpl->fetch('{$opName}.tpl');\n\n";
        $c .= "include NV_ROOTDIR . '/includes/header.php';\n";
        $c .= "echo nv_admin_theme(\$contents);\n";
        $c .= "include NV_ROOTDIR . '/includes/footer.php';\n";

        return $c;
    }

    /**
     * Sinh admin template {item}.tpl (Smarty)
     */
    private function buildTemplate(SchemaEntity $entity, string $mod, string $item, string $opName, string $listOp = ''): string
    {
        $itemLc = strtolower($item);
        $formFields = '';
        foreach ($entity->columns as $field => $col) {
            if ($field === 'id' || !empty($col['hidden']) || $this->isSpecialField((string) $field, $entity, $col)) {
                continue; // ID, Cột ẩn hoặc Cột AI tự động sinh thì ẩn khỏi Form
            }

            $label     = htmlspecialchars($col['label_vi'] ?? $field, ENT_QUOTES);
            $req       = !empty($col['required']) ? ' <span class="text-danger">*</span>' : '';
            $fieldHtml = $this->buildFormField($field, $col, $entity, $itemLc);

            $formFields .= "            <div class=\"mb-3\">\n";
            $formFields .= "                <label class=\"form-label fw-semibold\">{$label}{$req}</label>\n";
            $formFields .= "                {$fieldHtml}\n";
            $formFields .= "            </div>\n";
        }

        $c  = "<form action=\"{\$NV_BASE_ADMINURL}index.php\" method=\"post\" class=\"ajax-submit\">\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_LANG_VARIABLE}\" value=\"{\$NV_LANG_DATA}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_NAME_VARIABLE}\" value=\"{\$MODULE_NAME}\" />\n";
        $c .= "    <input type=\"hidden\" name=\"{\$NV_OP_VARIABLE}\" value=\"{\$OP}\" />\n";
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
        $c .= "            <a href=\"{\$URL_LIST}\" class=\"btn btn-outline-secondary\">\n";
        $c .= "                <i class=\"fa-solid fa-arrow-left me-1\"></i>{\$LANG->getModule('back')}\n";
        $c .= "            </a>\n";
        $c .= "        </div>\n";
        $c .= "    </div>\n";
        $c .= "</form>\n\n";
        $c .= "<script>\n";
        $c .= "{literal}\n";
        $c .= "$(function() {\n";
        $c .= "    $('[data-toggle=\"getaliaspage-{$opName}\"]').on('click', function(e) {\n";
        $c .= "        e.preventDefault();\n";
        $c .= "        var btn = $(this);\n";
        $c .= "        var source = $(btn.data('source'));\n";
        $c .= "        var title = source.val();\n";
        $c .= "        if (title != '') {\n";
        $c .= "            btn.find('i').addClass('fa-spin');\n";
        $c .= "            $.post(location.href, {get_alias: 1, title: title, checkss: btn.data('checkss')}, function(res) {\n";
        $c .= "                btn.find('i').removeClass('fa-spin');\n";
        $c .= "                if (res.status == 'success') {\n";
        $c .= "                    btn.closest('.input-group').find('input').val(res.alias);\n";
        $c .= "                }\n";
        $c .= "            }, 'json');\n";
        $c .= "        }\n";
        $c .= "    });\n";
        $c .= "    var btnAlias = $('[data-toggle=\"getaliaspage-{$opName}\"]');\n";
        $c .= "    if (btnAlias.length && btnAlias.data('auto-alias')) {\n";
        $c .= "        $(btnAlias.data('source')).on('change', function() {\n";
        $c .= "            btnAlias.trigger('click');\n";
        $c .= "        });\n";
        $c .= "    }\n";
        $c .= "});\n";
        $c .= "{/literal}\n";
        $c .= "</script>\n";

        return $c;
    }

    /**
     * Sinh HTML cho 1 form field theo view_type.
     */
    private function buildFormField(string $field, array $col, SchemaEntity $entity, string $itemLc): string
    {
        $vt = $col['view_type'] ?? 'textbox';

        return match ($vt) {
            'textarea'     => "<textarea name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" rows=\"5\">{\$DATA.{$field}}</textarea>",
            'editor'       => "{\$DATA.{$field}}",
            'textalias'    => (function() use ($field, $entity, $itemLc) {
                $source = !empty($entity->alias_source_field) ? $entity->alias_source_field : 'title';
                return "<div class=\"input-group\"><input type=\"text\" class=\"form-control\" name=\"{$field}\" id=\"id{$field}\" value=\"{\$DATA.{$field}}\" maxlength=\"250\"><button class=\"btn btn-secondary\" type=\"button\" data-toggle=\"getaliaspage-{$itemLc}\" data-source=\"#id{$source}\" data-auto-alias=\"{if empty(\$DATA.{$field})}1{else}0{/if}\" data-checkss=\"{\$CHECKSS}\" data-id=\"{\$ID}\"><i class=\"fa-solid fa-rotate\"></i></button></div>";
            })(),
            'textfile'     => "<div class=\"input-group\"><input class=\"form-control\" type=\"text\" name=\"{$field}\" id=\"id{$field}\" value=\"{\$DATA.{$field}}\"><button type=\"button\" class=\"btn btn-secondary\" data-toggle=\"selectfile\" data-target=\"id{$field}\" data-path=\"{\$UPLOADS_DIR_USER}\" data-type=\"all\"><i class=\"fa-solid fa-folder-open\"></i></button></div>",
            'number_int'   => "<input type=\"number\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'number_float' => "<input type=\"number\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" step=\"0.01\" value=\"{\$DATA.{$field}}\">",
            'checkbox'     => "<div class=\"form-check\"><input type=\"checkbox\" name=\"{$field}\" id=\"id{$field}\" class=\"form-check-input\" value=\"1\" {if \$DATA.{$field}}checked{/if}></div>",
            'date'         => "<input type=\"date\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'time'         => "<input type=\"datetime-local\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'email'        => "<input type=\"email\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'url'          => "<input type=\"url\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
            'password'     => "<input type=\"password\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\">",
            'select'       => $this->buildSelectField($field, $col),
            'radio'        => $this->buildRadioField($field, $col),
            default        => "<input type=\"text\" name=\"{$field}\" id=\"id{$field}\" class=\"form-control\" value=\"{\$DATA.{$field}}\">",
        };
    }

    private function buildSelectField(string $field, array $col): string
    {
        $choiceType = $col['choice_type'] ?? 'static';

        if ($choiceType === 'sql') {
            $idCol   = htmlspecialchars($col['choice_id_col'] ?? 'id', ENT_QUOTES);
            $textCol = htmlspecialchars($col['choice_text_col'] ?? 'name', ENT_QUOTES);
            return "<select name=\"{$field}\" id=\"id{$field}\" class=\"form-select\">\n"
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

        return "<select name=\"{$field}\" id=\"id{$field}\" class=\"form-select\">\n"
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

    /**
     * Tự động sinh các file Shared nếu chưa tồn tại.
     */
    private function addSharedFiles(array &$files, string $mod, string $nvRootDir): void
    {
        $sharedDir = "modules/{$mod}/Shared";

        // 1. AbstractEntity.php
        $file = "{$sharedDir}/AbstractEntity.php";
        if (!file_exists($nvRootDir . '/' . $file)) {
            $c = "<?php\n\nnamespace NukeViet\\Module\\{$mod}\\Shared;\n\n";
            $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
            $c .= "abstract class AbstractEntity\n{\n";
            $c .= "    protected const VIEW_FIELDS = [];\n";
            $c .= "    protected const PRIMARY_KEY = '';\n\n";
            $c .= "    public static function getDbColumns(): array\n    {\n";
            $c .= "        \$allFields = array_keys(get_class_vars(static::class));\n";
            $c .= "        \$exclude = static::VIEW_FIELDS;\n";
            $c .= "        if (static::PRIMARY_KEY !== '') {\n";
            $c .= "            \$exclude[] = static::PRIMARY_KEY;\n";
            $c .= "        }\n";
            $c .= "        return array_values(array_diff(\$allFields, \$exclude));\n";
            $c .= "    }\n\n";
            $c .= "    public static function getIntColumns(): array\n    {\n";
            $c .= "        \$cache = [];\n";
            $c .= "        \$class = static::class;\n";
            $c .= "        if (!isset(\$cache[\$class])) {\n";
            $c .= "            \$cache[\$class] = [];\n";
            $c .= "            foreach (get_class_vars(\$class) as \$field => \$default) {\n";
            $c .= "                if (\$default !== null && is_int(\$default)) {\n";
            $c .= "                    \$cache[\$class][\$field] = true;\n";
            $c .= "                }\n";
            $c .= "            }\n";
            $c .= "        }\n";
            $c .= "        return \$cache[\$class];\n";
            $c .= "    }\n\n";
            $c .= "    abstract public function toArray(): array;\n\n";
            $c .= "    public static function fromArray(array \$data): static\n    {\n";
            $c .= "        \$entity = new static();\n";
            $c .= "        foreach (\$data as \$key => \$value) {\n";
            $c .= "            if (!property_exists(\$entity, \$key) || \$value === null) {\n";
            $c .= "                continue;\n";
            $c .= "            }\n";
            $c .= "            \$default = \$entity->\$key;\n";
            $c .= "            if (\$default === null) {\n";
            $c .= "                continue;\n";
            $c .= "            }\n";
            $c .= "            \$entity->\$key = is_int(\$default) ? (int) \$value : (string) \$value;\n";
            $c .= "        }\n";
            $c .= "        return \$entity;\n";
            $c .= "    }\n}\n";
            $this->addFile($files, $file, $c, $nvRootDir);
        }

        // 2. ValidationException.php
        $file = "{$sharedDir}/ValidationException.php";
        if (!file_exists($nvRootDir . '/' . $file)) {
            $c = "<?php\n\nnamespace NukeViet\\Module\\{$mod}\\Shared;\n\n";
            $c .= "if (!defined('NV_MAINFILE')) {\n    exit('Stop!!!');\n}\n\n";
            $c .= "class ValidationException extends \\InvalidArgumentException\n{\n";
            $c .= "    private array \$errors;\n\n";
            $c .= "    public function __construct(array \$errors)\n    {\n";
            $c .= "        \$firstCode = (int) array_key_first(\$errors);\n";
            $c .= "        parent::__construct(\$errors[\$firstCode], \$firstCode);\n";
            $c .= "        \$this->errors = \$errors;\n";
            $c .= "    }\n\n";
            $c .= "    public function getErrors(): array\n    {\n";
            $c .= "        return \$this->errors;\n";
            $c .= "    }\n}\n";
            $this->addFile($files, $file, $c, $nvRootDir);
        }
    }

    private function buildListController(SchemaEntity $entity, string $mod, string $item, string $opName, string $formOpName): string
    {
        $ns = "NukeViet\\Module\\{$mod}\\{$item}";
        $activeField = $entity->active_field;
        $weightField = $entity->weight_field;
        $itemLc = strtolower($item);

        $c  = "<?php\n\n";
        $c .= $this->copyright() . "\n\n";
        $c .= "if (!defined('NV_IS_FILE_ADMIN')) {\n    exit('Stop!!!');\n}\n\n";
        $c .= "use {$ns}\\{$item}Repository;\n";
        $c .= "use {$ns}\\{$item}Service;\n\n";
        $c .= "\$itemRepo = new {$item}Repository(\$db, \$config['table_{$itemLc}'], \$nv_Cache, \$module_name);\n";
        $c .= "\$service  = new {$item}Service(\$itemRepo);\n\n";
        
        $c .= "if (\$nv_Request->isset_request('checkss', 'post')) {\n";
        $c .= "    if (!csrf_check(\$nv_Request->get_string('checkss', 'post'), \$csrf_key)) {\n";
        $c .= "        nv_jsonOutput(['status' => 'error', 'mess' => \$nv_Lang->getGlobal('error_checkss')]);\n";
        $c .= "    }\n\n";

        if (!empty($activeField)) {
            $c .= "    if (\$nv_Request->isset_request('toggle_status', 'post')) {\n";
            $c .= "        \$id = \$nv_Request->get_int('toggle_status', 'post', 0);\n";
            $c .= "        \$newStatus = \$itemRepo->toggleStatus(\$id);\n";
            $c .= "        nv_jsonOutput(['status' => 'success', 'new_status' => \$newStatus]);\n";
            $c .= "    }\n\n";
        }

        if (!empty($weightField)) {
            $c .= "    if (\$nv_Request->isset_request('change_weight', 'post')) {\n";
            $c .= "        \$id = \$nv_Request->get_int('id', 'post', 0);\n";
            $c .= "        \$newWeight = \$nv_Request->get_int('change_weight', 'post', 0);\n";
            $c .= "        \$itemRepo->reorderWeight(\$id, \$newWeight);\n";
            $c .= "        nv_jsonOutput(['status' => 'success']);\n";
            $c .= "    }\n\n";
        }
        $c .= "}\n\n";

        $c .= "\$page  = \$nv_Request->get_int('page', 'get', 1);\n";
        $c .= "\$per_page = 20;\n";
        $c .= "\$q = \$nv_Request->get_title('q', 'get', '');\n\n";
        $c .= "\$items = \$itemRepo->getList(\$page, \$per_page, -1, \$q);\n";
        $c .= "\$all_count = \$itemRepo->count(-1, \$q);\n\n";
        $c .= "\$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '=' . \$op;\n";
        $c .= "if (!empty(\$q)) {\n    \$base_url .= '&q=' . urlencode(\$q);\n}\n";
        $c .= "\$generate_page = nv_generate_page(\$base_url, \$all_count, \$per_page, \$page);\n\n";
        $c .= "\$tpl = new \\NukeViet\\Template\\NVSmarty();\n";
        $c .= "\$tpl->setTemplateDir(get_module_tpl_dir('{$opName}.tpl'));\n";
        $c .= "\$tpl->assign('LANG', \$nv_Lang);\n";
        $c .= "\$tpl->assign('MODULE_NAME', \$module_name);\n";
        $c .= "\$tpl->assign('OP', \$op);\n";
        $c .= "\$tpl->assign('ITEMS', \$items);\n";
        $c .= "\$tpl->assign('Q', \$q);\n";
        $c .= "\$tpl->assign('PAGES', \$generate_page);\n";
        $c .= "\$tpl->assign('CHECKSS', csrf_create(\$csrf_key));\n";
        $c .= "\$tpl->assign('URL_ADD', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '={$formOpName}');\n";
        $c .= "\$tpl->assign('URL_EDIT', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . \$module_name . '&' . NV_OP_VARIABLE . '={$formOpName}&id=');\n\n";
        $c .= "\$contents = \$tpl->fetch('{$opName}.tpl');\n\n";
        $c .= "include NV_ROOTDIR . '/includes/header.php';\n";
        $c .= "echo nv_admin_theme(\$contents);\n";
        $c .= "include NV_ROOTDIR . '/includes/footer.php';\n";
        return $c;
    }

    private function buildListTemplate(SchemaEntity $entity, string $mod, string $item, string $opName, string $formOpName): string
    {
        $activeField = $entity->active_field;
        $weightField = $entity->weight_field;

        $c = "<div class=\"d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2\">\n";
        $c .= "    <form action=\"{\$NV_BASE_ADMINURL}index.php\" method=\"get\" class=\"hstack gap-2\">\n";
        $c .= "        <input type=\"hidden\" name=\"{\$NV_LANG_VARIABLE}\" value=\"{\$NV_LANG_DATA}\" />\n";
        $c .= "        <input type=\"hidden\" name=\"{\$NV_NAME_VARIABLE}\" value=\"{\$MODULE_NAME}\" />\n";
        $c .= "        <input type=\"hidden\" name=\"{\$NV_OP_VARIABLE}\" value=\"{\$OP}\" />\n";
        $c .= "        <input type=\"text\" name=\"q\" value=\"{\$Q}\" class=\"form-control form-control-sm\" placeholder=\"{\$LANG->getGlobal('search')}...\" style=\"min-width: 200px\" />\n";
        $c .= "        <button type=\"submit\" class=\"btn btn-primary btn-sm text-nowrap\">{\$LANG->getGlobal('search')}</button>\n";
        $c .= "    </form>\n";
        $c .= "    <a href=\"{\$URL_ADD}\" class=\"btn btn-primary btn-sm\">\n";
        $c .= "        <i class=\"fa-solid fa-plus\"></i> {\$LANG->getModule('add')}\n";
        $c .= "    </a>\n";
        $c .= "</div>\n\n";

        $c .= "<div class=\"card layer-1\">\n";
        $c .= "    <div class=\"card-body\">\n";
        $c .= "        <div class=\"table-responsive-lg table-card pb-1\">\n";
        $c .= "            <table class=\"table table-striped align-middle table-sticky mb-0\">\n";
        $c .= "                <thead>\n                    <tr>\n";
        if (!empty($weightField)) {
            $c .= "                        <th class=\"text-nowrap text-center\" style=\"width:80px\">{\$LANG->getModule('weight')}</th>\n";
        }
        $c .= "                        <th class=\"text-nowrap\">{\$LANG->getModule('title')}</th>\n";
        if (!empty($activeField)) {
            $c .= "                        <th class=\"text-nowrap text-center\" style=\"width:100px\">{\$LANG->getModule('active')}</th>\n";
        }
        $c .= "                        <th class=\"text-nowrap text-center\" style=\"width:150px\">{\$LANG->getGlobal('actions')}</th>\n";
        $c .= "                    </tr>\n                </thead>\n";
        $c .= "                <tbody>\n";
        $c .= "                    {foreach from=\$ITEMS item=row}\n";
        $c .= "                    <tr>\n";
        if (!empty($weightField)) {
            $c .= "                        <td class=\"text-center\">\n";
            $c .= "                            <select class=\"form-select form-select-sm fw-semibold mb-0\" onchange=\"nv_change_weight('{\$row->id}', this.value);\">\n";
            $c .= "                                {for \$i=1 to (\$ITEMS|@count + 10)}\n";
            $c .= "                                <option value=\"{\$i}\" {if \$row->{$weightField} == \$i}selected{/if}>{\$i}</option>\n";
            $c .= "                                {/for}\n";
            $c .= "                            </select>\n";
            $c .= "                        </td>\n";
        }
        $c .= "                        <td><a href=\"{\$URL_EDIT}{\$row->id}\" class=\"text-decoration-none fw-bold\">{\$row->title}</a></td>\n";
        if (!empty($activeField)) {
            $c .= "                        <td class=\"text-center\">\n";
            $c .= "                            <div class=\"form-check form-switch d-inline-block mb-0\">\n";
            $c .= "                                <input type=\"checkbox\" class=\"form-check-input\" {if \$row->{$activeField}}checked{/if} onchange=\"nv_change_status('{\$row->id}');\" role=\"switch\" />\n";
            $c .= "                            </div>\n";
            $c .= "                        </td>\n";
        }
        $c .= "                        <td class=\"text-center\">\n";
        $c .= "                            <div class=\"hstack gap-1 justify-content-center\">\n";
        $c .= "                                <a href=\"{\$URL_EDIT}{\$row->id}\" class=\"btn btn-sm btn-outline-primary\" title=\"{\$LANG->getGlobal('edit')}\"><i class=\"fa-solid fa-pen-to-square\"></i></a>\n";
        $c .= "                                <a href=\"javascript:void(0);\" onclick=\"nv_delete_item('{\$row->id}');\" class=\"btn btn-sm btn-outline-danger\" title=\"{\$LANG->getGlobal('delete')}\"><i class=\"fa-solid fa-trash\"></i></a>\n";
        $c .= "                            </div>\n";
        $c .= "                        </td>\n";
        $c .= "                    </tr>\n";
        $c .= "                    {/foreach}\n";
        $c .= "                </tbody>\n";
        $c .= "            </table>\n";
        $c .= "        </div>\n";
        $c .= "    </div>\n";
        $c .= "</div>\n\n";

        $c .= "<div class=\"mt-4 d-flex justify-content-center\">\n    {\$PAGES}\n</div>\n\n";

        $c .= "<script>\n{literal}\n";
        $c .= "function nv_change_status(id) {\n";
        $c .= "    $.post(location.href, {toggle_status: id, checkss: '{/literal}{\$CHECKSS}{literal}'}, function(res) {\n";
        $c .= "        if (res.status != 'success') alert(res.mess);\n";
        $c .= "    }, 'json');\n";
        $c .= "}\n";
        $c .= "function nv_change_weight(id, weight) {\n";
        $c .= "    $.post(location.href, {id: id, change_weight: weight, checkss: '{/literal}{\$CHECKSS}{literal}'}, function(res) {\n";
        $c .= "        if (res.status == 'success') location.reload();\n";
        $c .= "        else alert(res.mess);\n";
        $c .= "    }, 'json');\n";
        $c .= "}\n";
        $c .= "function nv_delete_item(id) {\n";
        $c .= "    if (confirm('{/literal}{\$LANG->getGlobal('delete_confirm')}{literal}')) {\n";
        $c .= "        $.post('{/literal}{\$URL_EDIT}{literal}' + id, {delete: 1, checkss: '{/literal}{\$CHECKSS}{literal}'}, function(res) {\n";
        $c .= "            if (res.status == 'success') location.reload();\n";
        $c .= "            else alert(res.mess);\n";
        $c .= "        }, 'json');\n";
        $c .= "    }\n}\n{/literal}\n</script>\n";
        return $c;
    }
}
