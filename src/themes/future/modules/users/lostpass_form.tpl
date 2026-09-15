<form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=lostpass" method="post" class="fw-300"
    data-toggle="usersLostPass" data-precheck="nv_precheck_form"
    autocomplete="off" novalidate {$CAPTCHA_ATTRS}
>
    <input type="hidden" name="step" value="step1">
    <input type="hidden" name="checkss" value="{$DATA.checkss}">
    {if not empty($NV_REDIRECT)}
    <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
    {/if}
    <div class="alert alert-info mb-3" data-area="info" data-default="{$LANG->getModule('lostpass_info1')|escape}">{$LANG->getModule('lostpass_info1')}</div>
    <div data-area="form">
        {* Bước 1: Tên đăng nhập hoặc email *}
        <div class="mb-3" data-step="step1">
            <div class="position-relative">
                <input type="text" class="form-control ps-with-fw-icon" name="userField" maxlength="100" value=""
                    placeholder="{$LANG->getModule('username_or_email')}" aria-label="{$LANG->getModule('username_or_email')}"
                    minlength="3" data-valid data-error-type="tooltip"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-user position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
        </div>

        {* Bước 2: Trả lời câu hỏi bảo mật *}
        <div class="mb-3 d-none" data-step="step2">
            <div class="position-relative">
                <input type="text" class="form-control ps-with-fw-icon" name="answer" maxlength="255" value=""
                    placeholder="{$LANG->getModule('answer_question')}" aria-label="{$LANG->getModule('answer_question')}"
                    data-valid data-error-type="tooltip"
                    data-error-mess="{$LANG->getModule('answer_empty')}"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-pen-to-square position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
        </div>

        {* Bước 3: Mã xác minh gửi qua email *}
        <div class="mb-3 d-none" data-step="step3">
            <div class="position-relative">
                <input type="text" class="form-control ps-with-fw-icon" name="verifykey" maxlength="10" value=""
                    placeholder="{$LANG->getModule('lostpass_key')}" aria-label="{$LANG->getModule('lostpass_key')}"
                    data-pattern="/^[a-zA-Z0-9]{literal}{10}{/literal}$/" data-valid data-error-type="tooltip"
                    data-error-mess="{$LANG->getModule('lostpass_active_error')}"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-shield-halved position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
        </div>

        {* Bước 4: Mật khẩu mới *}
        <div class="d-none" data-step="step4">
            <div class="mb-3 position-relative">
                <input type="password" autocomplete="new-password" class="form-control ps-with-fw-icon" name="new_password" maxlength="{$GCONFIG.nv_upassmax}" value=""
                    placeholder="{$LANG->getModule('pass_new')}" aria-label="{$LANG->getModule('pass_new')}"
                    data-pattern="{$PASSWORD_PATTERN}" data-valid data-error-type="tooltip"
                    data-error-mess="{$PASSWORD_RULE}"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
            <div class="mb-3 position-relative">
                <input type="password" autocomplete="new-password" class="form-control ps-with-fw-icon" name="re_password" maxlength="{$GCONFIG.nv_upassmax}" value=""
                    placeholder="{$LANG->getModule('pass_new_re')}" aria-label="{$LANG->getModule('pass_new_re')}"
                    data-valid data-error-type="tooltip" data-valid-callback="userLostpassRepassCheck"
                    data-error-mess="{$LANG->getGlobal('passwordsincorrect')}"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                {$LANG->getModule('lostpass_submit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
            </button>
        </div>
    </div>
</form>
