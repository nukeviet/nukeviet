
<div class="block-supporter">
    <div class="mb-4">
        <div class="text-uppercase text-secondary fw-bold mb-3 small section-title">Phòng ban</div>
        <div class="nav flex-column nav-pills gap-2" role="tablist">
            {foreach from=$DEPARTMENTS item=dep}
            <button class="nav-link text-start w-100 rounded-3 py-2 px-3 d-flex align-items-center justify-content-between {if $dep.active}active{/if}" 
                    id="tab-dep-{$dep.id}" 
                    data-bs-toggle="pill" 
                    data-bs-target="#dep-{$dep.id}" 
                    type="button" 
                    role="tab" 
                    aria-controls="dep-{$dep.id}" 
                    aria-selected="{if $dep.active}true{else}false{/if}">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid {$dep.icon} dep-icon"></i>
                    <span>{$dep.full_name}</span>
                </div>
                <i class="fa-solid fa-circle text-white dot-active"></i>
            </button>
            {/foreach}
        </div>
    </div>

    <div class="tab-content">
        {foreach from=$DEPARTMENTS item=dep}
        <div class="tab-pane fade{if $dep.active} show active{/if}" id="dep-{$dep.id}" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3 px-3">
                <div class="text-uppercase text-secondary fw-bold mb-0 small section-title d-inline-flex align-items-center gap-2">
                    <span class="section-marker"></span>
                    {$SUPPORTERS[$dep.id]|@count} {$LANG->getModule('supporters')}
                </div>
                <span class="text-success d-inline-flex align-items-center"><i class="fa-solid fa-circle status-dot-sm"></i></span>
            </div>
            
            {foreach from=$SUPPORTERS[$dep.id] item=supporter name=sps}
            <div class="card border-0 shadow-sm rounded-4 supporter-card{if !$smarty.foreach.sps.last} mb-3{/if}">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="position-relative">
                            <img src="{$supporter.image}" class="rounded-circle border avatar-img" alt="{$supporter.full_name}">
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle status-indicator"></span>
                        </div>
                        <div class="ms-3">
                            <div class="mb-1 fw-bold text-dark supporter-name">{$supporter.full_name}</div>
                            <div class="small text-uppercase text-secondary fw-semibold role-text">
                                {$LANG->getModule('supporters')}
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        {if $supporter.has_call}
                        <a href="{$supporter.call_href}" class="btn btn-primary w-100 rounded-4 py-2 px-3 fw-semibold border-0 shadow-sm btn-call d-flex align-items-center">
                            <i class="fa-solid fa-phone me-3 fs-5"></i>
                            <div class="text-start">
                                <div class="action-value">{$supporter.phone_text}</div>
                            </div>
                        </a>
                        {/if}
                        {if $supporter.has_email}
                        <a href="{$supporter.email_href}" class="btn btn-light w-100 rounded-4 py-2 px-3 text-secondary fw-semibold border-0 bg-light d-flex align-items-center">
                            <i class="fa-solid fa-envelope me-3 fs-5"></i>
                            <div class="text-start">
                                <div class="action-value">{$supporter.email_text}</div>
                            </div>
                        </a>
                        {/if}
                    </div>

                    {if isset($supporter.others) && $supporter.others}
                    <div class="mt-3">
                        <div class="text-secondary small fw-bold text-uppercase mb-2">SOCIAL</div>
                        <div class="row g-2">
                        {foreach from=$supporter.others key=k item=v}
                            {if $k == 'zalo'}
                            <div class="col-3">
                                <a href="https://zalo.me/{$v}" target="_blank" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center border-0 social-btn contact-icons">
                                    <i class="icon-zalo1 fs-4 mb-1 h4-font-size"></i>
                                </a>
                            </div>
                            {elseif $k == 'whatsapp'}
                            <div class="col-3">
                                <a href="https://wa.me/{$v}" target="_blank" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center border-0 social-btn whatsapp">
                                    <i class="fa-brands fa-whatsapp fs-4 mb-1 social-icon"></i>
                                </a>
                            </div>
                            {elseif $k == 'viber'}
                            <div class="col-3">
                                <a href="viber://chat?number={$v}" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center border-0 social-btn viber">
                                    <i class="fa-brands fa-viber fs-4 mb-1 social-icon"></i>
                                </a>
                            </div>
                            {elseif $k == 'skype'}
                            <div class="col-3">
                                <a href="skype:{$v}?chat" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center border-0 social-btn skype">
                                    <i class="fa-brands fa-skype fs-4 mb-1 social-icon"></i>
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
