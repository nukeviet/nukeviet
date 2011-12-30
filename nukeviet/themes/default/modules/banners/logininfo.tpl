<!-- BEGIN: logininfo -->
    <div id="client_login">
        <div class="lt"><label>{LOGIN_LANG}:</label>
            <input name="{LOGIN_INPUT_NAME}" id="{LOGIN_INPUT_NAME}" type="text" maxlength="{LOGIN_INPUT_MAXLENGTH}" />
        </div>
        <div class="lt"><label>{PASSWORD_LANG}:</label>
            <input name="{PASS_INPUT_NAME}" id="{PASS_INPUT_NAME}" type="password" maxlength="{PASS_INPUT_MAXLENGTH}" />
        </div>
        <!-- BEGIN: captcha -->
        <div class="lt"><label>{CAPTCHA_LANG}:</label>
            <input name="{CAPTCHA_NAME}" id="{CAPTCHA_NAME}" type="text" maxlength="{CAPTCHA_MAXLENGTH}" class="captcha" />
            <img id="vimg" alt="{CAPTCHA_LANG}" title="{CAPTCHA_LANG}" src="{CAPTCHA_IMG}" width="73" height="17" />
            <img alt="{CAPTCHA_REFRESH}" title="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" style="cursor:pointer;" onclick="nv_change_captcha('vimg','{CAPTCHA_NAME}');" />
        </div>
        <!-- END: captcha -->
        <div style="text-align: center;"><input type="button" value="{SUBMIT_LANG}" name="{SM_BUTTON_NAME}" id="{SM_BUTTON_NAME}" onclick="{SM_BUTTON_ONCLICK}" /></div>
    </div>
    <div class="module_info">{CLIENT_LOGIN_INFO}.</div>
<!-- END: logininfo -->