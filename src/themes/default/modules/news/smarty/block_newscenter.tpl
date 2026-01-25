{if empty($CONFIGS.tooltip_length) || $CONFIGS.tooltip_length < 50}
{$CONFIGS.tooltip_length = 150}
{/if}

<div id="hot-news">
    <div class="panel panel-default news_column">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-14 margin-bottom-lg">
                    <div class="margin-bottom text-center"><a href="{$MAIN_ROW.link}" title="{$MAIN_ROW.title}"{if $MAIN_ROW.external_link} target="_blank"{/if}><img src="{$MAIN_ROW.imgsource}" alt="{$MAIN_ROW.title}" width="{$MAIN_ROW.width}" class="img-thumbnail"/></a></div>
                    <div class="h2 margin-bottom-sm"><a href="{$MAIN_ROW.link}" title="{$MAIN_ROW.title}"{if $MAIN_ROW.external_link} target="_blank"{/if}><strong>{if $CONFIGS.length_othertitle > 0}{$MAIN_ROW.title|truncate:$CONFIGS.length_othertitle:"..."}{else}{$MAIN_ROW.title}{/if}</strong></a></div>
                    {$MAIN_ROW.hometext|strip_tags}
                    <p class="text-right"><a href="{$MAIN_ROW.link}"><em class="fa fa-sign-out"></em>{$LANG->getModule('more')}</a></p>
                </div>
                <div class="hot-news-others col-md-10 margin-bottom-lg">
{if !empty($OTHER_ROWS)}
                    <ul class="column-margin-left list-none">
{foreach $OTHER_ROWS as $other_row}
                        <li class="icon_list">
                            <a class="show black h4 clearfix" href="{$other_row.link}"{if $other_row.external_link} target="_blank"{/if}{if $CONFIGS.showtooltip} data-placement="{$CONFIGS.tooltip_position|default:"bottom"}" data-content="{$other_row.hometext|truncate:$CONFIGS.tooltip_length:"..."}" data-img="{$other_row.imgsource}" data-rel="tooltip"{else} title="{$other_row.title}"{/if}><img src="{$other_row.imgsource}" alt="{$other_row.title}" class="img-thumbnail pull-right margin-left-sm" style="width:65px;"/><span>{if $CONFIGS.length_othertitle > 0}{$other_row.title|truncate:$CONFIGS.length_othertitle:"..."}{else}{$other_row.title}{/if}</span></a>
                        </li>
{/foreach}
                    </ul>
{/if}
                </div>
            </div>
        </div>
    </div>
</div>
