<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="text-center mb-4">
            <div class="mb-3 d-flex justify-content-center">
                <div class="d-flex fw-60 fh-60 align-items-center rounded-circle justify-content-center bg-success-subtle text-success-emphasis fs-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <h1 class="h3 mb-2">{$LANG->getModule('request_accepted')}</h1>
            <p class="text-muted mb-0">{$LANG->getModule('delaccount_pending_info')}.</p>
        </div>
        <div class="rounded-4 border shadow-lg p-4">
            <p>{$LANG->getModule('delaccount_pending_info1')} <strong class="text-danger">{$DATA.estimated_time_show}</strong>.</p>
            <p>{$LANG->getModule('delaccount_pending_info2')}.</p>
            <div class="alert alert-info mb-0">
                <strong>{$LANG->getModule('delaccount_recover_info1')}</strong><br>
                {$LANG->getModule('delaccount_recover_info2')}.
            </div>
            <hr class="my-4">
            <div class="text-center">
                <a href="{$DATA.link_home}" class="btn btn-primary"><i class="fa-solid fa-house"></i> {$LANG->getModule('gohome')}</a>
            </div>
        </div>
    </div>
</div>
