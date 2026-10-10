<h6>{$LANG->getModule('cfg_step1')}</h6>
<div class="text-center">
    <img alt="QR" src="{$QR_SRC}" class="twostep-qrimg img-thumbnail">
</div>
<hr>
<p>
    {$LANG->getModule('cfg_step1_manual')}
    <a href="#" data-bs-toggle="modal" data-bs-target="#manualsecretkey-modal">{$LANG->getModule('cfg_step1_manual1')}</a>
    {$LANG->getModule('cfg_step1_manual2')}.
</p>
<p>{$LANG->getModule('cfg_step2_info')}</p>
<h6 class="mb-2">{$LANG->getModule('cfg_step2')}</h6>
<form action="{$FORM_ACTION}" method="post" data-toggle="ajax-form" data-precheck="nv_precheck_form" autocomplete="off" novalidate>
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-key fa-fw"></i></span>
            <input type="text" class="required form-control" placeholder="123456"
                   value="" data-valid name="opt" minlength="6" maxlength="6">
        </div>
        <div class="invalid-feedback"></div>
    </div>
    <div class="text-center">
        <input type="hidden" name="checkss" value="{$smarty.const.NV_CHECK_SESSION}">
        <input type="hidden" name="nv_redirect" value="{$NV_REDIRECT}">
        <button class="btn btn-primary" type="submit">
            <i class="fa-solid fa-check"></i> {$LANG->getModule('confirm')}
        </button>
    </div>
</form>
<div class="modal fade" id="manualsecretkey-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{$LANG->getModule('setup_key')}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{$LANG->getGlobal('close')}"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <strong class="h4">{$SECRETKEY}</strong>
                </div>
                <p class="text-muted">{$LANG->getModule('cfg_step1_note')}</p>
            </div>
        </div>
    </div>
</div>
