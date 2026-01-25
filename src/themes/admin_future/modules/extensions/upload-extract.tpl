{if not empty($NO_EXTRACT)}
<div class="card border-danger border-1">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('autoinstall_cantunzip')}</div>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$NO_EXTRACT item=value}
        <li class="list-group-item">
            <div class="text-break">{$value}</div>
        </li>
        {/foreach}
    </ul>
</div>
{elseif not empty($ERROR_CREATE_FOLDER)}
<div class="card border-danger border-1">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('autoinstall_error_warning_permission_folder')}</div>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$ERROR_CREATE_FOLDER item=value}
        <li class="list-group-item">
            <div class="text-break">{$value}</div>
        </li>
        {/foreach}
    </ul>
</div>
{elseif not empty($ERROR_MOVE_FOLDER)}
<div class="card border-danger border-1">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('autoinstall_error_movefile')}</div>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$ERROR_MOVE_FOLDER item=value}
        <li class="list-group-item">
            <div class="text-break">{$value}</div>
        </li>
        {/foreach}
    </ul>
</div>
{elseif not empty($ARRAY_ERROR_MINE)}
<div class="card border-warning border-1">
    <div class="card-header">
        <div class="fs-5 fw-medium mb-2">{$LANG->getModule('autoinstall_error_mimetype')}</div>
        <button type="button" class="btn btn-warning" data-toggle="upExtDismissWarning" data-link="{$DISMISS_LINK}">{$LANG->getModule('autoinstall_error_mimetype_pass')}</button>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$ARRAY_ERROR_MINE item=value}
        <li class="list-group-item">
            <div class="row g-2">
                <div class="col-7 text-break">{$value.filename}</div>
                <div class="col-5 text-break">{$value.mime}</div>
            </div>
        </li>
        {/foreach}
    </ul>
</div>
{else}
<div class="card border-success border-1">
    <div class="card-body">
        <div class="fs-5 fw-medium mb-2">{$LANG->getModule('autoinstall_unzip_success')}</div>
        {if $EXTCONFIG.extension.type eq 'module'}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=modules&amp;{$smarty.const.NV_OP_VARIABLE}=setup" data-toggle="upExtSuccessAutolink">{$LANG->getModule('autoinstall_unzip_setuppage')}</a>
        {elseif $EXTCONFIG.extension.type eq 'theme'}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=themes" data-toggle="upExtSuccessAutolink">{$LANG->getModule('autoinstall_unzip_setuppage')}</a>
        {elseif $EXTCONFIG.extension.type eq 'block'}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=themes&amp;{$smarty.const.NV_OP_VARIABLE}=blocks" data-toggle="upExtSuccessAutolink">{$LANG->getModule('autoinstall_unzip_setuppage')}</a>
        {elseif $EXTCONFIG.extension.type eq 'cronjob'}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}=settings&amp;{$smarty.const.NV_OP_VARIABLE}=cronjobs&amp;auto_add_file={$EXTCONFIG.extension.name}" data-toggle="upExtSuccessAutolink">{$LANG->getModule('autoinstall_unzip_setuppage')}</a>
        {else}
        <a href="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=manage" data-toggle="upExtSuccessAutolink">{$LANG->getModule('autoinstall_unzip_setuppage')}</a>
        {/if}
    </div>
</div>
{/if}
