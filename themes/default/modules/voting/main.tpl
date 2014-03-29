<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<form action="">
	<h3>{VOTING.question}</h3>
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
			<a title="{VOTING.langresult}" href="javascript:void(0);" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');"><button type="button" class="btn btn-link btn-sm">{VOTING.langresult}</button></a>
		</div>
	</fieldset>
</form>
<br/>
<br/>
<!-- END: loop -->
<!-- END: main -->