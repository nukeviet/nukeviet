{if !empty($IS_MAIN)}
<!-- BEGIN: main -->
<!-- BEGIN: remote_api_off -->
{if empty($GCONFIG.remote_api_access)}
<div class="alert alert-danger">
    {$REMOTE_API_OFF}
</div>
{/if}
<!-- END: remote_api_off -->
<div id="rolelist" data-page-url="{$PAGE_URL}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-group w200">
                                <span class="input-group-text">{$LANG->getModule('api_role_type')}</span>
                                <select class="form-control role-type">
                                    <option value="">{$LANG->getModule('all')}</option>
                                    {foreach $TYPES as $TYPE}
                                    <!-- BEGIN: role_type -->
                                    <option value="{$TYPE}" {if $TYPE == $TYPE_API}selected="selected"{/if}>{$LANG->getModule("api_role_type_$TYPE")}</option>
                                    <!-- END: role_type -->
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group w200">
                                <span class="input-group-text">{$LANG->getModule('api_role_object')}</span>
                                <select class="form-control role-object">
                                    <option value="">{$LANG->getModule('all')}</option>
                                    {foreach $OBJECTS as $OBJECT}
                                    <!-- BEGIN: role_object -->
                                    <option value="{$OBJECT}" {if $OBJECT == $OBJECT_API}selected="selected"{/if}>{$LANG->getModule("api_role_object_$OBJECT")}</option>
                                    <!-- END: role_object -->
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <a href="{$ADD_API_ROLE_URL}" class="btn btn-primary mb-3">{$LANG->getModule('add_role')}</a>
                </div>
            </div>
        </div>
        {if empty($ROLE_LIST)}
        <div class="card-body">
            <!-- BEGIN: role_list_empty -->
            <div class="alert alert-info text-center">
                {$LANG->getModule('api_roles_empty')}
            </div>
            <!-- END: role_list_empty -->
        </div>
        {else}
        <!-- BEGIN: role_list -->
    
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-nowrap text-center">{$LANG->getModule('api_roles_title')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_type')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_object')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_edittime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $ROLE_LIST as $ROLE}
                        <!-- BEGIN: loop -->
                        <tr class="item" data-id="{$ROLE.id}">
                            <td>{$ROLE.title}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.type}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.object}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.addtime}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$ROLE.edittime}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">
                                <select class="form-control w100 change-status">
                                    {foreach [$LANG->getModule('inactive'), $LANG->getModule('active')] as $K_STATUS => $STATUS}
                                    <!-- BEGIN: status -->
                                    <option value="{$K_STATUS}" {if $K_STATUS == $ROLE.status}selected="selected"{/if}>{$STATUS}</option>
                                    <!-- END: status -->
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-nowrap text-center" style="width: 1%">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#apiroledetail{$ROLE.id}">{$LANG->getModule('api_roles_allowed')}</button>
                                <!-- START FORFOOTER -->
                                <div id="apiroledetail{$ROLE.id}" tabindex="-1" role="dialog" class="modal fade">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title"><strong>{$LANG->getModule('api_roles_detail')}: {$ROLE.title}</strong></div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- if (!empty($role['apis'][''])) {
        //         foreach ($role['apis'][''] as $cat_data) {
        //             $xtpl->assign('CAT_DATA', $cat_data);
        
        //             foreach ($cat_data['apis'] as $api_data) {
        //                 $xtpl->assign('API_DATA', $api_data);
        //                 $xtpl->parse('main.role_list.loop.catsys.loop');
        //             }
        
        //             $xtpl->parse('main.role_list.loop.catsys');
        //         }
        //     }-->                             
                                                {if !empty($ROLE.apis[''])}
                                                {foreach $ROLE.apis[''] as $CAT_DATA}
                                                <!-- BEGIN: catsys -->
                                                <div class="card">
                                                    <div class="card-header"><strong><i class="fa fa-folder-open-o"></i> {$LANG->getModule('api_of_system')}: {$CAT_DATA.title}</strong></div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            {foreach $CAT_DATA.apis as $API_DATA}
                                                            <!-- BEGIN: loop -->
                                                            <div class="col-sm-6">
                                                                <div class="text-truncate mb-3"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                            </div>
                                                            <!-- END: loop -->
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END: catsys -->
                                                {/foreach}
                                                {/if}
                                                {assign var='FORLANGS' value=[]}
                                                {foreach $GCONFIG.setup_langs as $KEY_LANG => $_LG}
                                                    {if $_LG == $smarty.const.NV_LANG_DATA}
                                                        {append var='FORLANGS' value=['active' => 'active', 'in' => ' in active', 'expanded' => 'true', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {else}
                                                        {append var='FORLANGS' value=['active' => '', 'in' => '', 'expanded' => 'false', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {/if}
                                                {/foreach}
                                                <div>
                                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                                        {foreach $FORLANGS as $FORLANG}
                                                        <!-- BEGIN: forlang -->
                                                        <li role="presentation" class="nav-item"><a id="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab" href="#forlang-{$FORLANG.langkey}-{$ROLE.id}" class="nav-link {$FORLANG.active}" aria-controls="forlang-{$FORLANG.langkey}-{$ROLE.id}" role="tab" data-bs-toggle="tab" aria-expanded="{$FORLANG.expanded}">{$FORLANG.langname}</a></li>
                                                        <!-- END: forlang -->
                                                        {/foreach}
                                                    </ul>
                                                    <div class="tab-content">
                                                        {foreach $FORLANGS as $_LG => $FORLANG}
                                                        <!-- BEGIN: tabcontent_forlang -->
                                                        <div role="tabpanel" class="tab-pane fade{$FORLANG.in}" id="forlang-{$FORLANG.langkey}-{$ROLE.id}" aria-labelledby="forlang-{$FORLANG.langkey}-{$ROLE.id}-tab">
                                                            {if !empty($ROLE.apis.$_LG)}
                                                            {foreach $ROLE.apis.$_LG as $MOD_TITLE => $MOD_DATA}
                                                            {foreach $MOD_DATA as $CAT_DATA}
                                                            <!-- BEGIN: apimod -->
                                                            <!-- BEGIN: mod -->
                                                            <div class="card">
                                                                <div class="card-header"><strong><i class="fa fa-folder-open-o"></i> {$SITE_MOD.$MOD_TITLE.custom_title}
                                                                        {if !empty($CAT_DATA.title)}
                                                                        <!-- BEGIN: title --> <i class="fa fa-angle-right"></i> {$CAT_DATA.title}
                                                                        <!-- END: title -->
                                                                        {/if}
                                                                    </strong></div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        {foreach $CAT_DATA.apis as $API_DATA}
                                                                        <!-- BEGIN: loop -->
                                                                        <div class="col-sm-6">
                                                                            <div class="text-truncate mb-3" title="{$API_DATA}"><i class="fa fa-caret-right"></i> {$API_DATA}</div>
                                                                        </div>
                                                                        <!-- END: loop -->
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- END: mod -->
                                                            <!-- END: apimod -->
                                                            {/foreach}
                                                            {/foreach}
                                                            {/if}
                                                        </div>
                                                        <!-- END: tabcontent_forlang -->
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                                <a href="{$ADD_API_ROLE_URL}&amp;id={$ROLE.id}" class="btn btn-secondary"><i class="fa fa-pencil"></i> {$LANG->getGlobal('edit')}</a>
                                <button type="button" class="btn btn-secondary" data-toggle="apiroledel"><i class="fa fa-trash-o"></i> {$LANG->getGlobal('delete')}</button>
                            </td>
                        </tr>
                        <!-- END: loop -->
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer border-top">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                {$GENERATE_PAGE}
            </div>
        </div>
        {/if}
    </div>
    <!-- END: role_list -->
    {/if}
</div>
<!-- END: main -->

{/if}
