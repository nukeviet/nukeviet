<!-- BEGIN: main -->
<nav id="menu-wrap">    
	<ul id="navbar_menu">
		<li><a title="{LANG.Home}" href="{THEME_SITE_HREF}"><span>{LANG.Home}</span></a></li>
		<!-- BEGIN: top_menu -->
			<li>
				<a title="{TOP_MENU.title}" href="{TOP_MENU.link}">{TOP_MENU.title}</a>
				{TOP_MENU.submenu}
			</li>
		<!-- END: top_menu -->
	</ul>
</nav>

<script type="text/javascript">
$( function() {
	if( $.browser.msie && $.browser.version.substr( 0,1 )<7 )
	{
		$('li').has('ul').mouseover(function()
		{
			$(this).children('ul').css('visibility','visible');
		}).mouseout(function()
		{
			$(this).children('ul').css('visibility','hidden');
		})
	}

	/* Mobile */
	$('#menu-wrap').prepend('<div id="menu-trigger">Menu</div>');		
	$("#menu-trigger").on("click", function(){
		$("#menu").slideToggle();
	});

	// iPad
	var isiPad = navigator.userAgent.match(/iPad/i) != null;
	if (isiPad) $('#menu ul').addClass('no-transition');      
});          
</script>
<!-- END: main -->