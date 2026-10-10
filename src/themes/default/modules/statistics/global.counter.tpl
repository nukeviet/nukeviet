<ul class="vstack gap-1 list-unstyled mb-0">
    {if not empty($DATA.online)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-bolt fa-fw text-center"></i> {$LANG->getGlobal('online')}</div>
        <div class="fw-medium">{$DATA.online}</div>
    </li>
    {/if}
    {if not empty($DATA.users)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-user fa-fw text-center"></i> {$LANG->getGlobal('users')}</div>
        <div class="fw-medium">{$DATA.users}</div>
    </li>
    {/if}
    {if not empty($DATA.bots)}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-robot fa-fw text-center"></i> {$LANG->getGlobal('bots')}</div>
        <div class="fw-medium">{$DATA.bots}</div>
    </li>
    {/if}
    {if not empty($DATA.guests) and $DATA.guests neq $DATA.online}
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-bullseye fa-fw text-center"></i> {$LANG->getGlobal('guests')}</div>
        <div class="fw-medium">{$DATA.guests}</div>
    </li>
    {/if}
    <li><hr class="my-2"/></li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-filter fa-fw text-center"></i> {$LANG->getGlobal('today')}</div>
        <div class="fw-medium">{$DATA.day}</div>
    </li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-calendar fa-fw text-center"></i> {$LANG->getGlobal('current_month')}</div>
        <div class="fw-medium">{$DATA.month}</div>
    </li>
    <li class="d-flex gap-2 align-items-center justify-content-between">
        <div><i class="fa-solid fa-bars fa-fw text-center"></i> {$LANG->getGlobal('hits')}</div>
        <div class="fw-medium">{$DATA.all}</div>
    </li>
</ul>
