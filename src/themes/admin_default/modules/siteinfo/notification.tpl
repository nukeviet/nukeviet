<!-- BEGIN: main -->
<div id="notification-lists">
    <!-- BEGIN: loop -->
    <div class="notify_item clearfix">
        <div class="tools">
            <a class="ntf-toggle" href="#" title="{DATA.toggle_title}" data-msg-read="{LANG.notification_make_read}" data-msg-unread="{LANG.notification_make_unread}" data-id="{DATA.id}" data-toggle="notitoggle">
                <!-- BEGIN: set_read --><i class="fa fa-eye"></i><!-- END: set_read -->
                <!-- BEGIN: set_unread --><i class="fa fa-eye-slash"></i><!-- END: set_unread -->
            </a>
            <a class="pull-right ntf-delete" href="#" title="{GLANG.delete}" data-id="{DATA.id}" data-toggle="notidelete">
                <i class="fa fa-trash"></i>
            </a>
        </div>
        <a class="body-noti<!-- BEGIN: read --> noti-read<!-- END: read -->" href="{DATA.link}" data-id="{DATA.id}">
            <img src="{DATA.photo}" class="pull-left bg-gainsboro" />
            <div class="pull-left" style="width: 89%">
                {DATA.title}
                <br />
                <abbr class="timeago" title="{DATA.add_time_iso}">{DATA.add_time}</abbr>
            </div>
            <div class="clearfix"></div>
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
