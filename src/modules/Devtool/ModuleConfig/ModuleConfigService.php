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
        $sql = "SELECT title, custom_title FROM " . NV_MODULES_TABLE . " ORDER BY title ASC";
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
    public function prepareInitialMetadata(string $module, string $lang): ModuleConfigEntity
    {
        $existing = $this->repository->loadMetadata($module);
        if ($existing) {
            return $existing;
        }

        $keys = $this->repository->getModuleConfigKeys($module, $lang);
        $fields = [];
        foreach ($keys as $key) {
            $fields[$key] = [
                'type' => 'textbox',
                'label' => $key,
                'note' => '',
                'default' => ''
            ];
        }

        return new ModuleConfigEntity($module, [
            [
                'title' => 'config_common',
                'fields' => $fields
            ]
        ]);
    }

    /**
     * Thu thập dữ liệu từ request để tạo Entity
     * @param Request $request
     * @return ModuleConfigEntity
     */
    public function collectFromRequest(Request $request): ModuleConfigEntity
    {
        $module = $request->get_string('target_module', 'post', '');
        $groups_raw = $request->get_array('groups', 'post', []);
        
        $groups = [];
        foreach ($groups_raw as $g_idx => $g_data) {
            $group = [
                'title' => $g_data['title'] ?? '',
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
        
        return new ModuleConfigEntity($module, $groups);
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
        $base_keys = ['type', 'label', 'default', 'note'];
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
