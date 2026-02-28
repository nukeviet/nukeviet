{if not $ACTIVE_2STEP}
<div class="alert alert-info" role="alert">
    <div class="text-center">
        <i class="fa-solid fa-lock fa-3x" aria-hidden="true"></i>
        <h1 class="mb-4">{$LANG->getModule('title_2step_off')}</h1>
        <p>{$LANG->getModule('title_2step_off_note')}</p>
        <a class="btn btn-primary" href="{$LINK_TURNON}">{$LANG->getModule('title_2step_turnon')}</a>
    </div>
    </div>
{else}
<script src="{$smarty.const.NV_STATIC_URL}themes/{$TEMPLATE_JS}/js/users.passkey.js"></script>
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>

<div class="vstack gap-3">
    <div class="card">
        <div class="card-body shadow-sm">
            <div class="h2 mb-2"><strong>{$LANG->getModule('preferred_2fa_method')}</strong></div>
            <p class="text-muted">{$LANG->getModule('preferred_2fa_method_help')}</p>
            <div class="input-group tstep-select shadow-sm rounded-3">
                <span class="input-group-text"><i class="fa fa-shield" aria-hidden="true"></i></span>
                <select class="form-select form-select-lg" name="preferred_2fa_method" data-toggle="preferred_2fa_method" data-current="{$DATA.pref_2fa}" data-checkss="{$NV_CHECK_SESSION}">
                    {if !empty($DATA.publicKeys)}
                    <option value="2" {if $DATA.pref_2fa==2}selected{/if}>{$LANG->getModule('tstep_key')}</option>
                    {/if}
                    <option value="1" {if $DATA.pref_2fa==1}selected{/if}>{$LANG->getModule('tstep_app')}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="h5 mb-0">{$LANG->getModule('title_2step')}</div>
                <div class="dropdown">
                    <a id="tstep-turnoff-btn" href="#" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-ellipsis-h text-white" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="tstep-turnoff-btn">
                        <li><a id="btn-turnoff-2step" class="dropdown-item" href="#md-turnoff-2step" data-target="#md-turnoff-2step" data-toggle="modal"><i class="fa fa-ban text-danger" data-icon="fa-ban" aria-hidden="true"></i> {$LANG->getModule('turnoff_2step')}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body shadow-sm">
            <div class="vstack gap-3">
                <div class="d-flex gap-3 align-items-start" id="container-edit-app" {if $SCROLL_APP}data-autoscroll="1"{/if}>
                    <div class="rounded-3 bg-body-secondary p-3 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                    </div>
                    <div class="flex-grow-1 rounded-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="h5 mb-0">{$LANG->getModule('tstep_app')}</div>
                            <span class="badge bg-success">{$LANG->getModule('configured')}</span>
                        </div>
                        <div class="text-muted mt-1">{$LANG->getModule('tstep_app_note')}.</div>
                        {if $DATA.show_type=='app'}
                        <div class="pt-3">
                            <div class="h5">{$LANG->getModule('cfg_step1')}</div>
                            <div class="text-center">
                                <img alt="QR" src="{$QR_SRC}" class="twostep-qrimg img-thumbnail">
                            </div>
                            <hr>
                            <p>{$LANG->getModule('cfg_step1_manual')} <a href="#manualsecretkey" data-toggle="manualsecretkey">{$LANG->getModule('cfg_step1_manual1')}</a> {$LANG->getModule('cfg_step1_manual2')}.</p>
                            <p>{$LANG->getModule('cfg_step2_info')}</p>
                            <div class="h5 mb-2">{$LANG->getModule('cfg_step2')}</div>
                            <form action="{$FORM_ACTION}" method="post" data-toggle="opt_validForm" autocomplete="off" novalidate>
                                <div class="nv-info mb-3" data-default="" style="display: none"></div>
                                <div class="form-detail">
                                    <div class="step1">
                                        <div class="mb-3">
                                            <div class="input-group">
                                                <span class="input-group-text"><em class="fa fa-key fa-lg"></em></span>
                                                <input type="text" class="required form-control" placeholder="123456" value="" name="opt" maxlength="6" data-pattern="/^(.){ldelim}6,{rdelim}$/" data-toggle="valid2faErrorHidden" data-mess="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <input type="hidden" name="checkss" value="{$NV_CHECK_SESSION}">
                                        <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                                        <button class="bsubmit btn btn-primary" type="submit">{$LANG->getModule('confirm')}</button>
                                    </div>
                                </div>
                            </form>
                            <div class="hidden" id="manualsecretkey" title="{$LANG->getModule('setup_key')}">
                                <div class="twostep-manualsecretkey">
                                    <div class="text-center">
                                        <strong>{$SECRETKEY}</strong>
                                    </div>
                                    <hr>
                                    {$LANG->getModule('cfg_step1_note')}
                                </div>
                            </div>
                        </div>
                        {/if}
                    </div>
                    <div class="tstep-action">
                        <a href="{$DATA.page_url}&amp;type=app" class="btn btn-outline-secondary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2"><i class="fa fa-pencil" aria-hidden="true"></i><span>{$LANG->getGlobal('edit')}</span></a>
                    </div>
                </div>
                <hr class="my-3">

                <div class="d-flex gap-3 align-items-start" data-toggle="ctn">
                    <div class="rounded-3 bg-body-secondary p-3 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                        <i class="fa fa-key fa-2x" aria-hidden="true"></i>
                    </div>
                    <div class="flex-grow-1 rounded-3">
                        <form method="post" action="{$DATA.form_url}" id="passkey-form">
                            <input type="hidden" name="checkss" value="{$DATA.checkss}">
                            <div class="d-flex align-items-start gap-2">
                                <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3">
                                        <div class="h5 mb-0">{$LANG->getModule('security_keys')}</div>
                                        {if $DATA.security_keys>0}
                                        <span class="badge bg-success">{$LANG->getModule('configured')}</span>
                                        <span class="badge bg-secondary">{$NUMBER_KEYS_TEXT}</span>
                                        {/if}
                                    </div>
                                    <div class="text-muted mt-1">{$LANG->getModule('security_keys_note')}.</div>
                                    {if $MESSAGE ne ''}
                                    <div class="alert alert-info mb-0 mt-2" role="alert">{$MESSAGE}</div>
                                    {/if}
                                    <div class="text-danger hidden" data-toggle="passkey-not-supported">{$LANG->getModule('passkey_not_supported')}</div>
                                    <div class="text-danger mt-2 hidden" data-toggle="error"></div>
                                </div>
                            </div>
                            {if $DATA.security_keys>0}
                            <div class="collapse {if $SHOW_KEYS}show{/if}" id="security-keys" data-show-keys-url="{$DATA.page_url}&amp;type=key" data-page-url="{$DATA.page_url}">
                                <div class="pt-3">
                                    {foreach from=$SECKEYS item=SECKEY}
                                    <div class="d-flex justify-content-between align-items-start gap-3 item">
                                        <div>
                                            <strong>{$SECKEY.nickname}</strong>
                                            {if $SECKEY.this_client}<span class="badge bg-secondary">{$LANG->getModule('passkey_seenthis')}</span>{/if}
                                            <div class="mt-2 text-muted">
                                                {$LANG->getModule('passkey_created_at')}: {$SECKEY.created_at} |
                                                {$LANG->getModule('passkey_last_used_at')}: {$SECKEY.last_used_at}.
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 align-items-start">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="edit" data-id="{$SECKEY.id}" data-nickname="{$SECKEY.nickname}"><i class="fa fa-pencil" data-icon="fa-pencil" aria-hidden="true"></i> {$LANG->getGlobal('edit')}</button>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="del" data-id="{$SECKEY.id}"><i class="fa fa-trash" data-icon="fa-trash" aria-hidden="true"></i> {$LANG->getGlobal('delete')}</button>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    {/foreach}
                                    <button class="btn btn-outline-secondary" type="button" data-toggle="passkey-add" data-enable-login="0"><i class="fa fa-plus" data-icon="fa-plus" aria-hidden="true"></i> {$LANG->getModule('security_keys_add')}</button>
                                </div>
                            </div>
                            {/if}
                        </form>
                    </div>
                    <div class="tstep-action">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2" data-toggle="collapse" data-target="#security-keys" aria-expanded="{if $SHOW_KEYS}true{else}false{/if}" aria-controls="security-keys"><i class="fa fa-eye" aria-hidden="true"></i><span>{$LANG->getGlobal('view')}</span></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white py-3">
            <div class="h5 mb-0">{$LANG->getModule('backup_methods')}</div>
        </div>
        <div class="card-body shadow-sm">
            <div class="d-flex gap-3 align-items-start">
                <div class="rounded-3 bg-body-secondary p-3 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="fa fa-terminal fa-2x" aria-hidden="true"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3">
                        <div class="h5 mb-0">{$LANG->getModule('recovery_codes')}</div>
                        <span class="badge bg-secondary">{$REMAIN_CODE_TEXT}</span>
                    </div>
                    <div class="text-muted mt-1">{$LANG->getModule('recovery_codes_note')}.</div>
                    {if $USEDUP_CODE}
                    <div class="alert alert-danger mb-0 mt-2" role="alert">{$LANG->getModule('usedup_code')}</div>
                    {elseif $LACK_CODE}
                    <div class="alert alert-warning mb-0 mt-2" role="alert">{$LANG->getModule('lack_code')}</div>
                    {/if}
                    <div class="collapse {if $SHOW_CODES}show{/if} rounded-3" id="recovery-codes" data-show-codes-url="{$DATA.page_url}&amp;type=code" data-page-url="{$DATA.page_url}">
                        <div class="row">
                            {foreach from=$DATA.backupcodes item=CODE}
                            <div class="col-12 text-center">
                                <div class="recovery-code">
                                    {if empty($CODE.is_used)}<i class="fa fa-check-circle text-success" aria-hidden="true" title="{$LANG->getModule('code_is_available')}" aria-label="{$LANG->getModule('code_is_available')}"></i>{else}<i class="fa fa-ban text-danger" aria-hidden="true" title="{$LANG->getModule('code_is_used')}" aria-label="{$LANG->getModule('code_is_used')}"></i>{/if}
                                    <span>{$CODE.code}</span>
                                </div>
                            </div>
                            {/foreach}
                        </div>
                        <div class="text-center mt-3">
                            <a class="btn btn-primary confirmed-codes" href="{$DATA.download_code_url}"><i class="fa fa-download" aria-hidden="true"></i> {$LANG->getGlobal('download')}</a>
                            <a class="btn btn-primary confirmed-codes" href="{$DATA.print_code_url}" data-toggle="print-codes"><i class="fa fa-print" aria-hidden="true"></i> {$LANG->getGlobal('print')}</a>
                            <button class="btn btn-primary confirmed-codes" type="button" data-toggle="copy-codes" data-clipboard-text="{$DATA.text_codes}" data-copied="{$LANG->getGlobal('copied')}"><i class="fa fa-clipboard" aria-hidden="true"></i> <span>{$LANG->getGlobal('copy_to_clipboard')}</span></button>
                        </div>
                        <hr>
                        <p class="text-muted">{$LANG->getModule('creat_other_note')}</p>
                        <button type="button" class="btn btn-outline-secondary" data-toggle="changecode2step" data-tokend="{$NV_CHECK_SESSION}"><i class="fa fa-refresh" aria-hidden="true"></i> {$LANG->getModule('creat_other_code')}</button>
                    </div>
                </div>
                <div class="tstep-action">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-2" data-toggle="collapse" data-target="#recovery-codes" aria-expanded="{if $SHOW_CODES}true{else}false{/if}" aria-controls="recovery-codes"><i class="fa fa-eye"></i><span>{$LANG->getGlobal('view')}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- START FORFOOTER -->
<div class="modal fade" tabindex="-1" role="dialog" data-toggle="md-edit-passkey">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title h3"><strong>{$LANG->getModule('passkey_nickname_edit')}</strong></div>
            </div>
            <div class="modal-body">
                <form action="{$DATA.form_url}" id="passkey-form-nickname" method="post" autocomplete="off" novalidate>
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="id" value="0">
                    <div class="form-group">
                        <label for="element_nickname" class="control-label">{$LANG->getModule('passkey_nickname')} <span class="text-danger">(*)</span>:</label>
                        <input type="text" class="form-control" name="nickname" data-nickname="" id="element_nickname" value="" maxlength="100">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" data-icon="fa-floppy-o" aria-hidden="true"></i> {$LANG->getGlobal('save')}</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title h3"><strong>{$LANG->getModule('deactive_mess')}</strong></div>
            </div>
            <div class="modal-body">
                <p>{$LANG->getModule('title_2step_off_note2')}.</p>
                <p>{$LANG->getModule('title_2step_off_note3')}.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-toggle="turnoff2step" data-tokend="{$NV_CHECK_SESSION}"><i class="fa fa-ban" data-icon="fa-ban" aria-hidden="true"></i> {$LANG->getModule('turnoff_2step')}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> {$LANG->getGlobal('close')}</button>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
{/if}
