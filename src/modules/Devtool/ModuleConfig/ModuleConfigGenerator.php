<?php
/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Module\Devtool\ModuleConfig;

/**
 * Class ModuleConfigGenerator
 * Sinh mã nguồn PHP và TPL cho giao diện cấu hình module
 */
class ModuleConfigGenerator
{
    /**
     * Sinh mã nguồn file giao diện admin/config.php
     * @param string $target_module
     * @param ModuleConfigEntity $entity
     * @return string
     */
    public function generatePHP(string $target_module, ModuleConfigEntity $entity): string
    {
        $groups  = $entity->getGroups();
        $op      = $entity->getOp();
        
        $php = "<?php\n\n";
        $php .= "/**\n * @author Devtool Generator\n */\n\n";
        $php .= "if (!defined('NV_IS_FILE_ADMIN')) {\n    exit('Stop!!!');\n}\n\n";
        $php .= "global \$db, \$db_config, \$tables, \$nv_Cache, \$nv_Request, \$nv_Lang, \$module_name, \$module_data, \$module_info, \$module_upload, \$op, \$csrf_key, \$admin_info, \$module_config;\n\n";
        $php .= "\$page_title = \$nv_Lang->getModule('config');\n\n";
        
        // Xử lý lưu cấu hình
        $php .= "if (\$nv_Request->isset_request('save', 'post')) {\n";
        $php .= "    if (!csrf_check(\$nv_Request->get_string('checkss', 'post'), \$csrf_key)) {\n";
        $php .= "        nv_jsonOutput(['status' => 'error', 'mess' => \$nv_Lang->getGlobal('error_checkss')]);\n";
        $php .= "    }\n\n";
        
        $php .= "    \$config_data = [];\n";
        foreach ($groups as $group) {
            foreach ($group['fields'] as $key => $field) {
                $type = $field['type'] ?? 'textbox';
                if ($type === 'checkbox_single') {
                    $php .= "    \$config_data['$key'] = \$nv_Request->get_int('$key', 'post', 0);\n";
                } elseif ($type === 'checkbox' || $type === 'multiselectbox' || $type === 'multiselect') {
                    $php .= "    \$values = \$nv_Request->get_array('$key', 'post', []);\n";
                    $php .= "    \$config_data['$key'] = implode(',', \$values);\n";
                } elseif ($type === 'number') {
                    $php .= "    \$config_data['$key'] = \$nv_Request->get_int('$key', 'post', 0);\n";
                } else {
                    $php .= "    \$config_data['$key'] = \$nv_Request->get_string('$key', 'post', '');\n";
                }
            }
        }
        
        $php .= "\n    foreach (\$config_data as \$config_name => \$config_value) {\n";
        $php .= "        \$db->prepare(\"UPDATE \" . NV_CONFIG_GLOBALTABLE . \" SET config_value = :config_value WHERE lang = :lang AND module = :module AND config_name = :config_name\")\n";
        $php .= "           ->execute(['config_value' => \$config_value, 'lang' => NV_LANG_DATA, 'module' => '$target_module', 'config_name' => \$config_name]);\n";
        $php .= "    }\n\n";
        
        $php .= "    \$nv_Cache->delMod('$target_module');\n";
        $php .= "    nv_jsonOutput(['status' => 'success', 'mess' => \$nv_Lang->getGlobal('save_success'), 'refresh' => 1]);\n";
        $php .= "}\n\n";
        
        // Chuẩn bị dữ liệu hiển thị (ví dụ load DB cho selectbox/radio/checkbox)
        $php .= "\$data = [];\n";
        foreach ($groups as $group) {
            foreach ($group['fields'] as $key => $field) {
                $type = $field['type'] ?? '';
                if (in_array($type, ['selectbox', 'radio', 'checkbox', 'multiselectbox', 'multiselect']) && ($field['source'] ?? '') === 'db') {
                    $db_opts = $field['options_db'] ?? [];
                    if (!empty($db_opts['table'])) {
                        $php .= "\$stmt = \$db->prepare(\"SELECT {$db_opts['key_col']}, {$db_opts['val_col']} FROM {$db_opts['table']} ORDER BY {$db_opts['val_col']} ASC\");\n";
                        $php .= "\$stmt->execute();\n";
                        $php .= "\$data['options']['$key'] = \$stmt->fetchAll(\\PDO::FETCH_KEY_PAIR);\n";
                    }
                }
            }
        }
        
        $php .= "\n\$tpl = new \\NukeViet\\Template\\NVSmarty();\n";
        $php .= "\$tpl->setTemplateDir(get_module_tpl_dir('$op.tpl'));\n";
        $php .= "\$tpl->assign('LANG', \$nv_Lang);\n";
        $php .= "\$tpl->assign('MODULE_NAME', '$target_module');\n";
        $php .= "\$tpl->assign('CONFIG', \$module_config['$target_module']);\n";
        $php .= "\$tpl->assign('DATA', \$data);\n";
        $php .= "\$tpl->assign('CHECKSS', csrf_create(\$csrf_key));\n\n";
        $php .= "\$contents = \$tpl->fetch('$op.tpl');\n\n";
        $php .= "include NV_ROOTDIR . '/includes/header.php';\n";
        $php .= "echo nv_admin_theme(\$contents);\n";
        $php .= "include NV_ROOTDIR . '/includes/footer.php';\n";
        
        return $php;
    }

    /**
     * Sinh mã nguồn file giao diện config.tpl
     * @param ModuleConfigEntity $entity
     * @return string
     */
    public function generateTPL(ModuleConfigEntity $entity): string
    {
        $groups = $entity->getGroups();
        
        $tpl = "<form action=\"\" method=\"post\" class=\"ajax-submit\">\n";
        $tpl .= "    <input type=\"hidden\" name=\"save\" value=\"1\">\n";
        $tpl .= "    <input type=\"hidden\" name=\"checkss\" value=\"{\$CHECKSS}\">\n\n";
        
        foreach ($groups as $group) {
            $cols  = max(1, min(3, (int)($group['cols'] ?? 1)));
            $title = htmlspecialchars($group['title'] ?? '', ENT_QUOTES);

            $tpl .= "    <div class=\"card mb-4 border-0 shadow-sm\">\n";
            $tpl .= "        <div class=\"card-header bg-primary-subtle py-3\">\n";
            $tpl .= "            <h5 class=\"mb-0 text-primary fw-bold\">" . $title . "</h5>\n";
            $tpl .= "        </div>\n";
            $tpl .= "        <div class=\"card-body\">\n";

            if ($cols === 1) {
                // Layout 1 cột: label trái, input phải (horizontal)
                foreach ($group['fields'] as $key => $field) {
                    $label = htmlspecialchars($field['label'] ?? '', ENT_QUOTES);
                    $tpl .= "            <div class=\"row mb-3 align-items-center\">\n";
                    $tpl .= "                <label class=\"col-sm-3 col-form-label fw-bold\">" . $label . "</label>\n";
                    $tpl .= "                <div class=\"col-sm-9\">\n";
                    $tpl .= $this->renderField($key, $field);
                    if (!empty($field['note'])) {
                        $tpl .= "                    <div class=\"form-text mt-1 text-muted\">" . htmlspecialchars($field['note'], ENT_QUOTES) . "</div>\n";
                    }
                    $tpl .= "                </div>\n";
                    $tpl .= "            </div>\n";
                }
            } else {
                // Layout 2-3 cột: Bootstrap grid, label trên input (responsive)
                $colClass = ($cols === 3) ? 'col-12 col-md-6 col-xl-4' : 'col-12 col-md-6';
                $tpl .= "            <div class=\"row g-3\">\n";
                foreach ($group['fields'] as $key => $field) {
                    $label   = htmlspecialchars($field['label'] ?? '', ENT_QUOTES);
                    $divCols = ($field['span'] ?? '') === 'full' ? 'col-12' : $colClass;
                    $tpl .= "                <div class=\"" . $divCols . "\">\n";
                    $tpl .= "                    <label class=\"form-label fw-bold\">" . $label . "</label>\n";
                    $tpl .= $this->renderField($key, $field);
                    if (!empty($field['note'])) {
                        $tpl .= "                    <div class=\"form-text mt-1 text-muted\">" . htmlspecialchars($field['note'], ENT_QUOTES) . "</div>\n";
                    }
                    $tpl .= "                </div>\n";
                }
                $tpl .= "            </div>\n";
            }

            $tpl .= "        </div>\n";
            $tpl .= "    </div>\n";
        }
        
        $tpl .= "    <div class=\"text-center sticky-bottom bg-body py-3 border-top shadow-lg\" style=\"bottom: 0px; z-index: 1000;\">\n";
        $tpl .= "        <button type=\"submit\" class=\"btn btn-lg btn-primary px-5 shadow\">\n";
        $tpl .= "            <i class=\"fa-solid fa-floppy-disk me-2\"></i>{\$LANG->getGlobal('save')}\n";
        $tpl .= "        </button>\n";
        $tpl .= "    </div>\n";
        $tpl .= "</form>\n";
        
        return $tpl;
    }

    /**
     * Render HTML Smarty cho từng loại field
     */
    private function renderField(string $key, array $field): string
    {
        $type = $field['type'] ?? 'textbox';
        $name = $key;
        $val      = '{$CONFIG.' . $key . '}';  // Dùng trong text context HTML
        $bare_var = '$CONFIG.' . $key;          // Dùng bên trong Smarty tag
        
        switch ($type) {
            case 'number':
                $html = '                    <input type="number" name="' . $name . '" value="' . $val . '" class="form-control"';
                if (isset($field['min']) && $field['min'] !== '') {
                    $html .= ' min="' . $field['min'] . '"';
                }
                if (isset($field['max']) && $field['max'] !== '') {
                    $html .= ' max="' . $field['max'] . '"';
                }
                $html .= ">\n";
                return $html;
            
            case 'date':
                $class = ($field['display'] ?? 'datepicker') === 'datetimepicker' ? 'datetimepicker' : 'datepicker';
                return '                    <div class="input-group">
                        <input type="text" name="' . $name . '" value="' . $val . '" class="form-control ' . $class . '" readonly>
                        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                    </div>' . "\n";
            
            case 'textarea':
                return '                    <textarea name="' . $name . '" class="form-control" rows="4">' . $val . "</textarea>\n";
                
            case 'editor':
                return '                    {nv_editor("' . $name . '", "100%", "300px", ' . $val . ")}\n";
                
            case 'checkbox_single':
                return '                    <div class="form-check form-switch">
                        <input type="checkbox" name="' . $name . '" value="1" {if ' . $val . ' eq \'1\'}checked{/if} class="form-check-input" style="cursor: pointer; width: 2.5em; height: 1.25em;">
                    </div>' . "\n";
            
            case 'selectbox':
                $html = '                    <select name="' . $name . '" class="form-select">' . "\n";
                if (($field['source'] ?? 'static') === 'static') {
                    foreach ($field['options_static'] ?? [] as $opt) {
                        $html .= '                        <option value="' . $opt['key'] . '" {if ' . $val . ' eq \'' . $opt['key'] . '\'}selected{/if}>' . htmlspecialchars($opt['val'], ENT_QUOTES) . '</option>' . "\n";
                    }
                } else {
                    $html .= '                        {foreach from=$DATA.options.' . $key . ' key=opt_k item=opt_v}' . "\n";
                    $html .= '                        <option value="{$opt_k}" {if ' . $val . ' eq $opt_k}selected{/if}>{$opt_v}</option>' . "\n";
                    $html .= '                        {/foreach}' . "\n";
                }
                $html .= "                    </select>\n";
                return $html;
            
            case 'radio':
                $html = '                    <div class="d-flex flex-wrap gap-3 mt-1">' . "\n";
                if (($field['source'] ?? 'static') === 'static') {
                    foreach ($field['options_static'] ?? [] as $opt) {
                        $html .= '                        <div class="form-check">
                            <input type="radio" name="' . $name . '" id="' . $name . '_' . $opt['key'] . '" value="' . $opt['key'] . '" {if ' . $val . ' eq \'' . $opt['key'] . '\'}checked{/if} class="form-check-input">
                            <label class="form-check-label" for="' . $name . '_' . $opt['key'] . '">' . htmlspecialchars($opt['val'], ENT_QUOTES) . '</label>
                        </div>' . "\n";
                    }
                } else {
                    $html .= '                        {foreach from=$DATA.options.' . $key . ' key=opt_k item=opt_v}' . "\n";
                    $html .= '                        <div class="form-check">
                            <input type="radio" name="' . $name . '" id="' . $name . '_{$opt_k}" value="{$opt_k}" {if ' . $val . ' eq $opt_k}checked{/if} class="form-check-input">
                            <label class="form-check-label" for="' . $name . '_{$opt_k}">{$opt_v}</label>
                        </div>' . "\n";
                    $html .= '                        {/foreach}' . "\n";
                }
                $html .= "                    </div>\n";
                return $html;

            case 'checkbox':
                $html = '                    <div class="d-flex flex-wrap gap-3 mt-1">' . "\n";
                $html .= '                        {assign var="current_vals" value=' . $bare_var . '|split:","}' . "\n";
                if (($field['source'] ?? 'static') === 'static') {
                    foreach ($field['options_static'] ?? [] as $opt) {
                        $html .= '                        <div class="form-check">
                            <input type="checkbox" name="' . $name . '[]" id="' . $name . '_' . $opt['key'] . '" value="' . $opt['key'] . '" {if in_array(\'' . $opt['key'] . '\', $current_vals)}checked{/if} class="form-check-input">
                            <label class="form-check-label" for="' . $name . '_' . $opt['key'] . '">' . htmlspecialchars($opt['val'], ENT_QUOTES) . '</label>
                        </div>' . "\n";
                    }
                } else {
                    $html .= '                        {foreach from=$DATA.options.' . $key . ' key=opt_k item=opt_v}' . "\n";
                    $html .= '                        <div class="form-check">
                            <input type="checkbox" name="' . $name . '[]" id="' . $name . '_{$opt_k}" value="{$opt_k}" {if in_array($opt_k, $current_vals)}checked{/if} class="form-check-input">
                            <label class="form-check-label" for="' . $name . '_{$opt_k}">{$opt_v}</label>
                        </div>' . "\n";
                    $html .= '                        {/foreach}' . "\n";
                }
                $html .= "                    </div>\n";
                return $html;

            case 'multiselect':
            case 'multiselectbox':
                $html = '                    <select multiple name="' . $name . '[]" class="form-select">' . "\n";
                $html .= '                        {assign var="current_vals" value=' . $bare_var . '|split:","}' . "\n";
                if (($field['source'] ?? 'static') === 'static') {
                    foreach ($field['options_static'] ?? [] as $opt) {
                        $html .= '                        <option value="' . $opt['key'] . '" {if in_array(\'' . $opt['key'] . '\', $current_vals)}selected{/if}>' . htmlspecialchars($opt['val'], ENT_QUOTES) . '</option>' . "\n";
                    }
                } else {
                    $html .= '                        {foreach from=$DATA.options.' . $key . ' key=opt_k item=opt_v}' . "\n";
                    $html .= '                        <option value="{$opt_k}" {if in_array($opt_k, $current_vals)}selected{/if}>{$opt_v}</option>' . "\n";
                    $html .= '                        {/foreach}' . "\n";
                }
                $html .= "                    </select>\n";
                return $html;

            case 'file':
                return '                    <div class="input-group">
                        <input type="text" name="' . $name . '" id="' . $name . '" value="' . $val . '" class="form-control">
                        <button type="button" class="btn btn-outline-secondary" data-toggle="selectfile" data-target="' . $name . '"><i class="fa-solid fa-folder-open"></i></button>
                    </div>' . "\n";

            default:
                return '                    <input type="text" name="' . $name . '" value="' . $val . '" class="form-control">' . "\n";
        }
    }

    /**
     * Cập nhật admin.functions.php và admin.menu.php của module mục tiêu.
     * Config ops chỉ dành cho NV_IS_SPADMIN — thêm vào block đó, không phải $allow_func chính.
     *
     * @param string $nvRootDir   NV_ROOTDIR
     * @param string $module      Tên module (vd: Content)
     * @param string $op          Tên op (vd: config-seo)
     * @param string $menuLabel   Nhãn menu (nếu để trống dùng op name)
     * @return array              Danh sách các file đã được patch
     */
    public function patchAdminFiles(string $nvRootDir, string $module, string $op, string $menuLabel = ''): array
    {
        $patched = [];
        $root    = rtrim($nvRootDir, '/\\');

        // ── 1. admin.functions.php ───────────────────────────────────────────
        $funcPath = $root . '/modules/' . $module . '/admin.functions.php';
        if (file_exists($funcPath)) {
            $content = file_get_contents($funcPath);

            // Bỏ qua nếu op đã tồn tại trong file
            if (!preg_match("/['\"]" . preg_quote($op, '/') . "['\"]/", $content)) {
                $newLine = "    \$allow_func[] = '{$op}';\n";

                if (preg_match('/if\s*\(\s*defined\s*\(\s*[\'"]NV_IS_SPADMIN[\'"]\s*\)\s*\)/i', $content)) {
                    // Chèn vào trước dấu } cuối của block NV_IS_SPADMIN
                    $content = preg_replace_callback(
                        '/(if\s*\(\s*defined\s*\(\s*[\'"]NV_IS_SPADMIN[\'"]\s*\)\s*\)\s*\{)(.*?)(\})/is',
                        fn($m) => $m[1] . $m[2] . $newLine . $m[3],
                        $content
                    );
                } else {
                    // Tạo block mới cuối file
                    $content = rtrim($content) . "\n\nif (defined('NV_IS_SPADMIN')) {\n{$newLine}}\n";
                }

                file_put_contents($funcPath, $content);
                $patched[] = 'modules/' . $module . '/admin.functions.php';
            }
        }

        // ── 2. admin.menu.php ────────────────────────────────────────────────
        $menuPath = $root . '/modules/' . $module . '/admin.menu.php';
        if (file_exists($menuPath)) {
            $content = file_get_contents($menuPath);

            // Bỏ qua nếu submenu[op] đã tồn tại
            if (!preg_match("/\\\$submenu\s*\[\s*['\"]" . preg_quote($op, '/') . "['\"]\s*\]/i", $content)) {
                $label   = !empty($menuLabel)
                    ? "'" . addslashes($menuLabel) . "'"
                    : "'" . addslashes($op) . "'";
                $newLine = "    \$submenu['{$op}'] = {$label};\n";

                if (preg_match('/if\s*\(\s*defined\s*\(\s*[\'"]NV_IS_SPADMIN[\'"]\s*\)\s*\)/i', $content)) {
                    $content = preg_replace_callback(
                        '/(if\s*\(\s*defined\s*\(\s*[\'"]NV_IS_SPADMIN[\'"]\s*\)\s*\)\s*\{)(.*?)(\})/is',
                        fn($m) => $m[1] . $m[2] . $newLine . $m[3],
                        $content
                    );
                } else {
                    $content = rtrim($content) . "\n\nif (defined('NV_IS_SPADMIN')) {\n{$newLine}}\n";
                }

                file_put_contents($menuPath, $content);
                $patched[] = 'modules/' . $module . '/admin.menu.php';
            }
        }

        return $patched;
    }
}
