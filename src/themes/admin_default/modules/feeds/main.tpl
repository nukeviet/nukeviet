<!-- BEGIN: main -->
<form action="{FORM_ACTION}" method="post" data-toggle="formSubmit">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <tbody>
                <tr>
                    <td class="text-right"><strong>{LANG.rss_logo}</strong></td>
                    <td>
                        <div class="w300 input-group mb-0">
                            <input class="form-control" type="text" name="rss_logo" id="rss_logo" value="{DATA.rss_logo}" />
                            <span class="input-group-btn">
                                <button type="button" data-toggle="selectfile" data-target="rss_logo" data-path="{UPLOADS_DIR_USER}" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-right"><strong>{LANG.atom_logo}</strong></td>
                    <td>
                        <div class="w300 input-group mb-0">
                            <input class="form-control" type="text" name="atom_logo" id="atom_logo" value="{DATA.atom_logo}" />
                            <span class="input-group-btn">
                                <button type="button" data-toggle="selectfile" data-target="atom_logo" data-path="{UPLOADS_DIR_USER}" data-type="image" class="btn btn-info" title="{GLANG.browse_image}"><em class="fa fa-folder-open-o"></em></button>
                            </span>
                        </div>
                    </td>
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