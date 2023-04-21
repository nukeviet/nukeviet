var pushCheck,
    pushObj,
    pushModuleUrl,
    checkPushUrl,
    refresh_time = 30000,
    userid = 0,
    usergroups = '',
    csrf = '',
    push_cookie_name = nv_cookie_prefix + '_pushtime',
    lastCheckPush = 0;

function pushCheck_setTimeout(tm) {
    clearTimeout(pushCheck);
    pushCheck = setTimeout(function() {
        pushNotifyGetCount();
    }, tm);
}

function pushNotifyGetCount() {
    var current = new Date().getTime(),
        pas = current - lastCheckPush;
    if (pas > refresh_time) {
        nv_setCookie(push_cookie_name, current, 365);
        lastCheckPush = current;
        var url = checkPushUrl + ((-1 < checkPushUrl.indexOf("?")) ? '&' : '?') + 'nocache=' + current;
        $.ajax({
            type: 'POST',
            url: url,
            data: '__checkPush=1&__userid=' + userid + '&__groups=' + usergroups + '&_csrf=' + csrf,
            dataType: "json",
            success: function(data) {
                $('.new-count', pushObj).text(data.count);
                if (data.count > 0) {
                    $('.new-count', pushObj).show()
                } else {
                    $('.new-count', pushObj).text(data.count).hide();
                }
            }
        });
        pushCheck_setTimeout(refresh_time)
    } else {
        pushCheck_setTimeout(refresh_time - pas)
    }
}

function pushNotifyGetList() {
    var filter = $('input[name=aj_filter]', pushObj).val(),
        query = ('' != filter && 'all' != filter) ? 'filter=' + filter + '&nocache=' : 'nocache=',
        url = pushModuleUrl + ((-1 < pushModuleUrl.indexOf("?")) ? '&' : '?') + query + new Date().getTime();
    $.ajax({
        type: 'GET',
        url: url,
        dataType: "json",
        success: function(result) {
            $('.push-content', pushObj).html(result.content);
            if (result.count) {
                pushNotifyGetCount()
            }
        }
    })
}

function pushNotifySetStatus(id, status, callback) {
    var url = pushModuleUrl + ((-1 < pushModuleUrl.indexOf("?")) ? '&' : '?') + 'nocache=' + new Date().getTime();
    $.ajax({
        type: 'POST',
        url: url,
        data: 'setStatus=' + status + '&id=' + id,
        dataType: "json",
        success: function(result) {
            if ('OK' == result.status) {
                pushNotifyGetList();
                if (typeof callback === "function") {
                    callback()
                }
            }
        }
    })
}

$(window).on('load', function() {
    if ($('#push-notification').length) {
        pushObj = $('#push-notification');
        refresh_time = parseInt(pushObj.data('refresh-time')) * 1000;
        pushModuleUrl = pushObj.data('url');
        checkPushUrl = pushObj.data('checkpush-url');
        userid = pushObj.data('userid');
        usergroups = pushObj.data('usergroups');
        csrf = pushObj.data('csrf');

        if (typeof PerfectScrollbar != "undefined") {
            var ps = new PerfectScrollbar('#push-notification .push-content', {
                wheelSpeed: 1,
                wheelPropagation: true,
                minScrollbarLength: 20
            })
        }

        pushObj.on({
            "click": function(event) {
                if ($(event.target).closest('.dropdown-toggle, .btn-close').length) {
                    $(this).data('closable', true);
                } else {
                    $(this).data('closable', false);
                }
            },
            "hide.bs.dropdown": function(event) {
                hide = $(this).data('closable');
                $(this).data('closable', true);
                return hide;
            },
            "show.bs.dropdown": function() {
                pushNotifyGetList()
            }
        });

        $('[data-toggle=pushNotifyRefresh]', pushObj).on('click', function() {
            pushNotifyGetList()
        });

        $('[data-toggle=changeFilter]', pushObj).on('click', function(e) {
            e.preventDefault();
            $('[name=aj_filter]', pushObj).val($(this).data('filter'));
            if (!$(this).is('.active')) {
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                pushNotifyGetList()
            }
        })

        pushObj.on('click', '[data-toggle=pushNotifySetStatus]', function(e) {
            e.preventDefault();
            pushNotifySetStatus($(this).parents('.item').data('id'), $(this).data('status'))
        });

        pushObj.on('click', '.message a', function(e) {
            var item = $(this).parents('.item'),
                href = $(this).attr('href');
            if (item.is('.viewed-0')) {
                e.preventDefault();
                pushNotifySetStatus(item.data('id'), 'viewed', function() {
                    if ('' != href && '#' != href) {
                        window.location.href = href
                    }
                })
            }
        });

        pushObj.on('click', '[data-toggle=more]', function(e) {
            e.preventDefault();
            var obj = $(this).parents('.item');
            $('.more', obj).hide();
            $('.morecontent', obj).show()
        });

        lastCheckPush = nv_getCookie(push_cookie_name);
        lastCheckPush = lastCheckPush ? parseInt(lastCheckPush) : 0;
        pushNotifyGetCount()
    }
});
