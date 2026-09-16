<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$LANG->getModule('remove_2step_method_title')}</h1>
        <form action="{$FORM_ACTION}" method="post" class="fw-300"
            data-toggle="usersR2s" data-precheck="nv_precheck_form"
            autocomplete="off" novalidate
        >
            <input type="hidden" name="checkss" value="{$DATA.checkss}">
            <input type="hidden" name="email_sent" value="0">
            <div class="alert alert-info mb-3" data-area="info">{$LANG->getModule('remove_2step_info')}</div>
            <div data-area="form">
                {* Bước 1: Email tài khoản và câu hỏi bảo mật nếu có *}
                <div data-step="step1">
                    <div class="mb-3 position-relative">
                        <input type="email" class="form-control ps-with-fw-icon" name="useremail" maxlength="100" value=""
                            placeholder="{$LANG->getModule('remove_2step_email')}" aria-label="{$LANG->getModule('remove_2step_email')}"
                            data-valid data-error-type="tooltip"
                        >
                        <i class="z-10 text-center fa-fw fa-solid fa-envelope position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                    {if not empty($DATA.question)}
                    <div class="mb-3">
                        {$LANG->getModule('lostpass_question')}: <strong>{$DATA.question}</strong>
                    </div>
                    <div class="mb-3 position-relative">
                        <input type="text" class="form-control ps-with-fw-icon" name="useranswer" maxlength="100" value=""
                            placeholder="{$LANG->getModule('answer')}" aria-label="{$LANG->getModule('answer')}"
                            data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getModule('answer_empty')}"
                        >
                        <i class="z-10 text-center fa-fw fa-solid fa-shield position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                    {/if}
                </div>

                {* Bước 2: Mã xác minh gửi qua email *}
                <div class="d-none" data-step="step2">
                    <div class="mb-3 position-relative">
                        <input type="text" class="form-control ps-with-fw-icon" name="verifykey" maxlength="100" value=""
                            placeholder="{$LANG->getModule('verifykey')}" aria-label="{$LANG->getModule('verifykey')}"
                            data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getModule('verifykey_empty')}"
                        >
                        <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        {$LANG->getGlobal('submit')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
