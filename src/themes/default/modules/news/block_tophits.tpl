<ul class="list-unstyled vstack gap-2 mb-0 block-hits">
    {foreach from=$LIST item=row}
    <li>
        <article class="d-flex gap-2"
            {if not empty($CONFIG.showtooltip)}
            data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.homeimgalt}" data-img="{$row.imgsource}"
            data-bs-toggle="tooltip" data-bs-placement="{$CONFIG.tooltip_position}"
            {/if}
        >
            <span class="mt-1"><span class="hit-indicate"></span></span>
            <a class="bl-text link-body-emphasis text-truncate-3" href="{$row.link}"{if $row.external_link} target="_blank"{/if} title="{$row.title}">{$row.title}</a>
        </article>
    </li>
    {/foreach}
</ul>
