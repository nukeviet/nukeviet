{assign var="icons" value=[
    'phone'    => 'fa-solid fa-phone-volume',
    'email'    => 'fa-solid fa-envelope',
    'skype'    => 'fa-brands fa-skype',
    'viber'    => 'fa-brands fa-viber',
    'whatsapp' => 'fa-brands fa-whatsapp',
    'zalo'     => 'fa-solid fa-comments',
]}
{assign var="dep_count" value=$DEPARTMENTS|count}
{if $dep_count > 1}
<div class="dropdown mb-2" id="supporter-dd-{$MODULE}" data-supporter-tabs="supporter-tabs-{$MODULE}">
    <button class="btn btn-primary btn-sm dropdown-toggle w-100 text-start text-truncate" type="button" data-bs-toggle="dropdown" aria-expanded="false">{$DEPARTMENTS[0].full_name}</button>
    <ul class="dropdown-menu w-100">
        {foreach from=$DEPARTMENTS item=dep}
        <li><a class="dropdown-item{if $dep@first} active{/if}" href="#" data-dep="dep-{$dep.id}">{$dep.full_name}</a></li>
        {/foreach}
    </ul>
</div>
{/if}
<div class="tab-content"{if $dep_count > 1} id="supporter-tabs-{$MODULE}"{/if}>
    {foreach from=$DEPARTMENTS item=dep}
    <div class="tab-pane{if $dep@first} active{/if}" id="dep-{$dep.id}">
        <ul class="list-unstyled mb-0">
            {foreach from=$dep.supporters item=sp}
            <li>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <img src="{$sp.image}" class="rounded-circle flex-shrink-0 object-fit-cover fw-40 fh-40" alt="{$sp.full_name}">
                    <div class="fw-semibold">{$sp.full_name}</div>
                </div>
                {if not empty($sp.contacts) or not empty($sp.others)}
                <ul class="list-unstyled mb-0">
                    {foreach from=$sp.contacts item=contact}
                    {assign var="icon" value=$icons[$contact.type]|default:'fa-solid fa-address-book'}
                    <li class="d-flex align-items-center gap-2 mb-1">
                        <i class="{$icon} fa-fw flex-shrink-0"></i>
                        {if not empty($contact.link)}<a href="{$contact.link}" class="text-break">{$contact.display}</a>{else}<span class="text-break">{$contact.display}</span>{/if}
                    </li>
                    {/foreach}
                    {foreach from=$sp.others item=other}
                    <li class="d-flex align-items-center gap-2 mb-1">
                        <i class="fa-solid fa-address-book fa-fw flex-shrink-0"></i>
                        <span class="text-break">{$other.name}:&nbsp;{if not empty($other.link)}<a href="{$other.link}">{$other.value}</a>{else}{$other.value}{/if}</span>
                    </li>
                    {/foreach}
                </ul>
                {/if}
                {if not $sp@last}<hr class="my-2" />{/if}
            </li>
            {/foreach}
        </ul>
    </div>
    {/foreach}
</div>
