<!-- BEGIN: main -->
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
            <div class="panel-body">
                <h2 class="text-center margin-bottom-lg">{LANG.confirm_password}</h2>
                <form action="{FORM_ACTION}" method="post" onsubmit="return confirmpass_validForm(this);" autocomplete="off" novalidate>
                    <div class="nv-info margin-bottom" data-default="{LANG.confirm_password_info}">{LANG.confirm_password_info}</div>
                    <div class="form-detail">
                        <div class="step1">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><em class="fa fa-key fa-lg"></em></span>
                                    <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="100" data-pattern="/^(.){1,}$/" onkeypress="validErrorHidden(this);" data-mess="">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center margin-bottom-lg">
                             <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
                            <button class="bsubmit btn btn-primary" type="submit">{LANG.confirm}</button>
                       	</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
<!-- BEGIN: pass_empty -->
<div class="centered">
    <div class="alert alert-danger margin-top-lg">{CHANGE_2STEP_NOTVALID}</div>
</div>
<!-- END: pass_empty -->