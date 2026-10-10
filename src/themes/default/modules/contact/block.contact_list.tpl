{assign var="icons" value=[
    'phone'    => 'fa-solid fa-phone-volume',
    'email'    => 'fa-solid fa-envelope',
    'skype'    => 'fa-brands fa-skype',
    'viber'    => 'fa-brands fa-viber',
    'whatsapp' => 'fa-brands fa-whatsapp',
    'zalo'     => 'fa-solid fa-comments',
]}
{foreach from=$DEPARTMENTS item=dept}
<div class="card{if not $dept@last} mb-3{/if}">
    {if not empty($dept.image)}
    <img src="{$dept.image}" class="card-img-top object-fit-cover maxh-140" alt="{$dept.full_name}">
    {/if}
    <div class="card-body py-2">
        <div class="h6 card-title text-center mb-2">{$dept.full_name}</div>
        {if not empty($dept.contacts) or not empty($dept.others)}
        <ul class="list-unstyled mb-0">
            {foreach from=$dept.contacts item=contact}
            {assign var="icon" value=$icons[$contact.type]|default:'fa-solid fa-address-book'}
            <li class="d-flex align-items-center gap-2 mb-1">
                <i class="{$icon} fa-fw flex-shrink-0"></i>
                {if not empty($contact.link)}<a href="{$contact.link}" class="text-break">{$contact.display}</a>{else}<span class="text-break">{$contact.display}</span>{/if}
            </li>
            {/foreach}
            {foreach from=$dept.others item=other}
            <li class="d-flex align-items-center gap-2 mb-1">
                <i class="fa-solid fa-address-book fa-fw flex-shrink-0"></i>
                <span class="text-break">{$other.name}:&nbsp;{$other.value}</span>
            </li>
            {/foreach}
        </ul>
        {/if}
    </div>
</div>
{/foreach}
