/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

// Giá trị này = 0 thì tạm dừng kiểm tra số thông báo
var load_notification = 1;
const cookieName = nv_cookie_prefix + '_antf';

// Hàm lấy số thông báo chưa đọc
function nv_get_notification() {
    if (load_notification) {
        let antf = nv_getCookie(cookieName);
        if (antf) {
            antf = JSON.parse(antf);
            if ((new Date().getTime() - antf.t) < 30000) {
                if (antf.cn > 0) {
                    $('#notification').show().html(antf.cf);
                } else {
                    $('#notification').hide().html(0);
                }
                // Load mỗi 30s một lần
                setTimeout(function() {
                    nv_get_notification();
                }, 30000);
                return;
            }
        }

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: {
                'notification_get': 1
            },
            dataType: 'json',
            success: function(data) {
                if (data.count > 0) {
                    $('#notification').show().html(data.count_formatted);
                } else {
                    $('#notification').hide().html(0);
                }
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.count,
                    'cf': data.count_formatted,
                    't': new Date().getTime()
                }), 365);
                // Load mỗi 30s một lần
                setTimeout(function() {
                    nv_get_notification();
                }, 30000);
            },
            cache: false,
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }
}

$(document).ready(function() {
    var last_id = 0;

    // Lấy và hiển thị số thông báo chưa đọc
    setTimeout(() => {
        nv_get_notification();
    }, 1000);

    // Hàm lấy danh sách thông báo bằng ajax
    function getNotis(firstTime) {
        last_id = 0;

        // Tìm id thông báo cuối cùng
        var lNoti = $('#notification_load ul li:last');
        if (lNoti.length == 1) {
            last_id = lNoti.data('id');
        }

        $('#notification_waiting').show();
        $.ajax({
            type: 'GET',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&last_id=' + last_id + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            cache: false,
            success: function(result) {
                $('#notification_waiting').hide();
                if (firstTime) {
                    $('#notification_load').html(result.html).slimScroll({
                        height: '250px'
                    });
                } else {
                    $('#notification_load').append(result.html);
                }
                $('.notification-info .date').timeago();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#notification_waiting').hide();
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Load thêm thông báo khi cuộn xuống
    $('#notification_load').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            getNotis(0);
        }
    });

    // Ấn nút mở thông báo ra
    $('#notification-area').on('show.bs.dropdown', function() {
        $('#notification_load').html('');
        getNotis(1);
    });

    function deleteNoti(target) {
        var eleBody = target.parent().parent();
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: 'delete=1&id=' + eleBody.data('id') + '&checksess=' + $('#notification-area').data('checksess'),
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(nv_is_del_confirm[2]);
                    return;
                }
                eleBody.remove();
                $('#notification').html(data.data.count_formatted);
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.data.count,
                    'cf': data.data.count_formatted,
                    't': new Date().getTime()
                }), 365);
                if (data.data.count < 1) {
                    $('#notification').hide();
                } else {
                    $('#notification').show();
                }
                if ($('.notify_item').length < 1) {
                    // Nếu xóa hết thông báo rồi thì load lại
                    getNotis(0);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Request Error!!!');
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function toggleNoti(target) {
        var eleBody = target.parent().parent();
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: 'toggle=1&id=' + eleBody.data('id') + '&checksess=' + $('#notification-area').data('checksess'),
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(nv_is_change_act_confirm[2]);
                    return;
                }
                if (data.view) {
                    // Đang là đã đọc > đánh dấu chưa đọc
                    $('.fa-solid', target).removeClass('fa-eye-slash fa-eye').addClass('fa-eye-slash');
                    target.attr('title', target.data('msg-unread'));
                    eleBody.removeClass('notification-unread');
                } else {
                    // Đang là chưa đọc > đánh dấu đã đọc
                    $('.fa-solid', target).removeClass('fa-eye-slash fa-eye').addClass('fa-eye');
                    target.attr('title', target.data('msg-read'));
                    eleBody.addClass('notification-unread');
                }
                $('#notification').html(data.data.count_formatted);
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.data.count,
                    'cf': data.data.count_formatted,
                    't': new Date().getTime()
                }), 365);
                if (data.data.count < 1) {
                    $('#notification').hide();
                } else {
                    $('#notification').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Request Error!!!');
                console.log(jqXHR, textStatus, errorThrown);
            }
        });
    }

    // Chặn đóng dropdown khi ấn bên trong nó và xử các tác vụ liên quan
    $('#notification-area .dropdown-menu').on('click', function(e) {
        e.stopPropagation();
        var target = $(e.target);
        if (target.is('.fa')) {
            target = target.parent();
        }

        // Đánh dấu đã đọc, chưa đọc
        if (target.is('.noti-toggle')) {
            e.preventDefault();
            toggleNoti(target);
            return;
        }

        // Xóa thông báo
        if (target.is('.noti-delete')) {
            e.preventDefault();
            deleteNoti(target);
            return;
        }

        // Đánh dấu đọc tất cả
        if (target.is('[data-toggle="markallnoti"]')) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: 'notification_reset=1&checksess=' + $('#notification-area').data('checksess'),
                success: function() {
                    $('#notification-area>a').trigger('click');
                    $('#notification').hide();
                    nv_setCookie(cookieName, JSON.stringify({
                        'cn': 0,
                        'cf': 0,
                        't': new Date().getTime()
                    }), 365);
                }
            });
            return;
        }

        // Click vào thông báo
        if (!target.is('.body-noti')) {
            target = target.closest('.body-noti');
        }
        if (target.length == 1) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: 'toggle=1&direct_view=1&id=' + target.data('id') + '&checksess=' + $('#notification-area').data('checksess'),
                success: function() {
                    var btn = $('.ntf-toggle', target.parent());
                    $('.fa', btn).removeClass('fa-eye-slash fa-eye').addClass('fa-eye-slash');
                    btn.attr('title', btn.data('msg-unread'));
                    target.removeClass('notification-unread');
                    window.location = target.attr('href');
                }
            });
            return;
        }
    });
});
