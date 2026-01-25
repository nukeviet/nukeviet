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
                    <td> {GLANG.username} </td>
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
                    <td>
                        <div class="input-group w300">
                            <input class="form-control password" style="height: 32.5px;" type="text" id="pass_iavim" name="password1" value="" maxlength="{NV_UPASSMAX}" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" title="{LANG.random_password}" data-toggle="genpass" data-field1="#pass_iavim" data-field2="#password2"><i class="fa fa-retweet"></i></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> {LANG.repassword} </td>
                    <td> <span class="text-danger">(*)</span> </td>
                    <td>
                        <input class="form-control required password w300" type="text" name="password2" value="" id="password2" />
                    </td>
                </tr>
                <tr>
                    <td> {LANG.pass_reset_request} </td>
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
                    <td> {LANG.email_reset_request} </td>
                    <td></td>
                    <td>
                        <select class="form-control" name="email_reset_request" style="width: fit-content;">
                            <!-- BEGIN: email_reset_request -->
                            <option value="{EMAILRESET.num}" {EMAILRESET.sel}>{EMAILRESET.title}</option>
                            <!-- END: email_reset_request -->
                        </select>
                    </td>
                </tr>
                <!-- BEGIN: name_show_0 -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_last_name -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_first_name -->
                <!-- END: name_show_0 -->
                <!-- BEGIN: name_show_1 -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_first_name -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_last_name -->
                <!-- END: name_show_1 -->
                <!-- BEGIN: show_gender -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <select class="form-control" name="gender" style="width: fit-content;">
                            <!-- BEGIN: gender -->
                            <option value="{GENDER.key}" {GENDER.selected}>{GENDER.title}</option>
                            <!-- END: gender -->
                        </select>
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_gender -->
                <!-- BEGIN: show_birthday -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <div class="form-inline">
                            <input name="birthday" id="birthday" class="form-control {FIELD.required} w100" value="{FIELD.value}" maxlength="10" type="text" autocomplete="off">
                        </div>
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_birthday -->
                <!-- BEGIN: show_sig -->
                <tr>
                    <td style="vertical-align:top"> {FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <textarea name="sig" class="form-control {FIELD.required} w300" cols="70" rows="5">{FIELD.value}</textarea>
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_sig -->
                <!-- BEGIN: show_question -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="question" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_question -->
                <!-- BEGIN: show_answer -->
                <tr>
                    <td>{FIELD.title}</td>
                    <td>
                        <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                    </td>
                    <td>
                        <input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="answer" />
                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: show_answer -->
                <tr>
                    <td> {LANG.avatar} </td>
                    <td></td>
                    <td>
                        <div class="input-group w300">
                            <input type="text" class="form-control" id="avatar" name="photo" value="" readonly="readonly" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" id="btn_upload"> <em class="fa fa-folder-open-o fa-fix">&nbsp;</em></button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td> {LANG.show_email} </td>
                    <td></td>
                    <td><input type="checkbox" name="view_mail" value="1" {DATA.view_mail} /></td>
                </tr>
                <tr>
                    <td> {LANG.is_official} </td>
                    <td></td>
                    <td><label><input type="checkbox" name="is_official" value="1" {DATA.is_official} /> <small>{LANG.is_official_note}</small></label></td>
                </tr>
                <!-- BEGIN: group -->
                <tr id="ctn-list-groups">
                    <td style="vertical-align:top"> {LANG.in_group} </td>
                    <td></td>
                    <td>
                        <!-- BEGIN: list -->
                        <div class="clearfix">
                            <label class="pull-left w200">
                                <input type="checkbox" value="{GROUP.id}" name="group[]" {GROUP.checked} /> {GROUP.title}
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
                    <td><label><input type="checkbox" name="adduser_email" value="1" {DATA.adduser_email} /> <small>{LANG.adduser_email1_note}</small></label></td>
                </tr>
                <tr>
                    <td> {LANG.is_email_verified} </td>
                    <td></td>
                    <td><label><input type="checkbox" name="is_email_verified" value="1" {DATA.is_email_verified}> <small>{LANG.is_email_verified1}</small></label></td>
                </tr>
            </tbody>
        </table>
        <!-- BEGIN: field -->
        <table class="table table-striped table-bordered table-hover">
            <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields} </caption>
            <colgroup>
                <col class="w300" />
                <col class="w20" />
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
                        <!-- BEGIN: file -->
                        <div class="filelist" data-field="{FIELD.field}" data-oclass="{FIELD.class}" data-maxnum="{FILEMAXNUM}">
                            <ul class="list-unstyled items"></ul>
                            <div><button type="button" class="btn btn-info btn-xs" data-toggle="addfilebtn" data-modal="uploadfile_{FIELD.field}"><i class="fa fa-upload"></i> {LANG.addfile}</button></div>
                            <!-- START FORFOOTER -->
                            <div class="modal fade uploadfile" tabindex="-1" role="dialog" id="uploadfile_{FIELD.field}" data-url="{URL_MODULE}" data-field="{FIELD.field}" data-csrf="{CSRF}" data-accept="{FILEACCEPT}" data-maxsize="{FILEMAXSIZE}" data-ext-error="{LANG.addfile_ext_error}" data-size-error="{LANG.addfile_size_error}" data-size-error2="{LANG.addfile_size_error2}" data-delete="{LANG.delete}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">{LANG.addfile}</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p class="fileinput" style="display:flex;justify-content:center;margin-top:20px;margin-bottom:20px"></p>
                                            <ul class="list-unstyled help-block small">
                                                <li>- {LANG.accepted_extensions}: {FILEACCEPT}</li>
                                                <li>- {LANG.field_file_max_size}: {FILEMAXSIZE_FORMAT}</li>
                                                <!-- BEGIN: widthlimit -->
                                                <li>- {WIDTHLIMIT}</li><!-- END: widthlimit -->
                                                <!-- BEGIN: heightlimit -->
                                                <li>- {HEIGHTLIMIT}</li><!-- END: heightlimit -->
                                            </ul>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END FORFOOTER -->
                        </div>
                        <!-- END: file -->

                        <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
        <!-- END: field -->
    </div>
    <!-- BEGIN: admin_add -->
    <div class="text-center mb">
        <label>
            <input type="checkbox" name="admin_add" value="1">{LANG.admin_add}
        </label>
    </div>
    <!-- END: admin_add -->
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
