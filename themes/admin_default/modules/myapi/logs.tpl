<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{ASSETS_STATIC_URL}/js/select2/select2.min.css">
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/select2/select2.min.js"></script>
<script src="{ASSETS_LANG_STATIC_URL}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div id="logs" data-page-url="{PAGE_URL}">
    <form method="get" action="{INDEX_PAGE}">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" title="{LANG.api_role}"><i class="fa fa-object-group"></i></span>
                        <select class="form-control role-id" name="role_id" style="width: 100%;">
                            <option value="0">{LANG.api_role_select}</option>
                            <!-- BEGIN: api_role -->
                            <option value="{ROLE.role_id}" {ROLE.sel}>{ROLE.role_title}</option>
                            <!-- END: api_role -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" title="API"><i class="fa fa-terminal"></i></span>
                        <select class="form-control command" name="command" style="width: 100%;">
                            <option value="">{LANG.api_select}</option>
                            <!-- BEGIN: command -->
                            <option value="{COMMAND.val}" {COMMAND.sel}>{COMMAND.val}</option>
                            <!-- END: command -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" title="{LANG.api_role_object}"><i class="fa fa-user"></i></span>
                        <select class="form-control userid" name="userid" style="width: 100%;" data-placeholder="{LANG.api_role_object}">
                            <!-- BEGIN: userid -->
                            <option value="{GET_DATA.userid}" selected="selected">{GET_DATA.username}</option>
                            <!-- END: userid -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" title="{LANG.fromdate}"><i class="fa fa-calendar"></i></span>
                        <input type="text" class="form-control fromdate" name="fromdate" value="{GET_DATA.fromdate}" maxlength="10" placeholder="{LANG.fromdate}" />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon" title="{LANG.todate}"><i class="fa fa-calendar"></i></span>
                        <input type="text" class="form-control todate" name="todate" value="{GET_DATA.todate}" maxlength="10" placeholder="{LANG.todate}" />
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">{LANG.filter_logs}</button>
                </div>
            </div>
        </div>
    </form>
    <!-- BEGIN: loglist -->
    <div class="table-responsive m-bottom">
        <table class="table table-bordered table-striped list" data-delete-confirm="{LANG.log_del_confirm}">
            <thead class="bg-primary">
                <!-- BEGIN: manuall_del_log1 --><th style="width: 1%;"><input type="checkbox" class="form-control checkall" /></th><!-- END: manuall_del_log1 -->
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.log_time}</th>
                <th class="text-center text-nowrap">{LANG.api_role}</th>
                <th class="text-center text-nowrap">API</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.api_role_object}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.log_ip}</th>
                <!-- BEGIN: manuall_del_log2 --><th style="width: 1%;"></th><!-- END: manuall_del_log2 -->
            </thead>
            <tbody>
                <!-- BEGIN: log -->
                <tr class="item" data-id="{LOG.id}">
                    <!-- BEGIN: manuall_del_log3 --><td style="width: 1%;"><input type="checkbox" class="form-control checkitem" /></td><!-- END: manuall_del_log3 -->
                    <td class="text-center" style="width: 1%;">{LOG.log_time}</td>
                    <td>{LOG.role_title} ({LANG.api_role_type}: {LOG.role_type}, {LANG.api_role_object}: {LOG.role_object})</td>
                    <td class="text-center text-nowrap" style="width: 1%;">{LOG.command}</td>
                    <td class="text-center text-nowrap" style="width: 1%;">{LOG.username}</td>
                    <td class="text-center" style="width: 1%;">{LOG.log_ip}</td>
                    <!-- BEGIN: manuall_del_log4 --><td><button type="button" class="btn btn-default log-del"><i class="fa fa-trash-o"></i> {GLANG.delete}</button></td><!-- END: manuall_del_log4 -->
                </tr>
                <!-- END: log -->
            </tbody>
            <!-- BEGIN: manuall_del_log5 -->
            <tfoot>
                <tr>
                    <td style="width: 1%;"><input type="checkbox" class="form-control checkall" /></td>
                    <td colspan="6">
                        <button type="button" class="btn btn-default log-multidel"><i class="fa fa-trash-o"></i> {LANG.del_selected}</button>
                        <button type="button" class="btn btn-default log-delall"><i class="fa fa-trash-o"></i> {LANG.del_all}</button>
                    </td>
                </tr>
            </tfoot>
            <!-- END: manuall_del_log5 -->
        </table>
    </div>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
    <!-- END: loglist -->
</div>
<!-- END: main -->