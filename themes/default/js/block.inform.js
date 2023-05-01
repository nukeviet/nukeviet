var informCheck,
    informObj,
    informModuleUrl,
    checkInformUrl,
    refresh_time = 30000,
    userid = 0,
    usergroups = '',
    csrf = '',
    inform_cookie_name = nv_cookie_prefix + '_inft',
    count_cookie_name = nv_cookie_prefix + '_infc',
    lastCount = 0,
    lastCheckInform = 0;

function informCheck_setTimeout(tm) {
    clearTimeout(informCheck);
    informCheck = setTimeout(function() {
        informNotifyGetCount();
    }, tm);
}

function informNotifyGetCount() {
    var current = new Date().getTime(),
        pas = current - lastCheckInform;
    if (pas > refresh_time) {
        lastCheckInform = current;
        nv_setCookie(inform_cookie_name, lastCheckInform, 365);
        var url = checkInformUrl + ((-1 < checkInformUrl.indexOf("?")) ? '&' : '?') + 'nocache=' + current;
        $.ajax({
            type: 'POST',
            url: url,
            data: '__checkInform=1&__userid=' + userid + '&__groups=' + usergroups + '&_csrf=' + csrf,
            dataType: "json",
            success: function(data) {
                lastCount = parseInt(data.count);
                nv_setCookie(count_cookie_name, lastCount, 365);
                $('.new-count', informObj).text(lastCount);
                if (lastCount > 0) {
                    $('.new-count', informObj).show()
                } else {
                    $('.new-count', informObj).hide();
                }
            }
        });
        informCheck_setTimeout(refresh_time)
    } else {
        $('.new-count', informObj).text(lastCount);
        if (lastCount > 0) {
            $('.new-count', informObj).show()
        } else {
            $('.new-count', informObj).hide();
        }
        informCheck_setTimeout(refresh_time - pas)
    }
}

function informNotifyGetList() {
    var filter = $('input[name=aj_filter]', informObj).val(),
        query = ('' != filter && 'all' != filter) ? 'filter=' + filter + '&nocache=' : 'nocache=',
        url = informModuleUrl + ((-1 < informModuleUrl.indexOf("?")) ? '&' : '?') + query + new Date().getTime();
    $.ajax({
        type: 'GET',
        url: url,
        dataType: "json",
        success: function(result) {
            $('.inform-content', informObj).html(result.content);
            if (result.count) {
                lastCount = 0;
                nv_setCookie(count_cookie_name, lastCount, 365);
                informNotifyGetCount()
            }
        }
    })
}

function informNotifySetStatus(id, status, callback) {
    var url = informModuleUrl + ((-1 < informModuleUrl.indexOf("?")) ? '&' : '?') + 'nocache=' + new Date().getTime();
    $.ajax({
        type: 'POST',
        url: url,
        data: 'setStatus=' + status + '&id=' + id,
        dataType: "json",
        success: function(result) {
            if ('OK' == result.status) {
                informNotifyGetList();
                if (typeof callback === "function") {
                    callback()
                }
            }
        }
    })
}

$(window).on('load', function() {
    if ($('#inform-notification').length) {
        informObj = $('#inform-notification');
        refresh_time = parseInt(informObj.data('refresh-time')) * 1000;
        informModuleUrl = informObj.data('url');
        checkInformUrl = informObj.data('checkinform-url');
        userid = informObj.data('userid');
        usergroups = informObj.data('usergroups');
        csrf = informObj.data('csrf');

        if (typeof PerfectScrollbar != "undefined") {
            var ps = new PerfectScrollbar('#inform-notification .inform-content', {
                wheelSpeed: 1,
                wheelPropagation: true,
                minScrollbarLength: 20
            })
        }

        informObj.on({
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
                informNotifyGetList()
            }
        });

        $('[data-toggle=informNotifyRefresh]', informObj).on('click', function() {
            informNotifyGetList()
        });

        $('[data-toggle=changeFilter]', informObj).on('click', function(e) {
            e.preventDefault();
            $('[name=aj_filter]', informObj).val($(this).data('filter'));
            if (!$(this).is('.active')) {
                $(this).addClass('active');
                $(this).siblings().removeClass('active');
                informNotifyGetList()
            }
        })

        informObj.on('click', '[data-toggle=informNotifySetStatus]', function(e) {
            e.preventDefault();
            informNotifySetStatus($(this).parents('.item').data('id'), $(this).data('status'))
        });

        informObj.on('click', '.message a', function(e) {
            var item = $(this).parents('.item'),
                href = $(this).attr('href');
            if (item.is('.viewed-0')) {
                e.preventDefault();
                informNotifySetStatus(item.data('id'), 'viewed', function() {
                    if ('' != href && '#' != href) {
                        window.location.href = href
                    }
                })
            }
        });

        informObj.on('click', '[data-toggle=more]', function(e) {
            e.preventDefault();
            var obj = $(this).parents('.item');
            $('.more', obj).hide();
            $('.morecontent', obj).show()
        });

        var informCookie = nv_getCookie(inform_cookie_name),
            countCookie = nv_getCookie(count_cookie_name);
        if (informCookie) {
            lastCheckInform = parseInt(informCookie)
        }
        if (countCookie) {
            lastCount = parseInt(countCookie)
        }

        informNotifyGetCount()
    }
});
