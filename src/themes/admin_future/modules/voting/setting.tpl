<!-- BEGIN: main -->
<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <label class="col-sm-5 col-lg-3 col-form-label text-sm-end">
                    {$LANG->getModule('difftimeout')}
                </label>
                <div class="col-sm-3 col-lg-3 col-xxl-2">
                    <div class="input-group">
                        <input type="text" class="form-control" name="difftimeout" value="{$DATA.difftimeout}">&nbsp;<span class="d-flex align-items-center">{$LANG->getModule('hours')}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-lg-3 offset-sm-5">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('config_save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->
