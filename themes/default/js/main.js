/* *
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */
function fix_banner_center() {
	var w = Math.round(($(window).width() - 1330)/4);
    if ( w > 0)
    {
    	$( "div.fix_banner_left" ).css( "left", w+"px" );
    	$( "div.fix_banner_right" ).css( "right", w+"px" );

		var h = Math.round(($(window).height() - $( "div.fix_banner_left" ).height())/2);
		if(h <=0 )
		{
			h = 0;
		}
		$( "div.fix_banner_left" ).css( "top", h+"px" );

		h = Math.round(($(window).height() - $( "div.fix_banner_right" ).height())/2);
		if(h <=0 )
		{
			h = 0;
		}
		$( "div.fix_banner_right" ).css( "top", h+"px" );

    	$( "div.fix_banner_left" ).show();
    	$( "div.fix_banner_right" ).show();
	}
	else
	{
    	$( "div.fix_banner_left" ).hide();
    	$( "div.fix_banner_right" ).hide();
	}
}

var myTimerPage = '';
var myTimersecField = '';

function timeoutsesscancel() {
	clearInterval(myTimersecField);
	$.ajax({
		url : nv_siteroot + 'index.php?second=statimg',
		cache : false
	}).done(function() {
		$("#timeoutsess").hide();
		myTimerPage = setTimeout(function() {
			timeoutsessrun();
		}, nv_check_pass_mstime);
	});
}

function timeoutsessrun() {
	
	clearInterval(myTimerPage);
	var Timeout = 60;
	document.getElementById('secField').innerHTML = Timeout;
	jQuery("#timeoutsess").show();
	var msBegin = new Date().getTime();
	myTimersecField = setInterval(function() {
		var msCurrent = new Date().getTime();
		var ms = Timeout - Math.round((msCurrent - msBegin) / 1000);
		if (ms >= 0) {
			document.getElementById('secField').innerHTML = ms;
		} else if (ms < -3) {
			clearInterval(myTimersecField);
			$(window).unbind();
			window.location.reload();
		}
	}, 1000);
}

// NukeViet Default Custom JS
$(document).ready(function(){
	fix_banner_center();

	// Modify all empty link
	$('a[href="#"], a[href=""]').attr('href','javascript:void(0);');

	// Smooth scroll to top
	$('#totop').click(function(){
		$('body').animate({scrollTop : 0}, 'slow');
		return false;
	});

// Show admin confirm
	myTimerPage = setTimeout(function() {
		timeoutsessrun();
	}, nv_check_pass_mstime);

	// Show confirm message on leave, reload page
	$('form.confirm-reload').change(function() {
		$(window).bind('beforeunload', function() {
			return nv_msgbeforeunload;
		});
	});
	
	// Trigger tooltip
	$('.form-tooltip').tooltip({
		selector: "[data-toggle=tooltip]",
		container: "body"
	});
});

$(window).on('resize', function()
{
	fix_banner_center();
});