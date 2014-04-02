<!-- BEGIN: main -->
<div style="margin: 10px">
	<!-- BEGIN: note -->
		<div class="alert alert-info">{VOTINGNOTE}</div>
	<!-- END: note -->
	
	<h3 class="text-primary text-center"><span class="glyphicon glyphicon-question-sign"></span> {VOTINGQUESTION}</h3>
	
	<!-- BEGIN: result -->
		<div class="row">
			<div class="col-xs-6">{VOTING.title}</div>
			<div class="col-xs-6">
				<div class="progress">
					<div class="progress-bar" role="progressbar" aria-valuenow="{WIDTH}" aria-valuemin="0" aria-valuemax="100" style="width: {WIDTH}%;"><span class="text-danger">{WIDTH}%</span></div>
				</div>
			</div>
		</div>
	<!-- END: result -->
	
	<p class="text-center">
		<strong>{LANG.total}</strong>: {TOTAL} {LANG.counter} - <strong>{LANG.publtime}: </strong>{PUBLTIME}
	</p>
</div>
<!-- END: main -->