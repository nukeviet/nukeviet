<!-- BEGIN: main -->
<h3 class="pagetitle"><span class="big-icon bdetail">&nbsp;</span>{LANG.s_result} {NUMRESULT}</h3>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="50" />
			<col width="100" />
			<col width="120" />
		</colgroup>
		<thead>
			<tr>
				<th class="text-center">{LANG.stt}</th>
				<th class="text-center">{LANG.code}</th>
				<th class="text-center">{LANG.publtime}</th>
				<th class="text-center">{LANG.introtext}</th>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{ROW.stt}</td>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<td class="text-center">{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
			</tr>
		<!-- END: loop -->
		<tbody>
	</table>
</div>

<div class="text-center">
	{generate_page}
</div>

<!-- END: main -->

<!-- BEGIN: empty -->
<div class="alert alert-info">
	{LANG.s_noresult}
</div>
<!-- END: empty -->