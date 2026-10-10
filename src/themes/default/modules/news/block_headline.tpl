<div class="article-headline">
    <div class="item-big{if not isset($DATA[3])} item-big-only{/if}">
        <div class="item">
            {if isset($DATA[0])}
            {assign var="row" value=$DATA[0]}
            <div class="item-inner">
                <div class="item-content">
                    {if not empty($row.imgsource)}
                    <img class="item-img" alt="{$row.homeimgalt}" src="{$row.imgsource}">
                    {/if}
                    <div class="item-texts">
                        <div>
                            <a class="cat-link" href="{$row.cat_link}">{$row.cat_name}</a>
                        </div>
                        <a class="article-link fw-medium link-light" href="{$row.link}"{if $row.external_link} target="_blank"{/if} title="{$row.title}">{$row.title}</a>
                    </div>
                </div>
            </div>
            {/if}
        </div>
    </div>
    <div class="item-small">
        {if isset($DATA[1])}
        {for $i=1 to count($DATA)-1}
        {assign var="row" value=$DATA[$i]}
        <div class="item">
            <div class="item-inner">
                <div class="item-content">
                    {if not empty($row.imgsource)}
                    <img class="item-img" alt="{$row.homeimgalt}" src="{$row.imgsource}">
                    {/if}
                    <div class="item-texts">
                        <div>
                            <a class="cat-link" href="{$row.cat_link}">{$row.cat_name}</a>
                        </div>
                        <a class="article-link fw-medium link-light" href="{$row.link}"{if $row.external_link} target="_blank"{/if} title="{$row.title}">{$row.title}</a>
                    </div>
                </div>
            </div>
        </div>
        {/for}
        {/if}
    </div>
</div>
