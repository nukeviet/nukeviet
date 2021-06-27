/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(document).ready(function() {
    $("img.imgstatnkv").attr("src", "//static.nukeviet.vn/img.jpg");

    // Thiết lập chế độ giao diện
    $('#nv_collapsed_leftsidebar').on('change', function() {
        var collapsed = $(this).is(':checked');
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + $('#main-admin-tab-settings').data('action') + '&nocache=' + new Date().getTime(),
            'nv_change_theme_config=1&collapsed_leftsidebar=' + (collapsed ? 1 : 0),
            function(res) {
                if (res == 'OK') {
                    $('.' + nvThemeCfg.toggleLeftSidebarBtnClass).trigger('click');
                }
            });
    });
});
