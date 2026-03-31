<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_name')}">{$LANG->getGlobal('company_name')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_name" value="{$CONFIG.company_name}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_sortname')}">{$LANG->getGlobal('company_sortname')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_sortname" value="{$CONFIG.company_sortname}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_regcode')}">{$LANG->getGlobal('company_regcode')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_regcode" value="{$CONFIG.company_regcode}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_regplace')}">{$LANG->getGlobal('company_regplace')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_regplace" value="{$CONFIG.company_regplace}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_licensenumber')}">{$LANG->getGlobal('company_licensenumber')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_licensenumber" value="{$CONFIG.company_licensenumber}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_responsibility')}">{$LANG->getGlobal('company_responsibility')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_responsibility" value="{$CONFIG.company_responsibility}"></div>
</div>
<div class="row">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_address')}">{$LANG->getGlobal('company_address')}:</label>
    <div class="col-sm-9">
        <div class="row g-2 mb-3">
            <div class="col-8">
                <input type="text" class="form-control" name="config_company_address" id="config_company_address" value="{$CONFIG.company_address}">
            </div>
            <div class="col-4">
                <select name="config_company_showmap" id="config_company_mapshow" class="form-select">
                    <option value="0"{if empty($CONFIG.company_showmap)} selected="selected"{/if}>{$LANG->getModule('cominfo_map_no')}</option>
                    <option value="1"{if !empty($CONFIG.company_showmap)} selected="selected"{/if}>{$LANG->getModule('cominfo_map_yes')}</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div id="config_company_maparea"{if empty($CONFIG.company_showmap)} class="d-none"{/if}></div>
    <div class="row mb-3">
        <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getModule('cominfo_mapurl')}">{$LANG->getModule('cominfo_mapurl')}:</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="config_company_mapurl" id="config_company_mapurl" value="{$CONFIG.company_mapurl}">
        </div>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_phone')}">{$LANG->getGlobal('company_phone')}:</label>
    <div class="col-sm-9">
        <input type="text" class="form-control mb-2" name="config_company_phone" value="{$CONFIG.company_phone}">
        <button type="button" class="btn btn-secondary btn-sm" onclick="modalShow('{$LANG->getGlobal('phone_note_title')}', '{$LANG->getGlobal('phone_note_content')}');return!1;"><i class="fa-solid fa-circle-question"></i> {$LANG->getGlobal('phone_note_title')}</button>
    </div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_fax')}">{$LANG->getGlobal('company_fax')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_fax" value="{$CONFIG.company_fax}"></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_email')}">{$LANG->getGlobal('company_email')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_email" value="{$CONFIG.company_email}"><span class="form-text">{$LANG->getGlobal('multi_note')}</span></div>
</div>
<div class="row mb-3">
    <label class="col-sm-3 col-form-label text-sm-end text-truncate fw-medium" title="{$LANG->getGlobal('company_website')}">{$LANG->getGlobal('company_website')}:</label>
    <div class="col-sm-9"><input type="text" class="form-control" name="config_company_website" value="{$CONFIG.company_website}"><span class="form-text">{$LANG->getGlobal('multi_note')}</span></div>
</div>
<script type="text/javascript">
$("#config_company_mapshow").on("change", function() {
    if ($(this).val() == "1") {
        $("#config_company_maparea").removeClass("d-none");
    } else {
        $("#config_company_maparea").addClass("d-none");
    }
});
</script>
