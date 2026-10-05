{assign var="formAction" value="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"}
{assign var="tabs" value=[
    'general_settings' => ['icon' => 'fa-gear', 'title' => $LANG->getModule('general_settings')],
    'access_token_create' => ['icon' => 'fa-key', 'title' => $LANG->getModule('access_token_create')],
    'webhook_setup' => ['icon' => 'fa-tower-broadcast', 'title' => $LANG->getModule('webhook_setup')],
    'system_check' => ['icon' => 'fa-stethoscope', 'title' => $LANG->getModule('system_check')],
    'vnsubdivisions' => ['icon' => 'fa-map-location-dot', 'title' => $LANG->getModule('vnsubdivisions_settings')],
    'callingcodes' => ['icon' => 'fa-phone', 'title' => $LANG->getModule('callingcodes_settings')]
] nocache}
{* Thanh tiến độ các bước thiết lập *}
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-xl-4">
            {foreach from=$SETUP_STATUS key=step item=done name=steps}
            <div class="col">
                <a href="#" class="d-flex align-items-center gap-3 h-100 text-decoration-none" data-toggle="zaloSettingGoto" data-tab="{$step}">
                    {if $done}
                    <i class="fa-solid fa-circle-check fa-2x text-success flex-shrink-0"></i>
                    {elseif $step eq 'system_check'}
                    <i class="fa-solid fa-triangle-exclamation fa-2x text-warning flex-shrink-0"></i>
                    {else}
                    <i class="fa-regular fa-circle fa-2x text-body-tertiary flex-shrink-0"></i>
                    {/if}
                    <span>
                        <span class="d-block fw-medium text-body">{$smarty.foreach.steps.iteration}. {$tabs[$step].title}</span>
                        {if $step eq 'system_check'}
                        <span class="d-block small {if $done}text-success{else}text-warning-emphasis{/if}">{$LANG->getModule($done ? 'suitable' : 'notsuitable')}</span>
                        {else}
                        <span class="d-block small {if $done}text-success{else}text-body-secondary{/if}">{$LANG->getModule($done ? 'configured' : 'not_configured')}</span>
                        {/if}
                    </span>
                </a>
            </div>
            {/foreach}
        </div>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4 col-xl-3 order-md-2">
        <div class="dropdown d-grid d-md-none" id="zalo-setting-select">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="fw-medium" data-toggle="dropdown-value">{$tabs[$ACTION].title}</span>
            </button>
            <ul class="dropdown-menu border-0 w-100">
                {foreach from=$tabs key=key item=tab}
                <li><a class="dropdown-item{if $key eq $ACTION} active{/if}" href="#" data-tab="{$key}"><i class="fa-solid {$tab.icon} fa-fw"></i> {$tab.title}</a></li>
                {/foreach}
            </ul>
        </div>
        <ul class="sticky-top d-none d-md-flex nav nav-pills nav-stacked" role="tablist" id="zalo-setting-tabs">
            {foreach from=$tabs key=key item=tab}
            <li class="nav-item w-100 mw-100" role="presentation">
                <a class="nav-link d-flex align-items-center gap-2{if $key eq $ACTION} active{/if}" href="#zalo-{$key}" id="zalo-{$key}-tab" role="tab" data-bs-toggle="pill" aria-controls="zalo-{$key}" aria-selected="{if $key eq $ACTION}true{else}false{/if}" data-tab="{$key}" data-location="{$formAction}&amp;action={$key}{if $key eq 'vnsubdivisions' and $SUBDIV_PARENT neq ''}&amp;subdiv={$SUBDIV_PARENT}{/if}">
                    <i class="fa-solid {$tab.icon} fa-fw"></i>
                    <span class="flex-grow-1">{$tab.title}</span>
                    {if isset($SETUP_STATUS[$key]) and not $SETUP_STATUS[$key]}
                    <i class="fa-solid fa-circle-exclamation text-warning" aria-hidden="true"></i>
                    {/if}
                </a>
            </li>
            {/foreach}
        </ul>
    </div>
    <div class="col-md-8 col-xl-9 order-md-1">
        <div class="tab-content">
            {* Cấu hình chung *}
            <div class="tab-pane fade{if $ACTION eq 'general_settings'} show active{/if}" id="zalo-general_settings" role="tabpanel" aria-labelledby="zalo-general_settings-tab" tabindex="0">
                <form method="post" action="{$formAction}" class="ajax-submit" novalidate>
                    <div class="card mb-3">
                        <div class="card-header fs-5 fw-medium">{$LANG->getModule('general_settings')}</div>
                        <div class="card-body pt-4">
                            <div class="row mb-3">
                                <label for="zalo-oaid" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('zalo_official_account_id')}</label>
                                <div class="col-sm-8 col-xxl-6">
                                    <input type="text" class="form-control number" id="zalo-oaid" name="zaloOfficialAccountID" value="{$GCONFIG.zaloOfficialAccountID}" maxlength="50" inputmode="numeric" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="zalo-app-id" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('app_id')}</label>
                                <div class="col-sm-8 col-xxl-6">
                                    <input type="text" class="form-control number" id="zalo-app-id" name="zaloAppID" value="{$GCONFIG.zaloAppID}" maxlength="50" inputmode="numeric" autocomplete="off">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="zalo-app-secret" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('app_secret_key')}</label>
                                <div class="col-sm-8 col-xxl-6">
                                    <input type="password" class="form-control" id="zalo-app-secret" name="zaloAppSecretKey" value="{$GCONFIG.zaloAppSecretKey}" maxlength="50" autocomplete="new-password">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4 offset-xxl-3">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="func" value="settings">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card">
                    <div class="card-header fw-medium"><i class="fa-solid fa-circle-info text-info"></i> {$LANG->getModule('setup_guide')}</div>
                    <div class="card-body text-break">
                        {$LANG->getModule('oa_create_note')}
                        {$LANG->getModule('app_note')}
                    </div>
                </div>
            </div>

            {* Mã thực thi *}
            <div class="tab-pane fade{if $ACTION eq 'access_token_create'} show active{/if}" id="zalo-access_token_create" role="tabpanel" aria-labelledby="zalo-access_token_create-tab" tabindex="0">
                {if not $IS_ALLOWED}
                <div class="alert alert-warning d-flex gap-2 align-items-start mb-0" role="alert">
                    <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                    <div>
                        {$LANG->getModule('access_token_create_note')}.
                        <a href="#" class="alert-link text-nowrap" data-toggle="zaloSettingGoto" data-tab="general_settings">{$LANG->getModule('general_settings')} <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                {else}
                <div class="card mb-3">
                    <div class="card-header fs-5 fw-medium">{$LANG->getModule('access_token_create')}</div>
                    <div class="card-body pt-4">
                        <div class="row mb-3">
                            <label for="zalo-access-token" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('access_token')}</label>
                            <div class="col-sm-8 col-xxl-9">
                                <input type="text" class="form-control" id="zalo-access-token" name="zaloOAAccessToken" value="{$GCONFIG.zaloOAAccessToken}" readonly autocomplete="off">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="zalo-refresh-token" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('refresh_token')}</label>
                            <div class="col-sm-8 col-xxl-9">
                                <input type="text" class="form-control" id="zalo-refresh-token" name="zaloOARefreshToken" value="{$GCONFIG.zaloOARefreshToken}" readonly autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8 offset-sm-4 offset-xxl-3">
                                <a href="{$formAction}&amp;func=access_token_create" class="btn btn-primary" data-toggle="zaloAccessTokenCreate"><i class="fa-solid fa-rotate"></i> {$LANG->getModule('access_token_create')}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <form method="post" action="{$formAction}" class="ajax-submit" novalidate>
                    <div class="card">
                        <div class="card-header fs-5 fw-medium">{$LANG->getModule('access_token_copy')}</div>
                        <div class="card-body pt-4">
                            <div class="alert alert-info d-flex gap-2 align-items-start">
                                <i class="fa-solid fa-circle-info mt-1"></i>
                                <div>{$LANG->getModule('access_token_copy_note')}</div>
                            </div>
                            <div class="row mb-3">
                                <label for="zalo-new-access-token" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('access_token')} <span class="text-danger">(*)</span></label>
                                <div class="col-sm-8 col-xxl-9">
                                    <input type="text" class="form-control required" id="zalo-new-access-token" name="new_access_token" value="" maxlength="500" autocomplete="off">
                                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="zalo-new-refresh-token" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('refresh_token')} <span class="text-danger">(*)</span></label>
                                <div class="col-sm-8 col-xxl-9">
                                    <input type="text" class="form-control required" id="zalo-new-refresh-token" name="new_refresh_token" value="" maxlength="500" autocomplete="off">
                                    <div class="invalid-feedback">{$LANG->getGlobal('required_invalid')}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4 offset-xxl-3">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="func" value="access_token_copy">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                {/if}
            </div>

            {* Thiết lập Webhook *}
            <div class="tab-pane fade{if $ACTION eq 'webhook_setup'} show active{/if}" id="zalo-webhook_setup" role="tabpanel" aria-labelledby="zalo-webhook_setup-tab" tabindex="0">
                {if not $IS_ALLOWED}
                <div class="alert alert-warning d-flex gap-2 align-items-start mb-0" role="alert">
                    <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                    <div>
                        {$LANG->getModule('webhook_setup_note')}.
                        <a href="#" class="alert-link text-nowrap" data-toggle="zaloSettingGoto" data-tab="general_settings">{$LANG->getModule('general_settings')} <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                {else}
                <div class="card mb-3">
                    <div class="card-header fs-5 fw-medium">{$LANG->getModule('webhook_setup')}</div>
                    <div class="card-body pt-4">
                        <form method="post" action="{$formAction}" class="ajax-submit" novalidate>
                            <div class="row mb-3">
                                <label for="zalo-oa-secret" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('oa_secrect_key')}</label>
                                <div class="col-sm-8 col-xxl-6">
                                    <input type="password" class="form-control" id="zalo-oa-secret" name="zaloOASecretKey" value="{$GCONFIG.zaloOASecretKey}" maxlength="50" autocomplete="new-password">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4 offset-xxl-3">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="func" value="webhook">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4">
                        <form method="post" action="{$formAction}" class="ajax-submit" novalidate>
                            <div class="row mb-3">
                                <label for="zalo-webhook-ips" class="col-sm-4 col-xxl-3 col-form-label text-sm-end">{$LANG->getModule('zalowebhook_ips')}</label>
                                <div class="col-sm-8 col-xxl-6">
                                    <textarea class="form-control" id="zalo-webhook-ips" name="zaloWebhookIPs" rows="4">{$WEBHOOK_IPS}</textarea>
                                    <div class="invalid-feedback"></div>
                                    <div class="form-text">{$LANG->getModule('zalowebhook_ip_input_note')}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8 offset-sm-4 offset-xxl-3">
                                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                                    <input type="hidden" name="func" value="webhookIPs">
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
                                    <button type="button" class="btn btn-secondary" data-toggle="zaloWebhookIPCheck" data-tokend="{$CHECKSS}"><i class="fa-solid fa-magnifying-glass" data-icon="fa-magnifying-glass"></i> {$LANG->getModule('zalowebhook_ip_check')}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header fw-medium"><i class="fa-solid fa-circle-info text-info"></i> {$LANG->getModule('setup_guide')}</div>
                    <div class="card-body text-break">
                        {$LANG->getModule('webhook_note')}
                    </div>
                </div>
                <div class="modal fade" id="zalo-webhook-ip-modal" tabindex="-1" aria-labelledby="zalo-webhook-ip-modal-label" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="zalo-webhook-ip-modal-label">{$LANG->getModule('zalowebhook_ip_check')}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
                            </div>
                            <div class="modal-body text-break">{$LANG->getModule('zalowebhook_ip_check_note')}</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-toggle="zaloWebhookIPUpdate" data-tokend="{$CHECKSS}"><i class="fa-solid fa-rotate" data-icon="fa-rotate"></i> {$LANG->getModule('zalowebhook_ip_update')}</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{$LANG->getGlobal('close')}</button>
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
            </div>

            {* Kiểm tra tính tương thích của hệ thống *}
            <div class="tab-pane fade{if $ACTION eq 'system_check'} show active{/if}" id="zalo-system_check" role="tabpanel" aria-labelledby="zalo-system_check-tab" tabindex="0">
                <div class="card">
                    <div class="card-header fs-5 fw-medium">{$LANG->getModule('system_check')}</div>
                    <div class="card-body">
                        <div class="alert alert-{if $SYSTEM_SUITABLE}success{else}warning{/if} d-flex gap-2 align-items-start" role="alert">
                            <i class="fa-solid {if $SYSTEM_SUITABLE}fa-circle-check{else}fa-triangle-exclamation{/if} mt-1"></i>
                            <div><strong>{$LANG->getModule('finally')}:</strong> {$LANG->getModule($SYSTEM_SUITABLE ? 'finally_suitable' : 'finally_not_suitable')}</div>
                        </div>
                        <div class="table-responsive-lg table-card pb-1 mt-4">
                            <table class="table table-striped align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap" style="width:25%">{$LANG->getModule('directive')}</th>
                                        <th class="text-nowrap" style="width:15%">{$LANG->getModule('required_value')}</th>
                                        <th class="text-nowrap" style="width:15%">{$LANG->getModule('current_value')}</th>
                                        <th class="text-nowrap" style="width:15%">{$LANG->getModule('result')}</th>
                                        <th class="text-nowrap" style="width:30%">{$LANG->getModule('recommedation')}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {foreach from=$SYSTEM_CHECK item=row}
                                    <tr>
                                        <td class="fw-medium">{$row.key}</td>
                                        <td>{$row.required}</td>
                                        <td>{$row.current}</td>
                                        <td class="text-nowrap">
                                            {if $row.suitable}
                                            <span class="badge text-bg-success"><i class="fa-solid fa-check"></i> {$LANG->getModule('suitable')}</span>
                                            {else}
                                            <span class="badge text-bg-danger"><i class="fa-solid fa-xmark"></i> {$LANG->getModule('notsuitable')}</span>
                                            {/if}
                                        </td>
                                        <td>{if not $row.suitable}{$row.recommendation}{/if}</td>
                                    </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {* Các đơn vị hành chính của Việt Nam, nội dung tải bằng ajax *}
            <div class="tab-pane fade{if $ACTION eq 'vnsubdivisions'} show active{/if}" id="zalo-vnsubdivisions" role="tabpanel" aria-labelledby="zalo-vnsubdivisions-tab" tabindex="0" data-load="vnsubdivisionsLoad" data-loaded="false" data-subdiv-parent="{$SUBDIV_PARENT}">
                <div class="zalo-tab-content">
                    <div class="card">
                        <div class="card-body text-center text-body-secondary py-5"><i class="fa-solid fa-spinner fa-spin-pulse fa-2x"></i></div>
                    </div>
                </div>
            </div>

            {* Mã gọi quốc gia, nội dung tải bằng ajax *}
            <div class="tab-pane fade{if $ACTION eq 'callingcodes'} show active{/if}" id="zalo-callingcodes" role="tabpanel" aria-labelledby="zalo-callingcodes-tab" tabindex="0" data-load="callingcodesLoad" data-loaded="false">
                <div class="zalo-tab-content">
                    <div class="card">
                        <div class="card-body text-center text-body-secondary py-5"><i class="fa-solid fa-spinner fa-spin-pulse fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
