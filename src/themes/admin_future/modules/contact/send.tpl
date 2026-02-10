<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('bt_send_row_title')}</div>
        <div class="card-body pt-4">
            {if !empty($MAIL_LANGS)}
            <div class="row mb-3">
                <label for="element_mail_lang" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('mail_language')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <select name="mail_lang" id="element_mail_lang" class="form-select w-auto mw-100">
                        {foreach $MAIL_LANGS as $lang}
                        <option value="{$lang.key}"{if $lang.selected} selected{/if}>{$lang.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {/if}
            <div class="row mb-3">
                <label for="element_title" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('title_send_title')} <span class="text-danger">*</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" name="title" id="element_title" class="form-control" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <label for="element_email" class="col-sm-3 col-form-label text-sm-end">{$LANG->getGlobal('email')} <span class="text-danger">*</span></label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" name="email" id="element_email" class="form-control" required>
                    <div class="form-text">{$LANG->getModule('to_note')}</div>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('content')} <span class="text-danger">*</span></div>
                <div class="col-sm-8 col-lg-10 col-xxl-9">
                    {$MESS_CONTENT}
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-paper-plane"></i> {$LANG->getModule('bt_send_row_title')}</button>
        </div>
    </div>
</form>
