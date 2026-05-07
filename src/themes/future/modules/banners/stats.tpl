<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.min.js"></script>
{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<div class="row mb-3">
    <div class="col-sm-6 col-md-5 mb-3">
        <select class="form-select" id="adsstat-ads" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_ads')}</option>
            {foreach from=$ADS item=ad}
            <option value="{$ad.id}">{$ad.title}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-sm-6 col-md-3 mb-3">
        <select class="form-select" id="adsstat-month" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_month')}</option>
            {foreach from=$MONTHS item=month}
            <option value="{$month}">{$month}</option>
            {/foreach}
        </select>
    </div>
</div>

<div class="card border-primary mb-3 d-none" id="stat-summary">
    <div class="card-body">
        <div class="text-uppercase small text-muted">{$LANG->getModule('hits_total')}</div>
        <div class="h1" id="total-clicks">0</div>
    </div>
</div>

<div class="text-center mb-3 d-none" id="stat-loading">
    <span class="spinner-border text-primary" role="status"></span>
</div>

<div id="stat-charts" class="d-none">
    <div class="card mb-3">
        <div class="card-header"><strong>{$LANG->getModule('stats_type_date')}</strong></div>
        <div class="card-body">
            <div id="chart-date" data-empty-mess="{$LANG->getModule('chart_data_empty')}"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><strong>{$LANG->getModule('stats_type_browser')}</strong></div>
                <div class="card-body">
                    <div id="chart-browser" data-empty-mess="{$LANG->getModule('chart_data_empty')}"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header"><strong>{$LANG->getModule('stats_type_os')}</strong></div>
                <div class="card-body">
                    <div id="chart-os" data-empty-mess="{$LANG->getModule('chart_data_empty')}"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><strong>{$LANG->getModule('stats_type_country')}</strong></div>
        <div class="card-body">
            <div id="chart-country" data-empty-mess="{$LANG->getModule('chart_data_empty')}"></div>
        </div>
    </div>
</div>
