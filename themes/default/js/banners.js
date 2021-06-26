/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var type = "",
    month = '',
    ads = '';
var charturl;

$(document).ready(function() {
    // Add banner
    $('#banner_plan').change(function() {
        var typeimage = $('option:selected', $(this)).data('image');
        var uploadtype = $('option:selected', $(this)).data('uploadtype');
        if (typeimage) {
            $('#banner_uploadimage').show();
            $('#banner_imagealt').show();
            $('#banner_urlrequired').hide();
            if (uploadtype == "") {
                $('#banner_uploadtype').hide();
                if (!$('#clinfomessage').data('dmessage')) {
                    $('#clinfomessage').data('dmessage', $('#clinfomessage').html());
                }
                $('#clinfomessage').html($('#banner_plan').data('blocked'));
            } else {
                $('#banner_uploadtype').html(' (' + uploadtype + ')').show();
                if ($('#clinfomessage').data('dmessage')) {
                    $('#clinfomessage').html($('#clinfomessage').data('dmessage'));
                }
            }
        } else {
            $('#banner_urlrequired').show();
            $('#banner_uploadimage').hide();
            $('#banner_imagealt').hide();
        }
    });
    $('#banner_plan').change();
    // Statistics
    $('#adsstat-ads a').click(function() {
        ads = $(this).attr('rel');
        $('#text-ads').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="' + charturl + '&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
    $('#adsstat-type a').click(function() {
        type = $(this).attr('rel');
        $('#text-type').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="' + charturl + '&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
    $('#adsstat-month a').click(function() {
        month = $(this).attr('rel');
        $('#text-month').html($(this).text());
        if (type != "" && month != "" & ads != "") {
            $('#chartdata').html('<img src="' + charturl + '&type=' + type + '&month=' + month + '&ads=' + ads + '" style="width:100%"/>');
        }
    });
});
