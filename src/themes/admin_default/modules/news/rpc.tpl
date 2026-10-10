<meta http-equiv="refresh" content="3;url={$REDIRECT_URL}">
<div class="d-flex justify-content-center py-5">
    <div class="card text-center" style="min-width:320px">
        <div class="card-body py-4">
            <div class="mb-3">
                <span class="spinner-border text-primary" role="status"></span>
            </div>
            <p class="fw-semibold mb-3">{$LANG->getModule('content_saveok')}</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{$REDIRECT_URL}" class="btn btn-sm btn-primary">
                    {$LANG->getModule('rpc_ping_page')}
                </a>
                <a href="{$MODULE_URL}" class="btn btn-sm btn-secondary">
                    {$LANG->getModule('content_main')} {$CUSTOM_TITLE}
                </a>
            </div>
        </div>
    </div>
</div>
