/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var pushObject;

function pushSetStatus(id, status, callback) {
    var url = pushObject.data('page-url');
    url += ((-1 < url.indexOf("?")) ? '&' : '?') + 'nocache=' + new Date().getTime();
    $.ajax({
        type: 'POST',
        url: url,
        data: 'setStatus=' + status + '&id=' + id,
        success: function(result) {
            if ('OK' == result.status) {
                if (typeof callback === "function") {
                    callback()
                } else {
                    $('[name=filter]', pushObject).trigger('change')
                }
            }
        }
    })
}

$(function() {
    pushObject = $('#push');

    $('[name=filter]', pushObject).on('change', function() {
        var url = pushObject.data('page-url'),
            filter = $('[name=filter]', pushObject).val(),
            query = (('' != filter && 'all' != filter) ? 'filter=' + filter + '&ajax=' : 'ajax=') + new Date().getTime();
        url += ((-1 < url.indexOf("?")) ? '&' : '?') + query;
        $.get(url, function(res) {
            $('.load_content', pushObject).html(res)
        })
    });

    $('[name=filter]', pushObject).trigger('change');

    pushObject.on('click', '[data-toggle=pushNotifySetStatus]', function(e) {
        e.preventDefault();
        var url = $(this).parents('.items').data('url');
        pushSetStatus($(this).parents('.item').data('id'), $(this).data('status'), function() {
            $.get(url, function(res) {
                $('.load_content', pushObject).html(res)
            })
        })
    });

    pushObject.on('click', '.message a', function(e) {
        var item = $(this).parents('.item'),
            href = $(this).attr('href');
        if (item.is('.viewed-0')) {
            e.preventDefault();
            pushSetStatus(item.data('id'), 'viewed', function() {
                if ('' != href && '#' != href) {
                    window.location.href = href
                }
            })
        }
    });

    pushObject.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        var obj = $(this).parents('.item');
        $('.more', obj).hide();
        $('.morecontent', obj).show()
    });
});
