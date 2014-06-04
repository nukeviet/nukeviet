<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="alert alert-warning">{LANG.modforum}</div>
<!-- END: is_forum -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<!-- BEGIN: edit_user -->
<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form  class="form-inline" role="form" id="form_user" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<colgroup>
				<col class="w300"/>
				<col class="w20"/>
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td> {LANG.account} </td>
					<td> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="required form-control" value="{DATA.username}" name="username" id="username_iavim" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.email} </td>
					<td> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="email required form-control" value="{DATA.email}" name="email" id="email_iavim" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.question} </td>
					<td> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="form-control required" type="text" value="{DATA.question}" name="question" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.answer} </td>
					<td> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="form-control required" type="text" value="{DATA.answer}" name="answer" style="width: 300px" /></td>
				</tr>
				<tr>
					<td colspan="2"> {LANG.name} </td>
					<td><input class="form-control" type="text" value="{DATA.full_name}" name="full_name" style="width: 300px" /></td>
				</tr>
				<tr>
					<td colspan="2"> {LANG.gender} </td>
					<td>
					<select class="form-control" name="gender">
						<!-- BEGIN: gender -->
						<option value="{GENDER.key}"{GENDER.selected}>{GENDER.title}</option>
						<!-- END: gender -->
					</select></td>
				</tr>
				<tr>
					<td colspan="2"> {LANG.avata} </td>
					<td>
						<!-- BEGIN: photo -->
						<p id="current-photo" class="pull-left text-center">
							<img src="{IMG.src}" alt="{DATA.username}" class="img-thumbnail m-bottom" width="{IMG.width}" height="{IMG.height}"/><br />
							<span class="fa-pointer" id="current-photo-btn"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.delete}</span>
							<input type="hidden" name="delpic" id="photo_delete" value="{DATA.delpic}"/>
						</p>
						<!-- END: photo -->
						<div id="change-photo" class="w300">
							<div class="input-group">
								<span class="input-group-addon">
									<em class="fa fa-trash-o fa-fix fa-pointer" onclick="$('#avatar').val('');">&nbsp;</em>
								</span>
								<input type="text" class="form-control" id="avatar" name="photo" value="" readonly="readonly"/>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button" id="btn_upload"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
								</span>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2"> {LANG.birthday} </td>
					<td><input name="birthday" class="form-control datepicker" value="{DATA.birthday}" style="width: 300px;" maxlength="10" readonly="readonly" type="text" />
				</tr>
				<tr>
					<td colspan="2"> {LANG.show_email} </td>
					<td><input type="checkbox" name="view_mail" value="1"{DATA.view_mail} /></td>
				</tr>
				<tr>
					<td style="vertical-align:top" colspan="2"> {LANG.sig} </td>
					<td><textarea name="sig" cols="70" rows="5" style="width:300px" class="form-control">{DATA.sig}</textarea></td>
				</tr>
				<!-- BEGIN: group -->
				<tr>
					<td style="vertical-align:top" colspan="2"> {LANG.in_group} </td>
					<td>
						<div class="row checkbox">
							<!-- BEGIN: list -->
							<label class="col-sm-10">
								<input type="checkbox" value="{GROUP.id}" name="group[]"{GROUP.checked} /> {GROUP.title}
							</label>
							<!-- END: list -->
						</div>
					</td>
				</tr>
				<!-- END: group -->
			</tbody>
		</table>
		<!-- BEGIN: field -->
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields} </caption>
			<colgroup>
				<col class="w300"/>
				<col class="w20"/>
				<col />
			</colgroup>
			<tbody>
				<!-- BEGIN: loop -->
				<tr>
					<td><strong>{FIELD.title}</strong>
					<br>
					<em>{FIELD.description}</em></td>
					<td>
					<!-- BEGIN: required -->
					(<span style="color:#FF0000">*</span>)
					<!-- END: required -->
					</td>
					<td>
					<!-- BEGIN: textbox -->
					<input class="form-control {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" style="width: 300px" />
					<!-- END: textbox -->
					<!-- BEGIN: date -->
					<input class="form-control txt datepicker {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" style="width:90px"/>
					<!-- END: date -->
					<!-- BEGIN: textarea --><textarea style="width:300px" rows="5" cols="70" name="custom_fields[{FIELD.field}]">{FIELD.value}</textarea>
					<!-- END: textarea -->
					<!-- BEGIN: editor -->
					{EDITOR}
					<!-- END: editor -->
					<!-- BEGIN: select -->
					<select class="form-control" name="custom_fields[{FIELD.field}]">
						<!-- BEGIN: loop -->
						<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
						<!-- END: loop -->
					</select>
					<!-- END: loopselect -->
					<!-- BEGIN: radio -->
					<label for="lb_{FIELD_CHOICES.id}"> <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
					<!-- END: radio -->
					<!-- BEGIN: checkbox -->
					<label for="lb_{FIELD_CHOICES.id}"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
					<!-- END: checkbox -->
					<!-- BEGIN: multiselect -->
					<select class="form-control" name="custom_fields[{FIELD.field}][]" multiple="multiple">
						<!-- BEGIN: loop -->
						<option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
						<!-- END: loop -->
					</select>
					<!-- END: multiselect -->
					</td>
				</tr>
				<!-- END: loop -->
			</tbody>
		</table>
		<!-- END: field -->
		<!-- BEGIN: changepass -->
		<table class="table table-striped table-bordered table-hover">
			<caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.edit_password_note} </caption>
			<colgroup>
				<col style="width:325px"/>
				<col />
			</colgroup>
			<tbody>
				<tr>
					<td> {LANG.password} </td>
					<td><input class="form-control" type="password" name="password1" autocomplete="off" value="{DATA.password1}" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.repassword} </td>
					<td><input class="form-control" type="password" name="password2" autocomplete="off" value="{DATA.password2}" style="width: 300px" /></td>
				</tr>
			</tbody>
		</table>
		<!-- END: changepass -->
	</div>
	<div class="text-center">
		<input class="btn btn-primary" type="submit" name="confirm" value="{LANG.edit_title}" />
	</div>
</form>
<br />
<script type="text/javascript">
//<![CDATA[
document.getElementById('form_user').setAttribute("autocomplete", "off");
$(function() {
	$('#form_user').validate({
		rules : {
			username : {
				minlength : 5
			}
		}
	});
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
//]]>
</script>
<!-- END: edit_user -->
<!-- END: main -->