<ul class="list-unstyled vstack gap-2 mb-0 block-news">
    {foreach from=$LIST item=row}
    <li>
        <article class="d-flex gap-2"
            {if not empty($CONFIG.showtooltip)}
            data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.homeimgalt}" data-img="{$row.imgsource}"
            data-bs-toggle="tooltip" data-bs-placement="{$CONFIG.tooltip_position}"
            {/if}
        >
            {if not empty($row.thumb)}
            <a class="thumbnail mt-2" href="{$row.link}"{if $row.external_link} target="_blank"{/if}>
                <span style="--nv-width: {$MCONFIG.blockwidth}px; --nv-height: {$MCONFIG.blockheight}px;">
                    <img src="{$row.thumb}" alt="{$row.homeimgalt}">
                </span>
            </a>
            {/if}
            <a class="bl-text link-body-emphasis text-truncate-3" href="{$row.link}"{if $row.external_link} target="_blank"{/if} title="{$row.title}">{$row.title_clean}</a>
        </article>
    </li>
    {/foreach}
</ul>
