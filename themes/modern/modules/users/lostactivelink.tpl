<!-- BEGIN: main -->
<div id="users">
	<h2 class="line padding_0">{LANG.lostactive_pagetitle}</h2>
	<!-- BEGIN: step1 -->
	<ol id="info" style="padding-top:10px">
		<li>
			<strong>{LANG.lostactive_noactive}</strong>
		</li>
		<li>
			<span>-</span>{LANG.lostactive_info1}
		</li>
		<li>
			<span>-</span>{LANG.lostactive_info2}
		</li>
	</ol>
	<form id="lostpassForm" class="register1 clearfix" action="{FORM1_ACTION}" method="post">
		<div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
			<strong>{DATA.info}</strong>
		</div>
		<div class="clearfix rows">
			<label> {LANG.field} </label>
			<input class="required" id="userField_iavim" name="userField" value="{DATA.userField}" maxlength="100" />
		</div>
		<div class="clearfix rows">
			<label> {LANG.captcha} </label>
			<img id="vimg" alt="{N_CAPTCHA}" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
			<img src="{CAPTCHA_REFR_SRC}" class="refesh" alt="{CAPTCHA_REFRESH}" onclick="nv_change_captcha('vimg','seccode_iavim');"/>
		</div>
		<div class="clearfix rows">
			<label> {LANG.retype_captcha} </label>
			<input class="required" name="nv_seccode" id="seccode_iavim" maxlength="{GFX_MAXLENGTH}" />
		</div>
		<input type="hidden" name="checkss" value="{DATA.checkss}" />
		<input type="submit" value="{LANG.lostactivelink_submit}" class="submit" />
	</form>
	<!-- END: step1 -->
	<!-- BEGIN: step2 -->
	<form id="lostpassForm" class="register1" action="{FORM2_ACTION}" method="post">
		<div class="info padding_0" style="padding-top:10px;padding-bottom:10px;">
			<strong>{DATA.info}</strong>
		</div>
		<div class="info" style="padding-top:10px;padding-bottom:10px;">
			{LANG.lostpass_question}:
			<br />
			<strong>{QUESTION}</strong>
		</div>
		<div class="clearfix rows">
			<label> {LANG.answer_question} </label>
			<input class="required" id="answer" name="answer" value="{DATA.answer}" maxlength="255" />
		</div>
		<input type="hidden" name="userField" value="{DATA.userField}" />
		<input type="hidden" name="nv_seccode" value="{DATA.nv_seccode}" />
		<input type="hidden" name="checkss" value="{DATA.checkss}" />
		<input type="hidden" name="send" value="1" />
		<input type="submit" value="{LANG.lostactivelink_submit}" class="submit" />
	</form>
	<!-- END: step2 -->
</div>
<!-- END: main -->