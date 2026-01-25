<!-- BEGIN: main-->
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-plug" aria-hidden="true"></i> <strong>{LANG.plugin_integrated}</strong>
    </div>
    <div class="panel-body">
        <p>{LANG.plugin_note}</p>
        <div class="m-bottom">
            <form action="{NV_BASE_ADMINURL}index.php" method="get" class="form-inline" id="formSearchPlugin">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
                <div class="form-group">
                    <label>{LANG.plugin_viewarea}: </label>
                    <select name="a" class="form-control">
                        <option value="">--</option>
                        <!-- BEGIN: select_hook -->
                        <option value="{AREA.key}" {AREA.selected}>{AREA.key}</option>
                        <!-- END: select_hook -->
                    </select>
                </div>
            </form>
        </div>
        <!-- BEGIN: note_order-->
        <div class="text-info">{LANG.plugin_note_order}.</div>
        <!-- END: note_order-->
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-panel mb-0">
            <thead>
                <tr>
                    <!-- BEGIN: col_weight -->
                    <th class="text-center text-nowrap" style="width:10%;">{LANG.plugin_number}</th>
                    <!-- END: col_weight -->
                    <th>{LANG.plugin_area}</th>
                    <th class="hidden-xs">{LANG.plugin_file}</th>
                    <th class="hidden-xs" style="width:15%;">{LANG.plugin_type}</th>
                    <th class="text-center text-nowrap" style="width:1%;">{LANG.plugin_func}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <!-- BEGIN: weight -->
                    <td class="text-center" style="vertical-align: middle;">
                        <select id="weight_{ROW.pid}" class="form-control" data-toggle="change_plugin_weight" data-pid="{ROW.pid}">
                            <!-- BEGIN: loop -->
                            <option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.key}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                    <!-- END: weight -->
                    <td style="vertical-align: middle;">
                        <strong>{ROW.hook_module}{ROW.plugin_area}</strong>
                        <div class="visible-xs-block">
                            {LANG.plugin_file}: {ROW.plugin_file}<br/>
                            {LANG.plugin_type}: {ROW.type}
                        </div>
                    </td>
                    <td class="hidden-xs" style="vertical-align: middle;">{ROW.plugin_file}</td>
                    <td class="hidden-xs" style="vertical-align: middle;">{ROW.type}</td>
                    <td class="text-center text-nowrap" style="vertical-align: middle;">
                        <!-- BEGIN: delete -->
                        <a href="#" class="btn btn-xs btn-danger" data-toggle="nv_del_plugin" data-pid="{ROW.pid}"><i class="fa fa-trash-o"></i> {LANG.isdel}</a>
                        <!-- END: delete -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
</div>

<!-- BEGIN: plugin_available -->
<div class="panel panel-primary">
    <div class="panel-heading">
        <i class="fa fa-list" aria-hidden="true"></i> <strong>{LANG.plugin_available}</strong>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-panel mb-0">
            <thead>
                <tr>
                    <th>{LANG.plugin_area}</th>
                    <th class="hidden-xs">{LANG.plugin_file}</th>
                    <th class="text-center text-nowrap hidden-xs" style="width:15%;">{LANG.plugin_type}</th>
                    <th class="text-center text-nowrap hidden-xs" style="width:15%;">{LANG.act}</th>
                    <th class="text-center text-nowrap" style="width:1%;">{LANG.plugin_func}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: row -->
                <tr>
                    <td style="vertical-align: middle;">
                        <strong>{ROW.area}</strong>
                        <div class="visible-xs-block">
                            {LANG.plugin_file}: {ROW.file}<br/>
                            {LANG.plugin_type}: {ROW.type}<br/>
                            {LANG.act}: {ROW.status}
                        </div>
                    </td>
                    <td class="hidden-xs" style="vertical-align: middle;">{ROW.file}</td>
                    <td class="text-center text-nowrap hidden-xs" style="vertical-align: middle;">{ROW.type}</td>
                    <td class="text-center text-nowrap hidden-xs" style="vertical-align: middle;">{ROW.status}</td>
                    <td class="text-center text-nowrap" style="vertical-align: middle;">
                        <!-- BEGIN: plugin_integrate -->
                        <a href="#" data-click="plintegrate" data-hkey="{HOOK_KEY}" data-fkey="{FILE_KEY}" data-hm="{ROW.hook_module}" data-rm="{ROW.receive_module}" class="btn btn-xs btn-primary"><i class="fa fa-cog"></i> {LANG.plugin_integrate}</a>
                        <!-- END: plugin_integrate -->
                    </td>
                </tr>
                <!-- END: row -->
            </tbody>
        </table>
    </div>
</div>
<!-- START FORFOOTER -->
<div class="modal fade" tabindex="-1" role="dialog" id="mdPluginConfig" data-backdrop="static">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><strong data-area="title"></strong></h4>
            </div>
            <div class="modal-body pb-0">
                <div class="form-group" data-area="hook_module">
                    <label for="mdPluginConfigH">{LANG.plugin_choose_hook_module}:</label>
                    <select class="form-control" id="mdPluginConfigH" name="hook_module">
                    </select>
                </div>
                <div class="form-group" data-area="receive_module">
                    <label for="mdPluginConfigR">{LANG.plugin_choose_receive_module}:</label>
                    <select class="form-control" id="mdPluginConfigR" name="receive_module">
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-primary" data-toggle="submitIntegratePlugin">{LANG.plugin_integrate}</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END FORFOOTER -->
<!-- END: plugin_available -->
<!-- END: main-->
