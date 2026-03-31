<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('module_in_menu')}">{$LANG->getModule('module_in_menu')}:</label>
    <div class="col-sm-9">
        <div class="row g-2">
            {foreach from=$SITE_MODS key=modname item=modvalues}
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox"{if in_array($modname, $CONFIG.module_in_menu, true)} checked{/if} value="{$modname}" name="module_in_menu[]" id="config_check_{$modname}">
                    <label class="form-check-label d-block text-truncate" for="config_check_{$modname}">{$modvalues.custom_title}</label>
                </div>
            </div>
            {/foreach}
        </div>
    </div>
</div>
