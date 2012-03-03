<!-- BEGIN: main -->
<div style="width:300px;position:relative;float:left">
	<table class="tab1" style="width:330px">
		<caption>{CAPTION}</caption>
		<col span="2" valign="top" width="50%" />
		<!-- BEGIN: info -->
		<tbody{INFO.class}>
			<tr>
				<td>{INFO.val.0}</td>
				<td>{INFO.val.1}</td>
			</tr>
		</tbody>
		<!-- END: info -->
	</table>
</div>
<div style="margin-top:10px;position:relative;float:right;width:490px;">
	<a class="button2" href="javascript:void(0);" onclick="nv_show_highlight('php');"><span><span>{SHOW_LANG.0}</span></span></a>
	<a class="button2" href="javascript:void(0);" onclick="nv_show_highlight('sql');"><span><span>{SHOW_LANG.1}</span></span></a>
	<div class="clear"></div>
	<div id="my_highlight" style="background-color:#F2F2F2;border: 1px solid #CCC;margin-top:10px;padding:10px;">
		{SHOW}
	</div>
</div>
<div class="clear"></div>
<br />
<table class="tab1">
	<caption>{RCAPTION}</caption>
	<col valign="top" width="40%" />
	<col span="5" valign="top" />
	<thead>
		<tr>
			<!-- BEGIN: column --><td>{VALUE}</td>
			<!-- END: column -->
		</tr>
	</thead>
	<!-- BEGIN: detail -->
	<tbody{CLASS}>
		<tr>
			<!-- BEGIN: loop --><td>{VAL}</td>
			<!-- END: loop -->
		</tr>
	</tbody>
	<!-- END: detail -->
</table>
<!-- END: main -->
