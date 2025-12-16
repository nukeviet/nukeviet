<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.min.js"></script>
{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-md-5">
        <select class="form-select" id="adsstat-ads" data-bs-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_ads')}</option>
{foreach $ADS as $ad}
            <option value="{$ad.id}">{$ad.title}</option>
{/foreach}
        </select>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <select class="form-select" id="adsstat-month" data-bs-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_month')}</option>
{for $month=1 to 12}
            <option value="{$month}">{$month|string_format: "%02d"}</option>
{/for}
        </select>
    </div>
</div>
<div class="card bg-primary text-white mb-4" id="stat-summary" style="display:none">
    <div class="card-body">
        <div class="text-uppercase small opacity-75">{$LANG->getModule('hits_total')}</div>
        <div class="fs-2 fw-bold" id="total-clicks">0</div>
    </div>
</div>
<div class="text-center mb-4" id="stat-loading" style="display:none">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<div class="row g-4" id="stat-charts" style="display:none">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{$LANG->getModule('stats_type_date')}</h5>
                <div id="chart-date"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{$LANG->getModule('stats_type_country')}</h5>
                <div id="chart-country"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{$LANG->getModule('stats_type_os')}</h5>
                <div id="chart-os"></div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{$LANG->getModule('stats_type_browser')}</h5>
                <div id="chart-browser"></div>
            </div>
        </div>
    </div>
</div>
