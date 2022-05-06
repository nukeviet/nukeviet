<!-- BEGIN: main -->
<script src="{ASSETS_STATIC_URL}/js/chart/chart.min.js"></script>
<script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/chartstat.js"></script>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw"></i> {CTSH.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_hour" data-chart-type="line" data-xtitle="{LANG.hour}" data-ytitle="{LANG.access_times}" data-bg="54, 162, 235" data-border="54, 162, 235" data-labels="{CTSH.dataLabel}" data-values="{CTSH.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTSH.total}</strong>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw"></i> {CTSDW.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_weekday" data-chart-type="pie" data-bg="255, 99, 132|255, 159, 64|255, 205, 86|75, 192, 192|54, 162, 235|153, 102, 255|201, 203, 207" data-labels="{CTSDW.dataLabel}" data-values="{CTSDW.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTSDW.total}</strong>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw"></i> {CTSDM.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_daymonth" data-chart-type="line" data-xtitle="{LANG.day}" data-ytitle="{LANG.access_times}" data-bg="54, 162, 235" data-border="54, 162, 235" data-labels="{CTSDM.dataLabel}" data-values="{CTSDM.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTSDM.total}</strong>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw"></i> {CTSM.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_month" data-chart-type="line" data-xtitle="{LANG.month}" data-ytitle="{LANG.access_times}" data-bg="54, 162, 235" data-border="54, 162, 235" data-labels="{CTSM.dataLabel}" data-values="{CTSM.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTSM.total}</strong>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><i class="fa fa-line-chart fa-fw"></i> {CTSY.caption}</div>
    <div class="panel-body">
        <canvas style="max-width:100%" id="canvas_year" data-chart-type="bar" data-xtitle="{LANG.year}" data-ytitle="{LANG.access_times}" data-bg="54, 162, 235" data-border="54, 162, 235" data-labels="{CTSY.dataLabel}" data-values="{CTSY.dataValue}"></canvas>
    </div>
    <div class="panel-footer">
        {LANG.hits_total}: <strong>{CTSY.total}</strong>
    </div>
</div>

<div class="table-responsive margin-bottom">
    <table summary="{LANG.statbycountry}" class="table table-bordered">
        <tbody>
            <tr class="bg-light">
                <th>{LANG.country}</th>
                <th class="hidden-xs">{LANG.last_visit}</th>
                <th colspan="2" class="text-center">{LANG.hits}</th>
            </tr>
            <!-- BEGIN: ctloop -->
            <tr>
                <td><span class="label label-default margin-right-sm">{CTLOOP.key}</span>{CTLOOP.name}</td>
                <td class="hidden-xs">{CTLOOP.last_visit}</td>
                <td class="text-right" style="width: 1%">{CTLOOP.count_format}</td>
                <td style="width:35%;min-width:200px">
                    <!-- BEGIN: progress -->
                    <div class="progress margin-top-sm" style="height: 10px;margin-bottom:0">
                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{CTLOOP.proc}%;" aria-valuenow="{CTLOOP.proc}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!-- END: progress -->
                </td>
            </tr>
            <!-- END: ctloop -->
            <!-- BEGIN: ctot -->
            <tr>
                <td>{LANG.others}</td>
                <td class="hidden-xs"></td>
                <td class="text-right" style="width: 1%">{CTSC.others}</td>
                <td style="width:35%;min-width:200px"><a href="{CTSC.others_url}">{LANG.viewall}</a></td>
            </tr>
            <!-- END: ctot -->
        </tbody>
        <caption class="bg-primary padding-top padding-bottom padding-left padding-right"><i class="fa fa-line-chart fa-fw me-1"></i> {LANG.statbycountry}</caption>
    </table>
</div>

<div class="table-responsive margin-bottom">
    <table summary="{LANG.statbybrowser}" class="table table-bordered">
        <tbody>
            <tr class="bg-light">
                <th>{LANG.browser}</th>
                <th class="hidden-xs">{LANG.last_visit}</th>
                <th colspan="2" class="text-center">{LANG.hits}</th>
            </tr>
            <!-- BEGIN: brloop -->
            <tr>
                <td>{BRLOOP.name}</td>
                <td class="hidden-xs">{BRLOOP.last_visit}</td>
                <td class="text-right" style="width: 1%">{BRLOOP.count_format}</td>
                <td style="width:35%;min-width:200px">
                    <!-- BEGIN: progress -->
                    <div class="progress margin-top-sm" style="height: 10px;margin-bottom:0">
                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{BRLOOP.proc}%;" aria-valuenow="{BRLOOP.proc}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!-- END: progress -->
                </td>
            </tr>
            <!-- END: brloop -->
            <!-- BEGIN: brot -->
            <tr>
                <td>{LANG.others}</td>
                <td class="hidden-xs"></td>
                <td class="text-right" style="width: 1%">{CTSB.others}</td>
                <td style="width:35%;min-width:200px"><a href="{CTSB.others_url}">{LANG.viewall}</a></td>
            </tr>
            <!-- END: brot -->
        </tbody>
        <caption class="bg-primary padding-top padding-bottom padding-left padding-right"><i class="fa fa-line-chart fa-fw me-1"></i> {LANG.statbybrowser}</caption>
    </table>
</div>

<div class="table-responsive margin-bottom">
    <table summary="{LANG.statbyos}" class="table table-bordered">
        <tbody>
            <tr class="bg-light">
                <th>{LANG.os}</th>
                <th class="hidden-xs">{LANG.last_visit}</th>
                <th colspan="2" class="text-center">{LANG.hits}</th>
            </tr>
            <!-- BEGIN: osloop -->
            <tr>
                <td>{OSLOOP.name}</td>
                <td class="hidden-xs">{OSLOOP.last_visit}</td>
                <td class="text-right" style="width:1%">{OSLOOP.count_format}</td>
                <td style="width:35%;min-width:200px">
                    <!-- BEGIN: progress -->
                    <div class="progress margin-top-sm" style="height: 10px;margin-bottom:0">
                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{OSLOOP.proc}%;" aria-valuenow="{OSLOOP.proc}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!-- END: progress -->
                </td>
            </tr>
            <!-- END: osloop -->
            <!-- BEGIN: osot -->
            <tr>
                <td>{LANG.others}</td>
                <td class="hidden-xs"></td>
                <td class="text-right" style="width:1%">{CTSO.others}</td>
                <td style="width:35%;min-width:200px"><a href="{CTSO.others_url}">{LANG.viewall}</a></td>
            </tr>
            <!-- END: osot -->
        </tbody>
        <caption class="bg-primary padding-top padding-bottom padding-left padding-right"><i class="fa fa-line-chart fa-fw me-1"></i> {LANG.statbyos}</caption>
    </table>
</div>
<!-- END: main -->