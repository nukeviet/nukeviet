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

function notification_reset() {
	$.post(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load&nocache=' + new Date().getTime(), 'notification_reset=1', function(res) {
		$('#notification').hide();
	});
}

var page = 1;
function notification_get_more() {
	$('#notification_load').scroll(function() {
		if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
			page++;
			$.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load&page=' + page, function(result) {
				$('#notification_load').append(result);
			});
		}
	});
}

function nv_get_notification(timestamp) {
	if(!timer_is_on)
	{
	    clearTimeout(timer);
	    timer_is_on = 0;
		var queryString = {
			'notification_get' : 1,
			'timestamp' : timestamp
		};

		$.ajax({
			type : 'GET',
			url : script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load&nocache=' + new Date().getTime(),
			data : queryString,
			success : function(data) {
				var obj = jQuery.parseJSON(data);
				if (obj.data_from_file > 0) {
					$('#notification').show().html(obj.data_from_file);
				} else {
					$('#notification').hide();
				}
				// call the function again
				timer = setTimeout("nv_get_notification()", 30000);// load step 30 sec
			}
		});
	}
}

$(function() {
	nv_get_notification();
	notification_get_more();

	// Notification
	$('.menu .dropdown').hover(function() {
		//NV.openMenu(this);
		$.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load', function(result) {
			notification_reset();
			$('#notification_load').html(result).slimScroll({
				height : '250px'
			});
			$("abbr.timeago").timeago();
		});
	}, function() {
		page = 1;
		//NV.closeMenu(this);
	});
});