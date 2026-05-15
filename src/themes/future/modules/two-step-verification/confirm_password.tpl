{if $IS_PASS_VALID}
<div class="d-flex justify-content-center mt-4">
    <div class="card shadow-sm w-100 maxw-360">
        <div class="card-body p-4">
            <h4 class="text-center mb-3">{$LANG->getModule('confirm_password')}</h4>
            <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"
                  method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form"
                  data-callback="confirmPassCallback" autocomplete="off" novalidate>
                <p class="text-muted mb-3">{$LANG->getModule('confirm_password_info')}</p>
                <div class="mb-3 position-relative">
                    <label class="form-label" for="password">{$LANG->getGlobal('password')} <span class="text-danger">(*)</span></label>
                    <div class="position-relative">
                        <input type="password" class="form-control ps-with-fw-icon required" id="password"
                               name="password" maxlength="100" minlength="1" autocomplete="off"
                               data-valid data-error-type="tooltip"
                               data-error-mess="">
                        <i class="z-10 text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                </div>
                <div class="text-center">
                    <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check"></i> {$LANG->getModule('confirm')}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{else}
<div class="alert alert-danger">{$LANG->getModule('change_2step_notvalid', $USERS_PASS_URL)}</div>
{/if}
