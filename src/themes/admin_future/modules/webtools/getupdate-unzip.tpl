{if not empty($NO_EXTRACT)}
<ul class="list-group">
    {foreach from=$NO_EXTRACT item=fi}
    <li class="list-group-item">
        <div class="hstack gap-2">
            <div class="text-danger">
                <i class="fa-solid fa-triangle-exclamation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('get_update_cantunzip')}" title="{$LANG->getModule('get_update_cantunzip')}" aria-label="{$LANG->getModule('get_update_cantunzip')}"></i>
            </div>
            <div class="text-break">{$fi}</div>
        </div>
    </li>
    {/foreach}
</ul>
{elseif not empty($ERROR_CREATE_FOLDER)}
<ul class="list-group">
    {foreach from=$ERROR_CREATE_FOLDER item=fi}
    <li class="list-group-item">
        <div class="hstack gap-2">
            <div class="text-danger">
                <i class="fa-solid fa-triangle-exclamation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('get_update_warning_permission_folder')}" title="{$LANG->getModule('get_update_warning_permission_folder')}" aria-label="{$LANG->getModule('get_update_warning_permission_folder')}"></i>
            </div>
            <div class="text-break">{$fi}</div>
        </div>
    </li>
    {/foreach}
</ul>
{elseif not empty($ERROR_MOVE_FOLDER)}
<ul class="list-group">
    {foreach from=$ERROR_MOVE_FOLDER item=fi}
    <li class="list-group-item">
        <div class="hstack gap-2">
            <div class="text-danger">
                <i class="fa-solid fa-triangle-exclamation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-title="{$LANG->getModule('get_update_error_movefile')}" title="{$LANG->getModule('get_update_error_movefile')}" aria-label="{$LANG->getModule('get_update_error_movefile')}"></i>
            </div>
            <div class="text-break">{$fi}</div>
        </div>
    </li>
    {/foreach}
</ul>
{else}
<div class="alert alert-success text-center" role="alert">
    <div class="mb-1 fw-medium">{$LANG->getModule('get_update_okunzip')}</div>
    <a data-toggle="autolink" href="{$smarty.const.NV_BASE_SITEURL}install/update.php">{$LANG->getModule('get_update_okunzip_link')}</a>
</div>
{/if}
