<!-- BEGIN: main -->
<div id="users">
	<div class="page-header m-bottom">
		<h3>{LANG.lostpass_page_title}</h3>
	</div>
	<!-- BEGIN: step1 -->
	<div class="note m-bottom">
		<strong>{LANG.lostpass_info}</strong>
		<ol>
			<li>
				{LANG.lostpass_info1}
			</li>
			<li>
				{LANG.lostpass_info2}
			</li>
		</ol>
	</div>
	<form id="lostpassForm" class="box-border-shadow clearfix content-box" action="{FORM1_ACTION}" method="post">
		<h5> {DATA.info} </h5>
		<fieldset>
			<div class="clearfix rows">
				<label> {LANG.field} </label>
				<input class="required input" id="userField_iavim" name="userField" value="{DATA.userField}" maxlength="100" />
			</div>
			<div class="clearfix rows">
				<label> {LANG.captcha} </label>
				<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" /><img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimg','seccode_iavim');"/>
			</div>
			<div class="clearfix rows">
				<label> {LANG.retype_captcha} </label>
				<input class="required input" name="nv_seccode" id="seccode_iavim" maxlength="{GFX_MAXLENGTH}" />
			</div>
			<div class="clearfix rows">
				<label> &nbsp; </label>
				<input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="submit" value="{LANG.lostpass_submit}" class="button" />
			</div>

		</fieldset>
	</form>
	<!-- END: step1 -->
	<!-- BEGIN: step2 -->
	<form id="lostpassForm" class="box-border-shadow clearfix content-box" action="{FORM2_ACTION}" method="post">
		<p>
			<strong>{DATA.info}</strong>
		</p>
		<p>
			{LANG.lostpass_question}:
			<br/>
			<strong>{QUESTION}</strong>
		</p>
		<p>
			{LANG.answer_question}
			<br />
			<input class="required input" id="answer" name="answer" value="{DATA.answer}" maxlength="255" />
		</p><input type="hidden" name="userField" value="{DATA.userField}" /><input type="hidden" name="nv_seccode" value="{DATA.nv_seccode}" /><input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="hidden" name="send" value="1" /><input type="submit" value="{LANG.lostpass_submit}" class="button" />
	</form>
	<!-- END: step2 -->
</div>
<!-- END: main -->