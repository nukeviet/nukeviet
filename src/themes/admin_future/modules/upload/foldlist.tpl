{function writeTrees trees=[]}
{foreach from=$trees item=tree}
<li{if $tree.active} class="active"{/if}>
    <div class="tree-item">
        {if not empty($tree.sub)}
        <a class="tree-collapse" data-bs-toggle="collapse" data-bs-target="#fms-tree-{$tree.uuid}" role="button" aria-expanded="{$tree.open ? 'true' : 'false'}" aria-controls="fms-tree-{$tree.uuid}">
            <i class="tree-icon fa-fw pe-none fa-solid {($tree.open and not empty($tree.sub)) ? 'fa-folder-open' : 'fa-folder-plus'}" data-toggle="tree-icon" data-icon="fa-folder-plus"></i>
        </a>
        {else}
        <span class="tree-collapse"><i class="tree-icon fa-fw pe-none fa-solid {($tree.open and not empty($tree.sub)) ? 'fa-folder-open' : 'fa-folder'}" data-toggle="tree-icon" data-icon="fa-folder"></i></span>
        {/if}
        <a href="#" class="tree-name">
            <span class="pe-none">{$tree.title}</span>
            {if not empty($tree.size)}<span class="pe-none tree-size">({$tree.size})</span>{/if}
        </a>
    </div>
    {if not empty($tree.sub)}
    <div class="sub-tree collapse{$tree.open ? ' show' : ''}" id="fms-tree-{$tree.uuid}" data-toggle="collapseTree">
        <ul>
            {writeTrees trees=$tree.sub}
        </ul>
    </div>
    {/if}
</li>
{/foreach}
{/function}
<ul>
    {writeTrees trees=$TREES}
</ul>
