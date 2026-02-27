
<div class="block-supporter">
    <div class="mb-3">
        <div class="text-uppercase text-secondary fw-bold mb-2 small">Phòng ban</div>
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
                        <a href="{$supporter.email_href}" class="btn btn-light w-100 rounded-3 py-2 px-3 d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 flex-shrink-0"></i>
                            <span class="fw-semibold flex-grow-1 text-truncate" title="{$supporter.email_text}">{$supporter.email_text|truncate:18.5:"..."}</span>
                        </a>
                        {/if}
                    </div>

                    {if isset($supporter.others) && $supporter.others}
                    <div class="mt-3">
                        <div class="text-secondary small fw-bold text-uppercase mb-2">Social</div>
                        <div class="row g-2">
                        {foreach from=$supporter.others key=k item=v}
                            {if $k == 'zalo'}
                            <div class="col-3">
                                <a href="https://zalo.me/{$v}" target="_blank" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                    <i class="icon-zalo-contact fs-4 mb-1"></i>
                                    <span class="small social-label">Zalo</span>
                                </a>
                            </div>
                            {elseif $k == 'whatsapp'}
                            <div class="col-3">
                                <a href="https://wa.me/{$v}" target="_blank" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fa-brands fa-whatsapp text-success fs-4 mb-1"></i>
                                    <span class="small social-label">WhatsApp</span>
                                </a>
                            </div>
                            {elseif $k == 'viber'}
                            <div class="col-3">
                                <a href="viber://chat?number={$v}" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fa-brands fa-viber text-primary fs-4 mb-1"></i>
                                    <span class="small social-label">Viber</span>
                                </a>
                            </div>
                            {elseif $k == 'skype'}
                            <div class="col-3">
                                <a href="skype:{$v}?chat" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center">
                                    <i class="fa-brands fa-skype text-info fs-4 mb-1"></i>
                                    <span class="small social-label">Skype</span>
                                </a>
                            </div>
                            {/if}
                        {/foreach}
                        </div>
                    </div>
                    {/if}
                </div>
            </div>
            {/foreach}
        </div>
        {/foreach}
    </div>
</div>
