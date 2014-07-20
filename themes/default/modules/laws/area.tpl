<!-- BEGIN: main -->
<h3 class="pagetitle"><span class="big-icon bcat">&nbsp;</span>{LANG.view_area} {CAT.title}</h3>
<table class="lawitem" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td class="text-center" style="width:20px">{LANG.stt}</td>
			<td class="text-center" style="width:100px">{LANG.code}</td>
			<td class="text-center" style="width:120px">{LANG.publtime}</td>
			<td class="text-center">{LANG.introtext}</td>
		</tr>
	</thead>
	<tbody>
	<!-- BEGIN: loop -->
		<tr>
			<td>{ROW.stt}</td>
			<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
			<td class="text-center">{ROW.publtime}</td>
			<td>{ROW.introtext}</td>
		</tr>
	<!-- END: loop -->
	<tbody>
</table>
<div class="generate_page">
{generate_page}
</div>
<!-- END: main -->