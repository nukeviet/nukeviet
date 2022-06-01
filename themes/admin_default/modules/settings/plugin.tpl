<!-- BEGIN: main-->
<div class="panel panel-primary">
    <div class="panel-heading">
        {LANG.plugin_integrated}
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
                        <!-- BEGIN: plugin_area -->
                        <option value="{AREA.key}" {AREA.selected}>{AREA.key}</option>
                        <!-- END: plugin_area -->
                    </select>
                </div>
            </form>
        </div>
        <!-- BEGIN: note_plugin_order-->
        <p class="text-info">{LANG.plugin_note_order}.</p>
        <!-- END: note_plugin_order-->
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <!-- BEGIN: not_empty_plugin_area -->
                        <th style="width:5%;">{LANG.plugin_number}</th>
                        <!-- END: not_empty_plugin_area -->
                        <th style="width:30%;">{LANG.plugin_area}</th>
                        <th style="width:35%;">{LANG.plugin_file}</th>
                        <th style="width:15%;">{LANG.plugin_type}</th>
                        <th class="text-right text-nowrap" style="width:10%;">{LANG.plugin_func}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: plugin_db_loop -->
                    <tr>
                        <!-- BEGIN: not_empty_plugin_area -->
                        <td>
                            <select id="weight_{ROW.pid}" class="form-control" style="width:auto" data-toggle="change_plugin_weight" data-pid="{ROW.pid}">
                                <!-- BEGIN: weight -->
                                <option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.key}</option>
                                <!-- END: weight -->
                            </select>
                        </td>
                        <!-- END: not_empty_plugin_area -->
                        <td>{ROW.hook_module}{ROW.plugin_area}</td>
                        <td>{ROW.plugin_file}</td>
                        <td>{ROW.type}</td>
                        <td class="text-right text-nowrap">
                            <!-- BEGIN: plugin_del -->
                            <a href="#" class="btn btn-xs btn-danger" data-toggle="nv_del_plugin" data-pid="{ROW.pid}"><i class="fa fa-trash-o"></i> {LANG.isdel}</a>
                            <!-- END: plugin_del -->
                        </td>
                    </tr>
                    <!-- END: plugin_db_loop -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- BEGIN: plugin_available -->
<div class="panel panel-primary">
    <div class="panel-heading">
        {LANG.plugin_available}
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:25%;">{LANG.plugin_area}</th>
                        <th style="width:25%;">{LANG.plugin_file}</th>
                        <th style="width:25%;">{LANG.plugin_type}</th>
                        <th style="width:15%;">{LANG.act}</th>
                        <th class="text-right text-nowrap" style="width:10%;">{LANG.plugin_func}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: row -->
                    <tr>
                        <td>{ROW.area}</td>
                        <td>{ROW.file}</td>
                        <td>{ROW.type}</td>
                        <td>
                            <!-- BEGIN: status_ok --><span class="text-success">{LANG.plugin_status_ok}</span><!-- END: status_ok -->
                            <!-- BEGIN: status_error --><span class="text-danger">{LANG.plugin_status_error}</span><!-- END: status_error -->
                        </td>
                        <td class="text-right text-nowrap">
                            <!-- BEGIN: plugin_integrate -->
                            <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;plugin_file={ROW.file}&amp;rand={RAND}" class="btn btn-xs btn-primary"><i class="fa fa-cog"></i> {LANG.plugin_integrate}</a>
                            <!-- END: plugin_integrate -->
                        </td>
                    </tr>
                    <!-- END: row -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- BEGIN: plugin_available -->
<!-- END: main -->
<!-- BEGIN: new_plugin -->
<!-- BEGIN: is_error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: is_error -->
<form method="post" action="{FORM_ACTION}" autocomplete="off">
    <div class="panel panel-primary">
        <div class="panel-body">
            <!-- BEGIN: hook_mods_empty -->
            <div class="alert alert-danger">{NO_HOOK_MODULE}</div>
            <!-- END: hook_mods_empty -->
            <!-- BEGIN: receive_mods_empty -->
            <div class="alert alert-danger">{NO_RECEIVE_MODULE}</div>
            <!-- END: receive_mods_empty -->
            <!-- BEGIN: hook_mods_not_empty -->
            <div class="form-group row">
                <label class="col-sm-8 control-label" for="hook_module">{LANG.plugin_choose_hook_module}</label>
                <div class="col-sm-16">
                    <select class="form-control" id="hook_module" name="hook_module">
                        <!-- BEGIN: loop -->
                        <option value="{HOOK_MOD.key}" {HOOK_MOD.selected}>{HOOK_MOD.title}</option>
                        <!-- END: loop -->
                    </select>
                </div>
            </div>
            <!-- END: hook_mods_not_empty -->
            <!-- BEGIN: receive_mods_not_empty -->
            <div class="form-group row">
                <label class="col-sm-8 control-label" for="receive_module">{LANG.plugin_choose_receive_module}</label>
                <div class="col-sm-16">
                    <select class="form-control" id="receive_module" name="receive_module">
                        <!-- BEGIN: loop -->
                        <option value="{RECEIVE_MOD.key}" {RECEIVE_MOD.selected}>{RECEIVE_MOD.title}</option>
                        <!-- END: loop -->
                    </select>
                </div>
            </div>
            <!-- END: receive_mods_not_empty -->
            <!-- BEGIN: submit_allowed -->
            <div class="form-group row">
                <label class="col-sm-8 control-label"></label>
                <div class="col-sm-16">
                    <input type="hidden" name="save" value="1">
                    <button class="btn btn-primary" type="submit">{LANG.submit}</button>
                </div>
            </div>
            <!-- END: submit_allowed -->
        </div>
    </div>
</form>
<!-- END: new_plugin -->
