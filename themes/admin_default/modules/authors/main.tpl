<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" id="aid{ID}">
        <col span="2" style="width: 50%"/>
        <thead>
            <tr>
                <th colspan="2">
                    <div class="pull-right">
                        <!-- BEGIN: 2step -->
                        <a class="btn btn-primary btn-xs" href="{2STEP_HREF}">{2STEP_NAME}</a>
                        <!-- END: 2step -->
                        <!-- BEGIN: edit -->
                        <a class="btn btn-primary btn-xs" href="{EDIT_HREF}">{EDIT_NAME}</a>
                        <!-- END: edit -->
                        <!-- BEGIN: suspend -->
                        <a class="btn btn-primary btn-xs" href="{SUSPEND_HREF}">{SUSPEND_NAME}</a>
                        <!-- END: suspend -->
                        <!-- BEGIN: del -->
                        <a class="btn btn-primary btn-xs" href="{DEL_HREF}">{DEL_NAME}</a>
                        <!-- END: del -->
                    </div>
                    <img class="refresh" alt="{OPTION_LEV}" src="{NV_BASE_SITEURL}themes/{NV_ADMIN_THEME}/images/admin{THREAD_LEV}.png" width="38" height="18" /> {CAPTION}
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: option_loop -->
            <tr>
                <td>{VALUE0}</td>
                <td>{VALUE1}</td>
            </tr>
            <!-- END: option_loop -->
        </tbody>
    </table>
</div>
<!-- END: loop -->
<!-- END: main -->
