<!-- BEGIN: empty -->
<div class="text-center">
    <strong>{LANG.nv_lang_error_exit}</strong>
</div>
<meta http-equiv="Refresh" content="3;URL={URL}" />
<!-- END: empty -->
<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>

<form action="{NV_BASE_ADMINURL}index.php" method="get">
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input type="hidden" name ="save" value="1" /><input type="submit" value="{LANG.nv_admin_submit}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>{LANG.nv_lang_data}:</td>
                    <td>
                    <select name="typelang" class="form-control w200">
                        <option value="">{LANG.nv_admin_sl3}</option>
                        <!-- BEGIN: language -->
                        <option value="{LANGUAGE.key}"{LANGUAGE.selected}>{LANGUAGE.title}</option>
                        <!-- END: language -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_lang_data_source}:</td>
                    <td>
                    <select name="sourcelang" class="form-control w200">
                        <option value="">{LANG.nv_admin_sl3}</option>
                        <!-- BEGIN: language_source -->
                        <option value="{LANGUAGE_SOURCE.key}"{LANGUAGE_SOURCE.selected}>{LANGUAGE_SOURCE.title}</option>
                        <!-- END: language_source -->
                    </select></td>
                </tr>
                <tr>
                    <td> {LANG.nv_lang_area}:</td>
                    <td>
                    <select name="idfile" id="idfile" class="form-control w200">
                        <option value="0">{LANG.nv_lang_checkallarea}</option>
                        <!-- BEGIN: language_area -->
                        <option value="{LANGUAGE_AREA.key}"{LANGUAGE_AREA.selected}>{LANGUAGE_AREA.title}</option>
                        <!-- END: language_area -->
                    </select></td>
                </tr>
                <tr>
                    <td>{LANG.nv_check_type}:</td>
                    <td>
                    <select name="check_type" class="form-control w300">
                        <!-- BEGIN: language_check_type -->
                        <option value="{LANGUAGE_CHECK_TYPE.key}"{LANGUAGE_CHECK_TYPE.selected}>{LANGUAGE_CHECK_TYPE.title}</option>
                        <!-- END: language_check_type -->
                    </select></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- BEGIN: nodata -->
<div class="text-center">
    <br />
    <strong>{LANG.nv_lang_check_no_data}</strong>
</div>
<!-- END: nodata -->
<!-- BEGIN: data -->
<form action="{NV_BASE_ADMINURL}index.php" method="post">
    <input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name ="save" value="1" />
    <input type="hidden" name ="typelang" value="{DATA.typelang}" />
    <input type="hidden" name ="sourcelang" value="{DATA.sourcelang}" />
    <input type="hidden" name ="check_type" value="{DATA.check_type}" />
    <input type="hidden" name ="idfile" value="{DATA.idfile}" />
    <input type="hidden" name ="savedata" value="{DATA.savedata}" />
    <!-- BEGIN: lang -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
            <col class="w50"/>
            <col class="w200"/>
            <col/>
            <thead>
                <tr>
                    <td>{LANG.nv_lang_nb}</td>
                    <td>{LANG.nv_lang_key}</td>
                    <td>{LANG.nv_lang_value}</td>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">{ROW.stt}</td>
                    <td class="text-right">{ROW.lang_key}</td>
                    <td class="text-left">
                        <textarea rows="1" name="pozlang[{ROW.id}]" class="form-control">{ROW.datalang}</textarea>
                        <br />
                        {ROW.sourcelang}
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- END: lang -->
    <div class="text-center">
        <input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" />
    </div>
</form>
<!-- END: data -->

<script type="text/javascript">
    $(document).ready(function() {
        $("#idfile").select2();
    });
</script>

<!-- END: main -->
