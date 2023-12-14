<ul class="nv-block-rss">
{foreach $RSS as $item}
    <li>
        <a class="list-title"{if $item.target} target="_blank"{/if} title="{$item.title}" href="{$item.link}">{$item.text}</a>
{if !empty($item.pubDate)}
        <div class="text-muted small">
            <em class="fa fa-calendar"></em>&nbsp;{$item.pubDate}
        </div>
{/if}
{if !empty($item.description)}
{if !empty($item.avatar)}
            <a href="{$item.link}"{if $item.target} target="_blank"{/if} title="{$item.title}"><img src="{$item.avatar}" alt="" /></a>
{/if}
            {$item.description}
{/if}
    </li>
{/foreach}
</ul>
