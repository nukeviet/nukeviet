<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.change_pass}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
                <a href="{URL_HREF}main">{LANG.user_info}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
                <!-- BEGIN: allowopenid --><strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid -->
                <!-- BEGIN: regroups --><strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a><!-- END: regroups -->
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->
            </span>
        </div>
    <div class="clear"></div>
    </div>
    <form id="changePassForm" action="{USER_CHANGEPASS}" method="post" class="register1 clearfix">
        <div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
            {DATA.change_info}
        </div>
        <!-- BEGIN: passEmpty -->
        <div class="clearfix rows">
            <label>
                {LANG.pass_old}
            </label>
            <input type="password" id="nv_password_iavim" name="nv_password" value="{DATA.nv_password}" class="required password" maxlength="{PASS_MAXLENGTH}" />
        </div>
        <!-- END: passEmpty -->
        <div class="clearfix rows">
            <label>
                {LANG.pass_new}
            </label>
            <input type="password" id="new_password_iavim" name="new_password" value="{DATA.new_password}" class="required password" maxlength="{PASS_MAXLENGTH}" />
        </div>
        <div class="clearfix rows">
            <label>
                {LANG.pass_new_re}
            </label>
            <input type="password" id="re_password_iavim" name="re_password" value="{DATA.re_password}" class="required password" maxlength="{PASS_MAXLENGTH}" />
        </div>
        <div>
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="submit" value="{LANG.change_pass}" class="submit" />
        </div>
    </form>
</div>
<!-- END: main -->