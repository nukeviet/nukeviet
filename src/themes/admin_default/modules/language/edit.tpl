<!-- BEGIN: main -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<div class="well">{LANG.nv_lang_note_edit}: {ALLOWED_HTML_LANG}</div>
<form action="{FORM_ACTION}" method="post" id="lang-edit-form">
    <input type="hidden" name="savedata" value="{NV_CHECK_SESSION}" />
    <input type="hidden" name="pozauthor[langtype]" data-key="langtype" value="{LANGTYPE}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <caption><i class="fa fa-file-o"></i> {LANG.nv_lang_module}: {EDIT_MODULE}, {LANG.nv_lang_area}: {MODULE_AREA}</caption>
            <colgroup>
                <col style="width: 1%;"/>
                <col style="width: 30%;"/>
                <col/>
                <col style="width: 1%;"/>
                <col style="width: 1%;"/>
            </colgroup>
            <thead class="bg-primary">
                <tr>
                    <th>{LANG.nv_lang_nb}</th>
                    <th>{LANG.nv_lang_key}</th>
                    <th colspan="3">{LANG.nv_lang_value}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: array_translator -->
                <tr>
                    <td>&nbsp;</td>
                    <td style="vertical-align: middle;">{ARRAY_TRANSLATOR.lang_key}</td>
                    <td colspan="3" style="vertical-align: middle;"><input type="text" value="{ARRAY_TRANSLATOR.value}" data-key="{ARRAY_TRANSLATOR.lang_key}" name="pozauthor[{ARRAY_TRANSLATOR.lang_key}]" data-sanitize-ignore="true" size="90" class="form-control" /></td>
                </tr>
                <!-- END: array_translator -->
            </tbody>
            <tbody class="counter" id="sortable">
                <!-- BEGIN: array_data -->
                <tr class="item">
                    <td class="text-center text-nowrap" style="padding-top:15px">
                        <input type="hidden" name="langid[]" value="{ARRAY_DATA.id}" />
                        <i class="fa fa-sort"></i>
                    </td>
                    <td>
                        <input type="text" value="{ARRAY_DATA.lang_key}" name="langkey[]" class="form-control alphanumeric" maxlength="50" style="min-width: 150px;" data-duplicate-error="{LANG.key_is_duplicate}" data-empty-error="{LANG.field_is_required}" />
                        <span class="invalid-feedback"></span>
                    </td>
                    <td>
                        <input type="text" value="{ARRAY_DATA.value}" name="langvalue[]" data-sanitize-ignore="true" class="form-control" style="min-width: 150px;" />
                    </td>
                    <td class="text-nowrap text-right">
                        <input type="hidden" name="isdel[]" value="0" />
                        <div class="checkbox delitem" style="margin-bottom:0">
                            <label style="margin-bottom: 0">
                                <input type="checkbox" class="del-item"> {GLANG.delete}
                            </label>
                        </div>
                        <button type="button" class="btn btn-default btn-sm del-new" style="display:none"><em class="fa fa-times"></em></button>
                    </td>
                    <td class="text-nowrap">
                        <button type="button" class="btn btn-default btn-sm add-new"><em class="fa fa-plus"></em></button>
                    </td>
                </tr>
                <!-- END: array_data -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <!-- BEGIN: write --><label><input type="checkbox" name="write" value="1"/> {LANG.nv_admin_write}</label><!-- END: write -->
                        <input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</form>
<!-- END: main -->