/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function nv_comment_reset(event, form) {
    event.preventDefault();
    var b = $("[onclick*='change_captcha']", $(form));
    if (b.length) {
        b.click();
    } else if ($('[data-toggle=recaptcha]', $(form)).length || $("[data-recaptcha3]", $(form).parent()).length) {
        change_captcha()
    }
    $("[name=pid]", form).val(0);
    $(form)[0].reset();
    if ($(form).data('editor')) {
        CKEDITOR.instances['commentcontent'].setData('', function() {
            this.updateElement()
        })
    }
}

function nv_commment_feedback(event, cid, post_name) {
    event.preventDefault();
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

function nv_commment_like(event, cid, checkss, like) {
    event.preventDefault();
    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&' + nv_fc_variable + '=like&nocache=' + new Date().getTime(), 'cid=' + cid + '&like=' + like + '&checkss=' + checkss, function(res) {
        var rs = res.split('_');
        if (rs[0] == 'OK') {
            $("#" + rs[1]).text(rs[2]);
        } else if (rs[0] == 'ERR') {
            alert(rs[1]);
        }
    });
}

function nv_commment_delete(cid, checkss) {
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
        var b = $("#formcomment [onclick*='change_captcha']");
        if (b.length) {
            b.click()
        } else if ($('#formcomment [data-toggle=recaptcha]').length || $("#formcomment [data-recaptcha3]").length) {
            change_captcha()
        }

        if (rs[0] == 'ERR') {
            alert(rs[2]);
            "" != rs[1] && $("#formcomment form [name=" + rs[1] + "]").focus()
        } else {
            alert(nv_content_failed);
        }
    }
}

function nv_comment_submit(form) {
    var name = strip_tags(trim($("[name=name]", form).val()));
    $("[name=name]", form).val(name);
    if ("" == name) {
        alert(nv_fullname);
        $("[name=name]", form).focus();
        return !1
    }
    var email = trim($("[name=email]", form).val());
    $("[name=email]", form).val(email);
    if (!(email.length >= 7 && nv_mailfilter.test(email))) {
        alert(nv_error_email);
        $("[name=email]", form).focus();
        return !1
    }
    if ($(form).data('editor')) {
        CKEDITOR.instances['commentcontent'].updateElement()
    }
    var content = strip_tags(trim($("[name=content]", form).val()));
    $("[name=content]", form).val(content);
    if ("" == content) {
        alert(nv_content);
        $("[name=content]", form).focus();
        return !1
    }
    if ($("[name=code]", form).length) {
        var gfx_count = parseInt($("[name=code]", form).attr('maxlength')),
            code = trim($("[name=code]", form).val());
        $("[name=code]", form).val(code);
        if (gfx_count != code.length) {
            error = nv_error_seccode.replace(/\[num\]/g, gfx_count);
            alert(error);
            $("[name=code]", form).focus();
            return !1
        }
    }
}

function nv_comment_sort_change(event, sel) {
    event.preventDefault();
    var data = $('#idcomment').data();
    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&checkss=' + data.checkss + '&comment_load=1' + '&nocache=' + new Date().getTime(), 'sortcomm=' + $(sel).val(), function(res) {
        $('#showcomment').html(res);
    });
}

$(document).ready(function() {
    var commentform = $('#formcomment form');
    if (commentform.length) {
        // Gửi comment khi ấn Ctrl + Enter
        var data = commentform.data();
        if (data.editor) {
            CKEDITOR.instances['commentcontent'].on('key', function(event) {
                if (event.data.keyCode === 1114125) {
                    commentform.submit()
                }
            });
        } else {
            $('#commentcontent').on("keydown", function(e) {
                if (e.ctrlKey && e.keyCode == 13) {
                    commentform.submit()
                }
            });
        }
    }
});
