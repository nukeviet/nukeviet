/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_show_cl_list(containerid) {
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cl_list&nocache=' + new Date().getTime());
    return false;
}

function nv_cl_del(cl_id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] == 'OK') {
                nv_show_cl_list(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_cl_del2(cl_id) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] == 'OK') {
                window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + r_split[2];
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_chang_act(cl_id, checkbox_id) {
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
                var sl = document.getElementById(r_split[1]);
                if (sl.checked == true)
                    sl.checked = false;
                else
                    sl.checked = true;
                clearTimeout(nv_timer);
                sl.disabled = true;
            }
        });
    } else {
        var sl = document.getElementById(checkbox_id);
        if (sl.checked == true)
            sl.checked = false;
        else
            sl.checked = true;
    }
    return;
}

function nv_chang_act2(cl_id) {
    if (confirm(nv_is_change_act_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_client&nocache=' + new Date().getTime(), 'id=' + cl_id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            } else {
                nv_client_info(r_split[2], r_split[3]);
            }
        });
    }
    return;
}

function nv_client_info(cl_id, containerid) {
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=info_cl&id=' + cl_id + '&nocache=' + new Date().getTime());
    return false;
}

function nv_banners_list(cl_id, containerid) {
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banners_client&id=' + cl_id + '&nocache=' + new Date().getTime());
    return false;
}

function nv_show_plans_list(containerid) {
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plist&nocache=' + new Date().getTime());
    return false;
}

function nv_pl_chang_act(pid, checkbox_id) {
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
                var sl = document.getElementById(r_split[1]);
                if (sl.checked == true)
                    sl.checked = false;
                else
                    sl.checked = true;
                clearTimeout(nv_timer);
                sl.disabled = true;
            }
        });
    } else {
        var sl = document.getElementById(checkbox_id);
        if (sl.checked == true)
            sl.checked = false;
        else
            sl.checked = true;
    }
    return;
}

function nv_pl_del(pid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
            var r_split = res.split("|");
            if (r_split[0] == 'OK') {
                nv_show_plans_list(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

// ---------------------------------------

function nv_pl_chang_act2(pid) {
    if (confirm(nv_is_change_act_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            } else {
                window.location.href = window.location.href;
            }
        });
    }
    return;
}

function nv_pl_del2(pid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_plan&nocache=' + new Date().getTime(), 'id=' + pid, function(res) {
            var r_split = res.split("|");
            if (r_split[0] == 'OK') {
                window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + r_split[2];
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function nv_show_banners_list(containerid, clid, pid, act, keyword) {
    var request_query = nv_fc_variable + '=b_list';
    if (clid != 0) {
        request_query += '&clid=' + clid;
    } else {
        if (pid != 0)
            request_query += '&pid=' + pid;
    }
    if (typeof keyword != 'undefined' && keyword != '') {
        request_query += '&q=' + encodeURIComponent(keyword);
    }
    request_query += '&act=' + act;
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
    return false;
}

function nv_chang_weight_banners(pid, id) {
    var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
    var weight = $("#id_weight_" + id).val();

    var request_query = nv_fc_variable + '=b_list';
    request_query += '&pid=' + pid
    request_query += '&id=' + id;
    request_query += '&weight=' + weight;

    $('#banners_list_act').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&act=1&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
    $('#banners_list_timeract').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&act=0&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
    $('#banners_list_deact').load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&act=3&num=' + nv_randomPassword(8) + '&nocache=' + new Date().getTime());
    return false;
}

function nv_b_chang_act(id, checkbox_id) {
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable(checkbox_id, 5000);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
                var sl = document.getElementById(r_split[1]);
                if (sl.checked == true)
                    sl.checked = false;
                else
                    sl.checked = true;
                clearTimeout(nv_timer);
                sl.disabled = true;
            } else {
                window.location.href = window.location.href;
            }
        });
    } else {
        var sl = document.getElementById(checkbox_id);
        if (sl.checked == true)
            sl.checked = false;
        else
            sl.checked = true;
    }
    return;
}

function nv_b_chang_act2(id) {
    if (confirm(nv_is_change_act_confirm[0])) {
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act_banner&nocache=' + new Date().getTime(), 'id=' + id, function(res) {
            var r_split = res.split("|");
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            } else {
                window.location.href = window.location.href;
            }
        });
    }
    return;
}

function nv_show_stat(bid, select_month, select_ext, button_id, containerid) {
    var nv_timer = nv_settimeout_disable(button_id, 5000);
    var month = $("#" + select_month).val();
    var ext = $("#" + select_ext).val();
    var request_query = nv_fc_variable + '=show_stat&id=' + bid + '&month=' + month + '&ext=' + ext;
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&nocache=' + new Date().getTime());
    return false;
}

function nv_show_list_stat(bid, month, ext, val, containerid, page) {
    var request_query = nv_fc_variable + '=show_list_stat&bid=' + bid + '&month=' + month + '&ext=' + ext + '&val=' + val;
    if (page != '0')
        request_query += '&page=' + page;
    $('#' + containerid).load(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + request_query + '&nocache=' + new Date().getTime());
}

function nv_genpass() {
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add_client&nocache=' + new Date().getTime(), 'nv_genpass=1', function(res) {
        $("input[name='pass_iavim']").val(res);
        $("input[name='re_pass_iavim']").val(res);
    });
    return;
}

$.toggleShowPassword = function(options) {
    var settings = $.extend({
        field: "#password",
        control: "#toggle_show_password"
    }, options);

    var control = $(settings.control);
    var field = $(settings.field);

    control.bind('click', function() {
        if (control.is(':checked')) {
            field.attr('type', 'text');
        } else {
            field.attr('type', 'password');
        }
    });
};

function find_User() {
    var name = $("#login_iavim").val();
    $.ajax({
        type: 'POST',
        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=add_client&nocache=' + new Date().getTime(),
        data: 'name=' + name,
        success: function(data) {
            var obj = jQuery.parseJSON(data);
            if ($('#email_iavim').val() == '') {
                $('#email_iavim').val(obj.email);
            };
            if ($('#full_name').val() == '') {
                $('#full_name').val(obj.full_name);
            };
            if ($('#phone').val() == '') {
                $('#phone').val(obj.phone);
            };
            if ($('#website_iavim').val() == '') {
                $('#website_iavim').val(obj.website);
            };
            if ($('#location').val() == '') {
                $('#location').val(obj.location);
            };
            if ($('#yim_iavim').val() == '') {
                $('#yim_iavim').val(obj.yim);
            };
            if ($('#fax').val() == '') {
                $('#fax').val(obj.fax);
            };
            if ($('#mobile').val() == '') {
                $('#mobile').val(obj.mobile);
            };
        },
    });
}

$(function() {
    // Auto complete search
    var autoSearchTimer = null;
    var autosearchpersion = $('.autosearchpersion');
    if (autosearchpersion.length == 1) {
        $('[name="assign_user"]', autosearchpersion).keyup(function(e) {
            $('.searchloading', autosearchpersion).addClass('hidden');
            $('.searchresultaj', autosearchpersion).html('').hide();
            if (autoSearchTimer) {
                clearTimeout(autoSearchTimer);
            }
            if (e.keyCode != 13) {
                var valu = $(this).val();
                valu = trim(valu);
                if (valu.length >= 3) {
                    $('.searchloading', autosearchpersion).removeClass('hidden');
                    autoSearchTimer = setTimeout(function() {
                        $.ajax({
                            type: "POST",
                            url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&nocache=" + new Date().getTime(),
                            data: "checkss=" + autosearchpersion.data('checkss') + "&ajaxqueryusername=" + encodeURIComponent(valu),
                            success: function(res) {
                                if (res.length) {
                                    var html = '<ul>';
                                    $.each(res, function(k, v) {
                                        html += '<li class="clearfix" data-value="' + v.username + '">';
                                        html += '<img class="left pull-left" src="' + v.photo + '" width="40" height="40"/>';
                                        html += '' + v.fullname + '<br />';
                                        html += '<small>' + v.username + '</small>';
                                        html += '</li>';
                                    });
                                    html += '</ul>';
                                    $('.searchresultaj', autosearchpersion).html(html).show();
                                }
                                $('.searchloading', autosearchpersion).addClass('hidden');
                            }
                        });
                    }, 300);
                }
            }
        });
        $(document).delegate('.autosearchpersion ul li', 'click', function() {
            $('[name="assign_user"]', autosearchpersion).val($(this).data('value')).focus();
            $('.searchresultaj', autosearchpersion).html('').hide();
        });
    }
    // Custom plan exp
    $('#plan_exp_time').change(function() {
        var val = $(this).val();
        if (val == -1) {
            $('#plan_exp_time_custom').show();
        } else {
            $('#plan_exp_time_custom').hide();
        }
    });
    $('[data-toggle="delval"]').click(function(e) {
        e.preventDefault();
        $($(this).data('target')).val('');
        if ($(this).data('select') != '' && typeof $(this).data('select') != 'undefined') {
            $($(this).data('select')).val('0');
        }
    });
});
