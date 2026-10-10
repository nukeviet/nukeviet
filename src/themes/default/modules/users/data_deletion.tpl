<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        {if empty($DATA.delete_at) or $DATA.delete_at <= $smarty.const.NV_CURRENTTIME}
        {* Đã gỡ liên kết tài khoản *}
        <h1 class="h3 mb-2">{$LANG->getModule('datadeletion_title')}</h1>
        <p class="text-muted mb-4">{$LANG->getModule('datadeletion_text')} {$DATA.request_source}.</p>
        <div class="rounded-4 border shadow-lg p-4 text-center">
            <div class="mb-3 d-flex justify-content-center">
                <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-primary-subtle text-primary-emphasis fs-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <h2 class="h4">{$LANG->getModule('datadeletion_success')}</h2>
            <p>{$LANG->getModule('datadeletion_success1')}.</p>
            <div class="border rounded-3 p-3 bg-body-tertiary">
                {$LANG->getModule('datadeletion_id')}:
                <div class="h2 text-primary mb-0 text-break">{$DATA.confirmation_code}</div>
            </div>
            <hr class="my-4">
            <div class="text-muted small">{$LANG->getModule('datadeletion_data_info1')} {$DATA.request_source} {$LANG->getModule('datadeletion_data_info2')}</div>
        </div>
        {else}
        {* Tài khoản đang chờ xóa *}
        <h1 class="h3 mb-2">{$LANG->getModule('datadeletion_pedding_title')}</h1>
        <p class="text-muted mb-4">{$LANG->getModule('datadeletion_pedding_sub')} {$DATA.request_source}.</p>
        <div class="rounded-4 border shadow-lg p-4 text-center">
            <div class="mb-3 d-flex justify-content-center">
                <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-warning-subtle text-warning-emphasis fs-3">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>
            <h2 class="h4">{$LANG->getModule('request_accepted')}</h2>
            <p>{$LANG->getModule('datadeletion_pedding_body')}.</p>
            <div class="border rounded-3 p-3 bg-body-tertiary">
                {$LANG->getModule('datadeletion_id')}:
                <div class="h2 text-primary mb-3 text-break">{$DATA.confirmation_code}</div>
                <div class="small">{$LANG->getModule('datadeletion_pedding_time')}:</div>
                <strong class="text-danger">{$DATA.delete_at|ddatetime}</strong>
            </div>
            <hr class="my-4">
            <div class="text-muted small">{$LANG->getModule('datadeletion_data_info1')} {$DATA.request_source} {$LANG->getModule('datadeletion_pedding_info')}.</div>
        </div>
        {/if}
    </div>
</div>
