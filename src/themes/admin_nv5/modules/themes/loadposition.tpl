<div class="form-group row">
    <label class="col-12 col-sm-3 col-form-label text-sm-right">{$LANG->get('xcopyblock_position')}</label>
    <div class="col-12 col-sm-10 col-lg-8 mt-1">
        <div class="row">
            {foreach from=$ARRAY_POSITION item=position}
            <div class="col-12 col-sm-6">
                <label class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" name="position[]" value="{$position.name}"><span class="custom-control-label">{$position.value}</span>
                </label>
            </div>
            {/foreach}
        </div>
    </div>
</div>
