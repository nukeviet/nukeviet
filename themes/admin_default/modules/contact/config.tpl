<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col style="width:30%" />
                <col style="width:70%" />
            </colgroup>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input type="hidden" name="save" value="1"><input type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <th class="text-right">{LANG.config_sendcopymode}</th>
                    <td ><select class="form-control w200" name="sendcopymode" id="sendcopymode">
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