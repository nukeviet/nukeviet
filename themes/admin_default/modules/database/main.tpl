<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CAPTION}</caption>
		<col span="2" style="width: 50%" />
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.key}</td>
				<td>{ROW.value}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<div id="show_tables"></div>
<script type="text/javascript">nv_show_dbtables();</script>
<!-- END: main -->