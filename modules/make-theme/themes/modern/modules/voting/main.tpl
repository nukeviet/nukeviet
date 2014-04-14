<!-- BEGIN: main -->
<!-- BEGIN: loop -->
<div class="box-border">
	<div class="content-box">
		<form action="" method="post">
			<div class="block-vote">
				<p>
					<strong>{VOTING.question}</strong>
				</p>
				<!-- BEGIN: resultn -->
				<p>
					<input type="checkbox" name="option[]" value="{RESULT.id}" onclick="return nv_check_accept_number(this.form,'{VOTING.accept}','{VOTING.errsm}')"/>
					<span class="right">{RESULT.title}</span>
				</p>
				<!-- END: resultn -->
				<!-- BEGIN: result1 -->
				<p>
					<input type="radio" name="option" value="{RESULT.id}" />
					{RESULT.title}
				</p>
				<!-- END: result1 -->
				<div class="f-action">
					<input class="button" type="button" value="{VOTING.langsubmit}" onclick="nv_sendvoting(this.form, '{VOTING.vid}', '{VOTING.accept}', '{VOTING.checkss}', '{VOTING.errsm}');"/>
					<a title="{VOTING.langresult}" href="javascript:void(0);" onclick="nv_sendvoting(this.form, '{VOTING.vid}', 0, '{VOTING.checkss}', '');">&nbsp; {VOTING.langresult}</a>
				</div>
			</div>
		</form>
	</div>
</div>
<br/>
<!-- END: loop -->
<!-- END: main -->