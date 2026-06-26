<div class="row g-1 mb-3">
    <div class="col-6">
        <label for="choicesql_column_key" class="form-label">{$LANG->getModule('field_options_choicesql_key')}</label>
        <select class="form-select" name="choicesql_column_key" id="choicesql_column_key">
            {foreach from=$SQL_LIST item=sql}
            <option value="{$sql.key}"{if $sql.sl_key} selected{/if}>{$sql.val}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-6">
        <label for="choicesql_column_val" class="form-label">{$LANG->getModule('field_options_choicesql_val')}</label>
        <select class="form-select" name="choicesql_column_val" id="choicesql_column_val">
            {foreach from=$SQL_LIST item=sql}
            <option value="{$sql.key}"{if $sql.sl_val} selected{/if}>{$sql.val}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="row g-1">
    <div class="col-6">
        <label for="choicesql_column_order" class="form-label">{$LANG->getModule('field_options_choicesql_order')}</label>
        <select class="form-select" name="choicesql_column_order" id="choicesql_column_order">
            <option value="">--</option>
            {foreach from=$SQL_LIST item=sql}
            <option value="{$sql.key}"{if $sql.sl_order} selected{/if}>{$sql.val}</option>
            {/foreach}
        </select>
    </div>
    <div class="col-6">
        <label for="choicesql_sort_type" class="form-label">{$LANG->getModule('field_options_choicesql_sort')}</label>
        <select class="form-select" name="choicesql_sort_type" id="choicesql_sort_type">
            {foreach from=$SORT_LIST item=sort}
            <option value="{$sort.key}"{if $sort.selected} selected{/if}>{$sort.title}</option>
            {/foreach}
        </select>
    </div>
</div>
