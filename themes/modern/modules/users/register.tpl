<!-- BEGIN: main -->
<div id="users">
	<div class="page-header m-bottom">
		<h3>{LANG.register}</h3>
	</div>
	<p class="acenter">
		{DATA.info}
	</p>
	<form id="registerForm" action="{USER_REGISTER}" method="post" class="box-border-shadow content-box clearfix reg">
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.name} </label>
			</dt>
			<dd class="fr">
				<input class="input" name="full_name" value="{DATA.full_name}" maxlength="255" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.email} </label>
			</dt>
			<dd class="fr">
				<input class="input email required" name="email" value="{DATA.email}" id="nv_email_iavim" maxlength="100" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.account} </label>
			</dt>
			<dd class="fr">
				<input class="input required" name="username" value="{DATA.username}" id="nv_username_iavim" maxlength="{NICK_MAXLENGTH}" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.password} </label>
			</dt>
			<dd class="fr">
				<input class="input required password" name="password" value="{DATA.password}" id="nv_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.re_password} </label>
			</dt>
			<dd class="fr">
				<input class="input required password" name="re_password" value="{DATA.re_password}" id="nv_re_password_iavim" type="password" maxlength="{PASS_MAXLENGTH}" autocomplete="off"/>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.question} </label>
			</dt>
			<dd class="fr">
				<select name="question">
					<!-- BEGIN: frquestion -->
					<option value="{QUESTIONVALUE.qid}"{QUESTIONVALUE.selected}>{QUESTIONVALUE.title}</option>
					<!-- END: frquestion -->
				</select>
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.your_question} </label>
			</dt>
			<dd class="fr">
				<input class="input" name="your_question" value="{DATA.your_question}" />
			</dd>
		</dl>
		<dl class="clearfix">
			<dt class="fl">
				<label> {LANG.answer_your_question} </label>
			</dt>
			<dd class="fr">
				<input class="input required" name="answer" value="{DATA.answer}" />
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
		<!-- BEGIN: captcha -->
		<dl class="clearfix captcha ">
			<dt class="fl">
				<label> {LANG.captcha} </label>
			</dt>
			<dd class="fr">
				<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimg','nv_seccode_iavim');"/>
				<br/>
				<input name="nv_seccode" id="nv_seccode_iavim" class="required" maxlength="{GFX_MAXLENGTH}" />
			</dd>
		</dl>
		<!-- END: captcha -->
		<div id="accept" style="padding-top:20px">
			<p>
				<strong>{LANG.usage_terms}</strong>
			</p>
			<div id="accept_scoll" style="border:1px solid #ccc;">
				<div style="margin:10px;">
					{NV_SITETERMS}
				</div>
			</div>
		</div>
		<dl class="clearfix" style="padding-top:10px">
			<dt class="fl">
				<input class="fl required" name="agreecheck" type="checkbox" value="1"{DATA.agreecheck} />
			</dt>
			<dd>
				<strong>{LANG.accept}</strong>
			</dd>
		</dl>
		<input type="hidden" name="checkss" value="{DATA.checkss}" />
		<input id="submit" type="submit" class="button" value="{LANG.register}" />
		<!-- BEGIN: lostactivelink -->
		<a href="{LOSTACTIVELINK_SRC}">{LANG.resend_activelink}</a>
		<!-- END: lostactivelink -->
	</form>
</div>
<!-- END: main -->