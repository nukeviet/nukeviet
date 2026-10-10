<div class="row mb-3">
    <label for="config_show_type" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('show_tags')}">{$LANG->getModule('show_tags')}:</label>
    <div class="col-sm-5">
        <select name="config_show_type" id="config_show_type" class="form-select">
            <option value="1"{if $CONFIG.show_type eq 1} selected{/if}>{$LANG->getModule('show_featured')}</option>
            <option value="0"{if $CONFIG.show_type eq 0} selected{/if}>{$LANG->getModule('show_random')}</option>
        </select>
    </div>
</div>
<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('show_tags_num')}">{$LANG->getModule('show_tags_num')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_numrow" id="config_numrow" class="form-control" value="{$CONFIG.numrow}">
    </div>
</div>
