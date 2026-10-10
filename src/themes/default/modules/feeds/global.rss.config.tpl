<div class="row mb-3">
    <label for="config_url" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('url')}:</label>
    <div class="col-sm-9">
        <input class="form-control" name="config_url" id="config_url" type="text" value="{$CONFIG.url}" />
    </div>
</div>
<div class="row mb-3">
    <label for="config_number" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('number')}:</label>
    <div class="col-sm-9">
        <select name="config_number" id="config_number" class="form-select">
            {for $i=1 to 50}
            <option value="{$i}"{if $i eq $CONFIG.number} selected{/if}>{$i}</option>
            {/for}
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_title_length" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('title_length')}:</label>
    <div class="col-sm-9">
        <select name="config_title_length" id="config_title_length" class="form-select">
            {for $i=0 to 255}
            <option value="{$i}"{if $i eq $CONFIG.title_length} selected{/if}>{$i}</option>
            {/for}
        </select>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">{$LANG->getModule('isdescription')}</label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="config_isdescription" name="config_isdescription" value="1"{if $CONFIG.isdescription} checked{/if} />
            <label for="config_isdescription" class="form-check-label">{$LANG->getModule('block_yes')}</label>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">{$LANG->getModule('ishtml')}:</label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="config_ishtml" name="config_ishtml" value="1"{if $CONFIG.ishtml} checked{/if} />
            <label for="config_ishtml" class="form-check-label">{$LANG->getModule('block_yes')}</label>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">{$LANG->getModule('ispubdate')}:</label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="config_ispubdate" name="config_ispubdate" value="1"{if $CONFIG.ispubdate} checked{/if} />
            <label for="config_ispubdate" class="form-check-label">{$LANG->getModule('block_yes')}</label>
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label py-0 text-sm-end text-truncate fw-medium">{$LANG->getModule('istarget')}:</label>
    <div class="col-sm-9">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="config_istarget" name="config_istarget" value="1"{if $CONFIG.istarget} checked{/if} />
            <label for="config_istarget" class="form-check-label">{$LANG->getModule('block_yes')}</label>
        </div>
    </div>
</div>
