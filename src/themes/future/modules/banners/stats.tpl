{if $smarty.const.NV_IS_BANNER_CLIENT}
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.main}">{$LANG->getModule('plan_info')}</a></li>
    <li class="nav-item"><a class="nav-link" href="{$MANAGEMENT.addads}">{$LANG->getModule('client_addads')}</a></li>
    <li class="nav-item"><a class="nav-link active" aria-current="page" href="{$MANAGEMENT.stats}">{$LANG->getModule('client_stats')}</a></li>
</ul>
{/if}
<div class="row mb-3">
    <div class="col-12 col-sm-5 mb-3">
        <select class="form-select" id="adsstat-ads" data-bs-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_ads')}</option>
{foreach $ADS as $ad}
            <option value="{$ad.id}">{$ad.title}</option>
{/foreach}
        </select>
    </div>

    <div class="col-9 col-sm-4 mb-3">
        <select class="form-select" id="adsstat-type" data-bs-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views')}</option>
            <option value="country">{$LANG->getModule('stats_type_country')}</option>
            <option value="browser">{$LANG->getModule('stats_type_browser')}</option>
            <option value="os">{$LANG->getModule('stats_type_os')}</option>
            <option value="date">{$LANG->getModule('stats_type_date')}</option>
        </select>
    </div>

    <div class="col-3 col-sm-3 mb-3">
        <select class="form-select" id="adsstat-month" data-bs-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_month')}</option>
{for $month=1 to 12}
            <option value="{$month}">{$month|string_format: "%02d"}</option>
{/for}
        </select>
    </div>
</div>
<div class="text-center mb-3" id="chartdata" style="display:none"></div>
