/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

$(document).ready(function() {
    $("img.imgstatnkv").attr("src","//static.nukeviet.vn/img.jpg");

    // Thiết lập chế độ giao diện
    $('#nv_collapsed_leftsidebar').on('change', function() {
        var collapsed = $(this).is(':checked');
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + $('#main-admin-tab-settings').data('action') + '&nocache=' + new Date().getTime(),
            'nv_change_theme_config=1&collapsed_leftsidebar=' + (collapsed ? 1 : 0), function(res) {
            if (res == 'OK') {
                $('.' + nvThemeCfg.toggleLeftSidebarBtnClass).trigger('click');
            }
        });
    });

    // Chọn ảnh
    $('[data-toggle="browsefile"]').on('click', function(e) {
        e.preventDefault();
        var path = $(this).data('path');
        var currentpath = $(this).data('cpath');
        var area = $(this).data('area');
        var type = $(this).data('type');
        if (!type) {
            type = 'file';
        }
        var currentfile = $('#' + area).val();
        nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=upload&popup=1&area=' + area + '&path=' + path + '&type=' + type + '&currentpath=' + currentpath + '&currentfile=' + currentfile, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
    });
});
