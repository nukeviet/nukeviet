<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="page">
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
                <input class="btn btn-primary btn-sm" type="button" value="{VOTING.langresult}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');"/>
    		</div>
    	</fieldset>
    </form>
</div>
<hr>
<!-- END: loop -->
<div class="modal fade" id="idmodals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				&nbsp;
			</div>
			<div class="modal-body">
				<em class="fa fa-spinner fa-spin">&nbsp;</em>
			</div>
		</div>
	</div>
</div>
<!-- END: main -->