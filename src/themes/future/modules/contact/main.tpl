<div class="mb-3 d-flex align-items-center gap-2">
    <h1 class="mb-0"><strong>{$PAGE_TITLE}</strong></h1>
</div>

{if not empty($DATA.bodytext)}
<div class="alert alert-primary mb-4 richtext-container">{$DATA.bodytext}</div>
{/if}

<div class="row g-4">
    <div class="col-md-5 vstack gap-3">
        {foreach from=$DEPARTMENTS item=DEP}
        <div class="card">
            <div class="card-header d-flex align-items-center">
                {if !$IS_SPECIFIC}
                <h2 class="card-title flex-grow-1 mb-0 fs-5 text-truncate">
                    <a href="{$DEP.url}" class="text-decoration-none text-body" title="{$DEP.full_name}">{$DEP.full_name}</a>
                </h2>
                <a href="{$DEP.url}" class="text-primary ms-2" aria-label="{$LANG->getModule('details')}">
                    <i class="fa-solid fa-arrow-right fa-fw" aria-hidden="true"></i>
                </a>
                {else}
                <h2 class="card-title flex-grow-1 mb-0 fs-5 fw-medium text-truncate" title="{$LANG->getModule('contact_info')}">{$LANG->getModule('contact_info')}</h2>
                {/if}
            </div>
            {if $DEP.image ne ''}
            <img src="{$DEP.image}" class="card-img-top rounded-top-0" alt="{$DEP.full_name}">
            {/if}
            <ul class="list-group list-group-flush">
                {if $DEP.note ne ''}
                <li class="list-group-item richtext-container">{$DEP.note}</li>
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
                    <span class="contact-icons text-center me-2"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,256,256"><g fill="#1477cc" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h32c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h6.58008c-3.57109,3.71569 -5.58008,8.51808 -5.58008,13.5c0,5.16 2.11016,10.09984 5.91016,13.83984c0.12,0.21 0.21977,1.23969 -0.24023,2.42969c-0.29,0.75 -0.87023,1.72961 -1.99023,2.09961c-0.43,0.14 -0.70969,0.56172 -0.67969,1.01172c0.03,0.45 0.36078,0.82992 0.80078,0.91992c2.87,0.57 4.72852,-0.2907 6.22852,-0.9707c1.35,-0.62 2.24133,-1.04047 3.61133,-0.48047c2.8,1.09 5.77938,1.65039 8.85938,1.65039c4.09369,0 8.03146,-0.99927 11.5,-2.88672v3.88672c0,1.66848 -1.33152,3 -3,3h-32c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM33,15c0.55,0 1,0.45 1,1v9c0,0.55 -0.45,1 -1,1c-0.55,0 -1,-0.45 -1,-1v-9c0,-0.55 0.45,-1 1,-1zM18,16h5c0.36,0 0.70086,0.19953 0.88086,0.51953c0.17,0.31 0.15875,0.69977 -0.03125,1.00977l-4.04883,6.4707h3.19922c0.55,0 1,0.45 1,1c0,0.55 -0.45,1 -1,1h-5c-0.36,0 -0.70086,-0.19953 -0.88086,-0.51953c-0.17,-0.31 -0.15875,-0.69977 0.03125,-1.00977l4.04883,-6.4707h-3.19922c-0.55,0 -1,-0.45 -1,-1c0,-0.55 0.45,-1 1,-1zM27.5,19c0.61,0 1.17945,0.16922 1.68945,0.44922c0.18,-0.26 0.46055,-0.44922 0.81055,-0.44922c0.55,0 1,0.45 1,1v5c0,0.55 -0.45,1 -1,1c-0.35,0 -0.63055,-0.18922 -0.81055,-0.44922c-0.51,0.28 -1.07945,0.44922 -1.68945,0.44922c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM38.5,19c1.93,0 3.5,1.57 3.5,3.5c0,1.93 -1.57,3.5 -3.5,3.5c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM27.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.19551,0.03988 -0.37754,0.11691 -0.53711,0.22461c-0.15957,0.1077 -0.2966,0.24473 -0.4043,0.4043c-0.10769,0.15957 -0.18473,0.3416 -0.22461,0.53711c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.15957,0.10769 0.3416,0.18473 0.53711,0.22461c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5zM38.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.09775,0.01994 -0.19149,0.04805 -0.28125,0.08594c-0.08977,0.03789 -0.17607,0.08482 -0.25586,0.13867c-0.07979,0.05385 -0.15289,0.11578 -0.2207,0.18359c-0.13562,0.13563 -0.24648,0.29703 -0.32227,0.47656c-0.03789,0.08976 -0.066,0.1835 -0.08594,0.28125c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.07979,0.05385 0.16609,0.10078 0.25586,0.13867c0.08976,0.03789 0.1835,0.066 0.28125,0.08594c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5z"></path></g></g></svg></span>Zalo:
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
        {if !empty($SUPPORTERS)}
        <div class="card">
            <div class="card-header">
                {if $IS_SPECIFIC}
                <h2 class="card-title mb-0 fs-5 fw-medium">{$LANG->getModule('supporters')}</h2>
                {else}
                <div class="card-title mb-0 fs-5 fw-medium">{$LANG->getModule('supporters')}</div>
                {/if}
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
                                <span class="contact-icons text-center me-2"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0,0,256,256"><g fill="#1477cc" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M9,4c-2.74952,0 -5,2.25048 -5,5v32c0,2.74952 2.25048,5 5,5h32c2.74952,0 5,-2.25048 5,-5v-32c0,-2.74952 -2.25048,-5 -5,-5zM9,6h6.58008c-3.57109,3.71569 -5.58008,8.51808 -5.58008,13.5c0,5.16 2.11016,10.09984 5.91016,13.83984c0.12,0.21 0.21977,1.23969 -0.24023,2.42969c-0.29,0.75 -0.87023,1.72961 -1.99023,2.09961c-0.43,0.14 -0.70969,0.56172 -0.67969,1.01172c0.03,0.45 0.36078,0.82992 0.80078,0.91992c2.87,0.57 4.72852,-0.2907 6.22852,-0.9707c1.35,-0.62 2.24133,-1.04047 3.61133,-0.48047c2.8,1.09 5.77938,1.65039 8.85938,1.65039c4.09369,0 8.03146,-0.99927 11.5,-2.88672v3.88672c0,1.66848 -1.33152,3 -3,3h-32c-1.66848,0 -3,-1.33152 -3,-3v-32c0,-1.66848 1.33152,-3 3,-3zM33,15c0.55,0 1,0.45 1,1v9c0,0.55 -0.45,1 -1,1c-0.55,0 -1,-0.45 -1,-1v-9c0,-0.55 0.45,-1 1,-1zM18,16h5c0.36,0 0.70086,0.19953 0.88086,0.51953c0.17,0.31 0.15875,0.69977 -0.03125,1.00977l-4.04883,6.4707h3.19922c0.55,0 1,0.45 1,1c0,0.55 -0.45,1 -1,1h-5c-0.36,0 -0.70086,-0.19953 -0.88086,-0.51953c-0.17,-0.31 -0.15875,-0.69977 0.03125,-1.00977l4.04883,-6.4707h-3.19922c-0.55,0 -1,-0.45 -1,-1c0,-0.55 0.45,-1 1,-1zM27.5,19c0.61,0 1.17945,0.16922 1.68945,0.44922c0.18,-0.26 0.46055,-0.44922 0.81055,-0.44922c0.55,0 1,0.45 1,1v5c0,0.55 -0.45,1 -1,1c-0.35,0 -0.63055,-0.18922 -0.81055,-0.44922c-0.51,0.28 -1.07945,0.44922 -1.68945,0.44922c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM38.5,19c1.93,0 3.5,1.57 3.5,3.5c0,1.93 -1.57,3.5 -3.5,3.5c-1.93,0 -3.5,-1.57 -3.5,-3.5c0,-1.93 1.57,-3.5 3.5,-3.5zM27.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.19551,0.03988 -0.37754,0.11691 -0.53711,0.22461c-0.15957,0.1077 -0.2966,0.24473 -0.4043,0.4043c-0.10769,0.15957 -0.18473,0.3416 -0.22461,0.53711c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.15957,0.10769 0.3416,0.18473 0.53711,0.22461c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5zM38.5,21c-0.10375,0 -0.20498,0.01131 -0.30273,0.03125c-0.09775,0.01994 -0.19149,0.04805 -0.28125,0.08594c-0.08977,0.03789 -0.17607,0.08482 -0.25586,0.13867c-0.07979,0.05385 -0.15289,0.11578 -0.2207,0.18359c-0.13562,0.13563 -0.24648,0.29703 -0.32227,0.47656c-0.03789,0.08976 -0.066,0.1835 -0.08594,0.28125c-0.01994,0.09775 -0.03125,0.19898 -0.03125,0.30273c0,0.10375 0.01131,0.20498 0.03125,0.30273c0.01994,0.09775 0.04805,0.19149 0.08594,0.28125c0.03789,0.08977 0.08482,0.17607 0.13867,0.25586c0.05385,0.07979 0.11578,0.15289 0.18359,0.2207c0.06781,0.06781 0.14092,0.12975 0.2207,0.18359c0.07979,0.05385 0.16609,0.10078 0.25586,0.13867c0.08976,0.03789 0.1835,0.066 0.28125,0.08594c0.09775,0.01994 0.19898,0.03125 0.30273,0.03125c0.10375,0 0.20498,-0.01131 0.30273,-0.03125c0.68428,-0.13959 1.19727,-0.7425 1.19727,-1.46875c0,-0.83 -0.67,-1.5 -1.5,-1.5z"></path></g></g></svg></span>Zalo: {foreach from=$CD.value item=ZA name=za}{$ZA}{if !$smarty.foreach.za.last}, {/if}{/foreach}</span>
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
    </div>

    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-header">
                <h2 class="card-title mb-0 fs-5 fw-medium">{$LANG->getModule('feedback_form')|default:$LANG->getGlobal('feedback')}</h2>
            </div>
            <div class="card-body">
                <p class="mb-4">{$LANG->getModule('feedback_form_note')}</p>
                {$FORM}
            </div>
        </div>
    </div>
</div>
