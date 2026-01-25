<!-- BEGIN: main -->
<div class="centered">
    <div class="login-box">
        <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
            <div class="panel-body">
                <h2 class="text-center margin-bottom-lg">{LANG.remove_2step_method_title}</h2>
                <form id="remove2step" action="{FORM_ACTION}" method="post" role="form" class="form-horizontal margin-bottom-lg">
                    <div class="nv-info margin-bottom">
                        {LANG.remove_2step_info}:
                    </div>
                    <div class="form-detail">
                        <div class="step1">
                            <div>
                                <div class="input-group margin-bottom-lg">
                                    <span class="input-group-addon"><em class="fa fa-envelope fa-fw"></em></span>
                                    <input type="email" class="required form-control" placeholder="{LANG.remove_2step_email}" value="" name="useremail" maxlength="100" data-toggle="validErrorHidden" data-event="keypress" data-mess="{GLANG.email_empty}">
                                </div>
                            </div>
                            <!-- BEGIN: sec_question -->
                            <div class="margin-bottom-lg">
                                {LANG.lostpass_question}: <strong>{DATA.question}</strong>
                            </div>
                            <div>
                                <div class="input-group margin-bottom-lg">
                                    <span class="input-group-addon"><em class="fa fa-shield fa-fw"></em></span>
                                    <input type="text" class="required form-control" placeholder="{LANG.answer}" value="" name="useranswer" maxlength="100" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.answer_empty}">
                                </div>
                            </div>
                            <!-- END: sec_question -->
                        </div>
                        <div class="step2" style="display: none;">
                            <div>
                                <div class="input-group margin-bottom-lg">
                                    <span class="input-group-addon"><em class="fa fa-key fa-fw"></em></span>
                                    <input type="text" class="required form-control" placeholder="{LANG.verifykey}" value="" name="verifykey" maxlength="100" data-pattern="/^(.){1,}$/" data-toggle="validErrorHidden" data-event="keypress" data-mess="{LANG.verifykey_empty}">
                                </div>
                            </div>
                        </div>
                        <div class="text-center margin-bottom-lg">
                            <input type="hidden" name="email_sent" value="0" />
                            <input type="hidden" name="checkss" value="{DATA.checkss}" />
                            <button class="bsubmit btn btn-primary" type="submit">{GLANG.submit}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->