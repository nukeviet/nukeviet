<div class="row mb-3">
    <label for="config_blockid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('blockid')}">{$LANG->getModule('blockid')}:</label>
    <div class="col-sm-5">
        <select name="config_blockid" class="form-select">
            <option value="0"> -- </option>
            {foreach from=$BLOCKS item=block}
            <option value="{$block.bid}"{if $CONFIG.blockid eq $block.bid} selected{/if} data-block-link="{$smarty.const.NV_BASE_SITEURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE}&amp;{$NV_OP_VARIABLE}={$SITE_MODS[$MODULE].alias.groups}/{$block.alias}">{$block.title}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('title_length')}">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="config_title_length" value="{$CONFIG.title_length}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('numrow')}">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-9">
        <input type="number" class="form-control" name="config_numrow" value="{$CONFIG.numrow}">
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
                    <input type="text" class="form-control" name="config_tooltip_length" value="{$CONFIG.tooltip_length}">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("select[name=config_blockid]").change(function() {
    $("input[name=title]").val($("select[name=config_blockid] option:selected").text());
    $("input[name=link]").val($("select[name=config_blockid] option:selected").data('block-link'));
});
</script>
