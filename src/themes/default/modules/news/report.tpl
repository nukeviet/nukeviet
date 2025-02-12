<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_STATIC_URL}themes/default/js/report.js"></script>
<!-- START FORFOOTER -->
<div class="modal fade error-report-modal" tabindex="-1" role="dialog" data-toggle="error-report-modal" data-info="{LANG.text_truncated}" data-samevalues="{LANG.report_same_values}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <div class="modal-title">{LANG.report_error_content}</div>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{LANG.error_text}</label>
                    <textarea class="form-control report_content auto-resize" maxlength="250" readonly></textarea>
                </div>
                <div class="form-group has-feedback">
                    <label>{LANG.proposal_text}</label>
                    <textarea class="form-control report_fix auto-resize" maxlength="250"></textarea>
                    <div class="invalid-feedback text-danger small" style="margin-top:5px;display:none"></div>
                </div>
                <div<!-- BEGIN: report_email_none --> style="display:none"<!-- END: report_email_none -->>
                    <div class="small help-block">{LANG.post_email_note}</div>
                    <div class="form-group">
                        <div class="input-group has-feedback">
                            <span class="input-group-addon"><strong>{LANG.post_email}</strong></span>
                            <input type="text" class="form-control report_email" maxlength="100" value="">
                            <div class="invalid-feedback text-danger small" style="margin-top:5px;display:none">{LANG.post_email_error}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.close}</button>
                <button type="button" class="btn btn-primary submit">{GLANG.submit}</button>
            </div>
        </div>
    </div>
</div>
<form method="post" action="{REPORT_URL}" class="hidden" data-toggle="error-report-form" <!-- BEGIN: captcha --> data-captcha="captcha"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 --><!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->>
    <input type="hidden" name="report_content" value="">
    <input type="hidden" name="report_fix" value="">
    <input type="hidden" name="report_email" value="">
    <input type="hidden" name="newsid" value="{NEWSID}">
    <input type="hidden" name="_csrf" value="{NEWSCHECKSS}">
    <input type="hidden" name="action" value="report">
    <button type="submit"></button>
</form>
<!-- END FORFOOTER -->
<!-- END: main -->
