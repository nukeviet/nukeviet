{if !$ACTIVE2STEP}
<div class="alert alert-info" role="alert">
    <div class="text-center">
        <i class="fa-solid fa-lock fa-3x" aria-hidden="true"></i>
        <h1 class="mb-4 mt-3">{$LANG->getModule('title_2step_off')}</h1>
        <p>{$LANG->getModule('title_2step_off_note')}</p>
        <a class="btn btn-primary" href="{$LINK_TURNON}">{$LANG->getModule('title_2step_turnon')}</a>
    </div>
</div>
{else}
<script src="{$smarty.const.NV_STATIC_URL}themes/{$TEMPLATE_JS}/js/users.passkey.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="card mb-3">
    <div class="card-body">
        <div class="h6 mb-1"><strong>{$LANG->getModule('preferred_2fa_method')}</strong></div>
        <p class="mb-2">{$LANG->getModule('preferred_2fa_method_help')}</p>
        <select class="form-select w-auto" name="preferred_2fa_method"
                data-toggle="preferred_2fa_method"
                data-current="{$DATA.pref_2fa}"
                data-checkss="{$smarty.const.NV_CHECK_SESSION}">
            {if $DATA.publicKeys}
            <option value="2"{if $DATA.pref_2fa == 2} selected{/if}>{$LANG->getModule('tstep_key')}</option>
            {/if}
            <option value="1"{if $DATA.pref_2fa == 1} selected{/if}>{$LANG->getModule('tstep_app')}</option>
        </select>
    </div>
</div>
<ul class="list-group mb-3">
    <li class="list-group-item text-bg-primary">
        <div class="my-1 d-flex gap-2 justify-content-between align-items-center">
            <div class="h6 mb-0"><strong>{$LANG->getModule('title_2step')}</strong></div>
            <div class="dropdown">
                <a id="tstep-turnoff-btn" href="#"
                   class="d-flex align-items-center justify-content-center"
                   data-bs-toggle="dropdown" role="button"
                   aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis text-white" aria-hidden="true"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="tstep-turnoff-btn">
                    <li>
                        <a class="dropdown-item" href="#md-turnoff-2step"
                           data-bs-toggle="modal" data-bs-target="#md-turnoff-2step">
                            <i class="fa-solid fa-ban text-danger" aria-hidden="true"></i> {$LANG->getModule('turnoff_2step')}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex gap-2" id="container-edit-app"{if $DATA.show_type == 'app'} data-autoscroll="1"{/if}>
        <div class="pt-3 px-2">
            <i class="fa-solid fa-mobile-screen-button fa-2x fa-fw text-center" aria-hidden="true"></i>
        </div>
        <div class="flex-grow-1 flex-shrink-1">
            <h6>
                <strong>{$LANG->getModule('tstep_app')}</strong>
                <span class="badge bg-success">{$LANG->getModule('configured')}</span>
            </h6>
            <div class="text-muted">{$LANG->getModule('tstep_app_note')}.</div>
            {if $DATA.show_type == 'app'}
            <div class="pt-3">
                {include file='form-setup-app.tpl'}
            </div>
            {/if}
        </div>
        <div>
            <a href="{$DATA.page_url}&amp;type=app" class="btn btn-secondary btn-sm text-nowrap">
                <i class="fa-solid fa-pencil" aria-hidden="true"></i> {$LANG->getGlobal('edit')}
            </a>
        </div>
    </li>
    <li class="list-group-item d-flex gap-2">
        <div class="pt-3 px-2">
            <i class="fa-solid fa-key fa-2x fa-fw text-center" aria-hidden="true"></i>
        </div>
        <div class="flex-grow-1 flex-shrink-1">
            <form method="post" action="{$DATA.form_url}" id="passkey-form">
                <input type="hidden" name="checkss" value="{$DATA.checkss}">
                <div class="d-flex gap-2">
                    <div class="flex-grow-1 flex-shrink-1">
                        <h6>
                            <strong>{$LANG->getModule('security_keys')}</strong>
                            {if $DATA.security_keys > 0}
                            <span class="badge bg-success">{$LANG->getModule('configured')}</span>
                            <span class="badge bg-secondary">{$LANG->getModule('number_keys', $DATA.security_keys|dnumber)}</span>
                            {/if}
                        </h6>
                        <div class="text-muted">{$LANG->getModule('security_keys_note')}.</div>
                        {if $DATA.login_keys > 0}
                        <div class="alert alert-info mb-0 mt-2" role="alert">{$LANG->getModule('rcode_note', $DATA.login_keys|dnumber)}</div>
                        {/if}
                        <div class="text-danger d-none" data-toggle="passkey-not-supported">{$LANG->getModule('passkey_not_supported')}</div>
                        <div class="text-danger mt-2 d-none" data-toggle="error"></div>
                    </div>
                    <div>
                        {if $DATA.security_keys == 0}
                        <button type="button" class="btn btn-secondary btn-sm d-none text-nowrap"
                                data-toggle="passkey-add" data-enable-login="0">
                            <i class="fa-solid fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {$LANG->getGlobal('add')}
                        </button>
                        {else}
                        <button type="button" class="btn btn-secondary btn-sm text-nowrap"
                                data-bs-toggle="collapse" data-bs-target="#security-keys"
                                aria-expanded="{if $DATA.show_type == 'key'}true{else}false{/if}"
                                aria-controls="security-keys">
                            <i class="fa-solid fa-eye" aria-hidden="true"></i> {$LANG->getGlobal('view')}
                        </button>
                        {/if}
                    </div>
                </div>
                {if $DATA.security_keys > 0}
                <div class="collapse{if $DATA.show_type == 'key'} show{/if}" id="security-keys"
                     data-show-keys-url="{$DATA.page_url}&amp;type=key"
                     data-page-url="{$DATA.page_url}">
                    <div class="pt-3">
                        {foreach from=$SECKEYS item=seckey}
                        <div class="d-flex justify-content-between gap-2 item">
                            <div>
                                <strong>{$seckey.nickname}</strong>
                                {if $seckey.is_this_client}
                                <span class="badge bg-secondary">{$LANG->getModule('passkey_seenthis')}</span>
                                {/if}
                                <div class="mt-1 text-muted">
                                    {$LANG->getModule('passkey_created_at')}: {$seckey.created_at} |
                                    {$LANG->getModule('passkey_last_used_at')}: {$seckey.last_used_at}.
                                </div>
                            </div>
                            <div>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                            data-toggle="edit"
                                            data-id="{$seckey.id}"
                                            data-nickname="{$seckey.nickname}">
                                        <i class="fa-solid fa-pencil" data-icon="fa-pencil" aria-hidden="true"></i> {$LANG->getGlobal('edit')}
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            data-toggle="del"
                                            data-id="{$seckey.id}">
                                        <i class="fa-solid fa-trash" data-icon="fa-trash" aria-hidden="true"></i> {$LANG->getGlobal('delete')}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr class="my-2">
                        {/foreach}
                        <button class="btn btn-secondary" type="button"
                                data-toggle="passkey-add" data-enable-login="0">
                            <i class="fa-solid fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {$LANG->getModule('security_keys_add')}
                        </button>
                    </div>
                </div>
                {/if}
            </form>
        </div>
    </li>
    <li class="list-group-item list-group-item-primary">
        <div class="h5 mb-0"><strong>{$LANG->getModule('backup_methods')}</strong></div>
    </li>
    <li class="list-group-item d-flex gap-2">
        <div class="pt-3 px-2">
            <i class="fa-solid fa-terminal fa-2x fa-fw text-center" aria-hidden="true"></i>
        </div>
        <div class="flex-grow-1 flex-shrink-1">
            <h6>
                <strong>{$LANG->getModule('recovery_codes')}</strong>
                <span class="badge bg-secondary">{$LANG->getModule('remain_code', $CODE_UNUSED|dnumber)}</span>
            </h6>
            <div class="text-muted">{$LANG->getModule('recovery_codes_note')}.</div>
            {if $CODE_UNUSED < 1}
            <div class="alert alert-danger mb-0 mt-2" role="alert">{$LANG->getModule('usedup_code')}</div>
            {elseif $CODE_UNUSED < 3}
            <div class="alert alert-warning mb-0 mt-2" role="alert">{$LANG->getModule('lack_code')}</div>
            {/if}
            <div class="collapse{if $DATA.show_type == 'code'} show{/if}" id="recovery-codes"
                 data-show-codes-url="{$DATA.page_url}&amp;type=code"
                 data-page-url="{$DATA.page_url}">
                <div class="row mt-2">
                    {foreach from=$DATA.backupcodes item=code}
                    <div class="col-6 col-sm-4 text-center">
                        <div class="recovery-code font-monospace">
                            {if $code.is_used}
                            <i class="fa-solid fa-ban text-danger" aria-hidden="true"
                               title="{$LANG->getModule('code_is_used')}"
                               aria-label="{$LANG->getModule('code_is_used')}"></i>
                            {else}
                            <i class="fa-solid fa-circle-check text-success" aria-hidden="true"
                               title="{$LANG->getModule('code_is_available')}"
                               aria-label="{$LANG->getModule('code_is_available')}"></i>
                            {/if}
                            <span>{$code.code}</span>
                        </div>
                    </div>
                    {/foreach}
                </div>
                <div class="text-center mt-4 d-flex flex-wrap gap-2 justify-content-center align-items-center">
                    <a class="btn btn-primary confirmed-codes" href="{$DATA.download_code_url}">
                        <i class="fa-solid fa-download" aria-hidden="true"></i> {$LANG->getGlobal('download')}
                    </a>
                    <a class="btn btn-primary confirmed-codes" href="{$DATA.print_code_url}" data-toggle="print-codes">
                        <i class="fa-solid fa-print" aria-hidden="true"></i> {$LANG->getGlobal('print')}
                    </a>
                    <button class="btn btn-primary confirmed-codes" type="button"
                            data-toggle="copy-codes"
                            data-clipboard-text="{$DATA.text_codes}"
                            data-copied="{$LANG->getGlobal('copied')}">
                        <i class="fa-solid fa-clipboard" aria-hidden="true"></i>
                        <span>{$LANG->getGlobal('copy_to_clipboard')}</span>
                    </button>
                </div>
                <hr>
                <p class="text-muted">{$LANG->getModule('creat_other_note')}</p>
                <button type="button" class="btn btn-secondary"
                        data-toggle="changecode2step"
                        data-tokend="{$smarty.const.NV_CHECK_SESSION}">
                    <i class="fa-solid fa-arrows-rotate" data-icon="fa-arrows-rotate" aria-hidden="true"></i> {$LANG->getModule('creat_other_code')}
                </button>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-secondary btn-sm text-nowrap"
                    data-bs-toggle="collapse" data-bs-target="#recovery-codes"
                    aria-expanded="{if $DATA.show_type == 'code'}true{else}false{/if}"
                    aria-controls="recovery-codes">
                <i class="fa-solid fa-eye" aria-hidden="true"></i> {$LANG->getGlobal('view')}
            </button>
        </div>
    </li>
</ul>
{* START FORFOOTER *}
<div class="modal fade" tabindex="-1" role="dialog" data-toggle="md-edit-passkey">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>{$LANG->getModule('passkey_nickname_edit')}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <form action="{$DATA.form_url}" id="passkey-form-nickname" method="post" autocomplete="off" novalidate>
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="id" value="0">
                    <div class="mb-3">
                        <label for="element_nickname" class="form-label">
                            {$LANG->getModule('passkey_nickname')} <span class="text-danger">(*)</span>:
                        </label>
                        <input type="text" class="form-control" name="nickname"
                               data-nickname="" id="element_nickname" value="" maxlength="100">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk" aria-hidden="true"></i> {$LANG->getGlobal('save')}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="md-turnoff-2step" aria-labelledby="btn-turnoff-2step">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><strong>{$LANG->getModule('deactive_mess')}</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <p>{$LANG->getModule('title_2step_off_note2')}.</p>
                <p>{$LANG->getModule('title_2step_off_note3')}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                        data-toggle="turnoff2step"
                        data-tokend="{$smarty.const.NV_CHECK_SESSION}">
                    <i class="fa-solid fa-ban" data-icon="fa-ban" aria-hidden="true"></i> {$LANG->getModule('turnoff_2step')}
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i> {$LANG->getGlobal('close')}
                </button>
            </div>
        </div>
    </div>
</div>
{* END FORFOOTER *}
{/if}
