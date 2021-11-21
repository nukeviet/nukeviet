<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="page panel panel-default">
    <div class="panel-body">
        <form action=""<!-- BEGIN: recaptcha3 --> data-recaptcha3="1"<!-- END: recaptcha3 -->>
        	<h3>{VOTING.question}</h3>
        	<div>
        		<!-- BEGIN: resultn -->
        		<div class="checkbox">
        		<label>
        			<input type="checkbox" name="option[]" value="{RESULT.id}" onclick="return nv_check_accept_number(this.form,'{VOTING.accept}','{VOTING.errsm}')">
        			{RESULT.title}
        		</label>
        		</div>
        		<!-- END: resultn -->
        		<!-- BEGIN: result1 -->
        		<div class="radio">
        			<label>
        		    	<input type="radio" name="option"  value="{RESULT.id}">
        		    	{RESULT.title}
        			</label>
        		</div>
        		<!-- END: result1 -->
        		<div class="clearfix">
        			<input class="btn btn-success btn-sm" type="button" value="{VOTING.langsubmit}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', '{VOTING.accept}', '{VOTING.checkss}', '{VOTING.errsm}', '{VOTING.active_captcha}');" />
                    <input class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '', '{VOTING.active_captcha}');"/>
        		</div>
        	</div>
        </form>
    </div>
</div>
<!-- BEGIN: has_captcha -->
<div id="voting-modal-{VOTING.vid}" class="hidden">
    <div class="clearfix">
        <!-- BEGIN: basic -->
        <div class="m-bottom">
            <strong>{LANG.enter_captcha}</strong>
        </div>
        <div class="clearfix">
            <div class="margin-bottom">
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control rsec" value="" name="captcha" maxlength="{GFX_MAXLENGTH}"/>
                    </div>
                    <div class="col-xs-12">
                        <img class="captchaImg display-inline-block" src="{SRC_CAPTCHA}" height="32" alt="{N_CAPTCHA}" title="{N_CAPTCHA}" />
        				<em class="fa fa-pointer fa-refresh margin-left margin-right" title="{CAPTCHA_REFRESH}" onclick="change_captcha('.rsec');"></em>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: basic -->
        <!-- BEGIN: recaptcha -->
        <div class="m-bottom text-center">
            <strong>{N_CAPTCHA}</strong>
        </div>
        <div class="margin-bottom clearfix">
            <div class="nv-recaptcha-default"><div id="{RECAPTCHA_ELEMENT}" data-toggle="recaptcha" data-pnum="3" data-btnselector="[name=submit]"></div></div>
        </div>
        <!-- END: recaptcha -->
        <input type="button" name="submit" class="btn btn-primary btn-block" value="{VOTING.langsubmit}" onclick="nv_sendvoting_captcha(this, {VOTING.vid}, '{LANG.enter_captcha_error}');"/>
    </div>
</div>
<!-- END: has_captcha -->
<!-- END: loop -->
<!-- END: main -->