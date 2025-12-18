<form method="post" action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$CONFIG.module}" data-toggle="ajax-form" data-precheck="nv_precheck_form" novalidate{$CAPTCHA_ATTRS}>
    <div class="card bg-body-tertiary">
        <div class="card-body">
            <div class="fs-5 fw-medium mb-2">{$LANG->getModule('requestform_title')}</div>
            <p>{$LANG->getModule('requestform_help')}</p>
            <div class="position-relative mb-2">
                <input class="form-control form-control-required" type="text" name="fname" value="" data-valid placeholder="{$LANG->getModule('fullname')}" aria-label="{$LANG->getModule('fullname')}">
            </div>
            <div class="position-relative">
                <input class="form-control form-control-required mb-3" type="email" name="femail" value="" data-valid placeholder="{$LANG->getModule('email')}" aria-label="{$LANG->getModule('email')}">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">{$LANG->getModule('sendcontact')}</button>
                <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                <input type="hidden" name="request_form" value="{$REQUEST_FORM}">
            </div>
        </div>
    </div>
</form>
