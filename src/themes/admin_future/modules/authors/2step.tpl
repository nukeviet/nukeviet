<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                {if empty($USER.active2step)}
                <p class="my-1">{$LANG->getModule('2step_code_off')}</p>
                {else}
                <p class="my-1">{$LANG->getModule('2step_code_on')}</p>
                {/if}
            </div>
            {if $ADMIN.admin_id eq $ADMIN_INFO.admin_id}
            <div class="ms-3">
                <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=two-step-verification" class="btn btn-primary">{$LANG->getGlobal('manage')}</a>
            </div>
            {elseif $MANAGER_USER_2STEP and not empty($USER.active2step)}
            <div class="ms-3">
                <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=users&amp;{$smarty.const.NV_OP_VARIABLE}=edit_2step&amp;userid={$USER.userid}" class="btn btn-primary">{$LANG->getGlobal('manage')}</a>
            </div>
            {/if}
        </div>
    </div>
</div>
{if not empty($ERROR)}
<div class="alert alert-danger" role="alert">{$ERROR}</div>
{/if}
<div class="card">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('2step_oauth')}</div>
    </div>
    <div class="card-body">
        {if empty($OAUTHS)}
        <div class="alert alert-info mb-0" role="alert">{$LANG->getModule('2step_oauth_empty')}</div>
        {else}
        <div class="table-responsive-lg table-card" id="list-items">
            <table class="table table-striped align-middle table-sticky mb-0">
                <thead class="text-muted">
                    <tr>
                        <th class="text-nowrap" style=" width:30%;">{$LANG->getModule('2step_oauth_gate')}</th>
                        <th class="text-nowrap" style=" width:40%;">{$LANG->getModule('2step_oauth_email_or_id')}</th>
                        <th class="text-nowrap" style=" width:20%;">{$LANG->getModule('2step_addtime')}</th>
                        <th class="text-end text-nowrap" style=" width:10%;">{$LANG->getModule('funcs')}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$OAUTHS item=oauth}
                    <tr>
                        <td>{$oauth.oauth_server}</td>
                        <td class="text-break">{$oauth.oauth_email ?: $oauth.oauth_id}</td>
                        <td class="text-nowrap">{datetime_format($oauth.addtime, 1)}</td>
                        <td class="text-end text-nowrap">
                            <a href="#" data-toggle="del2step" data-userid="{$ADMIN.admin_id}" data-id="{$oauth.id}" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {/if}
    </div>
    <div class="card-footer">
        <div class="row g-2">
            <div class="col-sm-7">
                {if $ADMIN.admin_id eq $ADMIN_INFO.admin_id and not empty($SERVER_ALLOWED)}
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {$LANG->getModule('2step_add')}
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$SERVER_ALLOWED key=key item=value}
                        <li><a class="dropdown-item" href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=2step&amp;auth={$key}">{$LANG->getModule("2step_add_`$key`")}</a></li>
                        {/foreach}
                    </ul>
                </div>
                {/if}
            </div>
            <div class="col-sm-5 text-sm-end">
                {if not empty($OAUTHS)}
                <a href="#" data-toggle="truncate2step" data-userid="{$ADMIN.admin_id}" class="btn btn-danger"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('2step_delete_all')}</a>
                {/if}
            </div>
        </div>
    </div>
</div>
