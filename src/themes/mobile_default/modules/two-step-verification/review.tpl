<!-- BEGIN: main -->
<div class="text-center"><i class="fa fa-shield fa-4x text-success" aria-hidden="true"></i></div>
<h1 class="text-center">{LANG.active_2tep_review1}</h1>
<div class="step-bar">
    <div class="step completed">
        <div class="circle"><i class="fa fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step completed">
        <div class="circle"><i class="fa fa-check" aria-hidden="true"></i></div>
    </div>
    <div class="step active">
        <div class="circle">3</div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <h2 class="margin-bottom">{LANG.active_2tep_review2}</h2>
        <p>{LANG.active_2tep_review3}</p>
    </div>
    <ul class="list-group">
        <li class="list-group-item tstep-flex tstep-gap-2">
            <div>
                <i class="fa fa-user-secret fa-3x fa-fw text-center" aria-hidden="true"></i>
            </div>
            <div class="tstep-grow tstep-shrink">
                <h3>
                    <strong>{LANG.passkey}</strong>
                    <!-- BEGIN: configured_passkey --><span class="label label-success">{LANG.configured}</span><!-- END: configured_passkey -->
                </h3>
                <div class="text-muted">{LANG.passkey_help}.</div>
            </div>
            <div>
                <a href="{DATA.link_passkey}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-external-link" aria-hidden="true"></i> {LANG.go_config}</a>
            </div>
        </li>
        <li class="list-group-item tstep-flex tstep-gap-2">
            <div>
                <i class="fa fa-key fa-3x fa-fw text-center" aria-hidden="true"></i>
            </div>
            <div class="tstep-grow tstep-shrink">
                <h3>
                    <strong>{LANG.security_keys}</strong>
                    <!-- BEGIN: configured_seckey --><span class="label label-success">{LANG.configured}</span><!-- END: configured_seckey -->
                </h3>
                <div class="text-muted">{LANG.security_keys_note}.</div>
            </div>
            <div>
                <a href="{DATA.link_seckey}" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-external-link" aria-hidden="true"></i> {LANG.go_config}</a>
            </div>
        </li>
    </ul>
</div>
<div class="text-center">
    <a href="{DATA.redirect}" class="btn btn-success">{GLANG.complete}</a>
</div>
<!-- END: main -->
