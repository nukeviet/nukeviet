/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Select 2
    if ($('.select2').length) {
        $('.select2').select2({
            language: nv_lang_interface,
            dir: $('html').attr('dir'),
            width: '100%'
        });
    }

    // Submit đóng gói theme theo module
    $('#pkgThemeMod').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let loader = $('#pkgThemeModLoader');
        let resCtn = $('#pkgThemeModResult');
        if (loader.is(':visible')) {
            return;
        }

        let themename = $("select[name=themename]", form).val();
        let module_file = [];
        $("[name='module_file[]']:checked", form).each(function() {
            module_file.push($(this).val());
        });
        if (themename == 0 || module_file.length == 0) {
            nvToast(form.data('error'), 'error');
            return;
        }
        loader.removeClass('d-none');
        resCtn.addClass('d-none');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                themename: themename,
                module_file: module_file.join(','),
                checkss: form.data('checkss')
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                loader.addClass('d-none');
                $('[data-toggle="pkgRes"]', resCtn).html('<a href="' + res.link + '" class="link-success fw-medium">' + res.name + ' (' + res.size + ')</a>');
                resCtn.removeClass('d-none');
            },
            error: function(xhr, text, err) {
                nvToast(text, 'error');
                console.log(xhr, text, err);
                loader.addClass('d-none');
            }
        });
    });

    // Chọn giao diện để copy block
    function xCopyBlockSel() {
        let theme1 = $("select[name=theme1]").val();
        let theme2 = $("select[name=theme2]").val();
        let lCtn = $('#loadposition');

        if (theme2 != 0 && theme1 != 0 && theme1 != theme2) {
            $("select[name=theme1]").prop('disabled', true);
            $("select[name=theme2]").prop('disabled', true);

            $('[data-toggle="res"]', lCtn).html('').addClass('d-none');
            $('[data-toggle="loader"]', lCtn).removeClass('d-none');
            lCtn.removeClass('d-none');

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadposition&nocache=' + new Date().getTime(),
                data: {
                    theme2: theme2,
                    theme1: theme1,
                    checkss: $('[name="checkss"]', lCtn.closest('form')).val()
                },
                dataType: 'json',
                cache: false,
                success: function(res) {
                    $("select[name=theme1]").prop('disabled', false);
                    $("select[name=theme2]").prop('disabled', false);
                    $('[data-toggle="loader"]', lCtn).addClass('d-none');
                    if (!res.success) {
                        $('[data-toggle="res"]', lCtn).html('').addClass('d-none');
                        lCtn.addClass('d-none');
                        nvToast(res.text, 'error');
                        return;
                    }
                    $('[data-toggle="res"]', lCtn).html(res.html).removeClass('d-none');
                },
                error: function(xhr, text, err) {
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                    $('[data-toggle="res"]', lCtn).html('').addClass('d-none');
                    $('[data-toggle="loader"]', lCtn).addClass('d-none');
                    lCtn.addClass('d-none');
                    $("select[name=theme1]").prop('disabled', false);
                    $("select[name=theme2]").prop('disabled', false);
                }
            });
        } else {
            $("select[name=theme1]").prop('disabled', false);
            $("select[name=theme2]").prop('disabled', false);
            $('[data-toggle="res"]', lCtn).html('').addClass('d-none');
            $('[data-toggle="loader"]', lCtn).addClass('d-none');
            lCtn.addClass('d-none');
        }
    }
    $('[data-toggle="xCpBlSel"]').on('change', function() {
        xCopyBlockSel();
    });

    // Chọn tất cả vị trí chép block
    $(document).on('click', '[data-toggle="checkallpos"]', function() {
        if ($(this).data('all')) {
            $('[name="position[]"]').prop('checked', false);
            $(this).data('all', 0);
        } else {
            $('[name="position[]"]').prop('checked', true);
            $(this).data('all', 1);
        }
    });

    // Submit chép block
    $('#formXcopyBlock').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        let btn = $('[type="submit"]', form);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        let theme1 = $("select[name=theme1]").val();
        let theme2 = $("select[name=theme2]").val();
        let positionlist = [];
        $('input[name="position[]"]:checked').each(function() {
            positionlist.push($(this).val());
        });
        if (positionlist.length < 1) {
            nvToast(form.data('error'), 'error');
            return;
        }

        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=xcopyprocess&nocache=' + new Date().getTime(),
            data: {
                theme2: theme2,
                theme1: theme1,
                position: positionlist.join(','),
                checkss: $('[name="checkss"]', form).val()
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (!res.success) {
                    nvToast(res.text, 'error');
                    return;
                }
                nvToast(res.text, 'success');
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Phần danh sách block
    if ($('#blocklist').length) {
        let blocklist = $('#blocklist');

        // Nếu chọn module của block
        $('[name=module]', blocklist).on('change', function() {
            window.location.href = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks&module=' + $(this).val();
        });

        // Nếu chọn function của block
        $('[name=function]', blocklist).on('change', function() {
            window.location = script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks&module=' + $('[name=module]', blocklist).val() + '&func=' + $(this).val();
        });

        // Thêm/sửa block
        $('.block_content', blocklist).on('click', function() {
            if ($(this).is('.add')) {
                nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=block_content&selectthemes=" + blocklist.data('selectthemes') + "&blockredirect=" + blocklist.data('blockredirect'), "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
            } else {
                var bid = parseInt($(this).parents('.item').data("id"));
                nv_open_browse(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=block_content&selectthemes=" + blocklist.data('selectthemes') + "&bid=" + bid + "&blockredirect=" + blocklist.data('blockredirect'), "ChangeBlock", 800, 500, "resizable=no,scrollbars=yes,toolbar=no,location=no,status=no");
            }
        });

        // Đặt lại thứ tự block
        $('.block_weight', blocklist).on('click', function(e) {
            e.preventDefault();

            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('confirm'), () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks_reset_order&nocache=' + new Date().getTime(),
                    data: {
                        checkss: blocklist.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(respon) {
                        if (!respon.success) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nvToast(respon.text, 'error');
                            return;
                        }
                        nvToast(respon.text, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Thay đổi thứ tự block
        $('.order, .order_func', blocklist).on('change', function() {
            var order = $(this).val(),
                bid = $(this).closest('.item').data('id');
            let btn = $(this);
            $('.order, .order_func', blocklist).prop('disabled', true);
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + ($(this).is('.order') ? '=blocks_change_order_group' : '=blocks_change_order'),
                data: ($(this).is('.order_func') ? 'func_id=' + func_id + '&' : '') + 'order=' + order + '&bid=' + bid + '&checkss=' + blocklist.data('checkss'),
                success: function() {
                    location.reload();
                },
                error: function(xhr, text, err) {
                    $('.order, .order_func', blocklist).prop('disabled', false);
                    btn.val(btn.data('current'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Bật/tắt block
        $('.act', blocklist).on('change', function() {
            var that = $(this),
                item = that.closest('.item'),
                bid = item.data('id'),
                checkss = item.data('checkss');
            that.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=block_change_show",
                data: "bid=" + bid + "&checkss=" + checkss,
                success: function() {
                    that.prop('disabled', false);
                    nvToast(that.data('success'), 'success');
                },
                error: function(xhr, text, err) {
                    that.prop('disabled', false);
                    that.val(that.data('current'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Nút hiển thị danh sách vị trí hiển thị của block
        $('.viewlist', blocklist).on('click', function() {
            $(this).hide().closest('.item').find('.funclist').removeClass('d-none');
        });

        // Bật modal thay đổi vị trí block
        $('.change_pos_block', blocklist).on('click', function(e) {
            e.preventDefault();
            bootstrap.Modal.getOrCreateInstance($('.modal', $(this).closest('.item'))[0]).show();
        });

        // Thay đổi vị trí block
        function changeBlPos(btn) {
            btn.prop('disabled', true);
            let pos = btn.val();
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=blocks_change_pos",
                data: "bid=" + btn.closest('.item').data('id') + "&pos=" + pos + "&checkss=" + blocklist.data('checkss'),
                success: function() {
                    location.reload();
                },
                error: function(xhr, text, err) {
                    btn.prop('disabled', false);
                    btn.val(btn.data('default'));
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
        $('[name=listpos]', blocklist).on('change', function() {
            let btn = $(this);
            if (blocklist.data('funcid')) {
                nvConfirm(blocklist.data('warning1') + ' ' + btn.closest('.item').data('id') + '. ' + blocklist.data('warning2'), () => {
                    changeBlPos(btn);
                }, () => {
                    btn.val(btn.data('default'));
                    bootstrap.Modal.getOrCreateInstance(btn.closest('.modal')[0]).hide();
                });
            } else {
                changeBlPos(btn);
            }
        });

        // Xóa block
        $('.delete_block', blocklist).on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('confirm'), () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: 'POST',
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=blocks_del&nocache=' + new Date().getTime(),
                    data: {
                        bid: parseInt(btn.closest('.item').data('id')),
                        checkss: blocklist.data('checkss')
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(respon) {
                        if (!respon.success) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nvToast(respon.text, 'error');
                            return;
                        }
                        location.reload();
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Thao tác với nhiều block
        $('.bl_action', blocklist).on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let action = $('[name=action]', blocklist).val();
            let list = [];
            $('[name=idlist]:checked', blocklist).each(function() {
                list.push($(this).val());
            });
            if (list.length < 1) {
                nvToast(blocklist.data('error-noblock'), 'warning');
                return;
            }
            list = list.join(',');

            if (action == 'blocks_show_device') {
                // Chọn thiết bị hiển thị của nhiều block
                bootstrap.Modal.getOrCreateInstance('#modal_show_device').show();
            } else if (action == 'delete_group') {
                // Xóa nhiều block
                nvConfirm(blocklist.data('del-confirm'), () => {
                    btn.prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=blocks_del_group",
                        data: "list=" + list + "&checkss=" + blocklist.data('checkss'),
                        success: function(data) {
                            nvToast(data, 'info');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        },
                        error: function(xhr, text, err) {
                            btn.prop('disabled', false);
                            nvToast(err, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                });
            } else if (action == 'bls_act') {
                // Bật nhiều block
                $('[name=action],.bl_action', blocklist).prop('disabled', true);
                btn.prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=block_change_show",
                    data: "multi=1&list=" + list + "&checkss=" + blocklist.data('checkss'),
                    success: function() {
                        btn.prop('disabled', false);
                        $('[name=action],.bl_action', blocklist).prop('disabled', false);
                        $('[name=idlist]:checked', blocklist).each(function() {
                            var item = $(this).closest('.item');
                            $('[name=act]', item).val('1');
                        });
                        nvToast(nv_is_change_act_confirm[1], 'success');
                    },
                    error: function(xhr, text, err) {
                        btn.prop('disabled', false);
                        $('[name=action],.bl_action', blocklist).prop('disabled', false);
                        nvToast(err, 'error');
                        console.log(xhr, text, err);
                    }
                });
            } else if (action == 'bls_deact') {
                // Tắt nhiều block
                $.ajax({
                    type: "POST",
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=block_change_show",
                    data: "multi=0&list=" + list + "&checkss=" + blocklist.data('checkss'),
                    success: function() {
                        btn.prop('disabled', false);
                        $('[name=action],.bl_action', blocklist).prop('disabled', false);
                        $('[name=idlist]:checked', blocklist).each(function() {
                            var item = $(this).closest('.item');
                            $('[name=act]', item).val('0');
                        });
                        nvToast(nv_is_change_act_confirm[1], 'success');
                    },
                    error: function(xhr, text, err) {
                        btn.prop('disabled', false);
                        $('[name=action],.bl_action', blocklist).prop('disabled', false);
                        nvToast(err, 'error');
                        console.log(xhr, text, err);
                    }
                });
            }
        });

        // Form Thay đổi thiết bị hiển thị
        $('#modal_show_device .submit', blocklist).on('click', function() {
            let list = [], btn = $(this),
                active_device = [];
            $('[name=idlist]:checked', blocklist).each(function() {
                list.push($(this).val());
            });
            $('[name=active_device]:checked', blocklist).each(function() {
                active_device.push($(this).val());
            });
            btn.prop('disabled', true);
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=blocks_change_active",
                data: "list=" + list + "&active_device=" + active_device + "&selectthemes=" + blocklist.data('selectthemes') + "&checkss=" + blocklist.data('checkss'),
                success: function(data) {
                    btn.prop('disabled', false);
                    nvToast(data, 'info');
                    bootstrap.Modal.getOrCreateInstance('#modal_show_device').hide();
                },
                error: function(xhr, text, err) {
                    btn.prop('disabled', false);
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    }

    let mainthemes = $('#mainthemes');
    if (mainthemes.length) {
        // Thiết lập, kích hoạt
        $(".activate", mainthemes).on('click', function(e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=activatetheme",
                data: "theme=" + btn.data('theme') + "&checkss=" + btn.data("checkss"),
                success: function(data) {
                    if (data != "OK_" + btn.data('theme')) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(data, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        // Xóa các thiết lập
        $(".delete", mainthemes).click(function(e) {
            e.preventDefault();
            let btn = $(this);
            let icon = $('i', btn);
            if (icon.is('.fa-spinner')) {
                return;
            }
            nvConfirm(btn.data('confirm') + ' ' + btn.data('theme'), () => {
                icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
                $.ajax({
                    type: "POST",
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=deletetheme",
                    data: "theme=" + btn.data('theme') + "&checkss=" + btn.data("checkss"),
                    success: function(data) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(data, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr, text, err) {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        nvToast(text, 'error');
                        console.log(xhr, text, err);
                    }
                });
            });
        });

        // Cho phép xem trước
        $('[data-toggle="previewtheme"]', mainthemes).on('click', function(e) {
            e.preventDefault();
            var $this = $(this);
            var $ctn = $this.closest('.modal');
            if ($this.find('i').is(':visible')) {
                return false;
            }
            $this.find('i').removeClass('d-none');
            $.ajax({
                type: "POST",
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + "=main",
                data: "togglepreviewtheme=1&theme=" + $this.data('value'),
                dataType: 'JSON',
                success: function(data) {
                    if (data.status == 'SUCCESS') {
                        $this.find('span').html(data.spantext);
                        if (data.mode == 'enable') {
                            $('.preview-label', $ctn).removeClass('d-none');
                            $('.preview-link', $ctn).removeClass('d-none');
                            $('.preview-link', $ctn).find('[type="text"]').val(data.link);
                            $this.closest('.d-flex').removeClass('justify-content-end').addClass('justify-content-between');
                        } else {
                            $('.preview-label', $ctn).addClass('d-none');
                            $('.preview-link', $ctn).addClass('d-none');
                            $this.closest('.d-flex').removeClass('justify-content-between').addClass('justify-content-end');
                        }
                    }
                    $this.find('i').addClass('d-none');
                },
                error: function(xhr, text, err) {
                    $this.find('i').addClass('d-none');
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });

        $('.selectedfocus', mainthemes).on('focus', function() {
            $(this).select();
        });

        let clipboard = new ClipboardJS('.preview-link-btn');
        clipboard.on('success', function(e) {
            nvToast($(e.trigger).data('success'), 'success');
        });
    }
});
