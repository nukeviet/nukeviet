<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('catid')}">{$LANG->getModule('catid')}:</div>
    <div class="col-sm-9">
        <div class="maxh-200 overflow-auto">
            {foreach from=$CATS item=cat}
            {if $cat.status == 1 or $cat.status == 2}
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="config_catid[]" value="{$cat.catid}"{if in_array($cat.catid, $CONFIG.catid)} checked{/if} id="config_catid_{$cat.catid}">
                <label class="form-check-label" for="config_catid_{$cat.catid}">{if $cat.lev > 0}{'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'|str_repeat:$cat.lev}{/if}{$cat.title}</label>
            </div>
            {/if}
            {/foreach}
        </div>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('title_length')}">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_title_length" id="config_title_length" class="form-control" value="{$CONFIG.title_length}">
    </div>
</div>
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
