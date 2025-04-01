/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

(() => {
    document.getElementById('element_action_btn').addEventListener('click', e => {
        e.preventDefault();
        let btn = e.target;
        if (btn.disabled) {
            return;
        }
        let ctn = document.querySelector(btn.dataset.ctn), listid = [];
        ctn.querySelectorAll('[data-toggle="checkSingle"]:checked').forEach(el => {
            listid.push(el.value);
        });
        if (listid.length < 1) {
            nvAlert(nv_please_check);
            return;
        }
        let action = document.getElementById('element_action').value;

        if (action === 'delete') {
            nvConfirm(nv_is_del_confirm[0], () => {
                btn.disabled = true;
                document.getElementById('element_action').disabled = true;
                fetch(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&nocache=" + new Date().getTime(), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'list=' + listid.join(',')
                })
                .then(response => response.text())
                .then(res => {
                    btn.disabled = false;
                    document.getElementById('element_action').disabled = false;
                    let r_split = res.split('_');
                    if (r_split[0] === 'OK') {
                        location.reload();
                    } else if (r_split[0] === 'ERR') {
                        nvToast(r_split[1], 'error');
                    } else {
                        nvToast(nv_is_del_confirm[2], 'error');
                    }
                })
                .catch(err => {
                    btn.disabled = false;
                    document.getElementById('element_action').disabled = false;
                    nvToast(err.message, 'error');
                    console.error(err);
                });
            });
        } else if (action === 'enable' || action === 'disable') {
            btn.disabled = true;
            document.getElementById('element_action').disabled = true;
            fetch(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=active&nocache=" + new Date().getTime(), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'list=' + listid.join(',') + '&active=' + (action === 'enable' ? 1 : 0)
            })
            .then(response => response.text())
            .then(res => {
                btn.disabled = false;
                document.getElementById('element_action').disabled = false;
                let r_split = res.split('_');
                if (r_split[0] === 'OK') {
                    location.reload();
                } else if (r_split[0] === 'ERR') {
                    nvToast(r_split[1], 'error');
                } else {
                    nvToast(nv_is_del_confirm[2], 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                document.getElementById('element_action').disabled = false;
                nvToast(err.message, 'error');
                console.error(err);
            });
        } else {
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + action + '&listid=' + listid.join(',') + '&checkss=' + document.body.dataset.checksess;
        }
    });
})();

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
    var fmt = nv_jsdate_post.replace(/dd/g, 'd').replace(/mm/g, 'm').replace(/yyyy/g, 'Y');
    $('#from_date,#to_date').flatpickr({
        enableTime: false,
        dateFormat: fmt,
        ariaDateFormat: fmt,
        locale: nv_lang_interface,
        onOpen: function (selectedDates, dateStr, instance) {
            if (instance.input.value.length == 0) {
                instance.setDate(new Date());
            }
        }
    });
    // if ($.fn.datepicker) {
    //     $("#from_date, #to_date").datepicker({
    //         dateFormat: "dd/mm/yy",
    //         changeMonth: true,
    //         changeYear: true,
    //         showOtherMonths: true,
    //         showOn: 'focus'
    //     });
    // }
    $('#to-btn').click(function() {
        $("#to_date").trigger('click');
    });
    $('#from-btn').click(function() {
        $("#from_date").trigger('click');
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
