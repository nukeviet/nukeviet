<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="page">
    <h2 class="margin-bottom-lg margin-top-lg">{LANG.editinfo_pagetitle}</h2>
    <ul class="users-menu nav nav-pills margin-bottom">
        <li class="{BASIC_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/basic" href="#edit_basic">{LANG.edit_basic}</a>
        </li>
        <!-- BEGIN: edit_avatar -->
        <li class="{AVATAR_ACTIVE}">
            <a data-toggle="tab" href="#edit_avatar" data-location="{EDITINFO_FORM}/avatar">{LANG.edit_avatar}</a>
        </li><!-- END: edit_avatar -->
        <!-- BEGIN: edit_username -->
        <li class="{USERNAME_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/username" href="#edit_username">{LANG.edit_login}</a>
        </li><!-- END: edit_username -->
        <!-- BEGIN: edit_email -->
        <li class="{EMAIL_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/email" href="#edit_email">{LANG.edit_email}</a>
        </li><!-- END: edit_email -->
        <!-- BEGIN: edit_password -->
        <li class="{PASSWORD_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/password" href="#edit_password">{LANG.edit_password}</a>
        </li><!-- END: edit_password -->
        <!-- BEGIN: 2step -->
        <li>
            <a href="{URL_2STEP}">{LANG.2step_status}</a>
        </li><!-- END: 2step -->
        <!-- BEGIN: edit_question -->
        <li class="{QUESTION_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/question" href="#edit_question">{LANG.edit_question}</a>
        </li><!-- END: edit_question -->
        <!-- BEGIN: edit_openid -->
        <li class="{OPENID_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/openid" href="#edit_openid">{LANG.openid_administrator}</a>
        </li><!-- END: edit_openid -->
        <!-- BEGIN: edit_group -->
        <li class="{GROUP_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/group" href="#edit_group">{LANG.group}</a>
        </li><!-- END: edit_group -->
        <!-- BEGIN: edit_others -->
        <li class="{OTHERS_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/others" href="#edit_others">{LANG.edit_others}</a>
        </li><!-- END: edit_others -->
        <!-- BEGIN: edit_safemode -->
        <li class="{SAFEMODE_ACTIVE}">
            <a data-toggle="tab" data-location="{EDITINFO_FORM}/safemode" href="#edit_safemode">{LANG.safe_mode}</a>
        </li><!-- END: edit_safemode -->
    </ul>

    <div class="tab-content margin-bottom-lg">

        <div id="edit_basic" class="tab-pane fade {TAB_BASIC_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <form action="{EDITINFO_FORM}/basic" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="" style="display:none"></div>
                        <div class="form-detail">
                            <!-- BEGIN: name_show_0 -->
                            <!-- BEGIN: show_last_name-->
                            <div class="form-group">
                                <label for="last_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess="">
                                </div>
                            </div>
                            <!-- END: show_last_name -->
                            <!-- BEGIN: show_first_name -->
                            <div class="form-group">
                                <label for="first_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess="">
                                </div>
                            </div>
                            <!-- END: show_first_name -->
                            <!-- END: name_show_0 -->
                            <!-- BEGIN: name_show_1 -->
                            <!-- BEGIN: show_first_name -->
                            <div class="form-group">
                                <label for="first_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess="">
                                </div>
                            </div>
                            <!-- END: show_first_name -->
                            <!-- BEGIN: show_last_name-->
                            <div class="form-group">
                                <label for="last_name" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" onkeypress="validErrorHidden(this);" data-mess="">
                                </div>
                            </div>
                            <!-- END: show_last_name -->
                            <!-- END: name_show_1 -->
                            <!-- BEGIN: show_gender -->
                            <div class="form-group">
                                <label for="gender" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <div class="clearfix radio-box {FIELD.required} {FIELD.class}" data-mess="">
                                        <!-- BEGIN: gender -->
                                        <label class="radio-box"> <input type="radio" name="gender" value="{GENDER.key}" {GENDER.checked} class="{FIELD.class}" onclick="validErrorHidden(this,5);"> {GENDER.title} </label>
                                        <!-- END: gender -->
                                    </div>
                                </div>
                            </div>
                            <!-- END: show_gender -->
                            <!-- BEGIN: show_birthday -->
                            <div class="form-group">
                                <label for="birthday" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control calendar-icon datepicker {FIELD.required} {FIELD.class}" name="birthday" value="{FIELD.value}" readonly="readonly" onfocus="datepickerShow(this);" data-mess="">
                                </div>
                            </div>
                            <!-- END: show_birthday -->
                            <!-- BEGIN: show_sig -->
                            <div class="form-group">
                                <label for="birthday" class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-12">
                                    <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="sig" onkeypress="validErrorHidden(this);" data-mess="">{FIELD.value}</textarea>
                                </div>
                            </div>
                            <!-- END: show_sig -->
                            <div class="form-group">
                                <label for="view_mail" class="control-label col-md-6 text-normal">{LANG.showmail}</label>
                                <div class="col-md-4">
                                    <select name="view_mail" class="form-control">
                                        <option value="0">{LANG.no}</option>
                                        <option value="1"{DATA.view_mail}>{LANG.yes}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                </div>
                                <div class="col-md-10">
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- BEGIN: tab_edit_avatar -->
        <div id="edit_avatar" class="tab-pane fade {TAB_AVATAR_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <div class="margin-bottom">
                        <img id="myavatar" class="img-thumbnail bg-gainsboro" src="{DATA.photo}" width="{DATA.photoWidth}" height="{DATA.photoHeight}" data-default="{AVATAR_DEFAULT}" />
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-xs margin-right-sm" onclick="changeAvatar('{URL_AVATAR}');">
                            {LANG.change_avatar}
                        </button>
                        <button type="button" class="btn btn-danger btn-xs" id="delavatar" onclick="deleteAvatar('#myavatar','{DATA.checkss}',this)"{DATA.imgDisabled}>
                            {GLANG.delete}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_avatar -->
        <!-- BEGIN: tab_edit_username -->
        <div id="edit_username" class="tab-pane fade {TAB_USERNAME_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <!-- BEGIN: username_empty_pass -->
                    <div class="alert alert-danger">
                        <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changelogin_notvalid}
                        <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">
                            {LANG.add_pass}
                        </button>
                    </div>
                    <!-- END: username_empty_pass -->
                    <form action="{EDITINFO_FORM}/username" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="{LANG.edit_login_warning}">
                            {LANG.edit_login_warning}
                        </div>

                        <div class="form-detail">
                            <div class="form-group">
                                <div class="col-md-6 text-right">
                                    {LANG.currentlogin}:
                                </div>
                                <div class="col-md-12">
                                    <strong>{DATA.username}</strong>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="username" class="control-label col-md-6 text-normal">{LANG.newlogin}</label>
                                <div class="col-md-12">
                                    <input type="text" class="required form-control" placeholder="{LANG.newlogin}" value="" name="username" maxlength="{NICK_MAXLENGTH}" data-pattern="/^(.){{NICK_MINLENGTH},{NICK_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.username_empty}">
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
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_username -->

        <!-- BEGIN: tab_edit_email -->
        <div id="edit_email" class="tab-pane fade {TAB_EMAIL_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <!-- BEGIN: email_empty_pass -->
                    <div class="alert alert-danger">
                        <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changeemail_notvalid}
                        <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">
                            {LANG.add_pass}
                        </button>
                    </div>
                    <!-- END: email_empty_pass -->
                    <form action="{EDITINFO_FORM}/email" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return changemail_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom">
                            {LANG.edit_email_warning}
                        </div>
                        <div class="nv-info-default hidden">
                            {LANG.edit_email_warning}
                        </div>

                        <div class="form-detail">
                            <div class="form-group">
                                <div class="col-md-6 text-right">
                                    {LANG.currentemail}:
                                </div>
                                <div class="col-md-12">
                                    <strong>{DATA.email}</strong>
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
                                        <input type="text" class="form-control" placeholder="{LANG.verifykey}" value="" name="verifykey" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.verifykey_empty}">
                                        <span class="input-group-btn">
                                            <button type="button" class="send-bt btn btn-warning pointer" onclick="verkeySend(this.form);">
                                                {LANG.verifykey_send}
                                            </button></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                    <input type="hidden" name="vsend" value="0" />
                                </div>
                                <div class="col-md-10">
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_email -->
        <!-- BEGIN: tab_edit_password -->
        <div id="edit_password" class="tab-pane fade {TAB_PASSWORD_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <form action="{EDITINFO_FORM}/password" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="" style="display:none"></div>

                        <div class="form-detail">
                            <!-- BEGIN: is_old_pass -->
                            <div class="form-group">
                                <label for="nv_password" class="control-label col-md-6 text-normal">{LANG.pass_old}</label>
                                <div class="col-md-12">
                                    <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_old}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.required}">
                                </div>
                            </div>
                            <!-- END: is_old_pass -->

                            <div class="form-group">
                                <label for="new_password" class="control-label col-md-6 text-normal">{LANG.pass_new}</label>
                                <div class="col-md-12">
                                    <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new}" value="" name="new_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.required}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="re_password" class="control-label col-md-6 text-normal">{LANG.pass_new_re}</label>
                                <div class="col-md-12">
                                    <input type="password" autocomplete="off" class="required form-control" placeholder="{LANG.pass_new_re}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.required}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                </div>
                                <div class="col-md-10">
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_password -->
        <!-- BEGIN: tab_edit_question -->
        <div id="edit_question" class="tab-pane fade {TAB_QUESTION_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <!-- BEGIN: question_empty_pass -->
                    <div class="alert alert-danger">
                        <em class="fa fa-exclamation-triangle ">&nbsp;</em> {LANG.changeemail_notvalid}
                        <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">
                            {LANG.add_pass}
                        </button>
                    </div>
                    <!-- END: question_empty_pass -->
                    <form action="{EDITINFO_FORM}/question" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="{LANG.edit_question_warning}">
                            {LANG.edit_question_warning}
                        </div>

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
                                        <span class="input-group-btn" onclick="showQlist(this);">
                                            <button type="button" class="btn btn-default pointer"><em class="fa fa-caret-down fa-lg"></em></button>
                                        </span>
                                    </div>
                                    <div class="qlist" data-show="no">
                                        <ul>
                                            <!-- BEGIN: frquestion -->
                                            <li>
                                                <a href="#" onclick="addQuestion(this);">{QUESTION}</a>
                                            </li>
                                            <!-- END: frquestion -->
                                        </ul>
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
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_question -->
        <!-- BEGIN: tab_edit_openid -->
        <div id="edit_openid" class="tab-pane fade {TAB_OPENID_ACTIVE}">
            <!-- BEGIN: openid_not_empty -->
            <form action="{EDITINFO_FORM}/openid" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display:none"></div>

                <div class="form-detail">
                    <table class="table table-bordered table-striped table-hover">
                        <colgroup>
                            <col style="width:20px"/>
                        </colgroup>
                        <thead>
                            <tr class="bg-lavender">
                                <td><!-- BEGIN: checkAll --><input type="checkbox" class="checkAll" onclick="checkAll(this.form);" /><!-- END: checkAll --></td>
                                <td class="text-uppercase">{LANG.openid_server}</td>
                                <td class="text-uppercase">{LANG.email}</td>
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
                                <th class="text-center"><!-- BEGIN: is_act --><input name="openid_del[]" type="checkbox" value="{OPENID_LIST.opid}" class="checkSingle" onclick="checkSingle(this.form);"{OPENID_LIST.disabled} /><!-- END: is_act --><!-- BEGIN: disabled --><em class="fa fa-shield text-danger pointer" title="{LANG.openid_default}"></em><!-- END: disabled --></th>
                                <td>{OPENID_LIST.openid}</td>
                                <td>{OPENID_LIST.email}</td>
                            </tr>
                            <!-- END: openid_list -->
                        </tbody>
                    </table>
                </div>
            </form>
            <!-- END: openid_not_empty -->
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender text-center">
                    <div class="margin-bottom-lg">
                        {LANG.openid_add_new}
                    </div>
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
            <form action="{EDITINFO_FORM}/group" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                <div class="nv-info margin-bottom" data-default="" style="display:none"></div>

                <div class="form-detail">
                    <table class="table table-bordered table-striped table-hover">
                        <colgroup>
                            <col width="20"/>
                            <col width="240" />
                            <col />
                            <col width="100"/>
                            <col width="120"/>
                        </colgroup>
                        <thead>
                            <tr class="bg-lavender">
                                <td><!-- BEGIN: checkAll --><input type="checkbox" class="checkAll" onclick="checkAll(this.form);"{CHECK_ALL_CHECKED} /><!-- END: checkAll --></td>
                                <td class="text-uppercase">{LANG.group_name}</td>
                                <td class="text-uppercase">{LANG.group_description}</td>
                                <td class="text-uppercase text-center">{LANG.group_userr}</td>
                                <td class="text-uppercase"></td>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="5"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input id="submit" type="submit" class="btn btn-primary" value="{LANG.group_reg}" /></td>
                            </tr>
                        </tfoot>
                        <tbody>
                            <!-- BEGIN: group_list -->
                            <tr>
                                <th class="text-center"><input name="in_groups[]" type="checkbox" value="{GROUP_LIST.group_id}" class="checkSingle" onclick="checkSingle(this.form);"{GROUP_LIST.checked} <!-- BEGIN: is_disable_checkbox -->disabled="disabled"<!-- END: is_disable_checkbox --> /></th>
                                <td><strong>{GROUP_LIST.title}</strong><em class="show text-success">{GROUP_LIST.group_type}</em><!-- BEGIN: is_leader --><span class="text-danger"><em class="fa fa-users">&nbsp;</em><a href="{URL_IS_LEADER}" title="">{LANG.group_manage}</a></span><!-- END: is_leader --></td>
                                <td>{GROUP_LIST.description}</td>
                                <td class="text-center">{GROUP_LIST.numbers}</td>
                                <td class="text-center">{GROUP_LIST.status}</td>
                            </tr>
                            <!-- END: group_list -->
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        <!-- END: tab_edit_group -->

        <!-- BEGIN: tab_edit_others -->
        <div id="edit_others" class="tab-pane fade {TAB_OTHERS_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <form action="{EDITINFO_FORM}/others" method="post" role="form" class="form-horizontal" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <div class="nv-info margin-bottom" data-default="{GLANG.required}">
                            {GLANG.required}
                        </div>

                        <div class="form-detail">

                            <!-- BEGIN: loop -->
                            <!-- BEGIN: textbox -->
                            <div class="form-group">
                                <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-18">
                                    <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" onkeypress="validErrorHidden(this);" data-mess=""/>
                                </div>
                            </div>
                            <!-- END: textbox -->

                            <!-- BEGIN: date -->
                            <div class="form-group">
                                <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" onchange="validErrorHidden(this);" onfocus="datepickerShow(this);" data-mess=""/>
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
                                <div class="col-md-18">
                                    {EDITOR}
                                </div>
                            </div>
                            <!-- END: editor -->

                            <!-- BEGIN: select -->
                            <div class="form-group">
                                <label class="control-label col-md-6 text-normal">{FIELD.title}</label>
                                <div class="col-md-18">
                                    <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" onchange="validErrorHidden(this);" data-mess="">
                                        <!-- BEGIN: loop -->
                                        <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}> {FIELD_CHOICES.value} </option>
                                        <!-- END: loop -->
                                    </select>
                                </div>
                            </div>
                            <!-- END: select -->

                            <!-- BEGIN: radio -->
                            <div>
                                <div>
                                    <div class="form-group clearfix radio-box {FIELD.required}" data-mess="">
                                        <label for="custom_fields[{FIELD.field}]" class="control-label col-md-6 {FIELD.required} text-normal" title="{FIELD.description}"> {FIELD.title} </label>
                                        <div class="btn-group col-md-18">
                                            <!-- BEGIN: loop -->
                                            <label for="lb_{FIELD_CHOICES.id}" class="radio-box text-normal"> <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="validErrorHidden(this,5);" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
                                            <!-- END: loop -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END: radio -->

                            <!-- BEGIN: checkbox -->
                            <div>
                                <div>
                                    <div class="form-group clearfix check-box {FIELD.required}" data-mess="">
                                        <label for="custom_fields[{FIELD.field}]" class="col-md-6 control-label {FIELD.required} text-normal" title="{FIELD.description}"> {FIELD.title} </label>
                                        <div class="btn-group col-md-18">
                                            <!-- BEGIN: loop -->
                                            <label for="lb_{FIELD_CHOICES.id}" class="check-box text-normal"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" class="{FIELD.class}" onclick="validErrorHidden(this,5);" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
                                            <!-- END: loop -->
                                        </div>
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
                                        <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
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
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <input type="submit" class="btn btn-primary" value="{LANG.editinfo_confirm}" />
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- BEGIN: ckeditor -->
                    <script type="text/javascript">
                        for (var i in CKEDITOR.instances) {
                            CKEDITOR.instances[i].on('change', function() {
                                CKEDITOR.instances[i].updateElement()
                            });
                        }
                    </script>
                    <!-- END: ckeditor -->
                </div>
            </div>
        </div>
        <!-- END: tab_edit_others -->
        <!-- BEGIN: tab_edit_safemode -->
        <div id="edit_safemode" class="tab-pane fade {TAB_SAFEMODE_ACTIVE}">
            <div class="page panel panel-default">
                <div class="panel-body bg-lavender">
                    <!-- BEGIN: safemode_empty_pass -->
                    <div class="alert alert-danger">
                        <em class="fa fa-exclamation-triangle">&nbsp;</em> {LANG.safe_deactive_notvalid}
                        <button type="button" class="btn btn-primary btn-xs" onclick="addpass()">
                            {LANG.add_pass}
                        </button>
                    </div>
                    <!-- END: safemode_empty_pass -->
                    <form action="{EDITINFO_FORM}/safemode" method="post" role="form" class="form-horizontal{FORM_HIDDEN}" onsubmit="return reg_validForm(this);" autocomplete="off" novalidate>
                        <h2 class="margin-bottom-lg text-center"><em class="fa fa-shield fa-lg margin-right text-danger"></em>{LANG.safe_activate}</h2>
                        <div class="nv-info margin-bottom">
                            {LANG.safe_activate_info}
                        </div>
                        <div class="nv-info-default hidden">
                            {LANG.safe_activate_info}
                        </div>

                        <div class="form-detail">
                            <div class="form-group">
                                <label for="nv_password" class="control-label col-md-6 text-normal">{GLANG.password}</label>
                                <div class="col-md-14">
                                    <div class="input-group">
                                        <span class="input-group-addon"><em class="fa fa-key fa-lg fa-fix"></em></span>
                                        <input type="password" autocomplete="off" class="required form-control" placeholder="{GLANG.password}" value="" name="nv_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){{PASS_MINLENGTH},{PASS_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.password_empty}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="safe_key" class="control-label col-md-6 text-normal">{LANG.safe_key}</label>
                                <div class="col-md-14">
                                    <div class="input-group">
                                        <span class="input-group-addon"><em class="fa fa-shield fa-lg"></em></span>
                                        <input type="text" class="required form-control" placeholder="{LANG.safe_key}" value="" name="safe_key" maxlength="32" data-pattern="/^[a-zA-Z0-9]{32,32}$/" onkeypress="validErrorHidden(this);" data-mess="{LANG.required}">
                                        <span class="input-group-btn"><input type="button" value="{LANG.verifykey_send}" class="safekeySend btn btn-warning" onclick="safekeySend(this.form);" /></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                </div>
                                <div class="col-md-10">
                                    <input type="button" value="{GLANG.reset}" class="btn btn-default" onclick="validReset(this.form);return!1;" />
                                    <button class="bsubmit btn btn-primary" type="submit">
                                        {LANG.editinfo_confirm}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END: tab_edit_safemode -->
    </div>

    <ul class="nav navbar-nav">
        <!-- BEGIN: navbar -->
        <li>
            <a href="{NAVBAR.href}"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a>
        </li><!-- END: navbar -->
    </ul>
</div>
<!-- END: main -->