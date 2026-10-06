<div class="card border-primary border-3 border-bottom-0 border-start-0 border-end-0 mb-3">
    <div class="card-body pt-4">
        <form method="post" class="ajax-submit" action="{$FORM_ACTION}" novalidate>
            <div class="row mb-3">
                <label for="zalo-oaid" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('zalo_oaid')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="zalo-oaid" name="zaloOfficialAccountID" value="{$DATA.zaloOfficialAccountID}" maxlength="50" inputmode="numeric" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="zalo-app-id" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('zalo_app_id')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="text" class="form-control" id="zalo-app-id" name="zaloAppID" value="{$DATA.zaloAppID}" maxlength="50" inputmode="numeric" autocomplete="off">
                </div>
            </div>
            <div class="row mb-3">
                <label for="zalo-app-secret" class="col-sm-3 col-form-label text-sm-end">{$LANG->getModule('zalo_app_secret')}</label>
                <div class="col-sm-8 col-lg-6 col-xxl-5">
                    <input type="password" class="form-control" id="zalo-app-secret" name="zaloAppSecretKey" value="{$DATA.zaloAppSecretKey}" maxlength="50" autocomplete="new-password">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 col-lg-6 col-xxl-5 offset-sm-3">
                    <input type="hidden" name="checkss" value="{$DATA.checkss}">
                    <input type="hidden" name="save" value="1">
                    <button type="submit" class="btn btn-primary">{$LANG->getModule('save')}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header fw-medium"><i class="fa-solid fa-circle-info text-info"></i> {$LANG->getModule('zalo_setup_guide')}</div>
    <div class="card-body text-break">
        {$LANG->getModule('zalo_oa_note')}
        {$LANG->getModule('zalo_app_note')}
    </div>
</div>
