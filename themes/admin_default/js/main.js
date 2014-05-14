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

var NV = {
	menuBusy: false,
	menuTimer: null,
	menu: null,
	openMenu: function(menu){
		this.menuBusy = true;
		this.menu = $(menu);
		this.menuTimer = setTimeout( function(){ NV.menu.addClass('open'); }, 300 );
	},
	closeMenu: function(menu){
		clearTimeout( this.menuTimer );
		this.menuBusy = false;
		this.menu = $(menu).removeClass('open');
	},
};

$(document).ready(function(){
	$('#menu-horizontal .dropdown, #left-menu .dropdown:not(.active)').hover(function(){
		NV.openMenu(this);
	}, function(){
		NV.closeMenu(this);
	});

	myTimerPage = setTimeout(function() {
		timeoutsessrun();
	}, nv_check_pass_mstime);

	$('form.confirm-reload').change(function() {
		$(window).bind('beforeunload', function() {
			return nv_msgbeforeunload;
		});
	});
	
	$('form').submit(function() {
		$(window).unbind();
	});
	
	$('.tip', $('#header')).tooltip();
});