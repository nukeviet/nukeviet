<div class="row mb-3">
    <label for="config_departmentid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('departmentid')}:</label>
    <div class="col-sm-5">
        <select name="config_departmentid" id="config_departmentid" class="form-select">
            {foreach from=$DEPARTMENTS item=department}
            {if $department.act}
            <option value="{$department.id}"{if $department.id eq $CONFIG.departmentid} selected{/if}>{$department.full_name}</option>
            {/if}
            {/foreach}
        </select>
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-9 offset-sm-3 form-text">{$LANG->getModule('pick_department')}</div>
    <div class="col-sm-3"></div>
</div>

