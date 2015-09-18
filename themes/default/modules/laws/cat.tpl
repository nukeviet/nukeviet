<!-- BEGIN: main -->
<h3 class="pagetitle"><span class="big-icon bcat">&nbsp;</span>{LANG.view_cat} {CAT.title}</h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th style="width:20px">{LANG.stt}</th>
			<th style="width:100px">{LANG.code}</th>
			<th style="width:120px">{LANG.publtime}</th>
			<th>{LANG.introtext}</th>
		</tr>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.stt}</td>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<td class="text-center">{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
			</tr>
		<!-- END: loop -->
		</tbody>
	</table>
</div>
<div class="generate_page">
{generate_page}
</div>
<!-- END: main -->