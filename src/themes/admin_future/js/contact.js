/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function modal_content(url, id, checkss, i = undefined, icon_class = '', replace_icon = false) {
    var md = $('#content');
    $.ajax({
        type: "POST",
        url: url,
        cache: !1,
        data: {
            'fc': 'content',
            'id': id,
            'checkss': checkss
        },
        dataType: "json"
    }).done(function(a) {
        if (a.status == 'error') {
            nukeviet.alert(a.mess)
        } else if (a.status == 'OK') {
            $('.modal-title', md).text(a.title);
            $('.modal-body', md).html(a.content);
            md.modal('show')
        }
        if (i !== undefined) {
            if (replace_icon) {
                i.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(icon_class);
            } else {
                i.remove();
            }
        }
    });
}

function department_view(url, icon, id = undefined) {
    $.ajax({
        type: "GET",
        url: url,
        cache: !1,
        data: {
            id: id
        },
        dataType: "json"
    }).done(function(a) {
        if (a.status == 'error') {
            nukeviet.alert(a.mess, 'error');
        } else if (a.status == 'OK') {
            modalShow(a.title, a.content)
        }
        icon.remove();
    })
}

$(function() {
    $('.view_feedback').on('click', function() {
        window.location.href = $(this).parents('.item').data('url')
    });

    $('.department-view').on('click', function(e) {
        if ($('i.fa-spinner', $(this)).length) {
            return;
        }
        $(this).append('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        var icon = $('i', $(this));
        e.preventDefault();
        department_view($(this).data('url'), icon)
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
                nukeviet.confirm(a.mess, function() {;
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
            nukeviet.alert(nv_please_check);
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
        nukeviet.confirm(nv_is_del_confirm[0], function() {
            icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
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
            nukeviet.confirm(nv_is_del_confirm[0], function() {
                icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
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
        nukeviet.confirm(nv_is_del_confirm[0], function() {
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

    $('.department_add').on('click', function() {
        if ($('i.fa-spinner', $(this)).length) {
            return;
        }
        $(this).prepend('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        var icon = $('i', $(this));
        modal_content($(this).data('url'), 0, $('.list').data('checkss'), icon)
    });

    if ($('.department_add.auto').length) {
        $('.department_add.auto').trigger('click');
    }

    $('.department_edit').on('click', function() {
        var icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        modal_content($(this).parents('.list').data('url'), $(this).parents('.item').data('id'), $(this).parents('.list').data('checkss'), icon, originalClass, true)
    });

    $('.department_view').on('click', function(e) {
        e.preventDefault();
        if ($('i.fa-spinner', $(this)).length) {
            return;
        }
        $(this).append('<i class="fa-solid fa-spinner fa-spin-pulse"></i>');
        var icon = $('i', $(this));
        department_view($(this).parents('.list').data('url'), icon, $(this).parents('.item').data('id'))
    });

    $('body').on('click', '.help-show', function() {
        var field = $(this).parents('.field'),
            help_bl = $('.help-block', field);
        if (help_bl.is(':visible')) {
            help_bl.slideUp()
        } else {
            help_bl.slideDown()
        }
    });

    $('body').on('click', '.str_add', function() {
        var strs = $(this).parents('.strs'),
            lg = $('.str', strs).length;
        if (lg < 10) {
            var str = $(this).parents('.str'),
                new_str = str.clone();
            $('input[type=text]', new_str).val('');
            str.after(new_str)
        }
    });

    $('body').on('click', '.str_del', function() {
        var strs = $(this).parents('.strs'),
            str = $(this).parents('.str'),
            lg = $('.str', strs).length;
        if (lg > 1) {
            str.remove()
        } else {
            $('input[type=text]', str).val('');
        }
    })

    // Đặt bộ phận làm mặc định
    $('[name=is_default]').on('change', function() {
        var that = $(this).parents('.list'),
            item = $(this).parents('.item'),
            id = $(this).val(),
            url = that.data('url'),
            checkss = that.data('checkss');
        $('.is-default', that).removeClass('is-default');
        $('.full_name', item).addClass('is-default');
        $('[name=is_default]', that).prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'set_default',
                'id': id,
                'checkss': checkss
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                nukeviet.toast(a.mess, 'error');
            } else if (a.status == 'OK') {
                setTimeout(() => {
                    $('[name=is_default]', that).prop('disabled', false)
                }, 1000)
                nukeviet.toast(a.mess, 'success');
            }
        })
    });

    // Xóa bộ phận
    $('.department_del').on('click', function() {
        var that = $(this),
            icon = $('i', that);
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        nukeviet.confirm(nv_is_del_confirm[0], function() {
            var id = that.parents('.item').data('id'),
                url = that.parents('.list').data('url');
            icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
            $.ajax({
                type: "POST",
                url: url,
                cache: !1,
                data: {
                    'fc': 'delete',
                    'id': id,
                    'checkss': that.parents('.list').data('checkss')
                },
                dataType: "json"
            }).done(function(a) {
                if (a.status == 'error') {
                    nukeviet.alert(a.mess)
                    icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(originalClass);
                } else if (a.status == 'OK') {
                    window.location.reload()
                }
            })
        }, function() {
            icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(originalClass);
        })
    });

    // Lấy alias bộ phận
    function department_change_alias(form, icon, originalClass) {
        $.ajax({
            type: "POST",
            url: form.attr('action'),
            cache: !1,
            data: {
                'fc': 'alias',
                'id': $('[name=id]', form).val(),
                'title': rawurldecode(trim($('[name=full_name]', form).val())),
                'checkss': $('[name=checkss]', form).val()
            }
        }).done(function(a) {
            if (a.status == 'error') {
                nvAlert(a.mess);
            } else if (a.status == 'OK') {
                $('[name=alias]', form).val(a.alias)
            }
            icon.removeClass('fa-solid fa-spinner fa-spin-pulse').addClass(originalClass);
        })
    }

    $('body').on('change', '.department_content [name=full_name]', function() {
        var txt = trim($(this).val()),
            form = $(this).parents('form'),
            alias = trim($('[name=alias]', form).val()),
            icon = $('i', $('.department_alias')),
            originalClass = icon.attr("class");

        if (!txt.length || alias.length) {
            return !1
        }
        if (icon.is('.fa-spinner')) {
            return;
        }
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        department_change_alias(form, icon, originalClass)
    });

    $('body').on('click', '.department_alias', function() {
        var icon = $('i', $(this));
        if (icon.is('.fa-spinner')) {
            return;
        }
        var originalClass = icon.attr("class");
        icon.attr("class", "fa-solid fa-spinner fa-spin-pulse");
        department_change_alias($(this).parents('form'), icon, originalClass)
    })

    // Chuyển trạng thái bộ phận
    $('.department_cstatus').on('change', function() {
        var that = $(this),
            id = that.parents('.item').data('id'),
            nstatus = that.val(),
            url = that.parents('.list').data('url'),
            checkss = that.parents('.list').data('checkss');
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'change_status',
                'id': id,
                'ns': nstatus,
                'checkss': checkss
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                that.val(that.data('default'));
                nukeviet.toast(a.mess, 'error');
            } else if (a.status == 'OK') {
                setTimeout(() => {
                    that.prop('disabled', false);
                }, 2000)
                nukeviet.toast(a.mess, 'success');
            }
        })
    });

    // Cập nhật thứ tự bộ phận
    $('.department_cweight').on('change', function() {
        var that = $(this),
            id = that.parents('.item').data('id'),
            nweight = that.val(),
            url = that.parents('.list').data('url'),
            checkss = that.parents('.list').data('checkss');
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'change_weight',
                'id': id,
                'nw': nweight,
                'checkss': checkss
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                that.val(that.data('default'));
                nukeviet.toast(a.mess, 'error');
                setTimeout(() => {
                    that.prop('disabled', false);
                }
                , 2000);
            } else if (a.status == 'OK') {
                window.location.reload()
            }
        })
    });
});
