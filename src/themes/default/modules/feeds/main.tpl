{if not empty($INTRO)}
<div class="mb-4 richtext-container">{$INTRO}</div>
{/if}

{function name=render_rss_tree nodes=[]}
<ul>
    {foreach from=$nodes item=node}
    <li>
        <div class="item bg-body-tertiary border rounded-2">
            <span>{$node.title}</span>
            <span class="btn-group ms-2">
                <a class="btn btn-sm btn-outline-warning" rel="nofollow" title="RSS" href="{$node.rss_url}"><i class="fa-solid fa-rss"></i><span class="d-none d-sm-inline"> RSS</span></a>
                <a class="btn btn-sm btn-outline-info" rel="nofollow" title="ATOM" href="{$node.atom_url}"><i class="fa-solid fa-atom"></i><span class="d-none d-sm-inline"> ATOM</span></a>
            </span>
        </div>
        {if not empty($node.children)}
        {call name=render_rss_tree nodes=$node.children}
        {/if}
    </li>
    {/foreach}
</ul>
{/function}

{if not empty($RSS_TREE)}
<div class="tree">
    {call name=render_rss_tree nodes=$RSS_TREE}
</div>
{/if}
