<div class="card mb-4">
    <div class="card-body">
        {$LANG->getModule('suspend_status')}: <span class="badge text-bg-{$OLD_SUSPEND ? 'danger' : 'success'}">{$LANG->getModule("suspend_status`$OLD_SUSPEND`")}</span>
    </div>
</div>
{if empty($SUSP_REASON)}
<div class="alert alert-info" role="alert">{$LANG->getModule('suspend_info_empty', $USER.username)}</div>
{else}
<div class="card mb-4">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('suspend_info_yes')}</div>
    </div>
    <div class="card-body">
        <div class="table-responsive-lg table-card pb-1">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('suspend_start')}</th>
                        <th class="text-nowrap" style="width: 30%;">{$LANG->getModule('suspend_end')}</th>
                        <th class="text-nowrap" style="width: 60%;">{$LANG->getModule('suspend_reason')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$SUSP_REASON item=row}
                    <tr{if not empty($row.endtime)} class="text-decoration-line-through"{/if}>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    {if not empty($row.endtime)}
                                    <i class="fa-solid fa-stop text-danger fa-fw text-center"></i>
                                    {else}
                                    <i class="fa-solid fa-play text-success fa-fw text-center"></i>
                                    {/if}
                                </div>
                                <div>
                                    <div>{$LANG->getGlobal('at')}: {datetime_format($row.starttime)}</div>
                                    <div>{$LANG->getGlobal('by')}: {$ADMINS[$row.start_admin] ?? 'N/A'}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {if not empty($row.endtime)}
                            <div>{$LANG->getGlobal('at')}: {datetime_format($row.endtime)}</div>
                            <div>{$LANG->getGlobal('by')}: {$ADMINS[$row.end_admin] ?? 'N/A'}</div>
                            {/if}
                        </td>
                        <td>{$row.info}</td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{/if}
{if $ALLOW_CHANGE}
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule("chg_is_suspend`$NEW_SUSPEND`")}</div>
    </div>
    <div class="card-body pt-4 bg-body">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;admin_id={$USER.userid}" novalidate>
            {if $NEW_SUSPEND}
            <div class="row mb-3">
                <label for="element_new_reason" class="col-12 col-sm-3 col-form-label text-sm-end">{$LANG->getModule('suspend_reason')} <span class="text-danger">(*)</span></label>
                <div class="col-12 col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="element_new_reason" name="new_reason" value="" maxlength="55">
                    <div class="invalid-feedback">{$LANG->getModule('susp_reason_empty')}</div>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="sendmail" value="1" role="switch" id="element_sendmail">
                        <label class="form-check-label" for="element_sendmail">{$LANG->getModule('suspend_sendmail')}</label>
                    </div>
                </div>
            </div>
            {if $smarty.const.NV_IS_GODADMIN and (($NEW_SUSPEND and !empty($SUSP_REASON)) or (empty($NEW_SUSPEND) and count($SUSP_REASON) >= 1))}
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="clean_history" value="1" role="switch" id="element_clean_history">
                        <label class="form-check-label" for="element_clean_history">{$LANG->getModule('clean_history')}</label>
                    </div>
                </div>
            </div>
            {/if}
            <div class="row">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-3">
                    <input name="save" type="hidden" value="1">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
{/if}
