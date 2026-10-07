{if $FINISH eq 1}
<div class="text-center py-3">
    <div class="display-5 text-success mb-3"><i class="fa-solid fa-circle-check"></i></div>
    <h3 class="h4 text-dark install_succesfull">{$LANG->getModule('success')}</h3>
    <p class="mb-0">{$LANG->getModule('congratulations')}</p>
</div>
<div class="alert alert-info mt-3">
    <p>{$LANG->getModule('noteuploads')}</p>
    <p class="mb-0">{$LANG->getModule('notesupport')}</p>
</div>
<div class="install-nav">
    <a class="btn btn-outline-secondary home" href="{$smarty.const.NV_BASE_SITEURL}index.php"><i class="fa-solid fa-house"></i> {$LANG->getModule('gohome')}</a>
    <a class="btn btn-primary okay" href="{$smarty.const.NV_BASE_SITEURL}{$smarty.const.NV_ADMINDIR}/index.php"><i class="fa-solid fa-gauge"></i> {$LANG->getModule('goadmin')}</a>
</div>
{else}
<div class="alert alert-warning">{$LANG->getModule('movefileconfig')}</div>
<div class="install-nav">
    <a class="btn btn-primary okay ms-auto" href="{$STEP_URL}7"><i class="fa-solid fa-rotate"></i> {$LANG->getModule('checkfileconfig')}</a>
</div>
{/if}
