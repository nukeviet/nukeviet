/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function sendrating(id, point, newscheckss) {
    if (point == 1 || point == 2 || point == 3 || point == 4 || point == 5) {
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + newscheckss + '&point=' + point, function(res) {
            res = res.split('|');
            $('#stringrating').html(res[0]);
            if (typeof res[1] != 'undefined' && res[1] != '0') {
                $('#numberrating').html(res[1]);
            }
            if (typeof res[2] != 'undefined' && res[2] != '0') {
                $('#click_rating').html(res[2]);
            }
        })
    }
}

function nv_del_content(id, checkss, base_adminurl, detail) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(base_adminurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=del_content&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + checkss, function(res) {
            var r_split = res.split('_');
            if (r_split[0] == 'OK') {
                if (detail) {
                    window.location.href = r_split[2];
                } else {
                    window.location.href = strHref;
                }
            } else if (r_split[0] == 'ERR') {
                alert(r_split[1]);
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}

function get_alias(op) {
    var title = strip_tags(document.getElementById('idtitle').value);
    if (title != '') {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=' + op + '&nocache=' + new Date().getTime(), 'get_alias=' + encodeURIComponent(title), function(res) {
            if (res != "") {
                document.getElementById('idalias').value = res;
            } else {
                document.getElementById('idalias').value = '';
            }
        });
    }
}

function fix_news_image() {
    var news = $('#news-bodyhtml'),
        newsW, w, h;
    if (news.length) {
        var newsW = news.innerWidth();
        $.each($('img', news), function() {
            if (typeof $(this).data('width') == "undefined") {
                w = $(this).innerWidth();
                h = $(this).innerHeight();
                $(this).data('width', w);
                $(this).data('height', h);
            } else {
                w = $(this).data('width');
                h = $(this).data('height');
            }

            if (w > newsW) {
                $(this).prop('width', newsW);
                $(this).prop('height', h * newsW / w);
            }
        });
    }
}

function newsSendMailModal(fm, url, sess) {
    var loaded = $(fm).attr('data-loaded');
    if ('false' == loaded) {
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: 'checkss=' + sess,
            success: function(e) {
                $('.modal-body', $(fm)).html(e);
                loadCaptcha($(fm));
                $(fm).attr('data-loaded', 'true');
                $(fm).modal('show')
            }
        })
    } else {
        $(fm).modal('show')
    }
}

function newsSendMail(form) {
    var a = $("[name=friend_email]", form).val();
    a = trim(strip_tags(a));
    $("[name=friend_email]", form).val(a);
    if (!nv_mailfilter.test(a)) return alert($("[name=friend_email]", form).data("error")), $("[name=friend_email]", form).focus(), !1;
    a = $("[name=your_name]", form).val();
    a = trim(strip_tags(a));
    $("[name=your_name]", form).val(a);
    if ("" == a || !nv_uname_filter.test(a)) return alert($("[name=your_name]", form).data("error")), $("[name=your_name]", form).focus(), !1;
    if ($("[name=nv_seccode]:visible", form).length && (a = $("[name=nv_seccode]", form).val(), a.length != parseInt($("[name=nv_seccode]", form).attr("maxlength")) || !/^[a-z0-9]+$/i.test(a))) return alert($("[name=nv_seccode]", form).data("error")), $("[name=nv_seccode]", form).focus(), !1;
    $("[name=your_message]", form).length && $("[name=your_message]", form).val(trim(strip_tags($("[name=your_message]", form).val())));
    a = $(form).serialize();
    $("input,button,textarea", form).prop("disabled", !0);
    $.ajax({
        type: $(form).prop("method"),
        cache: !1,
        url: $(form).prop("action"),
        data: a,
        dataType: "json",
        success: function(b) {
            $("input,button,textarea", form).prop("disabled", !1);
            formChangeCaptcha(form);
            "error" == b.status ? (alert(b.mess), b.input && $("[name=" + b.input + "]:visible", form).length && $("[name=" + b.input + "]", form).focus()) : (alert(b.mess), $("[name=friend_email]", form).val(''), $("[name=your_message]", form).length && $("[name=your_message]", form).val(''), $("[data-dismiss=modal]", form).click())
        }
    })
}

$(window).on('load', function() {
    fix_news_image();
});

$(window).on("resize", function() {
    fix_news_image();
});

$(document).ready(function() {
    // Xem file đính kèm
    $('[data-toggle="collapsefile"]').each(function() {
        $('#' + $(this).attr('id')).on('show.bs.collapse', function() {
            if ('false' == $(this).attr('data-loaded')) {
                $(this).attr('data-loaded', 'true')
                $(this).find('iframe').attr('src', $(this).data('src'))
            }
        })
    })

    // Xem ảnh đính kèm
    $('[data-toggle="newsattachimage"]').click(function(e) {
        e.preventDefault();
        modalShow('', '<div class="text-center"><img src="' + $(this).data('src') + '" style="max-width: 100%; height: auto;"/></div>');
    });

    // Get Alias
    $('[data-toggle="get_alias"][data-op]').on('click', function(e) {
        e.preventDefault();
        get_alias($(this).data('op'));
    });

    // Send mail form submit
    $('body').on('submit', '[data-toggle=newsSendMail]', function(e) {
        e.preventDefault();
        return newsSendMail(this)
    });

    $('body').on('click', '[data-toggle=newsSendMailModal][data-obj][data-url][data-ss]', function(e) {
        e.preventDefault();
        newsSendMailModal($(this).data('obj'), $(this).data('url'), $(this).data('ss'))
    });

    // News print
    $('body').on('click', '[data-toggle="newsPrint"][data-url]', function(e) {
        e.preventDefault();
        nv_open_browse($(this).data('url'), 'newsPrint', 840, 500, 'resizable=yes,scrollbars=yes,toolbar=no,location=no,status=no')
    });

    // searchOnSite
    $('body').on('click', '[data-toggle=searchOnSite]', function(e) {
        e.preventDefault();
        var input = $("#fsea input[name=q]"),
            maxlength = input.attr("maxlength"),
            minlength = input.attr("data-minlength"),
            q = strip_tags(trim(input.val()));
        input.parent().removeClass("has-error");
        "" == q || q.length < minlength || q.length > maxlength ? (input.parent().addClass("has-error"), input.val(q).focus()) : window.location.href = $(this).data("href") + rawurlencode(q)
    });

    // Xóa tin
    $('body').on('click', '[data-toggle=nv_del_content]', function(e) {
        e.preventDefault();
        nv_del_content($(this).data('id'), $(this).data('checkss'), $(this).data('adminurl'), $(this).data('detail'))
    });

    if ($('[data-toggle=rating]').length) {
        var rat = $('[data-toggle=rating]'),
            isDisabled = $('.rating', rat).is('.disabled'),
            checkLoad = function(v) {
                $('input[value=' + v + ']', rat).prop('checked', true)
            },
            sendrating = function(id, point, newscheckss) {
                if (point >= 1 && point <= 5) {
                    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=rating&nocache=' + new Date().getTime(), 'id=' + id + '&checkss=' + newscheckss + '&point=' + point, function(res) {
                        res = res.split('|');
                        if (res[1] != '0' && res[2] != '0') {
                            $('#stringrating').html(res[0]);
                            $('#numberrating').text(res[1]);
                            $('#click_rating').text(res[2]);
                            checkLoad(Math.round(parseFloat(res[1])));
                            $(".feedback", rat).text($(".feedback", rat).data('success'));
                        } else {
                            checkLoad(rat.data('checked'));
                            $(".feedback", rat).text(res[0]);
                        }

                        $('.ratingInfo', rat).removeClass('hidden');
                    })
                }
            };
        $('label', rat).on("mouseenter", function() {
            !isDisabled && $(".feedback", rat).text($(this).data('title'))
        }).on("mouseleave", function() {
            !isDisabled && $(".feedback", rat).text($(".feedback", rat).data('default'))
        });
        $('input', rat).on('click', function() {
            if (!isDisabled) {
                var point = $('input:checked', rat).val();
                $('.rating', rat).addClass('disabled');
                $('label', rat).off('mouseenter mouseleave click');
                $(".feedback", rat).html('<span class="load-bar"></span>');
                sendrating(rat.data('id'), point, rat.data('checkss'))
            }
        });

        $('[type=radio]', rat).prop('checked', false);
        if (!!rat.data('checked')) {
            $('.ratingInfo', rat).removeClass('hidden');
            checkLoad(rat.data('checked'))
        }
    }

});
