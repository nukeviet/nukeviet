<!-- BEGIN: main -->
<div id="users">
    <!-- BEGIN: is_forum -->
    <div class="alert alert-warning">{LANG.modforum}</div>
    <!-- END: is_forum -->
    <div class="well">
        <form action="{FORM_ACTION}" method="get">
            <input name="{NV_NAME_VARIABLE}" type="hidden" value="{MODULE_NAME}" />
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <input class="form-control" type="text" name="value" value="{SEARCH_VALUE}" id="f_value" placeholder="{LANG.search_key}" />
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <select class="form-control" name="method" id="f_method">
                            <option value="">---{LANG.search_type}---</option>
                            <!-- BEGIN: method -->
                            <option value="{METHODS.key}"{METHODS.selected}>{METHODS.value}</option>
                            <!-- END: method -->
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="form-group">
                        <select class="form-control" name="usactive">
                            <option value="-1">---{LANG.usactive}---</option>
                            <option value="-2"{SELECTED_NEW_USERS}>{GLANG.level7}</option>
                            <!-- BEGIN: usactive -->
                            <option value="{USACTIVE.key}"{USACTIVE.selected}>{USACTIVE.value}</option>
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
                <div class="col-xs-12 col-md-4">
                    <div class="form-group">
                        <input class="btn btn-primary" name="search" type="submit" value="{LANG.submit}" />
                    </div>
                </div>
            </div>
            <label><em>{LANG.search_note}</em></label>
        </form>
    </div>
    <form>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <caption><em class="fa fa-file-text-o">&nbsp;</em>{TABLE_CAPTION}</caption>
                <thead>
                    <tr>
                        <th class="w20"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);" /></th>
                        <th class="w50"><a href="{HEAD.userid.href}">{HEAD.userid.title}</a></th>
                        <th><a href="{HEAD.username.href}">{HEAD.username.title}</a> / <a href="{HEAD.full_name.href}">{HEAD.full_name.title}</a></th>
                        <th><a href="{HEAD.email.href}">{HEAD.email.title}</a></th>
                        <th><a href="{HEAD.regdate.href}">{HEAD.regdate.title}</a></th>
                        <th class="text-center w100">{LANG.memberlist_active}</th>
                        <th class="text-center w100">{LANG.funcs}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: xusers -->
                    <tr>
                        <td class="align-middle"><!-- BEGIN: choose --><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{CONTENT_TD.userid}" name="idcheck[]" /><!-- END: choose --></td>
                        <td class="align-middle"> {CONTENT_TD.userid} </td>
                        <td>
                            <!-- BEGIN: is_admin -->
                            <img style="vertical-align:middle;" alt="{CONTENT_TD.level}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/{CONTENT_TD.img}.png" width="38" height="18" />
                            <!-- END: is_admin -->
                            <!-- BEGIN: view --><a href="{CONTENT_TD.link}" target="_blank">{CONTENT_TD.username}</a><!-- END: view -->
                            <!-- BEGIN: show -->{CONTENT_TD.username}<!-- END: show -->
                            <div class="mt-1">{CONTENT_TD.full_name}</div>
                        </td>
                        <td>
                            <a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a>
                            <div class="mt-1">{CONTENT_TD.info_verify}</div>
                        </td>
                        <td>
                            <span class="text-info">{CONTENT_TD.regdate}</span>
                            <div class="mt-1">{CONTENT_TD.active_obj}</div>
                        </td>
                        <td class="text-center align-middle"><input type="checkbox" name="active" id="change_status_{CONTENT_TD.userid}" value="{CONTENT_TD.userid}"{CONTENT_TD.checked}{CONTENT_TD.disabled} /></td>
                        <td class="text-center align-middle">
                            <!-- BEGIN: set_official -->
                            <a data-toggle="tooltip" title="{LANG.set_official_note}" href="javascript:void(0);" class="btn btn-xs btn-info" onclick="nv_set_official({CONTENT_TD.userid});"><em class="fa fa-user"></em></a>
                            <!-- END: set_official -->
                            <!-- BEGIN: edit -->
                            <div class="btn-group">
                                <a class="btn btn-xs btn-warning text-white" href="{EDIT_URL}" data-toggle="tooltip" title="{LANG.memberlist_edit}"><em class="fa fa-edit"></em></a>
                                <button type="button" class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">{LANG.memberlist_edit}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="{EDIT_OAUTH_URL}">{LANG.user_openid_mamager}</a></li>
                                    <li><a href="{EDIT_2STEP_URL}">{LANG.user_2step_mamager}</a></li>
                                </ul>
                            </div>
                            <!-- END: edit -->
                            <!-- BEGIN: del -->
                            <a data-toggle="tooltip" title="{LANG.delete}" href="javascript:void(0);" class="btn btn-xs btn-danger" onclick="nv_row_del({CONTENT_TD.userid});"><em class="fa fa-trash-o"></em></a>
                            <!-- END: del -->
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
                            <div class="pull-left margin-right form-inline">
                                <select class="form-control w150" id="mainuseropt">
                                    <!-- BEGIN: loop --><option value="{ACTION_KEY}">{ACTION_LANG}</option><!-- END: loop -->
                                </select>
                                <input type="button" class="btn btn-primary" value="{LANG.read_submit}" id="mainusersaction" data-msgnocheck="{LANG.msgnocheck}"/>
                            </div>
                            <!-- END: action -->
                            <!-- BEGIN: exportfile -->
                            <input type="button" class="btn btn-primary" value="{LANG.export}" name="data_export"/>
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
<script type="text/javascript">
var export_note = '{LANG.export_note}';
var export_complete = '{LANG.export_complete}';
</script>
<!-- END: main -->
