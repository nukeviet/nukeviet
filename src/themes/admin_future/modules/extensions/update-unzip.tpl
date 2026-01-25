{if not empty($NO_EXTRACT)}
<div class="card border-danger border-1">
    <div class="card-header">
        <div class="fs-5 fw-medium">{$LANG->getModule('get_update_cantunzip')}</div>
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
        <div class="fs-5 fw-medium">{$LANG->getModule('get_update_warning_permission_folder')}</div>
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
        <div class="fs-5 fw-medium">{$LANG->getModule('get_update_error_movefile')}</div>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$ERROR_MOVE_FOLDER item=value}
        <li class="list-group-item">
            <div class="text-break">{$value}</div>
        </li>
        {/foreach}
    </ul>
</div>
{else}
<div class="card border-success border-1">
    <div class="card-body">
        <div class="fs-5 fw-medium mb-2">{$LANG->getModule('get_update_okunzip')}</div>
        <a href="{$smarty.const.NV_BASE_SITEURL}install/update.php" data-toggle="upExtSuccessAutolink">{$LANG->getModule('get_update_okunzip_link')}</a>
    </div>
</div>
{/if}
