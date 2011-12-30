<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.openid_administrator}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
                <a href="{URL_HREF}main">{LANG.user_info}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo">{LANG.editinfo}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
                <!-- BEGIN: regroups --><strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a><!-- END: regroups -->
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->
            </span>
        </div>
    <div class="clear"></div>
    </div>
    <div style="text-align:center">
        <img alt="{LANG.openid_administrator}" title="{LANG.openid_administrator}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" /><br />
    </div>
    <!-- BEGIN: openid_empty -->
    <form id="openidForm" action="{FORM_ACTION}" method="post" class="register">
        <div class="content">
            <!-- BEGIN: openid_list -->
            <dl class="clearfix{OPENID_CLASS}">
                <dt class="fl">
                    <input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}" style="padding-right:5px"{OPENID_LIST.disabled} />
                </dt>
                <dd class="fl">
                    <a href="javascript:void(0);" title="{OPENID_LIST.openid}">{OPENID_LIST.server}</a>
                </dd>
                <dd class="fr">
                    {OPENID_LIST.email}
                </dd>
            </dl>
            <!-- END: openid_list -->
            <input id="submit" type="submit" class="submit" value="{LANG.openid_del}" />
        </div>
    </form>
    <!-- END: openid_empty -->
    <div style="padding-top:10px;text-align:center">
        <div style="margin-bottom:10px;">
            {DATA.info}
        </div>
        <!-- BEGIN: server -->
        <a href="{OPENID.href}"><img style="margin-left: 10px;margin-right:2px;vertical-align:middle;" alt="{OPENID.title}" title="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" /> {OPENID.title}</a>
        <!-- END: server -->
    </div>
</div>
<!-- END: main -->