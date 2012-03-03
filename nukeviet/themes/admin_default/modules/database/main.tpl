<!-- BEGIN: main -->
<table class="tab1">
	<caption>{CAPTION}</caption>
	<col span="2" valign="top" width="50%" />
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.key}</td>
			<td>{ROW.value}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<div id="show_tables"></div>
<script type="text/javascript">nv_show_dbtables();</script>
<!-- END: main -->
