<div class="d-flex justify-content-center my-3">
    <div class="rounded-4 border shadow-lg p-4 w-100 maxw-500">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis">
                <i class="fa-solid fa-user-lock"></i>
            </div>
        </div>
        <h1 class="h2 text-center mb-3">{$PAGE_TITLE}</h1>
        <div class="alert alert-info mb-3">{$INFO}</div>
        <form action="{$FORM_ACTION}" method="post" data-precheck="nv_precheck_form" autocomplete="off" novalidate {$CAPTCHA_ATTRS}>
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <input type="hidden" name="openid_account_confirm" value="1">
            {if not empty($NV_REDIRECT)}
            <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
            {/if}
            <div class="mb-3">
                <label class="form-label" for="openid_confirm_password">{$LANG->getGlobal('password')} <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="openid_confirm_password" name="password" value="" autocomplete="current-password" maxlength="100"
                    data-valid data-error-type="feedback"
                    data-error-mess="{$LANG->getGlobal('password_empty')}"
                >
                <div class="invalid-feedback"></div>
            </div>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-secondary" data-toggle="nv-reset-form">{$LANG->getGlobal('reset')}</button>
                <button type="submit" class="btn btn-primary">{$LANG->getGlobal('loginsubmit')}</button>
            </div>
        </form>
    </div>
</div>
