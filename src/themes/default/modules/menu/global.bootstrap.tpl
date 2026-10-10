{function writeTrees menus=[] lev=1}
{foreach from=$menus item=menu}
{assign var="mClasses" value=[]}
{if $menu.is_active} {append mClasses 'active'} {/if}
{if not empty($menu.css)} {append mClasses $menu.css} {/if}
<li data-toggle="item-lev-{$lev}"{if not empty($mClasses)} class="{$mClasses|join:' '}"{/if}>
    <div class="menu-item{if not empty($menu.sub)} has-submenu{/if}">
        <a class="item-link" href="{$menu.link}"{$menu.target} title="{$menu.note}" aria-label="{$menu.note}">
            {if not empty($menu.icon) and not empty($CONFIG.show_icon)}
            <span class="item-icon">
                <img src="{$menu.icon}" alt="{$menu.title}">
            </span>
            {/if}
            <span class="item-name">{$menu.title_trim}</span>
        </a>
        {if not empty($menu.sub)}
        <span class="item-arrow" data-toggle="subtg" aria-label="{$LANG->getGlobal('toggle_submenu')}">
            <i class="fa-solid fa-caret-down"></i>
        </span>
        {/if}
    </div>
    {if not empty($menu.sub)}
    <ul data-toggle="submenu">
        {writeTrees menus=$menu.sub lev=$lev+1}
    </ul>
    {/if}
</li>
{/foreach}
{/function}
<div class="main-nav" data-toggle="main-nav">
    <ul>
        {if $CONFIG.show_home}
        <li class="main-nav-home{if not empty($HOME)} active{/if}" data-toggle="item-lev-1">
            <div class="menu-item">
                <a class="item-link" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}" aria-label="{$LANG->getGlobal('Home')}">
                    <span class="item-icon"><i class="fa-solid fa-house"></i></span>
                    <span class="item-name">{$LANG->getGlobal('Home')}</span>
                </a>
            </div>
        </li>
        {/if}
        {writeTrees menus=$MENUS lev=1}
        <li class="nav-expanded" data-toggle="item-expanded">
            <div class="menu-item">
                <a class="item-link" href="#" aria-label="{$LANG->getGlobal('expand')}">
                    <span class="item-icon">
                        <i class="fa-solid fa-bars"></i>
                    </span>
                </a>
            </div>
        </li>
    </ul>
    <div class="nav-loader">
        <div class="loader-mask h-100"></div>
        <div class="loader-icon h-100 px-2 d-flex justify-content-center align-items-center">
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
            </div>
        </div>
    </div>
</div>
