/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

var nvNotificationTimer = 0;
var nvNotificationPage = 1;
var nvNotificationScrollbar = false;

/*
 * Query vĩnh viễn để tính số thông báo mới
 */
function nv_get_notification(timestamp) {
    if (nvNotificationTimer) {
        clearTimeout(nvNotificationTimer);
    }
    var queryString = {
        'notification_get': 1,
        'timestamp': timestamp
    };
    $.ajax({
        type: 'GET',
        url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
        data: queryString,
        success: function(data) {
            var indicator = $('#main-notifications>.icon-notifications .indicator');
            if (data.data_from_file > 0) {
                indicator.removeClass('d-none');
            } else {
                indicator.addClass('d-none');
            }
            $('#main-notifications>.main-notifications>li>.title .badge').html(data.data_from_file);
            nvNotificationTimer = setTimeout("nv_get_notification()", 30000);
        },
        error: function(jqXHR, exception) {
            nvNotificationTimer = setTimeout("nv_get_notification()", 30000);
        }
    });
}

function notification_reset() {
    $.post(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(), 'notification_reset=1', function(res) {
        var indicator = $('#main-notifications>.icon-notifications .indicator');
        indicator.addClass('d-none');
    });
}

$(document).ready(function() {
    // Xem thử có thông báo nào mới hay không
    nv_get_notification();

    // Cuộn xuống để xem nhiều thông báo hơn nữa
    ($('#main-notifications .nv-notification-scroller')[0]).addEventListener('ps-y-reach-end', function() {
        nvNotificationPage++;
        $('#main-notifications .loader').show();
        $.get(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&page=' + nvNotificationPage + '&nocache=' + new Date().getTime(), function(result) {
            $('#main-notifications .nv-notification-scroller .content').append(result);
            $('#main-notifications .loader').hide();
            if (result != '') {
                nvNotificationScrollbar.update();
                $('#main-notifications .nv-notification-scroller .content span.date').timeago();
            }
        });
    });

    // Khi ấn nút có hình cái chuông để xem thông báo
    $('#main-notifications').on('show.bs.dropdown', function() {
        if (nvNotificationScrollbar) {
            nvNotificationScrollbar.destroy();
            nvNotificationScrollbar = false;
        }
        $('#main-notifications .nv-notification-scroller .content').html('');
        $('#main-notifications .loader').show();
        nvNotificationPage = 1;
        $.get(script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&nocache=' + new Date().getTime(), function(result) {
            $('#main-notifications .nv-notification-scroller .content').html(result);
            $('#main-notifications .nv-notification-scroller .content span.date').timeago();
            $('#main-notifications .loader').hide();
            nvNotificationScrollbar = new PerfectScrollbar($('#main-notifications .nv-notification-scroller')[0], {
                wheelPropagation: false
            });
        });
    });
});
