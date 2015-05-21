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

           <input class="btn btn-primary btn-sm" id="pop_{VOTING.vid}" value="{VOTING.langresult}" type="button" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');"/>

            <!-- Creates the bootstrap modal where the image will appear -->
            <div class="modal fade" id="idmodal_{VOTING.vid}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">{VOTING.question}</h4>
                  </div>
                  <div class="modal-body" id="votingcontent_{VOTING.vid}">
                    <center>Các thông tin body trong đây
                    <br>
                    {VOTING.publtime}
                    </center>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

		</div>
	</fieldset>
</form>
<br/>
<script type="text/javascript">
$(document).ready(function() {
    $("#pop_{VOTING.vid}").on("click", function() {
       $('#idmodal_{VOTING.vid}').modal('show');
    });
});
</script>
<!-- END: loop -->
<!-- END: main -->