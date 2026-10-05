<div class="row mb-3">
    <label for="config_titlelength" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('titlelength')}">{$LANG->getModule('titlelength')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_titlelength" id="config_titlelength" class="form-control" value="{$CONFIG.titlelength}" aria-describedby="config_titlelength_note">
        <div class="form-text" id="config_titlelength_note">{$LANG->getModule('titlenote')}</div>
    </div>
</div>
<div class="row mb-3">
    <label for="config_numrow" class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('numrow')}">{$LANG->getModule('numrow')}:</label>
    <div class="col-sm-5">
        <input type="number" name="config_numrow" id="config_numrow" class="form-control" value="{$CONFIG.numrow}">
    </div>
</div>
