/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var informObj,
    pageUrl,
    informActionObj;

$(function() {
    informObj = $('#inform');
    pageUrl = informObj.data('page-url');
    informActionObj = $('#inform-action');

    $('[name=filter]', informObj).on('change', function() {
        var url = pageUrl,
            filter = $(this).val();
        if ('' != filter) {
            url += (url.includes('?') ? '&' : '?') + 'filter=' + filter
        }
        window.location.href = url
    });

    informObj.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        var obj = $(this).parents('.item');
        $('.more', obj).hide();
        $('.morecontent', obj).show()
    });

    $('[data-toggle=inform_del]', informObj).on('click', function() {
        var id = $(this).parents('.item').data('id'),
            conf = confirm(informObj.data('delete-confirm'));
        if (conf) {
            $.ajax({
                type: 'POST',
                cache: !1,
                url: pageUrl,
                data: {
                    'action': 'inform_del',
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

    $('[data-toggle=inform_action]', informObj).on('click', function() {
        var type = $(this).data('type'),
            id = (type == 'add') ? '0' : $(this).parents('.item').data('id'),
            title = $(this).data('title');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: pageUrl,
            data: {
                'action': 'inform_action',
                'id': id
            },
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    alert(result.mess);
                    return !1
                } else if ('OK' == result.status) {
                    $('.panel-heading', informActionObj).text(title);
                    $('.panel-body', informActionObj).html(result.content);
                    informObj.hide();
                    informActionObj.show()
                }
            }
        })
    });

    informActionObj.on('click', '[data-toggle=inform_action_canceled]', function() {
        informActionObj.hide();
        informObj.show()
    });

    $('[data-toggle=viewUser]').each(function(e) {
        var content = $(this).data('id') + '<br/>' + $(this).data('username') + '<br/>' + $(this).data('fullname');
        $(this).popover({
            'trigger': 'focus',
            'placement': 'top',
            'html': true,
            'content': content,
            sanitize: false
        })
    });

    $('.configs input[type=text]', informObj).on('input', function(e) {
        $(this).val($(this).val().replace(/[^0-9]/gi, ''))
    });

    $('.configs', informObj).on('submit', function(e) {
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
