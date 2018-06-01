/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 1 - 31 - 2010 5 : 12
 */

$(function() {
    $('[data-toggle="collapsepdf"]').each(function() {
        $('#' + $(this).attr('id')).on('shown.bs.collapse', function() {
            $(this).find('iframe').attr('src', $(this).data('src'));
        });
    });
    $('[data-toggle="newsattachimage"]').click(function(e) {
        e.preventDefault();
        modalShow('', '<div class="text-center"><img src="' + $(this).data('src') + '" style="max-width:auto;"/></div>');
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
                if( detail ){
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

function fix_news_image(){
    var news = $('#news-bodyhtml'), newsW, w, h;
    if( news.length ){
        var newsW = news.innerWidth();
        $.each($('img', news), function(){
            if( typeof $(this).data('width') == "undefined" ){
                w = $(this).innerWidth();
                h = $(this).innerHeight();
                $(this).data('width', w);
                $(this).data('height', h);
            }else{
                w = $(this).data('width');
                h = $(this).data('height');
            }

            if( w > newsW ){
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