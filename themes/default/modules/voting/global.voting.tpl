<!-- BEGIN: main -->
<script src="{NV_STATIC_URL}themes/{TEMPLATE}/js/voting.js"></script>

<form action="{NV_BASE_SITEURL}" method="get" data-id="{VOTING.vid}" data-accept="{VOTING.accept}" data-errmsg="{VOTING.errsm}" data-checkss="{VOTING.checkss}" data-toggle="votingSend"<!-- BEGIN: has_captcha --><!-- BEGIN: basic --> data-captcha="captcha"<!-- END: basic --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- END: has_captcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
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
            <input class="btn btn-success btn-sm" type="submit" value="{VOTING.langsubmit}"/>
            <input class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" data-toggle="votingResult"/>
        </div>
    </div>
</form>
<!-- END: main -->