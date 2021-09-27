<!-- BEGIN: main -->
<div>
<form method="post" action="{ACTION_FILE}" data-toggle="feedback" novalidate<!-- BEGIN: captcha --> data-captcha="fcode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
        <div class="nv-info margin-bottom-lg" data-mess="{LANG.note}">{LANG.note}</div>
        <!-- BEGIN: cats -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-folder-open fa-lg fa-horizon">
                    </em>
                </span>
                <select class="form-control" name="fcat">
                    <!-- BEGIN: select_option_loop -->
                    <option value="{SELECTVALUE}">
                        {SELECTNAME}
                    </option>
                    <!-- END: select_option_loop -->
                </select>
            </div>
        </div>
        <!-- END: cats -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-file-text fa-lg fa-horizon">
                    </em>
                </span>
                <input type="text" maxlength="255" class="form-control required" value="{CONTENT.ftitle}" name="ftitle" placeholder="{LANG.title}" data-pattern="/^(.){3,}$/" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_title}" />
            </div>
        </div>
        <!-- BEGIN: iguest -->
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><em class="fa fa-user fa-lg fa-horizon"></em></span>
                <input type="text" maxlength="100" value="" name="fname" class="form-control required" placeholder="{LANG.fullname}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_fullname}" data-callback="nv_uname_check" />
                <span class="input-group-addon pointer" title="{GLANG.loginsubmit}" data-toggle="loginForm"><em class="fa fa-sign-in fa-lg"></em></span>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-envelope fa-lg fa-horizon"></em>
                </span>
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
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <em class="fa fa-phone fa-lg fa-horizon"></em>
                </span>
                <input type="text" maxlength="60" value="{CONTENT.fphone}" name="fphone" class="form-control" placeholder="{LANG.phone}" />
            </div>
        </div>
        <div class="form-group">
            <div>
                <textarea cols="8" name="fcon" class="form-control required" maxlength="1000" placeholder="{LANG.content}" data-toggle="fb_validErrorHidden" data-mess="{LANG.error_content}"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="sendcopy" value="1" checked="checked" /><span>{LANG.sendcopy}</span></label>
        </div>
        <div class="text-center form-group">
            <input type="hidden" name="checkss" value="{CHECKSS}" />
            <input type="button" value="{LANG.reset}" class="btn btn-default" data-toggle="fb_validReset"/>
            <input type="submit" value="{LANG.sendcontact}" class="btn btn-primary"/>
        </div>
    </form>
</div>
<!-- END: main -->