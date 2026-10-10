<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-6">
        <div class="card border-danger">
            <div class="card-body text-center py-4">
                <h4 class="mb-3">
                    <i class="fa-solid fa-shield-halved text-danger"></i>
                    {$LANG->getModule('safe_mode')}
                </h4>
                <p class="mb-4">{$LANG->getModule('safe_active_info')}</p>
                <a href="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}=editinfo/safeshow"
                   class="btn btn-primary">
                    {$LANG->getModule('safe_deactivate')}
                </a>
            </div>
        </div>
    </div>
</div>
