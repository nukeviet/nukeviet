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

use NukeViet\Core\Request;

/**
 * Class ModuleConfigService
 * @package NukeViet\Module\Devtool\ModuleConfig
 */
class ModuleConfigService
{
    private ModuleConfigRepository $repository;
    private \NukeViet\Module\Devtool\Schema\SchemaRepository $schemaRepository;

    /**
     * ModuleConfigService constructor.
     * @param ModuleConfigRepository $repository
     * @param \NukeViet\Module\Devtool\Schema\SchemaRepository $schemaRepository
     */
    public function __construct(ModuleConfigRepository $repository, \NukeViet\Module\Devtool\Schema\SchemaRepository $schemaRepository)
    {
        $this->repository = $repository;
        $this->schemaRepository = $schemaRepository;
    }

    /**
     * Lấy danh sách các module đang cài đặt
     * @param \NukeViet\Database\Connection $db
     * @return array
     */
    public function getInstalledModules($db): array
    {
        $sql = "SELECT title, custom_title FROM " . NV_MODULES_TABLE . " ORDER BY weight ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * Lấy danh sách các bảng liên quan đến module
     * @param \NukeViet\Database\Connection $db
     * @param string $module
     * @return array
     */
    public function getTables($db, string $module = ''): array
    {
        // Sử dụng logic lọc chuẩn từ SchemaRepository
        global $db_config;
        $prefix = $db_config['prefix'] ?? 'nv5';
        
        return $this->schemaRepository->getFilteredTablesList($module, $prefix);
    }

    /**
     * Lấy danh sách các cột của một bảng
     * @param \NukeViet\Database\Connection $db
     * @param string $table
     * @return array
     */
    public function getColumns($db, string $table): array
    {
        if (empty($table)) return [];
        
        $columns = $this->schemaRepository->getTableColumns($table);
        return array_keys($columns);
    }

    /**
     * Chuẩn bị dữ liệu ban đầu cho module selected
     * @param string $module
     * @param string $lang
     * @return ModuleConfigEntity
     */
    public function prepareInitialMetadata(string $module, string $lang, string $op = 'config'): ModuleConfigEntity
    {
        $existing = $this->repository->loadMetadata($module, $op);
        if ($existing) {
            $existing->setGroups($this->normalizeGroups($existing->getGroups()));
            return $existing;
        }

        $keys = $this->repository->getModuleConfigKeys($module, $lang);
        $fields = [];
        foreach ($keys as $key) {
            $fields[$key] = $this->normalizeFieldForDisplay([
                'type'    => 'textbox',
                'label'   => $key,
                'note'    => '',
                'default' => ''
            ]);
        }

        return new ModuleConfigEntity($module, $op, [
            [
                'title'  => 'config_common',
                'cols'   => 1,
                'fields' => $fields
            ]
        ]);
    }

    /**
     * Chuẩn hoá toàn bộ groups (thêm default cho các key có thể thiếu khi load từ JSON)
     */
    private function normalizeGroups(array $groups): array
    {
        foreach ($groups as &$group) {
            if (!isset($group['cols'])) {
                $group['cols'] = 1;
            }
            foreach ($group['fields'] as &$field) {
                $field = $this->normalizeFieldForDisplay($field);
            }
            unset($field);
        }
        unset($group);
        return $groups;
    }

    /**
     * Đảm bảo field có đủ tất cả key cần thiết cho template hiển thị
     */
    private function normalizeFieldForDisplay(array $field): array
    {
        $defaults = [
            'type'           => 'textbox',
            'label'          => '',
            'default'        => '',
            'note'           => '',
            'min'            => '',
            'max'            => '',
            'display'        => 'datepicker',
            'validate'       => '',
            'source'         => 'static',
            'span'           => '',
            'options_static' => [],
            'options_db'     => ['module' => '', 'table' => '', 'key_col' => '', 'val_col' => ''],
        ];

        $result = array_merge($defaults, $field);

        // Xử lý nested options_db
        $result['options_db'] = array_merge(
            $defaults['options_db'],
            is_array($result['options_db']) ? $result['options_db'] : []
        );

        // Đảm bảo mỗi option tĩnh có key 'default'
        if (!empty($result['options_static'])) {
            foreach ($result['options_static'] as &$opt) {
                if (!isset($opt['default'])) {
                    $opt['default'] = 0;
                }
            }
            unset($opt);
        }

        return $result;
    }

    /**
     * Thu thập dữ liệu từ request để tạo Entity
     * @param Request $request
     * @return ModuleConfigEntity
     */
    public function collectFromRequest(Request $request): ModuleConfigEntity
    {
        $module = $request->get_string('target_module', 'post', '');
        $op_raw = $request->get_string('op_name', 'post', 'config');
        $op     = preg_replace('/[^a-z0-9\-]/', '', strtolower(trim($op_raw))) ?: 'config';
        $groups_raw = $request->get_array('groups', 'post', []);
        
        $groups = [];
        foreach ($groups_raw as $g_idx => $g_data) {
            $group = [
                'title'  => $g_data['title'] ?? '',
                'cols'   => max(1, min(3, (int)($g_data['cols'] ?? 1))),
                'fields' => []
            ];
            if (!empty($g_data['fields']) && is_array($g_data['fields'])) {
                foreach ($g_data['fields'] as $f_idx => $f_data) {
                    $key = $f_data['key'] ?? $f_idx;
                    unset($f_data['key']);

                    // Làm sạch dữ liệu: Chỉ giữ lại các trường liên quan đến kiểu dữ liệu
                    $f_data = $this->pruneFieldData($f_data);

                    $group['fields'][$key] = $f_data;
                }
            }
            $groups[] = $group;
        }
        
        return new ModuleConfigEntity($module, $op, $groups);
    }

    /**
     * Loại bỏ các trường thừa không liên quan đến type
     * @param array $data
     * @return array
     */
    private function pruneFieldData(array $data): array
    {
        $type = $data['type'] ?? 'textbox';
        
        // Các trường cơ bản luôn giữ
        $base_keys = ['type', 'label', 'default', 'note', 'span'];
        $relevant_keys = [];

        switch ($type) {
            case 'number':
                $relevant_keys = ['min', 'max'];
                break;
            case 'date':
                $relevant_keys = ['display'];
                break;
            case 'textbox':
            case 'textarea':
                $relevant_keys = ['validate'];
                break;
            case 'checkbox_single':
                $relevant_keys = []; // Boolean only needs default
                break;
            case 'selectbox':
            case 'radio':
            case 'checkbox':
            case 'multiselect':
                $relevant_keys = ['source'];
                if (($data['source'] ?? 'static') === 'static') {
                    $relevant_keys[] = 'options_static';
                    // Làm sạch tùy chọn tĩnh
                    if (!empty($data['options_static'])) {
                        $data['options_static'] = array_values(array_filter($data['options_static'], function($opt) {
                            return !empty($opt['key']) || !empty($opt['val']);
                        }));
                    }
                } else {
                    $relevant_keys[] = 'options_db';
                }
                break;
        }

        $all_allowed = array_merge($base_keys, $relevant_keys);
        
        return array_filter($data, function($key) use ($all_allowed) {
            return in_array($key, $all_allowed);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Lưu thực thể
     * @param ModuleConfigEntity $entity
     * @return bool
     */
    public function save(ModuleConfigEntity $entity): bool
    {
        return $this->repository->saveMetadata($entity);
    }
}
