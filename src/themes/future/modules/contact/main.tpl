<h1 class="hidden">{$THEME_PAGE_TITLE}</h1>
<div class="mb-3"><span class="h1"><strong>{$PAGE_TITLE}</strong></span></div>

{if $BODYTEXT ne ''}
<p class="mb-3">{$BODYTEXT}</p>
{/if}

<div class="row g-3">
    <div class="col-12 col-md-7">
        {foreach from=$DEPARTMENTS item=DEP}
        <div class="card mb-3">
            {if $IS_HOME}
            <a href="{$DEP.url}" class="card-header d-flex align-items-center text-decoration-none">
                <h2 class="card-title flex-grow-1 mb-0">{$DEP.full_name}</h2>
                <small class="text-dark">{$LANG->getModule('details')} <i class="fa fa-arrow-right fa-fw"></i></small>
            </a>
            {else}
            <div class="card-header">
                <h2 class="card-title mb-0">{$LANG->getModule('contact_info')}</h2>
            </div>
            {/if}

            {if $IS_HOME}
            <div class="card-body">
                {if $DEP.note ne ''}
                <p class="mb-0">{$DEP.note}</p>
                {/if}
            </div>
            {/if}
            <ul class="list-group list-group-flush">
                {if not $IS_HOME}
                    {if $DEP.image ne ''}
                    <li class="list-group-item">
                        <img src="{$DEP.image}" srcset="{$DEP.srcset}" class="img-thumbnail" alt="{$DEP.full_name}" />
                    </li>
                    {/if}
                    {if $DEP.note ne ''}
                    <li class="list-group-item">{$DEP.note}</li>
                    {/if}
                    {if $DEP.address ne ''}
                    <li class="list-group-item">
                        <em class="fa fa-map-marker fa-horizon me-2"></em>{$LANG->getModule('address')}: <span>{$DEP.address}</span>
                    </li>
                    {/if}
                {/if}
                {foreach from=$DEP.cd item=CD}
                <li class="list-group-item">
                    {if $CD.type eq 'phone'}
                        <em class="fa fa-phone fa-horizon me-2"></em>{$LANG->getModule('phone')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'fax'}
                        <em class="fa fa-fax fa-horizon me-2"></em>{$LANG->getModule('fax')}: <span>{$CD.value}</span>
                    {elseif $CD.type eq 'email'}
                        <em class="fa fa-envelope fa-horizon me-2"></em>{$LANG->getModule('email')}: 
                        {if is_array($CD.value)}
                            <span>{foreach from=$CD.value item=EM name=em}{$EM}{if !$smarty.foreach.em.last}, {/if}{/foreach}</span>
                        {else}
                            <span>{$CD.value}</span>
                        {/if}
                    {elseif $CD.type eq 'skype'}
                        <em class="fa fa-skype fa-horizon me-2"></em>Skype:
                        <span>{foreach from=$CD.value item=SK name=sk}{$SK}{if !$smarty.foreach.sk.last}, {/if}{/foreach}</span>
                    {elseif $CD.type eq 'viber'}
                        <em class="fa fa-viber fa-horizon me-2"></em>Viber:
                        <span>{foreach from=$CD.value item=VB name=vb}{$VB}{if !$smarty.foreach.vb.last}, {/if}{/foreach}</span>
                    {elseif $CD.type eq 'whatsapp'}
                        <em class="fa fa-whatsapp fa-horizon me-2"></em>WhatsApp:
                        <span>{foreach from=$CD.value item=WA name=wa}{$WA}{if !$smarty.foreach.wa.last}, {/if}{/foreach}</span>
                    {elseif $CD.type eq 'zalo'}
                        <em class="icon-zalo fa-horizon me-2"></em>Zalo:
                        <span>{foreach from=$CD.value item=ZA name=za}{$ZA}{if !$smarty.foreach.za.last}, {/if}{/foreach}</span>
                    {else}
                        <em class="fa fa-info-circle fa-horizon me-2"></em>{$CD.type}:
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

    <div class="col-12 col-md-5">
        {if $SUPPORTERS|@count gt 0}
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">{$LANG->getModule('supporters')}</h3>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$SUPPORTERS item=SUPPORTER}
                <li class="list-group-item">
                    <div class="d-flex">
                        <div>{if $SUPPORTER.image ne ''}<img src="{$SUPPORTER.image}" class="supporter-avatar" alt="" />{/if}</div>
                        <div class="flex-grow-1 ms-2">
                            <p class="mb-2"><strong>{$SUPPORTER.full_name}</strong></p>
                            {foreach from=$SUPPORTER.cd item=CD}
                            <p class="mb-1">
                                {if $CD.type eq 'phone'}
                                    <em class="fa fa-phone fa-horizon me-2"></em>{$LANG->getModule('phone')}: <span>{$CD.value}</span>
                                {elseif $CD.type eq 'email'}
                                    <em class="fa fa-envelope fa-horizon me-2"></em>{$LANG->getModule('email')}: 
                                    {if is_array($CD.value)}
                                        <span>{foreach from=$CD.value item=EM2 name=em2}{$EM2}{if !$smarty.foreach.em2.last}, {/if}{/foreach}</span>
                                    {else}
                                        <span>{$CD.value}</span>
                                    {/if}
                                {elseif $CD.type eq 'skype'}
                                    <em class="fa fa-skype fa-horizon me-2"></em>Skype: <span>{foreach from=$CD.value item=SK name=sk2}{$SK}{if !$smarty.foreach.sk2.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'viber'}
                                    <em class="fa fa-viber fa-horizon me-2"></em>Viber: <span>{foreach from=$CD.value item=VB name=vb2}{$VB}{if !$smarty.foreach.vb2.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'whatsapp'}
                                    <em class="fa fa-whatsapp fa-horizon me-2"></em>WhatsApp: <span>{foreach from=$CD.value item=WA name=wa2}{$WA}{if !$smarty.foreach.wa2.last}, {/if}{/foreach}</span>
                                {elseif $CD.type eq 'zalo'}
                                    <em class="icon-zalo fa-horizon me-2"></em>Zalo: <span>{foreach from=$CD.value item=ZA name=za2}{$ZA}{if !$smarty.foreach.za2.last}, {/if}{/foreach}</span>
                                {else}
                                    <em class="fa fa-info-circle fa-horizon me-2"></em>{$CD.type}:
                                    {if $CD.value.is_url}
                                        <a href="{$CD.value.content}">{$CD.value.content}</a>
                                    {else}
                                        <span>{$CD.value.content}</span>
                                    {/if}
                                {/if}
                            </p>
                            {/foreach}
                        </div>
                    </div>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">{$LANG->getGlobal('feedback')}</h3>
            </div>
            <div class="card-body text-center">
                <p class="mb-4">{$LANG->getModule('feedback_form_note')}</p>
                <button type="button" class="btn btn-primary btn-lg show-feedback-form" data-bs-toggle="modal" data-bs-target="#feedback-form">{$LANG->getModule('feedback_form')}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="feedback-form" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{$LANG->getModule('feedback_form')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="loadContactForm">{$FORM}</div>
            </div>
        </div>
    </div>
</div>
