<div class="d-flex justify-content-center mt-3 mt-lg-4">
    <div class="rounded-4 border shadow-lg p-4" data-toggle="form">
        <div class="text-center mb-3">
            <a title="{$GCONFIG.site_name}" href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}">
                <img class="img-fluid" src="{$smarty.const.NV_BASE_SITEURL}{$GCONFIG.site_logo}" alt="{$GCONFIG.site_name}">
            </a>
        </div>
        <h1 class="h2 text-center mb-3">{$LANG->getModule('change_pass')}</h1>
        <div class="alert alert-info mb-3 fw-300 d-none" role="alert" data-toggle="message"></div>
        <div data-toggle="ct">
            <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=editinfo/password" method="post" class="fw-300"
                data-toggle="ajax-form" data-precheck="nv_precheck_form"
                autocomplete="off" novalidate
            >
                <input type="hidden" name="checkss" value="{$CHECKSS}">
                <div class="alert alert-info mb-3">{$CHANGEPASS_INFO}</div>
                {if not $PASS_EMPTY}
                <div class="mb-3 position-relative">
                    <input type="password" autocomplete="current-password" class="form-control ps-with-fw-icon" name="nv_password" maxlength="100" value=""
                        placeholder="{$LANG->getModule('pass_old')}" aria-label="{$LANG->getModule('pass_old')}"
                        data-valid data-error-type="tooltip"
                    >
                    <i class="text-center fa-fw fa-solid fa-lock position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                </div>
                {/if}
                <div class="mb-3 position-relative">
                    <input type="password" autocomplete="new-password" class="form-control ps-with-fw-icon" name="new_password" maxlength="{$GCONFIG.nv_upassmax}" value=""
                        placeholder="{$LANG->getModule('pass_new')}" aria-label="{$LANG->getModule('pass_new')}"
                        data-pattern="{$PASSWORD_PATTERN}" data-valid data-error-type="tooltip"
                        data-error-mess="{$PASSWORD_RULE}"
                    >
                    <i class="text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                </div>
                <div class="mb-3 position-relative">
                    <input type="password" autocomplete="new-password" class="form-control ps-with-fw-icon" name="re_password" maxlength="{$GCONFIG.nv_upassmax}" value=""
                        placeholder="{$LANG->getModule('pass_new_re')}" aria-label="{$LANG->getModule('pass_new_re')}"
                        data-valid data-error-type="tooltip" data-valid-callback="userLostpassRepassCheck"
                        data-error-mess="{$LANG->getGlobal('passwordsincorrect')}"
                    >
                    <i class="text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        {$LANG->getModule('editinfo_confirm')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                    </button>
                    <button type="button" class="btn btn-secondary" data-toggle="nv-reset-form">{$LANG->getGlobal('reset')}</button>
                </div>
            </form>
        </div>
        <hr>
        <div class="text-center">
            <button type="button" class="btn btn-secondary btn-sm" data-toggle="{$LOGOUT_TOGGLE}" data-module="{$MODULE_NAME}" data-url="{$LOGOUT_URL}">
                <i class="fa-solid fa-arrow-right-from-bracket" data-icon="fa-arrow-right-from-bracket"></i> {$LANG->getModule('logout_title')}
            </button>
        </div>
    </div>
</div>
