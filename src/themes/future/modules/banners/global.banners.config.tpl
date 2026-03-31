<div class="row mb-3">
    <label for="config_idplanbanner" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('idplanbanner')}">{$LANG->getModule('idplanbanner')}:</label>
    <div class="col-sm-9">
        <select name="config_idplanbanner" id="config_idplanbanner" class="form-select">
            <option value="0">{$LANG->getModule('idplanbanner')}</option>
            {foreach from=$PLANS item=plan}
            <option value="{$plan.id}"{if $plan.id == $CONFIG.idplanbanner} selected{/if}>{$plan.show_title}</option>
            {/foreach}
        </select>
    </div>
</div>
