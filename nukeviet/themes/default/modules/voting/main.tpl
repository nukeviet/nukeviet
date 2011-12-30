<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<form class="voting" action="">
    <h2>{VOTING.question}</h2>
    <fieldset>
        <!-- BEGIN: resultn -->
	        <p>
	            <input type="checkbox" name="option[]" value="{RESULT.id}" onclick="return nv_check_accept_number(this.form,'{VOTING.accept}','{VOTING.errsm}')"/>{RESULT.title}
	        </p>
        <!-- END: resultn -->
        <!-- BEGIN: result1 -->
	        <p>
	            <input type="radio" name="option" value="{RESULT.id}" />{RESULT.title}
	        </p>
        <!-- END: result1 -->
        <div style="padding-top: 10px;" class="clearfix">
            <div class="submit">
                <input class="submit" type="button" value="{VOTING.langsubmit}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', '{VOTING.accept}', '{VOTING.checkss}', '{VOTING.errsm}');" />
            </div>
            <a class="forgot fl" title="{VOTING.langresult}" href="javascript:void(0);" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');">{VOTING.langresult}</a>
        </div>
    </fieldset>
</form>
<br/><br/>
<!-- END: loop -->
<!-- END: main -->