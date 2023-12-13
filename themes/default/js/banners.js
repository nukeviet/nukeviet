/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function afSubmit_precheck(form) {
    $(".has-error", form).removeClass("has-error");
    if ($('[name=title]', form).val().length < 3) {
        $('[name=title]', form).parent().addClass('has-error');
        alert($('[name=title]', form).data('mess'));
        $('[name=title]', form).focus();
        return !1
    }
    
    if ($('[name=image]', form).is('.required') && !$('[name=image]', form).val()) {
        $('[name=image]', form).parent().addClass('has-error');
        alert($('[name=image]', form).data('mess'));
        $('[name=image]', form).focus();
        return !1
    }

    if ($('[name=url]', form).is('.required') && $('[name=url]', form).val().length < 3) {
        $('[name=url]', form).parent().addClass('has-error');
        alert($('[name=url]', form).data('mess'));
        $('[name=url]', form).focus();
        return !1
    }

    if ($('[name=captcha]', form).length && $('[name=captcha]', form).val().length < parseInt($('[name=captcha]', form).attr('maxlength'))) {
        $('[name=captcha]', form).parent().addClass('has-error');
        alert($('[name=captcha]', form).data('mess'));
        $('[name=captcha]', form).focus();
        return !1
    }

    return !0
}

function afSubmit(form) {
    $(".has-error", form).removeClass("has-error");
    var data = new FormData(form);
    $("input,button,select", form).prop("disabled", !0);
    $.ajax({
        type: 'POST',
        cache: !0,
        url: $(form).prop("action"),
        data: data,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(d) {
            alert(d.mess);
            if (d.status == "error") {
                $("input,button,select", form).prop("disabled", !1);
                formChangeCaptcha(form);
                if ("" != d.input && $("[name=" + d.input + "]:visible", form).length) {
                    $("[name=" + d.input + "]:visible", form).parent().addClass('has-error');
                    $("[name=" + d.input + "]:visible", form).focus()
                }
            } else {
                window.location.href = d.redirect;
            }
        }
    })
}

function loadStat() {
    var type = $('#adsstat-type').val(),
        month = $('#adsstat-month').val(),
        ads = $('#adsstat-ads').val(),
        charturl = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=viewmap';
    if (!!type && !!month && !!ads) {
        $('#chartdata').html('<span class="load-bar"></span>').show();
        var width = Math.floor($('#chartdata').width()) > 500 ? 700 : 500;
        charturl += '&type=' + type + '&month=' + month + '&ads=' + ads + '&width=' + width;
        var img = new Image();
        $(img).on('load', function() {
            $('#chartdata').html('<img src="' + img.src + '" style="width:100%"/>');
        });
        img.src = charturl;
    } else {
        $('#chartdata').hide()
    }
}

$(function() {
    // Add banner
    if ($('#banner_plan').length) {
        $('#banner_plan').change(function() {
            var typeimage = $('option:selected', $(this)).data('image'),
                uploadtype = $('option:selected', $(this)).data('uploadtype'),
                form = $(this).parents('form');
            if (!!typeimage) {
                $('#banner_uploadtype').text(' (' + uploadtype + ')').show();
                $('#banner_uploadimage').show();
                $('.file', form).addClass('required');
                $('.url', form).removeClass('required');
            } else {
                $('#banner_uploadimage').hide();
                $('.file', form).removeClass('required');
                $('.url', form).addClass('required');
            }
        });
        $('#banner_plan').trigger('change')
    }

    $('body').on('submit', '[data-toggle=afSubmit]', function(e) {
        e.preventDefault();
        afSubmit(this)
    });

    $('body').on('keypress', '[data-toggle=errorHidden][data-event=keypress]', function() {
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=errorHidden][data-event=change]', function(e) {
        e.preventDefault();
        $(this).parent().removeClass("has-error")
    });

    $('body').on('change', '[data-toggle=loadStat]', function(e) {
        e.preventDefault();
        loadStat()
    });
});
