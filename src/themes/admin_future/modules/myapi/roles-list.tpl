{if empty($GCONFIG.remote_api_access)}
<div class="alert alert-danger">
    {$REMOTE_API_OFF}
</div>
{/if}
<div id="rolelist" data-page-url="{$PAGE_URL}" data-checkss="{$CHECKSS}">
    <div class="card">
        <div class="card-header">
            <div class="row g-2">
                <div class="col-sm-7 col-lg-8 col-xl-6 order-2 order-sm-1">
                    <div class="row g-2">
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-text">{$LANG->getModule('api_role_type')}</span>
                                <select class="form-select role-type" name="type">
                                    <option value="">{$LANG->getModule('all')}</option>
                                    {foreach $TYPES as $TYPE}
                                    <option value="{$TYPE}" {if $TYPE == $TYPE_API}selected="selected"{/if}>{$LANG->getModule("api_role_type_"|cat:$TYPE)}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <span class="input-group-text">{$LANG->getModule('api_role_object')}</span>
                                <select class="form-select role-object" name="object">
                                    <option value="">{$LANG->getModule('all')}</option>
                                    {foreach $OBJECTS as $OBJECT}
                                    <option value="{$OBJECT}" {if $OBJECT == $OBJECT_API}selected="selected"{/if}>{$LANG->getModule("api_role_object_"|cat:$OBJECT)}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-lg-4 col-xl-6 text-end order-1 order-sm-2">
                    <button onclick="window.location.href='{$ADD_API_ROLE_URL}';" class="btn btn-primary"><i class="fa-solid fa-plus"></i> {$LANG->getModule('add_role')}</button>
                </div>
            </div>
        </div>
        {if empty($ROLE_LIST)}
        <div class="card-body">
            <div class="alert alert-info text-center m-0">
                {$LANG->getModule('api_roles_empty')}
            </div>
        </div>
        {else}
        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-striped align-middle table-sticky mb-1">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-nowrap" style="width: 70%;">{$LANG->getModule('api_roles_title')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;">{$LANG->getModule('api_role_type')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;">{$LANG->getModule('api_role_object')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;">{$LANG->getModule('api_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;">{$LANG->getModule('api_edittime')}</th>
                            <th class="text-nowrap text-center" style="width: 5%;">{$LANG->getModule('status')}</th>
                            <th class="text-nowrap text-center" style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $ROLE_LIST as $ROLE}
                        <tr class="item" data-id="{$ROLE.role_id}">
                            <td>{$ROLE.role_title}</td>
                            <td class="text-nowrap text-center">{$LANG->getModule('api_role_type_'|cat:$ROLE.role_type)}</td>
                            <td class="text-nowrap text-center">{$LANG->getModule('api_role_object_'|cat:$ROLE.role_object)}</td>
                            <td class="text-nowrap text-center">{$ROLE.addtime|ddatetime}</td>
                            <td class="text-nowrap text-center">{if $ROLE.edittime}{$ROLE.edittime|ddatetime}{/if}</td>
                            <td class="text-nowrap text-center">
                                <select class="form-select change-status" style="width: 120px;" name="role_status">
                                    {foreach [$LANG->getModule('inactive'), $LANG->getModule('active')] as $K_STATUS => $STATUS}
                                    <option value="{$K_STATUS}" {if $K_STATUS == $ROLE.status}selected="selected"{/if}>{$STATUS}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td class="text-nowrap text-center">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#apiroledetail{$ROLE.role_id}" aria-label="{$LANG->getModule('api_roles_allowed')}" title="{$LANG->getModule('api_roles_allowed')}"><i class="fa-solid fa-list"></i><span class="d-none d-xl-inline"> {$LANG->getModule('api_roles_allowed')}</span></button>
                                <!-- START FORFOOTER -->
                                <div id="apiroledetail{$ROLE.role_id}" tabindex="-1" role="dialog" class="modal fade" aria-labelledby="#apiroletitle{$ROLE.role_id}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title" id="apiroletitle{$ROLE.role_id}"><strong>{$LANG->getModule('api_roles_detail')}: {$ROLE.role_title}</strong></div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                                            </div>
                                            <div class="modal-body">
                                                {if !empty($ROLE.apis[''])}
                                                {foreach $ROLE.apis[''] as $CAT_DATA}
                                                <div class="card mb-3 border">
                                                    <div class="card-header api-header"><strong><i class="fa-solid fa-folder-open"></i> {$LANG->getModule('api_of_system')}: {$CAT_DATA.title}</strong></div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            {foreach $CAT_DATA.apis as $API_DATA}
                                                            <div class="col-sm-6">
                                                                <div class="text-truncate mb-3"><i class="fa-solid fa-caret-right"></i> {$API_DATA}</div>
                                                            </div>
                                                            {/foreach}
                                                        </div>
                                                    </div>
                                                </div>
                                                {/foreach}
                                                {/if}
                                                {assign var='FORLANGS' value=[]}
                                                {foreach $GCONFIG.setup_langs as $KEY_LANG => $_LG}
                                                    {if $_LG == $smarty.const.NV_LANG_DATA}
                                                        {append var='FORLANGS' value=['active' => 'active', 'in' => ' in active show', 'expanded' => 'true', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {else}
                                                        {append var='FORLANGS' value=['active' => '', 'in' => '', 'expanded' => 'false', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                    {/if}
                                                {/foreach}
                                                <div>
                                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                                        {foreach $FORLANGS as $FORLANG}
                                                        <li role="presentation" class="nav-item"><a id="forlang-{$FORLANG.langkey}-{$ROLE.role_id}-tab" href="#forlang-{$FORLANG.langkey}-{$ROLE.role_id}" class="nav-link {$FORLANG.active}" aria-controls="forlang-{$FORLANG.langkey}-{$ROLE.role_id}" role="tab" data-bs-toggle="tab" aria-expanded="{$FORLANG.expanded}">{$FORLANG.langname}</a></li>
                                                        {/foreach}
                                                    </ul>
                                                    <div class="tab-content">
                                                        {foreach $FORLANGS as $_LG => $FORLANG}
                                                        <div role="tabpanel" class="tab-pane fade{$FORLANG.in}" id="forlang-{$FORLANG.langkey}-{$ROLE.role_id}" aria-labelledby="forlang-{$FORLANG.langkey}-{$ROLE.role_id}-tab">
                                                            {if !empty($ROLE.apis.$_LG)}
                                                            {foreach $ROLE.apis.$_LG as $MOD_TITLE => $MOD_DATA}
                                                            {foreach $MOD_DATA as $CAT_DATA}
                                                            <div class="card mb-3 border">
                                                                <div class="card-header api-header"><strong><i class="fa-solid fa-folder-open"></i> {$SITE_MOD.$MOD_TITLE.custom_title}
                                                                        {if !empty($CAT_DATA.title)}
                                                                        <i class="fa-solid fa-angle-right"></i> {$CAT_DATA.title}
                                                                        {/if}
                                                                    </strong></div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        {foreach $CAT_DATA.apis as $API_DATA}
                                                                        <div class="col-sm-6">
                                                                            <div class="text-truncate mb-3" title="{$API_DATA}"><i class="fa-solid fa-caret-right"></i> {$API_DATA}</div>
                                                                        </div>
                                                                        {/foreach}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {/foreach}
                                                            {/foreach}
                                                            {/if}
                                                        </div>
                                                        {/foreach}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORFOOTER -->
                                <button onclick="window.location.href='{$ADD_API_ROLE_URL}&amp;id={$ROLE.role_id}';" class="btn btn-secondary" aria-label="{$LANG->getGlobal('edit')}" title="{$LANG->getGlobal('edit')}"><i class="fa-solid fa-pencil"></i><span class="d-none d-xl-inline"> {$LANG->getGlobal('edit')}</span></button>
                                <button type="button" class="btn btn-secondary" data-toggle="apiroledel" aria-label="{$LANG->getGlobal('delete')}" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-trash text-danger"></i><span class="d-none d-xl-inline"> {$LANG->getGlobal('delete')}</span></button>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        {if !empty($GENERATE_PAGE)}
        <div class="card-footer border-top">
            <div class="d-flex flex-wrap justify-content-end align-items-center">
                <div class="pagination-wrap">{$GENERATE_PAGE}</div>
            </div>
        </div>
        {/if}
    </div>
    {/if}
</div>
