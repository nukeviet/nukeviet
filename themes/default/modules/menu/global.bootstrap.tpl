<!-- BEGIN: submenu -->
<ul class="dropdown-menu">
	<!-- BEGIN: loop -->
    <li <!-- BEGIN: submenu -->class="dropdown-submenu"<!-- END: submenu -->>
        <!-- BEGIN: icon -->
        <img src="{SUBMENU.icon}" />&nbsp;
        <!-- END: icon -->
        <a href="{SUBMENU.link}" title="{SUBMENU.note}" {SUBMENU.target}>{SUBMENU.title_trim}</a>
        <!-- BEGIN: item -->
        {SUB}
        <!-- END: item -->
    </li>
    <!-- END: loop -->
</ul>
<!-- END: submenu -->

<!-- BEGIN: main -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-site-default">
			<span class="sr-only">&nbsp;</span>
			<span class="icon-bar">&nbsp;</span>
			<span class="icon-bar">&nbsp;</span>
			<span class="icon-bar">&nbsp;</span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="menu-site-default">
        <ul class="nav navbar-nav">
			<li <!-- BEGIN: home_active --> class="active"<!-- END: home_active -->>
				<a title="{LANG.Home}" href="{THEME_SITE_HREF}"><em class="fa fa-lg fa-home">&nbsp;</em> {LANG.Home}</a>
			</li>
			<!-- BEGIN: top_menu -->
            <li {TOP_MENU.current} rol="presentation">
                <!-- BEGIN: icon -->
                <img src="{TOP_MENU.icon}" />&nbsp;
                <!-- END: icon -->
                <a class="dropdown-toggle" {TOP_MENU.dropdown_data_toggle} href="{TOP_MENU.link}" role="button" aria-expanded="false" title="{TOP_MENU.note}" {TOP_MENU.target}>{TOP_MENU.title_trim}<!-- BEGIN: has_sub --> <strong class="caret">&nbsp;</strong><!-- END: has_sub --></a>
                <!-- BEGIN: sub -->
                {SUB}
                <!-- END: sub -->
			</li>
			<!-- END: top_menu -->
         </ul>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="#" id="digclock">{THEME_DIGCLOCK_TEXT}</a></li>
		</ul>
	</div>
</div>
<script type="text/javascript">
nv_DigitalClock('digclock');
$(document).ready(function(){
	$('#menu-site-default a').hover(function(){
		$(this).attr("rel", $(this).attr("title"));
        $(this).removeAttr("title");
	}, function(){
		$(this).attr("title", $(this).attr("rel"));
        $(this).removeAttr("rel");
	});

	var $window = $(window);

    function checkWidth() {
        var windowsize = $window.width();
        if (theme_responsive == '1' && windowsize <= 640) {
            $( "li.dropdown ul" ).removeClass( "dropdown-menu" );
            $( "li.dropdown ul" ).addClass( "dropdown-submenu" );
            $( "li.dropdown a" ).addClass( "dropdown-mobile" );
            $( "#menu-site-default ul li a.dropdown-toggle" ).addClass( "dropdown-mobile" );
            $( "li.dropdown ul li a" ).removeClass( "dropdown-mobile" );
        }
        else{
            $( "li.dropdown ul" ).addClass( "dropdown-menu" );
            $( "li.dropdown ul" ).removeClass( "dropdown-submenu" );
            $( "li.dropdown a" ).removeClass( "dropdown-mobile" );
            $( "li.dropdown ul li a" ).removeClass( "dropdown-mobile" );
            $( "#menu-site-default ul li a.dropdown-toggle" ).removeClass( "dropdown-mobile" );
            $('#menu-site-default .dropdown').hover(function(){
                $(this).addClass('open');
            }, function(){
                $(this).removeClass('open');
            });
        }
    }
    // Execute on load
    checkWidth();
    // Bind event listener
    $(window).resize(checkWidth);
});
</script>
<!-- END: main -->