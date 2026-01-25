<!-- BEGIN: main -->
<form method="post" action="{DATA.form_action}">
    <div class="centered">
        <div class="sm-container-box">
            <div class="text-center">
                <div class="margin-bottom-lg">
                    <i class="fa fa-exclamation-triangle text-warning fa-3x" aria-hidden="true"></i>
                </div>
                <h1>{LANG.delacc_pending_title}</h1>
                <p>{LANG.delacc_pending_info}.</p>
            </div>
            <!-- BEGIN: error -->
            <div class="alert alert-danger">{DATA.error}</div>
            <!-- END: error -->
            <div class="box-security box-shadow-lg">
                <p class="margin-bottom-lg">{LANG.delacc_pending_info1} <strong class="text-danger">{DATA.estimated_time_show}</strong>.</p>
                <p><strong>{LANG.delacc_pending_info2}</strong>.</p>
                <hr>
                <div class="text-center">
                    <input type="hidden" name="checkss" value="{CHECKSS}">
                    <input type="hidden" name="nv_redirect" value="{NV_REDIRECT}">
                    <button type="submit" class="btn btn-primary">{LANG.delacc_cancel}</button>
                    <a href="{DATA.link_logout}" class="btn btn-default">{LANG.delacc_continue}</a>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->

<!-- BEGIN: cancel -->
<div class="centered">
    <div class="sm-container-box">
        <div class="text-center">
            <div class="margin-bottom-lg">
                <i class="fa fa-check-circle-o text-success fa-3x" aria-hidden="true"></i>
            </div>
            <h1>{LANG.delacc_cancel_success}</h1>
            <p>{LANG.delacc_cancel_success_info}.</p>
        </div>
        <div class="box-security box-shadow-lg">
            <p class="margin-bottom-lg">{LANG.delacc_cancel_success_info1}.</p>
            <p class="margin-bottom-lg">{LANG.delacc_cancel_success_info2}.</p>
            <div class="alert alert-warning">
                <strong>{LANG.delacc_cancel_success_after1}</strong><br>
                {DATA.protect_account_message}
            </div>
            <hr>
            <div class="text-center">
                <a href="{DATA.link_back}" class="btn btn-primary">{LANG.delacc_cancel_success_back}</a>
            </div>
        </div>
    </div>
</div>
<!-- END: cancel -->
