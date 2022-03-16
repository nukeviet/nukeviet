<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<form method="post" action="{FORM_ACTION}" onsubmit="return user_editcensor_validForm(this);">
    <!-- BEGIN: basic -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>{LANG.editcensor_info_basic}</caption>
            <thead>
                <tr>
                    <th class="w200">{LANG.editcensor_field}</th>
                    <th class="w250">{LANG.editcensor_current}</th>
                    <th>{LANG.editcensor_new}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: name_show_0 -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" /></td>
                </tr>
                <!-- END: show_last_name -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" /></td>
                </tr>
                <!-- END: show_first_name -->
                <!-- END: name_show_0 -->
                <!-- BEGIN: name_show_1 -->
                <!-- BEGIN: show_first_name -->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="first_name" /></td>
                </tr>
                <!-- END: show_first_name -->
                <!-- BEGIN: show_last_name-->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><input class="form-control {FIELD.required} w300" type="text" value="{FIELD.value}" name="last_name" /></td>
                </tr>
                 <!-- END: show_last_name -->
                <!-- END: name_show_1 -->
                <!-- BEGIN: show_gender -->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{GENDER_OLD}</td>
                    <td class="form-inline">
                        <select class="form-control" name="gender">
                            <!-- BEGIN: gender --><option value="{GENDER.key}"{GENDER.selected}>{GENDER.title}</option><!-- END: gender -->
                        </select>
                    </td>
                </tr>
                <!-- END: show_gender -->
                <!-- BEGIN: show_birthday -->
                <tr>
                    <td> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><input name="birthday" id="birthday" class="form-control {FIELD.required} w100" value="{FIELD.value}" maxlength="10" type="text" /></td>
                </tr>
                <!-- END: show_birthday -->
                <!-- BEGIN: show_sig -->
                <tr>
                    <td style="vertical-align:top"> {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required --> <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description --> </td>
                    <td>{FIELD.valueold}</td>
                    <td class="form-inline"><textarea name="sig" class="form-control {FIELD.required} w300" cols="70" rows="5" >{FIELD.value}</textarea></td>
                </tr>
                <!-- END: show_sig -->
                <tr>
                    <td> {LANG.show_email} </td>
                    <td>{VIEW_MAIL_OLD}</td>
                    <td class="form-inline"><input type="checkbox" name="view_mail" value="1"{VIEW_MAIL_NEW}></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- END: basic -->
    <!-- BEGIN: custom -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <caption>{LANG.editcensor_info_custom}</caption>
            <thead>
                <tr>
                    <th class="w200">{LANG.editcensor_field}</th>
                    <th class="w250">{LANG.editcensor_current}</th>
                    <th>{LANG.editcensor_new}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td>
                        {FIELD.title}<!-- BEGIN: required --> <span class="text-danger">(*)</span><!-- END: required -->
                        <!-- BEGIN: description --><br /><em>{FIELD.description}</em><!-- END: description -->
                    </td>
                    <td>
                        {FIELD.valueold}
                    </td>
                    <td class="form-inline">
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
    </div>
    <!-- END: custom -->
    <div class="text-center">
        <input type="hidden" name="confirm" value="1">
        <button type="submit" name="submit" value="submit" class="btn btn-success"><i class="fa fa-check"></i> {LANG.approved}</button>
        <a href="javascript:void(0);" class="btn btn-danger" onclick="nv_editcensor_row_del({REVIEWUID}, '{LANG.editcensor_confirm_denied}');"><i class="fa fa-trash"></i> {LANG.denied}</a>
    </div>
</form>
<!-- END: main -->
