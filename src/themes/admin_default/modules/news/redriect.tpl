<div class="card card-body text-center" id="redriect-page" data-autosave-key="{$AUTOSAVEKEY}" data-go-back="{$GO_BACK}" data-go-back-time="{$REDRIECT_T2}">
    <div class="fw-medium">{$MSG1}</div>
    <div class="my-2">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">{$LANG->getGlobal('wait_page_load')}</span>
        </div>
    </div>
    <a href="{$NV_REDIRECT}">{$MSG2}</a>
</div>
{if not $GO_BACK}
<meta http-equiv="refresh" content="{$REDRIECT_T1};url={$NV_REDIRECT}">
{/if}
