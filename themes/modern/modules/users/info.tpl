<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div id="users">
	<div class="page-header">
		<h3>{LANG.editinfo_pagetitle}</h3>
	</div>
			<ul class="list-tab top-option clearfix">
				<li class="ui-tabs-selected"><a href="{URL_HREF}editinfo">{LANG.editinfo}</a></li>
				<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
				<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
				<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
				<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
				<!-- BEGIN: logout --><li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li><!-- END: logout -->
			</ul>
	<form id="frm" action="{EDITINFO_FORM}" method="post" class="box-border content-box clearfix bgray" enctype="multipart/form-data">
		<div class="box-border content-box clearfix m-bottom edit-info bwhite">
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.account}
                    </label>
                </dt>
                <dd class="fl">
                    <!-- BEGIN: username_change -->
                    <input class="input required" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
                    <!-- END: username_change -->
                    <!-- BEGIN: username_no_change -->
                    <strong>{DATA.username}</strong>
                    <!-- END: username_no_change -->
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.email}
                    </label>
                </dt>
                <dd class="fl">
                    <!-- BEGIN: email_change -->
                    <input class="input required email" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
                    <!-- END: email_change -->
                    <!-- BEGIN: email_no_change -->
                    <strong>{DATA.email}</strong>
                    <!-- END: email_no_change -->
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.name}
                    </label>
                </dt>
                <dd class="fl">
                    <input class="input" name="full_name" value="{DATA.full_name}" maxlength="255" />
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.gender}
                    </label>
                </dt>
                <dd class="fl">
                    <select name="gender">
                        <!-- BEGIN: gender_option -->
                        <option value="{GENDER.value}"{GENDER.selected}>{GENDER.title}</option>
                        <!-- END: gender_option -->
                    </select>
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.avata} (80x80)
                    </label>
                </dt>
                <dd class="fl">
                    <input type="file" name="avatar" />
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.birthday}
                    </label>
                </dt>
                <dd class="fl">
                    <input name="birthday" value="{DATA.birthday}" style="width: 150px;text-align:left" class="input datepicker" maxlength="10" readonly="readonly" type= "text" />
                </dd>
            </dl>
            <dl class="clearfix">
                <dt class="fl">
                    <label>
                        {LANG.showmail}
                    </label>
                </dt>
                <dd class="fl">
                    <select name="view_mail">
                        <option value="0">{LANG.no}</option>
                        <option value="1"{DATA.view_mail}>{LANG.yes}</option>
                    </select>
                </dd>
            </dl>
		</div>	
        <div class="aright">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input name="submit" type="submit" class="button" value="{LANG.editinfo_confirm}" />
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
		$('#frm').validate();
	    $(".datepicker").datepicker(
	    {
	        showOn : "both",
	        dateFormat : "dd/mm/yy",
	        changeMonth : true,
	        changeYear : true,
	        showOtherMonths : true,
	        buttonImage : nv_siteroot + "images/calendar.gif",
	        buttonImageOnly : true
	    });
	});
</script>
<!-- END: main -->