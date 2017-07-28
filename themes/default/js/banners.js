/**
 * @Project NUKEVIET 4.x
 * @Author  VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3 - 24 - 2010 23 : 41
 */

var type = '', month = '', ads = '';
var charturl;

$(document).ready(function(){
    $('#adsstat-ads a').click(function() {
        ads = $(this).attr('rel');
        $('#text-ads').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
    $('#adsstat-type a').click(function() {
        type = $(this).attr('rel');
        $('#text-type').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
    $('#adsstat-month a').click(function() {
        month = $(this).attr('rel');
        $('#text-month').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="{charturl}&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
});