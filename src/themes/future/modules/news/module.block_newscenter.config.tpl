<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('numrow')}">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_numrow" id="config_numrow" class="form-control" value="{$CONFIG.numrow}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_width" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('width')}">{$LANG->getModule('width')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_width" id="config_width" class="form-control" value="{$CONFIG.width}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_height" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('height')}">{$LANG->getModule('height')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_height" id="config_height" class="form-control" value="{$CONFIG.height}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_length_title" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('length_title')}">{$LANG->getModule('length_title')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_length_title" id="config_length_title" class="form-control" value="{$CONFIG.length_title}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_length_hometext" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('length_hometext')}">{$LANG->getModule('length_hometext')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_length_hometext" id="config_length_hometext" class="form-control" value="{$CONFIG.length_hometext}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_length_othertitle" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('length_othertitle')}">{$LANG->getModule('length_othertitle')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_length_othertitle" id="config_length_othertitle" class="form-control" value="{$CONFIG.length_othertitle}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_margin_bottom" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('margin_bottom')}">{$LANG->getModule('margin_bottom')}:</label>
    <div class="col-sm-5">
        <select name="config_margin_bottom" id="config_margin_bottom" class="form-select">
            {for $i=0 to 5}
            <option value="{$i}"{if $CONFIG.margin_bottom eq $i} selected{/if}>{$i}</option>
            {/for}
        </select>
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
<div class="row mb-3">
    <div class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('nocatid')}">{$LANG->getModule('nocatid')}:</div>
    <div class="col-sm-9">
        <div class="maxh-200 overflow-auto">
            {foreach from=$CATS item=cat}
            {if $cat.status == 1 or $cat.status == 2}
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="config_nocatid[]" value="{$cat.catid}"{if in_array($cat.catid, $CONFIG.nocatid)} checked{/if} id="config_nocatid_{$cat.catid}">
                <label class="form-check-label" for="config_nocatid_{$cat.catid}">{if $cat.lev > 0}{'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'|str_repeat:$cat.lev}{/if}{$cat.title}</label>
            </div>
            {/if}
            {/foreach}
        </div>
    </div>
</div>
