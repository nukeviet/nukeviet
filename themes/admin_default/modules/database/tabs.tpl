<!-- BEGIN: main -->
<div class="row">
	<div class="col-md-6">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
				<col span="2" class="w200"/>
				<tbody>
					<!-- BEGIN: info -->
					<tr>
						<td>{INFO.0}</td>
						<td>{INFO.1}</td>
					</tr>
					<!-- END: info -->
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-md-6" style="margin-top: 28px">
		<a class="btn btn-default" href="javascript:void(0);" onclick="nv_show_highlight('php');">{SHOW_LANG.0}</a>
		<a class="btn btn-default" href="javascript:void(0);" onclick="nv_show_highlight('sql');">{SHOW_LANG.1}</a>
		<div id="my_highlight" style="background-color:#F2F2F2;border: 1px solid #CCC;margin-top:10px;padding:10px;">
			{SHOW}
		</div>
	</div>
</div>
<div class="clearfix">&nbsp;</div>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{RCAPTION}</caption>
		<thead>
			<tr>
				<!-- BEGIN: column -->
				<th>{VALUE}</th>
				<!-- END: column -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: detail -->
			<tr>
				<!-- BEGIN: loop -->
				<td>{VAL}</td>
				<!-- END: loop -->
			</tr>
			<!-- END: detail -->
		</tbody>
	</table>
</div>
<!-- END: main -->