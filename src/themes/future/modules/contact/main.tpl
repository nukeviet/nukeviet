<div class="mb-3 d-flex align-items-center gap-2">
  <div class="bg-primary p-2 rounded-3 d-inline-flex">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-text text-white" aria-hidden="true">
      <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"></path>
      <path d="M7 11h10"></path>
      <path d="M7 15h6"></path>
      <path d="M7 7h8"></path>
    </svg>
  </div>
  <h1 class="mb-0"><strong>{$PAGE_TITLE}</strong></h1>
</div>

{if $BODYTEXT ne ''}
<div class="alert alert-primary mb-4">{$BODYTEXT}</div>
{/if}

<div class="row g-3">
    <div class="col-md-5">
        {foreach from=$DEPARTMENTS item=DEP}
        <div class="card mb-3 shadow-sm border-0 rounded-4">
            <div class="card-header d-flex align-items-center">
                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3 me-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <path d="M16 3.128a4 4 0 0 1 0 7.744"></path>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h2 class="card-title flex-grow-1 mb-0">
                    <a href="{$DEP.url}" class="text-decoration-none text-body fs-5">{$DEP.full_name}</a>
                </h2>
            </div>
            <ul class="list-group list-group-flush">
                {if $DEP.image ne ''}
                <li class="list-group-item">
                    <img src="{$DEP.image}" srcset="{$DEP.srcset}" class="img-thumbnail" alt="{$DEP.full_name}">
                </li>
                {/if}
                {if $DEP.note ne ''}
                <li class="list-group-item">{$DEP.note}</li>
                {/if}
                {if $DEP.address ne ''}
                <li class="list-group-item">
                    <em class="fa fa-map-marker fa-horizon me-2 text-primary"></em>{$LANG->getModule('address')}:
                    <span>{$DEP.address}</span>
                </li>
                {/if}
                {foreach from=$DEP.cd item=CD}
                <li class="list-group-item">
                    {if $CD.type eq 'phone'}
                    <em class="fa fa-phone fa-horizon me-2 text-primary"></em>{$LANG->getModule('phone')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'fax'}
                    <em class="fa fa-fax fa-horizon me-2 text-primary"></em>{$LANG->getModule('fax')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'email'}
                    <em class="fa fa-envelope fa-horizon me-2 text-primary"></em>{$LANG->getModule('email')}:
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
                    <em class="fa fa-skype fa-horizon me-2 text-primary"></em>Skype:
                    <span>
                        {foreach from=$CD.value item=SK name=sk}
                        {$SK}{if !$smarty.foreach.sk.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'viber'}
                    <em class="fa fa-viber fa-horizon me-2 text-primary"></em>Viber:
                    <span>
                        {foreach from=$CD.value item=VB name=vb}
                        {$VB}{if !$smarty.foreach.vb.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'whatsapp'}
                    <em class="fa fa-whatsapp fa-horizon me-2 text-primary"></em>WhatsApp:
                    <span>
                        {foreach from=$CD.value item=WA name=wa}
                        {$WA}{if !$smarty.foreach.wa.last}, {/if}
                        {/foreach}
                    </span>
                    {elseif $CD.type eq 'zalo'}
                    <em class="icon-zalo fa-horizon me-2 text-primary"></em>Zalo:
                    <span>
                        {foreach from=$CD.value item=ZA name=za}
                        {$ZA}{if !$smarty.foreach.za.last}, {/if}
                        {/foreach}
                    </span>
                    {else}
                    <em class="fa fa-info-circle fa-horizon me-2 text-primary"></em>{$CD.type}:
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
        <div class="card mb-3 shadow-sm border-0 rounded-4 sticky-md-top">
            <div class="card-header">
                <div class="card-title mb-0 text-decoration-none text-body fs-5 p-2 fw-medium">{$LANG->getModule('feedback_form')|default:$LANG->getGlobal('feedback')}</div>
            </div>
            <div class="card-body">
                <p class="mb-4">{$LANG->getModule('feedback_form_note')}</p>
                {$FORM}
            </div>
        </div>
    </div>
</div>
