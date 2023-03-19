<!-- BEGIN: main -->
<form class="form-inline" role="form" action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col style="width: 320px;" />
                <col />
            </colgroup>
            <tfoot>
                <tr>
                    <td class="text-center" colspan="2"><input type="hidden" name="checkss" value="{DATA.checkss}" /><input type="hidden" name="save" value="1"><input class="btn btn-primary w100" type="submit" value="{LANG.save}"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td><strong>Google Client ID</strong></td>
                    <td><input type="text" class="form-control" style="width: 500px;" name="oauth_client_id" value="{DATA.oauth_client_id}" /></td>
                </tr>
            <tbody>
        </table>
    </div>
</form>
<!-- END: main -->