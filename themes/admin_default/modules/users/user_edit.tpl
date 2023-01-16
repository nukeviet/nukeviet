<!-- BEGIN: main -->
<!-- BEGIN: is_forum -->
<div class="alert alert-warning">{LANG.modforum}</div>
<!-- END: is_forum -->
<!-- BEGIN: edit_user -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<form role="form" action="{FORM_ACTION}" method="post" onsubmit="return user_validForm(this);">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <colgroup>
                <col class="w300" />
                <col class="w20" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td><strong>{LANG.account}</strong></td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input class="required form-control" value="{DATA.username}" name="username" id="username_iavim" style="width: 300px" /></td>
                </tr>
                <tr>
                    <td><strong>{LANG.email}</strong></td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td><input type="email" class="email required form-control" value="{DATA.email}" name="email" id="email_iavim" style="width: 300px" /></td>
                </tr>
                <!-- BEGIN: name_show_0 -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_last_name -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_first_name -->
                <!-- END: name_show_0 -->
                <!-- BEGIN: name_show_1 -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_first_name -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_last_name -->
                <!-- END: name_show_1 -->
                <!-- BEGIN: show_gender -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <select class="form-control" name="gender" style="width: fit-content;">
                            <!-- BEGIN: gender -->
                            <option value="{GENDER.key}" {GENDER.selected}>{GENDER.title}</option><!-- END: gender -->
                        </select>
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_gender -->
                <!-- BEGIN: show_birthday -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <div class="form-inline">
                            <input name="birthday" id="birthday" class="form-control {FIELD.required} w100" value="{FIELD.value}" maxlength="10" type="text" autocomplete="off">
                        </div>
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_birthday -->
                <!-- BEGIN: show_sig -->
                <tr>
                    <td style="vertical-align:top">
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <textarea name="sig" class="form-control {FIELD.required} w300" cols="70" rows="5">{FIELD.value}</textarea>
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_sig -->
                <!-- BEGIN: show_question -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="question" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_question -->
                <!-- BEGIN: show_answer -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
                    </td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="answer" />
                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_answer -->
                <tr>
                    <td><strong>{LANG.avatar}</strong></td>
                    <td></td>
                    <td>
                        <!-- BEGIN: photo -->
                        <p id="current-photo" class="pull-left text-center">
                            <img id="imageresource" alt="{DATA.username}" src="{IMG.src}" width="{IMG.width}" height="{IMG.height}" class="img-thumbnail fa-pointer" />
                            <span class="fa-pointer" id="current-photo-btn"><em class="fa fa-trash-o fa-lg">&nbsp;</em> {LANG.delete}</span>
                            <input type="hidden" name="delpic" id="photo_delete" value="{DATA.delpic}" />
                        </p>
                        <!-- END: photo -->
                        <div id="change-photo" class="w300">
                            <div class="input-group">
                                <span class="input-group-addon"> <em class="fa fa-trash-o fa-fix fa-pointer" onclick="$('#avatar').val('');">&nbsp;</em> </span>
                                <input type="text" class="form-control" id="avatar" name="photo" value="" readonly="readonly" />
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button" id="btn_upload">
                                        <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.show_email}</strong></td>
                    <td></td>
                    <td><input type="checkbox" name="view_mail" value="1" {DATA.view_mail} /></td>
                </tr>
                <!-- BEGIN: is_official -->
                <tr>
                    <td> {LANG.set_official_note} </td>
                    <td></td>
                    <td><input type="checkbox" name="is_official" value="1" /></td>
                </tr>
                <!-- END: is_official -->
                <!-- BEGIN: group -->
                <tr<!-- BEGIN: hide --> class="hidden"
                    <!-- END: hide --> id="ctn-list-groups">
                    <td style="vertical-align:top"><strong>{LANG.in_group}</strong></td>
                    <td></td>
                    <td>
                        <!-- BEGIN: list -->
                        <div class="clearfix">
                            <label class="pull-left w200"> <input type="checkbox" value="{GROUP.id}" name="group[]" {GROUP.checked} {GROUP.disabled} /> {GROUP.title} </label>
                            <label class="pull-left group_default" {GROUP.default_show}> <input type="radio" value="{GROUP.id}" name="group_default" {GROUP.default} /> {LANG.in_group_default} </label>
                        </div>
                        <!-- END: list -->
                        <div class="clearfix" id="cleargroupdefault" {SHOW_BTN_CLEAR}>
                            <label class="pull-left w200">&nbsp;</label>
                            <label class="pull-left">
                                <a href="#" data-toggle="cleargdefault" class="btn btn-default"><i class="fa fa-times-circle-o" aria-hidden="true"></i> {LANG.clear_group_default}</a>
                            </label>
                        </div>
                    </td>
                    </tr>
                    <!-- END: group -->
                    <tr>
                        <td><strong>{LANG.pass_reset_request}</strong></td>
                        <td></td>
                        <td>
                            <select class="form-control" name="pass_reset_request" style="width: fit-content;">
                                <!-- BEGIN: pass_reset_request -->
                                <option value="{PASSRESET.num}" {PASSRESET.sel}>{PASSRESET.title}</option>
                                <!-- END: pass_reset_request -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>{LANG.adduser_email1}</strong></td>
                        <td></td>
                        <td><label><input type="checkbox" name="adduser_email" value="1" {DATA.adduser_email} /> <small>{LANG.adduser_email1_note1}</small></label></td>
                    </tr>
            </tbody>
        </table>
        <!-- BEGIN: field -->
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields}
            </caption>
            <colgroup>
                <col class="w300" />
                <col class="w20" />
                <col />
            </colgroup>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        <strong>{FIELD.title}</strong>
                        <!-- BEGIN: for_admin-->
                        <div class="small text-muted">({LANG.for_admin})</div>
                        <!-- END: for_admin-->
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
                        <div class="form-inline">
                            <input class="form-control datepicker {FIELD.required} w100" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" />
                        </div>
                        <!-- END: date -->
                        <!-- BEGIN: textarea -->
                        <textarea class="form-control w300" rows="5" cols="70" name="custom_fields[{FIELD.field}]">{FIELD.value}</textarea>
                        <!-- END: textarea -->
                        <!-- BEGIN: editor -->
                        {EDITOR}
                        <!-- END: editor -->
                        <!-- BEGIN: select -->
                        <select class="form-control" name="custom_fields[{FIELD.field}]" style="width: fit-content;">
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

                        <!-- BEGIN: description -->
                        <div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
        <!-- END: field -->
        <!-- BEGIN: changepass -->
        <table class="table table-striped table-bordered table-hover">
            <caption>
                <em class="fa fa-file-text-o">&nbsp;</em>{LANG.edit_password_note}
            </caption>
            <colgroup>
                <col style="width:325px" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <td><strong>{LANG.password}</strong></td>
                    <td>
                        <div class="input-group w300">
                            <input class="form-control password" style="height: 32.5px;" type="text" id="password1" name="password1" value="" maxlength="{NV_UPASSMAX}" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" title="{LANG.random_password}" data-toggle="genpass" data-field1="#password1" data-field2="#password2"><i class="fa fa-retweet"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>{LANG.repassword}</strong></td>
                    <td>
                        <input class="form-control password w300" type="text" name="password2" value="" id="password2" />
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- END: changepass -->
    </div>
    <div class="text-center">
        <input type="hidden" name="confirm" value="1" />
        <input type="hidden" name="checkss" value="{DATA.checkss}" />
        <input type="hidden" name="nv_redirect" value="{NV_REDIRECT}" />
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-spin fa-spinner hidden"></i>
            <span>{LANG.edit_title}</span>
        </button>
    </div>
</form>
<br />
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
<!-- BEGIN: add_photo -->
<script type="text/javascript">
    $(function() {
        $('#change-photo').show();
    });
</script>
<!-- END: add_photo -->
<!-- END: edit_user -->
<!-- END: main -->