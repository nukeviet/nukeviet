var notificationObj;

$(function() {
    notificationObj = $('#notifications_manager');

    notificationObj.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        var obj = $(this).parents('.notification-item');
        $('.more', obj).hide();
        $('.morecontent', obj).show()
    });

    notificationObj.on('click', '[data-toggle=inform_action]', function(e) {
        var type = $(this).data('type'),
            title = $(this).data('title'),
            id = type == 'edit' ? $(this).parents('.notification-item').data('id') : '0',
            url = notificationObj.data('url');
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: {
                'action': id
            },
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    alert(result.mess);
                    return !1
                } else if ('OK' == result.status) {
                    $('#notification-action .panel-heading .action-title', notificationObj).text(title);
                    $('#notification-action .panel-body', notificationObj).html(result.content);
                    $('#generate_page', notificationObj).fadeOut(200, function() {
                        $('#notification-action', notificationObj).show()
                    });
                }
            }
        })
    });

    notificationObj.on('click', '[data-toggle=notification_action_cancel]', function() {
        $('#notification-action', notificationObj).hide();
        $('#generate_page', notificationObj).show()
    });

    notificationObj.on('submit', '#notification_action_form', function(e) {
        e.preventDefault();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        var url = $(this).attr('action'),
            data = $(this).serialize();
        $.ajax({
            type: 'POST',
            cache: !1,
            url: url,
            data: data,
            dataType: "json",
            success: function(result) {
                if ('error' == result.status) {
                    alert(result.mess);
                    return !1
                } else if ('OK' == result.status) {
                    $('.change-status', notificationObj).trigger('change')
                }
            }
        })
    });

    $('.change-status', notificationObj).on('change', function() {
        var url = notificationObj.data('url'),
            val = $(this).val(),
            data = 'ajax=1' + ('' != val ? '&filter=' + val : '');
        $.ajax({
            type: 'GET',
            url: url,
            data: data,
            success: function(result) {
                $('#notification-action', notificationObj).hide();
                $('#generate_page', notificationObj).html(result).show()
            }
        })
    });

    notificationObj.on('click', '[data-toggle=inform_del]', function(e) {
        e.preventDefault();
        var conf = confirm($(this).parents('.notification').data('delete-confirm'));
        if (conf) {
            var id = $(this).parents('.notification-item').data('id'),
                url = notificationObj.data('url'),
                csrf = notificationObj.data('csrf');
            $.ajax({
                type: 'POST',
                cache: !1,
                url: url,
                data: {
                    'delete': id,
                    '_csrf': csrf
                },
                dataType: "json",
                success: function(result) {
                    if ('error' == result.status) {
                        alert(result.mess);
                        return !1
                    } else if ('OK' == result.status) {
                        $('.change-status', notificationObj).trigger('change')
                    }
                }
            })
        }
    })
})
