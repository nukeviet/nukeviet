/**
 * AJAX long-polling
 *
 * 1. sends a request to the server (without a timestamp parameter)
 * 2. waits for an answer from server.php (which can take forever)
 * 3. if server.php responds (whenever), put data_from_file into #response
 * 4. and call the function again
 *
 * @param timestamp
 */
var timer = 0;
var timer_is_on = 0;
var load_notification = 1;

function notification_reset() {
    $.post(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(), 'notification_reset=1', function(res) {
        $('#notification').hide();
    });
}

var page = 1;

function notification_get_more() {
    $('#notification_load').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            page++;
            $('#notification_waiting').show();
            $.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&page=' + page + '&nocache=' + new Date().getTime(), function(result) {
                $('#notification_load').append(result);
                $('#notification_waiting').hide();
            });
        }
    });
}

function nv_get_notification(timestamp) {
    if (!timer_is_on) {
        clearTimeout(timer);
        timer_is_on = 0;
        var queryString = {
            'notification_get': 1,
            'timestamp': timestamp
        };
        if (load_notification) {
            $.ajax({
                type: 'GET',
                url: script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
                data: queryString,
                success: function(data) {
                    if (data.data_from_file > 0) {
                        $('#notification').show().html(data.data_from_file);
                    } else {
                        $('#notification').hide();
                    }
                    // call the function again
                    timer = setTimeout("nv_get_notification()", 30000); // load step 30 sec
                }
            });
        }
    }
}

$(function() {
    nv_get_notification();
    notification_get_more();

    // Notification
    $('#notification-area').on('show.bs.dropdown', function() {
        $.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&ajax=1&nocache=' + new Date().getTime(), function(result) {
            notification_reset();
            $('#notification_load').html(result).slimScroll({
                height: '250px'
            });
            $("abbr.timeago").timeago();
            $('#notification_waiting').hide();
        });
    });

    $('#notification-area').on('show.bs.dropdown', function() {
        page = 1;
        $('#notification_load').html('');
        $('#notification_waiting').show();
    });

    // Hide notification
    $('.notify_item .ntf-hide').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        $.ajax({
            type: 'POST',
            url: script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification&nocache=' + new Date().getTime(),
            data: 'delete=1&id=' + $this.data('id'),
            success: function(data) {
                if (data == 'OK') {
                    window.location.href = window.location.href;
                } else {
                    alert(nv_is_change_act_confirm[2]);
                }
            }
        });
    });
});