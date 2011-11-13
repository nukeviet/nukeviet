<!-- BEGIN: main -->
<div id="users">
    <h2 class="line padding_0" style="margin-bottom:5px">{LANG.editinfo_pagetitle}</h2>
    <div style="padding-bottom:10px">
        <div class="utop">
            <span class="topright">
                <a href="{URL_HREF}main">{LANG.user_info}</a> 
                <strong>&middot;</strong> <a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
                <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
                <!-- BEGIN: allowopenid --><strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a><!-- END: allowopenid -->
                <!-- BEGIN: regroups --><strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a><!-- END: regroups -->
                <!-- BEGIN: logout --><strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a><!-- END: logout -->
            </span>
        </div>
    <div class="clear"></div>
    </div>
    <form action="{EDITINFO_FORM}" method="post" class="register" enctype="multipart/form-data">
        <div class="content">
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.account}
                    </label>
                </dd>
                <dt class="fr">
                    <!-- BEGIN: username_change -->
                    <input class="txt" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
                    <!-- END: username_change -->
                    <!-- BEGIN: username_no_change -->
                    <strong>{DATA.username}</strong>
                    <!-- END: username_no_change -->
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.email}
                    </label>
                </dd>
                <dt class="fr">
                    <!-- BEGIN: email_change -->
                    <input class="txt" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
                    <!-- END: email_change -->
                    <!-- BEGIN: email_no_change -->
                    <strong>{DATA.email}</strong>
                    <!-- END: email_no_change -->
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.name}
                    </label>
                </dd>
                <dt class="fr">
                    <input class="txt" name="full_name" value="{DATA.full_name}" maxlength="255" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.gender}
                    </label>
                </dd>
                <dt class="fr">
                    <select name="gender">
                        <!-- BEGIN: gender_option -->
                        <option value="{GENDER.value}"{GENDER.selected}>{GENDER.title}</option>
                        <!-- END: gender_option -->
                    </select>
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.avata} (80x80)
                    </label>
                </dd>
                <dt class="fr">
                    <input type="file" class="txt" name="avatar" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.birthday}
                    </label>
                </dd>
                <dt class="fr">
                    <input name="birthday" id="birthday" value="{DATA.birthday}" style="width: 150px;text-align:left" maxlength="10" readonly="readonly" type= "text" />
                    <img src="{NV_BASE_SITEURL}images/calendar.jpg" style="cursor: pointer; vertical-align: middle;" onclick="popCalendar.show(this, 'birthday', 'dd.mm.yyyy', true);" alt="" height="17" />
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.website}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="website" value="{DATA.website}" maxlength="255" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.address}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="address" value="{DATA.address}" maxlength="255" />
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.yahoo}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="yim" value="{DATA.yim}" maxlength="100" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.phone}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="telephone" value="{DATA.telephone}" maxlength="100" />
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.fax}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="fax" value="{DATA.fax}" maxlength="100" />
                </dt>
            </dl>
            <dl class="clearfix">
                <dd class="fl">
                    <label>
                        {LANG.mobile}
                    </label>
                </dd>
                <dt class="fr">
                    <input type="text" class="txt" name="mobile" value="{DATA.mobile}" maxlength="100" />
                </dt>
            </dl>
            <dl class="clearfix gray">
                <dd class="fl">
                    <label>
                        {LANG.showmail}
                    </label>
                </dd>
                <dt class="fr">
                    <select name="view_mail">
                        <option value="0">{LANG.no}</option>
                        <option value="1"{DATA.view_mail}>{LANG.yes}</option>
                    </select>
                </dt>
            </dl>
        </div>
        <div style="text-align:center">
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input name="submit" type="submit" class="submit" value="{LANG.editinfo_confirm}" />
        </div>
    </form>
</div>
<!-- END: main -->