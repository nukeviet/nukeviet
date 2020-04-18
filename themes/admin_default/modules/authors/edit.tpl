<!-- BEGIN: edit -->
<div class="alert alert-info">{INFO}</div>
<form method="post" action="{ACTION}">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w250">
            </colgroup>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-center"><input name="save" id="save" type="hidden" value="1" /><input name="go_edit" type="submit" value="{LANG.save}" class="btn btn-primary" /></td>
                </tr>
            </tfoot>
            <tbody>
                <!-- BEGIN: position -->
                <tr>
                    <td>{POSITION0}: <sup class="required">(*)</sup></td>
                    <td>
                        <div class="row">
                            <div class="col-sm-8 col-md-6">
                                <input class="form-control" name="position" id="position" type="text" value="{POSITION1}" maxlength="250" />
                            </div>
                            <div class="col-sm-16 col-md-18">
                                <span>&lArr;</span> {POSITION2}
                            </div>
                        </div>
                    </td>
                </tr>
                <!-- END: position -->
                <tr>
                    <td>{LANG.themeadmin}</td>
                    <td>
                    <select name="admin_theme" class="form-control w300" >
                        <option value="">{LANG.theme_default}</option>
                        <!-- BEGIN: admin_theme -->
                        <option value="{THEME_NAME}"{THEME_SELECTED}>{THEME_NAME}</option>
                        <!-- END: admin_theme -->
                    </select></td>
                </tr>                
                <!-- BEGIN: editor -->
                <tr>
                    <td>{EDITOR0}:</td>
                    <td><select name="editor" id="editor" class="form-control w200">
                            <option value="">{EDITOR3}</option>
                            <!-- BEGIN: loop -->
                            <option value="{VALUE}"{SELECTED}>{VALUE} </option>
                            <!-- END: loop -->
                    </select></td>
                </tr>
                <!-- END: editor -->
                <tr>
                    <td>{LANG.main_module}:</td>
                    <td><select name="main_module" class="form-control w200">
                            <!-- BEGIN: module -->
                            <option value="{MODULE.module}"{MODULE.selected}>{MODULE.title} </option>
                            <!-- END: module -->
                    </select></td>
                </tr>
                <!-- BEGIN: allow_files_type -->
                <tr>
                    <td>{ALLOW_FILES_TYPE}:</td>
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
                    <td>{ALLOW_MODIFY_FILES}:</td>
                    <td><input name="allow_modify_files" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_modify_files -->
                <!-- BEGIN: allow_create_subdirectories -->
                <tr>
                    <td>{ALLOW_CREATE_SUBDIRECTORIES}:</td>
                    <td><input name="allow_create_subdirectories" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_create_subdirectories -->
                <!-- BEGIN: allow_modify_subdirectories -->
                <tr>
                    <td>{ALLOW_MODIFY_SUBDIRECTORIES}:</td>
                    <td><input name="allow_modify_subdirectories" type="checkbox" value="1" {CHECKED} /></td>
                </tr>
                <!-- END: allow_modify_subdirectories -->
                <!-- BEGIN: lev -->
                <tr>
                    <td>{LEV0}:</td>
                    <td>
                        <!-- BEGIN: if --> <label> <input name="lev" type="radio" value="2" onclick="nv_show_hidden('modslist',0);" {CHECKED2} /> &nbsp;{LEV4}
                    </label> &nbsp;&nbsp;&nbsp; <label> <input name="lev" type="radio" value="3" onclick="nv_show_hidden('modslist',1);" {CHECKED3} /> &nbsp; {LEV5}
                    </label> <br /> <!-- END: if -->
                        <div id="modslist" style="margin-top: 10px; {STYLE}">
                            {LEV1}: <em class="fa fa-check-square-o fa-lg">&nbsp;</em><a id="checkall" href="javascript:void(0);">{LANG.checkall}</a> &nbsp;&nbsp; <em class="fa fa-circle-o fa-lg">&nbsp;</em><a id="uncheckall" href="javascript:void(0);">{LANG.uncheckall}</a>
                            <div class="clear">
                                <br />
                            </div>
                            <div class="row">
                                <!-- BEGIN: loop -->
                                <div class="col-sm-8 col-md-6">
                                    <input name="modules[]" type="checkbox" value="{VALUE}" {CHECKED} />&nbsp; {CUSTOM_TITLE}
                                </div>
                                <!-- END: loop -->
                            </div>
                        </div>
                        </div>
                    </td>
                </tr>
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