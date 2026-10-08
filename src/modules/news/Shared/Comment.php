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

namespace NukeViet\Module\news\Shared;

use NukeViet\Comment\ICommentable;

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * Xác định quyền bình luận bài viết của module news
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

        // Bình luận bài viết chỉ ở khu vực trang chi tiết
        $funcAlias = $modInfo['alias']['detail'] ?? '';
        if (!isset($modInfo['funcs'][$funcAlias]) or $area !== (string) $modInfo['funcs'][$funcAlias]['func_id']) {
            return null;
        }

        $tablePrefix = NV_PREFIXLANG . '_' . $modInfo['module_data'];
        $row = $db->query('SELECT r.catid, r.status, r.publtime, r.exptime, r.allowed_comm, d.group_view
            FROM ' . $tablePrefix . '_rows r
            INNER JOIN ' . $tablePrefix . '_detail d ON r.id = d.id
            WHERE r.id = ' . (int) $id)->fetch();
        if (empty($row)) {
            return null;
        }

        // Xác định chuyên mục bài viết và chỉ lấy chuyên mục hiệu lực
        $sql = 'SELECT * FROM ' . $tablePrefix . '_cat WHERE status IN(1,2) ORDER BY sort ASC';
        $cats = $nv_Cache->db($sql, 'catid', $moduleName);
        if (!isset($cats[$row['catid']])) {
            return null;
        }

        if (Posts::checkView($row, (string) $cats[$row['catid']]['groups_view'], !empty($modInfo['is_modadmin'])) !== Posts::VIEW_OK) {
            return null;
        }

        return (string) $row['allowed_comm'];
    }
}
