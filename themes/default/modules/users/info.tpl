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
<ul class="nav nav-tabs m-bottom">
	<li><a href="{URL_HREF}main">{LANG.user_info}</a></li>
	<li><a href="{URL_HREF}changepass">{LANG.changepass_title}</a></li>
	<li><a href="{URL_HREF}editinfo&amp;changequestion">{LANG.question2}</a></li>
	<!-- BEGIN: allowopenid --><li><a href="{URL_HREF}openid">{LANG.openid_administrator}</a></li><!-- END: allowopenid -->
	<!-- BEGIN: regroups --><li><a href="{URL_HREF}regroups">{LANG.in_group}</a></li><!-- END: regroups -->
	<!-- BEGIN: logout --><li><a href="{URL_HREF}logout">{LANG.logout_title}</a></li><!-- END: logout -->
</ul>
<h2>{LANG.editinfo_pagetitle}</h2>
<form id="frm" role="form" action="{EDITINFO_FORM}" method="post" enctype="multipart/form-data" class="form-horizontal form-tooltip m-bottom">
	<!-- BEGIN: error -->
	<div class="alert alert-danger">
		<em class="fa fa-exclamation-triangle ">&nbsp;</em> {ERROR}
	</div>
	<!-- END: error -->
	<div class="form-group">
		<label for="nv_username_iavim" class="col-sm-3 control-label">{LANG.account}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<!-- BEGIN: username_change -->
			<input type="text" class="form-control required" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}"  placeholder="{LANG.account}"/>
			<!-- END: username_change -->
			<!-- BEGIN: username_no_change -->
			<label class="control-label">{DATA.username}</label>
			<!-- END: username_no_change -->
		</div>
	</div>
	<div class="form-group">
		<label for="nv_email_iavim" class="col-sm-3 control-label">{LANG.email}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-9">
			<!-- BEGIN: email_change -->
			<input type="text" class="form-control required email" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100"  placeholder="{LANG.email}"/>
			<!-- END: email_change -->
			<!-- BEGIN: email_no_change -->
			<label class="control-label">{DATA.email}</label>
			<!-- END: email_no_change -->
		</div>
	</div>
	<div class="form-group">
		<label for="full_name" class="col-sm-3 control-label">{LANG.name}:</label>
		<div class="col-sm-9">
			<input type="text" class="form-control required" name="full_name" value="{DATA.full_name}" id="full_name" maxlength="100"  placeholder="{LANG.name}"/>
		</div>
	</div>
	<div class="form-group">
		<label for="gender" class="col-sm-3 control-label">{LANG.gender}:</label>
		<div class="col-sm-9">
			<select class="form-control" name="gender" id="gender">
				<!-- BEGIN: gender_option -->
				<option value="{GENDER.value}"{GENDER.selected}>{GENDER.title}</option>
				<!-- END: gender_option -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="avatar" class="col-sm-3 control-label">{LANG.avata}:</label>
		<div class="col-sm-9">
			<!-- BEGIN: photo -->
			<p id="current-photo" class="pull-left text-center">
				<img src="{DATA.photo}" alt="{DATA.username}" class="img-thumbnail m-bottom"/><br />
				<span class="fa-pointer" id="current-photo-btn"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.avata_delete}</span>
				<input type="hidden" name="photo_delete" id="photo_delete" value="{DATA.photo_delete}"/>
			</p>
			<!-- END: photo -->
			<div id="change-photo">
				<div class="input-group">
					<span class="input-group-addon">
						<em class="fa fa-trash-o fa-fix fa-pointer" data-toggle="tooltip" data-placement="left" title="{LANG.avata_clear}" onclick="$('#avatar').val('');">&nbsp;</em>
					</span>
					<input type="text" class="form-control" id="avatar" name="avatar" value="" readonly="readonly"/>
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" id="btn_upload" data-toggle="tooltip" data-placement="right" title="{LANG.avata_pagetitle}"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="birthday" class="col-sm-3 control-label">{LANG.birthday}:</label>
		<div class="col-sm-9">
			<div class="input-group">
				<input type="text" class="form-control datepicker" id="birthday" name="birthday" value="{DATA.birthday}" readonly="readonly">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" id="birthday-btn"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
				</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label for="view_mail" class="col-sm-3 control-label">{LANG.showmail}:</label>
		<div class="col-sm-9">
			<select name="view_mail" id="view_mail" class="form-control">
				<option value="0">{LANG.no}</option>
				<option value="1"{DATA.view_mail}>{LANG.yes}</option>
			</select>
		</div>
	</div>
	<!-- BEGIN: field -->
	<!-- BEGIN: loop -->
	<div class="form-group">
		<label class="col-sm-3 control-label" data-toggle="tooltip" data-placement="right" title="{FIELD.description}">{FIELD.title}<!-- BEGIN: required --><span class="text-danger"> (*)</span><!-- END: required -->:</label>
		<div class="col-sm-9">
			<!-- BEGIN: textbox -->
			<input class="{FIELD.required} {FIELD.class} form-control" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}"/>
			<!-- END: textbox -->
			<!-- BEGIN: date -->
			<div class="input-group">
				<input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" id="custom_fields_{FIELD.field}" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" readonly="readonly">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="$('#custom_fields_{FIELD.field}').datepicker('show');"> <em class="fa fa-calendar fa-fix">&nbsp;</em></button>
				</span>
			</div>
			<!-- END: date -->
			<!-- BEGIN: textarea -->
			<textarea name="custom_fields[{FIELD.field}]" class="{FIELD.class} form-control">{FIELD.value}</textarea>
			<!-- END: textarea -->
			<!-- BEGIN: editor -->
			{EDITOR}
			<!-- END: editor -->
			<!-- BEGIN: select -->
			<select name="custom_fields[{FIELD.field}]" class="{FIELD.class} form-control">
				<!-- BEGIN: loop -->
				<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
				<!-- END: loop -->
			</select>
			<!-- END: select -->
			<!-- BEGIN: radio -->
			<label for="lb_{FIELD_CHOICES.id}"> <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" class="{FIELD.class}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
			<!-- END: radio -->
			<!-- BEGIN: checkbox -->
			<label for="lb_{FIELD_CHOICES.id}"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" class="{FIELD.class}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
			<!-- END: checkbox -->
			<!-- BEGIN: multiselect -->
			<select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} form-control">
				<!-- BEGIN: loop -->
				<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
				<!-- END: loop -->
			</select>
			<!-- END: multiselect -->
		</div>
	</div>
	<!-- END: loop -->
	<!-- END: field -->
	<div class="text-center">
		<input type="hidden" name="checkss" value="{DATA.checkss}" />
		<input name="submit" type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
	</div>
</form>
<script type="text/javascript">
$(document).ready(function() {
	$('#frm').validate();
	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn: 'focus'
	});
	$('#birthday-btn').click(function(){
		$("#birthday").datepicker('show');
	});
	$("#btn_upload").click(function() {
		nv_open_browse( nv_siteroot  + "index.php?" + nv_name_variable  + "=" + nv_module_name + "&" + nv_fc_variable  + "=avatar", "NVImg", 650, 650, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no");
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
</script>
<!-- END: main -->