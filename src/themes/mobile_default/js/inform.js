/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

var informObject;

function informSetStatus(id, status, callback) {
    var url = informObject.data('page-url');
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
                    $('[name=filter]', informObject).trigger('change')
                }
            }
        }
    })
}

$(function() {
    informObject = $('#inform');

    $('[name=filter]', informObject).on('change', function() {
        var url = informObject.data('page-url'),
            filter = $('[name=filter]', informObject).val(),
            query = (('' != filter && 'all' != filter) ? 'filter=' + filter + '&ajax=' : 'ajax=') + new Date().getTime();
        url += ((-1 < url.indexOf("?")) ? '&' : '?') + query;
        $.get(url, function(res) {
            $('.load_content', informObject).html(res)
        })
    });

    $('[name=filter]', informObject).trigger('change');

    informObject.on('click', '[data-toggle=informNotifySetStatus]', function(e) {
        e.preventDefault();
        var url = $(this).parents('.items').data('url');
        informSetStatus($(this).parents('.item').data('id'), $(this).data('status'), function() {
            $.get(url, function(res) {
                $('.load_content', informObject).html(res)
            })
        })
    });

    informObject.on('click', '.message a', function(e) {
        var item = $(this).parents('.item'),
            href = $(this).attr('href');
        if (item.is('.viewed-0')) {
            e.preventDefault();
            informSetStatus(item.data('id'), 'viewed', function() {
                if ('' != href && '#' != href) {
                    window.location.href = href
                }
            })
        }
    });

    informObject.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        var obj = $(this).parents('.item');
        $('.more', obj).hide();
        $('.morecontent', obj).show()
    });
});
