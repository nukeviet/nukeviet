<div class="alert alert-success">{$LANG->getModule('update_info_complete')}</div>
{if empty($CONFIG.formodule)}
<div id="versioninfo" class="mb-3" data-toggle="updateVersionInfo" data-url="{$UPDATE_URL}?step=3&amp;load=ver" data-mod-url="{$UPDATE_URL}?step=3&amp;load=mod" data-lang-waiting-continue="{$LANG->getModule('update_waiting_continue')|escape}">
    <div class="alert alert-light d-flex align-items-center gap-2 mb-0"><span class="spinner-border spinner-border-sm text-primary"></span> {$LANG->getModule('update_waiting')}</div>
</div>
{else}
<div id="versioninfo" class="mb-3" data-toggle="updateVersionInfo" data-url="{$UPDATE_URL}?step=3&amp;load=module">
    <div class="alert alert-light d-flex align-items-center gap-2 mb-0"><span class="spinner-border spinner-border-sm text-primary"></span> {$LANG->getModule('update_waiting')}</div>
</div>
{/if}
<div id="endupdate">
    <div class="alert alert-warning text-center mb-0">
        <p>{$LANG->getModule('update_info_end')}</p>
        <button type="button" class="btn btn-danger btn-sm" data-toggle="deleteUpdatePackageEnd" data-url="{$DELETE_URL}" data-checkss="{$CHECKSS_DELETE}"><i class="fa-solid fa-trash-can"></i> {$LANG->getModule('update_package_delete')}</button>
    </div>
    <div class="alert alert-success mt-3 mb-0" id="endupdate-success" hidden>{$LANG->getModule('update_package_deleted')}</div>
    <div class="alert alert-danger mt-3 mb-0" id="endupdate-error" hidden>{$LANG->getModule('update_package_not_deleted')}</div>
    <div class="install-nav" id="endupdate-nav" hidden>
        <a class="btn btn-outline-secondary home" href="{$URL_GOHOME}"><i class="fa-solid fa-house"></i> {$LANG->getModule('gohome')}</a>
        <a class="btn btn-primary okay" href="{$URL_GOADMIN}"><i class="fa-solid fa-gauge"></i> {$LANG->getModule('update_goadmin')}</a>
    </div>
</div>
