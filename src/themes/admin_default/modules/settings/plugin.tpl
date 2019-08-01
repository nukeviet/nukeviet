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
                <th class="w200">{LANG.plugin_type}</th>
                <th class="w150 text-center">{LANG.plugin_func}</th>
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
                <td>{ROW.hook_module}{ROW.plugin_area}</td>
                <td>{ROW.plugin_file}</td>
                <td>
                    <!-- BEGIN: type_sys -->{LANG.plugin_type_sys}<!-- END: type_sys -->
                    <!-- BEGIN: type_module -->{LANG.plugin_type_module}:{ROW.plugin_module_name}<!-- END: type_module -->
                </td>
                <td class="text-center">
                    <!-- BEGIN: delete -->
                    <a onclick="nv_del_plugin({ROW.pid});" href="javascript:void(0);" class="btn btn-danger btn-xs"><i class="fa fa-trash fa-fw"></i>{LANG.isdel}</a>
                    <!-- END: delete -->
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
            <th class="w200">{LANG.plugin_type}</th>
            <th class="w200">{LANG.act}</th>
            <th class="text-center w150">{LANG.plugin_func}</th>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.hook_module}{ROW.parea}</td>
                <td>{ROW.file}</td>
                <td>{ROW.type}</td>
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

<!-- BEGIN: setting -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form method="post" action="{FORM_ACTION}">
    <!-- BEGIN: no_hook_module -->
    <div class="alert alert-danger">{NO_HOOK_MODULE}</div>
    <!-- END: no_hook_module -->
    <!-- BEGIN: no_receive_module -->
    <div class="alert alert-danger">{NO_RECEIVE_MODULE}</div>
    <!-- END: no_receive_module -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <!-- BEGIN: hook_module -->
                <tr>
                    <td class="w200"><strong>{LANG.plugin_choose_hook_module}</strong></td>
                    <td>
                        <select class="form-control w300" name="hook_module">
                            <!-- BEGIN: loop -->
                            <option value="{HOOK_MODULE.key}"{HOOK_MODULE.selected}>{HOOK_MODULE.title}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: hook_module -->
                <!-- BEGIN: receive_module -->
                <tr>
                    <td class="w200"><strong>{LANG.plugin_choose_receive_module}</strong></td>
                    <td>
                        <select class="form-control w300" name="receive_module">
                            <!-- BEGIN: loop -->
                            <option value="{RECEIVE_MODULE.key}"{RECEIVE_MODULE.selected}>{RECEIVE_MODULE.title}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: receive_module -->
                <!-- BEGIN: submit -->
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <input type="submit" name="submit" class="btn btn-primary" value="{GLANG.save}"/>
                    </td>
                </tr>
                <!-- END: submit -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: setting -->
