<!-- BEGIN: main -->
<script src="{ASSETS_STATIC_URL}/js/chart/chart.min.js"></script>
<script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/chartstat.js"></script>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw me-1"></i>{CTS.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_month" data-chart-type="line" data-caption="{CTS.chart_caption}" data-xtitle="{LANG.month}" data-ytitle="{LANG.access_times}" data-bg="54, 162, 235" data-border="54, 162, 235" data-labels="{CTS.dataLabel}" data-values="{CTS.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTS.total}</strong>
    </div>
</div>
<!-- END: main -->