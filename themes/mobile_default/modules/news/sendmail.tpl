<!-- BEGIN: main -->
<form id="sendmailForm" action="{SENDMAIL.action}" method="post" class="form-horizontal margin-lg" role="form" onsubmit="newsSendMail(event,this)"<!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="form-group">
        <label for="friend_email" class="col-sm-8 control-label">{LANG.sendmail_email}<em>*</em></label>
        <div class="col-sm-16">
            <input type="text" id="friend_email" name="friend_email" value="" class="form-control" maxlength="100" data-error="{LANG.sendmail_err_mail}"/>
        </div>
    </div>

    <div class="form-group">
        <label for="your_name" class="col-sm-8 control-label">{LANG.sendmail_name}<em>*</em></label>
        <div class="col-sm-16">
            <input id="your_name" type="text" name="your_name" value="{SENDMAIL.your_name}" class="form-control" maxlength="100" data-error="{LANG.sendmail_err_name}"/>
        </div>
    </div>

    <!-- BEGIN: sender_is_user -->
    <div class="form-group">
        <label for="your_email" class="col-sm-8 control-label">{LANG.sendmail_youremail}</label>
        <div class="col-sm-16">
            <input type="email" id="your_email" name="your_email" value="{SENDMAIL.your_email}" class="form-control" maxlength="100" readonly="readonly"/>
        </div>
    </div>

    <div class="form-group">
        <label for="your_message" class="col-sm-8 control-label">{LANG.sendmail_content}</label>
        <div class="col-sm-16">
            <textarea id="your_message"  name="your_message" class="form-control" maxlength="500"></textarea>
        </div>
    </div>
    <!-- END: sender_is_user -->

    <!-- BEGIN: captcha -->
    <div class="form-group">
        <label for="nv_seccode" class="col-sm-8 control-label">{LANG.captcha}<em>*</em></label>
        <div class="col-sm-16">
            <input type="text" name="nv_seccode" id="nv_seccode" class="form-control" maxlength="{GFX_NUM}" autocomplete="off" style="width:100px;margin:4px 5px 0 0;float:left!important" data-error="{LANG.error_captcha}"/>
            <img class="captchaImg pull-left" alt="{N_CAPTCHA}" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha&t={NV_CURRENTTIME}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" />
            <a class="display-inline-block middle margin-left margin-top" href="javascript:void(0);" title="{CAPTCHA_REFRESH}" onclick="change_captcha('#nv_seccode');"><em class="fa fa-refresh"></em></a>
        </div>
    </div>
    <!-- END: captcha -->

    <!-- BEGIN: recaptcha -->
    <div class="form-group">
        <div class="col-sm-16 col-sm-push-8">
            <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="4" data-btnselector="[type=submit]"></div></div>
        </div>
    </div>
    <!-- END: recaptcha -->
    <div class="form-group">
        <div class="col-sm-16 col-sm-push-8">
            <input type="hidden" name="checkss" value="{SENDMAIL.checkss}" />
            <input type="hidden" name="send" value="1" />
            <input type="submit" value="{LANG.sendmail_submit}" class="btn btn-primary" />
            <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
        </div>
    </div>
</form>
<!-- END: main -->
