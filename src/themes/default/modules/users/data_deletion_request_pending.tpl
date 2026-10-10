<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        {if $DATA.is_cancel}
        {* Đã hủy yêu cầu xóa tài khoản *}
        <div class="text-center mb-4">
            <div class="mb-3 d-flex justify-content-center">
                <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-success-subtle text-success-emphasis fs-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <h1 class="h3 mb-2">{$LANG->getModule('delacc_cancel_success')}</h1>
            <p class="text-muted mb-0">{$LANG->getModule('delacc_cancel_success_info')}.</p>
        </div>
        <div class="rounded-4 border shadow-lg p-4">
            <p>{$LANG->getModule('delacc_cancel_success_info1')}.</p>
            <p>{$LANG->getModule('delacc_cancel_success_info2')}.</p>
            <div class="alert alert-warning mb-0">
                <strong>{$LANG->getModule('delacc_cancel_success_after1')}</strong><br>
                {$DATA.protect_account_message}
            </div>
            <hr class="my-4">
            <div class="text-center">
                <a href="{$DATA.link_back}" class="btn btn-primary">{$LANG->getModule('delacc_cancel_success_back')}</a>
            </div>
        </div>
        {else}
        {* Tài khoản đang chờ xóa *}
        <form action="{$DATA.form_action}" method="post" novalidate>
            <div class="text-center mb-4">
                <div class="mb-3 d-flex justify-content-center">
                    <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-warning-subtle text-warning-emphasis fs-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <h1 class="h3 mb-2">{$LANG->getModule('delacc_pending_title')}</h1>
                <p class="text-muted mb-0">{$LANG->getModule('delacc_pending_info')}.</p>
            </div>
            {if not empty($DATA.error)}
            <div class="alert alert-danger" role="alert">{$DATA.error}</div>
            {/if}
            <div class="rounded-4 border shadow-lg p-4">
                <p>{$LANG->getModule('delacc_pending_info1')} <strong class="text-danger">{$DATA.estimated_time_show}</strong>.</p>
                <p class="mb-0"><strong>{$LANG->getModule('delacc_pending_info2')}</strong>.</p>
                <hr class="my-4">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-rotate-left"></i> {$LANG->getModule('delacc_cancel')}
                    </button>
                    <a href="{$DATA.link_logout}" class="btn btn-secondary">
                        <i class="fa-solid fa-right-from-bracket"></i> {$LANG->getModule('delacc_continue')}
                    </a>
                </div>
            </div>
        </form>
        {/if}
    </div>
</div>
