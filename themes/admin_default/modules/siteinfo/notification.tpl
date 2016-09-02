<!-- BEGIN: main -->
<div id="notification-lists">
    <!-- BEGIN: loop -->
    <div class="notify_item clearfix">
        <a class="pull-right ntf-hide" href="#" title="{LANG.notification_hide}" data-id="{DATA.id}">
            <i class="fa fa-times-circle"></i>
        </a>
    	<a href="{DATA.link}">
    		<img src="{DATA.photo}" class="pull-left bg-gainsboro" />
    		<div class="pull-left" style="width: 89%">
    			{DATA.title}
    			<br />
    			<abbr class="timeago" title="{DATA.add_time_iso}">{DATA.add_time}</abbr>
    		</div> <div class="clearfix"></div>
    	</a>
    </div>
    <!-- END: loop -->
</div>

<!-- BEGIN: generate_page -->
<div class="clearfix notification-pages">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->

<!-- END: main -->

<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.notification_empty}</div>
<!-- END: empty -->