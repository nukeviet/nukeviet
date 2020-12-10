<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="alert alert-warning">{LANG.modforum}</div>
<!-- END: is_forum -->
<!-- BEGIN: edit_user -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<form role="form" action="{FORM_ACTION}" method="post" class="form-inline" onsubmit="return user_validForm(this);">
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
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input type="text" class="form-control required w300" value="{DATA.username}" name="username" id="username_iavim" maxlength="{NV_UNICKMAX}" /></td>
                </tr>
                <tr>
                    <td> {LANG.email} </td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input type="email" class="form-control email required w300" value="{DATA.email}" name="email" id="email_iavim" /></td>
                </tr>
                <tr>
                    <td> {LANG.password} </td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input class="form-control required password w300" type="password" autocomplete="new-password" id="pass_iavim" name="password1" value="{DATA.password1}" maxlength="{NV_UPASSMAX}" /> <a href="javascript:void(0);" onclick="return nv_genpass();" class="btn btn-primary btn-xs">{LANG.random_password}</a></td>
                </tr>
                <tr>
                    <td> {LANG.repassword} </td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input class="form-control required password w300" type="password" autocomplete="new-password" name="password2" value="{DATA.password2}" id="password2" /> <input id="methods" type="checkbox"> {LANG.show_password}</td>
                </tr>
                <!-- BEGIN: name_show_0 -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" /></td>
                </tr>
                <!-- END: show_last_name -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" /></td>
                </tr>
                <!-- END: show_first_name -->
                <!-- END: name_show_0 -->
                <!-- BEGIN: name_show_1 -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" /></td>
                </tr>
                <!-- END: show_first_name -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" /></td>
                </tr>
                 <!-- END: show_last_name -->
                <!-- END: name_show_1 -->
                <!-- BEGIN: show_gender -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td>
                        <select class="form-control" name="gender">
                            <!-- BEGIN: gender --><option value="{GENDER.key}"{GENDER.selected}>{GENDER.title}</option><!-- END: gender -->
                        </select>
                    </td>
                </tr>
                <!-- END: show_gender -->
                <!-- BEGIN: show_birthday -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input name="birthday" id="birthday" class="form-control {FIELD.required} w100" value="{FIELD.value}" maxlength="10" type="text" autocomplete="off"></td>
                </tr>
                <!-- END: show_birthday -->
                <!-- BEGIN: show_sig -->
                <tr>
                    <td style="vertical-align:top"> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><textarea name="sig" class="form-control {FIELD.required} w300" cols="70" rows="5" >{FIELD.value}</textarea></td>
                </tr>
                <!-- END: show_sig -->
                <!-- BEGIN: show_question -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="question" /></td>
                </tr>
                <!-- END: show_question -->
                <!-- BEGIN: show_answer -->
                <tr>
                    <td> {FIELD.title} <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td><!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required --></td>
                    <td><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="answer" /></td>
                </tr>
                <!-- END: show_answer -->
                <tr>
                    <td> {LANG.avatar} </td>
                    <td></td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-control" id="avatar" name="photo" value="" readonly="readonly"/>
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btn_upload"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
                            </span>
                       </div>
                    </td>
                </tr>
                <tr>
                    <td> {LANG.show_email} </td>
                    <td></td>
                    <td><input type="checkbox" name="view_mail" value="1"{DATA.view_mail} /></td>
                </tr>
                <tr>
                    <td> {LANG.is_official} </td>
                    <td></td>
                    <td><label><input type="checkbox" name="is_official" value="1"{DATA.is_official}/> <small>{LANG.is_official_note}</small></label></td>
                </tr>
                <!-- BEGIN: group -->
                <tr id="ctn-list-groups">
                    <td style="vertical-align:top"> {LANG.in_group} </td>
                    <td></td>
                    <td>
                        <!-- BEGIN: list -->
                        <div class="clearfix">
                            <label class="pull-left w200">
                                <input type="checkbox" value="{GROUP.id}" name="group[]"{GROUP.checked} /> {GROUP.title}
                            </label>
                            <label class="pull-left group_default" style="display:none">
                                <input type="radio" value="{GROUP.id}" name="group_default" /> {LANG.in_group_default}
                            </label>
                        </div>
                        <!-- END: list -->
                        <div class="clearfix" id="cleargroupdefault" style="display: none;">
                            <label class="pull-left w200">&nbsp;</label>
                            <label class="pull-left">
                                <a href="#" data-toggle="cleargdefault" class="btn btn-default"><i class="fa fa-times-circle-o" aria-hidden="true"></i> {LANG.clear_group_default}</a>
                            </label>
                        </div>
                    </td>
                </tr>
                <!-- END: group -->
                <tr>
                    <td> {LANG.adduser_email1} </td>
                    <td></td>
                    <td><label><input type="checkbox" name="adduser_email" value="1"{DATA.adduser_email}/> <small>{LANG.adduser_email1_note}</small></label></td>
                </tr>
                <tr>
                    <td> {LANG.is_email_verified} </td>
                    <td></td>
                    <td><label><input type="checkbox" name="is_email_verified" value="1"{DATA.is_email_verified}> <small>{LANG.is_email_verified1}</small></label></td>
                </tr>
            </tbody>
        </table>
        <!-- BEGIN: field -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields} </caption>
            <colgroup>
                <col class="w300"/>
                <col class="w20"/>
            </colgroup>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description -->
                    </td>
                    <td>
                        <!-- BEGIN: required -->
                        <span class="text-danger">(*)</span>
                        <!-- END: required -->
                    </td>
                    <td>
                        <!-- BEGIN: textbox -->
                        <input class="form-control {FIELD.required} w300" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" />
                        <!-- END: textbox -->
                        <!-- BEGIN: date -->
                        <input class="form-control datepicker {FIELD.required} w100" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" />
                        <!-- END: date -->
                        <!-- BEGIN: textarea -->
                        <textarea class="form-control w300" rows="5" cols="70" name="custom_fields[{FIELD.field}]">{FIELD.value}</textarea>
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
                        <!-- END: select -->
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
    </div>
    <div class="text-center">
        <input type="hidden" name="confirm" value="1" />
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="hidden" name="nv_redirect" value="{NV_REDIRECT}" />
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-spin fa-spinner hidden"></i>
            <span>{LANG.member_add}</span>
        </button>
    </div>
</form>
<script type="text/javascript">
$(function() {
    $.toggleShowPassword({
        field: '#password2',
        control: '#methods'
    });

    $('[name="is_official"]').on('change', function() {
        var ctngroups = $('#ctn-list-groups');
        if (!ctngroups.length) {
            return;
        }
        if ($(this).is(":checked")) {
            ctngroups.removeClass('hidden');
        } else {
            ctngroups.addClass('hidden');
            $('[name="group[]"]').prop('checked', false);
            $('[name="group_default"]').prop('checked', false);
        }
    });

    $('[data-toggle="cleargdefault"]').on('click', function(e) {
        e.preventDefault();
        $('[name="group_default"]').prop('checked', false);
    });
});
</script>
<!-- END: edit_user -->
<!-- END: main -->
