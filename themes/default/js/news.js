/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
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

$(document).ready(function() {
    // Xem PDF đính kèm
    $('[data-toggle="collapsepdf"]').each(function() {
        $('#' + $(this).attr('id')).on('shown.bs.collapse', function() {
            $(this).find('iframe').attr('src', $(this).data('src'));
        });
    });

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
});

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
        });
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
    return false;
}

function fix_news_image() {
    var news = $('#news-bodyhtml'), newsW, w, h;
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

$(window).on('load', function() {
    fix_news_image();
});

$(window).on("resize", function() {
    fix_news_image();
});
