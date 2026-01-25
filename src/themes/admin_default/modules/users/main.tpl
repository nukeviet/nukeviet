<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<div id="users">
    <!-- BEGIN: is_forum -->
    <div class="alert alert-warning">{LANG.modforum}</div>
    <!-- END: is_forum -->
    <div class="well">
        <form action="{FORM_ACTION}" method="get">
            <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
            <div class="row">
                <div class="col-xs-12 col-md-5 col-md-offset-2">
                    <div class="form-group">
                        <input class="form-control" type="text" name="value" value="{SEARCH_VALUE}" id="f_value" placeholder="{LANG.search_key}" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <select class="form-control" name="method" id="f_method">
                            <option value="">---{LANG.search_type}---</option>
                            <!-- BEGIN: method -->
                            <option value="{METHODS.key}" {METHODS.selected}>{METHODS.value}</option>
                            <!-- END: method -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <select class="form-control" name="usactive">
                            <option value="-1">---{LANG.usactive}---</option>
                            <option value="-2" {SELECTED_NEW_USERS}>{GLANG.level7}</option>
                            <!-- BEGIN: usactive -->
                            <option value="{USACTIVE.key}" {USACTIVE.selected}>{USACTIVE.value}</option>
                            <!-- END: usactive -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <select class="form-control" name="group">
                            <!-- BEGIN: group -->
                            <option value="{GROUP.group_id}" {GROUP.selected}>{GROUP.title}</option>
                            <!-- END: group -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5 col-md-offset-2">
                    <div class="form-group">
                        <select class="form-control" name="active2step">
                            <!-- BEGIN: active2step -->
                            <option value="{ACTIVE2STEP.val}"{ACTIVE2STEP.sel}>{ACTIVE2STEP.name}</option>
                            <!-- END: active2step -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{REG_TIME_FROM}" name="reg_from" id="reg_time_from" placeholder="{LANG.reg_time_from}" autocomplete="off">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="from-btn">
                                    <em class="fa fa-calendar fa-fix"></em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" value="{REG_TIME_TO}" name="reg_to" id="reg_time_to" placeholder="{LANG.reg_time_to}" autocomplete="off">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="to-btn">
                                    <em class="fa fa-calendar fa-fix"></em>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-24 col-md-5">
                    <div class="form-group text-center">
                        <input class="btn btn-primary btn-block" type="submit" value="{LANG.submit}" />
                    </div>
                </div>
            </div>
            <div class="help-block text-center mb-0">{LANG.search_note}</div>
        </form>
    </div>
    <form>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
                <thead class="bg-primary">
                    <tr>
                        <th style="width:1%;"><input name="check_all[]" type="checkbox" class="form-control" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                        <th class="text-nowrap text-center" style="width:1%;"><a href="{HEAD.userid.href}" class="text-white">{HEAD.userid.title}</a></th>
                        <th><a href="{HEAD.username.href}" class="text-white">{HEAD.username.title}</a> / <a href="{HEAD.full_name.href}" class="text-white">{HEAD.full_name.title}</a></th>
                        <th><a href="{HEAD.email.href}" class="text-white">{HEAD.email.title}</a></th>
                        <th><a href="{HEAD.regdate.href}" class="text-white">{HEAD.regdate.title}</a></th>
                        <th class="text-nowrap text-center" style="width:1%;">{LANG.memberlist_active}</th>
                        <th class="text-nowrap text-center" style="width:1%;">{LANG.funcs}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: xusers -->
                    <tr>
                        <td class="align-middle">
                            <!-- BEGIN: choose --><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{CONTENT_TD.userid}" name="idcheck[]" /><!-- END: choose -->
                        </td>
                        <td class="text-nowrap text-center align-middle"> {CONTENT_TD.userid} </td>
                        <td class="align-middle">
                            <!-- BEGIN: is_admin -->
                            <img style="vertical-align:middle;" alt="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
                            <!-- END: is_admin -->
                            <!-- BEGIN: datadeletion_pending -->
                            <span class="d-inline-flex align-items-center justify-content-center bg-danger user-icon-inline text-danger" data-toggle="tooltip" title="{DELETE_AT}">
                                <i class="fa fa-user-times" aria-hidden="true"></i>
                            </span>
                            <!-- END: datadeletion_pending -->
                            <!-- BEGIN: view --><a href="javascript:void(0);" onclick="viewUser('{CONTENT_TD.link}')">{CONTENT_TD.username}</a><!-- END: view -->
                            <!-- BEGIN: show -->{CONTENT_TD.username}
                            <!-- END: show -->
                            <div class="mt-1">{CONTENT_TD.full_name}</div>
                        </td>
                        <td class="align-middle">
                            <a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a>
                            <div class="mt-1">{CONTENT_TD.info_verify}</div>
                        </td>
                        <td class="align-middle">
                            <span class="text-info">{CONTENT_TD.regdate}</span>
                            <div class="mt-1">{CONTENT_TD.active_obj}</div>
                        </td>
                        <td class="text-nowrap text-center align-middle"><input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}" {CONTENT_TD.checked}{CONTENT_TD.disabled} /></td>
                        <td class="text-nowrap align-middle">
                            <div class="btn-group d-inline-flex">
                                <!-- BEGIN: edit -->
                                <a class="btn btn-sm btn-info" href="{EDIT_URL}" data-toggle="tooltip" title="{LANG.memberlist_edit}"><em class="fa fa-edit fa-fw"></em></a>
                                <!-- END: edit -->
                                <!-- BEGIN: set_official -->
                                <button type="button" data-toggle="tooltip" title="{LANG.set_official_note}" class="btn btn-sm btn-warning" onclick="nv_set_official({CONTENT_TD.userid});"><em class="fa fa-user fa-fw"></em></button>
                                <!-- END: set_official -->
                                <!-- BEGIN: del -->
                                <button type="button" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-sm btn-danger" onclick="nv_row_del({CONTENT_TD.userid});"><em class="fa fa-trash-o fa-fw"></em></button>
                                <!-- END: del -->
                                <!-- BEGIN: edit2 -->
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">{LANG.memberlist_edit}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{EDIT_OAUTH_URL}">{LANG.user_openid_mamager}</a></li>
                                    <li><a href="{EDIT_2STEP_URL}">{LANG.user_2step_mamager}</a></li>
                                    <li><a href="javascript:void(0);" onclick="passResetRequest({CONTENT_TD.userid});">{LANG.pass_reset_request}</a></li>
                                    <li><a href="javascript:void(0);" onclick="emailResetRequest({CONTENT_TD.userid});">{LANG.email_reset_request}</a></li>
                                    <li><a href="javascript:void(0);" onclick="forcedReLogin({CONTENT_TD.userid});">{LANG.forcedrelogin}</a></li>
                                    <!-- BEGIN: cancel_deletion --><li><a href="javascript:void(0);" onclick="cancelDeletion({CONTENT_TD.userid});">{LANG.delacc_cancel_adm}</a></li><!-- END: cancel_deletion -->
                                </ul>
                                <!-- END: edit2 -->
                            </div>
                        </td>
                    </tr>
                    <!-- END: xusers -->
                </tbody>
                <!-- BEGIN: footer -->
                <tfoot>
                    <tr>
                        <td colspan="7">
                            <input type="hidden" name="checkss" value="{CHECKSESS}" />
                            <!-- BEGIN: action -->
                            <div class="input-group pull-left margin-right" style="width: fit-content;">
                                <select class="form-control" id="mainuseropt" style="width: fit-content;">
                                    <!-- BEGIN: loop -->
                                    <option value="{ACTION_KEY}">{ACTION_LANG}</option>
                                    <!-- END: loop -->
                                </select>
                                <span class="input-group-btn">
                                    <input type="button" class="btn btn-primary" value="{LANG.read_submit}" id="mainusersaction" data-msgnocheck="{LANG.msgnocheck}" />
                                </span>
                            </div>
                            <!-- END: action -->
                            <!-- BEGIN: exportfile -->
                            <input type="button" class="btn btn-primary" value="{LANG.export}" name="data_export" />
                            <!-- END: exportfile -->
                            <!-- BEGIN: generate_page -->
                            {GENERATE_PAGE}
                            <!-- END: generate_page -->
                        </td>
                    </tr>
                </tfoot>
                <!-- END: footer -->
            </table>
        </div>
    </form>
</div>
<div id="pass-reset-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{LANG.pass_reset_request}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="userid" value="0" />
                <p>{GLANG.username}: <span class="username"></span></p>
                <p>{LANG.currentpass_created_time}: <span class="currentpass-created-time"></span></p>
                <p>{LANG.currentpass_request_status}: <span class="currentpass-request-status"></span></p>
                <p><a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="passResetRequestSubmit(event, this, 1);">{LANG.pass_reset_request1_send}</a><span class="fa fa-spinner fa-spin m-left" style="display:none"></span></p>
                <p><a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="passResetRequestSubmit(event, this, 2);">{LANG.pass_reset_request2_send}</a><span class="fa fa-spinner fa-spin m-left" style="display:none"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
            </div>
        </div>
    </div>
</div>
<div id="email-reset-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{GLANG.close}"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{LANG.email_reset_request}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="userid" value="0" />
                <p>{GLANG.username}: <span class="username"></span></p>
                <p>{LANG.currentemail_created_time}: <span class="currentemail-created-time"></span></p>
                <p>{LANG.currentemail_request_status}: <span class="currentemail-request-status"></span></p>
                <p><a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="emailResetRequestSubmit(event, this, 1);">{LANG.email_reset_request1_send}</a><span class="fa fa-spinner fa-spin m-left" style="display:none"></span></p>
                <p><a class="btn btn-primary btn-xs" href="javascript:void(0);" onclick="emailResetRequestSubmit(event, this, 2);">{LANG.email_reset_request2_send}</a><span class="fa fa-spinner fa-spin m-left" style="display:none"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var export_note = '{LANG.export_note}';
    var export_complete = '{LANG.export_complete}';
</script>
<!-- END: main -->
