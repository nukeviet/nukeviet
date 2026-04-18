<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\Module\Content\Shared;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

use NukeViet\Module\Content\Shared\Contracts\HasStatus;
use NukeViet\Module\Content\Shared\Contracts\HasWeight;

/**
 * BaseCrudService — Lớp cha trừu tượng cho các Service CRUD.
 *
 * Tập trung logic dùng chung:
 * - saveEntity(): tự động gán timestamps, weight (nếu HasWeight), phát hook
 * - deleteEntity(): tự động reorder weight sau xóa (nếu HasWeight), phát hook
 * - changeStatus(): tự động toggle status (nếu HasStatus), phát hook
 * - changeWeight(): tự động reorder weight (nếu HasWeight), phát hook
 *
 * Naming convention cho Hook: {entityName}_{action}
 * VD: entityName = 'cat' → hooks: cat_saved, cat_deleted, cat_status_changed
 *
 * Class con phải khai báo:
 * - entityName(): tên định danh Entity (dùng cho Hook name), VD: 'cat', 'content'
 * - repo(): Repository instance
 */
abstract class BaseCrudService
{
    /**
     * Tên định danh Entity — dùng để tạo tên Hook tự động.
     * VD: 'cat' → hooks: cat_saved, cat_deleted, cat_status_changed
     */
    abstract protected function entityName(): string;

    /**
     * Repository instance.
     */
    abstract protected function repo(): BaseRepository;

    /**
     * Lưu Entity (INSERT hoặc UPDATE).
     * Tự động:
     * - Gán edit_time
     * - Gán add_time + weight (khi thêm mới, Entity implement HasWeight)
     * - Phát hook before_{entityName}_save và {entityName}_saved
     *
     * @param array  $data        Dữ liệu đã chuẩn hóa và validate
     * @param int    $id          Khóa chính (0 = thêm mới)
     * @param string $module_name Tên module để phát hook
     * @param array  $options     Tùy chọn: ['news_first' => bool, 'admin_id' => int]
     * @return int   Khóa chính của bản ghi vừa lưu
     */
    public function saveEntity(array $data, int $id, string $module_name, array $options = []): int
    {
        $repo = $this->repo();

        if ($id > 0) {
            $data['edit_time'] = NV_CURRENTTIME;
        } else {
            if ($repo->entitySupports(HasWeight::class)) {
                if (!empty($options['news_first'])) {
                    $data['weight'] = 1;
                    // Repo con cần implement incrementOthersWeight() nếu dùng news_first
                    if (method_exists($repo, 'incrementOthersWeight')) {
                        $repo->incrementOthersWeight();
                    }
                } else {
                    $data['weight'] = $repo->getMaxWeight() + 1;
                }
            }
            if (!empty($options['admin_id'])) {
                $data['admin_id'] = (int) $options['admin_id'];
            }
            $data['add_time']  = NV_CURRENTTIME;
            $data['edit_time'] = NV_CURRENTTIME;
        }

        // Hook trước khi lưu — cho phép plugin thay đổi data
        $hookName = 'before_' . $this->entityName() . '_save';
        $data     = nv_apply_hook($module_name, $hookName, [$data], $data);

        $savedId = $repo->save($data, $id);
        $repo->invalidateCache();

        nv_apply_hook($module_name, $this->entityName() . '_saved', [
            'id'     => $savedId,
            'title'  => $data['title'] ?? '',
            'action' => $id ? 'edit' : 'add',
        ]);

        return $savedId;
    }

    /**
     * Xóa Entity.
     * Tự động reorder weight sau khi xóa (nếu Entity implement HasWeight).
     * Phát hook {entityName}_deleted.
     *
     * @param int    $id          Khóa chính
     * @param string $module_name Tên module để phát hook
     * @param array  $options     Tùy chọn: ['comment_table' => string]
     * @return bool  true nếu xóa thành công
     */
    public function deleteEntity(int $id, string $module_name, array $options = []): bool
    {
        $repo = $this->repo();

        // Lấy thông tin trước khi xóa (để đưa vào hook)
        $row = $repo->findById($id);
        if (!$row) {
            return false;
        }

        // Repo cat không có commentTable, repo content có thể có
        $args = [$id];
        if (!empty($options['comment_table'])) {
            $args[] = $options['comment_table'];
        }

        $result = $repo->delete(...$args);

        if ($result) {
            if ($repo->entitySupports(HasWeight::class)) {
                $repo->reorderWeight();
            }
            $repo->invalidateCache();
            nv_apply_hook($module_name, $this->entityName() . '_deleted', [
                'id'    => $id,
                'title' => $row->title ?? '',
            ]);
        }

        return $result;
    }

    /**
     * Đổi trạng thái Entity (toggle 0 ↔ 1).
     * Yêu cầu Entity implement HasStatus (ném \LogicException nếu không).
     *
     * @return int Trạng thái mới, -1 nếu không tìm thấy bản ghi
     * @throws \LogicException nếu Entity không implement HasStatus
     */
    public function changeStatus(int $id, string $module_name): int
    {
        $repo = $this->repo();

        if (!$repo->entitySupports(HasStatus::class)) {
            throw new \LogicException($this->entityName() . ' không hỗ trợ HasStatus');
        }

        $newStatus = $repo->toggleStatus($id);
        if ($newStatus >= 0) {
            $repo->invalidateCache();
            nv_apply_hook($module_name, $this->entityName() . '_status_changed', [
                'id'         => $id,
                'new_status' => $newStatus,
            ]);
        }

        return $newStatus;
    }

    /**
     * Đổi vị trí (weight) Entity.
     * Yêu cầu Entity implement HasWeight (ném \LogicException nếu không).
     *
     * @return bool true nếu thành công
     * @throws \LogicException nếu Entity không implement HasWeight
     */
    public function changeWeight(int $id, int $newWeight, string $module_name): bool
    {
        $repo = $this->repo();

        if (!$repo->entitySupports(HasWeight::class)) {
            throw new \LogicException($this->entityName() . ' không hỗ trợ HasWeight');
        }

        $row = $repo->findById($id);
        if (!$row) {
            return false;
        }

        $repo->reorderWeight($id, $newWeight);
        $repo->invalidateCache();

        return true;
    }
}
