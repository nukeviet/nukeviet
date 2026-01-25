{if empty($DATA)}
{if $LAST_ID lte 0}
<div class="pt-3 px-2 text-center">
    {$LANG->getModule('notification_empty')}
</div>
{/if}
{else}
<ul class="list-unstyled">
    {foreach from=$DATA item=row}
    <li class="notification border-bottom position-relative{if not $row.view} notification-unread{/if}" data-id="{$row.id}">
        <div class="tools d-flex align-items-center">
            <a class="noti-toggle rounded-circle text-center" href="#" title="{if $row.view}{$LANG->getModule('notification_make_unread')}{else}{$LANG->getModule('notification_make_read')}{/if}" data-msg-read="{$LANG->getModule('notification_make_read')}" data-msg-unread="{$LANG->getModule('notification_make_unread')}">
                {if $row.view}
                <i class="fa-solid fa-eye-slash"></i>
                {else}
                <i class="fa-solid fa-eye"></i>
                {/if}
            </a>
            <a class="noti-delete rounded-circle ms-2 text-center" href="#" title="{$LANG->getGlobal('delete')}">
                <i class="fa-solid fa-trash text-danger"></i>
            </a>
        </div>
        <a class="noti-item d-flex p-3 fw-medium" href="{$row.link}">
            <div class="image me-2 rounded-circle overflow-hidden flex-shrink-0">
                {if $row.send_from_id gt 0}
                {if not empty($row.photo)}
                <img class="d-block" src="{$row.photo}" alt="{$row.send_from}">
                {else}
                <span class="d-block position-relative w-100 h-100"><i class="fa-solid fa-circle-user ico-vc"></i></span>
                {/if}
                {else}
                <span class="d-block position-relative w-100 h-100"><i class="fa-solid fa-gear ico-vc"></i></span>
                {/if}
            </div>
            <div class="notification-info">
                <div class="text lh-sm"><span class="user-name">{$row.send_from}</span> {$row.title}</div>
                <div class="date text-uppercase mt-1 lh-1" title="{$row.add_time_iso}">{$row.add_time}</div>
            </div>
        </a>
    </li>
    {/foreach}
</ul>
{/if}
