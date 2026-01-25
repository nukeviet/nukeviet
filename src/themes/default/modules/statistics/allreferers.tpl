<!-- BEGIN: main -->
<div class="table-responsive margin-bottom">
    <table summary="{LANG.statbyreferer}" class="table table-bordered">
        <tbody>
            <tr>
                <th>{LANG.referer}</th>
                <th class="hidden-xs">{LANG.last_visit}</th>
                <th colspan="2" class="text-center">{LANG.hits}</th>
                <th></th>
            </tr>
            <!-- BEGIN: loop -->
            <tr>
                <td><a target="_blank" href="http://{LOOP.key}">{LOOP.key}</a></td>
                <td class="hidden-xs">{LOOP.last_visit}</td>
                <td class="text-right" style="width: 1%">{LOOP.count_format}</td>
                <td style="width:35%;min-width:200px">
                    <!-- BEGIN: progress -->
                    <div class="progress margin-top-sm" style="height: 10px;">
                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{LOOP.proc}%;" aria-valuenow="{LOOP.proc}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!-- END: progress -->
                </td>
                <td class="text-right text-nowrap" style="width: 1%"><a href="{LOOP.bymonth_link}" title="{LANG.statbymonth2}"><i class="fa fa-calendar"></i></a></td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <caption class="bg-primary padding-top padding-bottom padding-left padding-right"><i class="fa fa-line-chart fa-fw"></i> {LANG.statbyreferer}</caption>
    </table>
</div>
<!-- BEGIN: gp -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: gp -->
<!-- END: main -->