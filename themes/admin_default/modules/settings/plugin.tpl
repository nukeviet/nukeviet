<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.plugin_note}</div>
<form action="{NV_BASE_ADMINURL}index.php" method="get" id="formSearchPlugin">
    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}"/>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}"/>
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}"/>
    <div class="form-group form-inline">
        {LANG.plugin_viewarea}:
        <select name="a" class="form-control w250">
            <option value="">--</option>
            <!-- BEGIN: plugin_area -->
            <option value="{PLUGIN_AREA}"{PLUGIN_AREA_SELECTED}>{PLUGIN_AREA}</option>
            <!-- END: plugin_area -->
        </select>
    </div>
</form>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption><i class="fa fa-fw fa-file"></i>{LANG.plugin_integrated}</caption>
        <thead>
            <tr class="text-center">
                <!-- BEGIN: col_weight -->
                <th class="w100">{LANG.plugin_number}</th>
                <!-- END: col_weight -->
                <th class="w300">{LANG.plugin_area}</th>
                <th>{LANG.plugin_file}</th>
                <th class="w200 text-center">{LANG.plugin_func}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <!-- BEGIN: weight -->
                <td>
                    <select id="weight_{ROW.pid}" onchange="nv_change_plugin_weight('{ROW.pid}');" class="form-control">
                        <!-- BEGIN: loop -->
                        <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: loop -->
                    </select>
                </td>
                <!-- END: weight -->
                <td>{ROW.plugin_area}</td>
                <td>{ROW.plugin_file}</td>
                <td class="text-center">
                    <a onclick="nv_del_plugin({ROW.pid});" href="javascript:void(0);" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-fw"></i>{LANG.isdel}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>

<!-- BEGIN: available -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption><i class="fa fa-fw fa-file"></i>{LANG.plugin_available}</caption>
        <thead>
            <th class="w300">{LANG.plugin_area}</th>
            <th>{LANG.plugin_file}</th>
            <th class="w200">{LANG.act}</th>
            <th class="text-center w200">{LANG.plugin_func}</th>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.parea}</td>
                <td>{ROW.file}</td>
                <td>
                    <!-- BEGIN: status_ok -->{LANG.plugin_status_ok}<!-- END: status_ok -->
                    <!-- BEGIN: status_error -->{LANG.plugin_status_error}<!-- END: status_error -->
                </td>
                <td class="text-center">
                    <!-- BEGIN: integrate -->
                    <a href="{LINK_INTEGRATE}" class="btn btn-xs btn-default"><i class="fa fa-cog fa-fw"></i>{LANG.plugin_integrate}</a>
                    <!-- END: integrate -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: available -->

<script type="text/javascript">
$(function() {
    $('[name="a"]').change(function() {
        $('#formSearchPlugin').submit();
    });
});
</script>
<!-- END: main -->