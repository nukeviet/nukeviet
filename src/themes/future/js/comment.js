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

/**
 * Hàm xử lý sau khi post form comment
 *
 * @param {Object} res Json status+mess+input
 */
function nv_commment_reload(res) {
    formChangeCaptcha($("#formcomment form"));

    if (res.status === 'OK') {
        const data = $('#idcomment').data();
        data.status_comment = res.mess;
        data.comment_load = 1;

        $.ajax({
            type: 'POST',
            cache: false,
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&nocache=' + new Date().getTime(),
            data: data,
            dataType: 'html',
            success: function(res) {
                $('#showcomment').html(res);
                $('html, body').animate({
                    scrollTop: $("#idcomment").offset().top
                }, 800);
            },
            error: function(xhr, text, err) {
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
        return;
    }

    if (res.status == 'ERR') {
        if (res.input) {
            const form = $("#formcomment form");
            const ipt = $("[name=" + res.input + "]", form);
            if (ipt.length && ipt.is(':visible')) {
                ipt.focus();
            }
        }
        nukeviet.toast(res.mess, 'error');
        return;
    }

    nukeviet.alert(nv_content_failed);
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
        const btn = $(this);
        const data = $('#idcomment').data();
        data.comment_load = 1;
        data.sortcomm = btn.val();
        btn.prop('disabled', true);

        $.ajax({
            type: 'POST',
            cache: false,
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&nocache=' + new Date().getTime(),
            data: data,
            dataType: 'html',
            success: function(res) {
                btn.prop('disabled', false);
                $('#showcomment').html(res);
            },
            error: function(xhr, text, err) {
                btn.prop('disabled', false);
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Hiển thị/giấu danh sách comments
    $('[data-toggle=commListShow][data-obj]').on('click', function(e) {
        e.preventDefault();
        $('[class*=fa-]', this).toggleClass('fa-eye fa-eye-slash');
        $($(this).data('obj')).toggleClass('d-none');
    });

    // Trả lời comment
    $('body').on('click', '[data-toggle=commFeedback][data-cid][data-postname]', function(e) {
        e.preventDefault();
        const btn = $(this);
        const form = $('#formcomment form');
        if (!form.length) return;

        $('[name=pid]', form).val(btn.data('cid'));
        const data = form.data();
        if (data.editor) {
            const editor = window.nveditor['commentcontent'];
            editor.model.change(() => {
                editor.model.insertContent(editor.data.toModel(editor.data.processor.toView("@" + btn.data('postname') + "&nbsp;")), editor.model.document.selection);
            });

            const editorElement = editor.ui.getEditableElement();
            const rect = editorElement.getBoundingClientRect();
            const absoluteTop = rect.top + window.pageYOffset;
            $('html, body').animate({ scrollTop: absoluteTop - 100 }, 200);

            editor.editing.view.focus();
        } else {
            $("form [name=content]", form).focus();
            $("form [name=content]", form).val("@" + btn.data('postname') + " ");
        }
    });

    // Like/dislike
    $('body').on('click', '[data-toggle=commLike][data-cid][data-checkss][data-like]', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = btn.prev();

        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            cache: false,
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&' + nv_fc_variable + '=like&nocache=' + new Date().getTime(),
            data: {
                cid: btn.data('cid'),
                like: btn.data('like'),
                checkss: btn.data('checkss')
            },
            dataType: 'json',
            success: function(res) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                if (res.status != 'success') {
                    return nukeviet.toast(res.mess, 'error');
                }
                $(`#count-comment${res.cid}-${res.mode}`).text(res.count);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                nukeviet.toast(err || text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xóa comment
    $('body').on('click', '[data-toggle=commDelete][data-cid][data-checkss]', function(e) {
        e.preventDefault();
        const btn = $(this);
        const icon = btn.prev();

        if (icon.is('.fa-spinner')) {
            return;
        }
        nukeviet.confirm(nv_is_del_confirm[0], () => {
            icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-spin-pulse');
            $.ajax({
                type: 'POST',
                cache: false,
                url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&' + nv_fc_variable + '=delete&nocache=' + new Date().getTime(),
                data: {
                    cid: btn.data('cid'),
                    checkss: btn.data('checkss')
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status != 'success') {
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        return nukeviet.toast(res.mess, 'error');
                    }
                    const data = $('#idcomment').data();
                    data.comment_load = 1;

                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&nocache=' + new Date().getTime(),
                        data: data,
                        dataType: 'html',
                        success: function(res) {
                            $('#showcomment').html(res);
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                        },
                        error: function(xhr, text, err) {
                            icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                            nukeviet.toast(err || text, 'error');
                            console.log(xhr, text, err);
                        }
                    });
                },
                error: function(xhr, text, err) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(icon.data('icon'));
                    nukeviet.toast(err || text, 'error');
                    console.log(xhr, text, err);
                }
            });
        });
    });
});
