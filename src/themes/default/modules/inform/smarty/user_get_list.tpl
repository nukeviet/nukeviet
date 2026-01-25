{if !empty($ITEMS)}
<ul class="list-unstyled items" data-url="{$PAGE_URL}">
{foreach $ITEMS as $item}
    <li class="item hidden-{$item.is_hidden} shown-{$item.shown_time} viewed-{$item.is_viewed} favorite-{$item.is_favorite}" data-id="{$item.id}">
        {strip}<span class="avatar">
{if $item.sender_avatar == 'group'}
            <em class="fa fa-users"></em>
{else if $item.sender_avatar == 'admin'}
            <em class="fa fa-star"></em>
{else}
            <em class="fa fa-windows"></em>
{/if}     
        </span>{/strip}
        <div class="title">{$item.title}</div>
        <div class="message">
            {$item.message.0}
            {if !empty($item.message.1)}<span class="more">... <u data-toggle="more">{$LANG->getModule('view_more')}</u></span><span class="morecontent" style="display: none">{$item.message.1}</span>{/if}
            {if !empty($item.link)}<div class="details"><a href="{$item.link}"><i class="fa fa-caret-right"></i> {$LANG->getModule('details')}</a></div>{/if}
        </div>
        <div class="foot">
            <span>{$item.add_time}</span>
            <div>
                {strip}{if !$item.is_hidden}
                    {if empty($item.viewed_time)}<button type="button" class="btn btn-viewed" data-toggle="informNotifySetStatus" data-status="viewed" title="{$LANG->getModule('mark_as_viewed')}"><i class="fa fa-bell-o"></i></button>
                    {else}<button type="button" class="btn btn-viewed" data-toggle="informNotifySetStatus" data-status="unviewed" title="{$LANG->getModule('mark_as_unviewed')}"><i class="fa fa-bell-o"></i></button>{/if}
                    {if empty($item.favorite_time)}<button type="button" class="btn btn-favorite" data-toggle="informNotifySetStatus" data-status="favorite" title="{$LANG->getModule('mark_as_favorite')}"><i class="fa fa-heart-o"></i></button>
                    {else}<button type="button" class="btn btn-favorite" data-toggle="informNotifySetStatus" data-status="unfavorite" title="{$LANG->getModule('mark_as_unfavorite')}"><i class="fa fa-heart-o"></i></button>{/if}
                    <button type="button" class="btn" data-toggle="informNotifySetStatus" data-status="hidden" title="{$LANG->getModule('hidden')}"><i class="fa fa-trash-o"></i></button>
                {else}
                    <button type="button" class="btn" data-toggle="informNotifySetStatus" data-status="unhidden" title="{$LANG->getModule('show')}"><i class="fa fa-eye"></i></button>
                {/if}{/strip}
            </div>
        </div>
    </li>
{/foreach}
</ul>
{else}
<div class="notify-empty">
    <p><i class="fa fa-bell-slash-o fa-2x"></i></p>{$LANG->getModule('no_notifications')}
</div>
{/if}
{if !empty($GENERATE_PAGE)}
<div class="panel-footer text-center">
    {$GENERATE_PAGE}
</div>
{/if}
