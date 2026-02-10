<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="save" value="1">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('config')}</div>
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="element_feedback_phone" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('feedback_phone')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-8">
                    <select class="form-select w-auto mw-100" id="element_feedback_phone" name="feedback_phone">
                        {foreach from=$FEEDBACK_PHONE_OPTIONS item=option}
                        <option value="{$option.val}"{$option.sel}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_feedback_address" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('feedback_address')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-8">
                    <select class="form-select w-auto mw-100" id="element_feedback_address" name="feedback_address">
                        {foreach from=$FEEDBACK_ADDRESS_OPTIONS item=option}
                        <option value="{$option.val}"{$option.sel}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_sendcopymode" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('config_sendcopymode')}</label>
                <div class="col-sm-4 col-lg-6 col-xxl-8">
                    <select class="form-select w-auto mw-100" id="element_sendcopymode" name="sendcopymode">
                        {foreach from=$SENDCOPYMODE_OPTIONS item=option}
                        <option value="{$option.key}"{$option.selected}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-8 col-lg-6 col-xxl-8 offset-sm-3 offset-xxl-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="silent_mode" value="1"{if $DATA.silent_mode} checked="checked"{/if} id="element_silent_mode">
                        <label class="form-check-label" for="element_silent_mode">{$LANG->getModule('silent_mode')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('silent_mode_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="bodytext" class="col-sm-3 col-xxl-4 col-form-label text-sm-end">{$LANG->getModule('admin_content')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-8">
                    {$DATA.bodytext}
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary">{$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>
