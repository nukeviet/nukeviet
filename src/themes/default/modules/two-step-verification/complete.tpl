<!-- BEGIN: main -->
<script type="text/javascript" src="{ASSETS_STATIC_URL}/js/clipboard/clipboard.min.js"></script>
<div class="panel panel-success">
    <div class="panel-body">
        <h1 class="margin-bottom">{LANG.active_2tep_success}</h1>
        <p>{LANG.active_2tep_success1}.</p>
        <p>{LANG.active_2tep_success2}.</p>
    </div>
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
            <a class="btn btn-primary" href="{DATA.download_url}"><i class="fa fa-download" aria-hidden="true"></i> {GLANG.download}</a>
            <a class="btn btn-primary" href="{DATA.print_url}" data-toggle="print-codes"><i class="fa fa-print" aria-hidden="true"></i> {GLANG.print}</a>
            <button class="btn btn-primary" type="button" data-toggle="copy-codes" data-clipboard-text="{DATA.text_codes}" data-copied="{GLANG.copied}"><i class="fa fa-clipboard" aria-hidden="true"></i> <span>{GLANG.copy_to_clipboard}</span></button>
        </div>
    </div>
</div>
<div class="text-center">
    <a href="{DATA.redirect}" class="btn btn-success">{GLANG.complete}</a>
</div>
<!-- END: main -->
