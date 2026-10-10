{function writeTrees menus=[]}
{foreach from=$menus item=menu}
<li>
    <div class="menu-item d-flex justify-content-between align-items-center gap-2 border-bottom">
        <a href="{$menu.link}" title="{$menu.title}" class="{if $menu.active}link-primary{else}link-body-emphasis{/if} py-1 text-truncate">{$menu.title0}</a>
        {if not empty($menu.sub)}
        <a class="d-flex align-items-center justify-content-center p-1 link-secondary{if not $menu.open} collapsed{/if}" data-bs-toggle="collapse" href="#collapse-{$CONFIG.bid}-{$menu.catid}" role="button" aria-expanded="{$menu.open ? 'true' : 'false'}" aria-controls="collapse-{$CONFIG.bid}-{$menu.catid}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
            </svg>
        </a>
        {/if}
    </div>
    {if not empty($menu.sub)}
    <ul class="list-unstyled mb-0 collapse{if $menu.open} show{/if}" id="collapse-{$CONFIG.bid}-{$menu.catid}">
        {writeTrees menus=$menu.sub}
    </ul>
    {/if}
</li>
{/foreach}
{/function}
<aside>
    <nav>
        <ul id="news-cat-{$CONFIG.bid}" class="news-menu-cats list-unstyled mb-0">
            {writeTrees menus=$MENULIST}
        </ul>
    </nav>
</aside>
