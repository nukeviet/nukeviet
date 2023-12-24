<!-- BEGIN: main -->
{if empty($BLOCK_CONTENTS.tooltip_length) || $BLOCK_CONTENTS.tooltip_length < 50}
    {$BLOCK_CONTENTS.tooltip_length = 150}
{/if}
<div class="row mb-2">
{if !empty($BLOCK_CONTENTS.carousel)}
    <div class="col-sm-12 margin-bottom">
        <div id="topnewsCarousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators" style="bottom:0">
{$count = count($BLOCK_CONTENTS.carousel) - 1}
{for $i=0 to $count}
                <li data-target="#topnewsCarousel" data-slide-to="{$i}"{if $i == 0} class="active"{/if} aria-current="{if $i == 0}true{else}false{/if}"></li>
{/for}
            </ol>
            <div class="carousel-inner" role="listbox">
{$isActive = false}
{foreach $BLOCK_CONTENTS.carousel as $item}
                <div class="item image-3-4{if !$isActive} active{/if}">
                    {strip}<a href="{$item.link}"{if $item.external_link} target="_blank"{/if}>
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' xml:space='preserve' style='fill-rule:evenodd;clip-rule:evenodd' viewBox='0 0 1 1'%3E%3Cpath style='fill:none' d='M0 0h1v1H0z'/%3E%3C/svg%3E" alt="{$item.alt}" style="background-image:url({$item.src})">
                        <span class="carousel-caption" style="padding-bottom: 10px;"><strong>{$item.title}</strong></span>
                    </a>{/strip}
                </div>
{$isActive = true}
{/foreach}
            </div>
            <a class="left carousel-control" href="#topnewsCarousel" role="button" data-slide="prev">
                <span class="icon-prev fa fa-chevron-left" aria-hidden="true"></span>
            </a>
            <a class="right carousel-control" href="#topnewsCarousel" role="button" data-slide="next">
                <span class="icon-next fa fa-chevron-right" aria-hidden="true"></span>
            </a>
        </div>
    </div>
{/if}
{if !empty($BLOCK_CONTENTS.blocks)}
    <div class="col-sm-12 margin-bottom">
        <ul class="nav nav-pills" role="tablist">
{$isActive = false}
{foreach $BLOCK_CONTENTS.blocks as $block}
            <li{if !$isActive} class="active"{/if} role="presentation">
                <a id="tab-button{$block.bid}" style="padding: 5px 15px;" data-toggle="pill" href="#tab-content{$block.bid}" type="button" role="tab" aria-controls="tab-content{$block.bid}" aria-selected="{if !$isActive}true{else}false{/if}">{$block.title}</a>
            </li>
{$isActive = true}
{/foreach}
        </ul>
        <div class="tab-content margin-top">
{$isActive = false}
{foreach $BLOCK_CONTENTS.blocks as $block}
            <div class="tab-pane{if !$isActive} active{/if}" id="tab-content{$block.bid}" role="tabpanel" aria-labelledby="tab-button{$block.bid}">
{if !empty($block.items)}
                <ul class="lastest-news list-none list-items">
{foreach $block.items as $item}
                    {strip}<li><a class="show clearfix" href="{$item.link}"{if $item.external_link} target="_blank"{/if}{if $BLOCK_CONTENTS.showtooltip} data-content="{$item.hometext|truncate:$BLOCK_CONTENTS.tooltip_length:"..."}" data-img="{$item.homeimgfile}" data-rel="tooltip" data-placement="{$BLOCK_CONTENTS.tooltip_position|default:"bottom"}"{else} title="{$item.title}"{/if}>
                        <i class="fa fa-caret-right margin-right-sm" aria-hidden="true"></i>
                        {$item.title|truncate:60:"..."}
                        {if $item.newday > $smarty.const.NV_CURRENTTIME}
                        <span class="icon_new margin-left-sm"></span>
                    {/if}
                    </a></li>{/strip}
{/foreach}
                </ul>
{/if}
            </div>
{$isActive = true}
{/foreach}
        </div>
    </div>
{/if}
</div>
