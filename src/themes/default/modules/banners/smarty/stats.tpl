<link rel="stylesheet" href="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.css">
<script src="{$smarty.const.ASSETS_STATIC_URL}/js/apexcharts/apexcharts.min.js"></script>
{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs m-bottom">
    <li><a href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li><a href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="active"><a href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<div class="row m-bottom">
    <div class="col-xs-24 col-sm-10 m-bottom">
        <select class="form-control" id="adsstat-ads" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_ads')}</option>
{foreach $ADS as $ad}
            <option value="{$ad.id}">{$ad.title}</option>
{/foreach}
        </select>
    </div>

    <div class="col-xs-24 col-sm-6 m-bottom">
        <select class="form-control" id="adsstat-month" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_month')}</option>
{for $month=1 to 12}
            <option value="{$month}">{$month|string_format: "%02d"}</option>
{/for}
        </select>
    </div>
</div>

<div class="panel panel-primary m-bottom" id="stat-summary" style="display:none">
    <div class="panel-body">
        <div class="text-uppercase small text-muted">{$LANG->getModule('hits_total')}</div>
        <div class="h2" id="total-clicks">0</div>
    </div>
</div>

<div class="text-center m-bottom" id="stat-loading" style="display:none">
    <span class="load-bar"></span>
</div>

<div id="stat-charts" style="display:none">
    <div class="row">
        <div class="col-xs-24 m-bottom">
            <div class="panel panel-default">
                <div class="panel-heading">{$LANG->getModule('stats_type_date')}</div>
                <div class="panel-body">
                    <div id="chart-date"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-24 col-md-12 m-bottom">
            <div class="panel panel-default">
                <div class="panel-heading">{$LANG->getModule('stats_type_country')}</div>
                <div class="panel-body">
                    <div id="chart-country"></div>
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-md-12 m-bottom">
            <div class="panel panel-default">
                <div class="panel-heading">{$LANG->getModule('stats_type_os')}</div>
                <div class="panel-body">
                    <div id="chart-os"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-24 m-bottom">
            <div class="panel panel-default">
                <div class="panel-heading">{$LANG->getModule('stats_type_browser')}</div>
                <div class="panel-body">
                    <div id="chart-browser"></div>
                </div>
            </div>
        </div>
    </div>
</div>
