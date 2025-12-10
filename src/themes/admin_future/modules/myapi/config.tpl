<form method="post" class="g-3 ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-header fw-medium fs-5 py-2">
            {$LANG->getModule('general_settings')}
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input type="checkbox" id="remote_api_access" name="remote_api_access" value="1" {if $DATA.remote_api_access == 1}checked="checked"{/if} class="form-check-input">
                        <label for="remote_api_access" class="form-check-label">{$LANG->getModule('remote_api_access')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('remote_api_access_help')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="api_check_time" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('api_check_time')} <span class="text-danger">(*)</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <div class="input-group">
                        <input type="number" name="api_check_time" id="api_check_time" value="{$DATA.api_check_time}" min="1" max="1440" class="form-control">
                        <span class="input-group-text">{$LANG->getGlobal('sec')}</span>
                    </div>
                    <div class="form-text">{$LANG->getModule('api_check_time_help')}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('submit')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
