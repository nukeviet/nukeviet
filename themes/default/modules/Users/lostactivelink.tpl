<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.validate.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.validator-{NV_LANG_INTERFACE}.js"></script>

<div class="centered">
<div class="login-box">
    <div class="page panel panel-default margin-top-lg box-shadow bg-lavender">
        <div class="panel-body">
            <h2 class="text-center margin-bottom-lg">{LANG.lostactive_pagetitle}</h2>
            <!-- BEGIN: step1 -->
            <form id="lostpassForm" action="{FORM1_ACTION}" method="post" role="form" class="form-horizontal margin-bottom-lg">
                <div class="nv-info margin-bottom">
                    {LANG.lostactive_noactive}<br />- {LANG.lostactive_info1}<br />- {LANG.lostactive_info2}
                </div>
            	<div class="text-center margin-bottom">
                    <strong>{DATA.info}</strong>
            	</div>
                
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><em class="fa fa-user fa-lg"></em></span>
                        <input type="text" class="required form-control" placeholder="{GLANG.username_email}" value="" name="userField" maxlength="100">
                    </div>
                </div>

                <!-- BEGIN: captcha -->
                <div class="form-group">
                    <div class="middle text-right clearfix">
                        <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" /><em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.bsec');"></em><input type="text" style="width:100px;" class="bsec required form-control display-inline-block" name="nv_seccode" value="" maxlength="{GFX_MAXLENGTH}" placeholder="{GLANG.securitycode}" data-pattern="/^(.){{GFX_MAXLENGTH},{GFX_MAXLENGTH}}$/" onkeypress="validErrorHidden(this);" data-mess="{GLANG.securitycodeincorrect}" />
                    </div>
                </div>
                <!-- END: captcha -->
                
                <!-- BEGIN: recaptcha -->
                <div class="form-group">
                    <div class="middle text-center clearfix">
                        <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}"></div></div>
                        <script type="text/javascript">
                        nv_recaptcha_elements.push({
                            id: "{RECAPTCHA_ELEMENT}",
                            btn: $('[type="submit"]', $('#{RECAPTCHA_ELEMENT}').parent().parent().parent().parent())
                        })
                        </script>
                    </div>
                </div>
                <!-- END: recaptcha -->
                
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
<script type="text/javascript">
$(document).ready(function(){
	$('#lostpassForm').validate();
});
</script>
<!-- END: main -->