/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

function modal_content(url, id) {
    var md = $('#content');
    $.ajax({
        type: "POST",
        url: url,
        cache: !1,
        data: {
            'fc': 'content',
            'id': id
        },
        dataType: "json"
    }).done(function(a) {
        if (a.status == 'error') {
            alert(a.mess)
        } else if (a.status == 'OK') {
            $('.modal-title', md).text(a.title);
            $('.modal-body', md).html(a.content);
            md.modal('show')
        }
    });
}

function department_change_alias(form) {
    $.ajax({
        type: "POST",
        url: form.attr('action'),
        cache: !1,
        data: {
            'fc': 'alias',
            'id': $('[name=id]', form).val(),
            'title': rawurldecode(trim($('[name=full_name]', form).val()))
        }
    }).done(function(a) {
        $('[name=alias]', form).val(a)
    })
}

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

    $('.view_user').on('click', function(e) {
        e.preventDefault();
        $('#view-user').modal('show')
    });

    $('.department_view').on('click', function(e) {
        e.preventDefault();
        department_view($(this).parents('.list').data('url') + '&id=' + $(this).parents('.item').data('id'))
    });
    $('.department-view').on('click', function(e) {
        e.preventDefault();
        department_view($(this).data('url'))
    });

    $('.feedback-reply').on('click', function() {
        $('#feedback-reply').modal('show')
    });

    $('.feedback-forward').on('click', function() {
        $('#feedback-forward').modal('show')
    });

    $('#feedback-reply form, #feedback-forward form').on('submit', function(e) {
        e.preventDefault();
        if (typeof CKEDITOR != "undefined") {
            for (var instanceName in CKEDITOR.instances) {
                $('#' + instanceName).val(CKEDITOR.instances[instanceName].getData());
            }
        }
        var url = $(this).parents('.page').data('url'),
            data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data,
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                alert(a.mess)
            } else if (a.status == 'OK') {
                alert(a.mess);
                window.location.reload()
            }
        })
    });

    $('.feedback_mark').on('click', function() {
        var form = $(this).parents('form'),
            mark = $(this).data('mark');
        if ($('[name^=sends]:checked', form).length) {
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                cache: !1,
                data: form.serialize() + '&mark=' + mark
            }).done(function(a) {
                window.location.reload()
            })
        } else {
            alert(form.data('error'))
        }
    });

    $('.feedback_mark_single').on('click', function() {
        var page = $(this).parents('.page'),
            url = page.data('url'),
            mark = $(this).data('mark');
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'mark': mark,
                'send': page.data('id')
            }
        }).done(function(a) {
            if (mark == 'unread') {
                window.location.href = url
            } else {
                window.location.reload()
            }
        })
    });

    $('.feedback_del').on('click', function() {
        var page = $(this).parents('.page');
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: "POST",
                url: page.data('url'),
                cache: !1,
                data: 'id=' + page.data('id') + '&delete=1'
            }).done(function(a) {
                window.location.href = page.data('url')
            })
        }
    });

    $('.feedback_del_sel').on('click', function() {
        var form = $(this).parents('form');
        if ($('[name^=sends]:checked', form).length) {
            if (confirm(nv_is_del_confirm[0])) {
                $.ajax({
                    type: "POST",
                    url: form.attr('action'),
                    cache: !1,
                    data: form.serialize() + '&delete=2'
                }).done(function(a) {
                    window.location.reload()
                })
            }
        } else {
            alert(form.data('error'))
        }
    });

    $('.feedback_del_all').on('click', function() {
        var form = $(this).parents('form');
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                cache: !1,
                data: 'delete=3'
            }).done(function(a) {
                window.location.reload()
            })
        }
    });

    $('body').on('submit', '.department_content, .supporter_content', function(e) {
        e.preventDefault();
        if (typeof CKEDITOR != "undefined") {
            for (var instanceName in CKEDITOR.instances) {
                $('#' + instanceName).val(CKEDITOR.instances[instanceName].getData());
            }
        }
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize();
        $('input, button, textarea, select', that).prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data,
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                alert(a.mess);
                $('input, button, textarea, select', that).prop('disabled', false)
            } else if (a.status == 'OK') {
                if (a.mess) {
                    alert(a.mess)
                }
                window.location.reload()
            }
        })
    });

    $('.send-form').on('submit', function(e) {
        e.preventDefault();
        if (typeof CKEDITOR != "undefined") {
            for (var instanceName in CKEDITOR.instances) {
                $('#' + instanceName).val(CKEDITOR.instances[instanceName].getData());
            }
        }
        var that = $(this),
            url = that.attr('action'),
            data = that.serialize();
        $('input, button, textarea', that).prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data,
            dataType: "json"
        }).done(function(a) {
            $('input, button, textarea', that).prop('disabled', false);
            if (a.status == 'error') {
                alert(a.mess)
            } else if (a.status == 'OK') {
                var conf = confirm(a.mess);
                if (conf) {
                    window.location.reload();
                    return !1
                }
            }
        })
    });

    $('.department_add, .supporter_add').on('click', function() {
        modal_content($(this).data('url'), 0)
    });

    $('.department_edit, .supporter_edit').on('click', function() {
        modal_content($(this).parents('.list').data('url'), $(this).parents('.item').data('id'))
    });

    $('body').on('change', '.department_content [name=full_name]', function() {
        var txt = trim($(this).val()),
            form = $(this).parents('form'),
            alias = trim($('[name=alias]', form).val());
        if (!txt.length || alias.length) {
            return !1
        }
        department_change_alias(form)
    });

    $('body').on('click', '.department_alias', function() {
        department_change_alias($(this).parents('form'))
    })

    $('.department_del').on('click', function() {
        if (confirm(nv_is_del_confirm[0])) {
            var that = $(this),
                id = that.parents('.item').data('id'),
                url = that.parents('.list').data('url');
            $.ajax({
                type: "POST",
                url: url,
                cache: !1,
                data: {
                    'fc': 'delete',
                    'id': id
                },
                dataType: "json"
            }).done(function(a) {
                if (a.status == 'error') {
                    alert(a.mess)
                } else if (a.status == 'OK') {
                    window.location.reload()
                }
            })
        }
    });

    $('.department_cstatus').on('change', function() {
        var that = $(this),
            id = that.parents('.item').data('id'),
            nstatus = that.val(),
            url = that.parents('.list').data('url');
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'change_status',
                'id': id,
                'ns': nstatus
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                that.val(that.data('default'));
                alert(a.mess)
            } else if (a.status == 'OK') {
                setTimeout(() => {
                    that.prop('disabled', false);
                }, 5000)
            }
        })
    });

    $('[name=is_default]').on('change', function() {
        var that = $(this).parents('.list'),
            item = $(this).parents('.item'),
            id = $(this).val(),
            url = that.data('url');
        $('.is-default', that).removeClass('is-default');
        $('.full_name', item).addClass('is-default');
        $('[name=is_default]', that).prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'set_default',
                'id': id
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                alert(a.mess)
            } else if (a.status == 'OK') {
                setTimeout(() => {
                    $('[name=is_default]', that).prop('disabled', false)
                }, 5000)
            }
        })
    });

    $('.department_cweight, .supporter_cweight').on('change', function() {
        var that = $(this),
            id = that.parents('.item').data('id'),
            nweight = that.val(),
            url = that.parents('.list').data('url');
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: {
                'fc': 'change_weight',
                'id': id,
                'nw': nweight
            },
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                that.val(that.data('default'));
                alert(a.mess)
            } else if (a.status == 'OK') {
                window.location.reload()
            }
        })
    });

    $('.supporter_del').on('click', function() {
        if (confirm(nv_is_del_confirm[0])) {
            $.ajax({
                type: "POST",
                url: $(this).parents('.list').data('url'),
                cache: !1,
                data: {
                    'fc': 'delete',
                    'id': $(this).parents('.item').data('id')
                },
                dataType: "json"
            }).done(function(a) {
                if (a.status == 'error') {
                    alert(a.mess)
                } else if (a.status == 'OK') {
                    window.location.reload()
                }
            })
        }
    });

    $('.supporter_act').on('change', function() {
        var that = $(this),
            is_checked = that.is(':checked'),
            url = that.parents('.list').data('url'),
            data = {
                'fc': 'change_act',
                'id': that.parents('.item').data('id')
            };
        that.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: url,
            cache: !1,
            data: data,
            dataType: "json"
        }).done(function(a) {
            if (a.status == 'error') {
                that.prop('disabled', false);
                that.prop('checked', is_checked ? false : true);
                alert(a.mess)
            } else if (a.status == 'OK') {
                setTimeout(() => {
                    that.prop('disabled', false);
                }, 5000);
            }
        })
    })

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
});
