/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function valid2faErrorShow(a) {
    $(a).parent().parent().addClass("has-error");
    $("[data-mess]", $(a).parent().parent().parent()).not(".tooltip-current").tooltip("destroy");
    $(a).tooltip({
        container: "body",
        placement: "bottom",
        title: function() {
            return "" != $(a).attr("data-current-mess") ? $(a).attr("data-current-mess") : nv_required
        }
    });
    $(a).focus().tooltip("show");
    "DIV" == $(a).prop("tagName") && $("input", a)[0].focus()
}

function valid2faCheck(a) {
    if ($(a).is(':visible')) {
        var c = $(a).attr("data-pattern"),
            d = $(a).val(),
            b = $(a).prop("tagName"),
            e = $(a).prop("type");
        if ("INPUT" == b && "email" == e) {
            if (!nv_mailfilter.test(d)) return !1
        } else if ("SELECT" == b) {
            if (!$("option:selected", a).length) return !1
        } else if ("DIV" == b && $(a).is(".radio-box")) {
            if (!$("[type=radio]:checked", a).length) return !1
        } else if ("DIV" == b && $(a).is(".check-box")) {
            if (!$("[type=checkbox]:checked", a).length) return !1
        } else if ("INPUT" == b || "TEXTAREA" == b)
            if ("undefined" == typeof c || "" == c) {
                if ("" == d) return !1
            } else if (a = c.match(/^\/(.*?)\/([gim]*)$/), !(a ? new RegExp(a[1], a[2]) : new RegExp(c)).test(d)) return !1;
    }
    return !0
}

function valid2faErrorHidden(a, b) {
    if (!b) b = 2;
    b = parseInt(b);
    var c = $(a),
        d = $(a);
    for (var i = 0; i < b; i++) {
        c = c.parent();
        if (i >= 2) d = d.parent()
    }
    d.tooltip("destroy");
    c.removeClass("has-error")
}

function form2faErrorHidden(a) {
    $(".has-error", a).removeClass("has-error");
    $("[data-mess]", a).tooltip("destroy")
}

function valid2faReset(a) {
    var d = $(".nv-info", a).attr("data-default");
    if (!d) d = $(".nv-info-default", a).html();
    $(".nv-info", a).removeClass("error success").html(d);
    form2faErrorHidden(a);
    $("input,button,select,textarea", a).prop("disabled", !1);
    $(a)[0].reset()
}

function confirmpass_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!valid2faCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), valid2faErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), form2faErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1),
                    $(".tooltip-current", a).removeClass("tooltip-current"),
                    "" != d.input ? $(a).find("[name=\"" + d.input + "\"]").each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                        valid2faErrorShow(this)
                    }) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
                        $("[type=submit]", a).prop("disabled", !1)
                    }, 1E3)
            } else {
                window.location.href = window.location.href;
            }
        }
    }));
    return !1
}

function opt_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!valid2faCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), valid2faErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), form2faErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1),
                    $(".tooltip-current", a).removeClass("tooltip-current"),
                    "" != d.input ? $(a).find("[name=\"" + d.input + "\"]").each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                        valid2faErrorShow(this)
                    }) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
                        $("[type=submit]", a).prop("disabled", !1)
                    }, 1E3)
            } else if (d.status == "ok") {
                if (typeof d.mess != 'undefined' && d.mess != '') {
                    alert(d.mess)
                }
                window.location.href = (typeof d.redirect != 'undefined' && d.redirect != '') ? d.redirect : window.location.href;
            }
        }
    }));
    return !1
}

$(function() {
    // View secretkey
    $('[data-toggle="manualsecretkey"]').click(function(e) {
        e.preventDefault();
        modalShowByObj($(this).attr('href'));
    });

    // Tắt xác thực 2 bước
    $('[data-toggle="turnoff2step"]').click(function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return false;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            type: 'post',
            data: {
                tokend: btn.data('tokend'),
                turnoff2step: 1
            },
            dataType: 'json',
            success: function(response) {
                if (response.status != 'ok') {
                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                    alert(response.mess);
                    return;
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                alert(error);
            }
        });
    });

    $('[data-toggle=opt_validForm]').on('submit', function() {
        return opt_validForm(this)
    });

    $('[data-toggle=confirmpass_validForm]').on('submit', function() {
        return confirmpass_validForm(this)
    });

    $('[data-toggle=valid2faErrorHidden]').on('keypress', function() {
        $(this).data('parents') ? valid2faErrorHidden(this, $(this).data('parents')) : valid2faErrorHidden(this)
    });

    // Đổi mã
    $('[data-toggle="changecode2step"]').on('click', function(e) {
        e.preventDefault();

        const btn = $(this);
        const icon = $('i', btn);
        if (icon.is('.fa-spinner')) {
            return false;
        }
        icon.removeClass(icon.data('icon')).addClass('fa-spinner fa-pulse');
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            type: 'post',
            data: {
                tokend: btn.data('tokend'),
                changecode2step: 1
            },
            dataType: 'json',
            success: function(response) {
                if (response.status != 'ok') {
                    icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                    alert(response.mess);
                    return;
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                icon.removeClass('fa-spinner fa-pulse').addClass(icon.data('icon'));
                alert(error);
            }
        });
    });

    // In code
    $('[data-toggle="print-codes"]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).attr('href'), 'printcodes', 800, 600);
    });

    // Copy code
    const cBtn = $('[data-toggle="copy-codes"]');
    if (cBtn.length) {
        var clipboard = new ClipboardJS(cBtn[0]);
        clipboard.on('success', function () {
            $('span', cBtn).text(cBtn.data('copied'));
        });
    }

    // Xác nhận đã chép mã
    $('.confirmed-codes').on('click', function() {
        $('[data-toggle="confirm-complete"]').prop('disabled', false);
    });
    $('[data-toggle="confirm-complete"]').on('click', function() {
        window.location.href = $(this).data('link');
    });

    // Đóng mở danh sách khóa bảo mật
    $('#security-keys').on('hide.bs.collapse', function (e) {
        locationReplace($(e.currentTarget).data('page-url'));
    });
    $('#security-keys').on('show.bs.collapse', function (e) {
        locationReplace($(e.currentTarget).data('show-keys-url'));
    });

    // Đóng mở danh sách mã dự phòng
    $('#recovery-codes').on('hide.bs.collapse', function (e) {
        locationReplace($(e.currentTarget).data('page-url'));
    });
    $('#recovery-codes').on('show.bs.collapse', function (e) {
        locationReplace($(e.currentTarget).data('show-codes-url'));
    });

    // Thay đổi phương án xác thực 2 bước ưu thích
    $('[data-toggle="preferred_2fa_method"]').on('change', function() {
        const btn = $(this);
        const value = btn.val();
        btn.prop('disabled', true);
        $.ajax({
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            type: 'post',
            data: {
                change_preferred_2fa: btn.data('checkss'),
                pref_2fa: value
            },
            dataType: 'json',
            success: function(response) {
                if (response.status != 'ok') {
                    alert(response.mess);
                }
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr, status, error);
                btn.prop('disabled', false);
                alert(error);
                location.reload();
            }
        });
    });
});

$(window).on('load', function() {
    const pkForm = $('#container-edit-app');
    if (pkForm.length) {
        if (pkForm.data('autoscroll')) {
            $('html, body').animate({
                scrollTop: pkForm.offset().top - 60
            }, 100);
        }
    }
});
