<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td class="text-nowrap" style="width:1%">{LANG.feedback_phone}</td>
                    <td>
                        <select class="form-control" name="feedback_phone" style="width: fit-content;">
                            <!-- BEGIN: feedback_phone -->
                            <option value="{PHONE.val}" {PHONE.sel}>{PHONE.title}</option>
                            <!-- END: feedback_phone -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap" style="width:1%">{LANG.feedback_address}</td>
                    <td>
                        <select class="form-control" name="feedback_address" style="width: fit-content;">
                            <!-- BEGIN: feedback_address -->
                            <option value="{ADDRESS.val}" {ADDRESS.sel}>{ADDRESS.title}</option>
                            <!-- END: feedback_address -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap" style="width:1%">{LANG.config_sendcopymode}</td>
                    <td>
                        <select class="form-control" name="sendcopymode" id="sendcopymode" style="width: fit-content;">
                            <!-- BEGIN: sendcopymode -->
                            <option value="{SENDCOPYMODE.key}" {SENDCOPYMODE.selected}>{SENDCOPYMODE.title}</option>
                            <!-- END: sendcopymode -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap" style="width:1%">{LANG.silent_mode}</td>
                    <td>
                        <input type="checkbox" name="silent_mode" value="1" {DATA.silent_mode} />
                        <span>{LANG.silent_mode_note}</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap" style="width:1%">{LANG.admin_content}</td>
                    <td>{DATA.bodytext}</td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->