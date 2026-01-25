<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script src="{NV_STATIC_URL}themes/{TEMPLATE_JS}/js/users.passkey.js"></script>
<!-- BEGIN: changepass_request2 -->
<div class="alert alert-danger">
    {CHANGEPASS_INFO}
</div>
<!-- END: changepass_request2 -->
<div class="page">
    <ul class="nav nav-tabs margin-bottom">
        <li role="presentation" class="dropdown active"><a id="myTabEl" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-controls="funcList" aria-expanded="false"><span class="caret"></span></a>
            <ul id="funcList" class="dropdown-menu" aria-labelledby="myTabEl">
                <li class="{BASIC_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/basic" href="#edit_basic">{LANG.edit_basic}</a></li>
                <!-- BEGIN: edit_avatar -->
                <li class="{AVATAR_ACTIVE}"><a data-toggle="tab" href="#edit_avatar" data-location="{EDITINFO_FORM}/avatar">{LANG.edit_avatar}</a></li>
                <!-- END: edit_avatar -->
                <!-- BEGIN: edit_username -->
                <li class="{USERNAME_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/username" href="#edit_username">{LANG.edit_login}</a></li>
                <!-- END: edit_username -->
                <!-- BEGIN: edit_email -->
                <li class="{EMAIL_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/email" href="#edit_email">{LANG.edit_email}</a></li>
                <!-- END: edit_email -->
                <!-- BEGIN: edit_password -->
                <li class="{PASSWORD_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/password" href="#edit_password">{LANG.edit_password}</a></li>
                <!-- END: edit_password -->
                <!-- BEGIN: edit_passkey_tab -->
                <li class="{PASSKEY_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/passkey" href="#edit_passkey">{LANG.edit_passkey}</a></li>
                <!-- END: edit_passkey_tab -->
                <!-- BEGIN: edit_passkey_linked -->
                <li><a href="{URL_CONFIRM_PASS_PASSKEY}">{LANG.edit_passkey}</a></li>
                <!-- END: edit_passkey_linked -->
                <!-- BEGIN: edit_langinterface -->
                <li class="{LANGINTERFACE_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/langinterface" href="#edit_langinterface">{GLANG.langinterface}</a></li>
                <!-- END: edit_langinterface -->
                <!-- BEGIN: 2step -->
                <li><a href="{URL_2STEP}">{LANG.2step_status}</a></li>
                <!-- END: 2step -->
                <!-- BEGIN: edit_question -->
                <li class="{QUESTION_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/question" href="#edit_question">{LANG.edit_question}</a></li>
                <!-- END: edit_question -->
                <!-- BEGIN: edit_openid -->
                <li class="{OPENID_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/openid" href="#edit_openid">{LANG.openid_administrator}</a></li>
                <!-- END: edit_openid -->
                <!-- BEGIN: edit_group -->
                <li class="{GROUP_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/group" href="#edit_group">{LANG.group}</a></li>
                <!-- END: edit_group -->
                <!-- BEGIN: edit_others -->
                <li class="{OTHERS_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/others" href="#edit_others">{LANG.edit_others}</a></li>
                <!-- END: edit_others -->
                <!-- BEGIN: edit_safemode -->
                <li class="{SAFEMODE_ACTIVE}"><a data-toggle="tab" data-location="{EDITINFO_FORM}/safemode" href="#edit_safemode">{LANG.safe_mode}</a></li>
                <!-- END: edit_safemode -->
                <!-- BEGIN: securityprivacy -->
                <li><a href="{URL_SECURITY_PRIVACY}">{LANG.security_privacy}</a></li>
                <!-- END: securityprivacy -->
            </ul></li>
    </ul>
    <div class="tab-content margin-bottom-lg">
        <div id="edit_basic" class="well-lg tab-pane fade {TAB_BASIC_ACTIVE}">
            <form action="{EDITINFO_FORM}/basic" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <!-- BEGIN: name_show_0 -->
                    <!-- BEGIN: show_last_name-->
                    <div class="form-group">
                        <label for="last_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_last_name -->
                    <!-- BEGIN: show_first_name -->
                    <div class="form-group">
                        <label for="first_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_first_name -->
                    <!-- END: name_show_0 -->
                    <!-- BEGIN: name_show_1 -->
                    <!-- BEGIN: show_first_name -->
                    <div class="form-group">
                        <label for="first_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_first_name -->
                    <!-- BEGIN: show_last_name-->
                    <div class="form-group">
                        <label for="last_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_last_name -->
                    <!-- END: name_show_1 -->
                    <!-- BEGIN: show_gender -->
                    <div class="form-group">
                        <label for="gender" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-4">
                            <select class="form-control {FIELD.required} {FIELD.class}" name="gender" data-toggle="validErrorHidden" data-event="change" data-parents="5" data-mess="">
                                <!-- BEGIN: gender -->
                                <option value="{GENDER.key}"{GENDER.sel}>{GENDER.title}</option>
                                <!-- END: gender -->
                            </select>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_gender -->
                    <!-- BEGIN: show_birthday -->
                    <div class="form-group">
                        <label for="birthday" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control calendar-icon datepicker {FIELD.required} {FIELD.class}" name="birthday" value="{FIELD.value}" readonly="readonly" style="background-color: #fff" data-focus="datepickerShow" data-mess="">
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_birthday -->
                    <!-- BEGIN: show_sig -->
                    <div class="form-group">
                        <label for="birthday" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="sig" data-toggle="validErrorHidden" data-event="keypress" data-mess="">{FIELD.value}</textarea>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_sig -->
                    <div class="form-group">
                        <div class="col-md-12 col-md-push-6">
                            <label class="check-box"><input type="checkbox" name="view_mail" style="margin-top: 0; background-color: #fff" value="1" {DATA.view_mail}/> {LANG.showmail}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- BEGIN: tab_edit_avatar -->
        <div id="edit_avatar" class="well-lg tab-pane fade {TAB_AVATAR_ACTIVE}">
            <div class="margin-bottom">
                <img id="myavatar" class="img-thumbnail bg-gainsboro" src="{DATA.photo}" width="{DATA.photoWidth}" height="{DATA.photoHeight}" data-default="{AVATAR_DEFAULT}" />
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-xs margin-right-sm" data-toggle="changeAvatar" data-url="{URL_AVATAR}">{LANG.change_avatar}</button>
                <button type="button" class="btn btn-danger btn-xs" id="delavatar" data-toggle="deleteAvatar" data-obj="#myavatar" data-ss="{DATA.checkss}"{DATA.imgDisabled}>{GLANG.delete}</button>
            </div>
        </div>
        <!-- END: tab_edit_avatar -->
        <!-- BEGIN: tab_edit_username -->
        <div id="edit_username" class="well-lg tab-pane fade {TAB_USERNAME_ACTIVE}">
            <!-- BEGIN: username_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changelogin_notvalid}
                <button type="button" class="btn btn-primary btn-xs" data-toggle="addpass">{LANG.add_pass}</button>
            </div>
            <!-- END: username_empty_pass -->
            <form action="{EDITINFO_FORM}/username" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="{LANG.edit_login_warning}">{LANG.edit_login_warning}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <div class="col-md-6 text-right">{LANG.currentlogin}:</div>
                        <div class="col-md-12">
                            <strong>{DATA.username}</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username" class="control-label col-md-6 text-normal">{LANG.newlogin}</label>
                        <div class="col-md-12">
                            <input type="text" class="required form-control" placeholder="{LANG.newlogin}" value="" name="username" maxlength="{NICK_MAXLENGTH}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{USERNAME_RULE}" data-callback="login_check" data-minlength="{NICK_MINLENGTH}" data-type="{LOGINTYPE}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <!-- BEGIN: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-12 col-md-offset-6">
                            <div class="checkbox">
                                <label><input type="checkbox" class="form-control" name="forcedrelogin" value="1" style="margin-top:2px"> {LANG.forcedrelogin}</label>
                            </div>
                        </div>
                    </div>
                    <!-- END: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_username -->
        <!-- BEGIN: tab_edit_email -->
        <div id="edit_email" class="well-lg tab-pane fade {TAB_EMAIL_ACTIVE}">
            <!-- BEGIN: email_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changeemail_notvalid}
                <button type="button" class="btn btn-primary btn-xs" data-toggle="addpass">{LANG.add_pass}</button>
            </div>
            <!-- END: email_empty_pass -->
            <form action="{EDITINFO_FORM}/email" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" data-toggle="changemail_validForm" autocomplete="off" novalidate>
                <div class="nv-info" style="margin-bottom:30px">{LANG.edit_email_warning}</div>
                <div class="nv-info-default hidden">{LANG.edit_email_warning}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <div class="col-md-12 col-md-push-6">
                            {LANG.currentemail}: <strong>{DATA.email}</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-md-6 text-normal">{LANG.newemail}</label>
                        <div class="col-md-12">
                            <input type="email" class="required form-control" placeholder="{LANG.newemail}" value="" name="email" maxlength="100" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.email_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verifykey" class="control-label col-md-6 text-normal">{LANG.verifykey}</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{LANG.verifykey}" value="" name="verifykey" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.verifykey_empty}"> <span class="input-group-btn">
                                    <button type="button" class="send-bt btn btn-warning pointer" data-toggle="verkeySend">{LANG.verifykey_send}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- BEGIN: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-12 col-md-offset-6">
                            <div class="checkbox">
                                <label><input type="checkbox" class="form-control" name="forcedrelogin" value="1" style="margin-top:2px"> {LANG.forcedrelogin}</label>
                            </div>
                        </div>
                    </div>
                    <!-- END: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" /> <input type="hidden" name="vsend" value="0" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_email -->
        <!-- BEGIN: tab_edit_password -->
        <div id="edit_password" class="well-lg tab-pane fade {TAB_PASSWORD_ACTIVE}">
            <form action="{EDITINFO_FORM}/password" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <!-- BEGIN: is_old_pass -->
                    <div class="form-group">
                        <label for="nv_password" class="control-label col-md-6 text-normal">{LANG.pass_old}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_old}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.required}">
                        </div>
                    </div>
                    <!-- END: is_old_pass -->
                    <div class="form-group">
                        <label for="new_password" class="control-label col-md-6 text-normal">{LANG.pass_new}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{PASSWORD_RULE}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="re_password" class="control-label col-md-6 text-normal">{LANG.pass_new_re}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.re_password_empty}">
                        </div>
                    </div>
                    <!-- BEGIN: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-12 col-md-offset-6">
                            <div class="checkbox">
                                <label><input type="checkbox" class="form-control" name="forcedrelogin" value="1" style="margin-top:2px"> {LANG.forcedrelogin}</label>
                            </div>
                        </div>
                    </div>
                    <!-- END: forcedrelogin -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_password -->
        <!-- BEGIN: tab_edit_passkey -->
        <div id="edit_passkey" class="well-lg tab-pane fade {TAB_PASSKEY_ACTIVE}">
            <!-- BEGIN: pass_confirmed -->
            <form action="{EDITINFO_FORM}/passkey" id="passkey-form" method="post" autocomplete="off" novalidate>
                <input type="hidden" name="checkss" value="{DATA.checkss}">
                <!-- BEGIN: no_loginkey -->
                <div class="panel panel-default">
                    <div class="panel-body text-center" data-toggle="ctn">
                        <div class="margin-bottom">
                            <i class="fa fa-key fa-4x" aria-hidden="true"></i>
                        </div>
                        <h2 class="margin-bottom">{LANG.passkey_login_create}</h2>
                        <p>{LANG.passkey_login_create_body}.</p>
                        <button class="btn btn-primary hidden" type="button" data-toggle="passkey-add" data-enable-login="1"><i class="fa fa-plus" aria-hidden="true" data-icon="fa-plus"></i> {LANG.passkey_add}</button>
                        <div class="text-danger hidden" data-toggle="passkey-not-supported">{LANG.passkey_not_supported}</div>
                        <div class="text-danger margin-top hidden" data-toggle="error"></div>
                    </div>
                </div>
                <!-- END: no_loginkey -->
                <!-- BEGIN: loginkeys -->
                <div class="text-danger margin-bottom hidden" data-toggle="passkey-not-supported">{LANG.passkey_not_supported}</div>
                <p>{LANG.passkey_login_create_body}.</p>
                <div class="panel panel-default" data-toggle="ctn">
                    <div class="panel-heading usr-flex-header">
                        <div class="h3">
                            <strong>{LANG.passkey_list}</strong>
                        </div>
                        <button class="btn btn-primary hidden" type="button" data-toggle="passkey-add" data-enable-login="1"><i class="fa fa-plus" aria-hidden="true" data-icon="fa-plus"></i> {LANG.passkey_add}</button>
                    </div>
                    <div class="panel-body text-danger hidden" data-toggle="error"></div>
                    <ul class="list-group">
                        <!-- BEGIN: loop -->
                        <li class="list-group-item">
                            <div class="usr-flex usr-justify-between usr-gap-2">
                                <div>
                                    <strong><i class="fa fa-key" aria-hidden="true"></i> {PUBLICKEY.nickname}</strong> <!-- BEGIN: this_client --><span class="label label-default">{LANG.passkey_seenthis}</span><!-- END: this_client -->
                                    <div class="margin-top-sm text-muted">
                                        {LANG.passkey_created_at}: {PUBLICKEY.created_at} |
                                        {LANG.passkey_last_used_at}: {PUBLICKEY.last_used_at}.
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-default" data-toggle="edit" data-id="{PUBLICKEY.id}" data-nickname="{PUBLICKEY.nickname}"><i class="fa fa-pencil" data-icon="fa-pencil" aria-hidden="true"></i> {GLANG.edit}</button>
                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="del" data-id="{PUBLICKEY.id}"><i class="fa fa-trash" data-icon="fa-trash" aria-hidden="true"></i> {GLANG.delete}</button>
                                </div>
                            </div>
                        </li>
                        <!-- END: loop -->
                    </ul>
                </div>
                <!-- END: loginkeys -->
            </form>
            <!-- START FORFOOTER -->
            <div class="modal fade" tabindex="-1" role="dialog" data-toggle="md-complete-passkey">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                            <div class="modal-title h3"><strong>{LANG.passkey_created}</strong></div>
                        </div>
                        <div class="modal-body">
                            {LANG.passkey_created_body}
                        </div>
                        <div class="modal-footer">
                            <div class="text-center">
                                <button type="button" class="btn btn-success" data-toggle="passkey-reload">{GLANG.complete}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" data-toggle="md-edit-passkey">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                            <div class="modal-title h3"><strong>{LANG.passkey_nickname_edit}</strong></div>
                        </div>
                        <div class="modal-body">
                            <form action="{EDITINFO_FORM}/passkey" id="passkey-form-nickname" method="post" autocomplete="off" novalidate>
                                <input type="hidden" name="checkss" value="{DATA.checkss}">
                                <input type="hidden" name="id" value="0">
                                <div class="form-group">
                                    <label for="element_nickname" class="control-label">{LANG.passkey_nickname} <span class="text-danger">(*)</span>:</label>
                                    <input type="text" class="form-control" name="nickname" data-nickname="" id="element_nickname" value="" maxlength="100">
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" data-icon="fa-floppy-o" aria-hidden="true"></i> {GLANG.save}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FORFOOTER -->
            <!-- END: pass_confirmed -->
        </div>
        <!-- END: tab_edit_passkey -->
        <!-- BEGIN: tab_edit_langinterface -->
        <div id="edit_langinterface" class="well-lg tab-pane fade {TAB_LANGINTERFACE_ACTIVE}">
            <form action="{EDITINFO_FORM}/langinterface" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm">
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <div class="form-group">
                        <label for="new_langinterface" class="control-label col-md-6 text-normal">{GLANG.langinterface}</label>
                        <div class="col-md-12">
                            <select name="langinterface" class="form-control" style="width:fit-content;">
                                <option value="">{LANG.bydatalang}</option>
                                <!-- BEGIN: lang_option -->
                                <option value="{OPTION.val}"{OPTION.sel}>{OPTION.name}</option>
                                <!-- END: lang_option -->
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{GLANG.submit}" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_langinterface -->
        <!-- BEGIN: tab_edit_question -->
        <div id="edit_question" class="well-lg tab-pane fade {TAB_QUESTION_ACTIVE}">
            <!-- BEGIN: question_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changequestion_notvalid}
                <button type="button" class="btn btn-primary btn-xs" data-toggle="addpass">{LANG.add_pass}</button>
            </div>
            <!-- END: question_empty_pass -->
            <form action="{EDITINFO_FORM}/question" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info" style="margin-bottom:30px" data-default="{LANG.edit_question_warning}">{LANG.edit_question_warning}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <label for="nv_password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <!-- BEGIN: show_question -->
                    <div class="form-group rel">
                        <label for="question" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="" name="question" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.your_question_empty}">
                                <div class="input-group-btn" role="group">
                                    <button type="button" class="btn btn-default pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <!-- BEGIN: frquestion -->
                                        <li><a href="#" data-toggle="addQuestion">{QUESTION}</a></li>
                                        <!-- END: frquestion -->
                                    </ul>
                                </div>
                            </div>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_question -->
                    <!-- BEGIN: show_answer -->
                    <div class="form-group">
                        <label for="answer" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="" name="answer" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.answer_empty}">
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: show_answer -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_question -->
        <!-- BEGIN: tab_edit_openid -->
        <div id="edit_openid" class="tab-pane fade {TAB_OPENID_ACTIVE}">
            <!-- BEGIN: openid_not_empty -->
            <form action="{EDITINFO_FORM}/openid" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <table class="table table-bordered table-striped table-hover">
                        <colgroup>
                            <col style="width: 20px" />
                        </colgroup>
                        <thead>
                            <tr class="bg-lavender">
                                <td>
                                    <!-- BEGIN: checkAll -->
                                    <input type="checkbox" class="checkAll" data-toggle="checkAll" />
                                <!-- END: checkAll -->
                                </td>
                                <td class="text-uppercase">{LANG.openid_server}</td>
                                <td class="text-uppercase">{LANG.openid_email_or_id}</td>
                            </tr>
                        </thead>
                        <!-- BEGIN: button -->
                        <tfoot>
                            <tr>
                                <td colspan="3"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input id="submit" type="submit" class="btn btn-primary" value="{LANG.openid_del}" /></td>
                            </tr>
                        </tfoot>
                        <!-- END: button -->
                        <tbody>
                            <!-- BEGIN: openid_list -->
                            <tr>
                                <th class="text-center">
                                    <!-- BEGIN: is_act -->
                                    <input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}" class="checkSingle" data-toggle="checkSingle" {OPENID_LIST.disabled} />
                                <!-- END: is_act -->
                                    <!-- BEGIN: disabled -->
                                    <em class="fa fa-shield text-danger pointer" title="{LANG.openid_default}"></em>
                                <!-- END: disabled -->
                                </th>
                                <td>{OPENID_LIST.openid}</td>
                                <td>{OPENID_LIST.email_or_id}</td>
                            </tr>
                            <!-- END: openid_list -->
                        </tbody>
                    </table>
                </div>
            </form>
            <!-- END: openid_not_empty -->
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender text-center">
                    <div class="margin-bottom-lg">{LANG.openid_add_new}</div>
                    <div>
                        <!-- BEGIN: server -->
                        <a href="{OPENID.href}" class="openid margin-right" data-toggle="openID_load"><img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" />{OPENID.title}</a>
                        <!-- END: server -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_openid -->
        <!-- BEGIN: tab_edit_group -->
        <div id="edit_group" class="tab-pane fade {TAB_GROUP_ACTIVE}">
            <form action="{EDITINFO_FORM}/group" method="post" role="form" class="form-horizontal" data-toggle="edit_group_submit" data-old="{DATA.old_in_groups}" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <table class="table table-bordered table-striped table-hover">
                        <colgroup>
                            <col width="20" />
                            <col width="240" />
                            <col />
                            <col width="30" />
                        </colgroup>
                        <thead>
                            <tr class="bg-lavender">
                                <td>
                                    <!-- BEGIN: checkAll -->
                                    <input type="checkbox" class="checkAll" data-toggle="checkAll" {CHECK_ALL_CHECKED} />
                                <!-- END: checkAll -->
                                </td>
                                <td class="text-uppercase">{LANG.group_name}</td>
                                <td class="text-uppercase">{LANG.group_description}</td>
                                <td class="text-uppercase"></td>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="4"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input id="submit" type="submit" class="btn btn-primary" value="{LANG.group_reg}" /></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- BEGIN: group_list -->
                            <tr>
                                <th class="text-center"><!-- BEGIN: is_checkbox --><input name="in_groups[]" type="checkbox" value="{GROUP_LIST.group_id}" class="checkSingle" data-toggle="checkSingle"{GROUP_LIST.checked}<!-- BEGIN: is_disable_checkbox --> disabled="disabled"/><input type="hidden" name="in_groups[]" value="{GROUP_LIST.group_id}"<!-- END: is_disable_checkbox -->/><!-- END: is_checkbox --></th>
                                <td><a class="pointer" data-toggle="modal" data-target="#modal-{GROUP_LIST.alias}"><strong>{GROUP_LIST.title}</strong><em class="show text-success">{GROUP_LIST.group_type_mess}</em></a>
                                <!-- BEGIN: is_leader -->
                                    <span class="text-danger"><em class="fa fa-users">&nbsp;</em><a href="{URL_IS_LEADER}" title="">{LANG.group_manage}</a></span>
                                <!-- END: is_leader --></td>
                                <td>{GROUP_LIST.description}</td>
                                <td class="text-right">
                                    <!-- BEGIN: if_not_joined --><i class="fa fa-power-off fa-lg text-muted" title="{GROUP_LIST.status_mess}"></i><!-- END: if_not_joined -->
                                    <!-- BEGIN: if_joined --><i class="fa fa-check fa-lg text-success" title="{GROUP_LIST.status_mess}"></i><!-- END: if_joined -->
                                    <!-- BEGIN: if_waited --><i class="fa fa-hourglass-half fa-lg text-warning" title="{GROUP_LIST.status_mess}"></i><!-- END: if_waited -->
                                </td>
                                <!-- START FORFOOTER -->
                                <div class="modal fade" id="modal-{GROUP_LIST.alias}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h2 class="modal-title">{GROUP_LIST.title}</h2>
                                                <div>{GROUP_LIST.description}</div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="clearfix margin-bottom-lg">
                                                    <div class="pull-left margin-right">
                                                        <img title="{GROUP_LIST.title}" src="{ASSETS_STATIC_URL}/images/pix.svg" width="80" height="80" style="background-image:url({GROUP_LIST.group_avatar});background-repeat:no-repeat;background-size:cover;" />
                                                    </div>
                                                    <p><strong>{LANG.group_type}: </strong>{GROUP_LIST.group_type_mess} ({GROUP_LIST.group_type_note})</p>
                                                    <p><strong>{LANG.group_exp_time}: </strong>{GROUP_LIST.exp}</p>
                                                    <p><strong>{LANG.group_userr}: </strong>{GROUP_LIST.numbers}</p>
                                                </div>
                                                {GROUP_LIST.content}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                            </tr>
                            <!-- END: group_list -->
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_group -->
        <!-- BEGIN: tab_edit_others -->
        <div id="edit_others" class="well-lg tab-pane fade {TAB_OTHERS_ACTIVE}">
            <form action="{EDITINFO_FORM}/others" method="post" role="form" class="form-horizontal" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="{GLANG.required}">{GLANG.required}</div>
                <div class="form-detail">
                    <!-- BEGIN: loop -->
                    <!-- BEGIN: textbox -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->/>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: textbox -->
                    <!-- BEGIN: date -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" data-toggle="validErrorHidden" data-event="keypress" data-focus="datepickerShow" data-mess="" />
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: date -->
                    <!-- BEGIN: textarea -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="custom_fields[{FIELD.field}]" data-toggle="validErrorHidden" data-event="keypress" data-mess="">{FIELD.value}</textarea>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: textarea -->
                    <!-- BEGIN: editor -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            {EDITOR}
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: editor -->
                    <!-- BEGIN: select -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" data-toggle="validErrorHidden" data-event="change" data-mess="">
                                <!-- BEGIN: loop -->
                                <option value="{FIELD_CHOICES.key}"{FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                                <!-- END: loop -->
                            </select>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: select -->
                    <!-- BEGIN: radio -->
                    <div class="form-group">
                        <label class="control-label col-md-6 {FIELD.required} text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <div class="radio-box {FIELD.required}" data-mess="">
                                <!-- BEGIN: loop -->
                                <label for="lb_{FIELD_CHOICES.id}" class="radio-box" style="vertical-align:middle"> <input type="radio" name="custom_fields[{FIELD.field}]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" data-toggle="validErrorHidden" data-event="click" data-parents="4"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br />
                                <!-- END: loop -->
                            </div>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: radio -->
                    <!-- BEGIN: checkbox -->
                    <div class="form-group">
                        <label class="control-label col-md-6 {FIELD.required} text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <div class="check-box {FIELD.required}" data-mess="">
                                <!-- BEGIN: loop -->
                                <label for="lb_{FIELD_CHOICES.id}" class="check-box" style="vertical-align:middle"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" style="margin-top: 0" data-toggle="validErrorHidden" data-event="click" data-parents="4"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br />
                                <!-- END: loop -->
                            </div>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: checkbox -->
                    <!-- BEGIN: multiselect -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} {FIELD.required} form-control" data-toggle="validErrorHidden" data-event="change" data-mess="">
                                <!-- BEGIN: loop -->
                                <option value="{FIELD_CHOICES.key}"{FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                                <!-- END: loop -->
                            </select>
                            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                        </div>
                    </div>
                    <!-- END: multiselect -->
                    <!-- BEGIN: file -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal {FIELD.required}">{FIELD.title}</label>
                        <div class="col-md-18 filelist" data-field="{FIELD.field}" data-oclass="{FIELD.class}" data-maxnum="{FILEMAXNUM}">
                            <ul class="list-unstyled items">
                                <!-- BEGIN: loop -->
                                <li><input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FILE_ITEM.key}" class="{FIELD.class}" checked><button type="button" class="btn btn-success btn-file type-{FILE_ITEM.type}" data-url="{FILE_ITEM.url}">{FILE_ITEM.value}</button> <button type="button" class="btn btn-link" data-toggle="thisfile_del">{LANG.delete}</button></li>
                                <!-- END: loop -->
                            </ul>
                            <div><button type="button" class="btn btn-info btn-xs" data-toggle="addfilebtn" data-modal="uploadfile_{FIELD.field}"<!-- BEGIN: addfile --> style="display:none"<!-- END: addfile -->><i class="fa fa-upload"></i> {LANG.addfile}</button></div>
                            <!-- START FORFOOTER -->
                            <div class="modal fade uploadfile" tabindex="-1" role="dialog" id="uploadfile_{FIELD.field}" data-url="{URL_MODULE}" data-field="{FIELD.field}" data-csrf="{CSRF}" data-accept="{FILEACCEPT}" data-maxsize="{FILEMAXSIZE}" data-ext-error="{LANG.addfile_ext_error}" data-size-error="{LANG.addfile_size_error}" data-size-error2="{LANG.addfile_size_error2}" data-delete="{LANG.delete}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">{LANG.addfile}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="fileinput" style="display:flex;justify-content:center;margin-top:20px;margin-bottom:20px"></p>
                                            <ul class="list-unstyled help-block small">
                                                <li>- {LANG.accepted_extensions}: {FILEACCEPT}</li>
                                                <li>- {LANG.field_file_max_size}: {FILEMAXSIZE_FORMAT}</li>
                                                <!-- BEGIN: widthlimit --><li>- {WIDTHLIMIT}</li><!-- END: widthlimit -->
                                                <!-- BEGIN: heightlimit --><li>- {HEIGHTLIMIT}</li><!-- END: heightlimit -->
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END FORFOOTER -->
                        </div>
                    </div>
                    <!-- END: file -->
                    <!-- END: loop -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_others -->
        <!-- BEGIN: tab_edit_safemode -->
        <div id="edit_safemode" class="well-lg tab-pane fade {TAB_SAFEMODE_ACTIVE}">
            <!-- BEGIN: safemode_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle">&nbsp;</em> {LANG.safe_deactive_notvalid}
                <button type="button" class="btn btn-primary btn-xs" data-toggle="addpass">{LANG.add_pass}</button>
            </div>
            <!-- END: safemode_empty_pass -->
            <form action="{EDITINFO_FORM}/safemode" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" data-toggle="reg_validForm" autocomplete="off" novalidate>
                <h2 class="margin-bottom-lg text-center">
                    <em class="fa fa-shield fa-lg margin-right text-danger"></em>{LANG.safe_activate}
                </h2>
                <div class="nv-info" style="margin-bottom:30px">{LANG.safe_activate_info}</div>
                <div class="nv-info-default hidden">{LANG.safe_activate_info}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <label for="nv_password" class="control-label col-md-6 text-normal">{GLANG.password}</label>
                        <div class="col-md-14">
                            <div class="input-group">
                                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span> <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.password_empty}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="safe_key" class="control-label col-md-6 text-normal">{LANG.safe_key}</label>
                        <div class="col-md-14">
                            <div class="input-group">
                                <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                                <input type="text" class="required form-control" placeholder="{LANG.safe_key}" value="" name="safe_key" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.required}">
                                <span class="input-group-btn"><input type="button" value="{LANG.verifykey_send}" class="safekeySend btn btn-info" data-toggle="safekeySend" /></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <button class="bsubmit btn btn-primary" type="submit">{LANG.editinfo_confirm}</button>
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_safemode -->
    </div>
    <ul class="nav navbar-nav">
        <!-- BEGIN: navbar -->
        <li><a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a></li>
        <!-- END: navbar -->
    </ul>
</div>
<script>
    $(function() {
        $('#funcList a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            changeTabTitle()
        });
        changeTabTitle()
    })
</script>
<!-- END: main -->
