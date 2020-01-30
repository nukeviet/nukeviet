/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

$(document).ready(function() {
    // Thay đổi thứ tự danh mục
    $('[data-toggle="weightcat"]').on('change', function() {
        var weight = $(this).val();
        var catid = $(this).data('catid');
        $(this).prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(),
            data: {
                'changeweight': 1,
                'catid': catid,
                'new_weight': weight
            },
            cache: false,
            success: function(c) {
                location.reload();
            },
            error: function(jqXHR, exception) {
                location.reload();
            }
        });
    });

    // Xóa danh mục
    $('[data-toggle="delcat"]').on('click', function(e) {
        e.preventDefault();
        var catid = $(this).data('catid');
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=categories&nocache=' + new Date().getTime(),
                data: {
                    'delete': 1,
                    'catid': catid
                },
                cache: false,
                success: function(res) {
                    var r_split = res.split('_');
                    if (r_split[0] != 'OK') {
                        alert(nv_is_change_act_confirm[2]);
                    }
                    location.reload();
                },
                error: function(jqXHR, exception) {
                    location.reload();
                }
            });
        }
    });

    // Xóa mẫu email
    $('[data-toggle="deltpl"]').on('click', function(e) {
        e.preventDefault();
        var emailid = $(this).data('emailid');
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(),
                data: {
                    'delete': 1,
                    'emailid': emailid
                },
                cache: false,
                success: function(res) {
                    var r_split = res.split('_');
                    if (r_split[0] != 'OK') {
                        alert(nv_is_change_act_confirm[2]);
                    }
                    location.reload();
                },
                error: function(jqXHR, exception) {
                    location.reload();
                }
            });
        }
    });

    // Xóa đính kèm
    $(document).delegate('[data-toggle="attdel"]', 'click', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
    });
});
