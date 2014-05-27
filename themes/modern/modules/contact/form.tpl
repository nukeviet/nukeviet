<!-- BEGIN: main -->
<div class="box-border">
	<div class="content-box">
		<!-- BEGIN: error -->
		<div class="err">
			{CONTENT.error}
		</div>
		<!-- END: error -->
		<!-- BEGIN: form -->
		<p>
			{LANG.note}
		</p>

		<form id="fcontact" method="post" action="{ACTION_FILE}" onsubmit="return sendcontact('{NV_GFX_NUM}');">
			<div class="contact-form box-border content-box">
				<p class="rows">
					{LANG.title}:
					<br/>
					<input type="text" maxlength="255" value="{CONTENT.ftitle}" id="ftitle" name="ftitle" class="input" />
				</p>
				<!-- BEGIN: iguest -->
				<p class="rows">
					{LANG.fullname}:
					<br/>
					<input type="text" maxlength="100" value="{CONTENT.fname}" id="fname" name="fname" class="input" />
				</p>
				<p class="rows">
					{LANG.email}:
					<br/>
					<input type="text" maxlength="60" value="{CONTENT.femail}" id="femail_iavim" name="femail" class="input" />
				</p>
				<!-- END: iguest -->
				<!-- BEGIN: iuser -->
				<p class="rows">
					{LANG.fullname}:
					<br/>
					<input type="text" maxlength="100" value="{CONTENT.fname}" id="fname" name="fname" class="input" disabled="disabled" />
				</p>
				<p class="rows">
					{LANG.email}:
					<br/>
					<input type="text" maxlength="60" value="{CONTENT.femail}" id="femail_iavim" name="femail" class="input" disabled="disabled" />
				</p>
				<!-- END: iuser -->
				<p class="rows">
					{LANG.phone}:
					<br/>
					<input type="text" maxlength="60" value="{CONTENT.fphone}" id="fphone" name="fphone" class="input" />
				</p>
				<p class="rows">
					{LANG.part}:
					<br/>
					<select class="sl2" id="fpart" name="fpart">
						<!-- BEGIN: select_option_loop -->
						<option value="{SELECT_VALUE}" {SELECTED}>{SELECT_NAME}</option>
						<!-- END: select_option_loop -->
					</select>
				</p>
				<p class="rows">
					{LANG.content}:
					<br/><textarea cols="4" rows="3" id="fcon" name="fcon" onkeyup="return nv_ismaxlength(this, 1000);">{CONTENT.fcon}</textarea>
				</p>
				<p class="rows">
					&nbsp;{LANG.captcha}:&nbsp;<img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" alt="{LANG.captcha}" id="vimg" />
					<img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','fcode_iavim');"/>
					<input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" class="input capcha" />
					<input type="hidden" name="checkss" value="{CHECKSS}" />&nbsp;
					<input type="reset" value="{LANG.reset}" class="button-2" />
					&nbsp;
					<input type="submit" value="{LANG.sendcontact}" id="btsend" name="btsend" class="button" />
				</p>
			</div>
		</form>
		<!-- END: form -->
	</div>
</div>
<!-- END: main -->