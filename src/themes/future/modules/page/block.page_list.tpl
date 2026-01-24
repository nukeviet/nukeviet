<ul class="ps-4" style="list-style: circle;">
    {foreach $DATA as $row}
    <li>
        <a href="{$row.link}" title="{$row.title}">{$row.title_clean60}</a>
    </li>
    {/foreach}
</ul>
