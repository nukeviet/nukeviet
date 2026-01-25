<!-- BEGIN: main -->
<!-- BEGIN: remote_api_off -->
<div class="alert alert-danger">
    {REMOTE_API_OFF}
</div>
<!-- END: remote_api_off -->
<div id="rolelist" data-page-url="{PAGE_URL}">
    <div class="row m-bottom">
        <div class="col-sm-12">
            <div class="d-inline-block">
                <div class="input-group w200 m-bottom">
                    <span class="input-group-addon">{LANG.api_role_type}</span>
                    <select class="form-control role-type">
                        <option value="">{LANG.all}</option>
                        <!-- BEGIN: role_type -->
                        <option value="{TYPE.key}" {TYPE.sel}>{TYPE.name}</option>
                        <!-- END: role_type -->
                    </select>
                </div>
            </div>
            <div class="d-inline-block">
                <div class="input-group w200 m-bottom">
                    <span class="input-group-addon">{LANG.api_role_object}</span>
                    <select class="form-control role-object">
                        <option value="">{LANG.all}</option>
                        <!-- BEGIN: role_object -->
                        <option value="{OBJECT.key}" {OBJECT.sel}>{OBJECT.name}</option>
                        <!-- END: role_object -->
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-right">
            <a href="{ADD_API_ROLE_URL}" class="btn btn-primary m-bottom">{LANG.add_role}</a>
        </div>
    </div>

    <!-- BEGIN: role_list -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="bg-primary">
                <tr>
                    <th class="text-nowrap text-center">{LANG.api_roles_title}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.api_role_type}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.api_role_object}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.api_addtime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.api_edittime}</th>
                    <th class="text-nowrap text-center" style="width: 1%;">{LANG.status}</th>
                    <th class="text-nowrap text-center" style="width: 1%"></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr class="item" data-id="{ROLE.id}">
                    <td>{ROLE.title}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.type}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.object}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.addtime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">{ROLE.edittime}</td>
                    <td class="text-nowrap text-center" style="width: 1%;">
                        <select class="form-control w100 change-status">
                            <!-- BEGIN: status -->
                            <option value="{STATUS.val}" {STATUS.sel}>{STATUS.title}</option>
                            <!-- END: status -->
                        </select>
                    </td>
                    <td class="text-nowrap text-center" style="width: 1%">
                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#apiroledetail{ROLE.id}">{LANG.api_roles_allowed}</button>
                        <!-- START FORFOOTER -->
                        <div id="apiroledetail{ROLE.id}" tabindex="-1" role="dialog" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="close"><span class="fa fa-times"></span></button>
                                        <div class="modal-title"><strong>{LANG.api_roles_detail}: {ROLE.title}</strong></div>
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
                        <a href="{ADD_API_ROLE_URL}&amp;id={ROLE.id}" class="btn btn-default"><i class="fa fa-pencil"></i> {GLANG.edit}</a>
                        <button type="button" class="btn btn-default" data-toggle="apiroledel"><i class="fa fa-trash-o"></i> {GLANG.delete}</button>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="7" class="text-center">
                        {GENERATE_PAGE}
                    </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
        </table>
    </div>
    <!-- END: role_list -->

    <!-- BEGIN: role_list_empty -->
    <div class="alert alert-info text-center">
        {LANG.api_roles_empty}
    </div>
    <!-- END: role_list_empty -->
</div>
<!-- END: main -->

<!-- BEGIN: role -->
<form method="post" action="{FORM_ACTION}" autocomplete="off" id="role">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <td class="left-col">{LANG.api_roles_title} <span class="text-danger">*</span>:</td>
                    <td><input type="text" id="role_title" name="role_title" value="{DATA.role_title}" class="form-control w350" maxlength="250"></td>
                </tr>
                <tr>
                    <td class="left-col">{LANG.api_roles_description}:</td>
                    <td><textarea class="form-control w350" id="role_description" name="role_description" rows="2" maxlength="250">{DATA.role_description}</textarea></td>
                </tr>
                <tr>
                    <td class="left-col">{LANG.api_role_type}:</td>
                    <td>
                        <div class="role_type">
                            <label><input type="radio" name="role_type" value="private" {DATA.role_type_private_checked}> {LANG.api_role_type_private}</label>
                            <label><input type="radio" name="role_type" value="public" {DATA.role_type_public_checked}> {LANG.api_role_type_public}</label>
                        </div>
                        <ul class="role_note note">
                            <li>{LANG.api_role_type_private_note}</li>
                            <li>{LANG.api_role_type_public_note}</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="left-col">{LANG.api_role_object}:</td>
                    <td>
                        <div class="role_type">
                            <label><input type="radio" name="role_object" value="admin" {DATA.role_object_admin_checked}> {LANG.api_role_object_admin}</label>
                            <label><input type="radio" name="role_object" value="user" {DATA.role_object_user_checked}> {LANG.api_role_object_user}</label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="left-col">{LANG.log_period}:</td>
                    <td>
                        <div class="input-group" style="width: fit-content;">
                            <input type="text" class="form-control w100 number" name="log_period" value="{DATA.log_period}" maxlength="10">
                            <span class="input-group-addon" style="border-left: 0;">{LANG.hours}</span>
                        </div>
                        <div class="help-block mb-0">{LANG.log_period_note}</div>
                    </td>
                </tr>
                <tr>
                    <td class="left-col">{LANG.flood_blocker}:</td>
                    <td class="items">
                        <!-- BEGIN: flood_rule -->
                        <div class="flood_rule item">
                            <div class="input-group" style="width: fit-content;">
                                <span class="input-group-addon">{LANG.flood_limit}</span>
                                <input type="text" class="form-control w100 number" name="flood_rules_limit[]" value="{RULE.limit}" maxlength="15">
                                <span class="input-group-addon" style="border-left: 0;">{LANG.flood_interval}</span>
                                <input type="text" class="form-control w100 number" style="border-left: 0;" name="flood_rules_interval[]" value="{RULE.interval}" maxlength="10">
                                <span class="input-group-addon" style="border-left: 0;">{LANG.minutes}</span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default del-rule" type="button"><em class="fa fa-minus"></em></button>
                                    <button class="btn btn-default add-rule" type="button"><em class="fa fa-plus"></em></button>
                                </span>
                            </div>
                        </div>
                        <!-- END: flood_rule -->
                        <div class="help-block mb-0">{LANG.flood_blocker_note}</div>
                    </td>
                </tr>
            </tbody>
            <tbody id="apicheck">{APICHECK}</tbody>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <select name="save" class="form-control" style="display:inline-block;width:fit-content">
                            <!-- BEGIN: saveopt -->
                            <option value="{SAVEOPT.val}">{SAVEOPT.name}</option>
                            <!-- END: saveopt -->
                        </select>
                        <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: role -->

<!-- BEGIN: apicheck -->
<tr>
    <td colspan="2">
        {LANG.api_roles_allowed}: <span class="total-api-enabled api-count{TOTAL_API_CHECKED}">{TOTAL_API_ENABLED}</span>
    </td>
</tr>
<tr>
    <td class="root-api-actions left-col">
        <ul class="nav nav-pills nav-stacked">
            <!-- BEGIN: api_tree -->
            <li role="presentation" class="<!-- BEGIN: active -->active<!-- END: active -->"><a role="tab" data-toggle="tab" data-cat="{API_TREE.key}" href="#{API_TREE.href}" aria-controls="{API_TREE.href}" aria-expanded="{API_TREE.expanded}" class="main"><i class="fa fa-folder-open-o"></i> {API_TREE.name}
                    <!-- BEGIN: total_api --> <span class="api-count{API_TREE.api_checked}"><span class="total_api">{API_TREE.total_api}</span>/{API_TREE.total}</span><!-- END: total_api -->
                </a></li>
            <!-- BEGIN: sub -->
            <li role="presentation" class="<!-- BEGIN: active -->active<!-- END: active -->"><a role="tab" data-toggle="tab" data-cat="{SUB.key}" href="#{SUB.href}" aria-controls="api-child-{SUB.key}" aria-expanded="{SUB.expanded}" class="sub">{SUB.name}
                    <!-- BEGIN: total_api --> <span class="api-count{SUB.api_checked}"><span class="total_api">{SUB.total_api}</span>/{SUB.total}</span><!-- END: total_api -->
                </a></li>
            <!-- END: sub -->
            <!-- END: api_tree -->
        </ul>
    </td>
    <td class="tab-content child-apis" style="background-color:white;">
        <!-- BEGIN: api_content -->
        <div role="tabpanel" class="tab-pane child-apis-item<!-- BEGIN: active --> active<!-- END: active -->" id="{API_CONTENT.id}">
            <table class="table table-bordered">
                <tbody>
                    <tr class="apilist">
                        <th style="width: 1%;"><input type="checkbox" class="form-control checkall" title="{LANG.api_roles_checkall}" {API_CONTENT.checkall} /></th>
                        <th>{LANG.cat_api_list}</th>
                    </tr>
                    <!-- BEGIN: api -->
                    <tr class="item">
                        <td style="width: 1%;"><input type="checkbox" class="form-control checkitem" name="api_{API_CONTENT.input_key}[]" id="api_{API.cmd}" value="{API.cmd}" {API.checked} /></td>
                        <td><label for="api_{API.cmd}" class="pointer mb-0">{API.cmd} - {API.name}</label></td>
                    </tr>
                    <!-- END: api -->
                </tbody>
            </table>
        </div>
        <!-- END: api_content -->
        <div role="tabpanel" class="tab-pane" id="empty-content"></div>
    </td>
</tr>
<!-- END: apicheck -->