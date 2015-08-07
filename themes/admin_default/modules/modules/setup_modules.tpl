<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: head -->
				<th>{HEAD}</th>
				<!-- END: head -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.stt}</td>
				<td>{ROW.values.title}</td>
				<td>{ROW.values.version}</td>
				<td>{ROW.values.addtime}</td>
				<td>{ROW.values.author}</td>
				<td><em class="fa fa-sun-o fa-lg"></em> <a class="nv-setup-module" data-title="{ROW.values.title}" href="{ROW.values.url_setup}">{LANG.setup}</a> {ROW.values.delete}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- BEGIN: vmodule -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{VCAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: vhead -->
				<th>{VHEAD}</th>
				<!-- END: vhead -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{VROW.stt}</td>
				<td>{VROW.values.title}</td>
				<td>{VROW.values.module_file}</td>
				<td>{VROW.values.addtime}</td>
				<td>{VROW.values.note}</td>
				<td>
					<!-- BEGIN: setup -->
						<em class="fa fa-sun-o fa-lg"></em><a class="nv-setup-module" data-title="{VROW.values.title}" href="{VROW.values.url_setup}">{LANG.setup}</a>
					<!-- END: setup -->
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: vmodule -->
<!-- START FORFOOTER -->
<div class="modal fade" id="modal-setup-module">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">{LANG.setup}</h4>
			</div>
			<div class="modal-body">
				<p class="text-info message"></p>
				<div class="row form-horizontal">
					<div class="col-xs-6 text-right">
						<label class="control-label">{LANG.setup_option}:</label>
					</div>
					<div class="col-xs-12">
						<select class="form-control option">
							<option value="0">{LANG.setup_option_0}</option>
							<option value="1">{LANG.setup_option_1}</option>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary submit">{GLANG.submit}</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">{GLANG.cancel}</button>
			</div>
		</div>
	</div>
</div>
<!-- END FORFOOTER -->
<!-- END: main -->