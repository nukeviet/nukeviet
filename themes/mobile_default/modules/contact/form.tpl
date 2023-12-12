<!-- BEGIN: main -->
<div>
    <form method="post" action="{ACTION_FILE}" data-toggle="feedback" data-precheck="feedback_precheck" novalidate<!-- BEGIN: captcha --> data-captcha="fcode"
        <!-- END: captcha -->
        <!-- BEGIN: recaptcha --> data-recaptcha2="1"
        <!-- END: recaptcha -->
        <!-- BEGIN: recaptcha3 --> data-recaptcha3="1"
        <!-- END: recaptcha3 -->>
        <div class="nv-info margin-bottom-lg" data-mess="{LANG.note}">{LANG.note}</div>
        <!-- BEGIN: cats -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-folder-open fa-lg fa-horizon"></em></span>
                <select class="form-control" name="fcat">
                    <option value="">{LANG.selectCat}</option>
                    <!-- BEGIN: optgroup -->
                    <optgroup label="{CATNAME}">
                        <!-- BEGIN: option -->
                        <option value="{OPT.val}">
                            {OPT.name}
                        </option>
                        <!-- END: option -->
                    </optgroup>
                    <!-- END: optgroup -->
                    <!-- BEGIN: option2 -->
                    <option value="{OPT.val}">
                        {OPT.name}
                    </option>
                    <!-- END: option2 -->
                </select>
            </div>
        </div>
        <!-- END: cats -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-file-text fa-lg fa-fw"></em></span>
                <input type="text" maxlength="255" class="form-control required" value="{CONTENT.ftitle}" name="ftitle" placeholder="{LANG.title}" data-pattern="/^(.){3,}$/" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_title}" />
            </div>
        </div>
        <!-- BEGIN: iguest -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg fa-fw"></em></span>
                <input type="text" maxlength="100" value="" name="fname" class="form-control required" placeholder="{LANG.fullname}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_fullname}" data-callback="nv_uname_check" />
                <span class="input-group-addon pointer" title="{GLANG.loginsubmit}" data-toggle="loginForm"><em class="fa fa-sign-in fa-lg"></em></span>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-envelope fa-lg fa-fw"></em></span>
                <input type="email" maxlength="60" value="" name="femail" class="form-control required" placeholder="{LANG.email}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_email}" />
            </div>
        </div>
        <!-- END: iguest -->
        <!-- BEGIN: iuser -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-user fa-lg fa-horizon">
                    </em>
                </span>
                <input type="text" maxlength="100" value="{CONTENT.fname}" name="fname" class="form-control required disabled" disabled="disabled" placeholder="{LANG.fullname}" data-pattern="/^(.){3,}$/" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_fullname}" />
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-envelope fa-fix fa-lg fa-horizon"></em>
                </span>
                <input type="email" maxlength="60" value="{CONTENT.femail}" name="femail" class="form-control required disabled" disabled="disabled" placeholder="{LANG.email}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_email}" />
            </div>
        </div>
        <!-- END: iuser -->
        <!-- BEGIN: feedback_phone -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-phone fa-lg fa-fw"></em></span>
                <input type="text" maxlength="60" value="{CONTENT.fphone}" name="fphone" class="form-control{CONTENT.phone_required}" placeholder="{LANG.phone}" data-pattern="/^(.){3,}$/" data-toggle="fb_validErrorHidden" data-mess="{LANG.phone_error}" />
            </div>
        </div>
        <!-- END: feedback_phone -->
        <!-- BEGIN: feedback_address -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-home fa-lg fa-fw"></em></span>
                <input type="text" maxlength="60" value="{CONTENT.faddress}" name="faddress" class="form-control{CONTENT.address_required}" placeholder="{LANG.address}" data-pattern="/^(.){3,}$/" data-toggle="fb_validErrorHidden" data-mess="{LANG.address_error}" />
            </div>
        </div>
        <!-- END: feedback_address -->
        <div class="form-group">
            <div>
                <textarea name="fcon" class="form-control required" style="height:130px" maxlength="1000" placeholder="{LANG.content}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_content}"></textarea>
            </div>
        </div>
        <!-- BEGIN: sendcopy -->
        <div class="checkbox">
            <label><input type="checkbox" style="margin-top:2px" name="sendcopy" value="1" checked="checked" /><span>{LANG.sendcopy}</span></label>
        </div>
        <!-- END: sendcopy -->
        <!-- BEGIN: confirm -->
        <div class="alert alert-info confirm" style="padding:0 10px">
            <!-- BEGIN: data_sending -->
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="form-control required" style="margin-top:2px" name="data_permission_confirm" value="1" data-mess="{GLANG.data_warning_error}" data-toggle="fb_errorHidden"> <small>{DATA_USAGE_CONFIRM}</small>
                </label>
            </div>
            <!-- END: data_sending -->

            <!-- BEGIN: antispam -->
            <div class="checkbox">
                <label>
                    <input type="checkbox" class="form-control required" style="margin-top:2px" name="antispam_confirm" value="1" data-mess="{GLANG.antispam_warning_error}" data-toggle="fb_errorHidden"> <small>{ANTISPAM_CONFIRM}</small>
                </label>
            </div>
            <!-- END: antispam -->
        </div>
        <!-- END: confirm -->
        <div class="text-center form-group">
            <input type="hidden" name="checkss" value="{CHECKSS}" />
            <input type="button" value="{LANG.reset}" class="btn btn-default" data-toggle="fb_validReset" />
            <input type="submit" value="{LANG.sendcontact}" class="btn btn-primary" />
        </div>
    </form>
</div>
<!-- END: main -->