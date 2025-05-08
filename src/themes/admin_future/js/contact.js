/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function department_view(url) {
    $.ajax({
        type: "GET",
        url: url,
        cache: !1,
        dataType: "json"
    }).done(function(a) {
        modalShow(a.title, a.content)
    })
}

$(function() {
    $('.view_feedback').on('click', function() {
        window.location.href = $(this).parents('.item').data('url')
    });

    $('.department-view').on('click', function(e) {
        e.preventDefault();
        department_view($(this).data('url'))
    });

    // Gởi phản hồi/Chuyển tiếp
    $('#feedback-reply form, #feedback-forward form').on('submit', function(e) {
        e.preventDefault();
        var url = $('.page').data('url'),
            data = $(this).serialize(),
            icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        };
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data
        }).done(function(a) {
            if (a.status == 'error') {
                icon.attr("class", originalClass);
                nvToast(a.mess, 'error')
            } else if (a.status == 'ok') {
                nvConfirm(a.mess, function() {;
                    window.location.reload()
                }, function() {;
                    window.location.reload()
                }, false);
            } else {
                icon.attr("class", originalClass);
                nvToast(nv_is_del_confirm[2], 'error')
            }
        })
    });

    // Đánh dấu nhiều liên hệ
    $('.feedback_mark').on('click', function() {
        var form = $('#feedback_list'),
            mark = $(this).data('mark'),
            icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        if ($('[name^=sends]:checked', form).length) {
            icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
            var listsend = [];
                $('[name^=sends]:checked', form).each(function() {
                    listsend.push($(this).val())
                }
            );
            var checkss = $('[name=checkss]', form).val();
            $.ajax({
                type: "POST",
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&nocache=" + new Date().getTime(),
                data: {
                    "mark": mark,
                    "checkss": checkss,
                    "sends": listsend
                }
            }).done(function(res) {
                if (res.status === 'ok') {
                    location.reload();
                } else if (res.status === 'error') {
                    icon.attr("class", originalClass);
                    nvToast(res.mess, 'error');
                } else {
                    icon.attr("class", originalClass);
                    nvToast(nv_is_del_confirm[2], 'error');
                }
            })
        } else {
            nvAlert(nv_please_check);
        }
    });

    // Đánh dấu 1 liên hệ từ trang chi tiết
    $('.feedback_mark_single').on('click', function() {
        var page = $('.page'),
            url = page.data('url'),
            checkss = page.data('checkss'),
            mark = $(this).data('mark'),
            icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        };
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'mark': mark,
                'send': page.data('id'),
                'checkss': checkss
            }
        }).done(function(a) {
            if (a.status == 'error') {
                icon.attr("class", originalClass);
                nvToast(a.mess, 'error')
            } else if (a.status == 'ok') {
                if (mark == 'unread') {
                    window.location.href = url
                } else {
                    window.location.reload()
                }
            } else {
                icon.attr("class", originalClass);
                nvToast(nv_is_del_confirm[2], 'error')
            }
        })
    });
    // Xoá 1 liên hệ từ trang chi tiết
    $('.feedback_del').on('click', function() {
        var page = $('.page'),
            icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        nvConfirm(nv_is_del_confirm[0], function() {
            $.ajax({
                type: "POST",
                url: page.data('url'),
                cache: false,
                data: {
                    id: page.data('id'),
                    delete: 1,
                    checkss: page.data('checkss')
                },
            }).done(function(a) {
                if (a.status == 'error') {
                    nvToast(a.mess)
                    icon.attr("class", originalClass);
                } else if (a.status == 'ok') {
                    window.location.href = page.data('url');
                } else {
                    nvToast(nv_is_del_confirm[2])
                    icon.attr("class", originalClass);
                }
            });
        }, function() {
            icon.attr("class", originalClass);
        });
    });
    // Xoá nhiều liên hệ
    $('.feedback_del_sel').on('click', function() {
        var form = $('#feedback_list');
        if ($('[name^=sends]:checked', form).length) {
            var listsend = [];
                $('[name^=sends]:checked', form).each(function() {
                    listsend.push($(this).val())
                }
            );
            var checkss = $('[name=checkss]', form).val(),
                icon = $('i', $(this));
            if (icon.is('.fa-spinner')) {
                return;
            }
            var originalClass = icon.attr("class");
            icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
            nvConfirm(nv_is_del_confirm[0], function() {
                $.ajax({
                    type: "POST",
                    url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&nocache=" + new Date().getTime(),
                    data: {
                        "delete": 2,
                        "checkss": checkss,
                        "sends": listsend
                    }
                }).done(function(res) {
                    if (res.status === 'ok') {
                        location.reload();
                    } else if (res.status === 'error') {
                        icon.attr("class", originalClass);
                        nvToast(res.mess, 'error');
                    } else {
                        icon.attr("class", originalClass);
                        nvToast(nv_is_del_confirm[2], 'error');
                    }
                })
            }, function() {
                icon.attr("class", originalClass);
            });
        } else {
            nvAlert(nv_please_check);
        }
    });
    // Xoá tất cả liên hệ
    $('.feedback_del_all').on('click', function() {
        var form = $('#feedback_list')
        var checkss = $('[name=checkss]', form).val(),
            icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        nvConfirm(nv_is_del_confirm[0], function() {
            $.ajax({
                type: "POST",
                url: script_name + "?" + nv_lang_variable + "=" + nv_lang_data + "&" + nv_name_variable + "=" + nv_module_name + "&nocache=" + new Date().getTime(),
                data: {
                    "delete": 3,
                    "checkss": checkss
                }
            }).done(function(res) {
                if (res.status === 'ok') {
                    location.reload();
                } else if (res.status === 'error') {
                    icon.attr("class", originalClass);
                    nvToast(res.mess, 'error');
                } else {
                    icon.attr("class", originalClass);
                    nvToast(nv_is_del_confirm[2], 'error');
                }
            })
        }, function() {
            icon.attr("class", originalClass);
        });
    });
});
