<ul class="ps-4 mb-0">
    {foreach $DATA as $row}
    <li class="mb-2">
        <a href="{$row.link}" title="{$row.title}" class="link-primary text-decoration-none">
            {$row.title_clean60}
        </a>
    </li>
    {/foreach}
</ul>
