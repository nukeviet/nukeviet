<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div id="my-role-api" data-page-url="{$PAGE_URL}">
    <div class="tools">
        <div class="mb-2">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {$TYPE_PUBLIC.active}" href="{$TYPE_PUBLIC.url}">
                        {$TYPE_PUBLIC.name}
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link {$TYPE_PRIVATE.active}" href="{$TYPE_PRIVATE.url}">
                        {$TYPE_PRIVATE.name}
                    </a>
                </li>
            </ul>
        </div>
        <div>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#credential_auth">
                <i class="fa fa-shield fa-lg text-danger"></i> {$LANG->getModule('authentication')}
            </button>
            <div id="credential_auth" tabindex="-1" aria-labelledby="credentialAuthLabel" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="credentialAuthLabel">
                                <strong>{$LANG->getModule('authentication')}</strong>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <label class="form-label"><strong>{$LANG->getModule('api_credential_ident')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$key}_ident" id="{$key}-credential_ident" value="{$method.ident|default:''}" class="form-control bg-white" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="clipboard" data-bs-target="#{$key}-credential_ident" data-bs-title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"><strong>{$LANG->getModule('api_credential_secret')}</strong></label>
                                        <div class="input-group">
                                            <input type="text" name="{$key}_secret" id="{$key}-credential_secret" value="{$method.secret|default:''}" class="form-control bg-white" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="clipboard" data-bs-target="#{$key}-credential_secret" data-bs-title="{$LANG->getModule('value_copied')}" data-bs-placement="left" data-bs-container="body" data-bs-trigger="manual">
                                                <i class="fa fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary w-100 create_authentication" data-method="{$key}">
                                                {$LANG->getModule('create_access_authentication')}
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-danger w-100 delete_authentication" data-method="{$key}">
                                                {$LANG->getModule('delete_authentication')}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row mb-3 api_ips"{if $method.not_access_authentication} style="display:none"{/if}>
                                        <div class="col-12 mb-3">
                                            <label class="form-label"><strong>{$LANG->getModule('api_ips')}</strong></label>
                                            <textarea class="form-control ips" name="{$key}_ips">{$method.ips|default:''}</textarea>
                                            <div class="form-text">{$LANG->getModule('api_ips_help')}</div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary api_ips_update" data-method="{$key}">
                                                {$LANG->getModule('api_ips_update')}
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
    {if empty($ROLELIST)}
    <div class="alert alert-info d-flex align-items-center justify-content-center mb-3" role="alert">
        <i class="fa fa-info-circle me-2"></i>
        <div>
            {$LANG->getModule('api_roles_empty')}
        </div>
    </div>
    {/if}
    {if not empty($ROLELIST)}
    <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-primary small">
                    <tr>
                        <th class="text-nowrap text-center">{$LANG->getModule('api_roles_list')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_status')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_credential_status')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_credential_addtime')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('endtime')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('quota')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_credential_access_count')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;">{$LANG->getModule('api_role_credential_last_access')}</th>
                        <th class="text-nowrap text-center" style="width: 1%;"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ROLELIST item=role}
                    <tr class="item{if $role.credential_status !== 1} text-muted{/if}" data-role-id="{$role.role_id}">
                        <td>
                            <strong>{$role.role_title}</strong>
                            {if not empty($role.role_description)}
                            <p class="description">{$role.role_description}</p>
                            {/if}
                        </td>
                        <td class="text-nowrap text-center" style="width:1%;">{$role.status}</td>
                        <td class="text-nowrap text-center" style="width:1%;">{$role.credential_status_format}</td>
                        <td class="text-center" style="width:1%;">{$role.credential_addtime}</td>
                        <td class="text-center" style="width:1%;">{$role.credential_endtime}</td>
                        <td class="text-center" style="width:1%;">{$role.credential_quota}</td>
                        <td class="text-nowrap text-center" style="width:1%;">{$role.credential_access_count}</td>
                        <td class="text-nowrap text-center" style="width:1%;">{$role.credential_last_access}</td>
                        <td class="text-nowrap text-center" style="width:1%;">
                            <div class="action-column">
                                <button
                                    type="button"
                                    class="btn btn-secondary open-api-modal"
                                    data-role-id="{$role.role_id}"
                                    data-role-title="{$role.role_title}"
                                    data-page-url="{$PAGE_URL}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#apiRoleModal">
                                    {$LANG->getModule('api_roles_detail')}
                                </button>

                                {if $TYPE == 'public' and $role.credential_status == 1}
                                <button type="button" class="btn btn-secondary credential-activate">
                                    {$LANG->getModule('activate')}
                                </button>
                                {elseif $TYPE == 'public' and $role.credential_status == -1}
                                <button type="button" class="btn btn-secondary credential-deactivate">
                                    {$LANG->getModule('deactivate')}
                                </button>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
    </div>
    {/if}
    {if not empty($GENERATE_PAGE)}
        <div class="text-center">
            {$GENERATE_PAGE}
        </div>
    {/if}
</div>

<div class="modal fade" id="apiRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center py-4" id="apiRoleLoading">
                    <div class="spinner-border"></div>
                </div>
                <div id="apiRoleContent" class="d-none"></div>
            </div>
        </div>
    </div>
</div>
