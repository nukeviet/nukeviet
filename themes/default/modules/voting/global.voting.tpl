<!-- BEGIN: main -->
<form action="">
	<h4>{VOTING.question}</h4>
	<fieldset>
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
			<input class="btn btn-success btn-sm" type="button" value="{VOTING.langsubmit}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', '{VOTING.accept}', '{VOTING.checkss}', '{VOTING.errsm}');" />
			<input class="btn btn-primary btn-sm" value="{VOTING.langresult}" type="button" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');" />
		</div>
	</fieldset>
</form>
<!-- END: main -->