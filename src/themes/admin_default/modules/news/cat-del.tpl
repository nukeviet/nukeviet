{assign var="form_action" value="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"}
<form action="{$form_action}" method="post" class="ajax-submit" novalidate>
    <input type="hidden" name="catid" value="{$CATID}">
    <input type="hidden" name="submitconfirm" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="text-center">
        <p class="fw-semibold mb-3">{$TITLE nofilter}</p>
        <button class="btn btn-danger mb-4" name="delcatandrows" value="1" type="submit">
            <i class="fa-solid fa-trash"></i> {$LANG->getModule('delcatandrows')}
        </button>
    </div>
</form>
<form action="{$form_action}" method="post" class="ajax-submit" novalidate>
    <input type="hidden" name="catid" value="{$CATID}">
    <input type="hidden" name="submitconfirm" value="1">
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <div class="text-center">
        <div class="fw-semibold mb-2">{$LANG->getModule('delcat_msg_rows_move')}:</div>
        <div class="d-flex justify-content-center gap-2">
            <div>
                <select class="form-select" name="catidnews" id="catidnews" style="max-width:320px">
                    {foreach from=$CAT_LIST key=catid_k item=title_v}
                    <option value="{$catid_k}">{$title_v nofilter}</option>
                    {/foreach}
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="flex-shrink-0">
                <button class="btn btn-primary" name="movecat" value="1" type="submit">
                    <i class="fa-solid fa-right-left"></i> {$LANG->getModule('action')}
                </button>
            </div>
        </div>
    </div>
</form>
