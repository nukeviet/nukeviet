<!-- BEGIN: main -->
<div class="row">
    <div class="col-lg-16">
        <form method="post" action="{FORM_ACTION}" class="send-form">
            <input name="save" type="hidden" value="1" />
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-center"><button type="submit" class="btn btn-primary">{LANG.bt_send_row_title}</button></td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td class="text-nowrap" style="width:1%">{LANG.title_send_title}</td>
                            <td>
                                <input name="title" type="text" value="" class="form-control" />
                            </td>
                        </tr>
                        <tr>
                            <td class="text-nowrap" style="width:1%">{GLANG.email}</td>
                            <td>
                                <input name="email" type="email" value="" class="form-control" />
                                <div class="help-block">{LANG.to_note}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">{MESS_CONTENT}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->