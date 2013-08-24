<!-- BEGIN: main -->
<div id="siteDiagnostic"></div>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#siteDiagnostic").html('<p class="center"><img src="' + nv_siteroot + 'images/load_bar.gif" alt="Waiting..."/></p>').load("index.php?{NV_NAME_VARIABLE}=seotools&{NV_OP_VARIABLE}=siteDiagnostic&i=process&num=" + nv_randomPassword(10))
	});
	//]]>
</script>
<!-- END: main -->
<!-- BEGIN: scontent -->
<table class="tab1">
	<caption> {LANG.EngineInfo} </caption>
	<col style="width: 30%"/>
	<col class="top" span="7" style="width: 10%" />
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td style="text-align:center"> {THEAD} </td>
			<!-- END: thead -->
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<!-- BEGIN: td -->
			<td style="{TD.style}">
			<div {TD.class}>
				{TD.content}
			</div></td>
			<!-- END: td -->
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- BEGIN: ref -->
<div style="text-align:right;">
	<a id="diagnosticRefresh" href="#">{LANG.reCheck}</a>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(document).ready(function() {
		$("#diagnosticRefresh").click(function() {
			$("#siteDiagnostic").text("").html('<p class="center"><img src="' + nv_siteroot + 'images/load_bar.gif" alt="Waiting..."/></p>').load("index.php?{NV_NAME_VARIABLE}=seotools&{NV_OP_VARIABLE}=siteDiagnostic&i=refresh&num=" + nv_randomPassword(10));
			return false
		})
	});
	//]]>
</script>
<!-- END: ref -->
<!-- END: scontent -->