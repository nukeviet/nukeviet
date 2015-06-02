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
<form id="form_user" action="{FORM_ACTION}" method="post" enctype="multipart/form-data" class="form-inline">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td> {LANG.account} </td>
					<td style="width:10px"> (<span style="color:#FF0000">*</span>) </td>
					<td><input type="text" class="form-control required" value="{DATA.username}" name="username" id="username_iavim" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.email} </td>
					<td style="width:10px"> (<span style="color:#FF0000">*</span>) </td>
					<td><input type="text" class="form-control email required" value="{DATA.email}" name="email" id="email_iavim" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.password} </td>
					<td style="width:10px"> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="form-control required password" type="password" id="pass_iavim" name="password1" value="{DATA.password1}" style="width: 300px" /> <a href="javascript:void(0);" onclick="return nv_genpass();" class="btn btn-primary btn-xs">{LANG.random_password}</a></td>
				</tr>
				<tr>
					<td> {LANG.repassword} </td>
					<td style="width:10px"> (<span style="color:#FF0000">*</span>) </td>
					<td><input class="form-control required password" type="password" name="password2" value="{DATA.password2}" style="width: 300px" id="password2" /> <input id="methods" type="checkbox"> {LANG.show_password}</td>
				</tr>
				<tr>
					<td> {LANG.question} </td>
					<td style="width:10px">
						(<span style="color:#FF0000">*</span>)
					</td>
					<td><input class="form-control required" type="text" value="{DATA.question}" name="question" style="width: 300px" /></td>
				</tr>
				<tr>
					<td> {LANG.answer} </td>
					<td style="width:10px">
						(<span style="color:#FF0000">*</span>)
					</td>
					<td><input class="form-control required" type="text" value="{DATA.answer}" name="answer" style="width: 300px" /></td>
				</tr>
				<!-- BEGIN: name_show_0 -->
                <tr>
                    <td colspan="2"> {LANG.last_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.last_name}" name="last_name" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.first_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.first_name}" name="first_name" style="width: 300px" /></td>
                </tr>
                <!-- END: name_show_0 -->
				<!-- BEGIN: name_show_1 -->
                <tr>
                    <td colspan="2"> {LANG.first_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.first_name}" name="first_name" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.last_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.last_name}" name="last_name" style="width: 300px" /></td>
                </tr>
                <!-- END: name_show_1 -->
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
					<td><input type="file" name='photo' /></td>
				</tr>
				<tr>
					<td colspan="2"> {LANG.birthday} </td>
					<td><input name="birthday" id="birthday" class="form-control" value="{DATA.birthday}" style="width: 100px;" maxlength="10" type="text" />
				</tr>
				<tr>
					<td colspan="2"> {LANG.show_email} </td>
					<td><input type="checkbox" name="view_mail" value="1"{DATA.view_mail} /></td>
				</tr>
				<tr>
					<td style="vertical-align:top" colspan="2"> {LANG.sig} </td>
					<td><textarea name="sig" class="form-control" cols="70" rows="5" style="width: 300px">{DATA.sig}</textarea></td>
				</tr>
				<!-- BEGIN: group -->
				<tr>
					<td style="vertical-align:top" colspan="2"> {LANG.in_group} </td>
					<td>
						<div class="row checkbox">
							<!-- BEGIN: list -->
							<label class="col-sm-20">
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
					<input class="form-control datepicker {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" style="width: 100px" />
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
	</div>
	<div class="text-center">
		<input class="btn btn-primary" type="submit" name="confirm" value="{LANG.member_add}" />
	</div>
</form>
<script type="text/javascript">
	//<![CDATA[
	document.getElementById('form_user').setAttribute("autocomplete", "off");
	$(function() {
		$.toggleShowPassword({
		    field: '#password2',
		    control: '#methods'
		});

		$('#form_user').validate({
			rules : {
				username : {
					minlength : '{NV_UNICKMIN}',
					maxlength : '{NV_UNICKMAX}',
				},
				password : {
					minlength : '{NV_UPASSMAX}',
					maxlength : '{NV_UPASSMAX}',
				},
				password2 : {
					equalTo : "#pass_iavim"
				}
			}
		});
		$("#birthday").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true,
			yearRange: "-99:+0"
		});

		$(".datepicker").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_siteroot + "images/calendar.gif",
			buttonImageOnly : true,
		});
	});
	//]]>
</script>
<!-- END: edit_user -->
<!-- END: main -->