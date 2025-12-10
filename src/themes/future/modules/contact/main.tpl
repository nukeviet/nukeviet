<div class="mb-3 d-flex align-items-center gap-2">
  <h1 class="mb-0"><strong>{$PAGE_TITLE}</strong></h1>
</div>

{if not empty($DATA.bodytext)}
<div class="alert alert-primary mb-4">{$DATA.bodytext}</div>
{/if}

<div class="row g-4">
    <div class="col-md-5">
        {foreach from=$DEPARTMENTS item=DEP}
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3 me-1">
                    <i class="fa-solid fa-users text-primary" aria-hidden="true"></i>
                </div>
                {if !$IS_SPECIFIC}
                <h2 class="card-title flex-grow-1 mb-0">
                    <a href="{$DEP.url}" class="text-decoration-none text-body fs-5">{$DEP.full_name}</a>
                </h2>
                {else}
                <div class="card-title flex-grow-1 mb-0 fs-5">{$LANG->getModule('contact_info')}</div>
                {/if}
            </div>
            {if $DEP.image ne ''}
            <img src="{$DEP.image}" class="card-img-top" alt="{$DEP.full_name}">
            {/if}
            <ul class="list-group list-group-flush">
                {if $DEP.note ne ''}
                <li class="list-group-item">{$DEP.note}</li>
                {/if}
                {if $DEP.address ne ''}
                <li class="list-group-item">
                    <i class="fa-solid fa-location-dot fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('address')}:
                    <span>{$DEP.address}</span>
                </li>
                {/if}
                {foreach from=$DEP.cd item=CD}
                <li class="list-group-item">
                    {if $CD.type eq 'phone'}
                    <i class="fa-solid fa-phone fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('phone')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'fax'}
                    <i class="fa-solid fa-fax fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('fax')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'email'}
                    <i class="fa-solid fa-envelope fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('email')}:
                    {if is_array($CD.value)}
                    <span>
                        {foreach from=$CD.value item=EM name=em}
                        {$EM}{if !$smarty.foreach.em.last}, {/if}
                        {/foreach}
                    </span>
                    {else}
                    <span>{$CD.value}</span>
                    {/if}
                    {elseif $CD.type eq 'skype'}
                    <i class="fa-brands fa-skype fa-fw text-center me-2 text-primary"></i>Skype:
                    <span>
                        {foreach from=$CD.value item=SK name=sk}
                        {$SK}{if !$smarty.foreach.sk.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'viber'}
                    <i class="fa-brands fa-viber fa-fw text-center me-2 text-primary"></i>Viber:
                    <span>
                        {foreach from=$CD.value item=VB name=vb}
                        {$VB}{if !$smarty.foreach.vb.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'whatsapp'}
                    <i class="fa-brands fa-whatsapp fa-fw text-center me-2 text-primary"></i>WhatsApp:
                    <span>
                        {foreach from=$CD.value item=WA name=wa}
                        {$WA}{if !$smarty.foreach.wa.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'zalo'}
                    <i class="icon-zalo me-2 text-primary"></i>Zalo:
                    <span>
                        {foreach from=$CD.value item=ZA name=za}
                        {$ZA}{if !$smarty.foreach.za.last}, {/if}
                        {/foreach}
                    </span>
                    {else}
                    <i class="fa-solid fa-circle-info fa-fw text-center me-2 text-primary"></i>{$CD.type}:
                    {if $CD.value.is_url}
                    <a href="{$CD.value.content}">{$CD.value.content}</a>
                    {else}
                    <span>{$CD.value.content}</span>
                    {/if}
                    {/if}
                </li>
                {/foreach}
            </ul>
        </div>
        {/foreach}
    </div>

    <div class="col-md-7">
        {if !empty($SUPPORTERS)}
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-title mb-0 fs-5 p-2 fw-medium">{$LANG->getModule('supporters')}</div>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$SUPPORTERS item=SUP}
                <li class="list-group-item">
                    <div class="d-flex align-items-start gap-3">
                        <img src="{$SUP.image}" alt="" class="rounded-circle" style="width:48px;height:48px;object-fit:cover">
                        <div class="flex-grow-1">
                            <div class="fw-medium mb-1">{$SUP.full_name}</div>
                            {foreach from=$SUP.cd item=CD}
                            <div class="mb-1">
                                {if $CD.type eq 'phone'}
                                <i class="fa-solid fa-phone fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('phone')}: <span>{$CD.value}</span>
                                {elseif $CD.type eq 'fax'}
                                <i class="fa-solid fa-fax fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('fax')}: <span>{$CD.value}</span>
                                {elseif $CD.type eq 'email'}
                                <i class="fa-solid fa-envelope fa-fw text-center me-2 text-primary"></i>{$LANG->getModule('email')}: <span>{$CD.value}</span>
                                {elseif $CD.type eq 'skype'}
                                <i class="fa-brands fa-skype fa-fw text-center me-2 text-primary"></i>Skype: <span>{foreach from=$CD.value item=SK name=sk}{$SK}{if !$smarty.foreach.sk.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'viber'}
                                <i class="fa-brands fa-viber fa-fw text-center me-2 text-primary"></i>Viber: <span>{foreach from=$CD.value item=VB name=vb}{$VB}{if !$smarty.foreach.vb.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'whatsapp'}
                                <i class="fa-brands fa-whatsapp fa-fw text-center me-2 text-primary"></i>WhatsApp: <span>{foreach from=$CD.value item=WA name=wa}{$WA}{if !$smarty.foreach.wa.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'zalo'}
                                <i class="icon-zalo me-2 text-primary"></i>Zalo: <span>{foreach from=$CD.value item=ZA name=za}{$ZA}{if !$smarty.foreach.za.last}, {/if}{/foreach}</span>
                                {else}
                                <i class="fa-solid fa-circle-info fa-fw text-center me-2 text-primary"></i>{$CD.type}: {if $CD.value.is_url}<a href="{$CD.value.content}">{$CD.value.content}</a>{else}<span>{$CD.value.content}</span>{/if}
                                {/if}
                            </div>
                            {/foreach}
                        </div>
                    </div>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-title mb-0 fs-5 p-2 fw-medium">{$LANG->getModule('feedback_form')|default:$LANG->getGlobal('feedback')}</div>
            </div>
            <div class="card-body">
                <p class="mb-4">{$LANG->getModule('feedback_form_note')}</p>
                {$FORM}
            </div>
        </div>
    </div>
</div>
