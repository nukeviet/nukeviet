<!-- BEGIN: main -->
<!-- BEGIN: management -->
<ul class="nav nav-tabs m-bottom">
    <li><a href="{MANAGEMENT.main}">{LANG.plan_info}</a></li>
    <li><a href="{MANAGEMENT.addads}">{LANG.client_addads}</a></li>
    <li class="active"><a href="{MANAGEMENT.stats}">{LANG.client_stats}</a></li>
</ul>
<!-- END: management -->
<div class="row m-bottom">
    <div class="col-xs-24 col-sm-10 m-bottom">
        <select class="form-control" id="adsstat-ads" onchange="loadStat()">
            <option value="">{LANG.stats_views_ads}</option>
            <!-- BEGIN: ads -->
            <option value="{ads.id}">{ads.title}</option>
            <!-- END: ads -->
        </select>
    </div>

    <div class="col-xs-18 col-sm-8 m-bottom">
        <select class="form-control" id="adsstat-type" onchange="loadStat()">
            <option value="">{LANG.stats_views}</option>
            <option value="country">{LANG.stats_type_country}</option>
            <option value="browser">{LANG.stats_type_browser}</option>
            <option value="os">{LANG.stats_type_os}</option>
            <option value="date">{LANG.stats_type_date}</option>
        </select>
    </div>

    <div class="col-xs-6 col-sm-6 m-bottom">
        <select class="form-control" id="adsstat-month" onchange="loadStat()">
            <option value="">{LANG.stats_views_month}</option>
            <!-- BEGIN: month -->
            <option value="{month}">{month}</option>
            <!-- END: month -->
        </select>
    </div>
</div>
<div class="text-center m-bottom" id="chartdata" style="display:none"></div>
<!-- END: main -->