/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

$(document).ready(function(){
    // Kiểm tra CHMOD các thư mục
    $("#checkchmod").on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.hasClass('fa-spin')) {
            return;
        }
        $this.attr('class', 'fas fa-spinner fa-spin');
        $.ajax({
            type : "POST",
            url : $this.data('url'),
            data : "",
            success : function(data) {
                $this.attr('class', 'fas fa-wrench');
                alert(data);
                location.reload();
            }
        });
    });

    // Delete update package
    $('.delete_update_backage').click(function() {
        if (confirm(nv_is_del_confirm[0])) {
            $('#infodetectedupg').append('<div id="dpackagew"><em class="fa fa-spin fa-spinner fa-2x m-bottom upload-fa-loading"></em></div>');
            $.get($(this).attr('href'), function(e) {
                $('#dpackagew').remove()
                if (e == 'OK') {
                    $('#infodetectedupg').slideUp(500, function() {
                        $('#infodetectedupg').remove()
                    });
                } else {
                    alert(e);
                }
            });
        }
        return !1;
    });

    /*
     * Nhật ký hệ thống
     */
    // Xóa form tìm kiếm
    $('#clear-log-search-form').on('click', function() {
        $('#log-search-form').find('[type="text"]').val('');
        $('#log-search-form').find('select option').removeAttr('selected');
        $(".select2").val('').trigger('change');
    });

    // Xóa nhật ký (1 dòng)
    $('[data-toggle="del-log"]').click(function(e) {
        e.preventDefault();
        if (confirm($(this).data('message'))) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=logs_del&nocache=' + new Date().getTime(),
                data: {
                    'id': $(this).data('id')
                },
                cache: false,
                success: function(data) {
                    var s = data.split('_');
                    if (s[0] == 'OK') {
                        location.reload();
                    } else {
                        alert(s[1]);
                    }
                },
                error: function(jqXHR, exception) {
                    alert(nv_is_del_confirm[2]);
                    location.reload();
                }
            });
        }
    });

    // Xóa nhật ký (nhiều dòng)
    $('[data-toggle="del-logs"]').click(function(e) {
        e.preventDefault();
        var ids = [];
        $('#list-logs [name="idcheck[]"]:checked').each(function() {
            ids.push($(this).val());
        });
        if (ids.length <= 0) {
            alert(nv_please_selrow);
            return;
        }
        if (confirm($(this).data('message'))) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=logs_del&nocache=' + new Date().getTime(),
                data: {
                    'listall': ids.join(',')
                },
                cache: false,
                success: function(data) {
                    var s = data.split('_');
                    if (s[0] == 'OK') {
                        location.reload();
                    } else {
                        alert(s[1]);
                    }
                },
                error: function(jqXHR, exception) {
                    alert(nv_is_del_confirm[2]);
                    location.reload();
                }
            });
        }
    });

    // Xóa toàn bộ nhật ký
    $('[data-toggle="del-all-logs"]').click(function(e) {
        e.preventDefault();
        if (confirm($(this).data('message'))) {
            $(this).prop("disabled", true);
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=logs_del&nocache=' + new Date().getTime(),
                data: {
                    'logempty': $(this).data('checksess')
                },
                cache: false,
                success: function(data) {
                    if (data == 'OK') {
                        location.reload();
                    } else {
                        alert(data);
                    }
                },
                error: function(jqXHR, exception) {
                    alert(nv_is_del_confirm[2]);
                    location.reload();
                }
            });
        }
    });

    // Xóa thông báo
    $('[data-toggle="del-notification"]').on('click', function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: {
                    'delete': 1,
                    'id': $(this).data('id')
                },
                cache: false,
                success: function(data) {
                    alert(nv_is_del_confirm[1]);
                    location.reload();
                },
                error: function(jqXHR, exception) {
                    alert(nv_is_del_confirm[2]);
                    location.reload();
                }
            });
        }
    });

    // Đánh dấu đã đọc thông báo
    $('[data-toggle="view-notification"]').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: {
                'setviewed': 1,
                'id': $(this).data('id')
            },
            cache: false,
            success: function(data) {
                location.reload();
            },
            error: function(jqXHR, exception) {
                location.reload();
            }
        });
    });

    // Xóa danh sách thông báo
    $('[data-toggle="del-notifications"]').on('click', function(e) {
        e.preventDefault();
        nv_notification_actions('delete');
    });

    // Đánh dấu đã đọc danh sách thông báo
    $('[data-toggle="view-notifications"]').on('click', function(e) {
        e.preventDefault();
        nv_notification_actions('setviewed');
    });
});

function nv_notification_actions(action) {
    var ids = [];
    $('#list-notifications [name="idcheck[]"]:checked').each(function() {
        ids.push($(this).val());
    });
    if (ids.length <= 0) {
        alert(nv_please_selrow);
        return;
    }
    if (action == 'delete' && !confirm(nv_is_del_confirm[0])) {
        return;
    }
    var postData = {};
    postData[action] = 1;
    postData['ids'] = ids.join(',');
    $.ajax({
        type: 'POST',
        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
        data: postData,
        cache: false,
        success: function(data) {
            location.reload();
        },
        error: function(jqXHR, exception) {
            location.reload();
        }
    });
}
