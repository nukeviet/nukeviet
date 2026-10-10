{assign var="icons" value=[
    'address'  => 'fa-solid fa-map-location-dot',
    'phone'    => 'fa-solid fa-phone-volume',
    'fax'      => 'fa-solid fa-fax',
    'email'    => 'fa-solid fa-envelope',
    'skype'    => 'fa-brands fa-skype',
    'viber'    => 'fa-brands fa-viber',
    'whatsapp' => 'fa-brands fa-whatsapp',
    'zalo'     => 'fa-solid fa-comments',
]}
<div class="card">
    <div class="card-body py-2">
        <div class="h6 fw-semibold mb-2">
            <a href="{$DEPARTMENT.url}" class="text-decoration-none">{$DEPARTMENT.full_name}</a>
        </div>
        {if not empty($DEPARTMENT.note)}
        <p class="mb-2 small">{$DEPARTMENT.note}</p>
        {/if}
        {if not empty($DEPARTMENT.contacts) or not empty($DEPARTMENT.others)}
        <ul class="list-unstyled mb-0">
            {foreach from=$DEPARTMENT.contacts item=contact}
            {assign var="icon" value=$icons[$contact.type]|default:'fa-solid fa-address-book'}
            <li class="d-flex align-items-center gap-2 mb-1">
                <i class="{$icon} fa-fw flex-shrink-0"></i>
                {if not empty($contact.link)}<a href="{$contact.link}" class="text-break">{$contact.display}</a>{else}<span class="text-break">{$contact.display}</span>{/if}
            </li>
            {/foreach}
            {foreach from=$DEPARTMENT.others item=other}
            <li class="d-flex align-items-center gap-2 mb-1">
                <i class="fa-solid fa-address-book fa-fw flex-shrink-0"></i>
                <span class="text-break">{$other.name}:&nbsp;{$other.value}</span>
            </li>
            {/foreach}
        </ul>
        {/if}
    </div>
</div>
