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
    formChangeCaptcha(form);
    $('.has-error', form).removeClass('has-error');
    $("[name=pid]", form).val(0);
    $(form)[0].reset();

    const eleWarnings = document.getElementById('commentWarnings');
    if (eleWarnings) {
        const collapse = bootstrap.Collapse.getOrCreateInstance(eleWarnings, {
            toggle: false
        });
        collapse.hide();
    }

    if ($(form).data('editor')) {
        window.nveditor['commentcontent'].setData('');
    }
    $('#commentcontent').val('');
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

        // Comment form reset button
        $('[data-toggle=commReset]', commentform).on('click', function(e) {
            e.preventDefault();
            commReset($(this).parents('form'))
        });
    }
});
