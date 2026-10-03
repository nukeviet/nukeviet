<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('numrow')}">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_numrow" id="config_numrow" class="form-control" value="{$CONFIG.numrow}">
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('showtooltip')}">{$LANG->getModule('showtooltip')}:</div>
    <div class="col-sm-9">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <input class="form-check-input" type="checkbox" value="1" name="config_showtooltip" aria-label="{$LANG->getModule('showtooltip')}"{if not empty($CONFIG.showtooltip)} checked{/if}>
            </div>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-text">{$LANG->getModule('tooltip_position')}</div>
                    <select name="config_tooltip_position" class="form-select" aria-label="{$LANG->getModule('tooltip_position')}">
                        {foreach from=$TOOLTIP_POSITION key=key item=value}
                        <option value="{$key}"{if $CONFIG.tooltip_position eq $key} selected{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-text">{$LANG->getModule('tooltip_length')}</div>
                    <input type="number" class="form-control" name="config_tooltip_length" value="{$CONFIG.tooltip_length}" aria-label="{$LANG->getModule('tooltip_length')}">
                </div>
            </div>
        </div>
    </div>
</div>
