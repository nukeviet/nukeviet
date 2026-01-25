/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

$(function() {
    var last_id = 0;
    var ps = false;
    var ctn = $('#main-notifications');
    const cookieName = nv_cookie_prefix + '_antf';

    if (ctn.length != 1) {
        return;
    }

    // Lấy số thông báo chưa đọc
    function getNotifications() {
        if (!ctn.data('enable')) {
            return;
        }
        let antf = nv_getCookie(cookieName);
        if (antf) {
            antf = JSON.parse(antf);
            if ((new Date().getTime() - antf.t) < 30000) {
                if (antf.cn > 0) {
                    $('.indicator', ctn).addClass('show');
                    $('.badge', ctn).text(antf.cf).data('count', antf.cn);
                } else {
                    $('.indicator', ctn).removeClass('show');
                    $('.badge', ctn).text('0').data('count', 0);
                }
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
                    $('.indicator', ctn).addClass('show');
                    $('.badge', ctn).text(data.count_formatted).data('count', data.count);
                } else {
                    $('.indicator', ctn).removeClass('show');
                    $('.badge', ctn).text('0').data('count', 0);
                }
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.count,
                    'cf': data.count_formatted,
                    't': new Date().getTime()
                }), 365);
            },
            error: function(xhr, text, err) {
                console.log(xhr, text, err);
                // Dừng khi lỗi
                ctn.data('enable', false);
            }
        });
    }

    // Khởi chạy bộ đếm số thông báo chưa đọc
    setTimeout(() => {
        getNotifications();
    }, 1000);
    setInterval(() => {
        getNotifications();
    }, 30000);

    // Hàm lấy danh sách thông báo bằng ajax
    function getNotis(firstTime) {
        last_id = 0;

        // Tìm id thông báo cuối cùng
        var lNoti = $('.notification:last', ctn);
        if (lNoti.length == 1) {
            last_id = lNoti.data('id');
        }

        $('.loader', ctn).removeClass('d-none');
        $.ajax({
            type: 'GET',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&last_id=' + last_id + '&nocache=' + new Date().getTime(),
            dataType: 'json',
            cache: false,
            success: function(result) {
                $('.loader', ctn).addClass('d-none');
                if (firstTime) {
                    $('.noti-lists-inner', ctn).html(result.html);
                    ps = new PerfectScrollbar($('.noti-lists', ctn)[0], {
                        wheelPropagation: false
                    });
                } else if (result.html != '') {
                    ps.update();
                    $('.noti-lists-inner', ctn).append(result.html);
                }
                $('.date', ctn).timeago();
            },
            error: function(xhr, text, err) {
                $('.loader', ctn).addClass('d-none');
                console.log(xhr, text, err);
                nvToast(text, 'error');
            }
        });
    }

    // Load thêm thông báo khi cuộn xuống cuối
    ($('.noti-lists', ctn)[0]).addEventListener('ps-y-reach-end', function() {
        getNotis(0);
    });

    // Khi ấn nút có hình cái chuông để xem thông báo
    ctn.on('show.bs.dropdown', function() {
        if (ps) {
            ps.destroy();
            ps = false;
        }
        $('.noti-lists-inner', ctn).html('');
        getNotis(1);
    });

    // Đánh dấu tất cả thông báo đã đọc
    $('.markall', ctn).on('click', function(e) {
        e.preventDefault();
        if ($('.loader', ctn).is(':visible')) {
            return;
        }
        $('.loader', ctn).removeClass('d-none');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: {
                notification_reset: 1,
                checksess: $('body').data('checksess')
            },
            success: function(res) {
                $('.loader', ctn).addClass('d-none');
                if (res != 'OK') {
                    nvToast('Wrong checkesss!!', 'error');
                    return;
                }
                $('.badge', ctn).text('0').data('count', 0);
                $('.notification', ctn).removeClass('notification-unread');
                $('.indicator', ctn).removeClass('show');
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': 0,
                    'cf': 0,
                    't': new Date().getTime()
                }), 365);
            }
        });
    });

    // Đánh dấu đã đọc, chưa đọc
    $(ctn).on('click', '.noti-toggle', function(e) {
        e.preventDefault();
        var $this = $(this);
        var icon = $('i', $(this));
        var noti = $(this).parent().parent();
        if (icon.is('.fa-spin-pulse')) {
            return;
        }
        var cIcon = icon.is('.fa-eye-slash') ? 'fa-eye-slash' : 'fa-eye';
        icon.removeClass('fa-eye-slash fa-eye').addClass('fa-spinner fa-spin-pulse');
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: {
                toggle: 1,
                id: noti.data('id'),
                checksess: $('body').data('checksess')
            },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass(cIcon);
                    nvToast(nv_is_change_act_confirm[2], 'error');
                    return;
                }
                if (data.view == 1) {
                    // Đang là đã đọc > đánh dấu chưa đọc
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-eye-slash');
                    $this.attr('title', $this.data('msg-unread'));
                    noti.removeClass('notification-unread');
                } else {
                    // Đang là chưa đọc > đánh dấu đã đọc
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-eye');
                    $this.attr('title', $this.data('msg-read'));
                    noti.addClass('notification-unread');
                }
                $('.badge', ctn).data('count', data.data.count).text(data.data.count_formatted);
                if (data.data.count > 0) {
                    $('.indicator', ctn).addClass('show');
                } else {
                    $('.indicator', ctn).removeClass('show');
                }
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.data.count,
                    'cf': data.data.count_formatted,
                    't': new Date().getTime()
                }), 365);
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass(cIcon);
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    // Xóa thông báo
    $(ctn).on('click', '.noti-delete', function(e) {
        e.preventDefault();
        var icon = $('i', $(this));
        var noti = $(this).parent().parent();
        if (icon.is('.fa-spin-pulse')) {
            return;
        }
        icon.removeClass('fa-trash').addClass('fa-spinner fa-spin-pulse');

        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: {
                delete: 1,
                id: noti.data('id'),
                checksess: $('body').data('checksess')
            },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                    nvToast(nv_is_del_confirm[2], 'error');
                    return;
                }
                noti.remove();
                $('.badge', ctn).data('count', data.data.count).text(data.data.count_formatted);
                if (data.data.count > 0) {
                    $('.indicator', ctn).addClass('show');
                } else {
                    $('.indicator', ctn).removeClass('show');
                }
                nv_setCookie(cookieName, JSON.stringify({
                    'cn': data.data.count,
                    'cf': data.data.count_formatted,
                    't': new Date().getTime()
                }), 365);
                if ($('.notification', ctn).length < 1) {
                    // Nếu xóa hết thông báo rồi thì load lại
                    ps.destroy();
                    ps = false;
                    $('.noti-lists-inner', ctn).html('');
                    getNotis(1);
                }
            },
            error: function(xhr, text, err) {
                icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-trash');
                nvToast(text, 'error');
                console.log(xhr, text, err);
            }
        });
    });

    /*
     * Khi nhấp vô thông báo trên header
     * Đánh dấu thông báo đó là đã đọc rồi chuyển hướng
     * Nếu đã đọc thì không xử lý gì
     */
    $(ctn).on('click', '.noti-item', function(e) {
        var $this = $(this), noti = $this.parent();
        if (noti.is('.notification-unread')) {
            e.preventDefault();
            if ($('.loader', ctn).is(':visible')) {
                return;
            }
            $('.loader', ctn).removeClass('d-none');

            var btn = $('.noti-toggle', noti);
            var icon = $('i', btn);

            $.ajax({
                type: 'POST',
                url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: {
                    toggle: 1,
                    direct_view: 1,
                    id: noti.data('id'),
                    checksess: $('body').data('checksess')
                },
                dataType: 'json',
                success: function(data) {
                    $('.loader', ctn).addClass('d-none');
                    if (data.error) {
                        nvToast(nv_is_change_act_confirm[2], 'error');
                        return;
                    }
                    if (data.view == 1) {
                        // Đang là đã đọc > đánh dấu chưa đọc
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-eye-slash');
                        btn.attr('title', btn.data('msg-unread'));
                        noti.removeClass('notification-unread');
                    } else {
                        // Đang là chưa đọc > đánh dấu đã đọc
                        icon.removeClass('fa-spinner fa-spin-pulse').addClass('fa-eye');
                        btn.attr('title', btn.data('msg-read'));
                        noti.addClass('notification-unread');
                    }
                    $('.badge', ctn).data('count', data.data.count).text(data.data.count_formatted);
                    if (data.data.count > 0) {
                        $('.indicator', ctn).addClass('show');
                    } else {
                        $('.indicator', ctn).removeClass('show');
                    }
                    nv_setCookie(cookieName, JSON.stringify({
                        'cn': data.data.count,
                        'cf': data.data.count_formatted,
                        't': new Date().getTime()
                    }), 365);
                    // Chuyển hướng nếu link hợp lệ
                    const href = $this.attr('href');
                    if (href && href.trim() !== '' && href !== '#' && !href.startsWith('javascript:')) {
                        window.location = href;
                    }
                },
                error: function(xhr, text, err) {
                    $('.loader', ctn).addClass('d-none');
                    nvToast(text, 'error');
                    console.log(xhr, text, err);
                }
            });
        }
    });
});
