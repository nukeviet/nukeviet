<ul style="list-style: circle; padding-left: 20px">
    {if not empty($DATA)}
    {foreach $DATA as $row}
    <li>
        <a href="{$row.link}" title="{$row.title}">{$row.title_clean60}</a>
    </li>
    {/foreach}
    {/if}
</ul>
