{function writeTrees menus=[] parent=''}
{foreach from=$menus item=menu}
<li{if not empty($menu.css)} class="{$menu.css}"{/if}>
    <div class="d-flex align-items-center gap-1">
        {if not empty($menu.sub)}
        <button type="button" class="tree-toggle btn btn-link link-secondary p-0 lh-1 flex-shrink-0{if not $menu.is_active} collapsed{/if}" data-bs-toggle="collapse" data-bs-target="#treeview-{$CONFIG.bid}-{$menu.id}" aria-expanded="{if $menu.is_active}true{else}false{/if}" aria-controls="treeview-{$CONFIG.bid}-{$menu.id}" aria-label="{$LANG->getGlobal('toggle_submenu')}">
            <i class="icon-collapsed fa-regular fa-square-plus fa-fw"></i>
            <i class="icon-expanded fa-regular fa-square-minus fa-fw"></i>
        </button>
        {else}
        <span class="fa-fw text-secondary flex-shrink-0"><i class="fa-solid fa-angle-right fa-xs"></i></span>
        {/if}
        <a href="{$menu.link}" {$menu.target} title="{$menu.note}" class="d-flex align-items-center gap-2 py-1 overflow-hidden {if $menu.is_active}link-primary fw-medium{else}link-body-emphasis{/if}">
            {if not empty($menu.icon) and not empty($CONFIG.show_icon)}
            <img src="{$menu.icon}" alt="{$menu.title}" class="fw-20 fh-20 object-fit-contain flex-shrink-0">
            {/if}
            <span class="text-truncate">{$menu.title_trim}</span>
        </a>
    </div>
    {if not empty($menu.sub)}
    <ul class="list-unstyled mb-0 ms-2 ps-3 border-start collapse{if $menu.is_active} show{/if}" id="treeview-{$CONFIG.bid}-{$menu.id}" data-bs-parent="#{$parent}">
        {writeTrees menus=$menu.sub parent="treeview-{$CONFIG.bid}-{$menu.id}"}
    </ul>
    {/if}
</li>
{/foreach}
{/function}
<nav>
    <ul class="treeview-menu list-unstyled mb-0" id="treeview-{$CONFIG.bid}">
        {writeTrees menus=$MENUS parent="treeview-{$CONFIG.bid}"}
    </ul>
</nav>
