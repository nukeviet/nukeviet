<div class="d-flex justify-content-center" data-area="usersSafeMode">
    <div class="rounded-4 border shadow-lg p-4">
        <div class="mb-2 d-flex justify-content-center">
            <div class="d-flex fw-40 fh-40 align-items-center rounded-circle justify-content-center bg-danger-subtle text-danger-emphasis">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
        </div>
        {* Thông báo tài khoản đang ở chế độ an toàn *}
        <div class="fw-300{if $DATA.safeshow} d-none{/if}" data-area="safeActiveInfo">
            <h1 class="h2 text-center mb-3">{$LANG->getModule('safe_mode')}</h1>
            <div class="alert alert-warning mb-0">
                {$LANG->getModule('safe_active_info')} <a href="#" data-toggle="usersSafeDeactivateShow">{$LANG->getModule('safe_deactivate')}?</a>
            </div>
        </div>
        {* Form tắt chế độ an toàn *}
        <div class="{if not $DATA.safeshow}d-none{/if}" data-area="safeDeactivate">
            <h1 class="h2 text-center mb-3">{$LANG->getModule('safe_deactivate')}</h1>
            <form action="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=editinfo" method="post" class="fw-300"
                data-toggle="ajax-form" data-precheck="nv_precheck_form"
                autocomplete="off" novalidate
            >
                <input type="hidden" name="checkss" value="{$DATA.checkss}">
                <input type="hidden" name="type" value="safe_deactivate">
                <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                <div class="alert alert-info mb-3" data-area="info" data-default="{$LANG->getModule('safe_deactivate_info')}">{$LANG->getModule('safe_deactivate_info')}</div>
                <div data-area="form">
                    <div class="mb-3 position-relative">
                        <input type="password" autocomplete="off" class="form-control ps-with-fw-icon" name="nv_password" maxlength="{$GCONFIG.nv_upassmax}" value=""
                            placeholder="{$LANG->getGlobal('password')}" aria-label="{$LANG->getGlobal('password')}"
                            data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getGlobal('password_empty')}"
                        >
                        <i class="text-center fa-fw fa-solid fa-key position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                    <div class="mb-2 position-relative">
                        <input type="text" autocomplete="off" class="form-control ps-with-fw-icon" name="safe_key" minlength="32" maxlength="32" value=""
                            placeholder="{$LANG->getModule('safe_key')}" aria-label="{$LANG->getModule('safe_key')}"
                            data-pattern="/^[a-zA-Z0-9]+$/" data-valid data-error-type="tooltip"
                            data-error-mess="{$LANG->getModule('safe_key_invalid')}"
                        >
                        <i class="text-center fa-fw fa-solid fa-shield position-absolute top-50 start-0 ms-2 translate-middle-y"></i>
                    </div>
                    <div class="mb-3 text-end">
                        <button type="button" class="btn btn-sm btn-warning" data-toggle="usersSafeKeySend">
                            <i class="fa-solid fa-paper-plane" data-icon="fa-paper-plane"></i> {$LANG->getModule('safe_resendkey')}
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            {$LANG->getModule('editinfo_confirm')} <i class="fa-solid fa-arrow-right-long align-baseline-xs ms-1"></i>
                        </button>
                        <button type="button" class="btn btn-secondary" data-toggle="nv-reset-form">{$LANG->getGlobal('reset')}</button>
                    </div>
                </div>
            </form>
        </div>
        {if not empty($NAVS)}
        <div class="fw-300 mx-auto mt-4 text-center">
            <ul class="list-inline mb-0">
                {foreach from=$NAVS item=nav}
                <li class="list-inline-item text-nowrap">
                    <a href="{$nav.href}">
                        <i class="fa-solid fa-caret-right"></i>&nbsp;{$nav.title}
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
        {/if}
    </div>
</div>
