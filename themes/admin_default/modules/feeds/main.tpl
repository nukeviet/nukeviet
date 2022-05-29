<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" data-toggle="formSubmit">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td class="text-right"><strong>{LANG.rss_logo}</strong></td>
                    <td><input class="w300 form-control pull-left" type="text" name="rss_logo" id="rss_logo" value="{DATA.rss_logo}" style="margin-right: 5px" /><input type="button" value="Browse server" name="selectimg" class="btn btn-info" data-path="{UPLOADS_DIR_USER}" /></td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.atom_logo}</strong></td>
                    <td><input class="w300 form-control pull-left" type="text" name="atom_logo" id="atom_logo" value="{DATA.atom_logo}" style="margin-right: 5px" /><input type="button" value="Browse server" name="selectimg" class="btn btn-info" data-path="{UPLOADS_DIR_USER}" /></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>{LANG.content}</strong>
                        <div>
                            {DATA.contents}
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">
                        <input name="save" type="hidden" value="1" />
                        <button type="submit" class="btn btn-primary">{LANG.save}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->