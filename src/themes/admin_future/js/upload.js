/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    // Giải thích các chức năng kiểm tra file tải lên
    $('[name=upload_checking_mode]').on('change', function() {
        var val = $(this).val();
        $(this).parent().find('[data-toggle="note"]').text($(this).find('option[value=' + val + ']').data('description'));
    });

    // Xóa dòng cấu hình thumbnail
    $('[data-toggle=remove_config]').on('click', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        $(this).closest('.item').remove();
        form.submit();
    });

    // Xem trước thumbnail
    $('[data-toggle="thumbCfgViewEx"]').click(function(e) {
        e.preventDefault();
        let btn = $(this);
        let icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return;
        }

        let ctn = btn.closest('.item');
        let did = btn.data('did') != -1 ? btn.data('did') : $('[name="other_dir"]', ctn).val(),
            thumbType = $('[name="other_type"]', ctn).length ? $('[name="other_type"]', ctn).val() : $('[name="thumb_type[' + did + ']"]', ctn).val(),
            thumbW = $('[name="other_thumb_width"]', ctn).length ? $('[name="other_thumb_width"]', ctn).val() : $('[name="thumb_width[' + did + ']"]', ctn).val(),
            thumbH = $('[name="other_thumb_height"]', ctn).length ? $('[name="other_thumb_height"]', ctn).val() : $('[name="thumb_height[' + did + ']"]', ctn).val(),
            thumbQuality = $('[name="other_thumb_quality"]', ctn).length ? $('[name="other_thumb_quality"]', ctn).val() : $('[name="thumb_quality[' + did + ']"]', ctn).val();

        if ((!did && btn.data('did') == -1) || thumbType == 0 || (!thumbW && thumbType != 2) || (!thumbH && thumbType != 1) || !thumbQuality || thumbQuality == 0) {
            nvToast(btn.data('errmsg'), 'error');
            return;
        }

        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + nv_func_name + '&nocache=' + new Date().getTime(),
            data: {
                getexample: 1,
                did: did,
                t: thumbType,
                w: thumbW,
                h: thumbH,
                q: thumbQuality,
                checkss: $('body').data('checksess')
            },
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (res.status != 'success') {
                    nvToast(res.message, 'warning');
                    return;
                }
                let md = $('#thumbnail-preview');
                $('.imgorg', md).attr('src', res.src);
                $('.imgthumb', md).attr('src', res.thumbsrc);
                bootstrap.Modal.getOrCreateInstance(md[0]).show();
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nvToast(err, 'error');
                console.log(xhr, text, err);
            }
        });
    });
});
