<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-body">
        <p class="2step-status">
            <!-- BEGIN: code_off -->{LANG.2step_code_off}<!-- END: code_off -->
            <!-- BEGIN: code_on -->{LANG.2step_code_on}<!-- END: code_on -->
        </p>
        <!-- BEGIN: code_self_manager -->
        <a href="{CODE_SELF_MANAGER}" class="btn btn-primary">{GLANG.manage}</a>
        <!-- END: code_self_manager -->
        <!-- BEGIN: code_manager -->
        <a href="{CODE_MANAGER}" class="btn btn-primary">{GLANG.manage}</a>
        <!-- END: code_manager -->
    </div>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.2step_oauth}</strong>
    </div>
    <!-- BEGIN: oauth_empty -->
    <div class="panel-body">
        <div class="alert alert-info mb-0">{LANG.2step_oauth_empty}</div>
    </div>
    <!-- END: oauth_empty -->
    <!-- BEGIN: oauth_data -->
    <div class="table-responsive">
        <table class="table table-bordered table-condensed table-hover table-striped">
            <thead>
                <tr>
                    <th class="text-nowrap" style=" width:30%;">{LANG.2step_oauth_gate}</th>
                    <th class="text-nowrap" style=" width:40%;">{LANG.2step_oauth_email_or_id}</th>
                    <th class="text-nowrap" style=" width:20%;">{LANG.2step_addtime}</th>
                    <th class="text-nowrap" style=" width:10%;"></th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: oauth -->
                <tr>
                    <td>{OAUTH.oauth_server}</td>
                    <td>{OAUTH.email_or_id}</td>
                    <td class="text-nowrap">{OAUTH.addtime}</td>
                    <td class="text-center text-nowrap">
                        <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="nv_del_oauthone('{OAUTH.id}', {USERID}, '{TOKEND}');"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: oauth -->
            </tbody>
        </table>
    </div>
    <!-- END: oauth_data -->
    <div class="panel-footer">
        <div class="row">
            <div class="col-sm-14">
                <!-- BEGIN: add_facebook -->
                <a href="{LINK_FACEBOOK}" class="btn btn-facebook btn-sm" ><i class="fa fa-facebook-official" aria-hidden="true"></i> {LANG.2step_add_facebook}</a>
                <!-- END: add_facebook -->
                <!-- BEGIN: add_google -->
                <a href="{LINK_GOOGLE}" class="btn btn-google btn-sm" ><i class="fa fa-google" aria-hidden="true"></i> {LANG.2step_add_google}</a>
                <!-- END: add_google -->
                <!-- BEGIN: add_zalo -->
                <a href="{LINK_ZALO}" class="btn btn-zalo btn-sm" ><i class="fa fa-zalo" aria-hidden="true"></i> {LANG.2step_add_zalo}</a>
                <!-- END: add_zalo -->
            </div>
            <div class="col-sm-10">
                <!-- BEGIN: delete_btn -->
                <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="nv_del_oauthall({USERID}, '{TOKEND}');"><i class="fa fa-trash-o"></i> {LANG.2step_delete_all}</a>
                <!-- END: delete_btn -->
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
