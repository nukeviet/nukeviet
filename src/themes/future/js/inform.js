/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

let informObject;

function informSetStatus(id, status, callback) {
    const baseUrl = informObject.data('page-url');
    const url = baseUrl + (baseUrl.includes('?') ? '&' : '?') + 'nocache=' + Date.now();
    $.ajax({
        type: 'POST',
        url: url,
        data: 'setStatus=' + status + '&id=' + id,
        dataType: 'json',
        success: function(result) {
            if ('OK' === result.status) {
                if (typeof callback === 'function') {
                    callback();
                } else {
                    $('[name=filter]', informObject).trigger('change');
                }
            }
        }
    });
}

$(function() {
    informObject = $('#inform');
    if (!informObject.length) return;

    $('[name=filter]', informObject).on('change', function() {
        const url = informObject.data('page-url');
        const filter = $('[name=filter]', informObject).val();
        const query = ('' !== filter && 'all' !== filter ? 'filter=' + filter + '&ajax=' : 'ajax=') + Date.now();
        const fullUrl = url + (url.includes('?') ? '&' : '?') + query;
        $.get(fullUrl, function(res) {
            $('.load_content', informObject).html(res);
        });
    });

    $('[name=filter]', informObject).trigger('change');

    informObject.on('click', '[data-toggle=informNotifySetStatus]', function(e) {
        e.preventDefault();
        const url = $(this).parents('.items').data('url');
        const id = $(this).parents('.item').data('id');
        const status = $(this).data('status');
        informSetStatus(id, status, function() {
            $.get(url, function(res) {
                $('.load_content', informObject).html(res);
            });
        });
    });

    informObject.on('click', '.message a', function(e) {
        const item = $(this).parents('.item');
        const href = $(this).attr('href');
        if (item.is('.viewed-0')) {
            e.preventDefault();
            informSetStatus(item.data('id'), 'viewed', function() {
                if ('' !== href && '#' !== href) {
                    window.location.href = href;
                }
            });
        }
    });

    informObject.on('click', '[data-toggle=more]', function(e) {
        e.preventDefault();
        const obj = $(this).parents('.item');
        $('.more', obj).hide();
        $('.morecontent', obj).show();
    });
});
