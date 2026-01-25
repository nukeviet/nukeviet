<!-- BEGIN: add -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="alert alert-info">{LANG.nv_admin_add_info}</div>
<form method="post" action="{ACTION}" id="addadmin" data-result="{RESULT_URL}">
    <input type="hidden" name="checkss" value="{CHECKSS}" />
    <input name="save" id="save" type="hidden" value="1" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <colgroup>
                <col class="w250">
            </colgroup>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" value="{LANG.nv_admin_add}" class="btn btn-primary" />
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td>{LANG.add_user}: <sup class="required">(*)</sup></td>
                    <td><input class="form-control pull-left" name="userid" id="userid" type="text" value="{USERID}" maxlength="20" style="width: 180px" />&nbsp;<input type="button" value="{LANG.add_select}" onclick="open_browse_us()" class="btn btn-default" /></td>
                </tr>
                <tr>
                    <td>{LANG.position}:<sup class="required">(*)</sup></td>
                    <td>
                        <div class="row">
                            <div class="col-sm-8 col-md-6">
                                <input class="form-control" name="position" id="position" type="text" value="" maxlength="250" />
                            </div>
                            <div class="col-sm-16 col-md-18">
                                <span>&lArr;</span> {LANG.position_info}
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.themeadmin}</td>
                    <td>
                        <select name="admin_theme" class="form-control" style="width: fit-content;">
                            <option value="">{LANG.theme_default}</option>
                            <!-- BEGIN: admin_theme -->
                            <option value="{THEME_NAME}">{THEME_NAME}</option>
                            <!-- END: admin_theme -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: editor -->
                <tr>
                    <td>{LANG.editor}:</td>
                    <td>
                        <select name="editor" id="editor" class="form-control" style="width: fit-content;">
                            <option value="">{LANG.not_use}</option>
                            <!-- BEGIN: loop -->
                            <option value="{EDITOR.val}"{EDITOR.sel}>{EDITOR.val}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: editor -->
                <!-- BEGIN: allow_files_type -->
                <tr>
                    <td>{LANG.allow_files_type}:</td>
                    <td>
                        <div class="row">
                            <!-- BEGIN: loop -->
                            <div class="col-sm-8 col-md-6">
                                <input name="allow_files_type[]" type="checkbox" value="{TP}" checked="checked"/> {TP}
                            </div>
                            <!-- END: loop -->
                        </div>
                    </td>
                </tr>
                <!-- END: allow_files_type -->
                <tr>
                    <td>{LANG.allow_modify_files}:</td>
                    <td><input name="allow_modify_files" type="checkbox" value="1" /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_create_subdirectories}:</td>
                    <td><input name="allow_create_subdirectories" type="checkbox" value="1" checked="checked" /></td>
                </tr>
                <tr>
                    <td>{LANG.allow_modify_subdirectories}:</td>
                    <td><input name="allow_modify_subdirectories" type="checkbox" value="1" /></td>
                </tr>
                <tr>
                    <td>{LANG.lev}: <sup class="required">(*)</sup></td>
                    <td>
                        <div class="mb">
                            <!-- BEGIN: show_lev_2 -->
                            <label><input name="lev" type="radio" value="2" />&nbsp;{GLANG.level2}&nbsp;&nbsp;&nbsp;</label>
                            <!-- END: show_lev_2 -->
                            <label><input name="lev" type="radio" value="3" checked="checked" />&nbsp;{GLANG.level3}</label>
                        </div>
                        <div id="modslist" class="panel-group">
                            <div class="mb">{LANG.if_level3_selected}:</div>
                            <!-- BEGIN: lang_mods -->
                            <div class="panel panel-primary" data-toggle="checklist">
                                <div class="panel-heading">
                                    {LANG_MODS.name}
                                </div>
                                <div class="panel-body">
                                    <div class="mb">
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="true"><em class="fa fa-check-square" style="margin-right:5px"></em>{LANG.checkall}</button> &nbsp;&nbsp; <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="false"><em class="fa fa-square" style="margin-right:5px"></em>{LANG.uncheckall}</button>
                                    </div>
                                    <div class="row">
                                        <!-- BEGIN: lev_loop -->
                                        <div class="col-md-12">
                                            <label style="display:inline-flex"><input data-toggle="checkitem" name="modules[{LANG_MODS.code}][]" type="checkbox" value="{MOD_VALUE}" style="margin:0 5px 0 0" />{MOD_VALUE} ({CUSTOM_TITLE})<label>
                                        </div>
                                        <!-- END: lev_loop -->
                                    </div>
                                </div>
                            </div>
                            <!-- END: lang_mods -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.lev_expired}:</td>
                    <td>
                        <div class="input-group mb" style="width:fit-content">
                            <input type="text" class="form-control" value="" name="lev_expired" placeholder="{DATE_FORMAT}" id="lev_expired" autocomplete="off">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="lev_expired_btn">
                                    <em class="fa fa-calendar fa-fix"></em>
                                </button>
                                <button class="btn btn-default" type="button" id="lev_expired_clear">
                                    <em class="fa fa-times fa-fix"></em>
                                </button>
                            </span>
                        </div>
                        <div class="help-block mb-0">{LANG.lev_expired_note}</div>
                    </td>
                </tr>
                <!-- BEGIN: show_lev_2_2 -->
                <tr id="after_exp_action" style="display:none;">
                    <td>{LANG.after_exp_action}:</td>
                    <td>
                        <div class="mb"><label><input name="downgrade_to_modadmin" type="checkbox" value="1"/>&nbsp;{LANG.downgrade_to_modadmin}</label> ({LANG.downgrade_to_modadmin_note})</div>
                        <div id="modslist2" style="display:none">
                            <div class="mb">{LANG.if_level3_selected}:</div>
                            <!-- BEGIN: lang_after_mods -->
                            <div class="panel panel-primary" data-toggle="checklist">
                                <div class="panel-heading">
                                    {LANG_MODS.name}
                                </div>
                                <div class="panel-body">
                                    <div class="mb">
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="true"><em class="fa fa-check-square" style="margin-right:5px"></em>{LANG.checkall}</button> &nbsp;&nbsp; <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="false"><em class="fa fa-square" style="margin-right:5px"></em>{LANG.uncheckall}</button>
                                    </div>
                                    <div class="row">
                                        <!-- BEGIN: lev_loop2 -->
                                        <div class="col-md-12">
                                            <label style="display:inline-flex"><input data-toggle="checkitem" name="after_modules[{LANG_MODS.code}][]" type="checkbox" value="{MOD_VALUE}" style="margin:0 5px 0 0" disabled/>{MOD_VALUE} ({CUSTOM_TITLE})</label>
                                        </div>
                                        <!-- END: lev_loop2 -->
                                    </div>
                                </div>
                            </div>
                            <!-- END: lang_after_mods -->
                        </div>
                    </td>
                </tr>
                <!-- END: show_lev_2_2 -->
            </tbody>
        </table>
    </div>
</form>
<script>
    function open_browse_us() {
        nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=users&' + nv_fc_variable + '=getuserid&area=userid&return=username&filtersql={FILTERSQL}', 'NVImg', 850, 500, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
    }
</script>
<!-- END: add -->
<!-- BEGIN: add_result -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <em class="fa fa-file-text-o">&nbsp;</em>{TITLE}:
        </caption>
        <col span="2" class="top" style="width: 50%" />
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{VALUE0}</td>
                <td>{VALUE1}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<br />
<div>
    <a class="btn btn-primary" href="{EDIT_HREF}">{EDIT}</a> <a class="btn btn-primary" href="{HOME_HREF}">{HOME}</a>
</div>
<!-- END: add_result -->
