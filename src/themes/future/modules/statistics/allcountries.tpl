<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fa-solid fa-earth-asia text-primary"></i>
        <h2 class="h6 mb-0">{$LANG->getModule('statbycountry')}</h2>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$COUNTRIES_LIST item=row}
        <li class="list-group-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-truncate me-2 small">
                    <span class="badge bg-secondary me-1">{$row.key}</span>{$row.name}
                </span>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    {if $row.last_visit}
                    <small class="text-muted d-none d-xl-inline">{$row.last_visit}</small>
                    {/if}
                    <span class="badge bg-primary rounded-pill">{$row.count_format}</span>
                </div>
            </div>
            {if $row.proc}
            <div class="progress" style="height:4px" role="progressbar" aria-valuenow="{$row.proc}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-warning" style="width:{$row.proc}%"></div>
            </div>
            {/if}
        </li>
        {foreachelse}
        <li class="list-group-item text-center text-muted py-4">
            <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('others')}
        </li>
        {/foreach}
    </ul>
    {if $PAGINATION}
    <div class="card-footer pagination-wrap text-center">
        {$PAGINATION nofilter}
    </div>
    {/if}
</div>
