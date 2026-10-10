<ul class="list-unstyled mb-0 list-rss-feeds">
    {foreach from=$ITEMS item=item}
    <li class="py-2{if not $item@last} border-bottom{/if} clearfix">
        {if not empty($item.link)}
        <a class="text-break fw-medium" href="{$item.link}"{if $ISTARGET} target="_blank" rel="noopener noreferrer"{/if} title="{$item.title}">{$item.text}</a>
        {else}
        <span class="text-break fw-medium">{$item.text}</span>
        {/if}
        {if not empty($item.pubDate)}
        <div class="text-muted small mt-1">
            <i class="fa-solid fa-calendar-days fa-fw"></i> {$item.pubDate}
        </div>
        {/if}
        {if not empty($item.description)}
        <div class="mt-1 small item-description overflow-hidden">
            {$item.description}
        </div>
        {/if}
    </li>
    {/foreach}
</ul>
