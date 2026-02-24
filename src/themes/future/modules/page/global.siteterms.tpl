 <ul>
    {foreach $ROW as $item}
    <li><a href="{$item.url}" title="{$item.title}">{$item.title}</a></li>
    {/foreach}
</ul>
