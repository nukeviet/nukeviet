<!-- BEGIN: main -->
<div class="contact">
    <!-- BEGIN: error -->
    <div class="err">
        {CONTENT.error}
    </div>
    <!-- END: error -->
    <!-- BEGIN: form -->
    <div class="title">
        {LANG.note}
    </div>
    <form id="fcontact" method="post" action="{ACTION_FILE}" onsubmit="return sendcontact('{NV_GFX_NUM}');">
        <div class="content clearfix">
            <div class="ftitle">
                <label for="ftitle">
                    {LANG.title}:
                </label>
                <input type="text" maxlength="255" value="{CONTENT.ftitle}" id="ftitle" name="ftitle" class="txtInput" />
            </div>
            <!-- BEGIN: iguest -->
            <div class="fname">
                <label for="fname">
                    {LANG.fullname}:
                </label>
                <input type="text" maxlength="100" value="{CONTENT.fname}" id="fname" name="fname" class="txtInput" />
            </div>
            <div class="femail">
                <label for="femail_iavim">
                    {LANG.email}:
                </label>
                <input type="text" maxlength="60" value="{CONTENT.femail}" id="femail_iavim" name="femail" class="txtInput" />
            </div>
            <!-- END: iguest -->
            <!-- BEGIN: iuser -->
            <div class="fname">
                <label for="fname">
                    {LANG.fullname}:
                </label>
                <input type="text" maxlength="100" value="{CONTENT.fname}" id="fname" name="fname" class="txtInput" disabled="disabled" />
            </div>
            <div class="femail">
                <label for="femail">
                    {LANG.email}:
                </label>
                <input type="text" maxlength="60" value="{CONTENT.femail}" id="femail_iavim" name="femail" class="txtInput" disabled="disabled" />
            </div>
            <!-- END: iuser -->
            <div class="fphone">
                <label for="fphone">
                    {LANG.phone}:
                </label>
                <input type="text" maxlength="60" value="{CONTENT.fphone}" id="fphone" name="fphone" class="txtInput" />
            </div>
            <div>
                <label for="fpart">
                    {LANG.part}:
                </label>
                <select class="sl2" id="fpart" name="fpart">
                    <!-- BEGIN: select_option_loop -->
                    <option value="{SELECT_VALUE}" {SELECTED}>{SELECT_NAME}</option>
                    <!-- END: select_option_loop -->
                </select>
            </div>
            <div>
                <label for="fcon">
                    {LANG.content}:
                </label>
                <textarea cols="8" rows="8" id="fcon" name="fcon" style="overflow: auto;" class="txttextArea" onkeyup="return nv_ismaxlength(this, 1000);">{CONTENT.fcon}</textarea>
            </div>
            <div class="capcha1">
                <label for="fcode_iavim">
                    {LANG.captcha}:
                </label>
                <input type="text" maxlength="6" value="" id="fcode_iavim" name="fcode" class="txtCaptcha" />
                <img height="22" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" title="{LANG.captcha}" alt="{LANG.captcha}" id="vimg" />
                <img alt="{CAPTCHA_REFRESH}" src="{CAPTCHA_REFR_SRC}" width="16" height="16" class="refresh" onclick="nv_change_captcha('vimg','fcode_iavim');"/>
            </div>
            <div style="clear:both"></div>
            <div style=" padding-top:5px;">
                <input type="hidden" name="checkss" value="{CHECKSS}" /><input type="reset" value="{LANG.reset}" class="bt1" />&nbsp;&nbsp;<input type="submit" value="{LANG.sendcontact}" id="btsend" name="btsend" class="bt1" />
            </div>
        </div>
    </form>
    <!-- END: form -->
    <!-- BEGIN: bodytext -->
    <div class="hometext">
        {CONTENT.bodytext}
    </div>
    <!-- END: bodytext -->
</div>
<!-- END: main -->
