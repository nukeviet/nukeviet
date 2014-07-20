<!-- BEGIN: main -->


<table class="table table-striped table-bordered table-hover">
	<tr class="hoatim">
		<th class="text-center" style="width:20px">{LANG.stt}</th>
		<th class="text-center" style="width:100px">{LANG.code}</th>
		<th class="text-center" style="width:120px">{LANG.publtime}</th>
		<th class="text-center">{LANG.introtext}</th>
	</tr>
	<tbody>
	<!-- BEGIN: loop -->
		<tr class="hoatim">
			<td class="text-center">{ROW.stt}</td>
			<td class="text-center"><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
			<td class="text-center">{ROW.publtime}</td>
			<td class="text-center"><a href="{ROW.url}" title="{ROW.title}">{ROW.introtext}</a></td>
		</tr>
	<!-- END: loop -->
	</tbody>
</table>
<div class="generate_page">
{generate_page}
</div>
<!-- END: main -->