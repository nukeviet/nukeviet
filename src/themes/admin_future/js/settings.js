/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Hàm đọc form thiết lập CDN theo ngôn ngữ
function country_cdn_list_load() {
    let ele = $('#collapse-country-cdn');
    $.ajax({
        type: 'GET',
        cache: !1,
        url: ele.data('url'),
        success: function(data) {
            ele.attr('data-loaded', 'true');
            ele.html(data);
        },
        error: function(xhr, text, err) {
            nvToast(err, 'error');
            console.log(xhr, text, err);
        }
    })
}

$(function() {
    // Select 2
    if ($('.select2').length) {
        $('.select2').select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });
    }

    // Đổi loại giao diện ở cấu hình site
    $('#site-settings [name^=theme_type]').on('change', function() {
        var form = $(this).closest('form'),
            types = [];
        $('[name^=theme_type]:checked').each(function() {
            types.push($(this).val());
        });
        if ($.inArray('m', types) !== -1) {
            $('.mobile_theme-wrap', form).removeClass('d-none');
            if ($('[name=mobile_theme]', form).val() != '') {
                $('.switch_mobi_des-wrap', form).removeClass('d-none');
            } else {
                $('.switch_mobi_des-wrap', form).addClass('d-none');
            }
        } else {
            $('.mobile_theme-wrap, .switch_mobi_des-wrap', form).addClass('d-none');
        }
        if ($.inArray('r', types) === -1 && $.inArray('d', types) === -1) {
            $('[name^=theme_type][value=r]', form).prop('checked', true);
        }
    });

    // Đổi giao diện mobile ở cấu hình site
    $('#site-settings [name=mobile_theme]').on('change', function() {
        var form = $(this).closest('form');
        if ($(this).val() != '') {
            $('.switch_mobi_des-wrap', form).removeClass('d-none');
        } else {
            $('.switch_mobi_des-wrap', form).addClass('d-none');
            $('[name=switch_mobi_des]', form).prop('checked', false);
        }
    });

    // Cảnh báo khi đổi chuyển hướng HTTP > HTTPS
    $('#element_ssl_https').on('change', function() {
        var val = parseInt($(this).data('val')),
            mode = parseInt($(this).val()),
            that = $(this);
        if (mode != 0 && val == 0) {
            nvConfirm($(this).data('confirm'), () => {}, () => {
                that.val('0');
            });
        }
    });

    // Xử lý giao diện khi bật tắt rewrite, đa ngôn ngữ
    $('[data-toggle="controlrw"]').on('change', function() {
        var lang_multi = $('[name="lang_multi"]').is(':checked');
        var rewrite_enable = $('[name="rewrite_enable"]').is(':checked');
        if ($('#lang-geo').length) {
            if (lang_multi) {
                if (!$('#lang-geo').is(':visible')) {
                    $('#lang-geo').hide().removeClass('d-none').slideDown();
                }
            } else {
                $('#lang-geo').slideUp(function() {
                    $(this).addClass('d-none');
                });
            }
        }
        if (!lang_multi && rewrite_enable) {
            if (!$('#ctn_rewrite_optional').is(':visible')) {
                $('#ctn_rewrite_optional').hide().removeClass('d-none').slideDown();
            }
        } else {
            $('#ctn_rewrite_optional').slideUp(function() {
                $(this).addClass('d-none');
            });
            $('[name="rewrite_optional"]').prop('checked', false);
        }
        $('[data-toggle="controlrw1"]').change();
    });

    // Xử lý giao diện khi bật tắt loại bỏ biến ngôn ngữ khỏi url
    $('[data-toggle="controlrw1"]').on('change', function() {
        var rewrite_optional = $(this).is(':checked');
        if (rewrite_optional) {
            $('#ctn_rewrite_op_mod').hide().removeClass('d-none').slideDown();
        } else {
            $('#ctn_rewrite_op_mod').slideUp(function() {
                $(this).addClass('d-none');
            });
            $('[name="rewrite_op_mod"]').find('option').prop('selected', false);
        }
    });

    // Xử lý giao diện khi đóng mở site
    $('#collapse-closesite').on('shown.bs.collapse', function() {
        let ctn = $(this).closest('.card');
        $('.card-header', ctn).addClass('rounded-bottom-0');
        $('.card-body', ctn).addClass('rounded-top-0');
    });
    $('#collapse-closesite').on('hidden.bs.collapse', function() {
        let ctn = $(this).closest('.card');
        $('.card-header', ctn).removeClass('rounded-bottom-0');
        $('.card-body', ctn).removeClass('rounded-top-0');
    });
    $('[name=closed_site]').on('change', function() {
        if ($(this).val() != '0') {
            if (!$("#reopening_time").is(':visible')) {
                $("#reopening_time").hide().removeClass('d-none').slideDown();
            }
        } else {
            $("#reopening_time").slideUp(function() {
                $(this).addClass('d-none');
            });
        }
    });

    // Thêm xóa cấu hình tùy chỉnh
    $('body').on('click', '[data-toggle="addCustomCfgItem"]', function() {
        var item = $(this).closest('.item'),
            new_item = item.clone();
        $('input[type=text]', new_item).val('');
        item.after(new_item);
    });

    $('body').on('click', '[data-toggle="delCustomCfgItem"]', function() {
        var item = $(this).closest('.item'),
            list = $(this).closest('.list');
        if ($('.item', list).length > 1) {
            item.remove();
        } else {
            $('input[type=text]', item).val('');
        }
    });

    // Tự xác định path của FTP
    $('#autodetectftp').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        var form = btn.closest('form'),
            ftp_server = $('input[name="ftp_server"]', form).val(),
            ftp_user_name = $('input[name="ftp_user_name"]', form).val(),
            ftp_user_pass = $('input[name="ftp_user_pass"]', form).val(),
            ftp_port = $('input[name="ftp_port"]', form).val();
        if (ftp_server == '' || ftp_user_name == '' || ftp_user_pass == '') {
            nvToast(form.data('error'), 'error');
            return !1;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: {
                'ftp_server': ftp_server,
                'ftp_port': ftp_port,
                'ftp_user_name': ftp_user_name,
                'ftp_user_pass': ftp_user_pass,
                'autodetect': 1
            },
            dataType: "json",
            success: function(c) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if ('error' == c.status) {
                    nvToast(c.mess, 'error');
                } else if('OK' == c.status) {
                    $('#ftp_path').val(c.mess);
                }
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Thêm và xóa CDN url ở trang thiết lập CDN
    $('body').on('click', '[data-toggle=add_cdn]', function(e) {
        e.preventDefault();
        var cdnlist = $(this).closest('.cdn-list'),
            item = $(this).closest('.item'),
            newitem = item.clone();
        let randId = nv_randomPassword(10);
        $('[data-toggle="cdn_default"]', newitem).attr('id', randId);
        $('[data-toggle="cdn_default_lbl"]', newitem).attr('for', randId);
        $('[name^=cdn_url], [name^=cdn_countries]', newitem).val('');
        $('[name^=cdn_is_default]', newitem).val('0');
        $('[data-toggle=cdn_default]', newitem).prop('checked', false);
        newitem.appendTo(cdnlist);
    });
    $('body').on('click', '[data-toggle=remove_cdn]', function(e) {
        e.preventDefault();
        var cdnlist = $(this).closest('.cdn-list'),
            item = $(this).closest('.item');
        if ($('.item', cdnlist).length > 1) {
            item.remove();
        } else {
            $('[name^=cdn_url], [name^=cdn_countries]', item).val('');
            $('[name^=cdn_is_default]', item).val('0');
            $('[data-toggle=cdn_default]', item).prop('checked', false);
        }
    });

    // Chọn CDN url mặc định
    $('body').on('change', '[data-toggle=cdn_default]', function() {
        var item = $(this).closest('.item');
        if ($(this).is(':checked')) {
            $('[name^=cdn_is_default]', item).val('1');
            $('[data-toggle=cdn_default]', item.siblings()).prop('checked', false);
            $('[name^=cdn_is_default]', item.siblings()).val('0');
        } else {
            $('[name^=cdn_is_default]', item).val('0');
        }
    });

    // Load CDN theo quốc gia
    $('#collapse-country-cdn').on('shown.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            country_cdn_list_load();
        }
    });

    // Tự động submit form CDN theo quốc gia
    $('body').on('change', '[name^=ccdn]', function(e) {
        e.preventDefault();
        if ($(this).val() != '') {
            $(this).closest('li').addClass('list-group-item-success');
        } else {
            $(this).closest('li').removeClass('list-group-item-success');
        }
        $(this).closest('form').submit();
    });

    // Xóa crontab
    $('[data-toggle="delCron"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    cron_del: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    nvToast(nv_is_del_confirm[1], 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Tạo datepicker trên các thẻ input
    function initDatepicker(element) {
        let inMd = element.closest('.modal');
        element.datepicker({
            dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            showButtonPanel: true,
            showOn: 'focus',
            isRTL: $('html').attr('dir') == 'rtl',
            beforeShow: (ipt, pkr) => {
                if (inMd.length) {
                    setTimeout(() => {
                        pkr.dpDiv[0].style.setProperty("z-index", "9999", "important");
                    }, 100);
                }
            }
        });
    }

    // Chọn ngày tháng
    if ($('.datepicker').length) {
        $('.datepicker').each(function() {
            initDatepicker($(this));
        });
    }

    let btnAddCron = $('[data-toggle="cronAdd"]');
    if (btnAddCron.length) {
        // Ấn nút thêm crontab
        btnAddCron.on('click', function(e) {
            e.preventDefault();
            let md = $('#mdCronForm');
            $('.modal-title').html($(this).attr('aria-label'));
            $('[type="checkbox"]', md).prop('checked', false);
            $('[type="text"]:not(.datepicker)', md).val('');
            $('.datepicker', md).each(function() {
                $(this).val($(this).data('default'));
            });
            $('[type="number"]', md).val('60');
            $('[name="id"]', md).val('0');
            $('option', md).prop('selected', false);
            $('.is-invalid', md).removeClass('is-invalid');
            $('[name="run_file"]', md).val(btnAddCron.data('autofile'));
            btnAddCron.data('autofile', '');
            bootstrap.Modal.getOrCreateInstance(md[0]).show();
        });

        // Tự mở form thêm
        if (btnAddCron.data('autofile') != '') {
            btnAddCron.trigger('click');
        }
    }

    // Lấy thông tin chi tiết crontab để sửa
    $('[data-toggle="editCron"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                crontabinfo: 1,
                checkss: btn.data('checkss'),
                id: btn.data('id')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (!respon.success) {
                    nvToast(respon.text, 'error');
                    return;
                }
                let data = respon.data;
                let md = $('#mdCronForm');

                $('.modal-title').html(data.form_title);
                $('[name="id"]', md).val(btn.data('id'));
                $('.is-invalid', md).removeClass('is-invalid');
                $('[name="cron_name"]', md).val(data.cron_name);
                $('[name="run_file"]', md).val(data.run_file);
                $('[name="run_func_iavim"]', md).val(data.run_func);
                $('[name="params_iavim"]', md).val(data.params);
                $('[name="hour"]', md).val(data.hour);
                $('[name="min"]', md).val(data.min);
                $('[name="start_date"]', md).val(data.start_date);
                $('[name="interval_iavim"]', md).val(data.inter_val);
                $('[name="inter_val_type"]', md).val(data.inter_val_type);
                $('[name="del"]', md).prop('checked', data.del);

                bootstrap.Modal.getOrCreateInstance(md[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
    // Hiệu chỉnh lại picker date khi mở modal edit/add lên
    $('#mdCronForm').on('shown.bs.modal', function() {
        $('.datepicker', $('#mdCronForm')).each(function() {
            let pk = $(this);
            let inMd = pk.closest('.modal');
            pk.datepicker('destroy');
            setTimeout(() => {
                pk.datepicker({
                    dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths: true,
                    showButtonPanel: true,
                    showOn: 'focus',
                    isRTL: $('html').attr('dir') == 'rtl',
                    beforeShow: (ipt, pkr) => {
                        if (inMd.length) {
                            setTimeout(() => {
                                pkr.dpDiv[0].style.setProperty("z-index", "9999", "important");
                                pkr.dpDiv.position({
                                    my: 'left top',
                                    at: 'left bottom',
                                    of: ipt
                                });
                            }, 10);
                        }
                    }
                });
            }, 1);
        });
    });

    // Kích hoạt đình chỉ crontab
    $('[data-toggle="actCron"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_change_act_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    cron_changeact: btn.data('id')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    nvToast(nv_is_change_act_confirm[1], 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Xóa plugin
    $('[data-toggle=nv_del_plugin]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    pid: btn.data('pid'),
                    del: 1
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (!respon.success) {
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Lọc plugin theo hook
    $('#formSearchPlugin [name=a]').on('change', function() {
        $('#formSearchPlugin').submit();
    });

    // Thay đổi thứ tự ưu tiên của plugin
    $('[data-toggle=change_plugin_weight]').on('change', function(e) {
        e.preventDefault();
        let btn = $(this);
        let new_weight = btn.val();
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                checkss: btn.data('checkss'),
                pid: btn.data('pid'),
                new_weight: new_weight,
                changeweight: 1
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                if (!respon.success) {
                    btn.prop('disabled', false);
                    btn.val(btn.data('weight'));
                    nvToast(respon.text, 'error');
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                btn.prop('disabled', false);
                btn.val(btn.data('weight'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Tích hợp plugin mới
    var mdPCfg = $('#mdPluginConfig');
    $('[data-click="plintegrate"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var icon = $('.fa-solid', $this);
        if ($('[data-click="plintegrate"] .fa-spin-pulse').length > 0) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        // Trường hợp là plugin thuần hệ thống
        if ($this.data('hm') == '' && $this.data('rm') == '') {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
                data: {
                    integrate: 1,
                    hook_key: $this.data('hkey'),
                    file_key: $this.data('fkey')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    if (respon.message == '') {
                        location.reload();
                        return;
                    }
                    nvToast(respon.message, 'error');
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
            return;
        }

        // Trường hợp là plugin trao đổi dữ liệu module => Gọi form tích hợp
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                loadform: 1,
                hook_key: $this.data('hkey'),
                file_key: $this.data('fkey')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (respon.message != '') {
                    nvToast(respon.message, 'error');
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
                    $('[data-area="hook_module"]', mdPCfg).removeClass('d-none');
                } else {
                    $('[data-area="hook_module"]', mdPCfg).addClass('d-none');
                }

                // Gọi event change module nguồn để load ra module đích
                $('[name="hook_module"]', mdPCfg).trigger('change');

                bootstrap.Modal.getOrCreateInstance(mdPCfg[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
    // Xử lý load module đích sau khi chọn module nguồn
    if (mdPCfg.length) {
        $('[name="hook_module"]', mdPCfg).on('change', function(e) {
            e.preventDefault();

            // Xác định module đích còn khả dụng
            var opts = ''
            var show = 0;
            var hook_mod = '';
            if (!$('[data-area="hook_module"]', mdPCfg).is('.d-none')) {
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
                $('[data-area="receive_module"]', mdPCfg).removeClass('d-none');
            } else {
                $('[data-area="receive_module"]', mdPCfg).addClass('d-none');
            }
        });
    }
    // Tích hợp plugin trao đổi dữ liệu module
    $('[data-toggle="submitIntegratePlugin"]').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
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
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (respon.message == '') {
                    location.reload();
                    return;
                }
                nvToast(respon.message, 'error');
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xem tệp cấu hình máy chủ
    $('[data-toggle=view_sconfig_file]').on('click', function(e) {
        e.preventDefault();
        var btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                getSconfigContents: 1,
                checkss: btn.data('checkss')
            },
            dataType: 'html',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                $('#sConfigModal .modal-body>pre>code').text(respon);
                bootstrap.Modal.getOrCreateInstance('#sConfigModal').show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
    $('#sConfigModal, #sDefaultModal').on('show.bs.modal', function() {
        hljs.debugMode();
        hljs.highlightAll();
    });

    // Tệp cấu hình gợi ý theo thiết lập hiện tại
    $('#sample-form').on('submit', function(e) {
        e.preventDefault();
        var url = $(this).data('url'),
            data = $(this).serialize(),
            rewrite_supporter = $('[name=rewrite_supporter]', this).val(),
            lang = $('option[value=' + rewrite_supporter + ']', this).data('highlight-lang');

        e.preventDefault();
        var btn = $('[type="submit"]', $(this));
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "html",
            success: function(b) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                $('#sDefaultModal .modal-body>pre>code').removeAttr('class').addClass(lang).text(b);
                bootstrap.Modal.getOrCreateInstance('#sDefaultModal').show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        })
    });

    // Reset form cấu hình gửi mail
    $('#sendmail-settings [data-toggle=form_reset]').on('click', function() {
        $('#sendmail-settings')[0].reset();
        $('#sendmail-settings [name=mailer_mode][value=' + $('#sendmail-settings').data('mailer-mode-default') + ']').trigger('change');
    });

    // Lưu và test gửi mail
    $('#sendmail-settings [data-toggle=smtp_test]').on('click', function() {
        var that = $('#sendmail-settings'),
            url = that.attr('action'),
            checkss = $('[name=checkss]', that).val(),
            data = that.serialize();
        $('input,button,textarea', that).prop('disabled', true);
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                if (result.status == 'error') {
                    $('input,button,textarea', that).prop('disabled', false);
                    nvToast(result.mess, 'error');
                    if (result.input) {
                        if ($('[name^=' + result.input + ']', that).length) {
                            $('[name^=' + result.input + ']', that).focus();
                        }
                    }
                    return;
                }
                if (result.status == 'OK') {
                    $.ajax({
                        type: 'POST',
                        cache: !1,
                        url: url,
                        data: 'submittest=1&checkss=' + checkss,
                        timeout: 3E4
                    }).done(function(e) {
                        nvToast(e, 'info');
                        $('input,button,textarea', that).prop('disabled', false)
                    }).fail(function(jqXHR, textStatus) {
                        console.log(jqXHR, textStatus);
                        if (textStatus === 'timeout') {
                            $('input,button,textarea', that).prop('disabled', false);
                            nvToast('Failed from timeout', 'error');
                        }
                    });
                    return;
                }
                nvToast('Unknow respon data!', 'error');
            },
            error: function(xhr, text, err) {
                $('input,button,textarea', that).prop('disabled', false);
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        })
    });

    // Xử lý form khi thay đổi chế độ gửi mail
    $("#sendmail-settings [name=mailer_mode]").on('change', function() {
        var type = $(this).val();
        if (type == "smtp") {
            if (!$('#ctn_mailer_mode_smtp').is(':visible')) {
                $('#ctn_mailer_mode_smtp').hide().removeClass('d-none').slideDown();
            }
        } else {
            $('#ctn_mailer_mode_smtp').slideUp(function() {
                $(this).addClass('d-none');
            });
        }
    });

    // Lấy danh sách DKIM qua ajax
    function dkim_list_load() {
        let html = trim($('#dkim_list').html());
        if (html == '') {
            $('#dkim_list').html('<div class="accordion-body"><i class="fa-solid fa-spinner fa-spin-pulse"></i></div>');
        } else {
            $('#dkim_list').css({
                opacity: 0.5
            });
        }
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $('#dkimaddForm').attr('action'),
            data: 'dkimlist=1',
            success: function(data) {
                $('#dkim_list').html(data);
                $('#collapse-dkim').attr('data-loaded', 'true');
                $('#dkim_list').css({
                    opacity: 1
                });
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    }

    // Submit form thêm chữ kí số miền gửi thư
    $("#dkimaddForm").on('submit', function(e) {
        e.preventDefault();
        var that = $(this),
            domain = $('[name=domain]', that).val();
        if ('' == domain) {
            $('[name=domain]', that).focus();
            return !1
        }
        var data = that.serialize();
        $('input, button', that).prop('disabled', true);
        $.ajax({
            url: that.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button', that).prop('disabled', false);
            $('[name=domain]', that).val('');
            if ('error' == a.status) {
                nvToast(a.mess, 'error');
            } else if ('OK' == a.status) {
                dkim_list_load();
                var myLdBtn = setInterval(function() {
                    if ($('#dkim_list [data-toggle="dkim_read"][data-domain="' + domain + '"]').length) {
                        clearInterval(myLdBtn);
                        $('#dkim_list [data-toggle="dkim_read"][data-domain="' + domain + '"]').trigger('click');
                    }
                }, 500);
            }
        }).fail(function(xhr, text, err) {
            $('input, button', that).prop('disabled', false);
            nvToast(err, 'error');
            console.log(xhr, text, err);
        });
    });

    // Lấy danh sách DKIM khi mở ra
    $('#collapse-dkim').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            dkim_list_load();
        }
    });

    // Đọc DKIM
    $('body').on('click', '[data-toggle=dkim_read]', function() {
        let btn = $(this);
        let icon = $('.ico', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#dkimaddForm').attr('action'),
            data: {
                'dkimread': 1,
                'domain': btn.data('domain')
            },
            success: function(data) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                $('#sign-read .modal-title').text(btn.data('domain'));
                $('#sign-read .modal-body').html(data);
                bootstrap.Modal.getOrCreateInstance('#sign-read').show();

                let cpele = $('[data-toggle=clipboard]', $('#sign-read'));
                if (cpele.length && ClipboardJS) {
                    cpele.each(function() {
                        var clipboard = new ClipboardJS(this);
                        clipboard.on('success', function(e) {
                            nvToast($(e.trigger).data('title'), 'success');
                        });
                    });
                }
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xóa DKIM
    $('body').on('click', '[data-toggle=dkimdel]', function(e) {
        e.preventDefault();
        let item = $(this).closest('.item');
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(item.data('confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: $('#dkimaddForm').attr('action'),
                data: {
                    'dkimdel': 1,
                    'domain': item.data('domain')
                },
                success: function() {
                    bootstrap.Modal.getOrCreateInstance('#sign-read').hide();
                    dkim_list_load();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Chứng thực DKIM
    $('body').on('click', '[data-toggle=dkimverify]', function() {
        var that = $(this),
            item = that.closest('.item');
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#dkimaddForm').attr('action'),
            data: {
                'dkimverify': 1,
                'domain': item.data('domain')
            },
            dataType: "json",
            success: function(a) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if ('OK' != a.status) {
                    nvToast(a.mess, 'error');
                    return;
                }
                nvToast(a.mess, 'success');
                setTimeout(() => {
                    bootstrap.Modal.getOrCreateInstance('#sign-read').hide();
                    dkim_list_load();
                }, 1500);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Đóng mở form khai báo chứng chỉ S/MIME
    $('[data-toggle=cert_other_add_show]').on('click', function() {
        if ($('#certOtherAddForm').is(':visible')) {
            $('#certOtherAddForm').slideUp(function() {
                $(this).addClass('d-none');
            });
        } else {
            $('#certOtherAddForm').hide().removeClass('d-none').slideDown();
        }
    });

    // List chứng chỉ S/MIME qua ajax
    function cert_list_load() {
        let html = trim($('#cert_list').html());
        if (html == '') {
            $('#cert_list').html('<div class="accordion-body"><i class="fa-solid fa-spinner fa-spin-pulse"></i></div>');
        } else {
            $('#cert_list').css({
                opacity: 0.5
            });
        }
        $.ajax({
            type: 'POST',
            cache: !1,
            url: $('#certAddForm').attr('action'),
            data: 'certlist=1',
            success: function(data) {
                $('#cert_list').html(data);
                $('#collapse-cert').attr('data-loaded', 'true');
                $('#cert_list').css({
                    opacity: 1
                });
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    }

    // Upload tệp chứng chỉ S/MIME
    $("#certAddForm").on('submit', function(e) {
        e.preventDefault();
        var data = new FormData(this),
            th = $(this);
        if ('' == $('[name=pkcs12]', th).val()) {
            return !1
        }
        $('input, button', th).prop('disabled', true);
        $.ajax({
            url: th.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button', th).prop('disabled', false);
            if ('error' == a.status) {
                nvToast(a.mess, 'error');
            } else if ('overwrite' == a.status) {
                nvConfirm(a.mess, () => {
                    $('[name=overwrite]', th).val('1');
                    th.submit();
                });
            } else {
                $('[type=file]', th).val('');
                $('[name=overwrite]', th).val('0');
                $('[name=passphrase]', th).val('');
                cert_list_load();
            }
        }).fail(function(xhr, text, err) {
            $('input, button', th).prop('disabled', false);
            nvToast(err, 'error');
            console.log(xhr, text, err);
        });
    });

    // Lấy danh sách S/MIME lần đầu
    $('#collapse-cert').on('show.bs.collapse', function() {
        if ($(this).attr('data-loaded') === 'false') {
            cert_list_load();
        }
    });

    // Hiển thị thông tin S/MIME
    $('body').on('click', '[data-toggle=cert_read]', function() {
        let btn = $(this);
        let icon = $('.ico', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: false,
            url: $('#certAddForm').attr('action'),
            data: {
                'smimeread': 1,
                'email': btn.data('email')
            },
            success: function(data) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                $('#sign-read .modal-title').text(btn.data('email'));
                $('#sign-read .modal-body').html(data);
                bootstrap.Modal.getOrCreateInstance('#sign-read').show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Tải về S/MIME
    $('body').on('click', '[data-toggle=smimedownload]', function() {
        var form = $(this).closest('form'),
            passphrase = prompt(form.data('prompt'), "");
        if (passphrase != null && passphrase != '') {
            $('[name=passphrase]', form).val(passphrase);
            form.trigger('submit');
        }
    });

    // Xóa S/MIME
    $('body').on('click', '[data-toggle=smimedel]', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(form.data('confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: form.attr('action'),
                data: {
                    'smimedel': 1,
                    'email': form.data('email')
                },
                success: function() {
                    bootstrap.Modal.getOrCreateInstance('#sign-read').hide();
                    cert_list_load();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            })
        });
    });

    // Thêm S/MIME qua ajax
    $('#certOtherAddForm').on('submit', function(e) {
        e.preventDefault();
        var th = $(this),
            data = th.serialize();
        $('input, button, textarea', th).prop('disabled', true);
        $.ajax({
            url: th.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            $('input, button, textarea', th).prop('disabled', false);
            if ('error' == a.status) {
                nvToast(a.mess, 'error');
            } else if ('overwrite' == a.status) {
                nvConfirm(a.mess, () => {
                    $('[name=overwrite]', th).val('1');
                    th.submit();
                });
            } else {
                $('textarea', th).val('');
                $('[name=overwrite]', th).val('0');
                th.slideUp(function() {
                    $(this).addClass('d-none');
                    cert_list_load();
                });
            }
        }).fail(function(xhr, text, err) {
            $('input, button, textarea', th).prop('disabled', false);
            nvToast(err, 'error');
            console.log(xhr, text, err);
        });
    });

    // Chọn tab thiết lập an ninh ở chế độ mobile
    $('#settingSelect a').on('click', function(e) {
        e.preventDefault();
        let ul = $(this).closest('ul');
        let dr = $(this).closest('.dropdown');
        $('.active', ul).removeClass('active');
        $('[data-toggle="dropdown-value"]', dr).text($(this).text());
        $(this).addClass('active');
        $('#settingTabs [aria-controls=' + $(this).data('tab') + ']')[0].click();
    });

    // Lấy danh sách IP cấm hoặc IP bỏ qua flood
    function ip_list_load(url, type) {
        let ele = type ? $('#noflips') : $('#banips');
        let html = trim(ele.html());
        if (html == '') {
            ele.html('<li class="list-group-item"><i class="fa-solid fa-spinner fa-spin-pulse"></i></li>');
        } else {
            ele.css({
                opacity: 0.5
            });
        }
        $.ajax({
            type: 'GET',
            cache: !1,
            url: url,
            success: function(data) {
                ele.html(data);
                ele.css({
                    opacity: 1
                });
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        })
    }

    // Tự động load danh sách IPs nếu ở tab thiết lập an ninh tương ứng
    if ($('[name=gselectedtab]').length) {
        var gselectedtab = $('[name=gselectedtab]').val();
        if (gselectedtab == '1' || gselectedtab == '3') {
            if ($('[aria-offsets=' + gselectedtab + ']').attr('data-loaded') == 'false') {
                $('[aria-offsets=' + gselectedtab + ']').attr('data-loaded', 'true');
                setTimeout(() => {
                    ip_list_load($('[aria-offsets=' + gselectedtab + ']').data('load-url'), $('[aria-offsets=' + gselectedtab + ']').data('type'));
                }, 200);
            }
        }
    }

    // Xử lý khi thay đổi các tab thiết lập an ninh
    $('#settingTabs [data-bs-toggle=pill]').on('show.bs.tab', function() {
        let dr = $('#settingSelect');
        $('[data-toggle="dropdown-value"]', dr).text($(this).text());
        $('.active', dr).removeClass('active');
        $('[data-tab="' + $(this).attr('aria-controls') + '"]', dr).addClass('active');
        if ($(this).is('[data-loaded]')) {
            if ($(this).attr('data-loaded') == 'false') {
                $(this).attr('data-loaded', 'true');
                ip_list_load($(this).data('load-url'), $(this).data('type'));
            }
        }
    }).on('shown.bs.tab', function() {
        $('[name="selectedtab"]').val($(this).attr('aria-offsets'));
        $('[name="gselectedtab"]').val($(this).attr('aria-offsets'));
    });

    // Thêm và xóa biến được phép chèn vào cuối URL
    $('#secForm').on('click', '.add-variable', function() {
        var item = $(this).closest('.item'),
            new_item = item.clone(),
            item_id = nv_randomPassword(8);
        $('[name^=parameters], [name^=end_url_variables]', new_item).val('');
        $('[name^=end_url_variables]', new_item).attr('id', item_id);
        $('.i-label', new_item).attr('for', item_id);
        $('.parameter', new_item).prop('checked', false);
        $('.parameter', new_item).each(function() {
            let id = nv_randomPassword(8);
            $(this).attr('id', id);
            $('label', $(this).parent()).attr('for', id);
        });
        item.after(new_item);
    });
    $('#secForm').on('click', '.del-variable', function() {
        var item = $(this).closest('.item'),
            list = $(this).closest('.list');
        if ($('.item', list).length > 1) {
            item.remove();
        } else {
            $('[name^=parameters], [name^=end_url_variables]', item).val('');
            $('.parameter', item).prop('checked', false);
        }
    });

    // Thay đổi parameter các biến được phép chèn vào cuối URL
    $('#secForm').on('change', '.parameter', function() {
        let item = $(this).closest('.item');
        var parameters = '';
        $('.parameter:checked', item).each(function() {
            parameters += $(this).val() + ','
        });
        if ('' != parameters) {
            parameters = parameters.substring(0, parameters.length - 1);
        }
        $('[name^=parameters]', item).val(parameters);
    });

    // Chọn tất cả các module dùng captcha này
    $('[data-toggle=selAllAs]').on('click', function() {
        var form = $(this).closest('form');
        $('select', form).val($(this).data('type')).trigger('change');
    });

    // Xử lý khi chọn recaptcha > cảnh báo
    $('#captcha-general-settings [name=recaptcha_sitekey], #captcha-general-settings [name=recaptcha_secretkey]').on('change', function() {
        $('#modcapt-settings select').trigger('change');
    });
    $('[name^=captcha_type]').on('change', function() {
        var form = $('#captcha-general-settings'),
            val = $(this).val(),
            sitekey = $('[name=recaptcha_sitekey]', form).val(),
            secretkey = $('[name=recaptcha_secretkey]', form).val();
            tt_sitekey = $('[name=turnstile_sitekey]', form).val(),
            tt_secretkey = $('[name=turnstile_secretkey]', form).val();
        if (val != 'recaptcha' || (val == 'recaptcha' && sitekey != '' && secretkey != '')) {
            $(this).next().slideUp(function() {
                $(this).addClass('d-none');
            });
        } else {
            let it = $(this).next();
            if (!it.is(':visible')) {
                it.hide().removeClass('d-none').slideDown();
            }
        }
        if (val != 'turnstile' || (val == 'turnstile' && tt_sitekey != '' && tt_secretkey != '')) {
            $(this).next().next().slideUp(function() {
                $(this).addClass('d-none');
            });
        } else {
            let it = $(this).next().next();
            if (!it.is(':visible')) {
                it.hide().removeClass('d-none').slideDown();
            }
        }
    });

    // Chọn tất cả captcha bình luận các module là
    $('[data-toggle=selAllCaptComm]').on('click', function() {
        var form = $(this).closest('form'),
            val = $(this).val();
        if (val != '-1') {
            $('[name^=captcha_area_comm]', form).val(val);
            $(this).val('-1');
        }
    });

    // Bật tắt CORS
    $('#cors-settings [name=crosssite_restrict], #cors-settings [name=crossadmin_restrict]').on('change', function() {
        let co = bootstrap.Collapse.getOrCreateInstance($(this).closest('.item').find('.collapse')[0]);
        if ($(this).is(':checked')) {
            co.show();
        } else {
            co.hide();
        }
    });

    // Thao tác với mã bí mật load-files
    $('[data-toggle=seccode_create]').on('click', function() {
        $($(this).data('target')).val(nv_randomPassword(32));
    });
    $('[data-toggle=seccode_remove]').on('click', function() {
        $($(this).data('target')).val('');
    });
    if ($('[data-toggle=clipboard]').length && ClipboardJS) {
        var clipboard = new ClipboardJS('[data-toggle=clipboard]');
        clipboard.on('success', function(e) {
            nvToast($(e.trigger).data('title'), 'success');
        });
    }

    // Điều khiển giao diện tắt mở CSP, RP, PP
    $('#csp-settings [name=nv_csp_act], #rp-settings [name=nv_rp_act]').on('change', function() {
        let co = bootstrap.Collapse.getOrCreateInstance($(this).data('target'));
        if ($(this).is(':checked')) {
            co.show();
        } else {
            co.hide();
        }
    });

    // Điều khiển giao diện tắt mở PP
    $('#pp-settings [data-toggle="pp_act"]').on('change', function() {
        let co = bootstrap.Collapse.getOrCreateInstance($(this).data('target'));
        if ($('#pp-settings [data-toggle="pp_act"]:checked').length > 0) {
            co.show();
        } else {
            co.hide();
        }
    });

    // Xử lý khi chọn giá trị nguồn ở thiết lập CSP
    $('#csp-settings [data-toggle=none]').on('click', function() {
        if ($(this).is(':checked')) {
            nvConfirm($(this).closest('form').data('confirm'), () => {
                $('[name^=directives]', $(this).closest('.directive')).not('[data-toggle=none]').prop('disabled', true);
            }, () => {
                $(this).prop('checked', false);
            });
        } else {
            $('[name^=directives]', $(this).closest('.directive')).prop('disabled', false);
        }
    });

    // Xử lý khi chọn giá trị nguồn ở thiết lập PP
    $('#pp-settings [data-toggle=none], #pp-settings [data-toggle=all], #pp-settings [data-toggle=ignore]').on('click', function() {
        if ($(this).is(':checked')) {
            let showPromt = !($('[data-toggle=none]:checked,[data-toggle=all]:checked,[data-toggle=ignore]:checked', $(this).closest('.directive')).not(this).length > 0);
            if (showPromt) {
                nvConfirm($(this).closest('form').data('cfnone'), () => {
                    $('[name^=directives]', $(this).closest('.directive')).not('[data-toggle=none],[data-toggle=all],[data-toggle=ignore]').prop('disabled', true);
                    $('[data-toggle=none],[data-toggle=all],[data-toggle=ignore]', $(this).closest('.directive')).not(this).prop('checked', false);
                }, () => {
                    $(this).prop('checked', false);
                });
            } else {
                if ($('[data-toggle=none]:checked,[data-toggle=all]:checked,[data-toggle=ignore]:checked', $(this).closest('.directive')).not(this).length > 0) {
                    $('[name^=directives]', $(this).closest('.directive')).not('[data-toggle=none],[data-toggle=all],[data-toggle=ignore]').prop('disabled', true);
                    $('[data-toggle=none],[data-toggle=all],[data-toggle=ignore]', $(this).closest('.directive')).not(this).prop('checked', false);
                } else {
                    $(this).prop('checked', false);
                }
            }
        } else {
            $('[name^=directives]', $(this).closest('.directive')).prop('disabled', false);
        }
    });

    // Xóa IP trong cấu hình an ninh
    $('body').on('click', '[data-toggle=del_ip]', function() {
        var that = $(this),
            list = that.closest('.list');
        let icon = $('i', that);
        if (icon.is('.fa-spinner')) {
            return;
        }
        nvConfirm(list.data('confirm'), () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: !1,
                url: list.data('del-url'),
                data: '&id=' + that.data('id') + '&checkss=' + list.data('checkss'),
                dataType: "json",
                success: function(a) {
                    ip_list_load(a.url, a.type);
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            })
        });
    });

    // Thêm IP trong cấu hình an ninh
    $('body').on('click', '[data-toggle=add_ip]', function() {
        var that = $(this),
            list = that.closest('.list');
        $.ajax({
            type: 'GET',
            cache: !1,
            url: list.data('url'),
            success: function(result) {
                $('#page-tool .modal-title').text(that.attr('title'));
                $('#page-tool .modal-body').html(result);

                // Ngày tháng trong form
                $('.datepicker', $('#page-tool .modal-body')).each(function() {
                    initDatepicker($(this));
                });

                bootstrap.Modal.getOrCreateInstance('#page-tool').show();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Thay đổi phiên bản IP v4, v6 ở form thiết lập an ninh
    $('body').on('change', '.ip-version', function() {
        var form = $(this).closest('form');
        if ($(this).val() == '4') {
            $(".ip-mask [data-version='4']", form).prop('disabled', false).removeAttr('style');
            $(".ip-mask [data-version='6']", form).prop('disabled', true).prop('selected', false).css({
                'display': 'none'
            });
        } else {
            $(".ip-mask [data-version='6']", form).prop('disabled', false).removeAttr('style');
            $(".ip-mask [data-version='4']", form).prop('disabled', true).prop('selected', false).css({
                'display': 'none'
            });
        }
    });

    // Submit form thêm/sửa IP ở thiết lập an ninh
    $('body').on('submit', '.ip-action', function(e) {
        e.preventDefault();
        var that = $(this),
            data = that.serialize();
        $('input,button,textarea,select', that).prop('disabled', true);
        $.ajax({
            url: that.attr('action'),
            type: 'POST',
            data: data,
            cache: false,
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                nvToast(a.mess, 'error');
                $('input,button,textarea,select', that).prop('disabled', false);
            } else if (a.status == 'OK') {
                bootstrap.Modal.getOrCreateInstance('#page-tool').hide();
                ip_list_load(a.url, a.type);
            }
        }).fail(function(xhr, text, err) {
            $('input,button,textarea,select', that).prop('disabled', false);
            nvToast(err, 'error');
            console.log(xhr, text, err);
        });
    });

    // Ấn nút sửa IP trong thiết lập an ninh
    $('body').on('click', '[data-toggle=edit_ip]', function() {
        var that = $(this),
            list = that.closest('.list');
        let icon = $('i', that);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'GET',
            cache: !1,
            url: list.data('url') + '&id=' + that.data('id'),
            success: function(result) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));

                $('#page-tool .modal-title').text(that.attr('title'));
                $('#page-tool .modal-body').html(result);

                // Ngày tháng trong form
                $('.datepicker', $('#page-tool .modal-body')).each(function() {
                    initDatepicker($(this));
                });

                bootstrap.Modal.getOrCreateInstance('#page-tool').show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Cảnh báo chọn phương thức xác thực 2 bước
    function checkRequire2Step() {
        const req2step = parseInt($('[name="two_step_verification"]').val());
        let levError = false;
        let levWarning = false;

        if (req2step == 1 || req2step == 3) {
            levError = $('[name="admin_2step_opt[]"]:checked').length > 0 ? false : true;
            levWarning = ($('[name="admin_2step_opt[]"]:checked').length == 1 && $('[name="admin_2step_opt[]"]:checked:first').val() == 'key') ? true : false;
        }
        if (levWarning) {
            $('[data-toggle="2step-check-lev1"]').removeClass('d-none');
        } else {
            $('[data-toggle="2step-check-lev1"]').addClass('d-none');
        }
        if (levError) {
            $('[data-toggle="2step-check-lev2"]').removeClass('d-none');
        } else {
            $('[data-toggle="2step-check-lev2"]').addClass('d-none');
        }
    }
    $('[name="admin_2step_opt[]"]').on('change', function() {
        checkRequire2Step();
    });
    $('[name="two_step_verification"]').on('change', function() {
        checkRequire2Step();
    });
    checkRequire2Step();

    // Hiển thị cài đặt cache theo memcached hoặc redis
    $('#element_cache_system').on('change', function() {
        let val = $(this).val();
        if (val == 'memcached') {
            $('.memcached-settings').removeClass('d-none');
            $('.redis-settings').addClass('d-none');
        } else if (val == 'redis') {
            $('.memcached-settings').addClass('d-none');
            $('.redis-settings').removeClass('d-none');
        } else {
            $('.memcached-settings').addClass('d-none');
            $('.redis-settings').addClass('d-none');
        }
    }).trigger('change');
});
