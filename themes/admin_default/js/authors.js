/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_admin_add_result(form_id, go, tp) {
    var formid = document.getElementById(form_id);
    var input_go = document.getElementById(go);
    input_go.value = (tp == 2) ? "sendmail" : "savefile";
    formid.submit();
    return false;
}

function nv_admin_edit_result(form_id, go, tp) {
    var formid = document.getElementById(form_id);
    var input_go = document.getElementById(go);
    input_go.value = (tp == 2) ? "sendmail" : "savefile";
    formid.submit();
    return false;
}

function nv_chang_weight(mid) {
    var nv_timer = nv_settimeout_disable('id_weight_' + mid, 5000);
    var new_vid = $("#id_weight_" + mid).val();
    var checkss = $("input[name='checkss']").val();
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changeweight=' + mid + '&new_vid=' + new_vid + '&checkss=' + checkss, function (res) {
        $("#main_module").html(res);
    });
    return;
}

function nv_chang_act(mid, act) {
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_act_' + act + '_' + mid, 5000);
        var checkss = $("input[name='checkss']").val();
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=module&nocache=' + new Date().getTime(), 'changact=' + act + '&mid=' + mid + '&checkss=' + checkss, function (res) {
            nv_set_disable_false('change_act_' + act + '_' + mid);
        });
    } else {
        var sl = document.getElementById('change_act_' + act + '_' + mid);
        sl.checked = (sl.checked == true) ? false : true;
    }
    return;
}

// Xóa hết Oauth của quản trị
function nv_del_oauthall(userid, tokend) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=2step&admin_id=' + userid + '&nocache=' + new Date().getTime(), 'delall=' + tokend, function (res) {
            if (res == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

// Xóa một Oauth của quản trị
function nv_del_oauthone(id, userid, tokend) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=2step&admin_id=' + userid + '&nocache=' + new Date().getTime(), 'del=' + tokend + '&id=' + id, function (res) {
            if (res == 'OK') {
                location.reload();
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
}

$(function () {
    $('.number').on('input', function () {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    $('[data-toggle=checkall]').on('click', function () {
        var obj = $(this).parents('[data-toggle=checklist]');
        $('[data-toggle=checkitem]', obj).prop("checked", $(this).data('check-value'))
    });

    if ($("#lev_expired").length) {
        $("#lev_expired").datepicker({
            dateFormat: "dd.mm.yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showOn: 'focus'
        });
        $('#lev_expired_btn').click(function () {
            $("#lev_expired").datepicker('show');
        });
        $('#lev_expired_clear').on('click', function() {
            $("#lev_expired").val('').trigger('change')
        })
    };

    $("#addadmin").submit(function (e) {
        e.preventDefault();
        var that = $(this),
            a = that.serialize(),
            result_url = that.data('result');
        $("input[type=submit]", that).prop("disabled", true);
        $.ajax({
            type: "POST",
            url: that.attr("action"),
            data: a,
            success: function (c) {
                if (c == "OK") {
                    window.location = result_url;
                } else {
                    alert(c);
                    $("input[type=submit]", that).prop("disabled", false)
                }
            }
        })
    });

    $('[name=lev]').on('click', function () {
        var lev_expired = $('[name=lev_expired]').val();

        if ($(this).attr('value') == '2') {
            $('#modslist').hide();
            $('#modslist input').prop('disabled', true);
            if (lev_expired != '') {
                $('#after_exp_action input').prop('disabled', false);
                $('#after_exp_action').slideDown()
            } else {
                $('#after_exp_action').hide();
                $('#after_exp_action input').prop('disabled', true)
            }
        } else {
            $('#modslist input').prop('disabled', false);
            $('#modslist').slideDown();
            if ($('#after_exp_action').length) {
                $('#after_exp_action').hide();
                $('#after_exp_action input').prop('disabled', true)
            }
        }
    });

    $('[name=downgrade_to_modadmin]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#modslist2 input').prop('disabled', false);
            $('#modslist2').slideDown()
        } else {
            $('#modslist2').hide();
            $('#modslist2 input').prop('disabled', true)
        }
    });

    $('[name=lev_expired]').on('change', function () {
        var lev_expired = $(this).val(),
            lev = $('[name=lev]:checked').val();
        if ($('#after_exp_action').length) {
            if (lev == 2 && lev_expired != '') {
                $('#after_exp_action input').prop('disabled', false);
                $('#after_exp_action').slideDown()
            } else {
                $('#after_exp_action').hide();
                $('#after_exp_action input').prop('disabled', true)
            }
        }
    })
});