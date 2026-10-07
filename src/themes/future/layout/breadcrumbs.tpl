{if not empty($BREADCRUMBS)}
<div class="site-breadcrumbs">
    <div class="container">
        <nav aria-label="{$LANG->getModule('breadcrumbs')}" data-toggle="breadcrumbs">
            <ol class="site-breadcrumbs-list" data-toggle="breadcrumbs-list">
                <li class="site-breadcrumbs-item site-breadcrumbs-more dropdown d-none" data-toggle="breadcrumbs-more">
                    <button type="button" class="site-breadcrumbs-more-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{$LANG->getModule('breadcrumbs_more')}" title="{$LANG->getModule('breadcrumbs_more')}"><i class="fa-solid fa-ellipsis" aria-hidden="true"></i></button>
                    <ul class="dropdown-menu" data-toggle="breadcrumbs-menu"></ul>
                </li>
                <li class="site-breadcrumbs-item" data-toggle="breadcrumbs-item">
                    <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}" title="{$LANG->getGlobal('Home')}"><i class="fa-solid fa-house" aria-hidden="true"></i><span class="site-breadcrumbs-text site-breadcrumbs-home-text">{$LANG->getGlobal('Home')}</span></a>
                </li>
                {foreach from=$BREADCRUMBS item=crumb name=crumbs}
                <li class="site-breadcrumbs-item{if $smarty.foreach.crumbs.last} is-current{/if}" data-toggle="breadcrumbs-item">
                    <a href="{$crumb.link}" title="{$crumb.title}"{if $smarty.foreach.crumbs.last} aria-current="page"{/if}><span class="site-breadcrumbs-text">{$crumb.title}</span></a>
                </li>
                {/foreach}
            </ol>
        </nav>
    </div>
</div>
{/if}
