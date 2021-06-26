/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_change_active(cid) {
    var new_status = $('#change_active_' + cid).is(':checked') ? 1 : 0;
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_active_' + cid, 3000);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_active&nocache=' + new Date().getTime(), 'change_active=1&cid=' + cid + '&new_status=' + new_status, function(res) {

        });
    } else {
        $('#change_active_' + cid).prop('checked', new_status ? false : true);
    }
}

$(document).ready(function() {
    if ($.fn.datepicker) {
        $("#from_date, #to_date").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showOn: 'focus'
        });
    }
    $('#to-btn').click(function() {
        $("#to_date").datepicker('show');
    });
    $('#from-btn').click(function() {
        $("#from_date").datepicker('show');
    });
    $("#checkall").click(function() {
        $("input[name=commentid]:checkbox").each(function() {
            $(this).prop("checked", true);
        });
    });
    $("#uncheckall").click(function() {
        $("input[name=commentid]:checkbox").each(function() {
            $(this).prop("checked", false);
        });
    });
    $("a.enable").click(function() {
        var list = [];
        $("input[name=commentid]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.nocheck);
            return false;
        }
        $.ajax({
            type: "POST",
            url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=active",
            data: "list=" + list + "&active=1",
            success: function(data) {
                alert(data);
                window.location = window.location.href;
            }
        });
        return false;
    });
    $("a.disable").click(function() {
        var list = [];
        $("input[name=commentid]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.nocheck);
            return false;
        }
        $.ajax({
            type: "POST",
            url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=active",
            data: "list=" + list + "&active=0",
            success: function(data) {
                alert(data);
                window.location = window.location.href;
            }
        });
        return false;
    });
    $("a.delete").click(function() {
        var list = [];
        $("input[name=commentid]:checked").each(function() {
            list.push($(this).val());
        });
        if (list.length < 1) {
            alert(LANG.nocheck);
            return false;
        }
        if (confirm(LANG.delete_confirm)) {
            $.ajax({
                type: "POST",
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del",
                data: "list=" + list,
                success: function(data) {
                    alert(data);
                    window.location = window.location.href;
                }
            });
        }
        return false;
    });
    $("a.deleteone").click(function() {
        if (confirm(LANG.delete_confirm)) {
            var url = $(this).attr("href");
            $.ajax({
                type: "POST",
                url: url,
                data: "",
                success: function(data) {
                    alert(data);
                    window.location = window.location.href;
                }
            });
        }
        return false;
    });
});
