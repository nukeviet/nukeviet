<link href="{$smarty.const.ASSETS_STATIC_URL}/js/perfect-scrollbar/style{$smarty.const.AUTO_MINIFIED}.css" rel="stylesheet">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/perfect-scrollbar/min.js" charset="utf-8"></script>
<script src="{$smarty.const.NV_STATIC_URL}themes/{$JS_DIR}/js/block.inform.js"></script>
{assign var="icons" value=[
    'all' => '<i class="fa-solid fa-bullseye" data-icon="fa-bullseye"></i>',
    'unviewed' => '<i class="fa-solid fa-circle" data-icon="fa-circle"></i>',
    'favorite' => '<i class="fa-solid fa-star" data-icon="fa-star"></i>'
]}
<div class="dropdown site-user-inform" id="inform-notification" data-refresh-time="{$REFRESH_TIME}" data-url="{$INFORM_MODULE_URL}" data-checkinform-url="{$CHECK_INFORM_URL}" data-userid="{$USERID}" data-usergroups="{$USERGROUPS}" data-csrf="{$CSRF}">
    <a class="inform-toggle" href="#" data-bs-toggle="dropdown" title="{$LANG->getGlobal('inform_notifications')}" aria-label="{$LANG->getGlobal('inform_notifications')}" data-bs-auto-close="outside" data-bs-offset="40,0" aria-expanded="false">
        <i data-toggle="loader" class="fa-solid fa-bell fa-lg fa-fw text-center" data-icon="fa-bell"></i>
        <span class="counter badge rounded-pill bg-danger d-none" data-toggle="unreadBadge">
            <span data-toggle="unreadCounter"></span>
            <span class="visually-hidden">{$LANG->getGlobal('inform_unread')}</span>
        </span>
    </a>
    <ul class="dropdown-menu bg-body-tertiary px-0 dropdown-menu-end inform-box">
        <div class="inform-header px-2 border-bottom d-flex justify-content-between align-items-center">
            <div class="filter-box d-flex gap-2">
                <input type="hidden" name="aj_filter" value="{$FILTER_DEFAULT}">
                {foreach $FILTERS as $key => $vals}
                <button type="button" class="btn btn-sm btn-outline-secondary {$key}{if $vals.is_active} active{/if}" data-toggle="changeFilter" data-filter="{$key}">{$icons[$key]} {$vals.name}</button>
                {/foreach}
            </div>
            <button type="button" data-toggle="informClose" class="btn-close" aria-label="{$LANG->getGlobal('close')}" title="{$LANG->getGlobal('close')}"></button>
        </div>
        <div class="inform-content bg-body"></div>
        <div class="inform-footer px-2 border-top d-flex gap-2 justify-content-center align-items-center">
            <a class="link-secondary rounded-1" href="#" data-toggle="refreshList"><i class="fa-solid fa-arrows-rotate" data-icon="fa-arrows-rotate"></i> {$LANG->getGlobal('refresh')}</a>
            <a class="link-secondary rounded-1" href="{$INFORM_VIEWALL_URL}"><i class="fa-solid fa-up-right-and-down-left-from-center" data-icon="fa-up-right-and-down-left-from-center"></i> {$LANG->getGlobal('viewall')}</a>
        </div>
    </ul>
</div>
