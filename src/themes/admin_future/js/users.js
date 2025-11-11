/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

"use strict";

$(function () {
    // Chép vào bộ nhớ tạm
    $('.copy-btn').each(function () {
        const clipboard = new ClipboardJS(this);
        clipboard.on('success', function(e) {
            nvToast($(e.trigger).data('success'), 'success');
        });
    });
});
