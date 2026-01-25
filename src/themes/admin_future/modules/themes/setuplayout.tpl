<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex gap-2 flex-wrap flex-lg-nowrap">
                <div class="flex-grow-1 flex-shrink-1 d-flex gap-2 align-items-center">
                    <label for="element_all_layout" class="text-nowrap text-truncate mw-100 flex-shrink-0">{$LANG->getModule('setup_select_layout')}</label>
                    <select id="element_all_layout" name="layout" class="form-select flex-grow-1 flex-shrink-1 mw-100">
                        <option value="">{$LANG->getModule('setup_select_layout')}</option>
                        {foreach from=$LAYOUT_ARRAY item=layout}
                        <option value="{$layout}">{$layout}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="flex-grow-1 flex-shrink-1 d-flex gap-2 align-items-center">
                    <label for="element_all_block_module" class="text-nowrap text-truncate mw-100 flex-shrink-0">{$LANG->getModule('add_block_module')}</label>
                    <select id="element_all_block_module" name="block_module" class="form-select flex-grow-1 flex-shrink-1 mw-100">
                        <option value="">{$LANG->getModule('add_block_all_module')}</option>
                        {foreach from=$ARRAY_MODULES item=mod}
                        <option value="{$mod.module.title}">{$mod.module.custom_title}</option>
                        {/foreach}
                    </select>
                </div>
                <button type="submit" class="btn btn-primary flex-shrink-0 text-nowrap">{$LANG->getModule('setup_save_layout')}</button>
            </div>
        </div>
        <input type="hidden" name="checkss" value="{$CHECKSS}">
        <input type="hidden" name="saveall" value="1">
    </div>
</form>
<form method="post" class="ajax-submit" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" novalidate>
    <div class="row g-3">
        {foreach from=$ARRAY_MODULES item=mod}
        <div class="col-md-6 col-xl-4 col-xxl-3">
            <div class="card">
                <div class="card-header fw-medium py-2">{$mod.module.custom_title}</div>
                <ul class="list-group list-group-flush">
                    {foreach from=$mod.funcs item=func}
                    <li class="list-group-item">
                        <div class="row g-2 align-items-center">
                            <div class="col-7 text-end">
                                <div class="text-truncate">
                                    <span title="{$func.1}" data-bs-toggle="tooltip" data-bs-trigger="hover">{$func.1}</span>
                                </div>
                            </div>
                            <div class="col-5">
                                <select class="form-select form-select-sm" name="func[{$func.0}]">
                                    {foreach from=$LAYOUT_ARRAY item=layout}
                                    <option value="{$layout}"{if $layout eq $func.2} selected{/if}>{$layout}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </li>
                    {/foreach}
                </ul>
                <div class="card-footer text-center border-top py-2">
                    <button class="btn btn-primary btn-sm" type="submit">{$LANG->getModule('setup_save_layout')}</button>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
    <input type="hidden" name="checkss" value="{$CHECKSS}">
    <input type="hidden" name="save" value="1">
</form>
