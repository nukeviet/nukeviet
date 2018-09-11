/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 9:36
 */

function nv_is_del_cron(cronid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.get(script_name + "?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cronjobs_del&id=" + cronid + "&nocache=" + new Date().getTime(), function(res) {
            if (res == 1) {
                alert(nv_is_del_confirm[1]);
                window.location.href = window.location.href;
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_del_plugin(pid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'del=1&pid=' + pid, function(res) {
            location.reload();
        });
    }
}

function nv_change_plugin_weight(pid) {
    var nv_timer = nv_settimeout_disable('weight_' + pid, 3000);
    var new_weight = document.getElementById('weight_' + pid).options[document.getElementById('weight_' + pid).selectedIndex].value;
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'changeweight=1&pid=' + pid + '&new_weight=' + new_weight, function(res) {
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        location.reload();
    });
    return;
}


$(document).ready(function(){
    // System
    $('#cdn_download').click(function() {
        window.location.href = script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cdn&cdndl=' + CFG.cdndl;
    });
    $('[data-toggle="controlrw1"]').change(function() {
        var rewrite_optional = $(this).is(':checked');
        if (rewrite_optional) {
            $('#tr_rewrite_op_mod').show();
        } else {
            $('#tr_rewrite_op_mod').hide();
            $('[name="rewrite_op_mod"]').find('option').prop('selected', false);
        }
    });
    $('[data-toggle="controlrw"]').change(function() {
        var lang_multi = $('[name="lang_multi"]').is(':checked');
        var rewrite_enable = $('[name="rewrite_enable"]').is(':checked');
        if (!lang_multi && rewrite_enable) {
            $('#tr_rewrite_optional').show();
        } else {
            $('#tr_rewrite_optional').hide();
            $('[name="rewrite_optional"]').prop('checked', false);
        }
        $('[data-toggle="controlrw1"]').change();
    });

    // Smtp
    $("input[name=mailer_mode]").click(function() {
        var type = $(this).val();
        if (type == "smtp") {
            $("#smtp").show();
        } else {
            $("#smtp").hide();
        }
    });

    // Security
    if($.fn.datepicker) {
        $(".datepicker, #start_date").datepicker({
            showOn : "both",
            dateFormat : "dd/mm/yy",
            changeMonth : true,
            changeYear : true,
            showOtherMonths : true,
            buttonImage : nv_base_siteurl + "assets/images/calendar.gif",
            buttonImageOnly : true
        });
    }
    $('a.deleteone-ip').click(function() {
        if (confirm(LANG.banip_delete_confirm)) {
            var url = $(this).attr('href');
            var selectedtab = $('[name="gselectedtab"]').val();
            $.ajax({
                type : 'POST',
                url : url,
                data : '',
                success : function(data) {
                    alert(LANG.banip_del_success);
                    window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=security&selectedtab=" + selectedtab;
                }
            });
        }
        return false;
    });
    $('[data-toggle="ctcaptcha"]').change(function() {
        if ($(this).val() == '2') {
            $('[data-captcha="typebasic"]').hide();
            $('[data-captcha="typerecaptcha"]').show();
        } else {
            $('[data-captcha="typebasic"]').show();
            $('[data-captcha="typerecaptcha"]').hide();
        }
    });

    // Tự nhận diện FTP
    $('#autodetectftp').click(function() {
        var btn = $(this);
        if (btn.find('i').hasClass('fa-spin')) {
            return false;
        }

        var ftp_server = $('input[name="ftp_server"]').val();
        var ftp_user_name = $('input[name="ftp_user_name"]').val();
        var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
        var ftp_port = $('input[name="ftp_port"]').val();
        if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
            alert(btn.data('errormsg'));
            return;
        }
        btn.find('i').addClass('fa-spin');

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ftp&nocache=' + new Date().getTime(),
            data: {
                'ftp_server': ftp_server,
                'ftp_port': ftp_port,
                'ftp_user_name': ftp_user_name,
                'ftp_user_pass': ftp_user_pass,
                'tetectftp': 1
            },
            cache: false,
            success: function(c) {
                c = c.split('|');
                if (c[0] == 'OK') {
                    $('#ftp_path').val(c[1]);
                } else {
                    alert(c[1]);
                }
                btn.find('i').removeClass('fa-spin');
            },
            error: function(jqXHR, exception) {
                alert('Request Error!!!');
                btn.find('i').removeClass('fa-spin');
            }
        });
    });

    $('#ssl_https').change(function(){
        var val = $(this).data('val');
        var mode = $(this).val();

        if( mode != 0 && val == 0 && ! confirm(LANG.note_ssl) ){
            $(this).val('0');
            return;
        }
    });
});
