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

var page = 1;

function notification_reset() {
	$.post(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load&nocache=' + new Date().getTime(), 'notification_reset=1', function(res) {
		$('#notification').hide();
	});
}

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

function getContent(timestamp) {
	var queryString = {
		'timestamp' : timestamp,
		'datadir' : nv_datadir,
		'file_admin' : nv_notification_file_admin
	};

	$.ajax({
		type : 'GET',
		url : '/notification.php',
		data : queryString,
		success : function(data) {
			// put result data into "obj"
			var obj = jQuery.parseJSON(data);
			// put the data_from_file into #response
			if (obj.data_from_file > 0) {
				$('#notification').show().html(obj.data_from_file);
			} else {
				$('#notification').hide();
			}
			// call the function again, this time with the timestamp we just got from server.php
			getContent(obj.timestamp);
		}
	});
}

// initialize jQuery
$(function() {
	getContent();

	notification_get_more();

	// Notification
	$('.menu .dropdown').hover(function() {
		NV.openMenu(this);
		$.get(script_name + '?' + nv_name_variable + '=siteinfo&' + nv_fc_variable + '=notification_load', function(result) {
			notification_reset();
			$('#notification_load').html(result).slimScroll({
				height : '250px'
			});
			$("abbr.timeago").timeago();
		});
	}, function() {
		page = 1;
		NV.closeMenu(this);
	});
});