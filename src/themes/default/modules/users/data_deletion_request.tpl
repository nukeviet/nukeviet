<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <form action="{$DATA.form_action}" method="post"
            data-precheck="nv_precheck_form" data-area="usersDataDeletion" data-checkss="{$CHECKSS}"
            autocomplete="off" novalidate
        >
            {if not $DATA.i_confirmed}
            {* Bước 1: Đọc kỹ cảnh báo và xác nhận xóa tài khoản *}
            <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between align-items-start gap-2 mb-4">
                <div>
                    <h1 class="h3 mb-2">{$LANG->getModule('delaccount_title')}</h1>
                    <p class="text-muted mb-0">{$LANG->getModule('delaccount_note')}.</p>
                </div>
                <a href="{$DATA.link_back}" class="btn btn-secondary text-nowrap"><i class="fa-solid fa-arrow-left-long"></i> {$LANG->getModule('delaccount_back')}</a>
            </div>
            <div class="rounded-4 border shadow-lg p-4">
                <div class="alert alert-danger">
                    <strong>{$LANG->getModule('delaccount_warn1')}</strong><br>
                    {$LANG->getModule('delaccount_warn2')}
                </div>
                <h2 class="h5 mb-3">{$LANG->getModule('delaccount_explain1')}</h2>
                <p>{$LANG->getModule('delaccount_explain2')}.</p>
                <p class="mb-1"><strong>{$LANG->getModule('delaccount_explain3')}:</strong></p>
                <p>{$LANG->getModule('delaccount_explain4')}.</p>
                <p class="mb-1"><strong>{$LANG->getModule('delaccount_explain5')}:</strong></p>
                <p class="mb-1">{$LANG->getModule('delaccount_explain6')}:</p>
                <ul class="mb-0">
                    <li>{$LANG->getModule('delaccount_explain7')}</li>
                    <li>{$LANG->getModule('delaccount_explain8')}</li>
                    <li>{$HOLD_MESSAGE}</li>
                </ul>
                <hr class="my-4">
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="usersDelIConfirmed" name="i_confirmed" value="1" data-toggle="usersDelConfirm">
                    <label class="form-check-label" for="usersDelIConfirmed">
                        <strong>{$LANG->getModule('delaccount_confirm1')}</strong>
                        <span class="d-block small text-muted">{$LANG->getModule('delaccount_confirm2')}</span>
                    </label>
                </div>
                <div class="text-center">
                    <input type="hidden" name="submit_confirmed" value="1">
                    <button type="submit" class="btn btn-danger" data-area="usersDelSubmit" disabled>
                        <i class="fa-solid fa-trash"></i> {$LANG->getModule('delaccount_confirm3')}
                    </button>
                </div>
            </div>
            {else}
            {* Bước 2: Nhập mã xác minh gửi qua email *}
            <input type="hidden" name="i_confirmed" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <div class="d-flex flex-wrap flex-sm-nowrap justify-content-between align-items-start gap-2 mb-4">
                <div>
                    <h1 class="h3 mb-2">{$LANG->getModule('delaccount_veremail_title')}</h1>
                    <p class="text-muted mb-0">{$LANG->getModule('delaccount_veremail_note')}.</p>
                </div>
                <a href="{$DATA.link_back}" class="btn btn-secondary text-nowrap"><i class="fa-solid fa-arrow-left-long"></i> {$LANG->getModule('delaccount_back')}</a>
            </div>
            <div class="rounded-4 border shadow-lg p-4">
                {if not empty($DATA.error)}
                <div class="alert alert-danger" role="alert">{$DATA.error}</div>
                {/if}
                <div class="text-center">
                    <div class="mb-3 d-flex justify-content-center">
                        <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis fs-3">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                    </div>
                    <h2 class="h4 mb-2">{$LANG->getModule('delaccount_veremail_checkmail')}</h2>
                    <p>{$DATA.message_checkmail}.</p>
                </div>
                <div class="mb-3 text-center">
                    <input type="text" class="form-control text-center maxw-250 mx-auto" name="verification_code" value=""
                        minlength="10" maxlength="10" placeholder="_ _ _ _ _ _ _ _ _ _" autocomplete="off"
                        aria-label="{$LANG->getModule('delaccount_veremail_checkmail')}"
                        data-valid data-error-type="feedback"
                    >
                    <div class="invalid-feedback"></div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> {$LANG->getModule('delaccount_veremail_title')}
                    </button>
                </div>
                <hr class="my-4">
                <div class="text-center small">
                    {$LANG->getModule('not_received_code')}
                    <span class="text-primary fw-bold{if $DATA.time_code_remain <= 0} d-none{/if}" data-area="usersDelTimer">{$LANG->getModule('try_received_code')} <span data-area="usersDelTimeRemain">{$DATA.time_code_remain}</span>s</span>
                    <button type="button" class="btn btn-link btn-sm p-0 align-baseline fw-bold{if $DATA.time_code_remain > 0} d-none{/if}" data-toggle="usersDelResendCode">{$LANG->getModule('send_received_code')}</button>
                    <span class="text-primary d-none" data-area="usersDelResendLoader"><i class="fa-solid fa-spinner fa-spin-pulse"></i></span>
                </div>
            </div>
            {/if}
        </form>
    </div>
</div>
