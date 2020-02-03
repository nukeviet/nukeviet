<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-nowrap" style="width: 20%;">{LANG.login}</th>
                <th class="text-nowrap" style="width: 25%;">{LANG.email}</th>
                <th class="text-nowrap" style="width: 15%;">{LANG.position}</th>
                <th class="text-nowrap" style="width: 15%;">{LANG.lev}</th>
                <th class="text-nowrap" style="width: 15%;">{LANG.is_suspend}</th>
                <th class="text-nowrap" style="width: 10%;">{LANG.adminip_funcs}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td><img alt="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /><a href="{DATA.link}" title="{DATA.full_name}"><strong>{DATA.login}</strong></a></td>
                <td>{DATA.email}</td>
                <td>{DATA.position}</td>
                <td>{DATA.lev}</td>
                <td>{DATA.is_suspend}</td>
                <td class="text-center">
                    <!-- BEGIN: tools -->
                    <div class="btn-group text-nowrap">
                        <!-- BEGIN: edit -->
                        <a href="{EDIT_HREF}" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> {EDIT_NAME}</a>
                        <!-- END: edit -->
                        <!-- BEGIN: dropdown -->
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <!-- BEGIN: 2step --><li><a href="{2STEP_HREF}"><i class="fa fa-key fa-fw"></i> {2STEP_NAME}</a></li><!-- END: 2step -->
                            <!-- BEGIN: suspend --><li><a href="{SUSPEND_HREF}" class="text-warning"><i class="fa fa-times-circle-o fa-fw"></i> {SUSPEND_NAME}</a></li><!-- END: suspend -->
                            <!-- BEGIN: del --><li><a href="{DEL_HREF}" class="text-danger"><i class="fa fa-trash-o fa-fw"></i> {DEL_NAME}</a></li><!-- END: del -->
                        </ul>
                        <!-- END: dropdown -->
                    </div>
                    <!-- END: tools -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->
