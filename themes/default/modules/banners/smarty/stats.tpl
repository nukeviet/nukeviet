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

    <div class="col-xs-18 col-sm-8 m-bottom">
        <select class="form-control" id="adsstat-type" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views')}</option>
            <option value="country">{$LANG->getModule('stats_type_country')}</option>
            <option value="browser">{$LANG->getModule('stats_type_browser')}</option>
            <option value="os">{$LANG->getModule('stats_type_os')}</option>
            <option value="date">{$LANG->getModule('stats_type_date')}</option>
        </select>
    </div>

    <div class="col-xs-6 col-sm-6 m-bottom">
        <select class="form-control" id="adsstat-month" data-toggle="loadStat">
            <option value="">{$LANG->getModule('stats_views_month')}</option>
{for $month=1 to 12}
            <option value="{$month}">{$month|string_format: "%02d"}</option>
{/for}
        </select>
    </div>
</div>
<div class="text-center m-bottom" id="chartdata" style="display:none"></div>
