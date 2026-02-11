<select class="form-select" name="{$choicesql_name}" data-next="{$choicesql_next}">
    {foreach from=$SQL_LIST item=sql}
    <option value="{$sql.key}"{if $sql.sl} selected{/if}>{$sql.val}</option>
    {/foreach}
</select>
