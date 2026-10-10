<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-4">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
            <div class="row mb-3">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="dump_autobackup" value="1"{if $DATA.dump_autobackup} checked="checked"{/if} role="switch" id="element_dump_autobackup">
                        <label class="form-check-label" for="element_dump_autobackup">{$LANG->getModule('dump_autobackup')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="element_dump_backup_ext">{$LANG->getModule('dump_backup_ext')}</label>
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5">
                    <select class="form-select" name="dump_backup_ext" id="element_dump_backup_ext">
                        {foreach from=$SQL_EXTS item=ext}
                        <option value="{$ext}"{if $ext eq $DATA.dump_backup_ext} selected{/if}>{$ext}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="element_dump_interval">{$LANG->getModule('dump_interval')}</label>
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5">
                    <div class="d-flex align-items-center w-auto">
                        <select class="form-select" name="dump_interval" id="element_dump_interval">
                            {for $value=1 to 10}
                            <option value="{$value}"{if $value eq $DATA.dump_interval} selected{/if}>{$value}</option>
                            {/for}
                        </select>
                        <span class="ms-2">({$LANG->getGlobal('day')})</span>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-12 col-sm-3 col-form-label text-sm-end" for="element_dump_backup_day">{$LANG->getModule('dump_backup_day')}</label>
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5">
                    <div class="d-flex align-items-center w-auto">
                        <select class="form-select" name="dump_backup_day" id="element_dump_backup_day">
                            {for $value=2 to 99}
                            <option value="{$value}"{if $value eq $DATA.dump_backup_day} selected{/if}>{$value}</option>
                            {/for}
                        </select>
                        <span class="ms-2">({$LANG->getGlobal('day')})</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-7 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
