<div class="row mb-3">
    <label for="config_show_type" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('show_type')}">{$LANG->getModule('show_type')}:</label>
    <div class="col-sm-5">
        <select name="config_show_type" id="config_show_type" class="form-select">
            <option value="random"{if $CONFIG.show_type eq 'random'} selected{/if}>{$LANG->getModule('show_type_random')}</option>
            <option value="fixed"{if $CONFIG.show_type eq 'fixed'} selected{/if}>{$LANG->getModule('show_type_fixed')}</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_vid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('vid')}">{$LANG->getModule('vid')}:</label>
    <div class="col-sm-5">
        <select name="config_vid" id="config_vid" class="form-select">
            <option value="0">----</option>
            {foreach from=$ITEMS item=item}
            <option value="{$item.vid}"{if $CONFIG.vid eq $item.vid} selected{/if}>{$item.question}</option>
            {/foreach}
        </select>
    </div>
</div>
