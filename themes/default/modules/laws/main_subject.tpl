<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="150" />
			<col width="130" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th class="text-center">{LANG.code}</th>
				<th class="text-center">{LANG.publtime}</th>
				<th class="text-center">{LANG.trichyeu}</th>
				<!-- BEGIN: down_in_home -->
				<th>{LANG.files}</th>
				<!-- END: down_in_home -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td colspan="<!-- BEGIN: down_in_home -->4<!-- END: down_in_home --> 3" class="warning"><strong><a href="{DATA.url_subject}">{DATA.title}</a> <span class="text-danger">({DATA.numcount})</span></strong></td>
			</tr>
			<!-- BEGIN: row -->
			<tr>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<td class="text-center">{ROW.publtime}</td>
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
				<!-- BEGIN: down_in_home -->
				<td width="130">
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
			<!-- END: row -->
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->