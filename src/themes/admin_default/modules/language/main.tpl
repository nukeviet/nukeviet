<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <caption><i class="fa fa-file-o"></i> {LANG.lang_installed}</caption>
        <thead class="bg-primary">
            <tr>
                <th class="w100">{LANG.order}</th>
                <th>{LANG.nv_lang_key}</th>
                <th>{LANG.nv_lang_name}</th>
                <th class="text-center text-nowrap" style="width:1%">{LANG.nv_lang_slsite}</th>
                <th style="width:1%">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: installed_loop -->
            <tr>
                <td style="vertical-align: middle;">
                    <select data-toggle="change_weight" data-keylang="{ROW.keylang}" class="form-control">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.w}" {WEIGHT.selected}>{WEIGHT.w}</option>
                        <!-- END: weight -->
                    </select>
                </td>
                <td style="vertical-align: middle;">{ROW.keylang}</td>
                <td style="vertical-align: middle;">{ROW.name}</td>
                <td class="text-nowrap text-center" style="vertical-align: middle;">
                    <!-- BEGIN: allow_sitelangs_note -->
                    {LANG.site_lang}
                    <!-- END: allow_sitelangs_note -->
                    <!-- BEGIN: allow_sitelangs -->
                    <select class="form-control" data-toggle="activelang">
                        <option {ALLOW_SITELANGS.selected_yes} value="{ALLOW_SITELANGS.url_yes}"> {GLANG.yes}</option>
                        <option {ALLOW_SITELANGS.selected_no} value="{ALLOW_SITELANGS.url_no}"> {GLANG.no}</option>
                    </select>
                    <!-- END: allow_sitelangs -->
                </td>
                <td class="text-nowrap text-center" style="vertical-align: middle;">
                    <!-- BEGIN: setup_delete -->
                    <button type="button" class="btn btn-default" data-toggle="setup_delete" data-url="{DELETE}" title="{LANG.nv_setup_delete}"><em class="fa fa-trash-o fa-lg"></em></button>
                    <!-- END: setup_delete -->
                    <!-- BEGIN: setup_note -->
                    <em class="fa fa-check fa-lg" title="{LANG.nv_setup}"></em>
                    <!-- END: setup_note -->
                </td>
            </tr>
            <!-- END: installed_loop -->
        </tbody>
    </table>
</div>

<!-- BEGIN: can_install -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <caption><i class="fa fa-file-o"></i> {LANG.lang_can_install}</caption>
        <thead class="bg-primary">
            <tr>
                <th class="text-nowrap" style="width: 1%;">{LANG.nv_lang_key}</th>
                <th>{LANG.nv_lang_name}</th>
                <th class="text-nowrap" style="width: 1%;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td style="vertical-align: middle;">{ROW.keylang}</td>
                <td style="vertical-align: middle;">{ROW.name}</td>
                <td class="text-nowrap" style="width: 1%;vertical-align: middle;">
                    <!-- BEGIN: setup_new -->
                    <button type="button" data-toggle="setup_new" data-url="{INSTALL}" title="{LANG.nv_setup_new}" class="btn btn-default"><em class="fa fa-sun-o fa-lg"></em> {LANG.nv_setup_new}</button>
                    <!-- END: setup_new -->
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- END: can_install -->

<div class="alert alert-warning">{LANG.nv_data_note}</div>
<!-- END: main -->
