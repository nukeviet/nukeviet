<!-- BEGIN: main -->
<h3 class="pagetitle"><span class="big-icon bcat">&nbsp;</span>{LANG.view_subject} {CAT.title}</h3>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="50" />
			<col width="100" />
			<col width="125" />
			<col />
			<col width="130" />
		</colgroup>
		<thead>
			<tr>
				<th class="text-center">{LANG.stt}</th>
				<th class="text-center">{LANG.code}</th>
				<th class="text-center">{LANG.publtime}</th>
				<th class="text-center">{LANG.introtext}</th>
				<!-- BEGIN: down_in_home -->
				<th>{LANG.files}</th>
				<!-- END: down_in_home -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>{ROW.stt}</td>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<td class="text-center">{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
				<!-- BEGIN: down_in_home -->
				<td>
					<!-- BEGIN: files -->
						<ul style="padding: 0">
							<!-- BEGIN: loopfile -->
							<li style="display: inline-block"><em class="fa fa-download">&nbsp;</em><a href="{FILE.url}" title="{FILE.title}">{FILE.titledown}</a></li>
							<!-- END: loopfile -->
						</ul>
					<!-- END: files -->
				</td>
				<!-- END: down_in_home -->
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<div class="text-center">
	{generate_page}
</div>
<!-- END: main -->