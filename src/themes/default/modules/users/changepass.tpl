<!-- BEGIN: main -->
<div class="changepass-cont">
    <div class="changepass">
        <p class="centered margin-bottom"><img class="logo" src="{LOGO}" alt="{SITE_NAME}" title="{SITE_NAME}" height="40"></p>
        <p class="text-center title">{LANG.change_pass}</p>
        <p class="alert alert-info">{CHANGEPASS_INFO}</p>
        <form action="{CHANGEPASS_FORM}" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
            <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
            <div class="form-detail userBlock">
                <!-- BEGIN: is_old_pass -->
                <div class="form-group">
                    <label for="nv_password" class="control-label col-md-10 text-normal">{LANG.pass_old}</label>
                    <div class="col-md-14">
                        <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_old}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.required}">
                    </div>
                </div>
                <!-- END: is_old_pass -->
                <div class="form-group">
                    <label for="new_password" class="control-label col-md-10 text-normal">{LANG.pass_new}</label>
                    <div class="col-md-14">
                        <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{PASSWORD_RULE}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="re_password" class="control-label col-md-10 text-normal">{LANG.pass_new_re}</label>
                    <div class="col-md-14">
                        <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.re_password_empty}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10">
                        <input type="hidden" name="checkss" value="{CHECKSS}" />
                    </div>
                    <div class="col-md-14">
                        <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                        <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                    </div>
                </div>
                <div class="form-group">
                    <hr />
                    <p class="text-center">
                        <button type="button" class="btn btn-default btn-sm active" data-toggle="{URL_LOGOUT}"><em class="icon-exit"></em>&nbsp;{LANG.logout_title}&nbsp;</button>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->