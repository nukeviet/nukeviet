<div id="my-role-api" data-page-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}">
    <div class="text-end mb-3">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#credential_auth"><i class="fa-solid fa-shield-halved fa-lg text-danger"></i> {$LANG->getModule('authentication')}</button>
    </div>
    <div class="card">
        <div class="card-header pt-3 border-bottom-0">
            <div class="card-header-tabs d-none d-sm-block">
                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link {if $TYPE == 'public'}active{/if}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}">{$LANG->getModule('api_role_type_public2')}</a></li>
                    <li class="nav-item"><a class="nav-link {if $TYPE == 'private'}active{/if}" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;type=private">{$LANG->getModule('api_role_type_private2')}</a></li>
                </ul>
            </div>
            <div class="d-block d-sm-none">
                <div class="dropdown">
                    <button class="w-100 btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$LANG->getModule($TYPE eq 'public' ? 'api_role_type_public2' : 'api_role_type_private2')}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}">{$LANG->getModule('api_role_type_public2')}</a></li>
                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;type=private">{$LANG->getModule('api_role_type_private2')}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            {if empty($GCONFIG.remote_api_access)}
            <div class="alert alert-danger">
                {$LANG->getModule('api_remote_off2')}
            </div>
            {/if}

            {if empty($ROLE_COUNT)}
            <div class="alert alert-info text-center">
                {$LANG->getModule('api_roles_empty')}
            </div>
            {else}
            <div class="table-responsive table-card mt-1">
                <table class="table table-striped align-middle table-sticky mb-1">
                    <thead class="text-muted tableFloatingHeaderOriginal">
                        <tr>
                            <th class="text-nowrap text-center" style="vertical-align:middle">{$LANG->getModule('api_roles_list')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_object')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_status')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_addtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('endtime')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('quota')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_access_count')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle">{$LANG->getModule('api_role_credential_last_access')}</th>
                            <th class="text-nowrap text-center" style="width: 1%;vertical-align:middle"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $ROLE_LIST as $ROLE}
                        <tr class="item{if $ROLE.credential_status|intval !== 1} text-muted{/if}" data-role-id="{$ROLE.role_id}">
                            <td>
                                <strong>{$ROLE.role_title}</strong>
                                {if !empty($ROLE.role_description)}
                                <p class="description">{$ROLE.role_description}</p>
                                {/if}
                            </td>
                            <td class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_object_'|cat:$ROLE.role_object)}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{if !empty($ROLE.status)}{$LANG->getModule('active')}{else}{$LANG->getModule('inactive')}{/if}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{if $ROLE.credential_status|intval === 1}{$LANG->getModule('activated')}{elseif $ROLE.credential_status|intval === 0}{$LANG->getModule('suspended')}{else}{$LANG->getModule('not_activated')}{/if}</td>
                            <td class="text-center" style="width: 1%;">{if $ROLE.credential_addtime > 0}{$ROLE.credential_addtime|ddatetime}{/if}</td>
                            <td class="text-center" style="width: 1%;">{if $ROLE.credential_endtime > 0}{$ROLE.credential_endtime|ddatetime}{/if}</td>
                            <td class="text-center" style="width: 1%;">{if $ROLE.credential_quota > 0}$ROLE.credential_quota|nnum_format{elseif $ROLE.credential_quota == 0}{$LANG->getModule('no_quota')}{/if}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{if $ROLE.credential_access_count >= 0}{$ROLE.credential_access_count}{/if}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">{if $ROLE.credential_last_access > 0}{$ROLE.credential_last_access|ddatetime}{/if}</td>
                            <td class="text-nowrap text-center" style="width: 1%;">
                                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#apiroledetail{$ROLE.role_id}">{$LANG->getModule('api_roles_allowed')}</button>
                                <!-- START FORFOOTER -->
                                <div id="apiroledetail{$ROLE.role_id}" tabindex="-1" role="dialog" class="modal fade" aria-labelledby="apiroletitle{$ROLE.role_id}">
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

                                                <div>
                                                    <ul class="nav nav-tabs mb-3" role="tablist">
                                                        {assign var='FORLANGS' value=[]}
                                                        {foreach $GCONFIG.setup_langs as $KEY_LANG => $_LG}
                                                            {if $_LG == $smarty.const.NV_LANG_DATA}
                                                                {append var='FORLANGS' value=['active' => 'active', 'in' => ' in active show', 'expanded' => 'true', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                            {else}
                                                                {append var='FORLANGS' value=['active' => '', 'in' => '', 'expanded' => 'false', 'langkey' => $_LG, 'langname' => $LANGUAGE_ARRAY[$_LG].name] index=$_LG}
                                                            {/if}
                                                        {/foreach}
                                                        {foreach $FORLANGS as $FORLANG}
                                                        <li role="presentation" class="nav-item"><a id="forlang-{$FORLANG.langkey}-{$ROLE.role_id}-tab" class="nav-link {$FORLANG.active}" href="#forlang-{$FORLANG.langkey}-{$ROLE.role_id}" aria-controls="forlang-{$FORLANG.langkey}-{$ROLE.role_id}" role="tab" data-bs-toggle="tab" aria-expanded="{$FORLANG.expanded}">{$FORLANG.langname}</a></li>
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
                                                                            <i class="fa fa-angle-right"></i> {$CAT_DATA.title}
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
                                {if $TYPE == 'public'}
                                {if $ROLE.credential_status == -1}
                                <button type="button" class="btn btn-secondary credential-activate">{$LANG->getModule('activate')}</button>
                                {elseif $ROLE.credential_status == 1}
                                <button type="button" class="btn btn-secondary credential-deactivate">{$LANG->getModule('deactivate')}</button>
                                {/if}
                                {/if}
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

<!-- START FORFOOTER -->
<div id="credential_auth" tabindex="-1" role="dialog" class="modal fade" id="credential_auth" aria-labelledby="create_auth_title">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="create_auth_title"><strong>{$LANG->getModule('authentication')}</strong></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="form-label">{$LANG->getModule('auth_method')}</div>
                {assign var='activeMethod' value=''}
                <ul class="d-none d-sm-flex nav nav-tabs mb-3" id="credential_auth_tabs">
                    {foreach $METHODS as $KEY => $METHOD}
                    {if empty($activeMethod)}
                    {assign var='activeMethod' value=$METHOD.name}
                    {/if}
                    <li class="nav-item" role="presentation"><a href="#{$METHOD.key}-panel" class="nav-link {$METHOD.active}" data-bs-toggle="tab" data-bs-target="#{$METHOD.key}-panel" type="button" aria-controls="{$METHOD.key}-panel"  role="tab">{$METHOD.name}</a></li>
                    {/foreach}
                </ul>
                <div class="d-block d-sm-none mb-3">
                    <div class="dropdown">
                        <button class="w-100 btn btn-secondary dropdown-toggle" type="button" data-toggle="credential_auth_dropdown_btn" data-bs-toggle="dropdown" aria-expanded="false">
                            {$activeMethod}
                        </button>
                        <ul class="dropdown-menu">
                            {foreach $METHODS as $KEY => $METHOD}
                            <li><a class="dropdown-item" data-toggle="credential_auth_dropdown_item" href="#{$METHOD.key}-panel">{$METHOD.name}</a></li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    {foreach $METHODS as $KEY => $METHOD}
                    <div role="tabpanel" class="tab-pane {$METHOD.active}" id="{$METHOD.key}-panel">
                        <div class="mb-3">
                            <label class="form-label" for="{$METHOD.key}-credential_ident">{$LANG->getModule('api_credential_ident')}</label>
                            <div class="input-group">
                                <input type="text" name="{$METHOD.key}_ident" id="{$METHOD.key}-credential_ident" value="{$METHOD.ident ?? ''}" class="form-control" readonly="readonly">
                                <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_ident" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa-solid fa-copy"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="{$METHOD.key}-credential_secret">{$LANG->getModule('api_credential_secret')}</label>
                            <div class="input-group">
                                <input type="text" name="{$METHOD.key}_secret" id="{$METHOD.key}-credential_secret" value="" class="form-control" readonly="readonly">
                                <button class="btn btn-secondary active" type="button" data-clipboard-target="#{$METHOD.key}-credential_secret" data-bs-toggle="tooltip" title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" data-bs-animation="false"><i class="fa-solid fa-copy"></i></button>
                            </div>
                        </div>
                        {if !empty($smarty.const.NV_IS_SPADMIN or $KEY == 'password_verify' or $KEY == 'md5_verify')}
                        <div class="row mb-3">
                            <div class="col-6">
                                <button type="button" class="btn btn-primary w-100 create_authentication" data-method="{$METHOD.key}">{$LANG->getModule('create_access_authentication')}</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger w-100 delete_authentication" data-method="{$METHOD.key}">{$LANG->getModule('delete_authentication')}</button>
                            </div>
                        </div>
                        {/if}

                        <div class="api_ips"{if $METHOD.not_access_authentication}style="display:none"{/if}>
                            <div class="form-group mb-3">
                                <label class="form-label" for="{$METHOD.key}_ips">{$LANG->getModule('api_ips')}</label>
                                <textarea class="form-control ips" name="{$METHOD.key}_ips" id="{$METHOD.key}_ips">{$METHOD.ips ?? ''}</textarea>
                                <div class="form-text">{$LANG->getModule('api_ips_help')}</div>
                            </div>
                            {if !empty($smarty.const.NV_IS_SPADMIN or $KEY == 'password_verify' or $KEY == 'md5_verify')}
                            <div class="text-center">
                                <button type="button" class="btn btn-primary api_ips_update" data-method="{$METHOD.key}">{$LANG->getModule('api_ips_update')}</button>
                            </div>
                            {/if}
                        </div>
                    </div>
                    {/foreach}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
