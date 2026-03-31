{if !empty($ITEMS)}
<ul class="list-group list-group-flush inform-items items" data-url="{$PAGE_URL}">
    {foreach $ITEMS as $item}
    <li class="list-group-item item hidden-{$item.is_hidden} shown-{$item.shown_time} viewed-{$item.is_viewed} favorite-{$item.is_favorite}" data-id="{$item.id}">
        <span class="avatar">
            {if $item.sender_avatar == 'group'}
            <i class="fa-solid fa-user-tie"></i>
            {else if $item.sender_avatar == 'admin'}
            <i class="fa-solid fa-star"></i>
            {else}
            <i class="fa-solid fa-globe"></i>
            {/if}
        </span>
        <div class="fw-medium">{$item.title}</div>
        <div class="message mb-2">
            {$item.message.0}
            {if !empty($item.message.1)}<span class="more">... <u data-toggle="more" role="button">{$LANG->getModule('view_more')}</u></span><span class="morecontent" style="display: none">{$item.message.1}</span>{/if}
            {if !empty($item.link)}<div class="details"><a href="{$item.link}"><i class="fa fa-caret-right"></i> {$LANG->getModule('details')}</a></div>{/if}
        </div>
        <div class="foot d-flex justify-content-between align-items-center">
            <small class="text-muted">{$item.add_time}</small>
            <div>
                {if !$item.is_hidden}
                {if empty($item.viewed_time)}
                <button type="button" class="btn btn-sm btn-outline-secondary active rounded-circle btn-viewed" data-toggle="informNotifySetStatus" data-status="viewed" title="{$LANG->getModule('mark_as_viewed')}" aria-label="{$LANG->getModule('mark_as_viewed')}"><i class="fa-regular fa-bell"></i></button>
                {else}
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle btn-viewed" data-toggle="informNotifySetStatus" data-status="unviewed" title="{$LANG->getModule('mark_as_unviewed')}" aria-label="{$LANG->getModule('mark_as_unviewed')}"><i class="fa-regular fa-bell"></i></button>
                {/if}
                {if empty($item.favorite_time)}
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle btn-favorite" data-toggle="informNotifySetStatus" data-status="favorite" title="{$LANG->getModule('mark_as_favorite')}" aria-label="{$LANG->getModule('mark_as_favorite')}"><i class="fa-regular fa-heart"></i></button>
                {else}
                <button type="button" class="btn btn-sm btn-outline-secondary active rounded-circle btn-favorite" data-toggle="informNotifySetStatus" data-status="unfavorite" title="{$LANG->getModule('mark_as_unfavorite')}" aria-label="{$LANG->getModule('mark_as_unfavorite')}"><i class="fa-regular fa-heart"></i></button>
                {/if}
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" data-toggle="informNotifySetStatus" data-status="hidden" title="{$LANG->getModule('hidden')}" aria-label="{$LANG->getModule('hidden')}"><i class="fa-regular fa-trash-can"></i></button>
                {else}
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" data-toggle="informNotifySetStatus" data-status="unhidden" title="{$LANG->getModule('show')}" aria-label="{$LANG->getModule('show')}"><i class="fa-regular fa-eye"></i></button>
                {/if}
            </div>
        </div>
    </li>
{/foreach}
</ul>
{else}
<div class="notify-empty p-3 text-center">
    <p><i class="fa-solid fa-bell-slash fa-2x"></i></p>{$LANG->getModule('no_notifications')}
</div>
{/if}
{if !empty($GENERATE_PAGE)}
<div class="panel-footer text-center">
    {$GENERATE_PAGE}
</div>
{/if}
