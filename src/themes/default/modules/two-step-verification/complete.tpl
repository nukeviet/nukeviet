<!-- BEGIN: main -->
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="step-bar">
    <div class="step completed">
        <div class="circle"><i class="fa fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step active">
        <div class="circle">2</div>
    </div>
    <div class="step">
        <div class="circle">3</div>
    </div>
</div>
<div class="alert alert-info" role="alert">
    <h1 class="margin-bottom">{LANG.active_2tep_success}</h1>
    <p>{LANG.active_2tep_success1}.</p>
    <p>{LANG.active_2tep_success2}.</p>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2>{LANG.recovery_codes}</h2>
    </div>
    <div class="panel-body">
        <p>
            {LANG.active_2tep_success3}.
        </p>
        <div class="alert alert-info">
            {LANG.backupcode_2step_note}
        </div>
        <div class="row">
            <!-- BEGIN: code -->
            <div class="col-xs-12 text-center">
                <div class="recovery-code">
                    <span class="h1">{CODE.code}</span>
                </div>
            </div>
            <!-- END: code -->
        </div>
        <div class="text-center">
            <a class="btn btn-primary confirmed-codes" href="{DATA.download_url}"><i class="fa fa-download" aria-hidden="true"></i> {GLANG.download}</a>
            <a class="btn btn-primary confirmed-codes" href="{DATA.print_url}" data-toggle="print-codes"><i class="fa fa-print" aria-hidden="true"></i> {GLANG.print}</a>
            <button class="btn btn-primary confirmed-codes" type="button" data-toggle="copy-codes" data-clipboard-text="{DATA.text_codes}" data-copied="{GLANG.copied}"><i class="fa fa-clipboard" aria-hidden="true"></i> <span>{GLANG.copy_to_clipboard}</span></button>
        </div>
    </div>
</div>
<div class="text-center">
    <button type="button" disabled class="btn btn-success" data-toggle="confirm-complete" data-link="{DATA.redirect}">{GLANG.continue}</button>
    <div class="margin-top text-muted"><i>{LANG.active_2tep_success4}</i></div>
</div>
<!-- END: main -->
