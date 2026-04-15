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
 * SchemaEntity — Đại diện cho toàn bộ cấu hình schema của một bảng DB.
 * Bao gồm: page_settings (cấu hình chung) + columns (cấu hình từng cột).
 */
class SchemaEntity
{
    // --- Page Settings ---
    public string $module = '';
    public string $table = '';
    public string $function_name = 'main';
    public string $layout_type = 'list_and_form';
    public string $area = 'admin';
    public string $note = '';

    // --- Features (nằm trong page_settings.features ở JSON) ---
    public bool $pagination = true;
    public bool $search = true;
    public string $menu_label = '';
    public bool $has_detail_view = false;
    public string $active_field = '';
    public string $weight_field = '';
    public string $alias_source_field = '';

    // --- Cấu hình từng cột: [field => config_array] ---
    public array $columns = [];

    /**
     * Tạo Entity từ mảng JSON đã decode.
     * Đọc cấu trúc lồng page_settings.features và columns.
     */
    public static function fromArray(array $data): self
    {
        $entity = new self();
        $ps = $data['page_settings'] ?? [];
        $features = $ps['features'] ?? [];

        $entity->module = (string) ($ps['module'] ?? '');
        $entity->table = (string) ($ps['table'] ?? '');
        $entity->function_name = (string) ($ps['function_name'] ?? 'main');
        $entity->menu_label = (string) ($ps['menu_label'] ?? '');
        $entity->layout_type = (string) ($ps['layout_type'] ?? 'list_and_form');
        $entity->area = (string) ($ps['area'] ?? 'admin');
        $entity->note = (string) ($ps['note'] ?? '');
        $entity->pagination = (bool) ($features['pagination'] ?? true);
        $entity->search = (bool) ($features['search'] ?? true);
        $entity->has_detail_view = (bool) ($features['has_detail_view'] ?? false);
        $entity->active_field = (string) ($features['active_field'] ?? '');
        $entity->weight_field = (string) ($features['weight_field'] ?? '');
        $entity->alias_source_field = (string) ($features['alias_source_field'] ?? '');
        $entity->columns = (array) ($data['columns'] ?? []);

        return $entity;
    }

    /**
     * Chuyển Entity → mảng chuẩn để json_encode lưu file.
     */
    public function toArray(): array
    {
        return [
            'page_settings' => [
                'module' => $this->module,
                'table' => $this->table,
                'function_name' => $this->function_name,
                'menu_label' => $this->menu_label,
                'layout_type' => $this->layout_type,
                'area' => $this->area,
                'note' => $this->note,
                'features' => [
                    'pagination' => $this->pagination,
                    'search' => $this->search,
                    'has_detail_view' => $this->has_detail_view,
                    'active_field' => $this->active_field,
                    'weight_field' => $this->weight_field,
                    'alias_source_field' => $this->alias_source_field,
                ],
            ],
            'columns' => $this->columns,
        ];
    }

    /**
     * Trả mảng page_settings phẳng để truyền vào Smarty template.
     */
    public function getPageSettingsForTpl(): array
    {
        return [
            'function_name' => $this->function_name,
            'menu_label' => $this->menu_label,
            'layout_type' => $this->layout_type,
            'area' => $this->area,
            'note' => $this->note,
            'features' => [
                'pagination' => $this->pagination,
                'search' => $this->search,
                'has_detail_view' => $this->has_detail_view,
                'active_field' => $this->active_field,
                'weight_field' => $this->weight_field,
                'alias_source_field' => $this->alias_source_field,
            ],
        ];
    }
}
