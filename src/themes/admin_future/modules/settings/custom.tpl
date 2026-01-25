<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <ul class="list-group list-group-flush pt-1 list">
            {foreach from=$CUSTOM_CONFIGS key=key item=vals}
            <li class="list-group-item item">
                <div class="row g-2">
                    <div class="col-lg-6 col-xl-4">
                        <label class="form-label fw-medium">{$LANG->getModule('config_key')}:</label>
                        <input type="text" class="form-control required anphanumeric" name="config_key[]" value="{$key}" maxlength="30">
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <label class="form-label fw-medium">{$LANG->getModule('config_value')}:</label>
                        <input type="text" class="form-control required" name="config_value[]" value="{is_array($vals) ? $vals[0] : ''}">
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <label class="form-label fw-medium">{$LANG->getModule('config_description')}:</label>
                        <div class="hstack gap-1">
                            <input type="text" class="form-control" name="config_description[]" value="{is_array($vals) ? $vals[1] : ''}">
                            <button type="button" class="btn btn-secondary" data-toggle="addCustomCfgItem" title="{$LANG->getGlobal('add')}" aria-label="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus text-primary"></i></button>
                            <button type="button" class="btn btn-secondary" data-toggle="delCustomCfgItem" title="{$LANG->getGlobal('delete')}" aria-label="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </li>
            {/foreach}
        </ul>
        <div class="card-footer border-top text-center">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary">{$LANG->getModule('submit')}</button>
        </div>
    </div>
</form>
<div class="card mt-3">
    <div class="card-body">
        <div>{$LANG->getModule('custom_configs_note')}</div>
        <strong>{$LANG->getModule('config_key')}</strong>: {$LANG->getModule('config_key_note')}
    </div>
</div>
