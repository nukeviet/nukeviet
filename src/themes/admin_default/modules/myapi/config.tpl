<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <td style="width: 25%;"><strong>{LANG.remote_api_access}</strong></td>
                <td style="width: 75%;"><label><input type="checkbox" name="remote_api_access" value="1" {CHECKED_REMOTE_API_ACCESS} /> {LANG.remote_api_access_help}</label></td>
            </tr>
            <tr>
                <td><strong>{LANG.api_check_time}</strong></td>
                <td class="form-inline">
                    <div class="input-group">
                        <input type="number" name="api_check_time" value="{DATA.api_check_time}" min="1" max="1440" class="form-control">
                        <span class="input-group-addon">{GLANG.sec}</span>
                    </div>
                </td>
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
