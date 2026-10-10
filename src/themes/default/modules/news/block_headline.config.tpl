<div class="row mb-3">
    <label for="config_group_id" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('blockid1')}">{$LANG->getModule('blockid1')}:</label>
    <div class="col-sm-5">
        <select name="config_group_id" id="config_group_id" class="form-select">
            {foreach from=$GROUPS item=group}
            <option value="{$group.bid}"{if $CONFIG.group_id eq $group.bid} selected{/if}>{$group.title}</option>
            {/foreach}
        </select>
    </div>
</div>
