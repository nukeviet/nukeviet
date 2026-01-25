<form method="post" action="{$smarty.const.NV_BASE_ADMINURL}index.php?{$smarty.const.NV_LANG_VARIABLE}={$smarty.const.NV_LANG_DATA}&amp;{$smarty.const.NV_NAME_VARIABLE}={$MODULE_NAME}&amp;{$smarty.const.NV_OP_VARIABLE}={$OP}" data-email="{$EMAIL}" data-prompt="{$LANG->getModule('smime_download_passphrase')}" data-confirm="{$LANG->getModule('smime_del_confirm')}">
    <input type="hidden" name="email" value="{$EMAIL}">
    <input type="hidden" name="passphrase" value="">
    <input type="hidden" name="smimedownload" value="1">
    <div class="table-card">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_cn')}</div>
                    <div class="col-7">{$SMIMEREAD.subject.CN}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_issuer_cn')}</div>
                    <div class="col-7">{$SMIMEREAD.issuer.O}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_subjectAltName')}</div>
                    <div class="col-7">{$SMIMEREAD.extensions.subjectAltName ?? ''}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_validTo')}</div>
                    <div class="col-7">{$SMIMEREAD.validTo_format}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_validFrom')}</div>
                    <div class="col-7">{$SMIMEREAD.validFrom_format}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_signatureTypeSN')}</div>
                    <div class="col-7">{$SMIMEREAD.signatureTypeSN}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row g-2">
                    <div class="col-5 fw-medium">{$LANG->getModule('smime_purposes')}</div>
                    <div class="col-7">{$SMIMEREAD.purposes_list}</div>
                </div>
            </li>
        </ul>
    </div>
    <div class="pt-4">
        <div class="hstack gap-2 flex-wrap justify-content-center">
            <button type="button" class="btn btn-primary" data-toggle="smimedownload"><i class="fa-solid fa-download"></i> {$LANG->getModule('smime_download')}</button>
            <button type="button" class="btn btn-danger" data-toggle="smimedel"><i class="fa-solid fa-trash" data-icon="fa-trash"></i> {$LANG->getModule('smime_del')}</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i>  {$LANG->getGlobal('close')}</button>
        </div>
    </div>
</form>
