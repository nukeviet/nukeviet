/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if (nv_func_name === 'configs') {
        $('#inform-configs-form').on('input', 'input[data-toggle="digit-only"]', function() {
            const input = $(this);
            input.val(input.val().replace(/[^0-9]/g, ''));
        });
    }
});
