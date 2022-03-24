/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function addpass() {
    $("a[href*=edit_password]").click();
    return !1
}

function safe_deactivate_show(a, b) {
    $(b).hide(0);
    $(a).fadeIn();
    return !1
}

function safekeySend(a) {
    $(".safekeySend", a).prop("disabled", !0);
    $.ajax({
        type: $(a).prop("method"),
        cache: !1,
        url: $(a).prop("action"),
        data: $(a).serialize() + '&resend=1',
        dataType: "json",
        success: function(e) {
            "error" == e.status ? ($(".safekeySend", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $("[name=\"" + e.input + "\"]", a).addClass("tooltip-current").attr("data-current-mess", $("[name=\"" + e.input + "\"]", a).attr("data-mess")), validErrorShow($("[name=\"" + e.input + "\"]", a))) : ($(".nv-info", a).html(e.mess).removeClass("error").addClass("success").show(), setTimeout(function() {
                var d = $(".nv-info", a).attr("data-default");
                if (!d) d = $(".nv-info-default", a).html();
                $(".nv-info", a).removeClass("error success").html(d);
                $(".safekeySend", a).prop("disabled", !1);
            }, 6E3))
        }
    });
    return !1
}

function changeAvatar(a) {
    if (nv_safemode) return !1;
    nv_open_browse(a, "NVImg", 650, 430, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
    return !1;
}

function deleteAvatar(a, b, c) {
    if (nv_safemode) return !1;
    $(c).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !1,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=avatar/del',
        data: 'checkss=' + b + '&del=1',
        dataType: 'json',
        success: function(e) {
            $(a).attr("src", $(a).attr("data-default"));
        }
    });
    return !1
}

function datepickerShow(a) {
    if ("object" == typeof $.datepicker) {
        $(a).datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: !0,
            changeYear: !0,
            showOtherMonths: !0,
            showOn: "focus",
            yearRange: "-90:+0"
        });
        $(a).css("z-index", "9998").datepicker('show');
    }
}

function button_datepickerShow(a) {
    var b = $(a).parent();
    datepickerShow($(".datepicker", b))
}

function verkeySend(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0;
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    d || ($("[name=vsend]", a).val("1"), $("[type=submit]", a).click());
    return !1
}

function addQuestion(a) {
    var b = $(a).parents('form');
    $("[name=question]", b).val($(a).text());
    validErrorHidden($("[name=question]", b));
    return !1
}

function usageTermsShow(t) {
    $.ajax({
        type: 'POST',
        cache: !0,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=register',
        data: 'get_usage_terms=1',
        dataType: 'html',
        success: function(e) {
            if ($('#sitemodal').length) {
                if (!$('#sitemodalTerm').length) {
                    $('body').append('<div id="sitemodalTerm" class="modal fade" role="dialog">' + $('#sitemodal').html() + '</div>')
                }
                "" != t && 'undefined' != typeof t && $("#sitemodalTerm .modal-content").prepend('<div class="modal-header"><h2 class="modal-title">' + t + '</h2></div>');
                $("#sitemodalTerm").find(".modal-title").html(t);
                $("#sitemodalTerm").find(".modal-body").html(e);
                $('#sitemodalTerm').on('hidden.bs.modal', function() {
                    $("#sitemodalTerm .modal-content").find(".modal-header").remove()
                });
                $("#sitemodalTerm").modal({
                    backdrop: "static"
                })
            } else {
                alert(strip_tags(e))
            }
        }
    });
    return !1
}

function validErrorShow(a) {
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

function uname_check(val) {
    return (val == '' || nv_uname_filter.test(val)) ? true : false;
}

function required_uname_check(val) {
    return (val != '' && nv_uname_filter.test(val)) ? true : false;
}

function login_check(val, type, max, min) {
    if ('' == val || val.length > max || val.length < min) return false;
    else if (type == '1' && !/^[0-9]+$/.test(val)) return false;
    else if (type == '2' && !/^[a-z0-9]+$/i.test(val)) return false;
    else if (type == '3' && !/^[a-z0-9]+[a-z0-9\-\_\s]+[a-z0-9]+$/i.test(val)) return false;
    else if (type == '4' && !/^[\p{L}\p{Mn}0-9]+([\s]+[\p{L}\p{Mn}0-9]+)*$/u.test(val)) return false;
    return true;
}

function validCheck(a) {
    if ($(a).is(':visible')) {
        var c = $(a).attr("data-pattern"),
            d = $(a).val(),
            b = $(a).prop("tagName"),
            e = $(a).prop("type"),
            f = $(a).attr("data-callback");
        if ("INPUT" == b && "email" == e) {
            if (!nv_mailfilter.test(d)) return !1
        } else if ("undefined" != typeof f && "uname_check" == f) {
            if (!uname_check(d)) return $(a).attr("data-mess", $(a).attr("data-error")), !1
        } else if ("undefined" != typeof f && "required_uname_check" == f) {
            if (!required_uname_check(d)) return $(a).attr("data-mess", $(a).attr("data-error")), !1
        } else if ("undefined" != typeof f && "login_check" == f) {
            if (!login_check(d, $(a).data("type"), $(a).attr("maxlength"), $(a).data("minlength"))) return !1
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

function validErrorHidden(a, b) {
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

function formErrorHidden(a) {
    $(".has-error", a).removeClass("has-error");
    $("[data-mess]", a).tooltip("destroy")
}

function validReset(a) {
    var d = $(".nv-info", a).attr("data-default");
    if (!d) d = $(".nv-info-default", a).html();
    $(".nv-info", a).removeClass("error success").html(d);
    formErrorHidden(a);
    $("input,button,select,textarea", a).prop("disabled", !1);
    $(a)[0].reset();
    var b = $("[onclick*='change_captcha']", $(a));
    if (b.length) {
        b.click()
    } else if ($('[data-toggle=recaptcha]', $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) {
        change_captcha()
    }
}

function login_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var c = 0,
        b = [];
    $(a).find(".required").each(function() {
        "password" == $(a).prop("type") && $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return c++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    c || (b.type = $(a).prop("method"), b.url = $(a).prop("action"), b.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: b.type,
        cache: !1,
        url: b.url,
        data: b.data,
        dataType: "json",
        success: function(d) {
            var b = $("[onclick*='change_captcha']", a);
            b && b.click();
            if (d.status == "error") {
                $("input,button", a).not("[type=submit]").prop("disabled", !1),
                    $(".tooltip-current", a).removeClass("tooltip-current"),
                    "" != d.input ? $(a).find("[name=\"" + d.input + "\"]").each(function() {
                        $(this).addClass("tooltip-current").attr("data-current-mess", d.mess);
                        validErrorShow(this)
                    }) : $(".nv-info", a).html(d.mess).addClass("error").show(), setTimeout(function() {
                        $("[type=submit]", a).prop("disabled", !1);
                        if ($('[data-toggle=recaptcha]', $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) {
                            change_captcha()
                        }
                    }, 1E3)
            } else if (d.status == "ok") {
                $(".nv-info", a).html(d.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(),
                    $(".form-detail", a).hide(), $("#other_form").hide(), setTimeout(function() {
                        if ("undefined" != typeof d.redirect && "" != d.redirect) {
                            window.location.href = d.redirect;
                        } else {
                            $('#sitemodal').modal('hide');
                            window.location.href = window.location.href;
                        }
                    }, 3E3)
            } else if (d.status == "2steprequire") {
                $(".form-detail", a).hide(), $("#other_form").hide();
                $(".nv-info", a).html("<a href=\"" + d.input + "\">" + d.mess + "</a>").removeClass("error").removeClass("success").addClass("info").show();
            } else {
                $("input,button", a).prop("disabled", !1);
                $('.loginstep1, .loginstep2, .loginCaptcha', a).toggleClass('hidden');
            }
        }
    }));
    return !1
}

function reg_validForm(a) {
    // Xử lý các trình soạn thảo
    if ("undefined" != typeof CKEDITOR)
        for (var c in CKEDITOR.instances) $("#" + c).val(CKEDITOR.instances[c].getData());
    $(".has-error", a).removeClass("has-error");
    var e = 0;
    c = [];
    $(a).find("input.required,input[data-callback],textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return e++,
            $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    e || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: c.type,
        cache: !1,
        url: c.url,
        data: c.data,
        dataType: "json",
        success: function(b) {
            var d = $("[onclick*='change_captcha']", a);
            d && d.click();
            "error" == b.status ? ($("input,button,select,textarea",
                a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), "" != b.input ? $(a).find('[name="' + b.input + '"]').each(function() {
                $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                validErrorShow(this)
            }) : ($(".nv-info", a).html(b.mess).addClass("error").show(), $("html, body").animate({
                scrollTop: $(".nv-info", a).offset().top
            }, 800)), ($("[data-toggle=recaptcha]", $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) && change_captcha()) : ($(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(),
                "ok" == b.input ? setTimeout(function() {
                    $(".nv-info", a).fadeOut();
                    $("input,button,select,textarea", a).prop("disabled", !1);
                    $("[onclick*=validReset]", a).click()
                }, 6E3) : ($("html, body").animate({
                    scrollTop: $(".nv-info", a).offset().top
                }, 800), $(".form-detail", a).hide(), setTimeout(function() {
                    window.location.href = "" != b.input ? b.input : window.location.href
                }, 6E3)))
        },
        error: function(b, d, f) {
            window.console.log ? console.log(b.status + ": " + f) : alert(b.status + ": " + f)
        }
    }));
    return !1
}

function lostpass_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0,
        c = [];
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess", $(this).attr("data-mess")), validErrorShow(this), !1
    });
    if (!d) {
        if (($('[data-toggle=recaptcha]', $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) && $("[name=step]", a).val() == 'step1') {
            $("[name=gcaptcha_session]", a).val($("[name=g-recaptcha-response]", a).val());
        }
        c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0);
        $.ajax({
            type: c.type,
            cache: !1,
            url: c.url,
            data: c.data,
            dataType: "json",
            success: function(b) {
                if (b.status == "error") {
                    $("[name=step]", a).val(b.step);
                    if ("undefined" != typeof b.info && "" != b.info) $(".nv-info", a).removeClass('error success').text(b.info);
                    $("input,button", a).prop("disabled", !1);
                    $(".required", a).removeClass("required");
                    $(".tooltip-current", a).removeClass("tooltip-current");
                    $("[class*=step]", a).hide();
                    $("." + b.step + " input", a).addClass("required");
                    $("." + b.step, a).show();
                    if (b.input == '') {
                        $(".nv-info", a).html(b.mess).addClass("error").show();
                    } else {
                        $(a).find("[name=\"" + b.input + "\"]").each(function() {
                            $(this).addClass("tooltip-current").attr("data-current-mess", b.mess);
                            validErrorShow(this);
                        });
                    }
                    if (b.step == 'step1') {
                        if ($("[onclick*='change_captcha']", a).length) {
                            $("[onclick*='change_captcha']", a).click()
                        } else if ($('[data-toggle=recaptcha]', $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) {
                            change_captcha();
                            $("[name=gcaptcha_session]", a).val('');
                        }
                    } else {
                        $('[data-toggle=recaptcha]', $(a)).length && $('[data-toggle=recaptcha]', $(a)).remove();
                        $("[data-recaptcha3]", $(a).parent()).length && $(a).data('recaptcha3', null);
                    }
                } else {
                    $(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show();
                    setTimeout(function() {
                        window.location.href = b.input;
                    }, 6E3);
                }
            }
        });
    }
    return !1;
}

function changemail_validForm(a) {
    $(".has-error", a).removeClass("has-error");
    var d = 0,
        c = [];
    $(a).find("input.required,textarea.required,select.required,div.required").each(function() {
        var b = $(this).prop("tagName");
        "INPUT" != b && "TEXTAREA" != b || "password" == $(a).prop("type") || "radio" == $(a).prop("type") || "checkbox" == $(a).prop("type") || $(this).val(trim(strip_tags($(this).val())));
        if (!validCheck(this)) return d++, $(".tooltip-current", a).removeClass("tooltip-current"), $(this).addClass("tooltip-current").attr("data-current-mess",
            $(this).attr("data-mess")), validErrorShow(this), !1
    });
    d || (c.type = $(a).prop("method"), c.url = $(a).prop("action"), c.data = $(a).serialize(), formErrorHidden(a), $(a).find("input,button,select,textarea").prop("disabled", !0), $.ajax({
        type: c.type,
        cache: !1,
        url: c.url,
        data: c.data,
        dataType: "json",
        success: function(b) {
            $("[name=vsend]", a).val("0");
            "error" == b.status ? ($("input,button,select,textarea", a).prop("disabled", !1), $(".tooltip-current", a).removeClass("tooltip-current"), $(a).find("[name=" + b.input + "]").each(function() {
                $(this).addClass("tooltip-current").attr("data-current-mess",
                    b.mess);
                validErrorShow(this)
            }), ($("[data-toggle=recaptcha]", $(a)).length || $("[data-recaptcha3]", $(a).parent()).length) && change_captcha()) : ($(".nv-info", a).html(b.mess + '<span class="load-bar"></span>').removeClass("error").addClass("success").show(), $(".form-detail", a).hide(), setTimeout(function() {
                window.location.href = "" != b.input ? b.input : window.location.href
            }, 6E3))
        }
    }));
    return !1
}

function bt_logout(a) {
    $(a).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !1,
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=users&' + nv_fc_variable + '=logout&nocache=' + new Date().getTime(),
        data: 'nv_ajax_login=1',
        dataType: 'html',
        success: function(e) {
            $('.userBlock', $(a).parent().parent().parent().parent()).hide();
            $('.nv-info', $(a).parent().parent().parent().parent()).addClass("text-center success").html(e).show();
            setTimeout(function() {
                window.location.href = window.location.href
            }, 2E3)
        }
    });
    return !1
}

function login2step_change(ele) {
    var ele = $(ele),
        form = ele,
        i = 0;
    while (!form.is('form')) {
        if (i++ > 10) {
            break;
        }
        form = form.parent();
    }
    if (form.is('form')) {
        $('.loginstep2 input,.loginstep3 input', form).val('');
        $('.loginstep2,.loginstep3', form).toggleClass('hidden');
    }
    return false;
}

var UAV = {};
// Default config, replace it with your own
UAV.config = {
    inputFile: 'image_file',
    uploadIcon: 'upload_icon',
    pattern: /^(image\/jpeg|image\/png)$/i,
    maxsize: 2097152,
    avatar_width: 80,
    avatar_height: 80,
    target: 'preview',
    uploadInfo: 'uploadInfo',
    uploadGuide: 'guide',
    x: 'crop_x',
    y: 'crop_y',
    w: 'crop_width',
    h: 'crop_height',
    originalDimension: 'original-dimension',
    displayDimension: 'display-dimension',
    imageType: 'image-type',
    imageSize: 'image-size',
    btnSubmit: 'btn-submit',
    btnReset: 'btn-reset',
    uploadForm: 'upload-form'
};
// Default language, replace it with your own
UAV.lang = {
    bigsize: 'File too large',
    smallsize: 'File too small',
    filetype: 'Only accept jmage file tyle',
    bigfile: 'File too big',
    upload: 'Please upload and drag to crop'
};
UAV.data = {
    error: false,
    busy: false,
    cropperApi: null
};
UAV.tool = {
    bytes2Size: function(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0) return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    },
    update: function(e) {
        $('#' + UAV.config.x).val(e.x);
        $('#' + UAV.config.y).val(e.y);
        $('#' + UAV.config.w).val(e.width);
        $('#' + UAV.config.h).val(e.height);
    },
    clear: function() {
        $('#' + UAV.config.x).val(0);
        $('#' + UAV.config.y).val(0);
        $('#' + UAV.config.w).val(0);
        $('#' + UAV.config.h).val(0);
    }
};
// Please use this package with fengyuanchen/cropper https://fengyuanchen.github.io/cropper
UAV.common = {
    read: function(file) {
        $('#' + UAV.config.uploadIcon).hide();
        var fRead = new FileReader();
        fRead.onload = function(e) {
            $('#' + UAV.config.target).show();
            $('#' + UAV.config.target).attr('src', e.target.result);
            $('#' + UAV.config.target).on('load', function() {
                var img = document.getElementById(UAV.config.target);
                var boxWidth = $('#' + UAV.config.target).innerWidth();
                var boxHeight = Math.round(boxWidth * img.naturalHeight / img.naturalWidth);
                var minCropBoxWidth = UAV.config.avatar_width / (img.naturalWidth / boxWidth);
                var minCropBoxHeight = UAV.config.avatar_height / (img.naturalHeight / boxHeight);
                if (img.naturalWidth < UAV.config.avatar_width || img.naturalHeight < UAV.config.avatar_height) {
                    UAV.common.error(UAV.lang.smallsize);
                    UAV.data.error = true;
                    return false;
                }
                if (!UAV.data.error) {
                    // Hide and show data
                    $('#' + UAV.config.uploadGuide).hide();
                    $('#' + UAV.config.uploadInfo).show();
                    $('#' + UAV.config.imageType).html(file.type);
                    $('#' + UAV.config.imageSize).html(UAV.tool.bytes2Size(file.size));
                    $('#' + UAV.config.originalDimension).html(img.naturalWidth + ' x ' + img.naturalHeight);

                    UAV.data.cropperApi = $('#' + UAV.config.target).cropper({
                        viewMode: 3,
                        dragMode: 'crop',
                        aspectRatio: 1,
                        responsive: true,
                        modal: true,
                        guides: false,
                        highlight: true,
                        autoCrop: false,
                        autoCropArea: 0.1,
                        movable: false,
                        rotatable: false,
                        scalable: false,
                        zoomable: false,
                        zoomOnTouch: false,
                        zoomOnWheel: false,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        minCropBoxWidth: minCropBoxWidth,
                        minCropBoxHeight: minCropBoxHeight,
                        minContainerWidth: 10,
                        minContainerHeight: 10,
                        crop: function(e) {
                            UAV.tool.update(e);
                        },
                        built: function() {
                            var imageData = $(this).cropper('getImageData');
                            var cropBoxScale = imageData.naturalWidth / imageData.width;
                            imageData.width = parseInt(Math.floor(imageData.width));
                            imageData.height = parseInt(Math.floor(imageData.height));
                            var cropBoxSize = {
                                width: 80 / cropBoxScale,
                                height: 80 / cropBoxScale
                            };
                            cropBoxSize.left = (imageData.width - cropBoxSize.width) / 2;
                            cropBoxSize.top = (imageData.height - cropBoxSize.height) / 2;
                            $(this).cropper('crop');
                            $(this).cropper('setCropBoxData', {
                                left: cropBoxSize.left,
                                top: cropBoxSize.top,
                                width: cropBoxSize.width,
                                height: cropBoxSize.height
                            });
                            $('#' + UAV.config.w).val(imageData.width);
                            $('#' + UAV.config.h).val(imageData.height);
                            $('#' + UAV.config.displayDimension).html(imageData.width + ' x ' + imageData.height);
                        }
                    });
                } else {
                    $('#' + UAV.config.uploadIcon).show();
                }
            });
        };
        fRead.readAsDataURL(file);
    },
    init: function() {
        UAV.data.error = false;
        if ($('#' + UAV.config.inputFile).val() == '') {
            UAV.data.error = true;
        }
        var image = $('#' + UAV.config.inputFile)[0].files[0];
        // Check ext
        if (!UAV.config.pattern.test(image.type)) {
            UAV.common.error(UAV.lang.filetype);
            UAV.data.error = true;
        }
        // Check size
        if (image.size > UAV.config.maxsize) {
            UAV.common.error(UAV.lang.bigfile);
            UAV.data.error = true;
        }
        if (!UAV.data.error) {
            // Read image
            UAV.common.read(image);
        }
    },
    error: function(e) {
        UAV.common.reset();
        alert(e);
    },
    reset: function() {
        if (UAV.data.cropperApi != null) {
            UAV.data.cropperApi.cropper('destroy');
            UAV.data.cropperApi = null;
        }
        UAV.data.error = false;
        UAV.data.busy = false;
        UAV.tool.clear();
        $('#' + UAV.config.target).removeAttr('src').removeAttr('style').hide();
        $('#' + UAV.config.uploadIcon).show();
        $('#' + UAV.config.uploadInfo).hide();
        $('#' + UAV.config.uploadGuide).show();
        $('#' + UAV.config.imageType).html('');
        $('#' + UAV.config.imageSize).html('');
        $('#' + UAV.config.originalDimension).html('');
        $('#' + UAV.config.w).val('');
        $('#' + UAV.config.h).val('');
        $('#' + UAV.config.displayDimension).html('');
    },
    submit: function() {
        if (!UAV.data.busy) {
            if ($('#' + UAV.config.w).val() == '' || $('#' + UAV.config.w).val() == '0') {
                alert(UAV.lang.upload);
                return false;
            }
            UAV.data.busy = true;
            return true;
        }
        return false;
    }
};
UAV.init = function() {
    $('#' + UAV.config.uploadIcon).click(function() {
        $('#' + UAV.config.inputFile).trigger('click');
    });
    $('#' + UAV.config.inputFile).change(function() {
        UAV.common.init();
    });
    $('#' + UAV.config.btnReset).click(function() {
        if (!UAV.data.busy) {
            UAV.common.reset();
            $('#' + UAV.config.uploadIcon).trigger('click');
        }
    });
    $('#' + UAV.config.uploadForm).submit(function() {
        return UAV.common.submit();
    });
};

$(document).ready(function() {
    // Delete user handler
    $('[data-toggle="admindeluser"]').click(function(e) {
        e.preventDefault();
        var data = $(this).data();
        if (confirm(nv_is_del_confirm[0])) {
            $.post(data.link, 'userid=' + data.userid, function(res) {
                if (res == 'OK') {
                    window.location.href = data.back;
                } else {
                    var r_split = res.split("_");
                    if (r_split[0] == 'ERROR') {
                        alert(r_split[1]);
                    } else {
                        alert(nv_is_del_confirm[2]);
                    }
                }
            });
        }
    });
});
