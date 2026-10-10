<script src="{$smarty.const.ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="step-bar">
    <div class="step completed">
        <div class="circle"><i class="fa-solid fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step active">
        <div class="circle">2</div>
    </div>
    <div class="step">
        <div class="circle">3</div>
    </div>
</div>
<div class="alert alert-info" role="alert">
    <h1 class="mb-2 h4">{$LANG->getModule('active_2tep_success')}</h1>
    <p>{$LANG->getModule('active_2tep_success1')}.</p>
    <p class="mb-0">{$LANG->getModule('active_2tep_success2')}.</p>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h2 class="mb-0 h5">{$LANG->getModule('recovery_codes')}</h2>
    </div>
    <div class="card-body">
        <p>{$LANG->getModule('active_2tep_success3')}.</p>
        <div class="alert alert-info" role="alert">
            {$LANG->getModule('backupcode_2step_note')}
        </div>
        <div class="row">
            {foreach from=$CODES item=code}
            <div class="col-6 col-sm-4 col-md-3 text-center">
                <div class="recovery-code font-monospace">
                    <span class="h4">{$code.code}</span>
                </div>
            </div>
            {/foreach}
        </div>
        <div class="text-center mt-3 d-flex flex-wrap gap-2 justify-content-center">
            <a class="btn btn-primary confirmed-codes" href="{$DATA.download_url}">
                <i class="fa-solid fa-download" aria-hidden="true"></i> {$LANG->getGlobal('download')}
            </a>
            <a class="btn btn-primary confirmed-codes" href="{$DATA.print_url}" data-toggle="print-codes">
                <i class="fa-solid fa-print" aria-hidden="true"></i> {$LANG->getGlobal('print')}
            </a>
            <button class="btn btn-primary confirmed-codes" type="button"
                    data-toggle="copy-codes"
                    data-clipboard-text="{$DATA.text_codes}"
                    data-copied="{$LANG->getGlobal('copied')}">
                <i class="fa-solid fa-clipboard" aria-hidden="true"></i>
                <span>{$LANG->getGlobal('copy_to_clipboard')}</span>
            </button>
        </div>
    </div>
</div>
<div class="text-center">
    <button type="button" disabled class="btn btn-success" data-toggle="confirm-complete" data-link="{$DATA.redirect}">
        {$LANG->getGlobal('continue')}
    </button>
    <div class="mt-2 text-muted"><i>{$LANG->getModule('active_2tep_success4')}</i></div>
</div>
