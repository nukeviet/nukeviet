<!-- BEGIN: main -->
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<!-- BEGIN: remote_api_off -->
<div class="alert alert-danger">
    {REMOTE_API_OFF}
</div>
<!-- END: remote_api_off -->
<div id="credentiallist" data-page-url="{PAGE_URL}" data-role-id="{ROLE_ID}">
    <div class="row m-bottom">
        <div class="col-sm-12">
            <div class="input-group m-bottom">
                <span class="input-group-addon">{LANG.api_role}</span>
                <select class="form-control role-id">
                    <option value="-1">{LANG.api_role_select}</option>
                    <!-- BEGIN: api_role -->
                    <option value="{ROLE.role_id}" {ROLE.sel}>{ROLE.title}</option>
                    <!-- END: api_role -->
                </select>
            </div>
        </div>
        <!-- BEGIN: add_credential_button -->
        <div class="col-sm-12 text-right">
            <button type="button" class="btn btn-primary m-bottom" data-toggle="modal" data-target="#credential-add">{LANG.api_role_credential_add}</a>
        </div>
        <!-- START FORFOOTER -->
        <div id="credential-add" role="dialog" class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                        <div class="modal-title"><strong>{LANG.api_role_credential_add}</strong></div>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="{ADD_CREDENTIAL_URL}" data-get-user-url="{GET_USER_URL}" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-6 control-label">{CREDENTIAL_ADD_LABEL}</label>
                                <div class="col-sm-18">
                                    <select class="form-control" style="width: 100%;" name="userid" data-placeholder="{LANG.api_role_credential_search}">
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-6 col-sm-18">
                                    <button type="submit" class="btn btn-default">{GLANG.submit}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END FORFOOTER -->
        <!-- END: add_credential_button -->
    </div>
    <!-- BEGIN: is_role -->
    <!-- BEGIN: credential_empty -->
    <div class="alert alert-info text-center">
        {LANG.api_role_credential_empty}
    </div>
    <!-- END: credential_empty -->
    <!-- BEGIN: credentials -->
    <div class="m-bottom">{LANG.api_role_credential_count}: {CREDENTIAL_COUNT}</div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr class="bg-primary">
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_userid}</th>
                    <th style="vertical-align:middle">{LANG.api_role_credential_username}</th>
                    <th style="vertical-align:middle">{LANG.api_role_credential_fullname}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_addtime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_access_count}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_last_access}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.status}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="item" data-userid="{CREDENTIAL.userid}">
                    <td class="text-nowrap text-center" style="width: 1%;">{CREDENTIAL.userid}</td>
                    <td>
                        <!-- BEGIN: is_admin -->
                        <img alt="Admin level" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{CREDENTIAL.level}.png" width="38" height="18" />
                        <!-- END: is_admin -->
                        {CREDENTIAL.username}
                    </td>
                    <td>{CREDENTIAL.fullname}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{CREDENTIAL.addtime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{CREDENTIAL.access_count}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{CREDENTIAL.last_access}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <select class="form-control w100 change-status">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.val}" {STATUS.sel}>{STATUS.title}</option>
                            <!-- END: status -->
                        </select>
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-default" data-toggle="credentialDel" data-confirm="{LANG.deprivation_confirm}" title="{LANG.deprivation}"><i class="fa fa-ban"></i></button>
                        <button type="button" class="btn btn-default" data-toggle="changeAuth" title="{LANG.authentication}"><i class="fa fa-shield"></i></button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="8" class="text-center">
                        {GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
    <!-- END: credentials -->
    <!-- END: is_role -->
</div>
<!-- START FORFOOTER -->
<div id="changeAuth" role="dialog" class="modal fade">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                <div class="modal-title"></div>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<!-- END: main -->

<!-- BEGIN: role_empty -->
<meta http-equiv="refresh" content="5;{ADD_API_ROLE_URL}">
<div class="alert alert-info text-center">
    {LANG.api_roles_empty2}
    <img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif" alt="Loading" />
</div>
<!-- END: role_empty -->

<!-- BEGIN: changeAuth -->
<div class="form-group">
    <label><strong>{LANG.api_credential_ident}</strong></label>
    <div class="input-group">
        <input type="text" name="ident" id="credential_ident" value="{API_USER.ident}" class="form-control bg-white" readonly="readonly">
        <div class="input-group-btn">
            <button class="btn btn-default active" type="button" data-clipboard-target="#credential_ident" id="credential_ident_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
        </div>
    </div>
</div>
<div class="form-group">
    <label><strong>{LANG.api_credential_secret}</strong></label>
    <div class="input-group">
        <input type="text" name="secret" id="credential_secret" value="" class="form-control bg-white" readonly="readonly">
        <div class="input-group-btn">
            <button class="btn btn-default active" type="button" data-clipboard-target="#credential_secret" id="credential_secret_btn" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
        </div>
    </div>
</div>
<div class="m-bottom">
    <label class="auth-info control-label mb-0" data-default="{AUTH_INFO}" data-error="{LANG.auth_method_select}">{AUTH_INFO}</label>
</div>
<div class="row m-bottom">
    <div class="col-xs-12">
        <select class="form-control" name="method">
            <option value="">{LANG.auth_method}</option>
            <!-- BEGIN: method -->
            <option value="{METHOD.key}"{METHOD.sel}>{METHOD.name}</option>
            <!-- END: method -->
        </select>
    </div>
    <div class="col-xs-12">
        <button type="button" class="btn btn-primary btn-block create_authentication" data-userid="{USERID}">
            <!-- BEGIN: not_access_authentication -->{LANG.create_access_authentication}<!-- END: not_access_authentication -->
            <!-- BEGIN: created_access_authentication -->{LANG.recreate_access_authentication}<!-- END: created_access_authentication -->
        </button>
    </div>
</div>
<div class="api_ips" <!-- BEGIN: not_access_authentication2 --> style="display:none"<!-- END: not_access_authentication2 -->>
    <div class="form-group">
        <label><strong>{LANG.api_ips}</strong></label>
        <textarea class="form-control" name="ips">{API_USER.ips}</textarea>
        <div class="help-block">{LANG.api_ips_help}</div>
    </div>
    <div class="text-center">
        <button type="button" class="btn btn-primary api_ips_update" data-userid="{USERID}">{LANG.api_ips_update}</button>
    </div>
</div>
<!-- END: changeAuth -->