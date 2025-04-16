/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    if ($('#cmt-main').length) {
        let checkss = $('[name=checkss]').val();
        var fmt = nv_jsdate_get.replace(/dd/g, 'd').replace(/mm/g, 'm').replace(/yyyy/g, 'Y');
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
        $('#to-btn').on('click', function() {
            $('#to_date').click();
        });
        $('#from-btn').on('click', function() {
            $('#from_date').click();
        });
        $("a.deleteone").click(function() {
            that = $(this);
            nvConfirm(nv_is_del_confirm[0], function() {
                var url = that.attr("href");
                $.ajax({
                    type: "POST",
                    url: url,
                    data: 'checkss=' + checkss,
                    success: function(res) {
                        if (res.status === 'ok') {
                            location.reload();
                        } else if (res.status === 'error') {
                            nvToast(res.mess, 'error');
                        } else {
                            nvToast(nv_is_del_confirm[2], 'error');
                        }
                    }
                })
            })
            return false;
        });
        $('#element_action_btn').click(function(e) {
            e.preventDefault();
            let btn = $(this);
            if (btn.prop('disabled')) {
                return;
            }
            let ctn = $(btn.data('ctn')), listid = [];
            ctn.find('[data-toggle="checkSingle"]:checked').each(function() {
                listid.push($(this).val());
            });
            if (listid.length < 1) {
                nvAlert(nv_please_check);
                return;
            }
            let action = document.getElementById('element_action').value;
            if (action === 'delete') {
                nvConfirm(nv_is_del_confirm[0], function() {
                    btn.prop('disabled', true);
                    $('#element_action').prop('disabled', true);
                    $.ajax({
                        type: 'POST',
                        url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=del&nocache=" + new Date().getTime(),
                        data: {
                            list: listid.join(','),
                            checkss: checkss
                        },
                        success: function(res) {
                            btn.prop('disabled', false);
                            $('#element_action').prop('disabled', false);
                            if (res.status === 'ok') {
                                location.reload();
                            } else if (res.status === 'error') {
                                nvToast(res.mess, 'error');
                            } else {
                                nvToast(nv_is_del_confirm[2], 'error');
                            }
                        },
                        error: function(err) {
                            btn.prop('disabled', false);
                            $('#element_action').prop('disabled', false);
                            nvToast(err.responseText, 'error');
                            console.error(err);
                        }
                    });
                });
            } else if (action === 'enable' || action === 'disable') {
                btn.prop('disabled', true);
                $('#element_action').prop('disabled', true);
                $.ajax({
                    type: 'POST',
                    url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=active&nocache=" + new Date().getTime(),
                    data: {
                        list: listid.join(','),
                        active: (action === 'enable' ? 1 : 0),
                        checkss: checkss
                    },
                    success: function(res) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        if (res.status === 'ok') {
                            location.reload();
                        } else if (res.status === 'error') {
                            nvToast(res.mess, 'error');
                        } else {
                            nvToast(nv_is_del_confirm[2], 'error');
                        }
                    },
                    error: function(err) {
                        btn.prop('disabled', false);
                        $('#element_action').prop('disabled', false);
                        nvToast(err.responseText, 'error');
                        console.error(err);
                    }
                });
            }
        });
    }
    if ($('#cmt-edit').length) {
        $('#post-file-remove').on('click', function() {
            $('#post-file').val('');
        });
        $('#post-file-download').on('click', function() {
            var file = $('#post-file').val();
            if (file !== '') {
                window.location = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&downloadfile=' + encodeURIComponent(file);
            }
        });
    }
    if ($('#cmt-config').length) {
        $('[data-mod]').on('click', function() {
            var mod = $(this).data('mod');
            $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=config&mod_name=' + mod, function(res) {
                if (res.status === 'ok') {
                    $('#config_comm_body').html(res.html);
                    $('#config_comm_label').html(res.title);
                    $('#config_comm_modal').modal('show');
                } else {
                    nvToast(nv_is_del_confirm[2], 'error');
                }
            });
        });
        $('#config_comm_submit').on('click', function() {
            $('#comm-cf-form').submit();
        });
    }
});

function nv_change_active(cid) {
    var new_status = $('#change_active_' + cid).is(':checked') ? 1 : 0;
    let checkss = $('[name=checkss]').val();
    nvConfirm(nv_is_change_act_confirm[0], function() {
        var nv_timer = nv_settimeout_disable('change_active_' + cid, 3000);
        $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_active&nocache=' + new Date().getTime(), 'change_active=1&cid=' + cid + '&new_status=' + new_status + '&checkss=' + checkss, function(res) {
            if (res.status === 'ok') {
                nvToast(res.mess, 'success');
            } else if (res.status === 'error') {
                nvToast(res.mess, 'error');
                $('#change_active_' + cid).checked = new_status ? false : true;
            } else {
                nvToast(nv_is_del_confirm[2], 'error');
                $('#change_active_' + cid).checked = new_status ? false : true;
            }
        });
    }, function() {
        $('#change_active_' + cid).prop('checked', new_status ? false : true);
    });
}
