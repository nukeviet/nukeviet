<div class="row g-2">
    {foreach from=$ARRAY key=key item=pos}
    <div class="col-6">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="element_position_{$key}" name="position[]" value="{$pos.tag}">
            <label class="form-check-label text-truncate mw-100" for="element_position_{$key}" title="{$pos.name}">{$pos.name}</label>
        </div>
    </div>
    {/foreach}
</div>
<button type="button" class="btn btn-sm mt-2 btn-secondary" data-all="0" data-toggle="checkallpos"><i class="fa-solid fa-check-double"></i> {$LANG->getModule('block_checkall')}</button>
