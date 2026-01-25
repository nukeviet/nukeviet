<!-- BEGIN: empty -->
<div class="text-center">
    <strong>{LANG_EMPTY}</strong>
</div>
<!-- END: empty -->
<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="bg-primary">
            <tr class="text-center">
                <th>{LANG.nv_lang_nb}</th>
                <th>{LANG.nv_lang_module}</th>
                <th>{LANG.nv_lang_area}</th>
                <th class="text-center text-nowrap">{LANG.nv_lang_author}</th>
                <th class="text-center text-nowrap">{LANG.nv_lang_createdate}</th>
                <th class="text-center text-nowrap" style="width: 1%;">{LANG.nv_lang_func}</th>
            </tr>
        </thead>
        <tbody class="counter">
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center" style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;">{ROW.module}</td>
                <td style="vertical-align: middle;">{ROW.langsitename}</td>
                <td class="text-center" style="vertical-align: middle;">{ROW.author}</td>
                <td class="text-center" style="vertical-align: middle;">{ROW.createdate}</td>
                <td class="text-center text-nowrap" style="vertical-align: middle;">
                    <a href="{ROW.url_edit}" title="{LANG.nv_admin_edit}" class="btn btn-default btn-sm"><em class="fa fa-edit fa-lg"></em> {LANG.nv_admin_edit}</a>
                    <!-- BEGIN: write -->
                    <button type="button" data-url="{ROW.url_export}" title="{LANG.nv_admin_write}" class="btn btn-default btn-sm" data-toggle="lang_export"><em class="fa fa-sun-o fa-lg"></em> {LANG.nv_admin_write}</button>
                    <!-- END: write -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: main -->