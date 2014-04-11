<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<h2>{LANG.register}</h2>
<div class="m-bottom">
	<em class="fa fa-quote-left">&nbsp;</em> 
	{DATA.info} 
	<em class="fa fa-quote-right">&nbsp;</em>
</div>
<form id="registerForm" action="{USER_REGISTER}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<div class="form-group">
		<label for="full_name" class="col-sm-4 control-label">{LANG.name}:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" id="full_name" name="full_name" value="{DATA.full_name}" maxlength="255" />
		</div>
	</div>
	<div class="form-group">
		<label for="nv_email_iavim" class="col-sm-4 control-label">{LANG.email}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="email" class="email required form-control" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
		</div>
	</div>
	<div class="form-group">
		<label for="nv_username_iavim" class="col-sm-4 control-label">{LANG.account}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input class="required form-control" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
		</div>
	</div>
	<div class="form-group">
		<label for="nv_password_iavim" class="col-sm-4 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input class="form-control required password" name="password" value="{DATA.password}" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
		</div>
	</div>
	<div class="form-group">
		<label for="nv_re_password_iavim" class="col-sm-4 control-label">{LANG.re_password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input class="form-control required password" name="re_password" value="{DATA.re_password}" id="nv_re_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
		</div>
	</div>
	<div class="form-group">
		<label for="question" class="col-sm-4 control-label">{LANG.question}:</label>
		<div class="col-sm-8">
			<select name="question" id="question" class="form-control">
				<!-- BEGIN: frquestion -->
				<option value="{QUESTIONVALUE.qid}"{QUESTIONVALUE.selected}>{QUESTIONVALUE.title}</option>
				<!-- END: frquestion -->
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="your_question" class="col-sm-4 control-label">{LANG.your_question}:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="your_question" id="your_question" value="{DATA.your_question}" />
		</div>
	</div>
	<div class="form-group">
		<label for="answer" class="col-sm-4 control-label">{LANG.answer_your_question}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="text" class="form-control required" name="answer" id="answer" value="{DATA.answer}" />
		</div>
	</div>
	<!-- BEGIN: groups -->
	<div class="form-group">
		<label class="col-sm-4 control-label">{LANG.in_group}:</label>
		<div class="col-sm-8">
			<!-- BEGIN: list -->
			<label class="checkbox-inline">
				<input type="checkbox" value="{GROUP.id}" name="group[]"{GROUP.checked} /> {GROUP.title} 
			</label>
			<!-- END: list -->	
		</div>
	</div>
	<!-- END: groups -->
	<!-- BEGIN: field -->
	<!-- BEGIN: loop -->
	<div class="form-group">
		<label class="col-sm-4 control-label" data-toggle="tooltip" data-placement="right" title="{FIELD.description}">{FIELD.title}<!-- BEGIN: required --><span class="text-danger"> (*)</span><!-- END: required -->:</label>
		<div class="col-sm-8">
			<!-- BEGIN: textbox -->
			<input class="{FIELD.required} {FIELD.class} form-control" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}"/>
			<!-- END: textbox -->
			<!-- BEGIN: date -->
			<div class="input-group">
				<input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" id="custom_fields_{FIELD.field}" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" readonly="readonly">
				<span class="input-group-btn">
					<button class="btn btn-default" type="button" onclick="$('#custom_fields_{FIELD.field}').datepicker('show');"> <em class="fa fa-calendar">&nbsp;</em></button>
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
	<!-- BEGIN: captcha -->
	<div class="form-group">
		<label for="nv_seccode_iavim" class="col-sm-4 control-label">{LANG.captcha}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-4">
			<input type="text" name="nv_seccode" id="nv_seccode_iavim" class="required form-control" maxlength="{GFX_MAXLENGTH}" />
		</div>
		<div class="col-sm-4">
			<label class="control-label">
				<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimg','seccode_iavim');">&nbsp;</em>
			</label>
		</div>
	</div>
	<!-- END: captcha -->
	<div class="panel panel-default">
		<div class="panel-body pre-scrollable">
			<h4>{LANG.usage_terms}</h4>
			<hr />
			{NV_SITETERMS}
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<div class="checkbox">
				<label>
					<input class="required" type="checkbox" name="agreecheck" value="1"{DATA.agreecheck}/> 
					{LANG.accept}
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input id="submit" type="submit" class="btn btn-primary" value="{LANG.register}" />
		</div>
	</div>
	<!-- BEGIN: lostactivelink -->
	<div class="alert alert-info"><a href="{LOSTACTIVELINK_SRC}">{LANG.resend_activelink}</a></div>
	<!-- END: lostactivelink -->
</form>
<script type="text/javascript">
$(document).ready(function() {
	$(".datepicker").datepicker({
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		showOn: 'focus'
	});
});
</script>
<!-- END: main -->