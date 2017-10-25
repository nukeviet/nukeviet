/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

function nv_commment_feedback(cid, post_name) {
    $("#commentpid").val(cid);
    var data = $('#formcomment form').data();
    if (data.editor) {
        CKEDITOR.instances['commentcontent'].insertText("@" + post_name + " ");
    } else {
        $("#commentcontent").focus();
        $("#commentcontent").val("@" + post_name + " ");
    }
}

function nv_commment_like(cid, checkss, like) {
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
                $("#cid_" + cid).remove();
                $.post(nv_url_comm + '&nocache=' + new Date().getTime(), 'sortcomm=' + $('#sort').val() , function(res) {
                    $('#idcomment').html(res);
                    nv_commment_buildeditor();
                });
            } else if (rs[0] == 'ERR') {
                alert(rs[1]);
            }
        });
    }
}

function nv_commment_reload(res) {
    var rs = res.split('_');
    var data = $('#formcomment form').data();
    if (rs[0] == 'OK') {
        $("#idcomment").load(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=comment&module=' + data.module + '&area=' + data.area + '&id=' + data.id + '&allowed=' + data.allowed + '&status_comment=' + rs[1] + '&checkss=' + data.checkss + '&header=0&nocache=' + new Date().getTime(), function() {
            if (data.gfxnum == -1) {
                change_captcha();
            }
            nv_commment_buildeditor();
        });
        $('html, body').animate({
            scrollTop: $("#idcomment").offset().top
        }, 800);
    } else {
        if (data.gfxnum > 0 ) {
            change_captcha('#commentseccode_iavim');
        }
        if (rs[0] == 'ERR') {
            alert(rs[1]);
        } else {
            alert(nv_content_failed);
        }
    }
    nv_set_disable_false('buttoncontent');
}

function nv_commment_buildeditor() {
    var data = $('#formcomment form').data();
    if (data.editor) {
        CKEDITOR.replace('content', {width: '100%',height: '200px',removePlugins: 'uploadfile,uploadimage,autosave'});
    }
}

$(document).ready(function() {
    $(document).delegate('#formcomment form', 'submit', function(e) {
        var commentname = document.getElementById('commentname');
        var commentemail = document.getElementById('commentemail_iavim');
        var code = "";
        var gfx_count = $(this).data('gfxnum');
        if (gfx_count > 0) {
            code = $("#commentseccode_iavim").val();
        } else if (gfx_count == -1) {
            code = $('[name="g-recaptcha-response"]', $(btn.form)).val();
        }
        var commentcontent = strip_tags($('textarea[name=content]').val());
        if (commentname.value == "") {
            alert(nv_fullname);
            commentname.focus();
            e.preventDefault();
        } else if (!nv_email_check(commentemail)) {
            alert(nv_error_email);
            commentemail.focus();
            e.preventDefault();
        } else if (gfx_count > 0 && !nv_namecheck.test(code)) {
            error = nv_error_seccode.replace(/\[num\]/g, gfx_count);
            alert(error);
            $("#commentseccode_iavim").focus();
            e.preventDefault();
        } else if (commentcontent == '') {
            alert(nv_content);
            e.preventDefault();
        } else {
            var sd = document.getElementById('buttoncontent');
            sd.disabled = true;
        }
    });
});
