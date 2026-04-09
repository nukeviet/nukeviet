<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="page panel panel-default">
    <div class="panel-body">
        <form action=""data-id="{VOTING.vid}" data-accept="{VOTING.accept}" data-errmsg="{VOTING.errsm}" data-checkss="{VOTING.checkss}" data-toggle="votingSend"<!-- BEGIN: has_captcha --><!-- BEGIN: basic --> data-captcha="captcha"<!-- END: basic --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- END: has_captcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 --><!-- BEGIN: turnstile --> data-turnstile="1"<!-- END: turnstile -->>
            <h3>{VOTING.question}</h3>
            <div>
                <!-- BEGIN: resultn -->
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="option[]" value="{RESULT.id}" data-toggle="votingAcceptNumber">
                        {RESULT.title}
                    </label>
                </div>
                <!-- END: resultn -->
                <!-- BEGIN: result1 -->
                <div class="radio">
                    <label>
                        <input type="radio" name="option" value="{RESULT.id}">
                        {RESULT.title}
                    </label>
                </div>
                <!-- END: result1 -->
                <div class="clearfix">
                    <input class="btn btn-success btn-sm" type="submit" value="{VOTING.langsubmit}"/>
                    <input class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" data-toggle="votingResult"/>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: loop -->
<!-- END: main -->
