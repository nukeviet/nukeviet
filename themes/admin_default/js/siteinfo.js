/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(document).ready(function() {
    // System info
    $("#checkchmod").click(function(event) {
        event.preventDefault();
        var url = $(this).attr("href");
        $("#checkchmod").hide();
        $("#wait").html('<img class="refresh" src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt=""/>');
        $.ajax({
            type: "POST",
            url: url,
            data: "",
            success: function(data) {
                $("#wait").html("");
                alert(data);
                $("#checkchmod").show();
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
    if ($.fn.datepicker) {
        $("#from,#to").datepicker({
            dateFormat: "dd.mm.yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            buttonText: '{LANG.select}',
            showButtonPanel: true,
            showOn: 'focus'
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
        if ((f_q != LANG.filter_enterkey && f_q != '') || f_from != '' || f_to != '' || f_lang != '' || f_user != '' || f_module != '') {
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
                type: 'POST',
                url: CFG.url_del,
                data: 'listall=' + listall + '&checksess=' + CFG.checksess,
                success: function(data) {
                    var s = data.split('_');
                    if (s[0] == 'OK') {
                        location.reload();
                    }
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
                type: 'POST',
                url: href,
                data: 'checksess=' + CFG.checksess,
                success: function(data) {
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
                type: 'POST',
                url: script_name,
                data: nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=logs_del&logempty=1&checksess=" + CFG.checksess,
                success: function(data) {
                    if (data == 'OK') {
                        window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=logs";
                    } else {
                        alert(data);
                    }

                    $("#logempty").removeAttr("disabled");
                }
            });
        }
    });

    // List notification
    var nfcLists = $('#notification-lists');
    if (nfcLists.length) {
        $("abbr.timeago", nfcLists).timeago();
    }
});
