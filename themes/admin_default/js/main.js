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
	fixContentHeight: function(){
		var wrap = $('.nvwrap');
		var vmenu = $('#left-menu');
		
		if( wrap.length > 0 ){
			if( wrap.height() < vmenu.height() + vmenu.offset().top && vmenu.is(':visible') ){
				wrap.css('min-height', ( vmenu.height() + vmenu.offset().top ) + 'px' )
			}else{
				wrap.css('min-height', '100%');
			}		
		}
	},
};

$(document).ready(function(){
	// Control content height
	NV.fixContentHeight();
	$(window).resize(function(){
		NV.fixContentHeight();
	});
	
	// Show submenu
	$('#menu-horizontal .dropdown, #left-menu .dropdown:not(.active)').hover(function(){
		NV.openMenu(this);
	}, function(){
		NV.closeMenu(this);
	});

	// Left menu handle
	$('#left-menu-toggle').click(function(){
		if( $('#left-menu').is(':visible') ){
			$('#left-menu, #left-menu-bg, #container, #footer').removeClass('open');
		}else{
			$('#left-menu, #left-menu-bg, #container, #footer').addClass('open');
		}
		NV.fixContentHeight();
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
	
	// Disable confirm message on submit form
	$('form').submit(function() {
		$(window).unbind();
	});
	
	// Header tooltip
	$('.tip', $('#header')).tooltip();
});