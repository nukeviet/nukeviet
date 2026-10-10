{function writeTrees menus=[] lev=1}
{foreach from=$menus item=menu}
<li class="{if $lev eq 1}list-group-item p-0{else}border-top{/if}{if not empty($menu.css)} {$menu.css}{/if}">
    <div class="d-flex justify-content-between align-items-center gap-2 px-3">
        <a href="{$menu.link}" {$menu.target} title="{$menu.note}" class="d-flex align-items-center gap-2 py-2 overflow-hidden {if $menu.is_active}link-primary fw-medium{else}link-body-emphasis{/if}">
            {if not empty($menu.icon) and not empty($CONFIG.show_icon)}
            <img src="{$menu.icon}" alt="{$menu.title}" class="fw-20 fh-20 object-fit-contain flex-shrink-0">
            {/if}
            <span class="text-truncate">{$menu.title_trim}</span>
        </a>
        {if not empty($menu.sub)}
        <button type="button" class="collapse-caret btn btn-link link-secondary p-1 lh-1 flex-shrink-0{if not $menu.is_active} collapsed{/if}" data-bs-toggle="collapse" data-bs-target="#metismenu-{$CONFIG.bid}-{$menu.id}" aria-expanded="{if $menu.is_active}true{else}false{/if}" aria-controls="metismenu-{$CONFIG.bid}-{$menu.id}" aria-label="{$LANG->getGlobal('toggle_submenu')}">
            <i class="fa-solid fa-angle-down fa-fw"></i>
        </button>
        {/if}
    </div>
    {if not empty($menu.sub)}
    <ul class="list-unstyled mb-0 ps-3 bg-body-tertiary collapse{if $menu.is_active} show{/if}" id="metismenu-{$CONFIG.bid}-{$menu.id}">
        {writeTrees menus=$menu.sub lev=$lev+1}
    </ul>
    {/if}
</li>
{/foreach}
{/function}
<nav>
    <ul class="list-group overflow-hidden">
        {writeTrees menus=$MENUS lev=1}
    </ul>
</nav>
