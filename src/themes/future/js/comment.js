/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

'use strict';

var cmtEditorCallback = (editor) => {
    let eventInited = false;
    window.nveditor['commentcontent'].model.document.on('change', () => {
        if (!eventInited) {
            eventInited = true;
            return;
        }
        if (window.nveditor['commentcontent'].getData().trim().length === 0) {
            return;
        }
        const eleWarnings = document.getElementById('commentWarnings');
        if (!eleWarnings) return;
        const collapse = bootstrap.Collapse.getOrCreateInstance(eleWarnings, {
            toggle: false
        });
        collapse.show();
    });
};

// Hàm thiết lập lại form comment
function commReset(form) {
    $("[name=pid]", form).val(0);

    const eleWarnings = document.getElementById('commentWarnings');
    if (eleWarnings) {
        const collapse = bootstrap.Collapse.getOrCreateInstance(eleWarnings, {
            toggle: false
        });
        collapse.hide();
    }
}

$(function() {
    const commentform = $('#formcomment form');
    const eleWarnings = document.getElementById('commentWarnings');
    if (commentform.length) {
        // Hiển thị cảnh báo khi người dùng nhập liệu
        $('input[type=text], input[type=email], input[type=file], textarea', commentform).on('keyup change', function() {
            if (!eleWarnings) return;
            const collapse = bootstrap.Collapse.getOrCreateInstance(eleWarnings, {
                toggle: false
            });
            collapse.show();
        });

        // Gửi comment khi ấn Ctrl + Enter
        var data = commentform.data();
        if (!data.editor) {
            $('#commentcontent').on("keydown", function(e) {
                if (e.ctrlKey && e.keyCode == 13) {
                    $('[type=submit]', commentform).trigger('click');
                }
            });
        }
    }

    // Sắp xếp comments
    $('[data-toggle=nv_comment_sort_change]').on('change', function(e) {
        e.preventDefault();
        const data = $('#idcomment').data();
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&checkss=' + data.checkss + '&comment_load=1' + '&nocache=' + new Date().getTime(), 'sortcomm=' + $(sel).val(), function(res) {
            $('#showcomment').html(res);
        });
    });

    // Hiển thị/giấu danh sách comments
    $('[data-toggle=commListShow][data-obj]').on('click', function(e) {
        e.preventDefault();
        $('[class*=fa-]', this).toggleClass('fa-eye fa-eye-slash');
        $($(this).data('obj')).toggleClass('d-none');
    });
});
