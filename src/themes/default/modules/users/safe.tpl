<!-- BEGIN: main -->
<div class="row centered margin-top-lg">
    <div class="col-md-16">
        <div class="page panel panel-default bg-lavender box-shadow">
            <div class="panel-body">
                <div class="safe_active_info" {SHOW1}>
                    <h2 class="text-center margin-bottom-lg"><em class="fa fa-shield fa-lg margin-right text-danger"></em>{LANG.safe_mode}</h2>
                    {LANG.safe_active_info} <a href="#" data-toggle="safe_deactivate_show" data-hide-obj=".safe-deactivate" data-show-obj=".safe_active_info">{LANG.safe_deactivate}?</a>
                </div>
                <div class="safe-deactivate" {SHOW2}>
                    <h2 class="text-center margin-bottom-lg">{LANG.safe_deactivate}</h2>
                    <form action="{EDITINFO_FORM}" method="post" data-toggle="userLogin" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="{LANG.safe_deactivate_info}">{LANG.safe_deactivate_info}</div>
                        <div class="form-detail">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                                    <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                                    <input type="text" class="required form-control" placeholder="{LANG.safe_key}" value="" name="safe_key" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.required}">
                                    <span class="input-group-btn"><input type="button" value="{LANG.safe_resendkey}" class="safekeySend btn btn-warning" data-toggle="safekeySend" /></span>
                                </div>
                            </div>

                            <div class="text-center margin-bottom-lg">
                                <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                <input type="hidden" name="type" value="safe_deactivate" />
                                <input type="hidden" name="nv_redirect" value="{NV_REDIRECT}">
                                <button class="bsubmit btn btn-primary" type="submit">{LANG.editinfo_confirm}</button>
                                <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <ul class="nav navbar-nav">
            <!-- BEGIN: navbar -->
            <li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li><!-- END: navbar -->
        </ul>
    </div>
</div>
<!-- END: main -->
