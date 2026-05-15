/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
});

/**
 * Callback riêng cho form xác nhận mật khẩu
 *
 * @param {Object} data
 * @returns
 */
function confirmPassCallback(data) {
    if (!data.redirect && !data.refresh) {
        location.reload();
        return false;
    }
}
