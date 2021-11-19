<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <div class="table-responsive">
        <table class="table table-bordered">
            <col class="w200"/>
            <col class="w400"/>
            <tbody>
                <tr>
                    <td><strong>{LANG.zalo_official_account_id}</strong></td>
                    <td><input type="text" name="zaloOfficialAccountID" value="{DATA.zaloOfficialAccountID}" class="form-control" maxlength="50" /></td>
                    <td>{LANG.oa_create_note}</td>
                </tr>
                <tr>
                    <td><strong>{LANG.app_id}</strong></td>
                    <td><input type="text" name="zaloAppID" value="{DATA.zaloAppID}" class="form-control" maxlength="50" /></td>
                    <td rowspan="5">{LANG.app_note}</td>
                </tr>
                <tr>
                    <td><strong>{LANG.app_secret_key}</strong></td>
                    <td><input type="text" name="zaloAppSecretKey" value="{DATA.zaloAppSecretKey}" class="form-control" maxlength="50" /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.access_token}</strong></td>
                    <td><input id="access_token" type="text" value="{DATA.zaloOAAccessToken}" class="form-control" readonly/></td>
                </tr>
                <tr>
                    <td><strong>{LANG.refresh_token}</strong></td>
                    <td><input id="refresh_token" type="text" value="{DATA.zaloOARefreshToken}" class="form-control" readonly/></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <input type="hidden" name="func" value="settings" />
                        <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
                        <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;func=access_token_create" role="button" class="btn btn-default" data-toggle="access_token_create">{LANG.access_token_create}</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<script>
$(function() {
    $('[data-toggle=access_token_create]').on('click', function(e) {
        e.preventDefault();
        nv_open_browse($(this).attr('href'), "NVNB", 500, 500, "resizable=no,scrollbars=1,toolbar=no,location=no,status=no")
    })
});
</script>
<!-- END: main -->

<!-- BEGIN: isSuccess -->
<script>
    $(function() {
        $("#access_token", opener.document).val('{RESULT.access_token}');
        $("#refresh_token", opener.document).val('{RESULT.refresh_token}');
        window.close()
    });
</script>
<!-- END: isSuccess -->

<!-- BEGIN: isError -->
<script>
    $(function() {
        alert('{ERROR}');
        window.close()
    });
</script>
<!-- END: isError -->