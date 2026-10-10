<form method="post" id="inform-configs-form" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-body pt-4">
            <div class="row mb-3">
                <div class="col-sm-6 offset-sm-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="inform_active" value="1" role="switch" id="inform_active"{if $DATA.inform_active} checked{/if}>
                        <label class="form-check-label" for="inform_active">{$LANG->getModule('inform_active')}</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inform_default_exp" class="col-sm-6 col-form-label text-sm-end">{$LANG->getModule('inform_default_exp')}</label>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <input type="text" class="form-control required w-sm-auto mw-100" id="inform_default_exp" name="inform_default_exp" value="{$DATA.inform_default_exp}" maxlength="3" inputmode="numeric" autocomplete="off" data-toggle="digit-only">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inform_exp_del" class="col-sm-6 col-form-label text-sm-end">{$LANG->getModule('inform_exp_del')}</label>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <input type="text" class="form-control required w-sm-auto mw-100" id="inform_exp_del" name="inform_exp_del" value="{$DATA.inform_exp_del}" maxlength="3" inputmode="numeric" autocomplete="off" data-toggle="digit-only">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inform_refresh_time" class="col-sm-6 col-form-label text-sm-end">{$LANG->getModule('inform_refresh_time')}</label>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <input type="text" class="form-control required w-sm-auto mw-100" id="inform_refresh_time" name="inform_refresh_time" value="{$DATA.inform_refresh_time}" maxlength="3" inputmode="numeric" autocomplete="off" data-toggle="digit-only">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inform_max_characters" class="col-sm-6 col-form-label text-sm-end">{$LANG->getModule('inform_max_characters')}</label>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <input type="text" class="form-control required w-sm-auto mw-100" id="inform_max_characters" name="inform_max_characters" value="{$DATA.inform_max_characters}" maxlength="4" inputmode="numeric" autocomplete="off" data-toggle="digit-only">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="inform_numrows" class="col-sm-6 col-form-label text-sm-end">{$LANG->getModule('inform_numrows')}</label>
                <div class="col-sm-6 col-lg-4 col-xl-2">
                    <input type="text" class="form-control required w-sm-auto mw-100" id="inform_numrows" name="inform_numrows" value="{$DATA.inform_numrows}" maxlength="3" inputmode="numeric" autocomplete="off" data-toggle="digit-only">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 offset-sm-6">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
