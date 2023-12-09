<!-- BEGIN: edit -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<div class="alert alert-info">{INFO}</div>
<form method="post" action="{ACTION}" id="editadmin">
    <input type="hidden" name="checkss" value="{CHECKSS}" />
    <input name="save" id="save" type="hidden" value="1" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w250">
            </colgroup>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" value="{LANG.save}" class="btn btn-primary" />
                        <input type="reset" value="{GLANG.reset}" class="btn btn-default" />
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: position -->
                <tr>
                    <td>{LANG.position}: <sup class="required">(*)</sup></td>
                    <td>
                        <div class="row">
                            <div class="col-sm-8 col-md-6">
                                <input class="form-control" name="position" id="position" type="text" value="{POSITION}" maxlength="250" />
                            </div>
                            <div class="col-sm-16 col-md-18">
                                <span>&lArr;</span> {LANG.position_info}
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- END: position -->
                <tr>
                    <td>{LANG.themeadmin}</td>
                    <td>
                        <select name="admin_theme" class="form-control w300">
                            <option value="">{LANG.theme_default}</option>
                            <!-- BEGIN: admin_theme -->
                            <option value="{THEME_NAME}" {THEME_SELECTED}>{THEME_NAME}</option>
                            <!-- END: admin_theme -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: editor -->
                <tr>
                    <td>{LANG.editor}:</td>
                    <td>
                        <select name="editor" id="editor" class="form-control w200">
                            <option value="">{LANG.not_use}</option>
                            <!-- BEGIN: loop -->
                            <option value="{VALUE}" {SELECTED}>{VALUE}</option>
                            <!-- END: loop -->
                        </select>
                    </td>
                </tr>
                <!-- END: editor -->
                <tr>
                    <td>{LANG.main_module}:</td>
                    <td>
                        <select name="main_module" class="form-control w200">
                            <!-- BEGIN: module -->
                            <option value="{MODULE.module}" {MODULE.selected}>{MODULE.title} </option>
                            <!-- END: module -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: allow_files_type -->
                <tr>
                    <td>{LANG.allow_files_type}:</td>
                    <td>
                        <div class="row">
                            <!-- BEGIN: loop -->
                            <div class="col-sm-8 col-md-6">
                                <input name="allow_files_type[]" type="checkbox" value="{VALUE}" {CHECKED} /> {VALUE}
                            </div>
                            <!-- END: loop -->
                        </div>
                    </td>
                </tr>
                <!-- END: allow_files_type -->
                <!-- BEGIN: allow_modify_files -->
                <tr>
                    <td>{LANG.allow_modify_files}:</td>
                    <td><input name="allow_modify_files" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_modify_files -->
                <!-- BEGIN: allow_create_subdirectories -->
                <tr>
                    <td>{LANG.allow_create_subdirectories}:</td>
                    <td><input name="allow_create_subdirectories" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_create_subdirectories -->
                <!-- BEGIN: allow_modify_subdirectories -->
                <tr>
                    <td>{LANG.allow_modify_subdirectories}:</td>
                    <td><input name="allow_modify_subdirectories" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_modify_subdirectories -->
                <!-- BEGIN: lev -->
                <tr>
                    <td>{LANG.lev}:</td>
                    <td>
                        <!-- BEGIN: if -->
                        <div class="mb">
                            <label><input name="lev" type="radio" value="2" {CHECKED2} />&nbsp;{GLANG.level2}</label> &nbsp;&nbsp;&nbsp;
                            <label><input name="lev" type="radio" value="3" {CHECKED3} />&nbsp; {GLANG.level3}</label>
                        </div>
                        <!-- END: if -->
                        <div id="modslist"<!-- BEGIN: modslist_hidden --> style="display:none"<!-- END: modslist_hidden -->>
                            <div class="mb">
                                {LANG.if_level3_selected}:
                            </div>
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
                                        <!-- BEGIN: loop -->
                                        <div class="col-md-12">
                                            <label style="display:inline-flex"><input data-toggle="checkitem" name="modules[{LANG_MODS.code}][]" type="checkbox" value="{VALUE}" style="margin:0 5px 0 0" {CHECKED} />{VALUE} ({CUSTOM_TITLE})</label>
                                        </div>
                                        <!-- END: loop -->
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
                            <input type="text" class="form-control" value="{LEV_EXPIRED}" name="lev_expired" placeholder="DD.MM.YYYY" id="lev_expired">
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
                <!-- BEGIN: after_exp_action -->
                <tr id="after_exp_action"<!-- BEGIN: hidden --> style="display:none;"<!-- END: hidden -->>
                    <td>{LANG.after_exp_action}:</td>
                    <td>
                        <div class="mb"><label><input name="downgrade_to_modadmin" type="checkbox" value="1"{DOWNGRADE_TO_MODADMIN_CHECKED}/>&nbsp;{LANG.downgrade_to_modadmin}</label> ({LANG.downgrade_to_modadmin_note})</div>
                        <div id="modslist2"<!-- BEGIN: modslist2_hidden --> style="display:none"<!-- END: modslist2_hidden -->>
                            <div class="mb">{LANG.if_level3_selected}:</div>
                            <!-- BEGIN: lang_after_mods -->
                            <div class="panel panel-primary" data-toggle="checklist">
                                <div class="panel-heading">
                                    {LANG_AFTER_MODS.name}
                                </div>
                                <div class="panel-body">
                                    <div class="mb">
                                        <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="true"><em class="fa fa-check-square" style="margin-right:5px"></em>{LANG.checkall}</button> &nbsp;&nbsp; <button type="button" class="btn btn-xs btn-default" data-toggle="checkall" data-check-value="false"><em class="fa fa-square" style="margin-right:5px"></em>{LANG.uncheckall}</button>
                                    </div>
                                    <div class="row">
                                        <!-- BEGIN: mod -->
                                        <div class="col-md-12">
                                            <label style="display:inline-flex"><input data-toggle="checkitem" name="after_modules[{LANG_AFTER_MODS.code}][]" type="checkbox" value="{MOD_VALUE}" style="margin:0 5px 0 0"{MOD_CHECKED}/>{MOD_VALUE} ({CUSTOM_TITLE})</label>
                                        </div>
                                        <!-- END: mod -->
                                    </div>
                                </div>
                            </div>
                            <!-- END: lang_after_mods -->
                        </div>
                    </td>
                </tr>
                <!-- END: after_exp_action -->
                <!-- END: lev -->
            </tbody>
        </table>
    </div>
</form>
<!-- END: edit -->
<!-- BEGIN: edit_resuilt -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <caption>
            <em class="fa fa-file-text-o">&nbsp;</em>{TITLE}:
        </caption>
        <thead>
            <tr>
                <th>{THEAD0}</th>
                <th>{THEAD1}</th>
                <th>{THEAD2}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{VALUE0}</td>
                <td>{VALUE1}</td>
                <td>{VALUE2}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<br />
<a class="btn btn-default" href="{EDIT_HREF}">{EDIT_NAME}</a>
<a class="btn btn-default" href="{HOME_HREF}">{HOME_NAME}</a>
<!-- END: edit_resuilt -->