<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{$LANG->getModule('bt_send_row_title')}</h5>
        </div>
        <div class="card-body pt-4">
            {if $MAIL_LANGS|@count > 0}
            <div class="row mb-3">
                <label for="mail_lang" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('mail_language')}</label>
                <div class="col-md-9">
                    <select class="form-select w-auto mw-100" id="mail_lang" name="mail_lang">
                        {foreach from=$MAIL_LANGS item=lang}
                        <option value="{$lang.key}"{if $lang.selected} selected{/if}>{$lang.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="title" class="col-md-3 col-form-label text-md-end">{$LANG->getModule('title_send_title')} <span class="text-danger">(*)</span></label>
                <div class="col-md-9">
                    <input type="text" class="form-control required" id="title" name="title" value="">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-md-3 col-form-label text-md-end">{$LANG->getGlobal('email')} <span class="text-danger">(*)</span></label>
                <div class="col-md-9">
                    <input type="email" class="form-control required" id="email" name="email" value="" autocomplete="off">
                    <div class="invalid-feedback"></div>
                    <div class="form-text">{$LANG->getModule('to_note')}</div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 col-form-label text-md-end">{$LANG->getModule('content')} <span class="text-danger">(*)</span></div>
                <div class="col-md-9">
                    {$MESS_CONTENT}
                </div>
            </div>
            <div class="row">
                <div class="col-md-9 offset-md-3">
                    <input type="hidden" name="checkss" value="{$CHECKSS}">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> {$LANG->getModule('bt_send_row_title')}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
