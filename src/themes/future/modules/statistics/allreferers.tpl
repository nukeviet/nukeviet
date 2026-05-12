<div class="card">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="fa-solid fa-chart-line"></i>
        <strong>{$LANG->getModule('statbyreferer')}</strong>
    </div>
    <ul class="list-group list-group-flush">
        {foreach from=$HOST_LIST item=row}
        <li class="list-group-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <a href="http://{$row.key}" target="_blank" rel="noopener noreferrer" class="text-truncate me-2">
                    <i class="fa-solid fa-arrow-up-right-from-square fa-xs me-1 text-muted"></i>{$row.key}
                </a>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    {if $row.last_visit}
                    <small class="text-muted d-none d-sm-inline">{$row.last_visit}</small>
                    {/if}
                    <span class="badge bg-primary rounded-pill">{$row.count_format}</span>
                    <a href="{$row.bymonth_link}"
                       class="btn btn-sm btn-outline-secondary py-0 px-2"
                       title="{$LANG->getModule('statbymonth2')}"
                       data-bs-toggle="tooltip">
                        <i class="fa-solid fa-calendar-days"></i>
                    </a>
                </div>
            </div>
            <div class="progress" style="height:5px" role="progressbar" aria-valuenow="{$row.proc}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-warning" style="width:{$row.proc}%"></div>
            </div>
        </li>
        {foreachelse}
        <li class="list-group-item text-center text-muted py-4">
            <i class="fa-solid fa-circle-info me-1"></i>Không có dữ liệu
        </li>
        {/foreach}
    </ul>
    {if $PAGINATION}
    <div class="card-footer pagination-wrap text-center">
        {$PAGINATION}
    </div>
    {/if}
</div>
