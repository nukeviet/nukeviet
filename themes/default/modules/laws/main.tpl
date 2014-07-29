<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th class="text-center" style="width:20px">{LANG.stt}</th>
			<th class="text-center" style="width:100px">{LANG.code}</th>
			<th class="text-center" style="width:120px">{LANG.publtime}</th>
			<th class="text-center">{LANG.introtext}</th>
		</tr>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.stt}</td>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<td class="text-center">{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.introtext}</a></td>
			</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>
<div class="generate_page">
{generate_page}
</div>
<!-- END: main -->