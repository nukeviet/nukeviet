<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Cat;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Shared\BaseCrudService;
use NukeViet\Module\Content\Shared\BaseRepository;

/**
 * CatService — Tầng nghiệp vụ cho Chủ đề.
 * Kế thừa BaseCrudService để tái sử dụng saveEntity, deleteEntity, changeStatus, changeWeight.
 */
class CatService extends BaseCrudService
{
    private CatRepository $repo;

    public function __construct(CatRepository $repo)
    {
        $this->repo = $repo;
    }

    protected function entityName(): string
    {
        return 'cat';
    }

    protected function repo(): BaseRepository
    {
        return $this->repo;
    }

    // ═══════════════════════════════════════
    // READ Operations
    // ═══════════════════════════════════════

    /**
     * Lấy danh sách tất cả chủ đề
     * @return CatEntity[]
     */
    public function getList(bool $activeOnly = false): array
    {
        return $activeOnly ? $this->repo->getAllActive() : $this->repo->getAll();
    }

    /**
     * Lấy danh sách dạng [catid => title] cho form select
     */
    public function getSelectList(bool $activeOnly = false): array
    {
        $cats   = $this->getList($activeOnly);
        $result = [];
        foreach ($cats as $cat) {
            $result[$cat->catid] = $cat->title;
        }
        return $result;
    }

    /**
     * Lấy chi tiết chủ đề
     * @throws \InvalidArgumentException nếu catid không hợp lệ
     * @throws \RuntimeException nếu không tìm thấy
     */
    public function getDetail(int $catid): CatEntity
    {
        if ($catid <= 0) {
            throw new \InvalidArgumentException('catid không hợp lệ');
        }
        $row = $this->repo->findById($catid);
        if (!$row) {
            throw new \RuntimeException('Không tìm thấy chủ đề', 404);
        }
        return $row;
    }

    // ═══════════════════════════════════════
    // WRITE Operations (CUD) — dùng chung BaseCrudService
    // ═══════════════════════════════════════

    /**
     * Thu thập dữ liệu từ Request (Admin & API dùng chung).
     * Gom toàn bộ các hàm nv_Request->get_xxx về một nơi để dễ bảo trì.
     */
    public function collectRequestData($nv_Request, array $defaultData = []): array
    {
        $row             = [];
        $row['title']    = $nv_Request->get_title('title', 'post', '', 250);
        $row['alias']    = $nv_Request->get_title('alias', 'post', '');
        $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
        $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', ''));
        $row['image']    = $nv_Request->get_string('image', 'post', '');
        $row['status']   = $nv_Request->get_int('status', 'post', 1);

        return array_merge($defaultData, $row);
    }

    /**
     * Chuẩn hóa dữ liệu chủ đề trước khi validate/save.
     * Gom logic alias, keywords, image — tránh lặp code giữa admin controller và API.
     */
    public function prepareSaveData(array $data, array $moduleConfig = [], string $moduleUpload = ''): array
    {
        // Alias: tự sinh từ title nếu rỗng
        $alias       = $data['alias'] ?? '';
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

        return $data;
    }

    /**
     * Lưu chủ đề — wrapper cho BaseCrudService::saveEntity()
     * Giữ tên phương thức cũ để Controller không phải thay đổi.
     * @return int catid của bản ghi vừa lưu
     */
    public function saveCat(array $data, int $catid, string $module_name): int
    {
        return $this->saveEntity($data, $catid, $module_name);
    }

    /**
     * Xóa chủ đề — wrapper cho BaseCrudService::deleteEntity()
     */
    public function deleteCat(int $catid, string $module_name): bool
    {
        return $this->deleteEntity($catid, $module_name);
    }
}
