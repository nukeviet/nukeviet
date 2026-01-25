/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Chọn icon giao diện
    let sel2Fa = $('.select2-fontawesome');
    if (sel2Fa.length) {
        sel2Fa.select2({
            minimumInputLength: 1,
            ajax: {
                delay: 250,
                cache: false,
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=edit&nocache=' + new Date().getTime(),
                dataType: 'json',
                data: params => {
                    return {
                        q: params.term,
                        ajax_icon: $('body').data('checksess'),
                        page: params.page || 1
                    };
                }
            },
            templateResult: state => {
                if (!state.id) {
                    return state.text;
                }
                return $(`<div class="d-flex align-items-center">
                    <i class="` + state.id + ` fa-fw"></i>
                    <div class="ms-2">` + state.text + `</div>
                </div>`);
            },
            templateSelection: state => {
                if (!state.id) {
                    return state.text;
                }
                return $(`<div class="d-flex align-items-center">
                    <i class="` + state.id + ` fa-fw"></i>
                    <div class="ms-2">` + state.text + `</div>
                </div>`);
            }
        });
    }

    // Kích hoạt/đình chỉ module
    $('[data-toggle="changeActModule"]').on('change', function() {
        let btn = $(this);
        let act = btn.is(':checked');
        nvConfirm(nv_is_change_act_confirm[0], () => {
            btn.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_act&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    mod: btn.data('mod')
                },
                dataType: 'json',
                cache: false,
                success: function(respon) {
                    if (!respon.success) {
                        btn.prop('checked', !act);
                        btn.prop('disabled', false);
                        nvToast(respon.text, 'error');
                        return;
                    }
                    location.reload();
                },
                error: function(xhr, text, err) {
                    btn.prop('checked', !act);
                    btn.prop('disabled', false);
                    nvToast(err, 'error');
                    console.log(xhr, text, err);
                }
            });
        }, () => {
            btn.prop('checked', !act);
        });
    });

    // Thay đổi thứ tự module
    $('[data-toggle="changeWeiModule"]').on('change', function() {
        let btn = $(this);
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_weight&nocache=' + new Date().getTime(),
            data: {
                mod: btn.data('mod'),
                new_weight: btn.val()
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                if (!respon.success) {
                    nvToast(respon.text, 'error');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });
    });

    // Xóa module
    $('[data-toggle="deleteModule"]').on('click', function(e) {
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
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del&nocache=' + new Date().getTime(),
                data: {
                    checkss: btn.data('checkss'),
                    mod: btn.data('mod')
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
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });

    // Cài lại module
    $('[data-toggle="recreateModule"]').on('click', function() {
        let md = $('#modal-reinstall-module');
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        $('[name="mod"]', md).val(btn.data('mod'));
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setup_module_check&nocache=' + new Date().getTime(),
            data: {
                checkss: btn.data('checkss'),
                module: btn.data('mod')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (respon.status != 'success') {
                    nvToast(respon.message.join(', '), 'error');
                    return;
                }
                $('[name="checkss"]', md).val(respon.checkss);
                $('[name="sample"]', md).val('0');
                if (respon.code == 1) {
                    $('.showoption', md).removeClass('d-none');
                } else {
                    $('.showoption', md).addClass('d-none');
                }
                $('.message', md).html(respon.message.join('. ') + '.');
                bootstrap.Modal.getOrCreateInstance(md[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Cài đặt mới module
    $('[data-toggle="setupModule"]').on('click', function() {
        let md = $('#modal-setup-module');
        let btn = $(this);
        let icon = $('i', btn);
        let link = btn.data('link');
        if (icon.is('.fa-spinner') || link == '' || link == '#' || link.match(/javascript\:void/g)) {
            return;
        }

        $('form', md).attr('action', btn.data('link'));
        $('form', md).data('mod', btn.data('mod'));

        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setup_module_check&nocache=' + new Date().getTime(),
            data: {
                setup: 1,
                module: btn.data('mod')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (respon.status != 'success') {
                    nvToast(respon.message.join(', '), 'error');
                    return;
                }
                if (respon.code == 0 && !respon.ishook) {
                    // Không dữ liệu mẫu, không hook thì chuyển thẳng vào link cài đặt
                    window.location = link;
                    return;
                }

                // Reset lại mặc định trước khi xử lý
                $('.option', md).val('0');
                $('.submit', md).prop('disabled', false);
                $('.sample', md).addClass('d-none');
                $('.checkmodulehook', md).addClass('d-none');
                $('.messagehook', md).addClass('d-none');
                $('[name="hook_files"]', md).val('');
                $('.hookmodulechoose', md).html('');

                if (respon.code == 1) {
                    $('.message', md).html(respon.message.splice(1, 2).join('. ') + '.');
                    $('.sample', md).removeClass('d-none');
                }

                if (respon.ishook) {
                    $('.checkmodulehook', md).removeClass('d-none');

                    if (respon.hookerror != '') {
                        $('.messagehook', md).html(respon.hookerror).removeClass('d-none');
                        $('.submit', md).prop('disabled', true);
                    }

                    var hook_files = new Array();
                    var hook_stt = 0;
                    $.each(respon.hookfiles, function(k, v) {
                        hook_files.push(k);
                        hook_stt++;
                        var html = '<div class="hook-items">' +
                            '<label class="form-label" for="choose_hook_' + hook_stt + '">' + respon.hookmgs[k] + '</label>' +
                            '<select class="form-select hookmods" id="choose_hook_' + hook_stt + '">';
                        $.each(v, function(k2, v2) {
                            html += '<option value="' + v2.title + '">' + v2.custom_title + '</option>';
                        });
                        html += '</select></div>';
                        if (v.length) {
                            $('#hookmodulechoose', md).append(html);
                        }
                    });

                    $('[name="hook_files"]', md).val(hook_files.join('|'));
                }
                bootstrap.Modal.getOrCreateInstance(md[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Submit form cài đặt module
    $('[data-toggle="setupModuleForm"]').on('submit', function(e) {
        e.preventDefault();
        let $this = $(this);
        let link = $this.attr('action');
        if ($('.sample', $this).is(':visible')) {
            link += '&sample=' + $this.find('.option').val();
        }
        if ($('.checkmodulehook', $this).is(':visible')) {
            link += '&hook_files=' + encodeURIComponent($('[name="hook_files"]').val());
            let hook_mods = new Array();
            $('.hookmods', $this).each(function() {
                hook_mods.push($(this).val());
            });
            link += '&hook_mods=' + hook_mods.join('|');
        }
        window.location = link;
    });

    // Chủ động cài đặt module nào đó
    let autoSetModule = $('[data-toggle="autosetupModule"]');
    if (autoSetModule.length) {
        let btn = $('[data-toggle="setupModule"][data-mod="' + autoSetModule.data('mod') + '"]');
        if (btn.length) {
            setTimeout(() => {
                btn.trigger('click');
            }, 200);
        }
    }

    // Đổi tên alias, tiêu đề, tên gọi ngoài site, mô tả function của module
    $('[data-toggle="changeValFunc"]').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'GET',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + btn.data('mode') + '&id=' + btn.data('func-id') + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            cache: false,
            success: function(data) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                let md = $('#funChange');
                $('#funChange-label').text(data.label);
                $('#funChange-title').text(data.title + ':');
                $('#funChange-name').attr('value', data.value).attr('maxlength', data.maxlength);
                $('#funChange-type').attr('value', data.type);
                $('#funChange-id').attr('value', data.id);
                bootstrap.Modal.getOrCreateInstance(md[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });
    $('#funChange').on('shown.bs.modal', function() {
        $('#funChange-name').focus();
    });
    $('[data-toggle="changeValFuncForm"]').on('submit', function(e) {
        e.preventDefault();
        let btn = $(this).find('[type="submit"]');
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');

        let type = $('#funChange-type').val(),
            id = $('#funChange-id').val(),
            newvalue = $('#funChange-name').val();
        $.ajax({
            type: 'POST',
            cache: false,
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + type + '&nocache=' + new Date().getTime(),
            data: 'save=1&id=' + id + '&newvalue=' + encodeURIComponent(newvalue),
            dataType: 'json',
            success: function(data) {
                if ('error' == data.status) {
                    nvToast(data.mess, 'error');
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        })
    });

    // Thay đổi thứ tự function của module
    $('[data-toggle="changeWeiFunc"]').on('change', function() {
        let btn = $(this);
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_weight&nocache=' + new Date().getTime(),
            data: {
                fid: btn.data('func-id'),
                new_weight: btn.val()
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                if (!respon.success) {
                    nvToast(respon.text, 'error');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    return;
                }
                location.reload();
            },
            error: function(xhr, text, err) {
                nvToast(err, 'error');
                console.log(xhr, text, err);
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });
    });

    // Kích hoạt/đình chỉ function của module
    $('[data-toggle="changeMenuFunc"]').on('change', function() {
        let btn = $(this);
        let act = btn.is(':checked');
        btn.prop('disabled', true);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_func_submenu&nocache=' + new Date().getTime(),
            data: {
                id: btn.data('func-id')
            },
            dataType: 'json',
            cache: false,
            success: function(respon) {
                btn.prop('disabled', false);
                if (!respon.success) {
                    btn.prop('checked', !act);
                    nvToast(respon.text, 'error');
                    return;
                }
            },
            error: function(xhr, text, err) {
                btn.prop('checked', !act);
                btn.prop('disabled', false);
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
});
