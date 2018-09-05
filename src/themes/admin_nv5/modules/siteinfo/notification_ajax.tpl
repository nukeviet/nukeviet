<ul>
    {foreach from=$DATA key=key item=row}
    <li class="notification{if not $row['view']} notification-unread{/if}" data-id="{$row.id}">
        <a href="{$row.link}">
            <div class="image"><img src="{$row.photo}" alt="{$row.send_from}"></div>
            <div class="notification-info">
                <div class="text">{$row.title}</div>
                <span class="date" title="{$row.add_time_iso}">{$row.add_time}</span>
            </div>
        </a>
    </li>
    {/foreach}
</ul>
