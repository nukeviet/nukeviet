/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

window.newsPls = {};
window.newsSeek = 0;

// Trình điều khiển báo nói
function newsPlayVoice(voiceid, voicepath, voicetitle, autoplay) {
    // Destroy các player đang phát
    $.each(window.newsPls, function(k, v) {
        v.destroy();
    });
    window.newsPls = {};
    window.newsPls[voiceid] = new Plyr('#newsVoicePlayer', {
        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'airplay']
    });
    window.newsPls[voiceid].source = {
        type: 'audio',
        title: voicetitle,
        sources: [{
            src: voicepath
        }],
    };
    window.newsPls[voiceid].isFirstPlay = true;
    window.newsPls[voiceid].isPlaying = false;
    window.newsPls[voiceid].on('playing', function() {
        window.newsPls[voiceid].isPlaying = true;
        if (window.newsPls[voiceid].isFirstPlay) {
            window.newsPls[voiceid].isFirstPlay = false;
            window.newsPls[voiceid].forward(window.newsSeek);
        }
    });
    window.newsPls[voiceid].on('pause', function() {
        window.newsPls[voiceid].isPlaying = false;
    });
    window.newsPls[voiceid].on('ended', function() {
        window.newsPls[voiceid].isPlaying = false;
    });
    window.newsPls[voiceid].on('ready', function() {
        if (autoplay) {
            window.newsPls[voiceid].play();
        }
        window.newsPls[voiceid].on('timeupdate', function() {
            if (window.newsPls[voiceid].isPlaying) {
                window.newsSeek = window.newsPls[voiceid].currentTime;
            }
        });
    });
}

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
    $('.has-error', form).removeClass('has-error');
    var a = $("[name=friend_email]", form).val();
    a = trim(strip_tags(a));
    $("[name=friend_email]", form).val(a);
    if (!nv_mailfilter.test(a)) {
        alert($("[name=friend_email]", form).data("error"));
        $("[name=friend_email]", form).parent().addClass('has-error');
        $("[name=friend_email]", form).focus();
        return !1
    }
    a = $("[name=your_name]", form).val();
    a = trim(strip_tags(a));
    $("[name=your_name]", form).val(a);
    if ("" == a || !nv_uname_filter.test(a)) {
        alert($("[name=your_name]", form).data("error"));
        $("[name=your_name]", form).parent().addClass('has-error');
        $("[name=your_name]", form).focus();
        return !1
    }
    if ($("[name=nv_seccode]:visible", form).length) {
        a = $("[name=nv_seccode]", form).val();
        if (a.length != parseInt($("[name=nv_seccode]", form).attr("maxlength")) || !/^[a-z0-9]+$/i.test(a)) {
            alert($("[name=nv_seccode]", form).data("error"));
            $("[name=nv_seccode]", form).parent().addClass('has-error');
            $("[name=nv_seccode]", form).focus();
            return !1
        }
    }
    if ($('[type=checkbox]', form).length) {
        var checkvalid = true;
        $('[type=checkbox]', form).each(function() {
            if (!$(this).is(':checked')) {
                checkvalid = false;
                alert($(this).data('error'));
                $(this).parent().addClass('has-error');
                $(this).focus();
                return !1
            }
        });
        if (!checkvalid) {
            return !1
        }
    }
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
    // Mục lục bài viết
    $('a[data-scroll-to]').on('click', function(e) {
        e.preventDefault;
        var id = $(this).data('scroll-to');
        $("html, body").animate({
            scrollTop: $('[data-id=' + id + ']').offset().top
        }, 800);
    });
    $('h2[data-id], h3[data-id]').on('click', function() {
        $("html, body").animate({
            scrollTop: $('#navigation').offset().top
        }, 800);
    });

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

    // Xử lý player
    var playerCtn = $('#newsVoicePlayer');
    if (playerCtn.length) {
        newsPlayVoice(playerCtn.data('voice-id'), playerCtn.data('voice-path'), playerCtn.data('voice-title'), playerCtn.data('autoplay'));
    }

    // Chọn giọng đọc: Thiết lập làm giọng đọc mặc định lần sau
    $('[data-news="voicesel"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        $(this).data('busy', true);
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            data: {
                setDefaultVoice: $this.data('tokend'),
                id: $this.data('id')
            }
        }).done(function() {
            $this.data('busy', false);
            $('[data-news="voiceval"]').html($this.text());
            newsPlayVoice($this.data('id'), $this.data('path'), $this.text(), true);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            console.error(jqXHR, textStatus, errorThrown);
        });
    });

    // Bật/Tắt tự phát giọng đọc
    $('[data-news="switchapl"]').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        if ($this.data('busy')) {
            return;
        }
        $(this).data('busy', true);
        $.ajax({
            type: 'POST',
            url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            data: {
                'setAutoPlayVoice': $this.data('tokend')
            }
        }).done(function(res) {
            $this.data('busy', false);
            if (res.value == 1) {
                $this.addClass('checked');
            } else {
                $this.removeClass('checked');
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $this.data('busy', false);
            console.error(jqXHR, textStatus, errorThrown);
        });
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
