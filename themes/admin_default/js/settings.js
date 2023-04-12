/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_is_del_cron(cronid, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.get(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=cronjobs_del&id=" + cronid + "&checkss=" + checkss + "&nocache=" + new Date().getTime(), function(res) {
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

$(document).ready(function() {
    // System
    $('#cdn_download').click(function() {
        window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cdn&cdndl=' + CFG.cdndl;
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
    if ($.fn.datepicker) {
        $(".datepicker, #start_date").datepicker({
            showOn: "both",
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            buttonImage: nv_base_siteurl + "assets/images/calendar.gif",
            buttonImageOnly: true
        });
    }
    $('a.deleteone-ip').click(function() {
        if (confirm(LANG.banip_delete_confirm)) {
            var url = $(this).attr('href');
            var selectedtab = $('[name="gselectedtab"]').val();
            $.ajax({
                type: 'POST',
                url: url,
                data: '',
                success: function(data) {
                    alert(LANG.banip_del_success);
                    window.location = script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=security&selectedtab=" + selectedtab;
                }
            });
        }
        return false;
    });

    // Site setting
    $(".selectimg").click(function() {
        var area = $(this).attr('data-name');
        var path = "";
        var currentpath = "images";
        var type = "image";
        nv_open_browse(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 420, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
        return false;
    });

    // FTP setting
    $('#autodetectftp').click(function() {
        var ftp_server = $('input[name="ftp_server"]').val();
        var ftp_user_name = $('input[name="ftp_user_name"]').val();
        var ftp_user_pass = $('input[name="ftp_user_pass"]').val();
        var ftp_port = $('input[name="ftp_port"]').val();

        if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
            alert(LANG.ftp_error_full);
            return;
        }

        $(this).attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: CFG.detect_ftp,
            data: {
                'ftp_server': ftp_server,
                'ftp_port': ftp_port,
                'ftp_user_name': ftp_user_name,
                'ftp_user_pass': ftp_user_pass,
                'tetectftp': 1
            },
            success: function(c) {
                c = c.split('|');
                if (c[0] == 'OK') {
                    $('#ftp_path_iavim').val(c[1]);
                } else {
                    alert(c[1]);
                }
                $('#autodetectftp').removeAttr('disabled');
            }
        });
    });

    $('#ssl_https').change(function() {
        var val = $(this).data('val');
        var mode = $(this).val();

        if (mode != 0 && val == 0 && !confirm(LANG.note_ssl)) {
            $(this).val('0');
            return;
        }
    });

    // formSearchPlugin Submit
    $('#formSearchPlugin [name=a]').on('change', function() {
        $('#formSearchPlugin').submit()
    })

    // nv_change_plugin_weight
    $('[data-toggle=change_plugin_weight]').on('change', function(e) {
        e.preventDefault();
        var pid = $(this).data('pid');
        var new_weight = $(this).val();
        nv_settimeout_disable($(this).attr('id'), 3000);
        $.post(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'changeweight=1&pid=' + pid + '&new_weight=' + new_weight, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
            location.reload();
        });
    });

    // nv_del_plugin
    $('[data-toggle=nv_del_plugin]').on('click', function(e) {
        e.preventDefault();
        if (confirm(nv_is_del_confirm[0])) {
            $.post(script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(), 'del=1&pid=' + $(this).data('pid'), function(res) {
                location.reload();
            })
        }
    });

    // Tích hợp plugin mới
    var mdPCfg = $('#mdPluginConfig');
    $('[data-click="plintegrate"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var icon = $('.fa', $this);
        if ($('[data-click="plintegrate"] .fa-spin').length > 0) {
            return;
        }
        icon.addClass('fa-spin');
        // Trường hợp là plugin thuần hệ thống
        if ($this.data('hm') == '' && $this.data('rm') == '') {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
                data: {
                    integrate: 1,
                    hook_key: $this.data('hkey'),
                    file_key: $this.data('fkey')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spin');
                    if (respon.message == '') {
                        location.reload();
                        return;
                    }
                    alert(respon.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    icon.removeClass('fa-spin');
                    console.log(jqXHR, textStatus, errorThrown);
                    alert('Request Error!!!');
                }
            });
            return;
        }
        // Trường hợp là plugin trao đổi dữ liệu module => Gọi form tích hợp
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
            data: {
                loadform: 1,
                hook_key: $this.data('hkey'),
                file_key: $this.data('fkey')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spin');
                if (respon.message != '') {
                    alert(respon.message);
                    return;
                }
                window.nv_plugin_data = respon;

                var opts, show;

                mdPCfg.data('hook_key', $this.data('hkey'));
                mdPCfg.data('file_key', $this.data('fkey'));
                $('[data-area="title"]', mdPCfg).html(respon.tag);

                // Xác định module nguồn còn khả dụng
                opts = '';
                show = 0;
                if (respon.hook_mod != '' && respon.hook_mods.length > 0) {
                    for (var i = 0; i < respon.hook_mods.length; i++) {
                        var avail = 1;
                        for (var j = 0; j < respon.exists.length; j++) {
                            if (respon.exists[j].hook_mod == respon.hook_mods[i].key && respon.exists[j].receive_mods.length >= respon.receive_mods.length) {
                                avail = 0;
                            }
                        }
                        if (avail) {
                            opts += '<option value="' + respon.hook_mods[i].key + '">' + respon.hook_mods[i].title + '</option>';
                            show = 1;
                        }
                    }
                }
                $('[name="hook_module"]', mdPCfg).html(opts);
                if (show) {
                    $('[data-area="hook_module"]', mdPCfg).removeClass('hidden');
                } else {
                    $('[data-area="hook_module"]', mdPCfg).addClass('hidden');
                }

                // Gọi event change module nguồn để load ra module đích
                $('[name="hook_module"]', mdPCfg).trigger('change');

                mdPCfg.modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                icon.removeClass('fa-spin');
                console.log(jqXHR, textStatus, errorThrown);
                alert('Request Error!!!');
            }
        });
    });

    // Xử lý load module đích sau khi chọn module nguồn
    $('[name="hook_module"]', mdPCfg).on('change', function(e) {
        e.preventDefault();

        // Xác định module đích còn khả dụng
        var opts = ''
        var show = 0;
        var hook_mod = '';
        if (!$('[data-area="hook_module"]', mdPCfg).is('.hidden')) {
            hook_mod = $('[name="hook_module"]', mdPCfg).val();
        }

        if (nv_plugin_data.receive_mod != '' && nv_plugin_data.receive_mods.length > 0) {
            for (var i = 0; i < nv_plugin_data.receive_mods.length; i++) {
                var avail = 1;
                for (var j = 0; j < nv_plugin_data.exists.length; j++) {
                    if (nv_plugin_data.exists[j].hook_mod == hook_mod && $.inArray(nv_plugin_data.receive_mods[i].key, nv_plugin_data.exists[j].receive_mods) > -1) {
                        avail = 0;
                    }
                }
                if (avail) {
                    opts += '<option value="' + nv_plugin_data.receive_mods[i].key + '">' + nv_plugin_data.receive_mods[i].title + '</option>';
                    show = 1;
                }
            }
        }
        $('[name="receive_module"]', mdPCfg).html(opts);
        if (show) {
            $('[data-area="receive_module"]', mdPCfg).removeClass('hidden');
        } else {
            $('[data-area="receive_module"]', mdPCfg).addClass('hidden');
        }
    });

    // Tích hợp plugin trao đổi dữ liệu module
    $('[data-toggle="submitIntegratePlugin"]').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);

        btn.prop('disable', true);

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=plugin&nocache=' + new Date().getTime(),
            data: {
                integrate: 1,
                hook_key: mdPCfg.data('hook_key'),
                file_key: mdPCfg.data('file_key'),
                hook_module: $('[name="hook_module"]', mdPCfg).val(),
                receive_module: $('[name="receive_module"]', mdPCfg).val()
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                btn.prop('disable', false);
                if (respon.message == '') {
                    location.reload();
                    return;
                }
                alert(respon.message);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                btn.prop('disable', false);
                console.log(jqXHR, textStatus, errorThrown);
                alert('Request Error!!!');
            }
        });
    });

    $('[data-toggle=ssetings_form_submit]').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "html",
            success: function(b) {
                window.location.href = url
            }
        })
    });

    $('textarea.nonewline').on('change', function(e) {
        e.preventDefault();
        var val = $(this).val();
        val = val.replace(/[\n\r]+/g, ' ').replace(/\s{2,}/g, ' ').replace(/^\s+|\s+$/, '');
        $(this).val(val)
    });

    $('[data-toggle=seccode_create]').on('click', function() {
        $($(this).data('target')).val(nv_randomPassword(32))
    });

    $('[data-toggle=seccode_remove]').on('click', function() {
        $($(this).data('target')).val('')
    });

    if ($('[data-toggle=clipboard]').length && ClipboardJS) {
        var clipboard = new ClipboardJS('[data-toggle=clipboard]');
        clipboard.on('success', function(e) {
            $(e.trigger).tooltip('show');
            setTimeout(function() {
                $(e.trigger).tooltip('destroy');
            }, 1000);
        });
    }
});
