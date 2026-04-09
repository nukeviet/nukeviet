<!-- BEGIN: main -->
<div class="table-responsive margin-bottom">
    <table summary="{LANG.statbybrowser}" class="table table-bordered">
        <tbody>
            <tr class="bg-gainsboro">
                <th>{LANG.browser}</th>
                <th class="hidden-xs">{LANG.last_visit}</th>
                <th colspan="2" class="text-center">{LANG.hits}</th>
            </tr>
            <!-- BEGIN: loop -->
            <tr>
                <td>{LOOP.name}</td>
                <td class="hidden-xs">{LOOP.last_visit}</td>
                <td class="text-right" style="width: 1%">{LOOP.count_format}</td>
                <td style="width:35%;min-width:200px">
                    <!-- BEGIN: progress -->
                    <div class="progress margin-top-sm" style="height: 10px;margin-bottom:0">
                        <div class="progress-bar progress-bar-warning" role="progressbar" style="width:{LOOP.proc}%;" aria-valuenow="{LOOP.proc}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <!-- END: progress -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
        <caption class="bg-primary padding-top padding-bottom padding-left padding-right"><i class="fa fa-line-chart fa-fw"></i> {LANG.statbybrowser}</caption>
    </table>
</div>
<!-- BEGIN: gp -->
<div class="text-center">
    {CTS.generate_page}
</div>
<!-- END: gp -->
<!-- END: main -->