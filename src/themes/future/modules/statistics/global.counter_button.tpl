{capture assign="popover_html"}
<ul class="list-unstyled mb-0 vstack gap-2">
    {if not empty($DATA.online)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-bolt fa-fw"></i> {$LANG->getGlobal('online')}</span>
        <span class="fw-medium ms-3">{$DATA.online}</span>
    </li>
    {/if}
    {if not empty($DATA.users)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-user fa-fw"></i> {$LANG->getGlobal('users')}</span>
        <span class="fw-medium ms-3">{$DATA.users}</span>
    </li>
    {/if}
    {if not empty($DATA.bots)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-robot fa-fw"></i> {$LANG->getGlobal('bots')}</span>
        <span class="fw-medium ms-3">{$DATA.bots}</span>
    </li>
    {/if}
    {if not empty($DATA.guests) and $DATA.guests neq $DATA.online}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-bullseye fa-fw"></i> {$LANG->getGlobal('guests')}</span>
        <span class="fw-medium ms-3">{$DATA.guests}</span>
    </li>
    {/if}
    <li><hr class="my-1"/></li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-filter fa-fw"></i> {$LANG->getGlobal('today')}</span>
        <span class="fw-medium ms-3">{$DATA.day}</span>
    </li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-calendar fa-fw"></i> {$LANG->getGlobal('current_month')}</span>
        <span class="fw-medium ms-3">{$DATA.month}</span>
    </li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <span><i class="fa-solid fa-bars fa-fw"></i> {$LANG->getGlobal('hits')}</span>
        <span class="fw-medium ms-3">{$DATA.all}</span>
    </li>
</ul>
{/capture}
<button type="button" class="btn btn-primary btn-sm"
        data-nv-counter-btn
        data-bs-toggle="popover"
        data-bs-html="true"
        data-bs-trigger="click"
        data-bs-placement="bottom"
        data-bs-content="{$popover_html|strip|escape:'html'}">
    <i class="fa-solid fa-bolt fa-fw"></i> {$LANG->getGlobal('online')}: {$DATA.online}
</button>
