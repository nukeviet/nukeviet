<!-- BEGIN: main -->
<script src="{NV_BASE_SITEURL}js/admin.js" type="text/javascript"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div id="users">
	<h2 class="line padding_0" style="margin-bottom:5px">{LANG.editinfo_pagetitle}</h2>
	<div style="padding-bottom:10px">
		<div class="utop">
			<span class="topright"> <a href="{URL_HREF}main">{LANG.user_info}</a> <strong>&middot;</strong> <a href="{URL_HREF}changepass">{LANG.changepass_title}</a> <strong>&middot;</strong> <a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
				<!-- BEGIN: allowopenid -->
				<strong>&middot;</strong> <a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
				<!-- END: allowopenid -->
				<!-- BEGIN: regroups -->
				<strong>&middot;</strong><a href="{URL_HREF}regroups">{LANG.in_group}</a>
				<!-- END: regroups -->
				<!-- BEGIN: logout -->
				<strong>&middot;</strong> <a href="{URL_HREF}logout">{LANG.logout_title}</a>
				<!-- END: logout -->
			</span>
		</div>
		<div class="clear"></div>
	</div>
	<form id="frm" action="{EDITINFO_FORM}" method="post" class="register" enctype="multipart/form-data">
		<!-- BEGIN: error -->
		<div style="color:#fb490b;text-align:center;">
			{ERROR}
		</div>
		<!-- END: error -->
		<div class="content">
			<dl class="clearfix gray">
				<dd class="fl">
					<label> {LANG.account} </label>
				</dd>
				<dt class="fr">
					<!-- BEGIN: username_change -->
					<input class="txt required" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
					<!-- END: username_change -->
					<!-- BEGIN: username_no_change -->
					<strong>{DATA.username}</strong>
					<!-- END: username_no_change -->
				</dt>
			</dl>
			<dl class="clearfix">
				<dd class="fl">
					<label> {LANG.email} </label>
				</dd>
				<dt class="fr">
					<!-- BEGIN: email_change -->
					<input class="txt required email" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
					<!-- END: email_change -->
					<!-- BEGIN: email_no_change -->
					<strong>{DATA.email}</strong>
					<!-- END: email_no_change -->
				</dt>
			</dl>
			<dl class="clearfix gray">
				<dd class="fl">
					<label> {LANG.name} </label>
				</dd>
				<dt class="fr">
					<input class="txt" name="full_name" value="{DATA.full_name}" maxlength="255" />
				</dt>
			</dl>
			<dl class="clearfix">
				<dd class="fl">
					<label> {LANG.gender} </label>
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
					<label> {LANG.avata} </label>
				</dd>
				<dt class="fr">
					<input type="text" class="txt" id="avatar" name="avatar" value="{DATA.photo}" />
					<input type="button" value="{LANG.avata_chosen}" id="btn_upload" />
				</dt>
			</dl>
			<dl class="clearfix">
				<dd class="fl">
					<label> {LANG.birthday} </label>
				</dd>
				<dt class="fr">
					<input name="birthday" class="datepicker" value="{DATA.birthday}" style="width: 150px;text-align:left" maxlength="10" readonly="readonly" type= "text" />
				</dt>
			</dl>
			<dl class="clearfix gray">
				<dd class="fl">
					<label> {LANG.showmail} </label>
				</dd>
				<dt class="fr">
					<select name="view_mail">
						<option value="0">{LANG.no}</option>
						<option value="1"{DATA.view_mail}>{LANG.yes}</option>
					</select>
				</dt>
			</dl>
			<!-- BEGIN: field -->
			<!-- BEGIN: loop -->
			<dl class="clearfix">
				<dt class="fl">
					<label>{FIELD.title}
						<!-- BEGIN: required -->
						<span class="error">(*)</span>
						<!-- END: required -->
					</label>
					<br>
					<em>{FIELD.description}</em>
				</dt>
				<dd class="fr">
					<!-- BEGIN: textbox -->
					<input class="{FIELD.required} {FIELD.class}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}"/>
					<!-- END: textbox -->
					<!-- BEGIN: date -->
					<input class="datepicker {FIELD.required} {FIELD.class}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}"/>
					<!-- END: date -->
					<!-- BEGIN: textarea --><textarea name="custom_fields[{FIELD.field}]" class="{FIELD.class}">{FIELD.value}</textarea>
					<!-- END: textarea -->
					<!-- BEGIN: editor -->
					{EDITOR}
					<!-- END: editor -->
					<!-- BEGIN: select -->
					<select name="custom_fields[{FIELD.field}]" class="{FIELD.class}">
						<!-- BEGIN: loop -->
						<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
						<!-- END: loop -->
					</select>
					<!-- END: loopselect -->
					<!-- BEGIN: radio -->
					<label for="lb_{FIELD_CHOICES.id}"> <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" class="{FIELD.class}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
					<!-- END: radio -->
					<!-- BEGIN: checkbox -->
					<label for="lb_{FIELD_CHOICES.id}"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" class="{FIELD.class}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
					<!-- END: checkbox -->
					<!-- BEGIN: multiselect -->
					<select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class}">
						<!-- BEGIN: loop -->
						<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
						<!-- END: loop -->
					</select>
					<!-- END: multiselect -->
				</dd>
			</dl>
			<!-- END: loop -->
			<!-- END: field -->
		</div>
		<div style="text-align:center">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input name="submit" type="submit" class="submit" value="{LANG.editinfo_confirm}" />
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#frm').validate();
		$(".datepicker").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true
		});
		
		$("#btn_upload").click(function() {
			nv_open_browse_file( nv_siteroot  + "index.php?" + nv_name_variable  + "=" + nv_module_name + "&" + nv_fc_variable  + "=avatar", "NVImg", 850, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
			return false;
		});
	});

	</script>
<!-- END: main -->