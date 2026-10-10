<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('siteterms')}</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <div class="form-label fw-medium">{$LANG->getModule('content')} <span class="text-danger">(*)</span></div>
                        {$EDITOR}
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary" name="submit">
                <i class="fa-solid fa-check me-2"></i>{$LANG->getGlobal('save')}
            </button>
        </div>
    </div>
</form>
