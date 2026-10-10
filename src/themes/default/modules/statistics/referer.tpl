<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.min.js"></script>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="fa-solid fa-chart-line text-primary"></i>
            <h2 class="h6 mb-0">{$CHART.caption}</h2>
        </div>
        <span class="badge bg-primary rounded-pill">{$CHART.total}</span>
    </div>
    <div class="card-body py-2">
        <div id="chart-referer-month"
             data-nv-stat-chart
             data-labels='{$CHART.labels|json_encode}'
             data-values='{$CHART.values|json_encode}'
             data-values-formatted='{$CHART.values_formatted|json_encode}'
             data-xtitle="{$LANG->getModule('month')}"
             data-ytitle="{$LANG->getModule('access_times')}"
             data-type="area">
        </div>
    </div>
</div>
