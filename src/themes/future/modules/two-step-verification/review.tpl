<div class="text-center mb-3">
    <i class="fa-solid fa-shield fa-4x text-success" aria-hidden="true"></i>
</div>
<h1 class="text-center mb-4">{$LANG->getModule('active_2tep_review1')}</h1>
<div class="step-bar">
    <div class="step completed">
        <div class="circle"><i class="fa-solid fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step completed">
        <div class="circle"><i class="fa-solid fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step active">
        <div class="circle">3</div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-body">
        <h2 class="mb-2 h5">{$LANG->getModule('active_2tep_review2')}</h2>
        <p>{$LANG->getModule('active_2tep_review3')}</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex gap-2">
            <div class="pt-2 px-2">
                <i class="fa-solid fa-user-secret fa-3x fa-fw" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1 flex-shrink-1">
                <h3 class="h6">
                    <strong>{$LANG->getModule('passkey')}</strong>
                    {if $DATA.login_keys > 0}
                    <span class="badge bg-success">{$LANG->getModule('configured')}</span>
                    {/if}
                </h3>
                <div class="text-muted">{$LANG->getModule('passkey_help')}.</div>
            </div>
            <div>
                <a href="{$DATA.link_passkey}" target="_blank" class="btn btn-sm btn-secondary text-nowrap">
                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> {$LANG->getModule('go_config')}
                </a>
            </div>
        </li>
        <li class="list-group-item d-flex gap-2">
            <div class="pt-2 px-2">
                <i class="fa-solid fa-key fa-3x fa-fw" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1 flex-shrink-1">
                <h3 class="h6">
                    <strong>{$LANG->getModule('security_keys')}</strong>
                    {if $DATA.security_keys > 0}
                    <span class="badge bg-success">{$LANG->getModule('configured')}</span>
                    {/if}
                </h3>
                <div class="text-muted">{$LANG->getModule('security_keys_note')}.</div>
            </div>
            <div>
                <a href="{$DATA.link_seckey}" target="_blank" class="btn btn-sm btn-secondary text-nowrap">
                    <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> {$LANG->getModule('go_config')}
                </a>
            </div>
        </li>
    </ul>
</div>
<div class="text-center">
    <a href="{$DATA.redirect}" class="btn btn-success">{$LANG->getGlobal('complete')}</a>
</div>
