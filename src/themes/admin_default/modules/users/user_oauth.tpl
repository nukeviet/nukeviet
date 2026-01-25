<!-- BEGIN: empty -->
<div class="alert alert-info">{LANG.user_oauthmanager_empty}</div>
<!-- END: empty -->

<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong>{LANG.user_oauthmanager_list}</strong>
    </div>
    <table class="table table-bordered table-condensed table-hover table-striped">
        <thead>
            <tr>
                <th class="w200 twostepp">{LANG.user_oauthmanager_gate}</th>
                <th>{LANG.user_oauthmanager_email}</th>
                <th class="w100"></th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: oauth -->
            <tr>
                <td class="twostepp">{OAUTH.openid}</td>
                <td>{OAUTH.email_or_id}</td>
                <td class="text-center">
                    <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="nv_del_oauthone('{OAUTH.opid}', {USERID});"><i class="fa fa-trash-o"></i>&nbsp;{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: oauth -->
        </tbody>
    </table>
    <div class="panel-footer text-right">
        <a href="javascript:void(0);" class="btn btn-danger btn-xs" onclick="nv_del_oauthall({USERID});"><i class="fa fa-trash-o"></i>&nbsp;{LANG.user_oauthmanager_deleteall}</a>
    </div>
</div>
<!-- END: main -->