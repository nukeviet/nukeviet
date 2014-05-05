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


jQuery(document).ready(function() {
	myTimerPage = setTimeout(function() {
		timeoutsessrun();
	}, nv_check_pass_mstime);

	ddsmoothmenu.init({
		arrowimages : {
			down : ['downarrowclass', nv_siteroot + 'themes/admin_default/images/menu_down.png', 23],
			right : ['rightarrowclass', nv_siteroot + 'themes/admin_default/images/menu_right.png']
		},
		zindexvalue : 8888,
		mainmenuid : "smoothmenu",
		orientation : 'h',
		classname : 'ddsmoothmenu',
		contentsource : "markup"
	});

	ddsmoothmenu.init({
		arrowimages : {
			down : ['downarrowclass', nv_siteroot + 'themes/admin_default/images/menu_down.png', 23],
			right : ['rightarrowclass', nv_siteroot + 'themes/admin_default/images/menu_right.png']
		},
		zindexvalue : 999,
		mainmenuid : "left_menu",
		orientation : 'v',
		classname : 'ddsmoothmenu-v',
		contentsource : "markup"
	});

	$('form.confirm-reload').change(function() {
		$(window).bind('beforeunload', function() {
			return nv_msgbeforeunload;
		});
	});
	$('form').submit(function() {
		$(window).unbind();
	});
	
	// Modify table caption
	$('table.tab1 caption').prepend(' &nbsp; <em class="icon-double-angle-right">&nbsp;</em> ');
});

function ver_menu_click() {
	if ($('#ver_menu').is(':visible')) {
		$('#ver_menu').hide({
			direction : "horizontal"
		}, 500);
		$('#contentwrapper').css("margin-left", "0px");
		$('#cs_menu i').removeClass("icon-circle-arrow-left").addClass("icon-circle-arrow-right");
	} else {
		$('#contentwrapper').css("margin-left", "226px");
		$('#ver_menu').show({
			direction : "horizontal"
		}, 500);
		$('#cs_menu i').removeClass("icon-circle-arrow-right").addClass("icon-circle-arrow-left");
	}
}