{if empty($CONFIGS.tooltip_length) || $CONFIGS.tooltip_length < 50}
{$CONFIGS.tooltip_length = 150}
{/if}

<ul class="list-none list-items">
{foreach $ITEMS as $item}
    {strip}<li>
        <a class="show clearfix" href="{$item.link}"{if $item.external_link} target="_blank"{/if}{if $CONFIGS.showtooltip} data-content="{$item.hometext|truncate:$CONFIGS.tooltip_length:"..."}" data-img="{$item.imgurl}" data-rel="tooltip" data-placement="{$CONFIGS.tooltip_position|default:"bottom"}"{else} title="{$item.title}"{/if}>
            {if !empty($item.imgurl)}
                <img src="{$item.imgurl}" alt="{$item.title}" width="{$item.width}" class="img-thumbnail pull-left margin-top-sm mr-1"/>
            {/if}
            {$item.title}
            {if $item.newday > $smarty.const.NV_CURRENTTIME}
            <span class="icon_new margin-left-sm"></span>
            {/if}
        </a>
    </li>{/strip}
{/foreach}
</ul>
