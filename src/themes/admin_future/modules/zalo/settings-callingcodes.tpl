{assign var="formAction" value="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}"}
<form method="post" action="{$formAction}" class="ajax-submit" novalidate>
    <div class="card">
        <div class="card-header fs-5 fw-medium">{$LANG->getModule('callingcodes_settings')}</div>
        <div class="card-body">
            <div class="table-responsive-lg table-card pb-1">
                <table class="table table-striped align-middle table-sticky mb-0">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width:10%">{$LANG->getModule('country_code')}</th>
                            <th class="text-nowrap" style="width:50%">{$LANG->getModule('country_name')}</th>
                            <th class="text-nowrap" style="width:40%">{$LANG->getModule('country_callcode')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$COUNTRIES item=country}
                        <tr>
                            <td><code>{$country.code}</code></td>
                            <td>{$country.name}</td>
                            <td>
                                <div class="d-grid gap-2 zalo-multi-inputs" data-max="5">
                                    {foreach from=$country.callcodes item=callcode}
                                    <div class="input-group zalo-multi-input">
                                        <span class="input-group-text">+</span>
                                        <input type="text" class="form-control number" name="callcode[{$country.code}][]" value="{$callcode}" maxlength="6" inputmode="numeric" autocomplete="off" aria-label="{$LANG->getModule('country_callcode')}">
                                        <button type="button" class="btn btn-secondary" data-toggle="zaloInputAdd" aria-label="{$LANG->getGlobal('add')}" data-bs-toggle="tooltip" title="{$LANG->getGlobal('add')}"><i class="fa-solid fa-plus fa-fw text-primary"></i></button>
                                        <button type="button" class="btn btn-secondary" data-toggle="zaloInputRemove" aria-label="{$LANG->getGlobal('delete')}" data-bs-toggle="tooltip" title="{$LANG->getGlobal('delete')}"><i class="fa-solid fa-xmark fa-fw text-danger"></i></button>
                                    </div>
                                    {/foreach}
                                    <div class="invalid-feedback"></div>
                                </div>
                            </td>
                        </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer border-top text-center sticky-bottom bg-body">
            <input type="hidden" name="callingcodesSave" value="1">
            <input type="hidden" name="checkss" value="{$CHECKSS}">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> {$LANG->getGlobal('save')}</button>
        </div>
    </div>
</form>
