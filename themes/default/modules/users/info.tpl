<!-- BEGIN: main -->
<link type="text/css" href="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_STATIC_URL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div class="page">
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.editinfo_pagetitle}</h2>
    <ul class="users-menu nav nav-tabs margin-bottom">
        <li class="dropdown active" role="presentation">
            <a id="myTabEl" href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-controls="funcList" aria-expanded="false"><span class="caret"></span></a>
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
            </ul></li>
    </ul>
    <div class="tab-content margin-bottom-lg">
        <div id="edit_basic" class="well-lg tab-pane fade {TAB_BASIC_ACTIVE}">
            <form action="{EDITINFO_FORM}/basic" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <!-- BEGIN: name_show_0 -->
                    <!-- BEGIN: show_last_name-->
                    <div class="form-group">
                        <label for="last_name" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-13 col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                        </div>
                    </div>
                    <!-- END: show_last_name -->
                    <!-- BEGIN: show_first_name -->
                    <div class="form-group">
                        <label for="first_name" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-13 col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                        </div>
                    </div>
                    <!-- END: show_first_name -->
                    <!-- END: name_show_0 -->
                    <!-- BEGIN: name_show_1 -->
                    <!-- BEGIN: show_first_name -->
                    <div class="form-group">
                        <label for="first_name" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-13 col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                        </div>
                    </div>
                    <!-- END: show_first_name -->
                    <!-- BEGIN: show_last_name-->
                    <div class="form-group">
                        <label for="last_name" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-13 col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                        </div>
                    </div>
                    <!-- END: show_last_name -->
                    <!-- END: name_show_1 -->
                    <!-- BEGIN: show_gender -->
                    <div class="form-group">
                        <label for="gender" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-5 col-md-4">
                            <select class="form-control {FIELD.required} {FIELD.class}" name="gender" onchange="validErrorHidden(this,5);" data-mess="">
                                <!-- BEGIN: gender -->
                                <option value="{GENDER.key}"{GENDER.sel}>{GENDER.title}</option>
                                <!-- END: gender -->
                            </select>
                        </div>
                    </div>
                    <!-- END: show_gender -->
                    <!-- BEGIN: show_birthday -->
                    <div class="form-group">
                        <label for="birthday" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-5 col-md-4">
                            <input type="text" class="form-control calendar-icon datepicker {FIELD.required} {FIELD.class}" name="birthday" value="{FIELD.value}" readonly="readonly" style="background-color: #fff" onfocus="datepickerShow(this);" data-mess="">
                        </div>
                    </div>
                    <!-- END: show_birthday -->
                    <!-- BEGIN: show_sig -->
                    <div class="form-group">
                        <label for="birthday" class="control-label col-sm-7 col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-sm-13 col-md-12">
                            <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="sig" onkeypress="validErrorHidden(this);" data-mess="">{FIELD.value}</textarea>
                        </div>
                    </div>
                    <!-- END: show_sig -->
                    <div class="form-group">
                        <div class="col-sm-13 col-sm-push-7 col-md-12 col-md-push-6">
                            <label class="check-box"><input type="checkbox" name="view_mail" style="margin-top: 0" value="1" {DATA.view_mail}/> {LANG.showmail}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-7 col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-sm-13 col-md-12">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- BEGIN: tab_edit_avatar -->
        <div id="edit_avatar" class="well-lg tab-pane fade {TAB_AVATAR_ACTIVE}">
            <div class="margin-bottom">
                <img id="myavatar" alt="My Avatar" class="img-thumbnail bg-gainsboro" src="{DATA.photo}" width="{DATA.photoWidth}" height="{DATA.photoHeight}" data-default="{AVATAR_DEFAULT}" />
            </div>
            <div>
                <button type="button" class="btn btn-primary btn-xs margin-right-sm" onclick="changeAvatar('{URL_AVATAR}');">{LANG.change_avatar}</button>
                <button type="button" class="btn btn-danger btn-xs" id="delavatar" onclick="deleteAvatar('#myavatar','{DATA.checkss}',this)"{DATA.imgDisabled}>{GLANG.delete}</button>
            </div>
        </div>
        <!-- END: tab_edit_avatar -->
        <!-- BEGIN: tab_edit_username -->
        <div id="edit_username" class="well-lg tab-pane fade {TAB_USERNAME_ACTIVE}">
            <!-- BEGIN: username_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changelogin_notvalid}
                <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">{LANG.add_pass}</button>
            </div>
            <!-- END: username_empty_pass -->
            <form action="{EDITINFO_FORM}/username" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
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
                            <input type="text" class="required form-control" placeholder="{LANG.newlogin}" value="" name="username" maxlength="{NICK_MAXLENGTH}" onkeypress="validErrorHidden(this);" data-mess="{USERNAME_RULE}" data-callback="login_check" data-minlength="{NICK_MINLENGTH}" data-type="{LOGINTYPE}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" /> <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
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
                <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">{LANG.add_pass}</button>
            </div>
            <!-- END: email_empty_pass -->
            <form action="{EDITINFO_FORM}/email" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return changemail_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info" style="margin-bottom:30px">{LANG.edit_email_warning}</div>
                <div class="nv-info-default hidden">{LANG.edit_email_warning}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <div class="col-md-18 col-md-push-6">
                            {LANG.currentemail}: <strong>{DATA.email}</strong>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label col-md-6 text-normal">{LANG.newemail}</label>
                        <div class="col-md-12">
                            <input type="email" class="required form-control" placeholder="{LANG.newemail}" value="" name="email" maxlength="100" onkeypress="validErrorHidden(this);" data-mess="{GLANG.email_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verifykey" class="control-label col-md-6 text-normal">{LANG.verifykey}</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="{LANG.verifykey}" value="" name="verifykey" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.verifykey_empty}"> <span class="input-group-btn">
                                    <button type="button" class="send-bt btn btn-info pointer" onclick="verkeySend(this.form);">{LANG.verifykey_send}</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" /> <input type="hidden" name="vsend" value="0" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_email -->
        <!-- BEGIN: tab_edit_password -->
        <div id="edit_password" class="well-lg tab-pane fade {TAB_PASSWORD_ACTIVE}">
            <form action="{EDITINFO_FORM}/password" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display: none"></div>
                <div class="form-detail">
                    <!-- BEGIN: is_old_pass -->
                    <div class="form-group">
                        <label for="nv_password" class="control-label col-md-6 text-normal">{LANG.pass_old}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_old}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.required}">
                        </div>
                    </div>
                    <!-- END: is_old_pass -->
                    <div class="form-group">
                        <label for="new_password" class="control-label col-md-6 text-normal">{LANG.pass_new}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{PASSWORD_RULE}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="re_password" class="control-label col-md-6 text-normal">{LANG.pass_new_re}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.re_password_empty}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_password -->
        <!-- BEGIN: tab_edit_question -->
        <div id="edit_question" class="well-lg tab-pane fade {TAB_QUESTION_ACTIVE}">
            <!-- BEGIN: question_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changequestion_notvalid}
                <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">{LANG.add_pass}</button>
            </div>
            <!-- END: question_empty_pass -->
            <form action="{EDITINFO_FORM}/question" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info" style="margin-bottom:30px" data-default="{LANG.edit_question_warning}">{LANG.edit_question_warning}</div>
                <div class="form-detail">
                    <div class="form-group">
                        <label for="nv_password" class="control-label col-md-6 text-normal">{LANG.password}</label>
                        <div class="col-md-12">
                            <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                        </div>
                    </div>
                    <!-- BEGIN: show_question -->
                    <div class="form-group rel">
                        <label for="question" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <div class="input-group">
                                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="" name="question" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.your_question_empty}">
                                <div class="input-group-btn" role="group">
                                    <button type="button" class="btn btn-default pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <!-- BEGIN: frquestion -->
                                        <li><a href="javascript:void(0)" onclick="addQuestion(this);">{QUESTION}</a></li>
                                        <!-- END: frquestion -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: show_question -->
                    <!-- BEGIN: show_answer -->
                    <div class="form-group">
                        <label for="answer" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="" name="answer" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.answer_empty}">
                        </div>
                    </div>
                    <!-- END: show_answer -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_question -->
        <!-- BEGIN: tab_edit_openid -->
        <div id="edit_openid" class="tab-pane fade {TAB_OPENID_ACTIVE}">
            <!-- BEGIN: openid_not_empty -->
            <form action="{EDITINFO_FORM}/openid" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
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
                                    <input type="checkbox" class="checkAll" onclick="checkAll(this.form);" />
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
                                    <input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}" class="checkSingle" onclick="checkSingle(this.form);" {OPENID_LIST.disabled} />
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
                        <a href="{OPENID.href}" class="openid margin-right" onclick="return openID_load(this);"><img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" />{OPENID.title}</a>
                        <!-- END: server -->
                    </div>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_openid -->
        <!-- BEGIN: tab_edit_group -->
        <div id="edit_group" class="tab-pane fade {TAB_GROUP_ACTIVE}">
            <form action="{EDITINFO_FORM}/group" method="post" role="form" class="form-horizontal" onsubmit="return edit_group_submit(event,this,'{DATA.old_in_groups}');" autocomplete="off" novalidate>
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
                                    <input type="checkbox" class="checkAll" onclick="checkAll(this.form);" {CHECK_ALL_CHECKED} />
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
                                <th class="text-center"><!-- BEGIN: is_checkbox --><input name="in_groups[]" type="checkbox" value="{GROUP_LIST.group_id}" class="checkSingle" onclick="checkSingle(this.form);"{GROUP_LIST.checked}<!-- BEGIN: is_disable_checkbox --> disabled="disabled"/><input type="hidden" name="in_groups[]" value="{GROUP_LIST.group_id}"<!-- END: is_disable_checkbox -->/><!-- END: is_checkbox --></th>
                                <td><a class="pointer" data-toggle="modal" data-target="#modal-{GROUP_LIST.alias}"><strong>{GROUP_LIST.title}</strong><em class="show text-success">{GROUP_LIST.group_type_mess}</em></a>
                                <!-- BEGIN: is_leader -->
                                    <span class="text-danger"><em class="fa fa-users">&nbsp;</em><a href="{URL_IS_LEADER}" title="">{LANG.group_manage}</a></span>
                                <!-- END: is_leader --></td>
                                <td>{GROUP_LIST.description}</td>
                                <td class="text-right">
                                    <!-- BEGIN: if_not_joined --><a href="javascript:void(0);" data-toggle="popover" data-container="body" data-placement="left" data-content="{GROUP_LIST.status_mess}"><i class="fa fa-power-off fa-lg text-muted"></i></a><!-- END: if_not_joined -->
                                    <!-- BEGIN: if_joined --><a href="javascript:void(0);" data-toggle="popover" data-container="body" data-placement="left" data-content="{GROUP_LIST.status_mess}"><i class="fa fa-check fa-lg text-success"></i></a><!-- END: if_joined -->
                                    <!-- BEGIN: if_waited --><a href="javascript:void(0);" data-toggle="popover" data-container="body" data-placement="left" data-content="{GROUP_LIST.status_mess}"><i class="fa fa-hourglass-half fa-lg text-warning"></i></a><!-- END: if_waited -->
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
                                                        <img title="{GROUP_LIST.title}" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/pix.gif" width="80" height="80" style="background-image:url({GROUP_LIST.group_avatar});background-repeat:no-repeat;background-size:cover;" />
                                                    </div>
                                                    <p><strong>{LANG.group_type}: </strong>{GROUP_LIST.group_type_mess}<!-- BEGIN: group_type_note --> ({GROUP_LIST.group_type_note})<!-- END: group_type_note --></p>
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
            <form action="{EDITINFO_FORM}/others" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info" style="margin-bottom:30px" data-default="{GLANG.required}">{GLANG.required}</div>
                <div class="form-detail">
                    <!-- BEGIN: loop -->
                    <!-- BEGIN: textbox -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" onkeypress="validErrorHidden(this);" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->/>
                        </div>
                    </div>
                    <!-- END: textbox -->
                    <!-- BEGIN: date -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" style="background-color:#fff" onchange="validErrorHidden(this);" onfocus="datepickerShow(this);" data-mess="" />
                        </div>
                    </div>
                    <!-- END: date -->
                    <!-- BEGIN: textarea -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="custom_fields[{FIELD.field}]" onkeypress="validErrorHidden(this);" data-mess="">{FIELD.value}</textarea>
                        </div>
                    </div>
                    <!-- END: textarea -->
                    <!-- BEGIN: editor -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">{EDITOR}</div>
                    </div>
                    <!-- END: editor -->
                    <!-- BEGIN: select -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" onchange="validErrorHidden(this);" data-mess="">
                                <!-- BEGIN: loop -->
                                <option value="{FIELD_CHOICES.key}"{FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                                <!-- END: loop -->
                            </select>
                        </div>
                    </div>
                    <!-- END: select -->
                    <!-- BEGIN: radio -->
                    <div class="form-group">
                        <label class="control-label col-md-6 {FIELD.required} text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <div class="radio-box {FIELD.required}" data-mess="">
                                <!-- BEGIN: loop -->
                                <label for="lb_{FIELD_CHOICES.id}" class="radio-box" style="vertical-align:middle"> <input type="radio" name="custom_fields[{FIELD.field}]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="validErrorHidden(this,4);"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br />
                                <!-- END: loop -->
                            </div>
                        </div>
                    </div>
                    <!-- END: radio -->
                    <!-- BEGIN: checkbox -->
                    <div class="form-group">
                        <label class="control-label col-md-6 {FIELD.required} text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <div class="check-box {FIELD.required}" data-mess="">
                                <!-- BEGIN: loop -->
                                <label for="lb_{FIELD_CHOICES.id}" class="check-box" style="vertical-align:middle"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" style="margin-top: 0" onclick="validErrorHidden(this,4);"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br />
                                <!-- END: loop -->
                            </div>
                        </div>
                    </div>
                    <!-- END: checkbox -->
                    <!-- BEGIN: multiselect -->
                    <div class="form-group">
                        <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                        <div class="col-md-18">
                            <select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} {FIELD.required} form-control" onchange="validErrorHidden(this);" data-mess="">
                                <!-- BEGIN: loop -->
                                <option value="{FIELD_CHOICES.key}"{FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                                <!-- END: loop -->
                            </select>
                        </div>
                    </div>
                    <!-- END: multiselect -->
                    <!-- END: loop -->
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                        </div>
                    </div>
                </div>
            </form>
            <!-- BEGIN: ckeditor --><script>for(var i in CKEDITOR.instances){CKEDITOR.instances[i].on('change',function(){CKEDITOR.instances[i].updateElement()})}</script><!-- END: ckeditor -->
        </div>
        <!-- END: tab_edit_others -->
        <!-- BEGIN: tab_edit_safemode -->
        <div id="edit_safemode" class="well-lg tab-pane fade {TAB_SAFEMODE_ACTIVE}">
            <!-- BEGIN: safemode_empty_pass -->
            <div class="alert alert-danger">
                <em class="fa fa-exclamation-triangle">&nbsp;</em> {LANG.safe_deactive_notvalid}
                <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">{LANG.add_pass}</button>
            </div>
            <!-- END: safemode_empty_pass -->
            <form action="{EDITINFO_FORM}/safemode" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
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
                                <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span> <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="safe_key" class="control-label col-md-6 text-normal">{LANG.safe_key}</label>
                        <div class="col-md-14">
                            <div class="input-group">
                                <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                                <input type="text" class="required form-control" placeholder="{LANG.safe_key}" value="" name="safe_key" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.required}">
                                <span class="input-group-btn"><input type="button" value="{LANG.verifykey_send}" class="safekeySend btn btn-info" onclick="safekeySend(this.form);" /></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        </div>
                        <div class="col-md-10">
                            <button class="bsubmit btn btn-primary" type="submit">{LANG.editinfo_confirm}</button>
                            <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
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
    function changeTabTitle() {
        var n = $("#funcList li.active a").text();
        n += ' <span class="caret"></span>';
        $("#myTabEl").html(n)
    }
    function edit_group_submit(event, obj, old) {
        event.preventDefault();
        var nw = [];
        if ($('[name^=in_groups]:checked').length) {
            $('[name^=in_groups]:checked').each(function(){
                nw.push($(this).val());
            })
        }

        nw = nw.join();
        if (nw == old) {
            return !1
        }

        reg_validForm(obj);
    }
    $(function() {
        $('[data-toggle="popover"]').popover();
        $('.users-menu a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            changeTabTitle()
        });
        changeTabTitle()
    })
</script>
<!-- END: main -->