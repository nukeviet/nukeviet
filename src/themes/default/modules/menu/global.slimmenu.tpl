{function writeTrees menus=[] parent=''}
{foreach from=$menus item=menu}
<li{if not empty($menu.css)} class="{$menu.css}"{/if}>
    <div class="d-flex align-items-center">
        <a href="{$menu.link}" {$menu.target} title="{$menu.note}" class="d-flex align-items-center gap-2 flex-grow-1 px-3 py-2 {if $menu.is_active}link-primary fw-medium{else}link-body-emphasis{/if}">
            {if not empty($menu.icon) and not empty($CONFIG.show_icon)}
            <img src="{$menu.icon}" alt="{$menu.title}" class="fw-20 fh-20 object-fit-contain flex-shrink-0">
            {/if}
            <span class="hmenu-text">{$menu.title_trim}</span>
        </a>
        {if not empty($menu.sub)}
        <button type="button" class="collapse-caret btn btn-link link-secondary ps-0 pe-2 py-1 lh-1 flex-shrink-0 collapsed" data-bs-toggle="collapse" data-bs-target="#slimmenu-{$CONFIG.bid}-{$menu.id}" aria-expanded="false" aria-controls="slimmenu-{$CONFIG.bid}-{$menu.id}" aria-label="{$LANG->getGlobal('toggle_submenu')}">
            <i class="fa-solid fa-caret-down fa-fw"></i>
        </button>
        {/if}
    </div>
    {if not empty($menu.sub)}
    <ul class="hmenu-sub list-unstyled mb-0 ps-3 ps-lg-0 collapse" id="slimmenu-{$CONFIG.bid}-{$menu.id}" data-bs-parent="#{$parent}">
        {writeTrees menus=$menu.sub parent="slimmenu-{$CONFIG.bid}-{$menu.id}"}
    </ul>
    {/if}
</li>
{/foreach}
{/function}
<nav class="hmenu bg-body-tertiary border rounded" data-toggle="hmenu">
    <div class="d-flex d-lg-none justify-content-end px-2 py-1">
        <button type="button" class="btn btn-link link-body-emphasis p-2 lh-1 collapsed" data-bs-toggle="collapse" data-bs-target="#slimmenu-{$CONFIG.bid}-wrap" aria-expanded="false" aria-controls="slimmenu-{$CONFIG.bid}-wrap" aria-label="{$LANG->getGlobal('expand')}">
            <i class="fa-solid fa-bars fa-lg"></i>
        </button>
    </div>
    <div class="collapse d-lg-block" id="slimmenu-{$CONFIG.bid}-wrap">
        <ul class="hmenu-list list-unstyled mb-0" id="slimmenu-{$CONFIG.bid}">
            {if not empty($CONFIG.show_home)}
            <li>
                <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}" title="{$LANG->getGlobal('Home')}" class="d-flex align-items-center gap-2 px-3 py-2 {if not empty($HOME)}link-primary fw-medium{else}link-body-emphasis{/if}">
                    <i class="fa-solid fa-house fa-fw"></i>
                    <span class="hmenu-text">{$LANG->getGlobal('Home')}</span>
                </a>
            </li>
            {/if}
            {writeTrees menus=$MENUS parent="slimmenu-{$CONFIG.bid}"}
        </ul>
    </div>
</nav>
