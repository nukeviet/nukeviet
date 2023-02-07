<!-- BEGIN: main -->
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
            <div class="panel-body">
                <h2 class="text-center margin-bottom-lg">{LANG.lostactive_pagetitle}</h2>
                <!-- BEGIN: step1 -->
                <form id="lostpassForm" action="{FORM1_ACTION}" method="post" role="form" class="form-horizontal margin-bottom-lg"<!-- BEGIN: captcha --> data-captcha="nv_seccode"<!-- END: captcha --><!-- BEGIN: recaptcha --> data-recaptcha2="1"<!-- END: recaptcha --><!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
                    <div class="nv-info margin-bottom">
                        {LANG.lostactive_noactive}<br />- {LANG.lostactive_info1}<br />- {LANG.lostactive_info2}
                    </div>
                    <div class="text-center margin-bottom">
                        <strong>{DATA.info}</strong>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                            <input type="text" class="required form-control" placeholder="{LANG.username_or_email}" value="" name="userField" maxlength="100">
                        </div>
                    </div>

                    <div class="text-center margin-bottom-lg">
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <button class="bsubmit btn btn-primary" type="submit">{LANG.lostactivelink_submit}</button>
                    </div>
                </form>
                <!-- END: step1 -->
                <!-- BEGIN: step2 -->
                <form id="lostpassForm" action="{FORM2_ACTION}" method="post" role="form" class="form-horizontal m-bottom">
                    <div class="text-center margin-bottom">
                        <strong>{DATA.info}</strong>
                    </div>
                    <div class="nv-info margin-bottom">
                        {LANG.lostpass_question}: <strong>{QUESTION}</strong>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><em class="fa fa-pencil-square-o fa-lg"></em></span>
                            <input type="text" class="required form-control" placeholder="{LANG.answer_question}" value="" name="answer" maxlength="255">
                        </div>
                    </div>

                    <div class="text-center margin-bottom-lg">
                        <input type="hidden" name="userField" value="{DATA.userField}" />
                        <input type="hidden" name="nv_seccode" value="{DATA.nv_seccode}" />
                        <input type="hidden" name="checkss" value="{DATA.checkss}" />
                        <input type="hidden" name="send" value="1" />
                        <button class="bsubmit btn btn-primary" type="submit">{LANG.lostactivelink_submit}</button>
                       </div>
                </form>
                <!-- END: step2 -->

                <div class="text-center margin-top-lg">
                    <!-- BEGIN: navbar --><a href="{NAVBAR.href}" class="margin-right-lg"><em class="fa fa-caret-right margin-right-sm"></em>{NAVBAR.title}</a><!-- END: navbar -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->