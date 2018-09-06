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

    // Logs
    if( $.fn.datepicker ){
        $("#from,#to").datepicker({
            showOn : "both",
            dateFormat : "dd.mm.yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            buttonText : '{LANG.select}',
            showButtonPanel : true,
            showOn : 'focus'
        });
    }
    $('input[name=clear]').click(function() {
        $('#filter-form .text').val('');
        $('input[name=q]').val(LANG.filter_enterkey);
    });
    $('input[name=action]').click(function() {
        var f_q = $('input[name=q]').val();
        var f_from = $('input[name=from]').val();
        var f_to = $('input[name=to]').val();
        var f_lang = $('select[name=lang]').val();
        var f_module = $('select[name=module]').val();
        var f_user = $('select[name=user]').val();
        if ((f_q != LANG.filter_enterkey && f_q != '' ) || f_from != '' || f_to != '' || f_lang != '' || f_user != '' || f_module != '') {
            $('#filter-form input, #filter-form select').attr('disabled', 'disabled');
            window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + '=logs&filter=1&checksess=' + CFG.checksess + '&q=' + f_q + '&from=' + f_from + '&to=' + f_to + '&lang=' + f_lang + '&module=' + f_module + '&user=' + f_user;
        } else {
            alert(LANG.filter_err_submit);
        }
    });
    $("#check_all").click(function() {
        if ($("#check_all").prop("checked")) {
            $('input.list').prop("checked", true);
        } else {
            $('input.list').prop("checked", false);
        }
    });
    $('#delall').click(function() {
        var listall = [];
        $('input.list:checked').each(function() {
            listall.push($(this).val());
        });
        if (listall.length < 1) {
            alert(LANG.log_del_no_items);
            return false;
        }
        if (confirm(LANG.log_del_confirm)) {
            $.ajax({
                type : 'POST',
                url : CFG.url_del,
                data : 'listall=' + listall,
                success : function(data) {
                    var s = data.split('_');
                    if (s[0] == 'OK'){location.reload();}
                    alert(s[1]);
                }
            });
        }
    });
    $('a.delete').click(function(event) {
        event.preventDefault();
        if (confirm(LANG.log_del_confirm)) {
            var href = $(this).attr('href');
            $.ajax({
                type : 'POST',
                url : href,
                data : '',
                success : function(data) {
                    var s = data.split('_');
                    if (s[0] == 'OK') {
                        location.reload();
                    } else {
                        alert(s[1]);
                    }
                }
            });
        }
    });
    $("#logempty").click(function() {
        if (confirm(LANG.log_del_confirm)) {
            $("#logempty").attr("disabled", "disabled");
            $.ajax({
                type : 'POST',
                url : script_name,
                data : nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=logs_del&logempty=" + CFG.checksess,
                success : function(data) {
                    if (data == 'OK'){window.location = script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=logs";} else {alert(data);}
                    $("#logempty").removeAttr("disabled");
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
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
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
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
        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
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
