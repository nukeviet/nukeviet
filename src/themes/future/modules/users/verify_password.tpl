<div class="d-flex justify-content-center">
    <div class="rounded-4 border shadow-lg p-4">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$LANG->getModule('verify_password_title')}</h1>
        <form action="{$DATA.form_action}" method="post" class="fw-300"
            data-toggle="ajax-form" data-precheck="nv_precheck_form"
            autocomplete="off" novalidate {$CAPTCHA_ATTRS}
        >
            <input type="hidden" name="_csrf" value="{$CHECKSS}">
            <input type="hidden" name="nv_redirect" value="{$DATA.redirect}">
            <input type="hidden" name="area" value="{$DATA.area}">
            <div class="alert alert-info mb-3">{$LANG->getModule('verify_password_note')}.</div>
            <div class="mb-3 position-relative">
                <input type="password" autocomplete="current-password" class="form-control ps-with-fw-icon" name="password" maxlength="100" value=""
                    placeholder="{$LANG->getGlobal('password')}" aria-label="{$LANG->getGlobal('password')}"
                    minlength="3" data-valid data-error-type="tooltip"
                >
                <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                    {$LANG->getGlobal('verify')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>
