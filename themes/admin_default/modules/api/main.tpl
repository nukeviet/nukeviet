<!-- BEGIN: main -->
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div id="my-role-api" data-page-url="{PAGE_URL}">
    <div class="tools">
        <div>
            <ul class="nav nav-pills m-bottom">
                <li role="presentation" class="{TYPE_PUBLIC.active}"><a href="{TYPE_PUBLIC.url}">{TYPE_PUBLIC.name}</a></li>
                <li role="presentation" class="{TYPE_PRIVATE.active}"><a href="{TYPE_PRIVATE.url}">{TYPE_PRIVATE.name}</a></li>
            </ul>
        </div>
        <div>
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#credential_auth"><i class="fa fa-shield fa-lg text-danger"></i> {LANG.authentication}</button>
            <!-- START FORFOOTER -->
            <div id="credential_auth" tabindex="-1" role="dialog" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                            <div class="modal-title"><strong>{LANG.authentication}</strong></div>
                        </div>
                        <div class="modal-body">
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
                                    <button type="button" class="btn btn-primary btn-block create_authentication">
                                        <!-- BEGIN: not_access_authentication -->{LANG.create_access_authentication}
                                        <!-- END: not_access_authentication -->
                                        <!-- BEGIN: created_access_authentication -->{LANG.recreate_access_authentication}
                                        <!-- END: created_access_authentication -->
                                    </button>
                                </div>
                            </div>
                            <div class="api_ips" <!-- BEGIN: not_access_authentication2 --> style="display:none"
                                <!-- END: not_access_authentication2 -->>
                                <div class="form-group">
                                    <label><strong>{LANG.api_ips}</strong></label>
                                    <textarea class="form-control" name="ips">{API_USER.ips}</textarea>
                                    <div class="help-block">{LANG.api_ips_help}</div>
                                </div>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary api_ips_update">{LANG.api_ips_update}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END FORFOOTER -->
        </div>
    </div>

    <!-- BEGIN: remote_api_off -->
    <div class="alert alert-danger">
        {LANG.api_remote_off2}
    </div>
    <!-- END: remote_api_off -->

    <!-- BEGIN: role_empty -->
    <div class="alert alert-info text-center">
        {LANG.api_roles_empty}
    </div>
    <!-- END: role_empty -->
    <!-- BEGIN: rolelist -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary">
                <tr>
                    <th class="text-nowrap text-center" style="vertical-align:middle">{LANG.api_roles_list}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_roles_allowed}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_object}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_status}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_status}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_addtime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_access_count}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_last_access}</th>
                    <!-- BEGIN: is_public -->
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
                    <!-- END: is_public -->
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: role -->
                <tr class="item<!-- BEGIN: credential_status_not_activated --> text-muted<!-- END: credential_status_not_activated -->" data-role-id="{ROLE.role_id}">
                    <td>
                        <strong>{ROLE.role_title}</strong>
                        <!-- BEGIN: description -->
                        <p class="description">{ROLE.role_description}</p>
                        <!-- END: description -->
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-default btn-block" data-toggle="modal" data-target="#apiroledetail{ROLE.role_id}">{ROLE.apitotal}</button>
                        <!-- START FORFOOTER -->
                        <div id="apiroledetail{ROLE.role_id}" tabindex="-1" role="dialog" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                                        <div class="modal-title"><strong>{LANG.api_roles_detail}: {ROLE.role_title}</strong></div>
                                    </div>
                                    <div class="modal-body">
                                        <!-- BEGIN: catsys -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {LANG.api_of_system}: {CAT_DATA.title}</strong></div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <!-- BEGIN: loop -->
                                                    <div class="col-sm-12">
                                                        <div class="text-truncate m-bottom"><i class="fa fa-caret-right"></i> {API_DATA}</div>
                                                    </div>
                                                    <!-- END: loop -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: catsys -->

                                        <!-- BEGIN: apimod -->
                                        <!-- BEGIN: mod -->
                                        <div class="panel panel-default">
                                            <div class="panel-heading"><strong><i class="fa fa-folder-open-o"></i> {MOD_TITLE}
                                                    <!-- BEGIN: title --> <i class="fa fa-angle-right"></i> {CAT_DATA.title}
                                                    <!-- END: title -->
                                                </strong></div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <!-- BEGIN: loop -->
                                                    <div class="col-sm-12">
                                                        <div class="text-truncate m-bottom" title="{API_DATA}"><i class="fa fa-caret-right"></i> {API_DATA}</div>
                                                    </div>
                                                    <!-- END: loop -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: mod -->
                                        <!-- END: apimod -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END FORFOOTER -->
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.object}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.status}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_status_format}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_addtime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_access_count}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_last_access}</td>
                    <!-- BEGIN: is_public -->
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <!-- BEGIN: activate -->
                        <button type="button" class="btn btn-default btn-block credential-activate">{LANG.activate}</button>
                        <!-- END: activate -->
                        <!-- BEGIN: deactivate -->
                        <button type="button" class="btn btn-default btn-block credential-deactivate">{LANG.deactivate}</button>
                        <!-- END: deactivate -->
                    </td>
                    <!-- END: is_public -->
                </tr>
                <!-- END: role -->
            </tbody>
        </table>
        <!-- BEGIN: generate_page -->
        <div class="text-center">
            {GENERATE_PAGE}
        </div>
        <!-- END: generate_page -->
    </div>
    <!-- END: rolelist -->
</div>
<!-- END: main -->