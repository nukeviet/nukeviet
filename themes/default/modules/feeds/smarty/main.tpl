{* define the function *}
{function name=rsslist level=0}
<ul>
{foreach $data as $entry}
<li>
    {strip}<div class="item">
    <span>{$entry.title}</span>
    <span class="text-nowrap">
        <a class="rss" rel="nofollow" title="RSS" href="{$entry.link}"></a>
        <a class="atom" rel="nofollow" title="ATOM" href="{$entry.link}&amp;type=atom"></a>
    </span>
    </div>{/strip}
{if !empty($entry.sub)}{call name=rsslist data=$entry.sub level=$level+1}{/if}
</li>
{/foreach}
</ul>
{/function}

<div class="tree">
{call name=rsslist data=$RSS_LIST}
</div>
