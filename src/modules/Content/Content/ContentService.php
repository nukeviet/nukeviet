<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Content;

use NukeViet\Module\Content\Shared\SchemaHelper;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * ContentService — Tầng nghiệp vụ xử lý Bài viết
 */
class ContentService
{
    private ContentRepository $repo;

    public function __construct(ContentRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Thu thập dữ liệu Cấu hình từ Request (DRY)
     */
    public function collectConfigData($nv_Request): array
    {
        $config = [];
        $config['viewtype'] = $nv_Request->get_int('viewtype', 'post', 0);
        $config['facebookapi'] = $nv_Request->get_string('facebookapi', 'post', '');
        $config['per_page'] = $nv_Request->get_page('per_page', 'post', 20);
        $config['related_articles'] = $nv_Request->get_int('related_articles', 'post', 0);
        $config['news_first'] = $nv_Request->get_int('news_first', 'post', 0);
        $config['copy_page'] = $nv_Request->get_int('copy_page', 'post', 0);
        $config['alias_lower'] = $nv_Request->get_int('alias_lower', 'post', 0);
        $config['socialbutton'] = $nv_Request->get_typed_array('socialbutton', 'post', 'title', []);
        $config['schema_type'] = $nv_Request->get_string('schema_type', 'post', '');
        $config['schema_about'] = $nv_Request->get_string('schema_about', 'post', '');

        return $config;
    }

    /**
     * Chuẩn hóa dữ liệu Cấu hình trước khi lưu
     */
    public function prepareConfigData(array $config, array $global_config, array $socialbuttons): array
    {
        // Xử lý Social Buttons
        $config['socialbutton'] = array_intersect($config['socialbutton'], $socialbuttons);
        if (in_array('zalo', $config['socialbutton'], true) and empty($global_config['zaloOfficialAccountID'])) {
            $config['socialbutton'] = array_diff($config['socialbutton'], ['zalo']);
        }
        $config['socialbutton'] = !empty($config['socialbutton']) ? implode(',', $config['socialbutton']) : '';

        // Kiểm tra Schema hợp lệ
        if (!array_key_exists($config['schema_type'], SchemaHelper::$schema_types)) {
            $config['schema_type'] = 'newsarticle';
        }
        if (!array_key_exists($config['schema_about'], SchemaHelper::$schema_abouts)) {
            $config['schema_about'] = 'organization';
        }

        return $config;
    }

    /**
     * Định dạng dữ liệu Cấu hình trước khi đẩy ra View
     */
    public function formatConfigForView(array $config): array
    {
        $config['socialbutton'] = !empty($config['socialbutton'])
            ? array_map('trim', explode(',', $config['socialbutton']))
            : [];

        return $config;
    }

    // ═══════════════════════════════════════
    // READ Operations
    // ═══════════════════════════════════════

    /**
     * Phân tích URL frontend → xác định chi tiết hay danh sách
     * Gom logic URL parsing ra khỏi controller (Thin Controller)
     *
     * @return array ['mode' => 'detail'|'list', 'id' => int, 'alias' => string, 'page' => int, 'row' => ?ContentEntity]
     */
    public function resolveRoute(array $array_op, int $viewtype = 0): array
    {
        // viewtype = 2: không hiển thị gì (chỉ dùng site_title)
        if ($viewtype == 2) {
            return ['mode' => 'none', 'id' => 0, 'alias' => '', 'page' => 1, 'row' => null];
        }

        $alias = (!empty($array_op) && !empty($array_op[0])) ? $array_op[0] : '';

        // Trang danh sách phân trang
        if (substr($alias, 0, 5) === 'page-') {
            return [
                'mode' => 'list',
                'id' => 0,
                'alias' => '',
                'page' => max(1, (int) substr($alias, 5)),
                'row' => null,
            ];
        }

        // viewtype = 0: nếu không có alias, tự động load bài đầu tiên
        if (empty($alias) && $viewtype == 0) {
            $items = $this->repo->getContentList(0, 1, 1, 1);
            if (!empty($items)) {
                $row = $items[0];
                return [
                    'mode' => 'detail',
                    'id' => $row->id,
                    'alias' => $row->alias,
                    'page' => 1,
                    'row' => $row,
                ];
            }
        }

        // Trang chi tiết
        if (!empty($alias)) {
            $row = $this->repo->findByAlias($alias);
            if ($row) {
                return [
                    'mode' => 'detail',
                    'id' => $row->id,
                    'alias' => $alias,
                    'page' => 1,
                    'row' => $row,
                ];
            }
        }

        // Mặc định: danh sách trang 1
        return ['mode' => 'list', 'id' => 0, 'alias' => '', 'page' => 1, 'row' => null];
    }

    /**
     * Lấy chi tiết bài viết theo ID
     * @throws \RuntimeException nếu không tìm thấy
     */
    public function getDetail(int $id): ContentEntity
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID không hợp lệ');
        }
        $row = $this->repo->findById($id);
        if (!$row) {
            throw new \RuntimeException('Không tìm thấy nội dung', 404);
        }
        return $row;
    }

    /**
     * Lấy danh sách bài viết (frontend, có phân trang)
     */
    public function getList(int $page, int $per_page, int $catid = 0): array
    {
        return [
            'total' => $this->repo->countActive($catid),
            'items' => $this->repo->getContentList($catid, 1, $page, $per_page),
        ];
    }

    /**
     * Build link cho từng item trong danh sách
     */
    public function buildItemLinks(array $items, string $base_url, string $rewrite_exturl): array
    {
        $result = [];
        foreach ($items as $entity) {
            $entity->link = $base_url . '&amp;' . NV_OP_VARIABLE . '=' . $entity->alias . $rewrite_exturl;
            $result[$entity->id] = $entity;
        }
        return $result;
    }

    // ═══════════════════════════════════════
    // WRITE Operations (CUD)
    // ═══════════════════════════════════════

    /**
     * Thu thập dữ liệu từ Request (Admin & API dùng chung)
     * Gom toàn bộ các hàm nv_Request->get_xxx về một nơi để dễ bảo trì.
     *
     * @param \NukeViet\Core\Request $nv_Request
     * @param array $defaultData Dữ liệu mặc định nếu cần gộp
     * @return array
     */
    public function collectRequestData($nv_Request, array $defaultData = []): array
    {
        $row = [];
        $row['catid'] = $nv_Request->get_int('catid', 'post', 0);
        $row['title'] = $nv_Request->get_title('title', 'post', '', 250);
        $row['alias'] = $nv_Request->get_title('alias', 'post', '');
        $row['image'] = $nv_Request->get_string('image', 'post', '');
        $row['imagealt'] = $nv_Request->get_title('imagealt', 'post', '');
        $row['imageposition'] = $nv_Request->get_int('imageposition', 'post', 0);
        $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
        $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
        $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', ''));
        $row['socialbutton'] = $nv_Request->get_int('socialbutton', 'post', 1);
        $row['hot_post'] = $nv_Request->get_int('hot_post', 'post', 0);
        $row['status'] = $nv_Request->get_int('status', 'post', 1);

        // Các trường Schema
        $row['schema_type'] = $nv_Request->get_title('schema_type', 'post', 'newsarticle');
        $row['schema_about'] = $nv_Request->get_title('schema_about', 'post', 'Organization', 50);

        // Layout & Phân quyền
        $row['layout_func'] = $nv_Request->get_title('layout_func', 'post', '');
        $row['activecomm'] = $nv_Request->get_array('activecomm', 'post', []);

        return array_merge($defaultData, $row);
    }

    /**
     * Chuẩn bị dữ liệu cho bản sao từ một Entity có sẵn
     */
    public function duplicateContentData(ContentEntity $source): array
    {
        $data = $source->toArray();
        $data['id'] = 0;
        $data['title'] = $data['title'] . ' (Copy)';
        $data['alias'] = ''; // Để sinh alias mới
        $data['status'] = 0; // Mặc định tắt để an toàn
        return $data;
    }

    /**
     * Chuẩn hóa dữ liệu bài viết trước khi validate/save.
     * Gom logic alias, keywords, image — tránh lặp code giữa admin controller và API.
     *
     * @param array $data Dữ liệu thô từ controller (title, alias, description, keywords, image, status, ...)
     * @param array $moduleConfig Config module (alias_lower, ...)
     * @param string $moduleUpload Thư mục upload của module (VD: 'content')
     * @param array $layoutArray Mảng các layout có sẵn để check hợp lệ
     * @return array Dữ liệu đã chuẩn hóa
     */
    public function prepareSaveData(array $data, array $moduleConfig = [], string $moduleUpload = '', array $layoutArray = []): array
    {
        // Alias: tự sinh từ title nếu rỗng
        $alias = $data['alias'] ?? '';
        $data['alias'] = empty($alias) ? change_alias($data['title']) : change_alias($alias);
        if (!empty($moduleConfig['alias_lower'])) {
            $data['alias'] = strtolower($data['alias']);
        }
        $data['alias'] = nv_substr($data['alias'], 0, 250);

        // Keywords: tự sinh từ title nếu rỗng
        if (empty($data['keywords'])) {
            $data['keywords'] = nv_get_keywords($data['title']);
        }

        // Image: kiểm tra file hợp lệ
        if (!empty($moduleUpload) && isset($data['image'])) {
            $image = $data['image'];
            if (!empty($image) && nv_is_file($image, NV_UPLOADS_DIR . '/' . $moduleUpload)) {
                $data['image'] = substr($image, strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $moduleUpload . '/'));
            } else {
                $data['image'] = '';
            }
        }

        // Xử lý Layout
        if (!empty($data['layout_func']) && !empty($layoutArray)) {
            if (!in_array('layout.' . $data['layout_func'] . '.tpl', $layoutArray, true)) {
                $data['layout_func'] = '';
            }
        }

        // Xử lý Phân quyền xem (Activecomm)
        if (isset($data['activecomm']) && is_array($data['activecomm'])) {
            $groups_list = nv_groups_list();
            $data['activecomm'] = !empty($data['activecomm']) ? implode(',', nv_groups_post(array_intersect($data['activecomm'], array_keys($groups_list)))) : '';
        }

        // Xử lý Schema
        if (isset($data['schema_type'])) {
            if (!array_key_exists($data['schema_type'], SchemaHelper::$schema_types)) {
                $data['schema_type'] = 'newsarticle';
            }
            if ($data['schema_type'] == 'webpage' && empty($data['schema_about'])) {
                $data['schema_about'] = 'Organization';
            }
        }

        return $data;
    }

    /**
     * Lưu bài viết (validate + save + clear cache)
     * Yêu cầu: dữ liệu phải được chuẩn hóa qua prepareSaveData() và
     * thẩm định bởi ContentValidator từ vòng ngoài trước khi vào đây
     * @return int ID của bản ghi vừa lưu
     */
    public function saveContent(array $data, int $id, string $module_name, array $config = [], int $admin_id = 0): int
    {
        // 1. Quản lý các trường hệ thống (System Fields) tự động
        if ($id > 0) {
            // Update bài viết
            $data['edit_time'] = NV_CURRENTTIME;
        } else {
            // Thêm mới hoặc copy bài viết
            if (!isset($data['weight']) || $data['weight'] <= 0) {
                if (!empty($config['news_first'])) {
                    $data['weight'] = 1;
                    $this->repo->incrementOthersWeight();
                } else {
                    $data['weight'] = $this->repo->getMaxWeight() + 1;
                }
            }
            $data['admin_id'] = $admin_id;
            $data['add_time'] = NV_CURRENTTIME;
            $data['edit_time'] = NV_CURRENTTIME;
            $data['status'] = $data['status'] ?? 1;
        }

        // 2. Chạy hooks nếu có
        $data = nv_apply_hook($module_name, 'before_content_save', [$data], $data);

        // 3. Thực thi lưu và xóa cache
        $savedId = $this->repo->save($data, $id);
        $this->repo->invalidateCache();

        // 4. Hook hành động thành công
        nv_apply_hook($module_name, 'content_saved', [
            'id' => $savedId,
            'title' => $data['title'],
            'action' => $id ? 'edit' : 'add',
        ]);

        return $savedId;
    }

    /**
     * Xóa bài viết (delete + reorder + clear cache)
     */
    public function deleteContent(int $id, string $module_name, string $commentTable = ''): bool
    {
        $row = $this->repo->findById($id);
        if (!$row) {
            return false;
        }

        $result = $this->repo->delete($id, $commentTable);
        if ($result) {
            $this->repo->reorderWeight();
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, 'content_deleted', [
                'id' => $id,
                'title' => $row->title,
            ]);
        }
        return $result;
    }

    /**
     * Đổi trạng thái bài viết
     * @return int Trạng thái mới (-1 nếu lỗi)
     */
    public function changeStatus(int $id, string $module_name): int
    {
        $newStatus = $this->repo->toggleStatus($id);
        if ($newStatus >= 0) {
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, 'content_status_changed', [
                'id' => $id,
                'new_status' => $newStatus,
            ]);
        }
        return $newStatus;
    }

    /**
     * Đổi vị trí (weight) bài viết
     */
    public function changeWeight(int $id, int $newWeight, string $module_name): bool
    {
        $row = $this->repo->findById($id);
        if (!$row) {
            return false;
        }
        $this->repo->reorderWeight($id, $newWeight);
        $this->repo->invalidateCache();
        return true;
    }
}
