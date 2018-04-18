<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input name="submit" type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <th class="text-right">{LANG.config_sendcopymode}</th>
                    <td ><select class="form-control" name="sendcopymode" id="sendcopymode">
                            <!-- BEGIN: sendcopymode -->
                            <option value="{SENDCOPYMODE.key}"{SENDCOPYMODE.selected}>{SENDCOPYMODE.title}</option>
                            <!-- END: sendcopymode -->
                    </select></td>
                </tr>
                <tr>
                    <th class="text-right">{LANG.content}</th>
                    <td>{DATA.bodytext}</td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->