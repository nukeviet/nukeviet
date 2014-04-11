<!-- BEGIN: main -->
<h2>{LANG.register}</h2>
<div class="m-bottom text-center">
	<img alt="{LANG.openid_register}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" />
</div>
<div class="text-center m-bottom">
	{DATA.info}
</div>
<form id="registerForm" action="{USER_REGISTER}" method="post" role="form" class="form-horizontal form-tooltip m-bottom">
	<div class="form-group">
		<label for="nv_username_iavim" class="col-sm-4 control-label">{LANG.account}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="text" class="required form-control" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
		</div>
	</div>
	<div class="form-group">
		<label for="nv_password_iavim" class="col-sm-4 control-label">{LANG.password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="password" class="required form-control password" name="password" value="{DATA.password}" id="nv_password_iavim" maxlength="{PASS_MAXLENGTH}" />
		</div>
	</div>
	<div class="form-group">
		<label for="nv_re_password_iavim" class="col-sm-4 control-label">{LANG.re_password}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-8">
			<input type="password" class="required form-control password" name="re_password" value="{DATA.re_password}" id="nv_re_password_iavim" maxlength="{PASS_MAXLENGTH}" />
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
			<input type="text" class="required form-control" name="answer" id="answer" value="{DATA.answer}" />
		</div>
	</div>
	<!-- BEGIN: captcha -->
	<div class="form-group">
		<label for="nv_seccode_iavim" class="col-sm-4 control-label">{LANG.captcha}<span class="text-danger"> (*)</span>:</label>
		<div class="col-sm-4">
			<input type="text" name="nv_seccode" id="nv_seccode_iavim" class="required form-control" maxlength="{GFX_MAXLENGTH}" />
		</div>
		<div class="col-sm-4">
			<label class="control-label">
				<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" class="nv-captcha"/>
				&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('vimg','nv_seccode_iavim');">&nbsp;</em>
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
					<input type="checkbox" name="agreecheck" value="1"{DATA.agreecheck}/> 
					{LANG.accept}
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-4 col-sm-8">
			<input type="hidden" name="checkss" value="{DATA.checkss}" />
			<input name="nv_redirect" value="{DATA.nv_redirect}" type="hidden" />
			<input id="submit" type="submit" class="btn btn-primary" value="{LANG.register}" />
		</div>
	</div>
</form>
<!-- END: main -->