<form class="w-100" action="{$USER_REGISTER}" method="post" data-toggle="ajax-form"
    data-form="userRegister" data-precheck="nv_precheck_form"
    autocomplete="off" novalidate{$CAPTCHA_ATTRS}
>
    <div class="alert alert-info py-2 mb-3" data-area="info" data-default="{$LANG->getModule('info')}">{$LANG->getModule('info')}</div>
    <div data-area="form">
        <div class="row g-3">
            {* Họ và tên hiển thị theo cấu hình name_show *}
            {if $GCONFIG.name_show == 1}
                {if isset($SYSTEM.first_name)}
                <div class="col-md-6">
                    <label class="form-label" for="reg_first_name">{$SYSTEM.first_name.title}{if $SYSTEM.first_name.required} <span class="text-danger">*</span>{/if}</label>
                    <input type="text" class="form-control {$SYSTEM.first_name.class}" id="reg_first_name"
                        placeholder="{$SYSTEM.first_name.title}" value="{$SYSTEM.first_name.value}"
                        name="first_name" autocomplete="given-name"
                        minlength="{$SYSTEM.first_name.min_length}"
                        maxlength="{$SYSTEM.first_name.max_length}"
                        {if $SYSTEM.first_name.required or $SYSTEM.first_name.callfunc} data-valid data-error-type="feedback"{/if}
                        {if not $SYSTEM.first_name.required} data-allowed-empty="1"{/if}
                        {if $SYSTEM.first_name.callfunc} data-valid-callback="{$SYSTEM.first_name.callfunc}"{/if}
                        data-error-mess="{$SYSTEM.first_name.errmess}"
                    >
                    <div class="invalid-feedback"></div>
                    {if $SYSTEM.first_name.description}<div class="form-text">{$SYSTEM.first_name.description}</div>{/if}
                </div>
                {/if}
                {if isset($SYSTEM.last_name)}
                <div class="col-md-6">
                    <label class="form-label" for="reg_last_name">{$SYSTEM.last_name.title}{if $SYSTEM.last_name.required} <span class="text-danger">*</span>{/if}</label>
                    <input type="text" class="form-control {$SYSTEM.last_name.class}" id="reg_last_name"
                        placeholder="{$SYSTEM.last_name.title}" value="{$SYSTEM.last_name.value}"
                        name="last_name"
                        minlength="{$SYSTEM.last_name.min_length}"
                        maxlength="{$SYSTEM.last_name.max_length}"
                        {if $SYSTEM.last_name.required or $SYSTEM.last_name.callfunc} data-valid data-error-type="feedback"{/if}
                        {if not $SYSTEM.last_name.required} data-allowed-empty="1"{/if}
                        {if $SYSTEM.last_name.callfunc} data-valid-callback="{$SYSTEM.last_name.callfunc}"{/if}
                        data-error-mess="{$SYSTEM.last_name.errmess}"
                    >
                    <div class="invalid-feedback"></div>
                    {if $SYSTEM.last_name.description}<div class="form-text">{$SYSTEM.last_name.description}</div>{/if}
                </div>
                {/if}
            {else}
                {if isset($SYSTEM.last_name)}
                <div class="col-md-6">
                    <label class="form-label" for="reg_last_name">{$SYSTEM.last_name.title}{if $SYSTEM.last_name.required} <span class="text-danger">*</span>{/if}</label>
                    <input type="text" class="form-control {$SYSTEM.last_name.class}" id="reg_last_name"
                        placeholder="{$SYSTEM.last_name.title}" value="{$SYSTEM.last_name.value}"
                        name="last_name" maxlength="{$SYSTEM.last_name.max_length}"
                        {if $SYSTEM.last_name.required or $SYSTEM.last_name.callfunc} data-valid data-error-type="feedback"{/if}
                        {if not $SYSTEM.last_name.required} data-allowed-empty="1"{/if}
                        {if $SYSTEM.last_name.callfunc} data-valid-callback="{$SYSTEM.last_name.callfunc}"{/if}
                        data-error-mess="{$SYSTEM.last_name.errmess}"
                    >
                    <div class="invalid-feedback"></div>
                    {if $SYSTEM.last_name.description}<div class="form-text">{$SYSTEM.last_name.description}</div>{/if}
                </div>
                {/if}
                {if isset($SYSTEM.first_name)}
                <div class="col-md-6">
                    <label class="form-label" for="reg_first_name">{$SYSTEM.first_name.title}{if $SYSTEM.first_name.required} <span class="text-danger">*</span>{/if}</label>
                    <input type="text" class="form-control {$SYSTEM.first_name.class}" id="reg_first_name"
                        placeholder="{$SYSTEM.first_name.title}" value="{$SYSTEM.first_name.value}"
                        name="first_name" autocomplete="given-name" maxlength="{$SYSTEM.first_name.max_length}"
                        {if $SYSTEM.first_name.required or $SYSTEM.first_name.callfunc} data-valid data-error-type="feedback"{/if}
                        {if not $SYSTEM.first_name.required} data-allowed-empty="1"{/if}
                        {if $SYSTEM.first_name.callfunc} data-valid-callback="{$SYSTEM.first_name.callfunc}"{/if}
                        data-error-mess="{$SYSTEM.first_name.errmess}"
                    >
                    <div class="invalid-feedback"></div>
                    {if $SYSTEM.first_name.description}<div class="form-text">{$SYSTEM.first_name.description}</div>{/if}
                </div>
                {/if}
            {/if}

            {* Tên đăng nhập *}
            <div class="col-md-6">
                <label class="form-label" for="reg_username">{$LANG->getModule('username')} <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" class="form-control" id="reg_username"
                        placeholder="{$LANG->getModule('username')}" value="" name="username" autocomplete="username"
                        minlength="{$GCONFIG.nv_unickmin}" maxlength="{$GCONFIG.nv_unickmax}"
                        data-valid data-error-type="feedback" data-valid-callback="userRegLoginCheck"
                        data-login-type="{$GCONFIG.nv_unick_type}" data-error-mess="{$USERNAME_RULE}"
                    >
                </div>
                <div class="invalid-feedback"></div>
            </div>

            {* Email *}
            <div class="col-md-6">
                <label class="form-label" for="reg_email">{$LANG->getModule('email')} <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" class="form-control" id="reg_email" placeholder="{$LANG->getModule('email')}" value=""
                        name="email" maxlength="100" autocomplete="email" data-valid data-error-type="feedback"
                        data-error-mess="{$LANG->getGlobal('email_required_rule')}"
                    >
                </div>
                <div class="invalid-feedback"></div>
            </div>

            {* Mật khẩu *}
            <div class="col-md-6">
                <label class="form-label" for="reg_password">{$LANG->getModule('password')} <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="reg_password" autocomplete="new-password"
                        placeholder="{$LANG->getModule('password')}" value="" name="password"
                        maxlength="{$GCONFIG.nv_upassmax}" data-valid data-error-type="feedback"
                        data-pattern="{$PASSWORD_PATTERN}" data-error-mess="{$PASSWORD_RULE}"
                    >
                </div>
                <div class="invalid-feedback"></div>
            </div>

            {* Nhập lại mật khẩu *}
            <div class="col-md-6">
                <label class="form-label" for="reg_re_password">{$LANG->getModule('re_password')} <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" class="form-control" id="reg_re_password" autocomplete="new-password" placeholder="{$LANG->getModule('re_password')}" value="" name="re_password" maxlength="{$GCONFIG.nv_upassmax}" data-valid data-error-type="feedback" data-error-mess="{$LANG->getGlobal('re_password_empty')}">
                </div>
                <div class="invalid-feedback"></div>
            </div>

            {* Giới tính *}
            {if isset($SYSTEM.gender)}
            <div class="col-md-6">
                <label class="form-label d-block" for="reg_gender_M">{$SYSTEM.gender.title}{if $SYSTEM.gender.required} <span class="text-danger">*</span>{/if}</label>
                <div class="d-flex flex-wrap gap-2">
                    {foreach from=$SYSTEM.gender.genders item=gender}
                    <input class="btn-check" type="radio" name="gender" id="reg_gender_{$gender.key}" value="{$gender.key}"{if $gender.checked} checked{/if}{if $SYSTEM.gender.required} data-valid data-error-type="feedback" data-min="1" data-max="1" data-error-mess="{$SYSTEM.gender.title}"{/if}>
                    <label class="btn btn-outline-primary" for="reg_gender_{$gender.key}">{$gender.title}</label>
                    {/foreach}
                    {* Thẻ báo lỗi phải nằm ngay sau label cuối để validator tìm thấy, w-100 để xuống dòng riêng *}
                    <div class="invalid-feedback w-100"></div>
                </div>
                {if $SYSTEM.gender.description}<div class="form-text">{$SYSTEM.gender.description}</div>{/if}
            </div>
            {/if}

            {* Ngày sinh *}
            {if isset($SYSTEM.birthday)}
            <div class="col-md-6">
                <label class="form-label" for="reg_birthday">{$SYSTEM.birthday.title}{if $SYSTEM.birthday.required} <span class="text-danger">*</span>{/if}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
                    <input type="text" class="form-control {$SYSTEM.birthday.class}" id="reg_birthday"
                        name="birthday" value="{$SYSTEM.birthday.value}"
                        data-provide="datepicker" data-min-old="{$SYSTEM.birthday.min_old_user}"
                        placeholder="{$SYSTEM.birthday.title}"
                        {if $SYSTEM.birthday.required} data-valid data-error-type="feedback"{/if}
                        data-error-mess="{$SYSTEM.birthday.errmess}"
                    >
                </div>
                <div class="invalid-feedback"></div>
                {if $SYSTEM.birthday.description}<div class="form-text">{$SYSTEM.birthday.description}</div>{/if}
            </div>
            {/if}

            {* Chữ ký *}
            {if isset($SYSTEM.sig)}
            <div class="col-12">
                <label class="form-label" for="reg_sig">{$SYSTEM.sig.title}{if $SYSTEM.sig.required} <span class="text-danger">*</span>{/if}</label>
                <textarea class="form-control {$SYSTEM.sig.class}" id="reg_sig" placeholder="{$SYSTEM.sig.title}" name="sig" rows="3"{if $SYSTEM.sig.required} data-valid data-error-type="feedback" data-error-mess="{$SYSTEM.sig.title}"{/if}>{$SYSTEM.sig.value}</textarea>
                <div class="invalid-feedback"></div>
                {if $SYSTEM.sig.description}<div class="form-text">{$SYSTEM.sig.description}</div>{/if}
            </div>
            {/if}

            {* Câu hỏi bảo mật *}
            {if isset($SYSTEM.question)}
            <div class="col-md-6">
                <label class="form-label" for="reg_question">{$SYSTEM.question.title}{if $SYSTEM.question.required} <span class="text-danger">*</span>{/if}</label>
                <div class="input-group">
                    <input type="text" class="form-control {$SYSTEM.question.class}" id="reg_question"
                        placeholder="{$SYSTEM.question.title}" value="{$SYSTEM.question.value}"
                        name="question" maxlength="{$SYSTEM.question.max_length}"
                        minlength="{$SYSTEM.question.min_length}"
                        {if $SYSTEM.question.required} data-valid data-error-type="feedback"{/if}
                        data-error-mess="{$SYSTEM.question.errmess}"
                    >
                    {if not empty($QUESTIONS)}
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="{$SYSTEM.question.title}"></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        {foreach from=$QUESTIONS item=question}
                        <li><a class="dropdown-item" href="#" data-toggle="addQuestion">{$question.title}</a></li>
                        {/foreach}
                    </ul>
                    {/if}
                </div>
                <div class="invalid-feedback"></div>
                {if $SYSTEM.question.description}<div class="form-text">{$SYSTEM.question.description}</div>{/if}
            </div>
            {/if}

            {* Câu trả lời *}
            {if isset($SYSTEM.answer)}
            <div class="col-md-6">
                <label class="form-label" for="reg_answer">{$SYSTEM.answer.title}{if $SYSTEM.answer.required} <span class="text-danger">*</span>{/if}</label>
                <input type="text" class="form-control {$SYSTEM.answer.class}" id="reg_answer"
                    placeholder="{$SYSTEM.answer.title}" value="{$SYSTEM.answer.value}"
                    name="answer" maxlength="{$SYSTEM.answer.max_length}"
                    minlength="{$SYSTEM.answer.min_length}"
                    {if $SYSTEM.answer.required} data-valid data-error-type="feedback"{/if}
                    data-error-mess="{$SYSTEM.answer.errmess}"
                >
                <div class="invalid-feedback"></div>
                {if $SYSTEM.answer.description}<div class="form-text">{$SYSTEM.answer.description}</div>{/if}
            </div>
            {/if}

            {* Các trường tùy chỉnh *}
            {if not empty($FIELDS)}
            <div class="col-12">
                <hr class="mt-2 mb-0">
            </div>
            {include file='custom_fields_form.tpl'}
            {/if}

            {* Đồng ý điều khoản *}
            {if $SHOW_AGREECHECK}
            <div class="col-12">
                <hr class="mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="agreecheck" value="1" id="reg_agreecheck" data-valid data-error-type="feedback" data-min="1" data-max="1" data-error-mess="{$LANG->getModule('agreecheck_empty')}">
                    <label class="form-check-label" for="reg_agreecheck">
                        {$LANG->getModule('accept2')} <a href="#" data-toggle="usageTermsShow" data-title="{$LANG->getModule('usage_terms')}">{$LANG->getModule('usage_terms')}</a> <span class="text-danger">*</span>
                    </label>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            {/if}
        </div>

        <input type="hidden" name="checkss" value="{$CHECKSS}">
        {if not empty($NV_REDIRECT)}<input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">{/if}

        <div class="d-flex flex-wrap justify-content-center gap-2 mt-4">
            <button type="button" class="btn btn-outline-secondary" data-toggle="nv-reset-form"><i class="fa-solid fa-rotate-left"></i> {$LANG->getGlobal('reset')}</button>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> {$LANG->getModule('register')}</button>
        </div>

        {if not empty($LOSTACTIVELINK)}
        <div class="text-center mt-3">
            <a href="{$LOSTACTIVELINK}">{$LANG->getModule('resend_activelink')}</a>
        </div>
        {/if}
    </div>
</form>
