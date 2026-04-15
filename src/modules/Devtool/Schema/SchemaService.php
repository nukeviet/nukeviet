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
 * SchemaService — Tầng nghiệp vụ của module Devtool.
 * Không chứa SQL hay file I/O trực tiếp — ủy thác cho SchemaRepository.
 *
 * Luồng chuẩn:
 *   collectRequestData() → prepareEntity() → [SchemaValidator::validateSave()] → saveSchema()
 */
class SchemaService
{
    public function __construct(private SchemaRepository $repo) {}

    // -------------------------------------------------------------------------
    // Hiển thị (View Data)
    // -------------------------------------------------------------------------

    /**
     * Gợi ý tên function (OP) từ tên bảng DB.
     *
     * Quy tắc:
     *   - Bỏ phần prefix (phần đầu, vd: nv5)
     *   - Bỏ mã ngôn ngữ 2 ký tự nếu có (vd: vi, en)
     *   - Nếu còn ≥ 2 phần → phần cuối là function name
     *   - Nếu chỉ còn 1 phần (tên module) → trả 'main'
     *
     * Ví dụ:
     *   nv5_vi_content_detail → detail
     *   nv5_vi_news_categories → categories
     *   nv5_users → main
     */
    public function suggestFunctionName(string $table): string
    {
        $parts = explode('_', $table);
        // Bỏ prefix (vd: nv5)
        if (count($parts) > 1) {
            array_shift($parts);
        }
        // Bỏ mã ngôn ngữ 2 ký tự (vd: vi, en)
        if (!empty($parts) && preg_match('/^[a-z]{2}$/', $parts[0])) {
            array_shift($parts);
        }
        return count($parts) > 1 ? (string) end($parts) : 'main';
    }

    /**
     * Trả cấu hình page_settings mặc định khi chưa có schema.
     */
    public function getDefaultPageSettings(array $dbColumns = []): array
    {
        $activeField = '';
        $weightField = '';
        $aliasSourceField = '';

        if (!empty($dbColumns)) {
            if (isset($dbColumns['status'])) {
                $activeField = 'status';
            }
            if (isset($dbColumns['weight'])) {
                $weightField = 'weight';
            } elseif (isset($dbColumns['sort'])) {
                $weightField = 'sort';
            }
            if (isset($dbColumns['title'])) {
                $aliasSourceField = 'title';
            } elseif (isset($dbColumns['name'])) {
                $aliasSourceField = 'name';
            }
        }

        return [
            'function_name' => 'main',
            'layout_type' => 'list_and_form',
            'area' => 'admin',
            'note' => '',
            'features' => [
                'pagination' => true,
                'search' => true,
                'active_field' => $activeField,
                'weight_field' => $weightField,
                'alias_source_field' => $aliasSourceField,
            ],
        ];
    }

    /**
     * Xây dựng dữ liệu hiển thị cho từng cột, kết hợp:
     *   - Cấu trúc DB thực tế (từ Repository)
     *   - Cấu hình cũ đã lưu (từ SchemaEntity nếu có)
     *
     * @param array<string, array> $dbColumns  Kết quả từ SchemaRepository::getTableColumns()
     * @param SchemaEntity|null    $existing   Schema đã lưu (null = chưa có)
     * @return array<string, array>            Dữ liệu sẵn sàng truyền cho Smarty
     */
    public function buildColumnViewData(array $dbColumns, ?SchemaEntity $existing): array
    {
        $result = [];
        foreach ($dbColumns as $field => $info) {
            $baseType = $info['base_type'];
            $viewOptions = $this->getViewOptions($baseType);
            $existingConfig = $existing?->columns[$field] ?? [];

            if (empty($existingConfig)) {
                if ($field === 'status' || $field === 'active') {
                    $existingConfig['view_type'] = 'checkbox';
                    $existingConfig['label_vi'] = 'Trạng thái';
                    $existingConfig['list'] = true;
                } elseif (in_array($field, ['weight', 'sort'], true)) {
                    $existingConfig['view_type'] = 'number_int';
                    $existingConfig['label_vi'] = 'Thứ tự';
                    $existingConfig['list'] = true;
                } elseif (in_array($field, ['title', 'name', 'subject'], true)) {
                    $existingConfig['label_vi'] = ($field === 'title') ? 'Tiêu đề' : (($field === 'name') ? 'Tên gọi' : 'Tiêu đề');
                    $existingConfig['list'] = true;
                } elseif (in_array($baseType, ['mediumtext', 'longtext'], true)) {
                    $existingConfig['view_type'] = 'editor';
                    $existingConfig['label_vi'] = 'Nội dung';
                    $existingConfig['list'] = false;
                } elseif (in_array($field, ['admin_id', 'add_time', 'edit_time', 'hitstotal'], true)) {
                    $existingConfig['hidden'] = true;
                    $existingConfig['list'] = ($field === 'add_time'); // Mặc định hiện thời gian tạo
                } else {
                    $existingConfig['list'] = false;
                }
            } else {
                $existingConfig['list'] = !empty($existingConfig['list']);
            }

            // Cột có select/radio → cần thêm panel "Nguồn dữ liệu"
            $hasChoice = isset($viewOptions['select']) || isset($viewOptions['radio']);

            $result[$field] = [
                'field' => $field,
                'sql_type' => $info['sql_type_full'],
                'base_type' => $baseType,
                'view_options' => $viewOptions,
                'default_view' => $this->suggestDefaultView($baseType, $field),
                'db_default' => $info['default'],
                'comment' => $info['comment'],
                'config' => $existingConfig,
                'has_choice' => $hasChoice,
                'choice_config' => [
                    'choice_type' => (string) ($existingConfig['choice_type'] ?? 'static'),
                    'choice_table' => (string) ($existingConfig['choice_table'] ?? ''),
                    'choice_id_col' => (string) ($existingConfig['choice_id_col'] ?? ''),
                    'choice_text_col' => (string) ($existingConfig['choice_text_col'] ?? ''),
                    'choice_values' => (string) ($existingConfig['choice_values'] ?? ''),
                ],
            ];
        }
        return $result;
    }

    // -------------------------------------------------------------------------
    // Thu thập & Chuẩn hóa (Request → Entity)
    // -------------------------------------------------------------------------

    /**
     * Thu thập dữ liệu thô từ HTTP Request.
     * Admin và API đều gọi hàm này — tránh lặp code.
     *
     * @param \NukeViet\Core\Request $nv_Request
     */
    public function collectRequestData($nv_Request, string $module_name): array
    {
        return [
            'table' => $nv_Request->get_title('table', 'post', ''),
            'module_name' => $module_name,
            'target_module' => $nv_Request->get_title('target_module', 'post', $module_name),
            'columns' => $nv_Request->get_array('columns', 'post'),
            'page_settings' => $nv_Request->get_array('page_settings', 'post'),
        ];
    }

    /**
     * Chuẩn hóa mảng thô từ collectRequestData() → SchemaEntity.
     * Chạy TRƯỚC khi gọi Validator.
     */
    public function prepareEntity(array $rawData, string $module_name): SchemaEntity
    {
        $ps = $rawData['page_settings'] ?? [];
        $features = $ps['features'] ?? [];

        $columns = [];
        foreach ((array) ($rawData['columns'] ?? []) as $field => $config) {
            $viewType = (string) ($config['view_type'] ?? 'textbox');
            $choiceType = (string) ($config['choice_type'] ?? '');

            $col = [
                'sql_type' => nv_unhtmlspecialchars((string) ($config['sql_type'] ?? '')),
                'view_type' => $viewType,
                'required' => isset($config['required']),
                'hidden' => isset($config['hidden']),
                'list' => isset($config['list']),
                'label_vi' => (string) ($config['label_vi'] ?? $field),
                'default' => (string) ($config['default'] ?? ''),
                'note' => (string) ($config['note'] ?? ''),
            ];

            if (in_array($viewType, ['select', 'radio'], true) && $choiceType !== '') {
                $col['choice_type'] = $choiceType;
                if ($choiceType === 'sql') {
                    $col['choice_table'] = (string) ($config['choice_table'] ?? '');
                    $col['choice_id_col'] = (string) ($config['choice_id_col'] ?? '');
                    $col['choice_text_col'] = (string) ($config['choice_text_col'] ?? '');
                } else {
                    $col['choice_values'] = (string) ($config['choice_values'] ?? '');
                }
            }

            $columns[(string) $field] = $col;
        }

        $entity = new SchemaEntity();
        $entity->module = (string) ($rawData['target_module'] ?? $module_name);
        $entity->table = (string) ($rawData['table'] ?? '');
        $entity->function_name = (string) ($ps['function_name'] ?? 'main');
        $entity->menu_label = (string) ($ps['menu_label'] ?? '');
        $entity->layout_type = (string) ($ps['layout_type'] ?? 'list_and_form');
        $entity->area = (string) ($ps['area'] ?? 'admin');
        $entity->note = (string) ($ps['note'] ?? '');
        $entity->pagination = isset($features['pagination']);
        $entity->search = isset($features['search']);
        $entity->has_detail_view = isset($features['has_detail_view']);
        $entity->active_field = (string) ($features['active_field'] ?? '');
        $entity->weight_field = (string) ($features['weight_field'] ?? '');
        $entity->alias_source_field = (string) ($features['alias_source_field'] ?? '');
        $entity->columns = $columns;

        return $entity;
    }

    // -------------------------------------------------------------------------
    // Lưu trữ
    // -------------------------------------------------------------------------

    /**
     * Lưu SchemaEntity (qua Repository) và trả về mã gợi ý action_mysql.php.
     */
    public function saveSchema(SchemaEntity $entity, array $dbConfig): string
    {
        $this->repo->saveSchema($entity->table, $entity);
        return '';
    }


    // -------------------------------------------------------------------------

    // -------------------------------------------------------------------------
    // Nội bộ
    // -------------------------------------------------------------------------

    /**
     * Trả danh sách view_options hợp lệ cho từng nhóm kiểu SQL.
     *
     * Quy tắc date/time:
     *   - Chỉ int (4 byte) và bigint (8 byte) mới lưu được Unix timestamp → có date/time
     *   - tinyint (1 byte), smallint (2 byte), mediumint (3 byte) KHÔNG đủ dung lượng → không có date/time
     */
    private function getViewOptions(string $baseType): array
    {
        // Chuỗi ký tự
        if (in_array($baseType, ['varchar', 'char'], true)) {
            return [
                'textbox' => 'Textbox',
                'email' => 'Email',
                'url' => 'URL',
                'textfile' => 'Textbox chọn file',
                'textalias' => 'Textbox alias url',
                'password' => 'Password',
                'select' => 'Selectbox (Load mảng)',
                'radio' => 'Radio (Load mảng)',
                'checkbox' => 'Checkbox (0/1)',
            ];
        }
        // Văn bản dài
        if (in_array($baseType, ['text', 'mediumtext', 'longtext'], true)) {
            return [
                'textbox' => 'Textbox',
                'textarea' => 'Textarea',
                'editor' => 'Editor (CKEditor)',
            ];
        }
        // Số nguyên nhỏ: tinyint (1B), smallint (2B) — không lưu được Unix timestamp
        if (in_array($baseType, ['tinyint', 'smallint'], true)) {
            return [
                'number_int' => 'Số nguyên',
                'checkbox' => 'Checkbox (0/1)',
                'select' => 'Selectbox (Load mảng)',
                'radio' => 'Radio (Load mảng)',
            ];
        }
        // Số nguyên vừa: mediumint (3B) — không lưu được Unix timestamp
        if ($baseType === 'mediumint') {
            return [
                'number_int' => 'Số nguyên',
                'number_float' => 'Số thực',
                'checkbox' => 'Checkbox (0/1)',
                'select' => 'Selectbox (Load mảng)',
                'radio' => 'Radio (Load mảng)',
            ];
        }
        // Số nguyên lớn: int (4B), bigint (8B) — lưu được Unix timestamp
        if (in_array($baseType, ['int', 'bigint'], true)) {
            return [
                'number_int' => 'Số nguyên',
                'number_float' => 'Số thực',
                'date' => 'Ngày/Tháng/Năm',
                'time' => 'Giờ:Phút Ngày/Tháng/Năm',
                'checkbox' => 'Checkbox (0/1)',
                'select' => 'Selectbox (Load mảng)',
                'radio' => 'Radio (Load mảng)',
            ];
        }
        // Số thực
        if (in_array($baseType, ['decimal', 'float', 'double'], true)) {
            return [
                'number_int' => 'Số nguyên',
                'number_float' => 'Số thực',
            ];
        }
        // Kiểu ngày tháng bản địa MySQL (DATE, DATETIME, TIMESTAMP, TIME, YEAR)
        if ($baseType === 'date') {
            return ['date' => 'Ngày/Tháng/Năm'];
        }
        if (in_array($baseType, ['datetime', 'timestamp'], true)) {
            return [
                'time' => 'Giờ:Phút Ngày/Tháng/Năm',
                'date' => 'Chỉ Ngày/Tháng/Năm',
            ];
        }
        if ($baseType === 'time') {
            return ['textbox' => 'Textbox (HH:MM:SS)'];
        }
        if ($baseType === 'year') {
            return ['number_int' => 'Số nguyên (năm)'];
        }
        return ['textbox' => 'Textbox'];
    }

    /**
     * Đề xuất view_type mặc định từ kiểu SQL cơ bản và tên cột.
     */
    private function suggestDefaultView(string $baseType, string $field): string
    {
        if (in_array($baseType, ['text', 'mediumtext', 'longtext'], true)) {
            return 'textarea';
        }
        if ($baseType === 'tinyint') {
            return 'checkbox';
        }
        // smallint, mediumint: không có date/time → luôn trả number_int
        if (in_array($baseType, ['smallint', 'mediumint'], true)) {
            return 'number_int';
        }
        // int, bigint: có thể là timestamp nếu tên cột chứa 'time'
        if (in_array($baseType, ['int', 'bigint'], true)) {
            return str_contains($field, 'time') ? 'time' : 'number_int';
        }
        if (in_array($baseType, ['decimal', 'float', 'double'], true)) {
            return 'number_float';
        }
        // Kiểu ngày tháng bản địa MySQL
        if ($baseType === 'date') {
            return 'date';
        }
        if (in_array($baseType, ['datetime', 'timestamp'], true)) {
            return 'time';
        }
        return 'textbox';
    }
}
