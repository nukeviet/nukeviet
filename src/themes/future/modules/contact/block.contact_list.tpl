{foreach from=$DEPARTMENTS item=department name=list}
<div class="card{if !$smarty.foreach.list.last} mb-3{/if}">
    {if $department.image}
    <img src="{$department.image}" class="card-img-top rounded-top" alt="{$department.full_name}">
    {/if}
    <div class="card-body">
        <div class="card-title text-center">
            <a href="{$department.url}" class="link-body-emphasis fw-medium fs-5">{$department.full_name}</a>
        </div>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$department.cd item=cd}
        <li class="list-group-item">
            {if $cd.type eq 'phone'}
            <i class="fa-solid fa-phone fa-fw text-center me-2 text-primary"></i>{$LANG->getGlobal('phonenumber')}: <span>{$cd.value}</span>
            {elseif $cd.type eq 'fax'}
            <i class="fa-solid fa-fax fa-fw text-center me-2 text-primary"></i>Fax: <span>{$cd.value}</span>
            {elseif $cd.type eq 'email'}
            <i class="fa-solid fa-envelope fa-fw text-center me-2 text-primary"></i>{$LANG->getGlobal('email')}:
            <span>{foreach from=$cd.value item=EM name=em}{$EM}{if !$smarty.foreach.em.last}, {/if}{/foreach}</span>
            {elseif $cd.type eq 'skype'}
            <i class="fa-brands fa-skype fa-fw text-center me-2 text-primary"></i>Skype:
            <span>{foreach from=$cd.value item=SK name=sk}{$SK}{if !$smarty.foreach.sk.last}, {/if}{/foreach}</span>
            {elseif $cd.type eq 'viber'}
            <i class="fa-brands fa-viber fa-fw text-center me-2 text-primary"></i>Viber:
            <span>{foreach from=$cd.value item=VB name=vb}{$VB}{if !$smarty.foreach.vb.last}, {/if}{/foreach}</span>
            {elseif $cd.type eq 'whatsapp'}
            <i class="fa-brands fa-whatsapp fa-fw text-center me-2 text-primary"></i>WhatsApp:
            <span>{foreach from=$cd.value item=WA name=wa}{$WA}{if !$smarty.foreach.wa.last}, {/if}{/foreach}</span>
            {elseif $cd.type eq 'zalo'}
            <i class="icon-zalo-contact me-2 text-primary"></i>Zalo:
            <span>{foreach from=$cd.value item=ZA name=za}{$ZA}{if !$smarty.foreach.za.last}, {/if}{/foreach}</span>
            {else}
            <i class="fa-solid fa-circle-info fa-fw text-center me-2 text-primary"></i>{$cd.type}:
            {if $cd.value.is_url}
            <a href="{$cd.value.content}">{$cd.value.content}</a>
            {else}
            <span>{$cd.value.content}</span>
            {/if}
            {/if}
        </li>
        {/foreach}
    </ul>
</div>
{/foreach}
