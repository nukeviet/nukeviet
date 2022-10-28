/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var pushObj,
    pageUrl,
    pushActionObj;

$(function() {
    pushObj = $('#push');
    pageUrl = pushObj.data('page-url');
    pushActionObj = $('#push-action');

    $('[name=filter]', pushObj).on('change', function() {
        var url = pageUrl,
            filter = $(this).val();
        if ('' != filter) {
            url += '&filter=' + filter
        }
        window.location.href = url
    });

    pushObj.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        var obj = $(this).parents('.item');
        $('.more', obj).hide();
        $('.morecontent', obj).show()
    });

    $('[data-toggle=push_del]', pushObj).on('click', function() {
        var id = $(this).parents('.item').data('id'),
            conf = confirm(pushObj.data('delete-confirm'));
        if (conf) {
            $.ajax({
                type: 'POST',
                cache: !1,
                url: pageUrl,
                data: {
                    'action': 'push_del',
                    'id': id
                },
                dataType: "json",
                success: function(result) {
                    if ('error' == result.status) {
                        alert(result.mess);
                        return !1
                    } else if ('OK' == result.status) {
                        window.location.href = window.location.href
                    }
                }
            })
        }
    });

    $('[data-toggle=push_action]', pushObj).on('click', function() {
        var type = $(this).data('type'),
            id = (type == 'add') ? '0' : $(this).parents('.item').data('id'),
            title = $(this).data('title');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: pageUrl,
            data: {
                'action': 'push_action',
                'id': id
            },
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    alert(result.mess);
                    return !1
                } else if ('OK' == result.status) {
                    $('.panel-heading', pushActionObj).text(title);
                    $('.panel-body', pushActionObj).html(result.content);
                    pushObj.hide();
                    pushActionObj.show()
                }
            }
        })
    });

    pushActionObj.on('click', '[data-toggle=push_action_canceled]', function() {
        pushActionObj.hide();
        pushObj.show()
    });

    $('[data-toggle=viewUser]').each(function(e) {
        var content = $(this).data('id') + '<br/>' + $(this).data('username') + '<br/>' + $(this).data('fullname');
        $(this).popover({
            'trigger': 'focus',
            'placement': 'top',
            'html': true,
            'content': content
        })
    });

    $('.configs input[type=text]', pushObj).on('input', function(e) {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    $('.configs', pushObj).on('submit', function(e) {
        e.preventDefault();
        var that = $(this);
        $('.has-error', that).removeClass('has-error');

        var url = that.attr('action'),
            data = that.serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    $('[name=' + result.input + ']', that).parents('.form-group').addClass('has-error')
                    return !1
                } else if ('OK' == result.status) {
                    window.location.href = window.location.href
                }
            }
        })
    })
});
