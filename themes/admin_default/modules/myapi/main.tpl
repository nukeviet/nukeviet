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
                            <div class="m-bottom"><strong>{LANG.auth_method}</strong></div>
                            <ul class="nav nav-tabs m-bottom" role="tablist">
                                <!-- BEGIN: method_tab -->
                                <li role="presentation" class="<!-- BEGIN: is_active -->active<!-- END: is_active -->"><a href="#{METHOD.key}-panel" aria-controls="{METHOD.key}-panel" role="tab" data-toggle="tab">{METHOD.name}</a></li>
                                <!-- END: method_tab -->
                            </ul>

                            <div class="tab-content">
                                <!-- BEGIN: method_panel -->
                                <div role="tabpanel" class="tab-pane<!-- BEGIN: is_active --> active<!-- END: is_active -->" id="{METHOD.key}-panel">
                                    <div class="form-group">
                                        <label><strong>{LANG.api_credential_ident}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{METHOD.key}_ident" id="{METHOD.key}-credential_ident" value="{METHOD.ident}" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{METHOD.key}-credential_ident" data-toggle="clipboard" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><strong>{LANG.api_credential_secret}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{METHOD.key}_secret" id="{METHOD.key}-credential_secret" value="" class="form-control bg-white" readonly="readonly">
                                            <div class="input-group-btn">
                                                <button class="btn btn-default active" type="button" data-clipboard-target="#{METHOD.key}-credential_secret" data-toggle="clipboard" data-title="{LANG.value_copied}" data-placement="left" data-container="body" data-trigger="manual" data-animation="false"><i class="fa fa-copy"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- BEGIN: isEditLevel -->
                                    <div class="row m-bottom">
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-primary btn-block create_authentication" data-method="{METHOD.key}">{LANG.create_access_authentication}</button>
                                        </div>
                                        <div class="col-xs-12">
                                            <button type="button" class="btn btn-danger btn-block delete_authentication" data-method="{METHOD.key}">{LANG.delete_authentication}</button>
                                        </div>
                                    </div>
                                    <!-- END: isEditLevel -->

                                    <div class="api_ips" style="<!-- BEGIN: not_access_authentication -->display:none<!-- END: not_access_authentication -->">
                                        <div class="form-group">
                                            <label><strong>{LANG.api_ips}</strong></label>
                                            <textarea class="form-control ips" name="{METHOD.key}_ips">{METHOD.ips}</textarea>
                                            <div class="help-block">{LANG.api_ips_help}</div>
                                        </div>
                                        <!-- BEGIN: isEditLevel2 -->
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary api_ips_update" data-method="{METHOD.key}">{LANG.api_ips_update}</button>
                                        </div>
                                        <!-- END: isEditLevel2 -->
                                    </div>
                                </div>
                                <!-- END: method_panel -->
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
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_object}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_status}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_status}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_addtime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.endtime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.quota}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_access_count}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{LANG.api_role_credential_last_access}</th>
                    <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
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
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.object}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.status}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_status_format}</td>
                    <td class="text-center" style="width: 1%;">{ROLE.credential_addtime}</td>
                    <td class="text-center" style="width: 1%;">{ROLE.credential_endtime}</td>
                    <td class="text-center" style="width: 1%;">{ROLE.credential_quota}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_access_count}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.credential_last_access}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#apiroledetail{ROLE.role_id}">{LANG.api_roles_allowed}</button>
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

                                        <div>
                                            <ul class="nav nav-tabs m-bottom" role="tablist">
                                                <!-- BEGIN: forlang -->
                                                <li role="presentation" class="{FORLANG.active}"><a id="forlang-{FORLANG.langkey}-{ROLE.id}-tab" href="#forlang-{FORLANG.langkey}-{ROLE.id}" aria-controls="forlang-{FORLANG.langkey}-{ROLE.id}" role="tab" data-toggle="tab" aria-expanded="{FORLANG.expanded}">{FORLANG.langname}</a></li>
                                                <!-- END: forlang -->
                                            </ul>
                                            <div class="tab-content">
                                                <!-- BEGIN: tabcontent_forlang -->
                                                <div role="tabpanel" class="tab-pane fade{FORLANG.in}" id="forlang-{FORLANG.langkey}-{ROLE.id}" aria-labelledby="forlang-{FORLANG.langkey}-{ROLE.id}-tab">
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
                                                <!-- END: tabcontent_forlang -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END FORFOOTER -->
                        <!-- BEGIN: is_public -->
                        <!-- BEGIN: activate -->
                        <button type="button" class="btn btn-default credential-activate">{LANG.activate}</button>
                        <!-- END: activate -->
                        <!-- BEGIN: deactivate -->
                        <button type="button" class="btn btn-default credential-deactivate">{LANG.deactivate}</button>
                        <!-- END: deactivate -->
                        <!-- END: is_public -->
                    </td>
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