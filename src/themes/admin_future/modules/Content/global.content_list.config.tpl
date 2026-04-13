<div class="row mb-3">
    <label class="col-sm-4 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-8 col-lg-6">
        <input type="number" class="form-control" name="config_title_length" value="{$DATA.title_length}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-4 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-8 col-lg-6">
        <input type="number" class="form-control" name="config_numrow" value="{$DATA.numrow}">
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-4 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('cat')}:</label>
    <div class="col-sm-8 col-lg-6">
        <select name="config_catid" class="form-select">
            <option value="0">--- {$LANG->getModule('cat_select')} ---</option>
            {foreach from=$CATS key=catid item=title}
            <option value="{$catid}"{if $catid eq $DATA.catid} selected{/if}>{$title}</option>
            {/foreach}
        </select>
    </div>
</div>
