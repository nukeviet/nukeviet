<div class="row mb-3">
    <label for="config_facebookappid" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('facebookappid')}:</label>
    <div class="col-sm-9">
        <input class="form-control" type="text" name="config_facebookappid" id="config_facebookappid" value="{$CONFIG.facebookappid}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_width" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('width')}:</label>
    <div class="col-sm-9">
        <input class="form-control" type="text" name="config_width" id="config_width" value="{$CONFIG.width}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_numpost" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('numpost')}:</label>
    <div class="col-sm-9">
        <input class="form-control" type="text" name="config_numpost" id="config_numpost" value="{$CONFIG.numpost}">
    </div>
</div>
<div class="row mb-3">
    <label for="config_scheme" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium">{$LANG->getModule('scheme')}:</label>
    <div class="col-sm-5">
        <select class="form-select" name="config_scheme" id="config_scheme">
            <option value="light"{if $CONFIG.scheme eq 'light'} selected{/if}>Light</option>
            <option value="dark"{if $CONFIG.scheme eq 'dark'} selected{/if}>Dark</option>
        </select>
    </div>
</div>
