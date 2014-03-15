<!-- BEGIN: main -->
<div style="width:400px;position:relative;float:left">
	<table class="tab1">
		<caption>{CAPTION}</caption>
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
<div style="margin-top:10px;position:relative;float:right;width:490px;">
	<a class="button button-h" href="javascript:void(0);" onclick="nv_show_highlight('php');">{SHOW_LANG.0}</a>
	<a class="button button-h" href="javascript:void(0);" onclick="nv_show_highlight('sql');">{SHOW_LANG.1}</a>
	<div class="clearfix"></div>
	<div id="my_highlight" style="background-color:#F2F2F2;border: 1px solid #CCC;margin-top:10px;padding:10px;">
		{SHOW}
	</div>
</div>
<div class="clearfix"></div>
<br />
<table class="tab1">
	<caption>{RCAPTION}</caption>
	<thead>
		<tr>
			<!-- BEGIN: column -->
			<td>{VALUE}</td>
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
<!-- END: main -->