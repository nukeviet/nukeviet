<form class="user-reg-form" action="{USER_REGISTER}" method="post" data-toggle="reg_validForm" autocomplete="off" novalidate<!-- BEGIN: reg_captcha --> data-captcha="nv_seccode"<!-- END: reg_captcha --><!-- BEGIN: reg_recaptcha --> data-recaptcha2="1"<!-- END: reg_recaptcha --><!-- BEGIN: reg_recaptcha3 --> data-recaptcha3="1"<!-- END: reg_recaptcha3 -->>
    <div class="nv-info margin-bottom" data-default="{LANG.info}">{LANG.info}</div>

    <div class="form-detail">
        <!-- BEGIN: name_show_0 -->
        <!-- BEGIN: show_last_name-->
        <div class="form-group">
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.field_unane_error}"<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_last_name-->
        <!-- BEGIN: show_first_name-->
        <div class="form-group">
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.field_unane_error}"<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_first_name-->
        <!-- END: name_show_0 -->

        <!-- BEGIN: name_show_1 -->
        <!-- BEGIN: show_first_name-->
        <div class="form-group">
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="first_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_first_name-->
        <!-- BEGIN: show_last_name-->
        <div class="form-group">
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="last_name" maxlength="{FIELD.max_length}" data-toggle="validErrorHidden" data-event="keypress" data-mess=""<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}" data-error="{ERRMESS}"<!-- END: data_callback-->>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_last_name-->
        <!-- END: name_show_1 -->

        <div class="form-group">
            <div>
                <input type="text" class="required form-control" placeholder="{LANG.account}" value="" name="username" maxlength="{NICK_MAXLENGTH}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{USERNAME_RULE}" data-callback="login_check" data-minlength="{NICK_MINLENGTH}" data-type="{LOGINTYPE}">
            </div>
        </div>

        <div class="form-group">
            <div>
                <input type="email" class="required form-control" placeholder="{LANG.email}" value="" name="email" maxlength="100" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.email_empty}">
            </div>
        </div>

        <div class="form-group">
            <div>
                <input type="password" autocomplete="off" class="password required form-control" placeholder="{LANG.password}" value="" name="password" maxlength="{PASS_MAXLENGTH}" data-pattern="{PASSWORD_PATTERN}" data-toggle="validErrorHidden" data-event="keypress" data-mess="{PASSWORD_RULE}">
            </div>
        </div>

        <div class="form-group">
            <div>
                <input type="password" autocomplete="off" class="re-password required form-control" placeholder="{LANG.re_password}" value="" name="re_password" maxlength="{PASS_MAXLENGTH}" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.re_password_empty}">
            </div>
        </div>

        <!-- BEGIN: show_gender -->
        <div>
            <div>
                <div class="form-group clearfix radio-box {FIELD.required} {FIELD.class}" data-mess="">
                    <label class="col-sm-8 control-label {FIELD.required} {FIELD.class}" title="{FIELD.description}"> {FIELD.title} </label>
                    <div class="btn-group col-sm-16">
                        <!-- BEGIN: gender -->
                        <label class="radio-box"> <input type="radio" name="gender" value="{GENDER.key}" class="{FIELD.class}" data-toggle="validErrorHidden" data-event="click" data-parents="5" {GENDER.checked}> {GENDER.title} </label>
                        <!-- END: gender -->
                    </div>
                    <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                </div>
            </div>
        </div>
        <!-- END: show_gender -->

        <!-- BEGIN: show_birthday -->
        <div class="form-group">
            <div class="input-group" style="z-index: 1;">
                <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="birthday" readonly="readonly" style="background-color:#fff" data-toggle="validErrorHidden" data-event="change" data-focus="datepickerShow" data-mess=""/>
                <span class="input-group-addon pointer" data-toggle="button_datepickerShow"> <em class="fa fa-calendar"></em> </span>
            </div>
            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
        </div>
        <!-- END: show_birthday -->

        <!-- BEGIN: show_sig -->
        <div class="form-group">
            <div>
                <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="sig" data-toggle="validErrorHidden" data-event="keypress" data-mess="">{FIELD.value}</textarea>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_sig -->

        <!-- BEGIN: show_question -->
        <div class="form-group rel">
            <div class="input-group">
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="question" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.your_question_empty}">
                <div class="input-group-btn" role="group">
                    <button type="button" class="btn btn-default pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <!-- BEGIN: frquestion -->
                        <li><a href="#" data-toggle="addQuestion">{QUESTION}</a></li>
                        <!-- END: frquestion -->
                    </ul>
                </div>
            </div>
            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
        </div>
        <!-- END: show_question -->

        <!-- BEGIN: show_answer -->
        <div class="form-group">
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="answer" maxlength="{FIELD.max_length}" data-pattern="/^(.){{FIELD.min_length},}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.answer_empty}">
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: show_answer -->

        <!-- BEGIN: field -->
        <!-- BEGIN: loop -->
        <!-- BEGIN: textbox -->
        <div class="form-group">
            <label for="nvcf-{FIELD.field}">{FIELD.title}:</label>
            <div>
                <input type="text" class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" data-toggle="validErrorHidden" data-event="keypress" data-mess="" id="nvcf-{FIELD.field}"<!-- BEGIN: data_callback--> data-callback="{CALLFUNC}"  data-error="{ERRMESS}"<!-- END: data_callback-->>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: textbox -->

        <!-- BEGIN: date -->
        <div class="form-group">
            <label for="nvcf-{FIELD.field}">{FIELD.title}:</label>
            <div class="input-group" style="z-index: 1;">
                <input type="text" class="form-control datepicker {FIELD.required} {FIELD.class}" data-provide="datepicker" placeholder="{FIELD.title}" value="{FIELD.value}" name="custom_fields[{FIELD.field}]" readonly="readonly" style="background-color:#fff" data-toggle="validErrorHidden" data-event="change" data-focus="datepickerShow" data-mess="" id="nvcf-{FIELD.field}">
                <span class="input-group-addon pointer" data-toggle="button_datepickerShow"> <em class="fa fa-calendar"></em> </span>
            </div>
            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
        </div>
        <!-- END: date -->

        <!-- BEGIN: textarea -->
        <div class="form-group">
            <label for="nvcf-{FIELD.field}">{FIELD.title}:</label>
            <div>
                <textarea class="form-control {FIELD.required} {FIELD.class}" placeholder="{FIELD.title}" name="custom_fields[{FIELD.field}]" data-toggle="validErrorHidden" data-event="keypress" data-mess="" id="nvcf-{FIELD.field}">{FIELD.value}</textarea>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: textarea -->

        <!-- BEGIN: editor -->
        <div class="form-group">
            <label>{FIELD.title}:</label>
            {EDITOR}
            <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
        </div>
        <!-- END: editor -->

        <!-- BEGIN: select -->
        <div class="form-group">
            <label for="nvcf-{FIELD.field}">{FIELD.title}:</label>
            <div>
                <select name="custom_fields[{FIELD.field}]" class="form-control {FIELD.required} {FIELD.class}" data-toggle="validErrorHidden" data-event="change" data-mess="" id="nvcf-{FIELD.field}">
                    <!-- BEGIN: loop -->
                    <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}> {FIELD_CHOICES.value} </option>
                    <!-- END: loop -->
                </select>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: select -->

        <!-- BEGIN: radio -->
        <div>
            <div>
                <div class="form-group clearfix radio-box {FIELD.required}" data-mess="">
                    <div class="margin-bottom-sm">
                        <label class="control-label text-normal {FIELD.required}" title="{FIELD.description}"> {FIELD.title}</label>
                    </div>
                    <!-- BEGIN: loop -->
                    <label for="lb_{FIELD_CHOICES.id}" class="radio-box"><input type="radio" name="custom_fields[{FIELD.field}]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" data-toggle="validErrorHidden" data-event="click" data-parents="5"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br/>
                    <!-- END: loop -->
                    <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                </div>
            </div>
        </div>
        <!-- END: radio -->

        <!-- BEGIN: checkbox -->
        <div>
            <div>
                <div class="form-group clearfix check-box {FIELD.required}" data-mess="">
                    <div class="margin-bottom-sm">
                        <label class="control-label text-normal {FIELD.required}" title="{FIELD.description}"> {FIELD.title}</label>
                    </div>
                    <!-- BEGIN: loop -->
                    <label for="lb_{FIELD_CHOICES.id}" class="check-box"><input type="checkbox" name="custom_fields[{FIELD.field}][]" id="lb_{FIELD_CHOICES.id}" value="{FIELD_CHOICES.key}" class="{FIELD.class}" style="margin-top:0" data-toggle="validErrorHidden" data-event="click" data-parents="5"{FIELD_CHOICES.checked}> {FIELD_CHOICES.value} </label><br/>
                    <!-- END: loop -->
                    <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
                </div>
            </div>
        </div>
        <!-- END: checkbox -->

        <!-- BEGIN: multiselect -->
        <div class="form-group">
            <label for="nvcf-{FIELD.field}">{FIELD.title}:</label>
            <div>
                <select name="custom_fields[{FIELD.field}][]" multiple="multiple" class="{FIELD.class} {FIELD.required} form-control" data-toggle="validErrorHidden" data-event="change" data-mess="" id="nvcf-{FIELD.field}">
                    <!-- BEGIN: loop -->
                    <option value="{FIELD_CHOICES.key}" {FIELD_CHOICES.selected}>{FIELD_CHOICES.value}</option>
                    <!-- END: loop -->
                </select>
                <!-- BEGIN: description --><div class="small help-block" style="margin-bottom:0">{FIELD.description}</div><!-- END: description -->
            </div>
        </div>
        <!-- END: multiselect -->
        <!-- END: loop -->
        <!-- END: field -->

        <!-- BEGIN: agreecheck -->
        <div>
            <div>
                <div class="form-group text-center check-box required" data-mess="">
                    <input type="checkbox" name="agreecheck" value="1" class="fix-box" style="margin-top:0" data-toggle="validErrorHidden" data-event="click" data-parents="3"/>{LANG.accept2} <a href="#" data-toggle="usageTermsShow" data-title="{LANG.usage_terms}"><span class="btn btn-default btn-xs">{LANG.usage_terms}</span></a>
                </div>
            </div>
        </div>
        <!-- END: agreecheck -->

        <div class="text-center margin-bottom-lg">
            <input type="hidden" name="checkss" value="{CHECKSS}" />
            <!-- BEGIN: redirect --><input name="nv_redirect" value="{REDIRECT}" type="hidden" /><!-- END: redirect -->
            <input type="button" value="{GLANG.reset}" class="btn btn-default" data-toggle="validReset"/>
            <input type="submit" class="btn btn-primary" value="{LANG.register}"/>
        </div>
        <!-- BEGIN: lostactivelink -->
        <div class="text-center">
            <a href="{LOSTACTIVELINK_SRC}">{LANG.resend_activelink}</a>
        </div>
        <!-- END: lostactivelink -->
    </div>
</form>
