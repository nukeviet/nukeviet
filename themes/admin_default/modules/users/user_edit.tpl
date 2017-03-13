<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="alert alert-warning">{LANG.modforum}</div>
<!-- END: is_forum -->
<!-- BEGIN: edit_user -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>

<form  class="form-inline" role="form" action="{FORM_ACTION}" method="post" onsubmit="return user_validForm(this);">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w300"/>
                <col class="w20"/>
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td> {LANG.account} </td>
                    <td> (<span style="color:#FF0000">*</span>) </td>
                    <td><input class="required form-control" value="{DATA.username}" name="username" id="username_iavim" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td> {LANG.email} </td>
                    <td> (<span style="color:#FF0000">*</span>) </td>
                    <td><input class="email required form-control" value="{DATA.email}" name="email" id="email_iavim" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td> {LANG.question} </td>
                    <td>
                        (<span style="color:#FF0000">*</span>)
                    </td>
                    <td><input class="form-control required" type="text" value="{DATA.question}" name="question" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td> {LANG.answer} </td>
                    <td>
                        (<span style="color:#FF0000">*</span>)
                    </td>
                    <td><input class="form-control required" type="text" value="{DATA.answer}" name="answer" style="width: 300px" /></td>
                </tr>
                <!-- BEGIN: name_show_0 -->
                <tr>
                    <td colspan="2"> {LANG.last_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.last_name}" name="last_name" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.first_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.first_name}" name="first_name" style="width: 300px" /></td>
                </tr>
                <!-- END: name_show_0 -->
                <!-- BEGIN: name_show_1 -->
                <tr>
                    <td colspan="2"> {LANG.first_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.first_name}" name="first_name" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.last_name} </td>
                    <td><input class="form-control" type="text" value="{DATA.last_name}" name="last_name" style="width: 300px" /></td>
                </tr>
                <!-- END: name_show_1 -->
                <tr>
                    <td colspan="2"> {LANG.gender} </td>
                    <td>
                    <select class="form-control" name="gender">
                        <!-- BEGIN: gender -->
                        <option value="{GENDER.key}"{GENDER.selected}>{GENDER.title}</option>
                        <!-- END: gender -->
                    </select></td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.avatar} </td>
                    <td>
                        <!-- BEGIN: photo -->
                        <p id="current-photo" class="pull-left text-center">
                            <img id="imageresource" alt="{DATA.username}" src="{IMG.src}" width="{IMG.width}" height="{IMG.height}" class="img-thumbnail fa-pointer"/>
                            <span class="fa-pointer" id="current-photo-btn"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.delete}</span>
                            <input type="hidden" name="delpic" id="photo_delete" value="{DATA.delpic}"/>
                        </p>
                        <!-- END: photo -->
                        <div id="change-photo" class="w300">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <em class="fa fa-trash-o fa-fix fa-pointer" onclick="$('#avatar').val('');">&nbsp;</em>
                                </span>
                                <input type="text" class="form-control" id="avatar" name="photo" value="" readonly="readonly"/>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btn_upload"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"> {LANG.birthday} </td>
                    <td><input name="birthday" id="birthday" class="form-control" value="{DATA.birthday}" style="width: 100px;" maxlength="10" type="text" />
                </tr>
                <tr>
                    <td colspan="2"> {LANG.show_email} </td>
                    <td><input type="checkbox" name="view_mail" value="1"{DATA.view_mail} /></td>
                </tr>
                <tr>
                    <td style="vertical-align:top" colspan="2"> {LANG.sig} </td>
                    <td><textarea name="sig" cols="70" rows="5" style="width:300px" class="form-control">{DATA.sig}</textarea></td>
                </tr>
                <!-- BEGIN: group -->
                <tr>
                    <td style="vertical-align:top" colspan="2"> {LANG.in_group} </td>
                    <td>
                        <!-- BEGIN: list -->
                        <div class="clearfix">
                            <label class="pull-left w200">
                                <input type="checkbox" value="{GROUP.id}" name="group[]" {GROUP.checked} {GROUP.disabled}/>
                                {GROUP.title}
                            </label>
                            <label class="pull-left group_default"{GROUP_DEFAULT_STYLE}>
                                <input type="radio" value="{GROUP.id}" name="group_default"{GROUP.default}/> {LANG.in_group_default}
                            </label>
                        </div>
                        <!-- END: list -->
                    </td>
                </tr>
                <!-- END: group -->
                <!-- BEGIN: is_official -->
                <tr>
                    <td colspan="2"> {LANG.set_official_note} </td>
                    <td><input type="checkbox" name="is_official" value="1" /></td>
                </tr>
                <!-- END: is_official -->
                <tr>
                    <td colspan="2"> {LANG.adduser_email1} </td>
                    <td><label><input type="checkbox" name="adduser_email" value="1"{DATA.adduser_email}/> <small>{LANG.adduser_email1_note1}</small></label></td>
                </tr>
            </tbody>
        </table>
        <!-- BEGIN: field -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields} </caption>
            <colgroup>
                <col class="w300"/>
                <col class="w20"/>
                <col />
            </colgroup>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td><strong>{FIELD.title}</strong>
                    <br>
                    <em>{FIELD.description}</em></td>
                    <td>
                    <!-- BEGIN: required -->
                    (<span style="color:#FF0000">*</span>)
                    <!-- END: required -->
                    </td>
                    <td>
                    <!-- BEGIN: textbox -->
                    <input class="form-control {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" style="width: 300px" />
                    <!-- END: textbox -->
                    <!-- BEGIN: date -->
                    <input class="form-control txt datepicker {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" style="width:100px"/>
                    <!-- END: date -->
                    <!-- BEGIN: textarea --><textarea style="width:300px" rows="5" cols="70" name="custom_fields[{FIELD.field}]">{FIELD.value}</textarea>
                    <!-- END: textarea -->
                    <!-- BEGIN: editor -->
                    {EDITOR}
                    <!-- END: editor -->
                    <!-- BEGIN: select -->
                    <select class="form-control" name="custom_fields[{FIELD.field}]">
                        <!-- BEGIN: loop -->
                        <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                        <!-- END: loop -->
                    </select>
                    <!-- END: loopselect -->
                    <!-- BEGIN: radio -->
                    <label for="lb_{FIELD_CHOICES.id}"> <input type="radio" name="custom_fields[{FIELD.field}]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
                    <!-- END: radio -->
                    <!-- BEGIN: checkbox -->
                    <label for="lb_{FIELD_CHOICES.id}"> <input type="checkbox" name="custom_fields[{FIELD.field}][]" value="{FIELD_CHOICES.key}" id="lb_{FIELD_CHOICES.id}" {FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label>
                    <!-- END: checkbox -->
                    <!-- BEGIN: multiselect -->
                    <select class="form-control" name="custom_fields[{FIELD.field}][]" multiple="multiple">
                        <!-- BEGIN: loop -->
                        <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                        <!-- END: loop -->
                    </select>
                    <!-- END: multiselect -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
        <!-- END: field -->
        <!-- BEGIN: changepass -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.edit_password_note} </caption>
            <colgroup>
                <col style="width:325px"/>
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td> {LANG.password} </td>
                    <td><input class="form-control" type="password" name="password1" autocomplete="off" value="{DATA.password1}" style="width: 300px" /> <a href="javascript:void(0);" onclick="return nv_genpass();" class="btn btn-primary btn-xs">{LANG.random_password}</a></td>
                </tr>
                <tr>
                    <td> {LANG.repassword} </td>
                    <td><input class="form-control" type="password" name="password2" autocomplete="off" value="{DATA.password2}" style="width: 300px" id="password2" /> <input id="methods" type="checkbox">{LANG.show_password}</td>
                </tr>
            </tbody>
        </table>
        <!-- END: changepass -->
    </div>
    <div class="text-center">
        <input type="hidden" name="confirm" value="1" />
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-spin fa-spinner hidden"></i>
            <span>{LANG.edit_title}</span>
        </button>
    </div>
</form>
<br />
<script type="text/javascript">
//<![CDATA[
$(function() {
    $.toggleShowPassword({
        field: '#password2',
        control: '#methods'
    });
    control_theme_groups();
});
//]]>
</script>
<!-- BEGIN: add_photo -->
<script type="text/javascript">
//<![CDATA[
    $('#change-photo').show();
//]]>
</script>
<!-- END: add_photo -->
<!-- END: edit_user -->
<!-- END: main -->