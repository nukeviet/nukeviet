/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function commReset(form) {
    formChangeCaptcha(form);
    $('.has-error', form).removeClass('has-error');
    $("[name=pid]", form).val(0);
    $(form)[0].reset();
    if ($(form).data('editor')) {
        CKEDITOR.instances['commentcontent'].setData('', function() {
            this.updateElement();
            if ($('.confirm', form).length) {
                $('.confirm', form).slideUp()
            }
        })
    } else {
        if ($('.confirm', form).length) {
            $('.confirm', form).slideUp()
        }
    }
}

function commFeedback(cid, post_name) {
    if ($('#formcomment form').length) {
        $("#formcomment form [name=pid]").val(cid);
        var data = $('#formcomment form').data();
        if (data.editor) {
            CKEDITOR.instances['commentcontent'].insertText("@" + post_name + " ");
        } else {
            $("#formcomment form [name=content]").focus();
            $("#formcomment form [name=content]").val("@" + post_name + " ");
        }
    }
}

function commLike(cid, checkss, like) {
    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&' + nv_fc_variable + '=like&nocache=' + new Date().getTime(), 'cid=' + cid + '&like=' + like + '&checkss=' + checkss, function(res) {
        var rs = res.split('_');
        if (rs[0] == 'OK') {
            $("#" + rs[1]).text(rs[2]);
        } else if (rs[0] == 'ERR') {
            alert(rs[1]);
        }
    });
}

function commentDelete(cid, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&' + nv_fc_variable + '=delete&nocache=' + new Date().getTime(), 'cid=' + cid + '&checkss=' + checkss, function(res) {
            var rs = res.split('_');
            if (rs[0] == 'OK') {
                var data = $('#idcomment').data();
                $("#showcomment").load(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&checkss=' + data.checkss + '&comment_load=1&nocache=' + new Date().getTime());
            } else if (rs[0] == 'ERR') {
                alert(rs[1]);
            }
        });
    }
}

function nv_commment_reload(res) {
    var rs = res.split('_');
    var data = $('#idcomment').data();
    if (rs[0] == 'OK') {
        $("#showcomment").load(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&status_comment=' + rs[1] + '&checkss=' + data.checkss + '&comment_load=1&nocache=' + new Date().getTime(), function() {
            $("#formcomment form .reset").trigger("click")
        });
        $('html, body').animate({
            scrollTop: $("#idcomment").offset().top
        }, 800);
    } else {
        formChangeCaptcha($("#formcomment form"));
        if (rs[0] == 'ERR') {
            alert(rs[2]);
            "" != rs[1] && $("#formcomment form [name=" + rs[1] + "]:visible").length && $("#formcomment form [name=" + rs[1] + "]").focus()
        } else {
            alert(nv_content_failed);
        }
    }
}

function commFormSubmit(form) {
    $('.has-error', form).removeClass('has-error');
    var name = strip_tags(trim($("[name=name]", form).val()));
    if ("" == name) {
        $("[name=name]", form).parent().addClass('has-error');
        alert(nv_fullname);
        $("[name=name]", form).focus();
        return !1
    }

    var email = trim($("[name=email]", form).val());
    if (!(email.length >= 7 && nv_mailfilter.test(email))) {
        $("[name=email]", form).parent().addClass('has-error');
        alert(nv_error_email);
        $("[name=email]", form).focus();
        return !1
    }

    if ($(form).data('editor')) {
        CKEDITOR.instances['commentcontent'].updateElement()
    }
    var content = strip_tags($("[name=content]", form).val());
    content = content.replace(/\s*\&nbsp\;\s*/gi, ' ');
    content = trim(content);
    if ("" == content) {
        alert(nv_content);
        if ($(form).data('editor')) {
            $('#cke_commentcontent').parent().addClass('has-error');
            CKEDITOR.instances['commentcontent'].setData('', function() {
                this.updateElement();
                this.focus()
            })
        } else {
            $("[name=content]", form).parent().addClass('has-error');
            $("[name=content]", form).val('').focus();
        }
        return !1
    }

    if ($('[type=checkbox]', form).length) {
        var checkvalid = true;
        $('[type=checkbox]', form).each(function() {
            if (!$(this).is(':checked')) {
                checkvalid = false;
                $(this).parent().addClass('has-error');
                alert($(this).data('error'));
                $(this).focus();
                return !1
            }
        });
        if (!checkvalid) {
            return !1
        }
    }
    return !0
}

function nv_comment_sort_change(sel) {
    var data = $('#idcomment').data();
    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&checkss=' + data.checkss + '&comment_load=1' + '&nocache=' + new Date().getTime(), 'sortcomm=' + $(sel).val(), function(res) {
        $('#showcomment').html(res);
    });
}

$(function() {
    var commentform = $('#formcomment form');
    if (commentform.length) {
        // Gửi comment khi ấn Ctrl + Enter
        var data = commentform.data();
        if (data.editor) {
            CKEDITOR.instances['commentcontent'].on('key', function(event) {
                if (event.data.keyCode === 1114125) {
                    $('[type=submit]', commentform).trigger('click')
                }
            });
        } else {
            $('#commentcontent').on("keydown", function(e) {
                if (e.ctrlKey && e.keyCode == 13) {
                    $('[type=submit]', commentform).trigger('click')
                }
            });
        }

        // Comment form reset button
        $('[data-toggle=commReset]', commentform).on('click', function(e) {
            e.preventDefault();
            commReset($(this).parents('form'))
        });
    }

    $('input[type=text], input[type=email], input[type=file], textarea', commentform).on('change', function() {
        $('.confirm', commentform).slideDown()
    });

    if (typeof(CKEDITOR) !== 'undefined') {
        CKEDITOR.instances['commentcontent'].on('change', function() {
            $('.confirm', commentform).slideDown()
        });
    }

    // Sắp xếp comments
    $('[data-toggle=nv_comment_sort_change]').on('change', function(e) {
        e.preventDefault();
        nv_comment_sort_change(this)
    });

    // Hiển thị/giấu danh sách comments
    $('[data-toggle=commListShow][data-obj]').on('click', function(e) {
        e.preventDefault();
        $('[class*=fa-]', this).toggleClass('fa-eye fa-eye-slash');
        $($(this).data('obj')).toggleClass('hidden')
    });

    // Xóa comment
    $('body').on('click', '[data-toggle=commDelete][data-cid][data-checkss]', function(e) {
        e.preventDefault();
        commentDelete($(this).data('cid'), $(this).data('checkss'))
    });

    // Trả lời comment
    $('body').on('click', '[data-toggle=commFeedback][data-cid][data-postname]', function(e) {
        e.preventDefault();
        commFeedback($(this).data('cid'), $(this).data('postname'))
    });

    // Like/dislike
    $('body').on('click', '[data-toggle=commLike][data-cid][data-checkss][data-like]', function(e) {
        e.preventDefault();
        commLike($(this).data('cid'), $(this).data('checkss'), $(this).data('like'))
    });
});
