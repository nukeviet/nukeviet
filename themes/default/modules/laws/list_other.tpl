<!-- BEGIN: main -->

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="150" />
			<col width="125" />
			<col />
		</colgroup>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td><a href="{ROW.url}" title="{ROW.code}">{ROW.code}</a></td>
				<td>{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>

<!-- END: main -->