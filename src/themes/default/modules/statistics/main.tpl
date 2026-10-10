<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.min.js"></script>

<div class="row g-3 mb-3">
    {* Biểu đồ giờ hôm nay — rộng hơn vì nhiều điểm dữ liệu *}
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock text-primary"></i>
                    <h2 class="h6 mb-0">{$CTSH.caption}</h2>
                </div>
                <span class="badge bg-primary rounded-pill">{$CTSH.total}</span>
            </div>
            <div class="card-body py-2">
                <div id="chart-hour"
                     data-nv-stat-chart
                     data-labels='{$CTSH.labels|json_encode}'
                     data-values='{$CTSH.values|json_encode}'
                     data-values-formatted='{$CTSH.values_formatted|json_encode}'
                     data-xtitle="{$LANG->getModule('hour')}"
                     data-ytitle="{$LANG->getModule('access_times')}"
                     data-type="area">
                </div>
            </div>
        </div>
    </div>

    {* Biểu đồ ngày trong tuần — donut *}
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calendar-week text-primary"></i>
                    <h2 class="h6 mb-0">{$CTSDW.caption}</h2>
                </div>
                <span class="badge bg-primary rounded-pill">{$CTSDW.total}</span>
            </div>
            <div class="card-body py-2">
                <div id="chart-weekday"
                     data-nv-stat-chart
                     data-labels='{$CTSDW.labels|json_encode}'
                     data-values='{$CTSDW.values|json_encode}'
                     data-values-formatted='{$CTSDW.values_formatted|json_encode}'
                     data-type="donut">
                </div>
            </div>
        </div>
    </div>
</div>

{* Biểu đồ ngày trong tháng — full width vì nhiều điểm dữ liệu (tối đa 31 ngày) *}
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-calendar-days text-primary"></i>
            <h2 class="h6 mb-0">{$CTSDM.caption}</h2>
        </div>
        <span class="badge bg-primary rounded-pill">{$CTSDM.total}</span>
    </div>
    <div class="card-body py-2">
        <div id="chart-daymonth"
             data-nv-stat-chart
             data-labels='{$CTSDM.labels|json_encode}'
             data-values='{$CTSDM.values|json_encode}'
             data-values-formatted='{$CTSDM.values_formatted|json_encode}'
             data-xtitle="{$LANG->getModule('day')}"
             data-ytitle="{$LANG->getModule('access_times')}"
             data-type="area">
        </div>
    </div>
</div>

{* Tháng trong năm + Thống kê theo năm — cùng row vì đều là xu hướng dài hạn *}
<div class="row g-3 mb-3">
    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-primary"></i>
                    <h2 class="h6 mb-0">{$CTSM.caption}</h2>
                </div>
                <span class="badge bg-primary rounded-pill">{$CTSM.total}</span>
            </div>
            <div class="card-body py-2">
                <div id="chart-month"
                     data-nv-stat-chart
                     data-labels='{$CTSM.labels|json_encode}'
                     data-values='{$CTSM.values|json_encode}'
                     data-values-formatted='{$CTSM.values_formatted|json_encode}'
                     data-xtitle="{$LANG->getModule('month')}"
                     data-ytitle="{$LANG->getModule('access_times')}"
                     data-type="bar">
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-column text-primary"></i>
                    <h2 class="h6 mb-0">{$CTSY.caption}</h2>
                </div>
                <span class="badge bg-primary rounded-pill">{$CTSY.total}</span>
            </div>
            <div class="card-body py-2">
                <div id="chart-year"
                     data-nv-stat-chart
                     data-labels='{$CTSY.labels|json_encode}'
                     data-values='{$CTSY.values|json_encode}'
                     data-values-formatted='{$CTSY.values_formatted|json_encode}'
                     data-xtitle="{$LANG->getModule('year')}"
                     data-ytitle="{$LANG->getModule('access_times')}"
                     data-type="bar">
                </div>
            </div>
        </div>
    </div>
</div>

{* Danh sách quốc gia / trình duyệt / hệ điều hành *}
<div class="row g-3">
    {* Quốc gia *}
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fa-solid fa-earth-asia text-primary"></i>
                <h2 class="h6 mb-0">{$LANG->getModule('statbycountry')}</h2>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$CTSC.rows item=row}
                <li class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="text-truncate me-2">
                            <span class="badge bg-secondary me-1">{$row.key}</span>
                            <span class="small">{$row.name}</span>
                        </div>
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
                {if $CTSC.others}
                <li class="list-group-item px-3 py-2 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{$LANG->getModule('others')}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-pill">{$CTSC.others}</span>
                        <a href="{$CTSC.others_url}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                            <i class="fa-solid fa-angles-right"></i> {$LANG->getModule('viewall')}
                        </a>
                    </div>
                </li>
                {/if}
            </ul>
        </div>
    </div>

    {* Trình duyệt *}
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fa-solid fa-globe text-primary"></i>
                <h2 class="h6 mb-0">{$LANG->getModule('statbybrowser')}</h2>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$CTSB.rows item=row}
                <li class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="text-truncate me-2">
                            <span class="small">{$row.name}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            {if $row.last_visit}
                            <small class="text-muted d-none d-xl-inline">{$row.last_visit}</small>
                            {/if}
                            <span class="badge bg-primary rounded-pill">{$row.count_format}</span>
                        </div>
                    </div>
                    {if $row.proc}
                    <div class="progress" style="height:4px" role="progressbar" aria-valuenow="{$row.proc}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-info" style="width:{$row.proc}%"></div>
                    </div>
                    {/if}
                </li>
                {foreachelse}
                <li class="list-group-item text-center text-muted py-4">
                    <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('others')}
                </li>
                {/foreach}
                {if $CTSB.others}
                <li class="list-group-item px-3 py-2 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{$LANG->getModule('others')}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-pill">{$CTSB.others}</span>
                        <a href="{$CTSB.others_url}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                            <i class="fa-solid fa-angles-right"></i> {$LANG->getModule('viewall')}
                        </a>
                    </div>
                </li>
                {/if}
            </ul>
        </div>
    </div>

    {* Hệ điều hành *}
    <div class="col-12">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="fa-solid fa-desktop text-primary"></i>
                <h2 class="h6 mb-0">{$LANG->getModule('statbyos')}</h2>
            </div>
            <ul class="list-group list-group-flush">
                {foreach from=$CTSO.rows item=row}
                <li class="list-group-item px-3 py-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="text-truncate me-2">
                            <span class="small">{$row.name}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            {if $row.last_visit}
                            <small class="text-muted d-none d-xl-inline">{$row.last_visit}</small>
                            {/if}
                            <span class="badge bg-primary rounded-pill">{$row.count_format}</span>
                        </div>
                    </div>
                    {if $row.proc}
                    <div class="progress" style="height:4px" role="progressbar" aria-valuenow="{$row.proc}" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-success" style="width:{$row.proc}%"></div>
                    </div>
                    {/if}
                </li>
                {foreachelse}
                <li class="list-group-item text-center text-muted py-4">
                    <i class="fa-solid fa-circle-info me-1"></i>{$LANG->getModule('others')}
                </li>
                {/foreach}
                {if $CTSO.others}
                <li class="list-group-item px-3 py-2 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{$LANG->getModule('others')}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-secondary rounded-pill">{$CTSO.others}</span>
                        {if $CTSO.others_url}
                        <a href="{$CTSO.others_url}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                            <i class="fa-solid fa-angles-right"></i> {$LANG->getModule('viewall')}
                        </a>
                        {/if}
                    </div>
                </li>
                {/if}
            </ul>
        </div>
    </div>
</div>
