<!-- BEGIN: main -->
<div id="users">
    <div class="row">
        <div class="col-md-16">
            <div class="form-group">
                <form class="form-inline" role="form" action="{FORM_ACTION}" method="post" onsubmit="nv_check_form(this);return false;">
                    <span><strong>{LANG.search_type}:</strong></span>
                    <select class="form-control" name="method" id="f_method">
                        <option value="">---</option>
                        <!-- BEGIN: method -->
                        <option value="{METHODS.key}" {METHODS.selected}>{METHODS.value}</option>
                        <!-- END: method -->
                    </select>
                    <input class="form-control" type="text" name="value" id="f_value" value="{SEARCH_VALUE}" />
                    <input class="btn btn-primary" name='search' type="submit" value="{LANG.submit}" />
                    <div class="help-block">{LANG.search_note}</div>
                </form>
            </div>
        </div>
        <!-- BEGIN: resend_email -->
        <div class="col-md-8">
            <div class="form-group text-right">
                <a href="{RESEND_URL}" class="btn btn-primary"><i class="fa fa-paper-plane-o"></i> {LANG.userwait_resend_email}</a>
            </div>
        </div>
        <!-- END: resend_email -->
    </div>
    <!-- BEGIN: userlist -->
    <!-- BEGIN: warning -->
    <div class="alert alert-info">{LANG.warning}</div>
    <!-- END: warning -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <!-- BEGIN: head_td -->
                    <th class="text-center text-nowrap"><a href="{HEAD_TD.href}">{HEAD_TD.title}</a></th>
                    <!-- END: head_td -->
                    <th class="text-center text-nowrap" style="width:1%">{LANG.funcs}</th>
                </tr>
            </thead>
            <!-- BEGIN: generate_page -->
            <tfoot>
                <tr>
                    <td colspan="8"> {GENERATE_PAGE} </td>
                </tr>
            </tfoot>
            <!-- END: generate_page -->
            <tbody>
                <!-- BEGIN: xusers -->
                <tr>
                    <td class="text-center text-nowrap" style="width:1%"> {CONTENT_TD.userid} </td>
                    <td> {CONTENT_TD.username} </td>
                    <td> {CONTENT_TD.full_name} </td>
                    <td><a href="mailto:{CONTENT_TD.email}">{CONTENT_TD.email}</a></td>
                    <td class="text-center text-nowrap" style="width:1%"> {CONTENT_TD.regdate} </td>
                    <td class="text-center text-nowrap" style="width:1%">
                        <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ACTIVATE_URL}">{LANG.censorship}</a> &nbsp;
                        <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_waiting_row_del({CONTENT_TD.userid}, '{CONTENT_TD.checkss}');">{LANG.delete}</a>
                    </td>
                </tr>
                <!-- END: xusers -->
            </tbody>
        </table>
    </div>
    <!-- END: userlist -->
</div>
<!-- END: main -->

<!-- BEGIN: user_details -->
<link type="text/css" href="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{ASSETS_LANG_STATIC_URL}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<form role="form" action="{FORM_ACTION}" method="post" id="user_details">
    <div class="row">
        <div class="col-md-18 col-lg-14">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <colgroup>
                        <col class="w200" />
                        <col />
                    </colgroup>
                    <tbody>
                        <tr>
                            <td>{LANG.account} <span class="text-danger">(*)</span></td>
                            <td><input type="text" class="form-control required" value="{DATA.username}" name="username" id="username_iavim" maxlength="{NV_UNICKMAX}" /></td>
                        </tr>
                        <tr>
                            <td>{LANG.email} <span class="text-danger">(*)</span></td>
                            <td><input type="email" class="form-control email required" value="{DATA.email}" name="email" id="email_iavim" /></td>
                        </tr>
                        <tr>
                            <td>{LANG.password}</td>
                            <td>
                                <div class="input-group">
                                    <input class="form-control password" style="height: 32.5px;" type="text" id="password1" name="password" value="" maxlength="{NV_UPASSMAX}" />
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" title="{LANG.random_password}" data-toggle="genpass" data-field1="#password1" data-field2="#password2"><i class="fa fa-retweet"></i></button>
                                    </span>
                                </div>
                                <div class="help-block mb-0">{LANG.leave_blank_note}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>{LANG.repassword}</td>
                            <td>
                                <input class="form-control password" type="text" name="re_password" value="" id="password2" />
                                <div class="help-block mb-0">{LANG.leave_blank_note}</div>
                            </td>
                        </tr>
                        <tr>
                            <td> {LANG.pass_reset_request} </td>
                            <td>
                                <select class="form-control" name="pass_reset_request">
                                    <!-- BEGIN: pass_reset_request -->
                                    <option value="{PASSRESET.num}">{PASSRESET.title}</option>
                                    <!-- END: pass_reset_request -->
                                </select>
                            </td>
                        </tr>
                        <!-- BEGIN: name_show_0 -->
                        <!-- BEGIN: show_last_name-->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input class="form-control {FIELD.required}" type="text" value="{FIELD.value}" name="last_name" />
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_last_name -->
                        <!-- BEGIN: show_first_name -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input class="form-control {FIELD.required}" type="text" value="{FIELD.value}" name="first_name" />
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_first_name -->
                        <!-- END: name_show_0 -->
                        <!-- BEGIN: name_show_1 -->
                        <!-- BEGIN: show_first_name -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input class="form-control {FIELD.required}" type="text" value="{FIELD.value}" name="first_name" />
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_first_name -->
                        <!-- BEGIN: show_last_name-->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input class="form-control {FIELD.required}" type="text" value="{FIELD.value}" name="last_name" />
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_last_name -->
                        <!-- END: name_show_1 -->
                        <!-- BEGIN: show_gender -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <select class="form-control w150" name="gender">
                                    <!-- BEGIN: gender -->
                                    <option value="{GENDER.key}" {GENDER.selected}>{GENDER.title}</option><!-- END: gender -->
                                </select>
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_gender -->
                        <!-- BEGIN: show_birthday -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input name="birthday" class="form-control mydatepicker {FIELD.required} w150" value="{FIELD.value}" maxlength="10" type="text" autocomplete="off">
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_birthday -->
                        <!-- BEGIN: show_sig -->
                        <tr>
                            <td style="vertical-align:top"> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <textarea name="sig" class="form-control {FIELD.required}" cols="70" rows="5">{FIELD.value}</textarea>
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: show_sig -->
                        <!-- BEGIN: show_question -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <div class="input-group item">
                                    <input class="form-control {FIELD.required}" type="text" value="" name="question" />
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <!-- BEGIN: question -->
                                            <li><a href="#" class="question">{QUESTION}</a></li>
                                            <!-- END: question -->
                                        </ul>
                                    </div>
                                </div>
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                                <div class="help-block mb-0">{LANG.leave_blank_note}</div>
                            </td>
                        </tr>
                        <!-- END: show_question -->
                        <!-- BEGIN: show_answer -->
                        <tr>
                            <td> {FIELD.title}
                                <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <input class="form-control {FIELD.required}" type="text" value="" name="answer" />
                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                                <div class="help-block mb-0">{LANG.leave_blank_note}</div>
                            </td>
                        </tr>
                        <!-- END: show_answer -->
                    </tbody>
                </table>
                <!-- BEGIN: field -->
                <table class="table table-striped table-bordered table-hover">
                    <caption><em class="fa fa-file-text-o">&nbsp;</em>{LANG.fields} </caption>
                    <colgroup>
                        <col class="w200" />
                    </colgroup>
                    <tbody>
                        <!-- BEGIN: loop -->
                        <tr>
                            <td>
                                <strong>{FIELD.title}</strong> <!-- BEGIN: required --><span class="text-danger">(*)</span><!-- END: required -->
                            </td>
                            <td>
                                <!-- BEGIN: textbox -->
                                <input class="form-control {FIELD.required}" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" />
                                <!-- END: textbox -->
                                <!-- BEGIN: date -->
                                <input class="form-control mydatepicker {FIELD.required} w150" type="text" name="custom_fields[{FIELD.field}]" value="{FIELD.value}" />
                                <!-- END: date -->
                                <!-- BEGIN: textarea -->
                                <textarea class="form-control" rows="5" cols="70" name="custom_fields[{FIELD.field}]">{FIELD.value}</textarea>
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

                                <!-- BEGIN: description --><div class="help-block mb-0">{FIELD.description}</div><!-- END: description -->
                            </td>
                        </tr>
                        <!-- END: loop -->
                    </tbody>
                </table>
                <!-- END: field -->
            </div>
            <div class="text-center">
                <input type="hidden" name="checkss" value="{DATA.checkss}" />
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-spin fa-spinner hidden"></i>
                    <span>{LANG.awaiting_active}</span>
                </button>
                <button class="btn btn-default user-delete" data-userid="{DATA.userid}" type="button">
                    {LANG.delete}
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END: user_details -->