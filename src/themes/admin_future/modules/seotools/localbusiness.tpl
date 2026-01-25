<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}&amp;localbusiness_information=1" novalidate>
    <div class="card">
        <div class="card-body">
            <textarea class="form-control" name="jsondata" rows="20">{$DATA}</textarea>
            <div class="text-center mt-3">
                <div class="hstack gap-1 d-inline-flex">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" data-icon="fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
                    <button type="button" class="btn btn-secondary" data-toggle="sample_data" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"><i class="fa-solid fa-database" data-icon="fa-database"></i> {$LANG->getModule('localbusiness_reset')}</button>
                    <button type="button" class="btn btn-secondary" data-toggle="lbinf_delete" data-confirm="{$LANG->getModule('localbusiness_del_confirm')}" data-url="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"><i class="fa-solid fa-trash text-danger" data-icon="fa-trash"></i> {$LANG->getGlobal('delete')}</button>
                </div>
            </div>
        </div>
    </div>
</form>
