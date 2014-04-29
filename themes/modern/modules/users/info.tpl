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
	<div class="page-header">
		<h3>{LANG.editinfo_pagetitle}</h3>
	</div>
	<ul class="list-tab top-option clearfix">
		<li class="ui-tabs-selected">
			<a href="{URL_HREF}editinfo">{LANG.editinfo}</a>
		</li>
		<li>
			<a href="{URL_HREF}changepass">{LANG.changepass_title}</a>
		</li>
		<li>
			<a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a>
		</li>
		<!-- BEGIN: allowopenid -->
		<li>
			<a href="{URL_HREF}openid">{LANG.openid_administrator}</a>
		</li>
		<!-- END: allowopenid -->
		<!-- BEGIN: regroups -->
		<li>
			<a href="{URL_HREF}regroups">{LANG.in_group}</a>
		</li>
		<!-- END: regroups -->
		<!-- BEGIN: logout -->
		<li>
			<a href="{URL_HREF}logout">{LANG.logout_title}</a>
		</li>
		<!-- END: logout -->
	</ul>
	<form id="frm" action="{EDITINFO_FORM}" method="post" class="box-border content-box clearfix bgray" enctype="multipart/form-data">
		<!-- BEGIN: error -->
		<div style="color:#fb490b;text-align:center;">
			{ERROR}
		</div>
		<br/>
		<!-- END: error -->
		<div class="box-border content-box clearfix m-bottom edit-info bwhite">
			<dl class="clearfix">
				<dt class="fl">
					<label> {LANG.account} </label>
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
					<label> {LANG.email} </label>
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
					<label> {LANG.name} </label>
				</dt>
				<dd class="fl">
					<input class="input" name="full_name" value="{DATA.full_name}" maxlength="255" />
				</dd>
			</dl>
			<dl class="clearfix">
				<dt class="fl">
					<label> {LANG.gender} </label>
				</dt>
				<dd class="fl">
					<select name="gender" class="input">
						<!-- BEGIN: gender_option -->
						<option value="{GENDER.value}"{GENDER.selected}>{GENDER.title}</option>
						<!-- END: gender_option -->
					</select>
				</dd>
			</dl>
			<dl class="clearfix">
				<dt class="fl">
					<label> {LANG.avata} </label>
				</dt>
				<dd class="fl">
					<!-- BEGIN: photo -->
					<div id="current-photo">
						<img src="{DATA.photo}" alt="{DATA.username}" class="s-border"/><br />
						<input type="hidden" name="photo_delete" id="photo_delete" value="{DATA.photo_delete}"/>
						<a href="javascript:void(0);" id="current-photo-btn">{LANG.avata_delete}</a>
					</div>
					<!-- END: photo -->
					<div id="change-photo" class="hide">
						<input type="text" id="avatar" name="avatar" value="" class="input input-min"/>
						<input type="button" value="{LANG.avata_chosen}" id="btn_upload" class="button-2"/>
					</div>
				</dd>
			</dl>
			<dl class="clearfix">
				<dt class="fl">
					<label> {LANG.birthday} </label>
				</dt>
				<dd class="fl">
					<input name="birthday" value="{DATA.birthday}" style="width: 150px;text-align:left" class="input datepicker" maxlength="10" readonly="readonly" type= "text" />
				</dd>
			</dl>
			<dl class="clearfix">
				<dt class="fl">
					<label> {LANG.showmail} </label>
				</dt>
				<dd class="fl">
					<select name="view_mail" class="input">
						<option value="0">{LANG.no}</option>
						<option value="1"{DATA.view_mail}>{LANG.yes}</option>
					</select>
				</dd>
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
				<dd class="fl">
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
		<div class="aright">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input name="submit" type="submit" class="button" value="{LANG.editinfo_confirm}" />
		</div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
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
		nv_open_browse( nv_siteroot  + "index.php?" + nv_name_variable  + "=" + nv_module_name + "&" + nv_fc_variable  + "=avatar", "NVImg", 550, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
		return false;
	});
	$('#current-photo-btn').click(function(){
		$('#current-photo').hide();
		$('#photo_delete').val('1');
		$('#change-photo').show();
	});
	<!-- BEGIN: add_photo -->
	$('#change-photo').show();
	<!-- END: add_photo -->
});
//]]>
</script>
<!-- END: main -->