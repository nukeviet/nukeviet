<div class="mb-{$CONFIG.margin_bottom}">
    <div class="row g-4">
        {* Bài nổi bật *}
        <div class="{if empty($OTHERS)}col-12{else}col-md-7{/if}">
            {if not empty($MAIN.imgsource)}
            {if $MAIN.imgratio}
            <a href="{$MAIN.link}"{if $MAIN.external_link} target="_blank"{/if} title="{$MAIN.title}" class="ratio d-block mb-3 rounded overflow-hidden" style="--bs-aspect-ratio: {$MAIN.imgratio}%;">
                <img src="{$MAIN.imgsource}" alt="{$MAIN.title}" class="object-fit-cover">
            </a>
            {else}
            <a href="{$MAIN.link}"{if $MAIN.external_link} target="_blank"{/if} title="{$MAIN.title}" class="d-block mb-3">
                <img src="{$MAIN.imgsource}" alt="{$MAIN.title}" class="w-100 rounded">
            </a>
            {/if}
            {/if}
            <div class="h4 mb-2">
                <a class="link-body-emphasis" href="{$MAIN.link}"{if $MAIN.external_link} target="_blank"{/if} title="{$MAIN.title}">{$MAIN.titleclean60}</a>
            </div>
            <div class="mb-2">{$MAIN.hometext}</div>
            <div class="text-end">
                <a href="{$MAIN.link}"{if $MAIN.external_link} target="_blank"{/if}>{$LANG->getModule('more')} <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
        {* Các bài khác *}
        {if not empty($OTHERS)}
        <div class="col-md-5">
            <ul class="list-unstyled vstack gap-3 mb-0">
                {foreach from=$OTHERS item=row}
                <li>
                    <a class="d-flex gap-2 link-body-emphasis text-decoration-none" href="{$row.link}"{if $row.external_link} target="_blank"{/if} title="{$row.title}"
                        {if not empty($CONFIG.showtooltip)}
                        data-toggle="tooltipArticle" data-hometext="{$row.hometext_clean}" data-alt="{$row.title}" data-img="{$row.imgsource}"
                        data-bs-toggle="tooltip" data-bs-placement="{$CONFIG.tooltip_position}"
                        {/if}
                    >
                        {if not empty($row.imgsource)}
                        <img src="{$row.imgsource}" alt="{$row.title}" class="rounded flex-shrink-0 fw-75 fh-50 object-fit-cover">
                        {/if}
                        <span class="fw-medium text-truncate-3">{$row.titleclean60}</span>
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
