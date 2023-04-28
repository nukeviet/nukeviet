<!-- BEGIN: main -->
<form id="sendmailForm" action="{SENDMAIL.action}" method="post" class="margin-lg" role="form" data-toggle="newsSendMail" <!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
    <div class="form-horizontal">
        <div class="form-group">
            <label for="friend_email" class="col-sm-8 control-label">{LANG.sendmail_email}<em>*</em></label>
            <div class="col-sm-16">
                <input type="text" id="friend_email" name="friend_email" value="" class="form-control" maxlength="100" data-error="{LANG.sendmail_err_mail}" />
            </div>
        </div>

        <div class="form-group">
            <label for="your_name" class="col-sm-8 control-label">{LANG.sendmail_name}<em>*</em></label>
            <div class="col-sm-16">
                <input id="your_name" type="text" name="your_name" value="{SENDMAIL.your_name}" class="form-control" maxlength="100" data-error="{LANG.sendmail_err_name}" />
            </div>
        </div>

        <!-- BEGIN: sender_is_user -->
        <div class="form-group">
            <label for="your_email" class="col-sm-8 control-label">{LANG.sendmail_youremail}</label>
            <div class="col-sm-16">
                <input type="email" id="your_email" name="your_email" value="{SENDMAIL.your_email}" class="form-control" maxlength="100" readonly="readonly" />
            </div>
        </div>

        <div class="form-group">
            <label for="your_message" class="col-sm-8 control-label">{LANG.sendmail_content}</label>
            <div class="col-sm-16">
                <textarea id="your_message" name="your_message" class="form-control" maxlength="500"></textarea>
            </div>
        </div>
        <!-- END: sender_is_user -->
    </div>
    <!-- BEGIN: confirm -->
    <div class="alert alert-info confirm" style="padding:0 10px">
        <!-- BEGIN: data_sending -->
        <div class="checkbox">
            <label>
                <input type="checkbox" class="form-control" style="margin-top:2px" name="data_permission_confirm" value="1" data-error="{GLANG.data_warning_error}"> <small>{DATA_USAGE_CONFIRM}</small>
            </label>
        </div>
        <!-- END: data_sending -->

        <!-- BEGIN: antispam -->
        <div class="checkbox">
            <label>
                <input type="checkbox" class="form-control" style="margin-top:2px" name="antispam_confirm" value="1" data-error="{GLANG.antispam_warning_error}"> <small>{ANTISPAM_CONFIRM}</small>
            </label>
        </div>
        <!-- END: antispam -->
    </div>
    <!-- END: confirm -->

    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-16 col-sm-push-8">
                <input type="hidden" name="checkss" value="{SENDMAIL.checkss}" />
                <input type="hidden" name="send" value="1" />
                <input type="submit" value="{LANG.sendmail_submit}" class="btn btn-primary" />
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->