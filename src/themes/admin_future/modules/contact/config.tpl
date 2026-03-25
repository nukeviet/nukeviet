<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-body pt-4">
            <div class="row mb-3">
                <label for="feedback_phone" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('feedback_phone')}</label>
                <div class="col-md-9">
                    <select class="form-select w-auto mw-100" id="feedback_phone" name="feedback_phone">
                        {foreach from=$FEEDBACK_PHONE_OPTIONS item=option}
                        <option value="{$option.val}"{if $option.val == $DATA.feedback_phone} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="feedback_address" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('feedback_address')}</label>
                <div class="col-md-9">
                    <select class="form-select w-auto mw-100" id="feedback_address" name="feedback_address">
                        {foreach from=$FEEDBACK_ADDRESS_OPTIONS item=option}
                        <option value="{$option.val}"{if $option.val == $DATA.feedback_address} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <label for="sendcopymode" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('config_sendcopymode')}</label>
                <div class="col-md-9">
                    <select class="form-select w-auto mw-100" id="sendcopymode" name="sendcopymode">
                        {foreach from=$SENDCOPYMODE_OPTIONS item=option}
                        <option value="{$option.key}"{if $option.key == $DATA.sendcopymode} selected{/if}>{$option.title}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-9 offset-md-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="silent_mode" value="1"{if $DATA.silent_mode} checked{/if} role="switch" id="silent_mode">
                        <label class="form-check-label" for="silent_mode">{$LANG->getModule('silent_mode')}</label>
                    </div>
                    <div class="form-text">{$LANG->getModule('silent_mode_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 col-form-label text-md-end">{$LANG->getModule('admin_content')}</div>
                <div class="col-md-9">
                    {$DATA.bodytext}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 offset-md-3">
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
