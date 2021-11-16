<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">
    {ERROR}
</div>
<!-- END: error -->
<div class="panel-group" id="settings" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="general_settings_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#general_settings" aria-expanded="false" aria-controls="general_settings">
                    {LANG.general_settings}
                </a>
            </h4>
        </div>
        <div id="general_settings" class="panel-collapse collapse" role="tabpanel" aria-labelledby="general_settings_heading" data-location="{PAGE_LINK}&amp;action=general_settings">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><strong>{LANG.zalo_official_account_id}</strong></td>
                                            <td><input type="text" name="zaloOfficialAccountID" value="{DATA.zaloOfficialAccountID}" class="form-control" maxlength="50" /></td>
                                        </tr>
                                        <tr>
                                            <td><strong>{LANG.app_id}</strong></td>
                                            <td><input type="text" name="zaloAppID" value="{DATA.zaloAppID}" class="form-control" maxlength="50" /></td>
                                        </tr>
                                        <tr>
                                            <td><strong>{LANG.app_secret_key}</strong></td>
                                            <td><input type="text" name="zaloAppSecretKey" value="{DATA.zaloAppSecretKey}" class="form-control" maxlength="50" /></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                                <input type="hidden" name="func" value="settings" />
                                                <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 infolist">
                        {LANG.oa_create_note}
                        {LANG.app_note}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="webhook_setup_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#webhook_setup" aria-expanded="false" aria-controls="webhook_setup">
                    {LANG.webhook_setup}
                </a>
            </h4>
        </div>
        <div id="webhook_setup" class="panel-collapse collapse" role="tabpanel" aria-labelledby="webhook_setup_heading" data-location="{PAGE_LINK}&amp;action=webhook_setup">
            <div class="panel-body">
                <!-- BEGIN: webhook_not_allowed -->
                <div class="alert alert-warning">{LANG.webhook_setup_note}</div>
                <!-- END: webhook_not_allowed -->
                <!-- BEGIN: webhook_is_allowed -->
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><strong>{LANG.oa_secrect_key}</strong></td>
                                            <td><input type="text" name="zaloOASecretKey" value="{DATA.zaloOASecretKey}" class="form-control" maxlength="50" /></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="hidden" name="checkss" value="{DATA.checkss}" />
                                                <input type="hidden" name="func" value="webhook" />
                                                <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-primary" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 infolist">
                        {LANG.webhook_note}
                    </div>
                </div>
                <!-- END: webhook_is_allowed -->
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="access_token_create_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#access_token_create" aria-expanded="false" aria-controls="access_token_create">
                    {LANG.access_token_create}
                </a>
            </h4>
        </div>
        <div id="access_token_create" class="panel-collapse collapse" role="tabpanel" aria-labelledby="access_token_create_heading" data-location="{PAGE_LINK}&amp;action=access_token_create">
            <div class="panel-body">
                <!-- BEGIN: access_token_not_allowed -->
                <div class="alert alert-warning">{LANG.access_token_create_note}</div>
                <!-- END: access_token_not_allowed -->
                <!-- BEGIN: access_token_is_allowed -->
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td><strong>{LANG.access_token}</strong></td>
                            <td><input id="access_token" type="text" value="{DATA.zaloOAAccessToken}" class="form-control" readonly /></td>
                        </tr>
                        <tr>
                            <td><strong>{LANG.refresh_token}</strong></td>
                            <td><input id="refresh_token" type="text" value="{DATA.zaloOARefreshToken}" class="form-control" readonly /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;func=access_token_create" role="button" class="btn btn-default" data-toggle="access_token_create">{LANG.access_token_create}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- END: access_token_is_allowed -->
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="system_check_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#system_check" aria-expanded="false" aria-controls="system_check">
                    {LANG.system_check}
                </a>
            </h4>
        </div>
        <div id="system_check" class="panel-collapse collapse" role="tabpanel" aria-labelledby="system_check_heading" data-location="{PAGE_LINK}&amp;action=system_check">
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-nowrap  text-center" style="width: 1%;">{LANG.directive}</th>
                            <th class="text-center">{LANG.required_value}</th>
                            <th class="text-center">{LANG.current_value}</th>
                            <th class="text-nowrap text-center" style="width: 1%;">{LANG.result}</th>
                            <th class=" text-center">{LANG.recommedation}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- BEGIN: system_check -->
                        <tr>
                            <td class="text-nowrap" style="width: 1%;">{CHECK.key}</td>
                            <td class="text-center">{CHECK.required}</td>
                            <td class="text-center">{CHECK.current}</td>
                            <td class="text-nowrap" style="width: 1%;">
                                <!-- BEGIN: suitable --><i class="fa fa-check text-success fa-fw"></i><!-- END: suitable -->
                                <!-- BEGIN: notsuitable --><i class="fa fa-times text-danger fa-fw"></i><!-- END: notsuitable -->
                                {CHECK.suitable_info}
                            </td>
                            <td>
                                {CHECK.recommendation}
                            </td>
                        </tr>
                        <!-- END: system_check -->
                    </tbody>
                    <tfoot>
                        <tr class="<!-- BEGIN: suitable2 -->bg-info<!-- END: suitable2 --><!-- BEGIN: notsuitable2 -->bg-warning<!-- END: notsuitable2 -->">
                            <td class="text-nowrap" style="width: 1%;">{LANG.finally}</td>
                            <td colspan="4">
                                <!-- BEGIN: suitable --><i class="fa fa-check text-success fa-fw"></i> {LANG.finally_suitable}
                                <!-- END: suitable -->
                                <!-- BEGIN: notsuitable --><i class="fa fa-times text-danger fa-fw"></i> {LANG.finally_not_suitable}
                                <!-- END: notsuitable -->
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="vnsubdivisions_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#vnsubdivisions" aria-expanded="false" aria-controls="vnsubdivisions">
                    {LANG.vnsubdivisions_settings}
                </a>
            </h4>
        </div>
        <div id="vnsubdivisions" class="panel-collapse collapse" role="tabpanel" aria-labelledby="vnsubdivisions_heading" data-location="{PAGE_LINK}&amp;action=vnsubdivisions&amp;suvdiv={SUBDIV_PARENT}" data-subdiv-parent="{SUBDIV_PARENT}" data-loaded="false">
            <div class="panel-body content"></div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="callingcodes_heading">
            <h4 class="panel-title">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#settings" href="#callingcodes" aria-expanded="false" aria-controls="callingcodes">
                    {LANG.callingcodes_settings}
                </a>
            </h4>
        </div>
        <div id="callingcodes" class="panel-collapse collapse" role="tabpanel" aria-labelledby="callingcodes_heading" data-location="{PAGE_LINK}&amp;action=callingcodes" data-loaded="false">
            <div class="panel-body content"></div>
        </div>
    </div>
</div>
<!-- BEGIN: action -->
<script>
    $(function() {
        $('a[data-toggle="collapse"][aria-controls="{ACTION}"]').trigger('click')
    })
</script>
<!-- END: action -->
<!-- END: main -->

<!-- BEGIN: isSuccess -->
<script>
    $(function() {
        $("#access_token", opener.document).val('{RESULT.access_token}');
        $("#refresh_token", opener.document).val('{RESULT.refresh_token}');
        window.close()
    });
</script>
<!-- END: isSuccess -->

<!-- BEGIN: isError -->
<script>
    $(function() {
        alert('{ERROR}');
        window.close()
    });
</script>
<!-- END: isError -->

<!-- BEGIN: vnsubdivisions_page -->
<div class="row m-bottom">
    <div class="col-md-15">
        <select class="form-control input-lg" data-toggle="subdiv_parent_change">
            <option value="" data-url="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;action=vnsubdivisions">{LANG.provincial_vnsubdivisions}</option>
            <!-- BEGIN: province -->
            <option value="{PROVINCE.code}" data-url="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;action=vnsubdivisions&amp;subdiv={PROVINCE.code}" {PROVINCE.sel}>{PROVINCE.name}</option>
            <!-- END: province -->
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-15">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" data-toggle="vnsubdivisionsSubmit">
            <input type="hidden" name="parent" value="{PARENT}">
            <input type="hidden" name="vnsubdivisionsSave" value="1">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width:1%">#</th>
                            <th class="text-nowrap text-center" style="width:1%">{LANG.vnsubdivisions_code}</th>
                            <th>{LANG.vnsubdivisions_main_name}</th>
                            <th>{LANG.vnsubdivisions_other_name}<br/><em style="font-weight: normal">({LANG.vnsubdivisions_other_name_note})</em></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td class="text-center" colspan="4">
                                <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody class="vnsubdivisions" data-mess="{LANG.change_name_note}">
                        <!-- BEGIN: loop -->
                        <tr class="unit">
                            <td class="text-center" style="width:1%">{SUBDIV.tt}</td>
                            <td class="text-center" style="width:1%"><span class="code">{SUBDIV.code_format}</span></td>
                            <td>
                                <div class="input-group">
                                    <input type="text" name="subdiv_mainname[{SUBDIV.code}]" value="{SUBDIV.mainname}" class="form-control" maxlength="100" readonly="readonly" />
                                    <span class="input-group-addon" style="padding:6px 8px"><input type="checkbox" data-toggle="subdiv_remove_readonly" style="margin:0"></span>
                                </div>
                            </td>
                            <td class="other-names">
                                <div class="other_name">
                                    <!-- BEGIN: other_name -->
                                    <div class="input-group name">
                                        <input type="text" name="subdiv_othername[{SUBDIV.code}][]" value="{OTHER_NAME}" class="form-control" maxlength="100" />
                                        <span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="subdiv_name_remove"><span>&times;</span></button><button class="btn btn-default" type="button" data-toggle="add_other_name" data-code="{SUBDIV.code}"><span>+</span></button></span>
                                    </div>
                                    <!-- END: other_name -->
                                </div>
                            </td>
                        </tr>
                        <!-- END: loop -->
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<!-- END: vnsubdivisions_page -->

<!-- BEGIN: callingcodes_page -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" data-toggle="callingcodesSubmit" data-mess="{LANG.country_callcode_error}">
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="callingcodesSave" value="1">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center" style="width:1%">{LANG.country_code}</th>
                            <th>{LANG.country_name}</th>
                            <th>{LANG.country_callcode}</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td class="text-center" colspan="3">
                                <button type="submit" class="btn btn-primary">{GLANG.submit}</button>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <!-- BEGIN: loop -->
                        <tr>
                            <td class="text-center" style="width:1%">{COUNTRY.code}</td>
                            <td>{COUNTRY.name}</td>
                            <td style="max-width: 120px;">
                                <div class="callcodes" data-country="{COUNTRY.code}">
                                    <!-- BEGIN: callcode -->
                                    <div class="input-group callcode">
                                        <input type="text" name="callcode[{COUNTRY.code}][]" value="{CALLCODE}" class="form-control" maxlength="6" />
                                        <span class="input-group-btn"><button class="btn btn-default" type="button" data-toggle="callcode_remove"><span>&times;</span></button><button class="btn btn-default" type="button" data-toggle="callcode_add"><span>+</span></button></span>
                                    </div>
                                    <!-- END: callcode -->
                                </div>
                            </td>
                        </tr>
                        <!-- END: loop -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<!-- END: callingcodes_page -->