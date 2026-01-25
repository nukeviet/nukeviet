/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

$(function() {
    let form = $('#block-content-form');

    // Thôi thêm/sửa block
    $('[data-toggle="closeWindow"]', form).on('click', function() {
        window.close();
    });

    // Lấy danh sách block khi chọn module
    $('[name="module_type"]', form).on('change', function() {
        let btn = $(this);
        let module = btn.val();
        $('[name="file_name"]', form).html('<option value="">' + $('[name="file_name"]', form).data('default') + '</option>');
        $('#block_config').html('').addClass('d-none');
        if (module == '') {
            $('.funclist', form).addClass('d-none');
            $('#check_all_func_1', form).addClass('d-none');
            return;
        }
        $('[name="file_name"]', form).prop('disabled', true);
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                checkss: $('body').data('checksess'),
                loadBlocks: module,
                bid: $('[name="bid"]', form).val()
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                $('[name="file_name"]', form).prop('disabled', false);
                btn.prop('disabled', false);
                if (res.error) {
                    nvToast(res.text, 'error');
                    return;
                }
                $('[name="file_name"]', form).html(res.html);
            },
            error: function(xhr, text, err) {
                $('[name="file_name"]', form).prop('disabled', false);
                btn.prop('disabled', false);
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Lấy config của block khi chọn block
    function loadConfig() {
        let file_name = $('[name="file_name"]', form).val();
        let module_type = $('[name="module_type"]', form).val();

        let block_fname = '';
        if (file_name != '') {
            var arr_file = file_name.split("|");
            if (parseInt(arr_file[1]) == 1) {
                block_fname = arr_file[0];
            }
        }

        // Xử lý hiển thị hoặc ẩn các funcs của module tùy vào block module hay global
        if (file_name.substring(0, 7) == 'global.') {
            // Block global
            $('.funclist', form).removeClass('d-none');
            $('#check_all_func_1', form).removeClass('d-none');
        } else {
            // Block module
            $('.funclist', form).addClass('d-none');
            $('#check_all_func_1', form).addClass('d-none');

            if (module_type == 'theme') {
                if ('undefined' != typeof arr_file) {
                    var arr = arr_file[2].split('.');
                    for (var i = 0; i < arr.length; i++) {
                        $('#idmodule_' + arr[i]).removeClass('d-none');
                    }
                }
            } else {
                $("#idmodule_" + module_type).removeClass('d-none');
            }

            var $radios = $('input:radio[name="all_func"]', form);
            $radios.filter('[value=0]').prop('checked', true);
            $('#shows_all_func', form).removeClass('d-none');
        }

        if (block_fname != '') {
            $('#block_config').html('<div class="text-center mb-3"><i class="fa-solid fa-spinner fa-spin-pulse fa-lg"></i></div>');
            $('#block_config').removeClass('d-none');

            $('[name="file_name"]', form).prop('disabled', true);
            $('[name="module_type"]', form).prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=block_config&nocache=' + new Date().getTime(),
                data: {
                    checkss: $('body').data('checksess'),
                    bid: $('[name="bid"]', form).val(),
                    module: module_type,
                    selectthemes: form.data('selectthemes'),
                    file_name: block_fname,
                },
                dataType: 'json',
                cache: false,
                success: function(res) {
                    $('[name="file_name"]', form).prop('disabled', false);
                    $('[name="module_type"]', form).prop('disabled', false);

                    if (res.error) {
                        $('#block_config').html('').addClass('d-none');
                        nvToast(res.text, 'error');
                        return;
                    }
                    if (trim(res.html) == '') {
                        $('#block_config').html('').addClass('d-none');
                        return;
                    }

                    $('#block_config').html(res.html);
                },
                error: function(xhr, text, err) {
                    $('[name="file_name"]', form).prop('disabled', false);
                    $('[name="module_type"]', form).prop('disabled', false);
                    $('#block_config').html('').addClass('d-none');
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        } else {
            $('#block_config').html('').addClass('d-none');
        }
    }
    loadConfig();
    $('[name="file_name"]', form).on('change', function() {
        loadConfig();
    });

    // Xử lý khi click hiển thị toàn bộ hay từng funcs
    $('[name="all_func"]', form).on('click', function() {
        let module = $('[name="module_type"]', form).val();
        let all_func = $(this).val();
        if (all_func == 0 && module != 'global') {
            $('#shows_all_func', form).removeClass('d-none');
        } else if (module == 'global' && all_func == 0) {
            $('#shows_all_func', form).removeClass('d-none');
        } else if (all_func == 1) {
            $('#shows_all_func', form).addClass('d-none');
        }
    });

    // Xử lý khi ấn tách nhóm
    $('[name="leavegroup"]', form).on('click', function() {
        if ($('[name="leavegroup"]:checked', form).val() == '1') {
            $('[name="all_func"]', form).filter('[value=0]').prop('checked', true);
            $('#shows_all_func').removeClass('d-none');
        }
    });

    // Click chọn tất cả các funcs hiển thị
    $('[name="checkallmod"]', form).on('click', function(e) {
        e.preventDefault();
        var obj = $('#shows_all_func', form),
            notcheck = $('[type=checkbox]:not(:checked)', obj).length;
        $('[type=checkbox]', obj).prop('checked', notcheck);
    });

    // Xử lý khi chọn func > nếu chọn hết check cả module
    $('[name^=func_id]', form).on('change', function() {
        var item = $(this).parents('.funclist'),
            notcheck = $('[name^=func_id]:not(:checked)', item).length;
        $('.checkmodule', item).prop('checked', !notcheck);
    });

    // Xử lý khi check/bỏ check module > check/bỏ check hết funcs của module
    $('.checkmodule', form).on('change', function() {
        var item = $(this).parents('.funclist');
        $('[name^=func_id]', item).prop('checked', $(this).prop('checked'));
    });

    // Định nghĩa hàm xử lý sau khi thêm block
    if (typeof nvBlockCtCallback == 'undefined') {
        window.nvBlockCtCallback = (respon) => {
            nvToast(respon.mess, 'success');
            setTimeout(() => {
                if (respon.redirect != '') {
                    window.opener.location.href = respon.redirect;
                } else {
                    window.opener.location.href = window.opener.location.href;
                }
                window.opener.focus();
                window.close();
            }, 2000);
            return 0;
        }
    }

    // Xử lý khi thay đổi kiểu hiển thị
    $('[name="dtime_type"]', form).on('change', function() {
        let btn = $(this);
        let dtime = btn.val();
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                checkss: $('body').data('checksess'),
                get_dtime_details: dtime,
                bid: $('[name="bid"]', form).val()
            },
            dataType: 'json',
            cache: false,
            success: function(res) {
                btn.prop('disabled', false);
                if (res.error) {
                    btn.val(btn.data('current'));
                    nvToast(res.text, 'error');
                    return;
                }
                $('#dtime_details').html(res.html);
                $('#dtime_details .date').datepicker({
                    dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                    changeMonth: true,
                    changeYear: true,
                    showOtherMonths: true,
                    showButtonPanel: true,
                    showOn: 'focus',
                    isRTL: $('html').attr('dir') == 'rtl'
                });
            },
            error: function(xhr, text, err) {
                btn.prop('disabled', false);
                btn.val(btn.data('current'));
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Thêm một dòng thời gian hiển thị
    form.on('click', '.add_dtime', function() {
        var item = $(this).parents('.dtime_details'),
            new_item = item.clone();
        new_item.find('[type=text]').val('');
        new_item.find('option:selected').prop('selected', false);
        $('.date', new_item).each(function() {
            $(this).removeAttr('id').removeClass('hasDatepicker').datepicker({
                dateFormat: nv_jsdate_post.replace('yyyy', 'yy'),
                changeMonth: true,
                changeYear: true,
                showOtherMonths: true,
                showButtonPanel: true,
                showOn: 'focus',
                isRTL: $('html').attr('dir') == 'rtl'
            });
        });
        item.after(new_item);
    });

    // Xóa một dòng thời gian hiển thị
    form.on('click', '.del_dtime', function() {
        var items = $(this).parents('.dtime'),
            count = $('.dtime_details', items).length,
            item = $(this).parents('.dtime_details');
        if (count > 1) {
            item.remove();
        } else {
            item.find('[type=text]').val('');
            item.find('option:selected').prop('selected', false);
        }
    });
});
