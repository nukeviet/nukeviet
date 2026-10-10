<div class="row mb-3">
    <label for="config_departmentid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('departmentid')}:</label>
    <div class="col-sm-9">
        <select name="config_departmentid" id="config_departmentid" class="form-select">
            {foreach from=$DEPARTMENTS item=dept}
            <option value="{$dept.id}"{if $dept.id eq $CONFIG.departmentid} selected{/if}>{$dept.full_name}</option>
            {/foreach}
        </select>
    </div>
</div>
