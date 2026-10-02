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

namespace NukeViet\Comment;

/**
 * NukeViet\Comment\ICommentable
 *
 * Giao tiếp mà module có chức năng bình luận phải cài đặt tại class
 * NukeViet\Module\<module_file>\Shared\Comment. Module comment không tin bất kỳ
 * thông tin quyền hạn nào từ client mà luôn hỏi module sở hữu nội dung qua giao tiếp này.
 * Module không cài đặt sẽ không được bình luận.
 *
 * @package NukeViet\Comment
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @version 5.x
 * @access public
 */
interface ICommentable
{
    /**
     * Xác định đối tượng được bình luận
     *
     * Trả về null nếu đối tượng không tồn tại, không thuộc khu vực $area
     * hoặc người dùng hiện tại không được xem. Ngược lại trả về chuỗi ID các nhóm
     * được đăng bình luận theo cấu hình riêng của đối tượng, giá trị này chỉ được dùng
     * khi cấu hình allowed_comm của module là -1
     *
     * @param string $moduleName
     * @param string $area
     * @param string $id
     * @return string|null
     */
    public static function getAllowed(string $moduleName, string $area, string $id): ?string;
}
