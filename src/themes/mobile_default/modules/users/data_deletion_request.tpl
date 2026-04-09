<!-- BEGIN: main -->
<form method="post" action="{DATA.form_action}">
    <div class="centered user-delete-account" id="user-request-deletion-page" data-checkss="{CHECKSS}">
        <div class="sm-container-box">
            <!-- BEGIN: not_confirmed -->
            <div class="usr-flex usr-justify-between usr-gap-2 margin-bottom-lg">
                <div>
                    <h1>{LANG.delaccount_title}</h1>
                    <p>{LANG.delaccount_note}.</p>
                </div>
                <div>
                    <a href="{DATA.link_back}" class="btn btn-default"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> {LANG.delaccount_back}</a>
                </div>
            </div>
            <div class="box-security box-shadow-lg">
                <div class="alert alert-danger">
                    <strong>{LANG.delaccount_warn1}</strong><br>
                    {LANG.delaccount_warn2}
                </div>
                <h2 class="margin-bottom">{LANG.delaccount_explain1}</h2>
                <p>{LANG.delaccount_explain2}.</p>
                <p><strong>{LANG.delaccount_explain3}:</strong></p>
                <p>{LANG.delaccount_explain4}.</p>
                <p><strong>{LANG.delaccount_explain5}:</strong></p>
                <p>{LANG.delaccount_explain6}:</p>
                <ul class="list-default">
                    <li>{LANG.delaccount_explain7}</li>
                    <li>{LANG.delaccount_explain8}</li>
                    <li>{HOLD_MESSAGE}</li>
                </ul>
                <hr>
                <div class="usr-flex usr-gap-1 margin-bottom-lg">
                    <input type="checkbox" id="i_confirmed" name="i_confirmed" value="1">
                    <div>
                        <label for="i_confirmed" class="margin-bottom-sm">{LANG.delaccount_confirm1}</label>
                        <div><small>{LANG.delaccount_confirm2}</small></div>
                    </div>
                </div>
                <div class="text-center">
                    <input type="hidden" name="submit_confirmed" value="1">
                    <button type="submit" class="btn btn-danger" disabled>{LANG.delaccount_confirm3}</button>
                </div>
            </div>
            <!-- END: not_confirmed -->
            <!-- BEGIN: verification_page -->
            <input type="hidden" name="i_confirmed" value="1">
            <input type="hidden" name="checkss" value="{CHECKSS}">
            <div class="usr-flex usr-justify-between usr-gap-2 margin-bottom-lg">
                <div>
                    <h1>{LANG.delaccount_veremail_title}</h1>
                    <p>{LANG.delaccount_veremail_note}.</p>
                </div>
                <div>
                    <a href="{DATA.link_back}" class="btn btn-default"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> {LANG.delaccount_back}</a>
                </div>
            </div>
            <div class="box-security box-shadow-lg">
                <!-- BEGIN: error -->
                <div class="alert alert-danger" role="alert">{DATA.error}</div>
                <!-- END: error -->
                <div class="text-center">
                    <p class="margin-bottom-lg"><i class="fa fa-envelope-o fa-3x text-primary" aria-hidden="true"></i></p>
                    <h2 class="margin-bottom">{LANG.delaccount_veremail_checkmail}</h2>
                    <p>{DATA.message_checkmail}.</p>
                </div>
                <div class="form-group">
                    <input type="text" maxlength="10" name="verification_code" value="" class="form-control text-center input-confirm-code" placeholder="_ _ _ _ _ _ _ _ _ _" autocomplete="off">
                </div>
                <div class="text-center">
                    <button type="submit" disabled class="btn btn-danger">{LANG.delaccount_veremail_title}</button>
                </div>
                <hr>
                <div class="text-center small">
                    {LANG.not_received_code}
                    <span data-toggle="timer-code" class="text-primary text-bold"<!-- BEGIN: timing_code --> style="display: none;"<!-- END: timing_code -->>{LANG.try_received_code} <span data-toggle="time-code-remain">{DATA.time_code_remain}</span>s</span>
                    <a href="#" class="text-bold"<!-- BEGIN: request_new_code --> style="display: none;"<!-- END: request_new_code --> data-toggle="request-new-code">{LANG.send_received_code}</a>
                    <span data-toggle="recode-loader" class="text-primary" style="display: none;"><i class="fa fa-spinner fa-pulse"></i></span>
                </div>
            </div>
            <!-- END: verification_page -->
        </div>
    </div>
</form>
<!-- END: main -->
