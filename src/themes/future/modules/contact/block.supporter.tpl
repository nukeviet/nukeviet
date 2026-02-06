
<div class="block-supporter">
    <div class="mb-4">
        <h6 class="text-uppercase text-secondary fw-bold mb-3 small section-title">Phòng ban</h6>
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
                <h6 class="text-uppercase text-secondary fw-bold mb-0 small section-title d-inline-flex align-items-center gap-2">
                    <span class="section-marker"></span>
                    {$SUPPORTERS[$dep.id]|@count} nhân viên trực
                </h6>
                <span class="text-success d-inline-flex align-items-center"><i class="fa-solid fa-circle status-dot-sm"></i></span>
            </div>
            
            {foreach from=$SUPPORTERS[$dep.id] item=supporter}
            <div class="card border-0 shadow-sm rounded-4 mb-3 supporter-card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="position-relative">
                            <img src="{$supporter.image}" class="rounded-circle border avatar-img" alt="{$supporter.full_name}">
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle status-indicator"></span>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-1 fw-bold text-dark supporter-name">{$supporter.full_name}</h6>
                            <div class="small text-uppercase text-secondary fw-semibold role-text">
                                Hỗ trợ trực tuyến
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
                                <a href="https://zalo.me/{$v}" target="_blank" class="btn btn-light w-100 p-2 rounded-3 d-flex flex-column align-items-center justify-content-center border-0 social-btn zalo">
                                    <svg class="social-icon mb-1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0,0,256,256"><g fill="#1477cc" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h32c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h6.58008c-3.57109,3.71569 -5.58008,8.51808 -5.58008,13.5c0,5.16 2.11016,10.09984 5.91016,13.83984c0.12,0.21 0.21977,1.23969 -0.24023,2.42969c-0.29,0.75 -0.87023,1.72961 -1.99023,2.09961c-0.43,0.14 -0.70969,0.56172 -0.67969,1.01172c0.03,0.45 0.36078,0.82992 0.80078,0.91992c2.87,0.57 4.72852,-0.2907 6.22852,-0.9707c1.35,-0.62 2.24133,-1.04047 3.61133,-0.48047c2.8,1.09 5.77938,1.65039 8.85938,1.65039c4.09369,0 8.03146,-0.99927 11.5,-2.88672v3.88672c0,1.66848 -1.33152,3 -3,3h-32c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM33,15c0.55,0 1,0.45 1,1v9c0,0.55 -0.45,1 -1,1c-0.55,0 -1,-0.45 -1,-1v-9c0,-0.55 0.45,-1 1,-1zM18,16h5c0.36,0 0.70086,0.19953 0.88086,0.51953c0.17,0.31 0.15875,0.69977 -0.03125,1.00977l-4.04883,6.4707h3.19922c0.55,0 1,0.45 1,1c0,0.55 -0.45,1 -1,1h-5c-0.36,0 -0.70086,-0.19953 -0.88086,-0.51953c-0.17,-0.31 -0.15875,-0.69977 0.03125,-1.00977l4.04883,-6.4707h-3.19922c-0.55,0 -1,-0.45 -1,-1c0,-0.55 0.45,-1 1,-1zM27.5,19c0.61,0 1.17945,0.16922 1.68945,0.44922c0.18,-0.26 0.46055,-0.44922 0.81055,-0.44922c0.55,0 1,0.45 1,1v5c0,0.55 -0.45,1 -1,1c-0.35,0 -0.63055,-0.18922 -0.81055,-0.44922c-0.51,0.28 -1.07945,0.44922 -1.68945,0.44922c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM38.5,19c1.93,0 3.5,1.57 3.5,3.5c0,1.93 -1.57,3.5 -3.5,3.5c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM27.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.19551,0.03988 -0.37754,0.11691 -0.53711,0.22461c-0.15957,0.1077 -0.2966,0.24473 -0.4043,0.4043c-0.10769,0.15957 -0.18473,0.3416 -0.22461,0.53711c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.15957,0.10769 0.3416,0.18473 0.53711,0.22461c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5zM38.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.09775,0.01994 -0.19149,0.04805 -0.28125,0.08594c-0.08977,0.03789 -0.17607,0.08482 -0.25586,0.13867c-0.07979,0.05385 -0.15289,0.11578 -0.2207,0.18359c-0.13562,0.13563 -0.24648,0.29703 -0.32227,0.47656c-0.03789,0.08976 -0.066,0.1835 -0.08594,0.28125c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.07979,0.05385 0.16609,0.10078 0.25586,0.13867c0.08976,0.03789 0.1835,0.066 0.28125,0.08594c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5z"></path></g></g></svg>
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
