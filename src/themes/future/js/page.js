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
    // Admin xóa tin
    $('body').on('click', '[data-toggle="nv_del_content"]', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        nukeviet.confirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: btn.data('adminurl') + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(),
                data: {
                    id: btn.data('id'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nukeviet.alert(response.text);
                        return;
                    }
                    if (btn.data('detail')) {
                        window.location.href = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name;
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });
});
