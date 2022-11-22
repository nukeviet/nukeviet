<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <td><strong>{LANG.remote_api_access}</strong></td>
                <td><label><input type="checkbox" name="remote_api_access" value="1" {CHECKED_REMOTE_API_ACCESS} /> {LANG.remote_api_access_help}</label></td>
            </tr>
            <tr>
                <td><strong>{LANG.remote_api_log}</strong></td>
                <td><input type="checkbox" name="remote_api_log" value="1" {CHECKED_REMOTE_API_LOG} /></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-center">
                    <input type="hidden" name="checkss" value="{CHECKSS}">
                    <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                </td>
            </tr>
        </tfoot>
    </table>
</form>
<!-- END: main -->