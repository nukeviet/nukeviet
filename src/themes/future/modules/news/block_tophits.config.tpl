<div class="row mb-3">
    <label for="config_number_day" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('number_day')}">{$LANG->getModule('number_day')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_number_day" class="form-control" value="{$CONFIG.number_day}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('numrow')}">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_numrow" class="form-control" value="{$CONFIG.numrow}">
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('showtooltip')}">{$LANG->getModule('showtooltip')}:</div>
    <div class="col-sm-9">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <input class="form-check-input" type="checkbox" value="1" name="config_showtooltip"{if not empty($CONFIG.showtooltip)} checked{/if}>
            </div>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-text">{$LANG->getModule('tooltip_position')}</div>
                    <select name="config_tooltip_position" class="form-select">
                        {foreach from=$TOOLTIP_POSITION key=key item=value}
                        <option value="{$key}"{if $CONFIG.tooltip_position eq $key} selected{/if}>{$value}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="input-group">
                    <div class="input-group-text">{$LANG->getModule('tooltip_length')}</div>
                    <input type="number" class="form-control" name="config_tooltip_length" value="{$CONFIG.tooltip_length}">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('nocatid')}">{$LANG->getModule('nocatid')}:</div>
    <div class="col-sm-9">
        <div style="max-height: 200px; overflow: auto">
            {foreach from=$CATS item=cat}
            {if $cat.status == 1 || $cat.status == 2}
                {assign var="xtitle_i" value=""}
                {if $cat.lev > 0}
                    {section name=i loop=$cat.lev}
                        {assign var="xtitle_i" value="{$xtitle_i}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"}
                    {/section}
                {/if}
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="config_nocatid[]" value="{$cat.catid}"{if in_array($cat.catid, $CONFIG.nocatid)} checked{/if} id="config_nocatid_{$cat.catid}">
                    <label class="form-check-label" for="config_nocatid_{$cat.catid}">{$xtitle_i}{$cat.title}</label>
                </div>
            {/if}
            {/foreach}
        </div>
    </div>
</div>
