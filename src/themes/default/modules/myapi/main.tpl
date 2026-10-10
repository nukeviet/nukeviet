<script src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>

{function name=renderApiRoleModal role=[]}
<div class="modal fade" id="apiRoleModal_{$role.role_id}" tabindex="-1" aria-labelledby="apiRoleModalLabel_{$role.role_id}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-md-down modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="apiRoleModalLabel_{$role.role_id}">{$role.role_title}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                {if empty($role.apis)}
                <div class="alert alert-warning">{$LANG->getModule('api_roles_empty')}</div>
                {else}
                <ul class="nav nav-tabs mb-3" role="tablist">
                    {assign var="active_tab_set" value=false}
                    {if !empty($role.apis[''])}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-sys-{$role.role_id}" data-bs-toggle="tab" data-bs-target="#content-sys-{$role.role_id}" type="button" role="tab" aria-selected="true">
                            <i class="fa-solid fa-server" aria-hidden="true"></i> {$LANG->getModule('api_system')}
                        </button>
                    </li>
                    {assign var="active_tab_set" value=true}
                    {/if}
                    {foreach from=$role.apis key=lang item=modules}
                    {if $lang neq ''}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {if !$active_tab_set}active{/if}" id="tab-{$lang}-{$role.role_id}" data-bs-toggle="tab" data-bs-target="#content-{$lang}-{$role.role_id}" type="button" role="tab" aria-selected="{if !$active_tab_set}true{else}false{/if}">
                            <i class="fa-solid fa-language" aria-hidden="true"></i> {$LANGUAGE_ARRAY[$lang]['name']}
                        </button>
                    </li>
                    {assign var="active_tab_set" value=true}
                    {/if}
                    {/foreach}
                </ul>
                <div class="tab-content">
                    {assign var="active_pane_set" value=false}
                    {if !empty($role.apis[''])}
                    <div class="tab-pane fade show active" id="content-sys-{$role.role_id}" role="tabpanel" aria-labelledby="tab-sys-{$role.role_id}">
                        <div class="list-group list-group-flush mb-4">
                            {foreach from=$role.apis[''] item=catData}
                            <div class="list-group-item">
                                <div class="mb-2 fw-bold text-success">
                                    <i class="fa-regular fa-folder-open me-1" aria-hidden="true"></i> {$catData.title}
                                </div>
                                <div class="ms-3">
                                    {foreach from=$catData.apis item=apiName}
                                    <div class="d-flex align-items-start mb-1 text-break"><i class="fa-solid fa-caret-right text-body-secondary mt-1 me-2" aria-hidden="true"></i><span>{$apiName}</span></div>
                                    {/foreach}
                                </div>
                            </div>
                            {/foreach}
                        </div>
                    </div>
                    {assign var="active_pane_set" value=true}
                    {/if}

                    {foreach from=$role.apis key=lang item=modules}
                    {if $lang neq ''}
                    <div class="tab-pane fade {if !$active_pane_set}show active{/if}" id="content-{$lang}-{$role.role_id}" role="tabpanel" aria-labelledby="tab-{$lang}-{$role.role_id}">
                        <div class="list-group list-group-flush mb-4">
                            {foreach from=$modules key=mod_title item=cats}
                            <div class="list-group-item">
                                <strong class="text-uppercase"><i class="fa-solid fa-cube me-1" aria-hidden="true"></i> {$SITE_MODS[$mod_title].custom_title}</strong>
                            </div>
                            {foreach from=$cats item=catData}
                            <div class="list-group-item">
                                <div class="mb-2 fw-bold text-primary"><i class="fa-regular fa-folder-open me-1" aria-hidden="true"></i> {$catData.title}</div>
                                <div class="ms-3">
                                    {foreach from=$catData.apis item=apiName}
                                        <div class="d-flex align-items-start mb-1 text-break"><i class="fa-solid fa-caret-right text-body-secondary mt-1 me-2" aria-hidden="true"></i><span>{$apiName}</span></div>
                                    {/foreach}
                                </div>
                            </div>
                            {/foreach}
                            {/foreach}
                        </div>
                    </div>
                    {assign var="active_pane_set" value=true}
                    {/if}
                    {/foreach}
                </div>
                {/if}
            </div>
        </div>
    </div>
</div>
{/function}

<div id="my-role-api" data-page-url="{$PAGE_URL}" data-checkss="{$CHECKSS}">
    <div class="d-flex align-items-end">
        <div class="mb-2">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {$TYPE_PUBLIC.active}" href="{$TYPE_PUBLIC.url}" title="{$TYPE_PUBLIC.name}">
                        {$TYPE_PUBLIC.name}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {$TYPE_PRIVATE.active}" href="{$TYPE_PRIVATE.url}" title="{$TYPE_PRIVATE.name}">
                        {$TYPE_PRIVATE.name}
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto mb-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#credential_auth">
                <i class="fa-solid fa-shield-halved text-danger" aria-hidden="true"></i> {$LANG->getModule('authentication')}
            </button>
            <div id="credential_auth" tabindex="-1" aria-labelledby="credentialAuthLabel" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" id="credentialAuthLabel">
                                {$LANG->getModule('authentication')}
                            </h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3"><strong>{$LANG->getModule('auth_method')}</strong></div>

                            <ul class="nav nav-tabs mb-3" role="tablist">
                                {foreach from=$METHODS key=key item=method}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {if $method@first}active{/if}"
                                            id="{$key}-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#{$key}-panel"
                                            type="button" role="tab">
                                        {$method.name}
                                    </button>
                                </li>
                                {/foreach}
                            </ul>

                            <div class="tab-content" id="authTabContent">
                                {foreach from=$METHODS key=key item=method}
                                <div class="tab-pane fade {if $method@first}show active{/if}" id="{$key}-panel" role="tabpanel" aria-labelledby="{$key}-tab">
                                    <div class="mb-3">
                                        <label class="form-label" for="{$key}-credential_ident"><strong>{$LANG->getModule('api_credential_ident')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$key}_ident" id="{$key}-credential_ident" value="{$method.ident|default:''}" class="form-control bg-body-secondary" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="clipboard" data-bs-target="#{$key}-credential_ident" data-bs-title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" aria-label="{$LANG->getGlobal('copy')}">
                                                <i class="fa-solid fa-copy" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="{$key}-credential_secret"><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$key}_secret" id="{$key}-credential_secret" value="{$method.secret|default:''}" class="form-control bg-body-secondary" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="clipboard" data-bs-target="#{$key}-credential_secret" data-bs-title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual" aria-label="{$LANG->getGlobal('copy')}">
                                                <i class="fa-solid fa-copy" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary w-100 create_authentication" data-method="{$key}">
                                                <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> {$LANG->getModule('create_access_authentication')}
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-danger w-100 delete_authentication" data-method="{$key}">
                                                <i class="fa-solid fa-trash me-1" aria-hidden="true"></i> {$LANG->getModule('delete_authentication')}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 api_ips{if $method.not_access_authentication} d-none{/if}">
                                        <div class="col-12 mb-3">
                                            <label class="form-label" for="{$key}-api_ips"><strong>{$LANG->getModule('api_ips')}</strong></label>
                                            <textarea class="form-control ips" name="{$key}_ips" id="{$key}-api_ips">{$method.ips|default:''}</textarea>
                                            <div class="form-text">{$LANG->getModule('api_ips_help')}</div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-primary api_ips_update" data-method="{$key}">
                                                <i class="fa-solid fa-floppy-disk me-1" aria-hidden="true"></i> {$LANG->getModule('api_ips_update')}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {if empty($ROLECOUNT)}
    <div class="alert alert-info d-flex align-items-center justify-content-center mb-3" role="alert">
        <i class="fa-solid fa-circle-info me-2" aria-hidden="true"></i>
        <div>
            {$LANG->getModule('api_roles_empty')}
        </div>
    </div>
    {/if}
    {if not empty($ROLELIST)}
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-primary small">
                <tr>
                    <th class="text-nowrap text-center">{$LANG->getModule('api_roles_list')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('api_role_status')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('api_role_credential_status')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('api_role_credential_addtime')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('endtime')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('quota')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('api_role_credential_access_count')}</th>
                    <th class="text-nowrap text-center w-auto">{$LANG->getModule('api_role_credential_last_access')}</th>
                    <th class="text-nowrap text-center w-auto"></th>
                </tr>
            </thead>
            <tbody>
                {foreach from=$ROLELIST item=role}
                <tr class="item{if $role.credential_status !== 1} text-body-secondary{/if}" data-role-id="{$role.role_id}">
                    <td>
                        <div class="fw-bold">{$role.role_title}</div>
                        {if not empty($role.role_description)}
                        <div class="small text-body-secondary">{$role.role_description}</div>
                        {/if}
                    </td>
                    <td class="text-nowrap text-center">{$role.status}</td>
                    <td class="text-nowrap text-center">{$role.credential_status_format}</td>
                    <td class="text-center text-nowrap">{$role.credential_addtime}</td>
                    <td class="text-center text-nowrap">{$role.credential_endtime}</td>
                    <td class="text-center text-nowrap">{$role.credential_quota}</td>
                    <td class="text-nowrap text-center">{$role.credential_access_count}</td>
                    <td class="text-nowrap text-center">{$role.credential_last_access}</td>
                    <td class="text-nowrap text-center">
                        <div class="d-flex gap-1 justify-content-center flex-lg-column align-items-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#apiRoleModal_{$role.role_id}">
                                <i class="fa-solid fa-list me-1" aria-hidden="true"></i> {$LANG->getModule('apis_list')}
                            </button>
                            {if $TYPE == 'public' and $role.credential_status == 1}
                            <button type="button" class="btn btn-sm btn-outline-secondary credential-deactivate">
                                <i class="fa-solid fa-power-off me-1" aria-hidden="true"></i> {$LANG->getModule('deactivate')}
                            </button>
                            {elseif $TYPE == 'public' and $role.credential_status == -1}
                            <button type="button" class="btn btn-sm btn-outline-secondary credential-activate">
                                <i class="fa-solid fa-power-off me-1" aria-hidden="true"></i> {$LANG->getModule('activate')}
                            </button>
                            {/if}
                        </div>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <div class="d-lg-none">
        {foreach from=$ROLELIST item=role}
        <div class="card mb-3 item{if $role.credential_status !== 1} text-body-secondary{/if}" data-role-id="{$role.role_id}">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <h3 class="card-title h6 mb-0"><strong>{$role.role_title}</strong></h3>
                        {if not empty($role.role_description)}
                        <small class="text-body-secondary">{$role.role_description}</small>
                        {/if}
                    </div>
                    <div>{$role.status}</div>
                </div>
                <hr class="my-2">
                <div class="row g-2 small">
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('api_role_credential_status')}:</span>
                        <div class="fw-bold">{$role.credential_status_format}</div>
                    </div>
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('api_role_credential_addtime')}:</span>
                        <div class="fw-bold">{$role.credential_addtime}</div>
                    </div>
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('endtime')}:</span>
                        <div class="fw-bold">{$role.credential_endtime}</div>
                    </div>
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('quota')}:</span>
                        <div class="fw-bold">{$role.credential_quota}</div>
                    </div>
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('api_role_credential_access_count')}:</span>
                        <div class="fw-bold">{$role.credential_access_count}</div>
                    </div>
                    <div class="col-6">
                        <span class="text-body-secondary">{$LANG->getModule('api_role_credential_last_access')}:</span>
                        <div class="fw-bold">{$role.credential_last_access}</div>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#apiRoleModal_{$role.role_id}">
                        <i class="fa-solid fa-list me-1" aria-hidden="true"></i> {$LANG->getModule('apis_list')}
                    </button>
                    {if $TYPE == 'public' and $role.credential_status == 1}
                    <button type="button" class="btn btn-sm btn-secondary credential-deactivate">
                        <i class="fa-solid fa-power-off me-1" aria-hidden="true"></i> {$LANG->getModule('deactivate')}
                    </button>
                    {elseif $TYPE == 'public' and $role.credential_status == -1}
                    <button type="button" class="btn btn-sm btn-secondary credential-activate">
                        <i class="fa-solid fa-power-off me-1" aria-hidden="true"></i> {$LANG->getModule('activate')}
                    </button>
                    {/if}
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    {/if}

    {foreach from=$ROLELIST item=role}
    {call name=renderApiRoleModal role=$role}
    {/foreach}

    {if not empty($GENERATE_PAGE)}
    <div class="text-center">
        {$GENERATE_PAGE}
    </div>
    {/if}
</div>
