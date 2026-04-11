<form id="bVoting{CONFIG.bid}-v{VOTING.vid}" action="{FORM_ACTION}" method="post" data-result-title="{LANG.voting_result}" data-accept="{VOTING.accept}" data-errmsg="{VOTING.errsm}" data-toggle="votingSend" data-precheck="votingPrecheck"<!-- BEGIN: has_captcha --><!-- BEGIN: basic --> data-captcha="captcha"<!-- END: basic --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- END: has_captcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 --><!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->>
    <div class="h4 margin-bottom"><strong>{VOTING.question}</strong></div>
    <div>
        <!-- BEGIN: resultn -->
        <div class="checkbox">
            <label><input type="checkbox" name="option[]" value="{RESULT.id}" data-toggle="votingAcceptNumber"> {RESULT.title}</label>
        </div>
        <!-- END: resultn -->
        <!-- BEGIN: result1 -->
        <div class="radio">
            <label><input type="radio" name="option" value="{RESULT.id}"> {RESULT.title}</label>
        </div>
        <!-- END: result1 -->
        <div class="clearfix">
            <input class="btn btn-success btn-sm" type="submit" value="{VOTING.langsubmit}">
            <input id="bVoting{CONFIG.bid}-v{VOTING.vid}-viewresult" class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" data-toggle="votingResult">
        </div>
    </div>
    <input type="hidden" name="vid" value="{VOTING.vid}">
    <input type="hidden" name="checkss" value="{VOTING.checkss}">
    <input type="hidden" name="nv_ajax_voting" value="1">
</form>
