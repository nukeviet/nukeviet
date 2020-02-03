<div class="card card-flat">
    <div class="card-body pt-4">
        <h3 class="2step-status my-0">
            {if empty($ACTIVE2STEP)}
            {$LANG->get('2step_code_off')}
            {else}
            {$LANG->get('2step_code_on')}
            {/if}
        </h3>
        {if $USERID eq $ADMIN_INFO.admin_id}
        <div class="mt-2">
            <a href="{$NV_BASE_SITEURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}=two-step-verification" class="btn btn-primary">{$LANG->get('manage')}</a>
        </div>
        {elseif not empty($ACTIVE2STEP) and $ALLOWED_MANAGER_2STEP}
        <div class="mt-2">
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}=users&amp;{$NV_OP_VARIABLE}=edit_2step&amp;userid={$USERID}" class="btn btn-primary">{$LANG->get('manage')}</a>
        </div>
        {/if}
    </div>
</div>

{if not empty($ERROR)}
<div role="alert" class="alert alert-danger alert-dismissible">
    <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
    <div class="icon"><i class="far fa-times-circle"></i></div>
    <div class="message">{$ERROR}</div>
</div>
{/if}

<div class="card card-table card-footer-nav">
    <div class="card-header">
        {$LANG->get('2step_oauth')}
    </div>
    <div class="card-body">
        {if empty($ARRAY_OAUTH)}
        <div role="alert" class="alert alert-warning alert-dismissible mx-4 mt-0 mb-4">
            <button type="button" data-dismiss="alert" aria-label="{$LANG->get('close')}" class="close"><i class="fas fa-times"></i></button>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="message">{$LANG->get('2step_oauth_empty')}</div>
        </div>
        {else}
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-nowrap" style=" width:30%;">{$LANG->get('2step_oauth_gate')}</th>
                        <th class="text-nowrap" style=" width:40%;">{$LANG->get('2step_oauth_email')}</th>
                        <th class="text-nowrap" style=" width:20%;">{$LANG->get('2step_addtime')}</th>
                        <th class="text-nowrap" style=" width:10%;"></th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$ARRAY_OAUTH item=row}
                    <tr>
                        <td>{$row.oauth_server}</td>
                        <td>{$row.oauth_email}</td>
                        <td class="text-nowrap">{"H:i d/m/Y"|date:$row.addtime}</td>
                        <td class="text-right text-nowrap">
                            <a href="javascript:void(0);" class="btn btn-danger" onclick="nv_del_oauthone('{$row.id}', {$USERID}, '{$TOKEND}');"><i class="icon icon-left fas fa-trash"></i> {$LANG->get('delete')}</a>
                        </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
        {/if}
    </div>
    <div class="card-footer">
        {if $USERID eq $ADMIN_INFO.admin_id}
        <div class="page-tools">
            {if isset($SERVER_ALLOWED.facebook)}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=2step&amp;auth=facebook" class="btn btn-primary" ><i class="icon icon-left fab fa-facebook-square"></i> {$LANG->get('2step_add_facebook')}</a>
            {/if}
            {if isset($SERVER_ALLOWED.google)}
            <a href="{$NV_BASE_ADMINURL}index.php?{$NV_LANG_VARIABLE}={$NV_LANG_DATA}&amp;{$NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$NV_OP_VARIABLE}=2step&amp;auth=google" class="btn btn-danger" ><i class="icon icon-left fab fa-google"></i> {$LANG->get('2step_add_google')}</a>
            {/if}
        </div>
        {/if}
        {if not empty($ARRAY_OAUTH)}
        <div class="page-nav">
            <a href="javascript:void(0);" class="btn btn-danger" onclick="nv_del_oauthall({$USERID}, '{$TOKEND}');"><i class="icon icon-left fas fa-trash"></i> {$LANG->get('2step_delete_all')}</a>
        </div>
        {/if}
    </div>
</div>
