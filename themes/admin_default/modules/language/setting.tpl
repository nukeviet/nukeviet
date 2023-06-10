<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.nv_lang_show} </caption>
        <thead class="bg-primary">
            <tr class="text-center">
                <th class="text-center text-nowrap">{LANG.nv_lang_key}</th>
                <th class="text-center text-nowrap">{LANG.nv_lang_name}</th>
                <th class="text-center text-nowrap">{LANG.nv_lang_native_name}</th>
                <th class="text-center text-nowrap" style="width: 1%;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center text-nowrap" style="vertical-align: middle;">{ROW.key}</td>
                <td class="text-center text-nowrap" style="vertical-align: middle;">{ROW.language}</td>
                <td class="text-center text-nowrap" style="vertical-align: middle;">{ROW.name}</td>
                <td class="text-nowrap" style="vertical-align: middle;">
                    <button type="button" class="btn btn-default read-lang" data-url="{LANG_FUNC.read}">{LANG.nv_admin_read_all}</button>
                    <a class="btn btn-default" href="{LANG_FUNC.download}">{LANG.nv_admin_download}</a>
                    <!-- BEGIN: edit -->
                    <a class="btn btn-default" href="{LANG_FUNC.edit}">{LANG.nv_admin_edit}</a>
                    <!-- END: edit -->
                    <!-- BEGIN: write -->
                    <button type="button" class="btn btn-default write-lang" data-url="{LANG_FUNC.write}">{LANG.nv_admin_write}</button>
                    <!-- END: write -->
                    <!-- BEGIN: delete -->
                    <button type="button" class="btn btn-default delete-lang" data-url="{LANG_FUNC.delete}">{LANG.nv_admin_delete}</button>
                    <!-- END: delete -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>

<form action="{NV_BASE_ADMINURL}index.php" method="post" data-toggle="lang_setting_form">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.nv_setting_read} </caption>
            <!-- BEGIN: type -->
            <tr>
                <td>
                    <label class="mb-0"><input name="read_type" value="{TYPE.key}" type="radio" {TYPE.checked} /> {TYPE.title}</label>
                </td>
            </tr>
            <!-- END: type -->
        </table>
    </div>
    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
    <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}" />
    <div class="text-center">
        <input type="submit" value="{LANG.nv_admin_edit_save}" class="btn btn-primary" />
    </div>
</form>
<!-- END: main -->