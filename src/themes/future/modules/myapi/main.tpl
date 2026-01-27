<script type="text/javascript" src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div id="my-role-api" data-page-url="{$PAGE_URL}">
    <div class="tools">
            <div class="mb-3">
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
            <button type="button" class="btn btn-outline-secondary mb-3" data-bs-toggle="modal" data-bs-target="#credential_auth">
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
                                {foreach from=$METHODS key=key item=value name=foo}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {if $smarty.foreach.foo.first}active{/if}"
                                            id="{$key}-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#{$key}-panel"
                                            type="button" role="tab">
                                        {$value}
                                    </button>
                                </li>
                                {/foreach}
                            </ul>

                            <div class="tab-content" id="authTabContent">
                                {foreach from=$METHODS key=key item=value name=foo}
                                    <div class="tab-pane fade {if $smarty.foreach.foo.first}show active{/if}"
                                        id="{$key}-panel"
                                        role="tabpanel"
                                        aria-labelledby="{$key}-tab">
                                        <p class="p-3">Nội dung của phương thức: {$value}</p>
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
                    {* Nội dung bảng *}
                </tbody>
            </table>
        </div>
    </div>
    {/if}
    {if not empty($GENERATE_PAGE)}
        <div class="text-center">
            {$GENERATE_PAGE}
        </div>
    {/if}
</div>
