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

/**
 * CatService — Tầng nghiệp vụ cho Chủ đề
 * Xử lý validation, business logic; không chứa SQL
 */
class CatService
{
    private CatRepository $repo;

    public function __construct(CatRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Lấy danh sách tất cả chủ đề (dùng cho dropdown select)
     * @return array Array dạng [catid => title] hoặc CatEntity[]
     */
    public function getList(bool $activeOnly = false): array
    {
        $cats = $activeOnly ? $this->repo->getAllActive() : $this->repo->getAll();
        return $cats;
    }

    /**
     * Lấy danh sách dạng [catid => title] cho form select
     */
    public function getSelectList(bool $activeOnly = false): array
    {
        $cats = $this->getList($activeOnly);
        $result = [];
        foreach ($cats as $cat) {
            $result[$cat->catid] = $cat->title;
        }
        return $result;
    }

    /**
     * Lấy chi tiết chủ đề
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
        $row['title'] = $nv_Request->get_title('title', 'post', '', 250);
        $row['alias'] = $nv_Request->get_title('alias', 'post', '');
        $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
        $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', ''));
        $row['image'] = $nv_Request->get_string('image', 'post', '');
        $row['status'] = $nv_Request->get_int('status', 'post', 1);

        return array_merge($defaultData, $row);
    }

    /**
     * Chuẩn hóa dữ liệu chủ đề trước khi validate/save.
     * Gom logic alias, keywords, image — tránh lặp code giữa admin controller và API.
     *
     * @param array $data Dữ liệu thô từ controller (title, alias, description, keywords, image, status)
     * @param array $moduleConfig Config module (alias_lower, ...)
     * @param string $moduleUpload Thư mục upload của module (VD: 'content')
     * @return array Dữ liệu đã chuẩn hóa
     */
    public function prepareSaveData(array $data, array $moduleConfig = [], string $moduleUpload = ''): array
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

        return $data;
    }

    /**
     * Lưu chủ đề (save + weight/timestamps + clear cache + hook)
     * Yêu cầu: dữ liệu phải được chuẩn hóa qua prepareSaveData() và
     * thẩm định bởi CatValidator từ vòng ngoài trước khi vào đây
     * @return int CatID của bản ghi vừa lưu
     */
    public function saveCat(array $data, int $catid, string $module_name): int
    {
        // Tự động gắn weight + timestamps
        if ($catid > 0) {
            $data['edit_time'] = NV_CURRENTTIME;
        } else {
            $data['weight'] = $this->repo->getMaxWeight() + 1;
            $data['add_time'] = NV_CURRENTTIME;
            $data['edit_time'] = NV_CURRENTTIME;
        }

        $savedId = $this->repo->save($data, $catid);
        $this->repo->invalidateCache();

        nv_apply_hook($module_name, 'cat_saved', [
            'catid' => $savedId,
            'title' => $data['title'],
            'action' => $catid ? 'edit' : 'add',
        ]);

        return $savedId;
    }

    /**
     * Xóa chủ đề (delete + reorder + clear cache)
     */
    public function deleteCat(int $catid, string $module_name): bool
    {
        $row = $this->repo->findById($catid);
        if (!$row) {
            return false;
        }

        $result = $this->repo->delete($catid);
        if ($result) {
            $this->repo->reorderWeight();
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, 'cat_deleted', [
                'catid' => $catid,
                'title' => $row->title,
            ]);
        }
        return $result;
    }

    /**
     * Đổi trạng thái chủ đề
     * @return int Trạng thái mới (-1 nếu lỗi)
     */
    public function changeStatus(int $catid, string $module_name): int
    {
        $newStatus = $this->repo->toggleStatus($catid);
        if ($newStatus >= 0) {
            $this->repo->invalidateCache();
            nv_apply_hook($module_name, 'cat_status_changed', [
                'catid' => $catid,
                'new_status' => $newStatus,
            ]);
        }
        return $newStatus;
    }

    /**
     * Đổi vị trí (weight) chủ đề
     */
    public function changeWeight(int $catid, int $newWeight, string $module_name): bool
    {
        $row = $this->repo->findById($catid);
        if (!$row) {
            return false;
        }
        $this->repo->reorderWeight($catid, $newWeight);
        $this->repo->invalidateCache();
        return true;
    }
}
