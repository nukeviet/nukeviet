/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Giá trị này = 0 thì tạm dừng kiểm tra số thông báo
var load_notification = 1;

$(document).ready(function() {
    function notification_reset() {
        $.post(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(), 'notification_reset=1', function(res) {
            $('#notification').hide();
        });
    }

    function nv_get_notification(timestamp) {
        if (load_notification) {
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: {
                    'notification_get': 1,
                    'timestamp': timestamp
                },
                success: function(data) {
                    if (data.data_from_file > 0) {
                        $('#notification').show().html(data.data_from_file);
                    } else {
                        $('#notification').hide();
                    }
                    // Load mỗi 30s một lần
                    setTimeout(function() {
                        nv_get_notification(0);
                    }, 30000);
                },
                cache: false
            });
        }
    }

    // Lấy và hiển thị số thông báo chưa đọc
    nv_get_notification(0);

    // Load thêm thông báo khi cuộn xuống
    var page = 1;
    $('#notification_load').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            page++;
            $('#notification_waiting').show();
            $.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&page=' + page + '&nocache=' + new Date().getTime(), function(result) {
                $('#notification_load').append(result);
                $('#notification_waiting').hide();
            });
        }
    });

    // Notification
    $('#notification-area').on('show.bs.dropdown', function() {
        $.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&nocache=' + new Date().getTime(), function(result) {
            notification_reset();
            $('#notification_load').html(result).slimScroll({
                height: '250px'
            });
            $("abbr.timeago").timeago();
            $('#notification_waiting').hide();
        });
    });

    $('#notification-area').on('show.bs.dropdown', function() {
        page = 1;
        $('#notification_load').html('');
        $('#notification_waiting').show();
    });

    // Hide notification
    $('.notify_item .ntf-hide').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: 'delete=1&id=' + $this.data('id'),
            success: function(data) {
                if (data == 'OK') {
                    window.location.href = window.location.href;
                } else {
                    alert(nv_is_change_act_confirm[2]);
                }
            }
        });
    });
});
