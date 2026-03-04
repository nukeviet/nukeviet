
<div class="block-supporter">
    <div class="mb-3">
        <div class="nav flex-column nav-pills gap-2" role="tablist">
            {foreach from=$DEPARTMENTS item=dep}
            <button class="nav-link btn btn-outline-primary text-start w-100 rounded-3 py-2 px-3 {if $dep.active}active{/if}" 
                    id="tab-dep-{$dep.id}" 
                    data-bs-toggle="pill" 
                    data-bs-target="#dep-{$dep.id}" 
                    type="button" 
                    role="tab" 
                    aria-controls="dep-{$dep.id}" 
                    aria-selected="{if $dep.active}true{else}false{/if}">
                {if !empty($dep.icon)}
                <i class="fa-solid {$dep.icon} me-2" aria-hidden="true"></i>
                {/if}
                <span>{$dep.full_name}</span>
            </button>
            {/foreach}
        </div>
    </div>

    <div class="tab-content">
        {foreach from=$DEPARTMENTS item=dep}
        <div class="tab-pane fade{if $dep.active} show active{/if}" id="dep-{$dep.id}" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                <div class="text-secondary small fw-bold text-uppercase mb-0">
                    {$SUPPORTERS[$dep.id]|@count} {$LANG->getModule('supporters')}
                </div>
            </div>
            
            {foreach from=$SUPPORTERS[$dep.id] item=supporter name=sps}
            <div class="card border-0 shadow-sm rounded-3{if !$smarty.foreach.sps.last} mb-3{/if}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{$supporter.image}" class="rounded-circle border avatar-img" alt="{$supporter.full_name}">
                        <div class="ms-3">
                            <div class="mb-1 fw-bold text-dark">{$supporter.full_name}</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        {if $supporter.has_call}
                        <a href="{$supporter.call_href}" class="btn btn-primary w-100 rounded-3 py-2 px-3 d-flex align-items-center">
                            <i class="fa-solid fa-phone me-3"></i>
                            <div class="text-start">
                                <div class="fw-semibold">{$supporter.phone_text}</div>
                            </div>
                        </a>
                        {/if}
                        {if $supporter.has_email}
                        <a href="{$supporter.email_href}" class="btn btn-light text-truncate rounded-3 py-2 px-3 d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 flex-shrink-0"></i><span class="fw-semibold text-truncate flex-grow-1" title="{$supporter.email_text}">{$supporter.email_text}</span>
                        </a>
                        {/if}
                    </div>

                    {if isset($supporter.others) && $supporter.others}
                    {assign var=social_count value=0}
                    {capture name=social_html}
                        {foreach from=$supporter.others key=k item=v}
                            {if !empty($v)}
                                {if $k == 'zalo'}
                                <div class="col-3">
                                    <a href="https://zalo.me/{$v}" target="_blank" aria-label="zalo" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                        <i class="icon-zalo-contact fs-4"></i>
                                    </a>
                                </div>
                                {assign var=social_count value=$social_count+1}
                                {elseif $k == 'whatsapp'}
                                <div class="col-3">
                                    <a href="https://wa.me/{$v}" target="_blank" aria-label="whatsapp" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                        <i class="fa-brands fa-whatsapp text-success fs-4"></i>
                                    </a>
                                </div>
                                {assign var=social_count value=$social_count+1}
                                {elseif $k == 'viber'}
                                <div class="col-3">
                                    <a href="viber://chat?number={$v}" aria-label="viber" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                        <i class="fa-brands fa-viber text-primary fs-4"></i>
                                    </a>
                                </div>
                                {assign var=social_count value=$social_count+1}
                                {elseif $k == 'skype'}
                                <div class="col-3">
                                    <a href="skype:{$v}?chat" aria-label="skype" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                        <i class="fa-brands fa-skype text-info fs-4"></i>
                                    </a>
                                </div>
                                {assign var=social_count value=$social_count+1}
                                {/if}
                            {/if}
                        {/foreach}
                    {/capture}
                    {if $social_count gt 0}
                    <div class="mt-3">
                        <div class="text-secondary small fw-bold text-uppercase mb-2">{$LANG->getModule('otherContacts')}</div>
                        <div class="row g-2">{$smarty.capture.social_html}</div>
                    </div>
                    {/if}
                    {/if}
                </div>
            </div>
            {/foreach}
        </div>
        {/foreach}
    </div>
</div>
