<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2026 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

declare(strict_types=1);

namespace NukeViet\Module\page\Shared;

use NukeViet\Comment\ICommentable;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Xác định quyền bình luận bài viết của module page
 *
 * @author VINADES.,JSC <contact@vinades.vn>
 */
class Comment implements ICommentable
{
    /**
     * {@inheritDoc}
     * @see \NukeViet\Comment\ICommentable::getAllowed()
     */
    public static function getAllowed(string $moduleName, string $area, string $id): ?string
    {
        global $db, $nv_Cache, $site_mods;

        /**
         * Chú ý: phương thức này được gọi từ các nơi khác nhau trong hệ thống
         * nên bắt buộc phải dùng $site_mods, không dùng $module_name, $module_info,...
         */
        if (!isset($site_mods[$moduleName]) or !preg_match('/^[1-9][0-9]*$/', $id)) {
            return null;
        }
        $modInfo = $site_mods[$moduleName];

        // Bình luận bài viết chỉ ở khu vực main
        $funcAlias = $modInfo['alias']['main'] ?? '';
        if (!isset($modInfo['funcs'][$funcAlias]) or $area !== (string) $modInfo['funcs'][$funcAlias]['func_id']) {
            return null;
        }

        // Kiểm tra cấu hình hiển thị của module
        $list = $nv_Cache->db('SELECT config_name, config_value FROM ' . NV_PREFIXLANG . '_' . $modInfo['module_data'] . '_config', '', $moduleName);
        foreach ($list as $values) {
            // Kiểu hiển thị không có nội dung bài viết
            if ($values['config_name'] == 'viewtype' and $values['config_value'] == 2) {
                return null;
            }
        }

        $row = $db->query('SELECT status, activecomm FROM ' . NV_PREFIXLANG . '_' . $modInfo['module_data'] . ' WHERE id = ' . (int) $id)->fetch();
        if (empty($row)) {
            return null;
        }

        // Bài viết đình chỉ chỉ quản trị mới xem được
        if (empty($row['status']) and empty($modInfo['is_modadmin'])) {
            return null;
        }

        return (string) $row['activecomm'];
    }
}
