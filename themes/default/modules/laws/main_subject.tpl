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
				<!-- BEGIN: publtime_title -->
                <th class="text-center">{LANG.publtime}</th>
                <!-- END: publtime_title -->
                <!-- BEGIN: comm_title -->
                <th class="text-center">{LANG.comm_time_title}</th>
                <!-- END: comm_title -->
				<th class="text-center">{LANG.trichyeu}</th>
				<!-- BEGIN: down_in_home -->
				<th>{LANG.files}</th>
				<!-- END: down_in_home -->
				<!-- BEGIN: send_comm_title -->
                <th></th>
                <!-- END: send_comm_title -->
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td colspan="<!-- BEGIN: down_in_home -->{COL}<!-- END: down_in_home --> 3" class="warning"><strong><a href="{DATA.url_subject}">{DATA.title}</a> <span class="text-danger">({DATA.numcount})</span></strong></td>
			</tr>
			<!-- BEGIN: row -->
			<tr>
				<td><a href="{ROW.url}" title="{ROW.title}">{ROW.code}</a></td>
				<!-- BEGIN: publtime -->
                <td class="text-center">{ROW.publtime}</td>
                <!-- END: publtime -->
                <!-- BEGIN: comm -->
                <td class="text-center">{ROW.comm_time}</td>
                <!-- END: comm -->
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
				<!-- BEGIN: send_comm -->
                	<td><a href="{ROW.url}" title="{ROW.send_comm_title}">{ROW.send_comm_title}</a></td>
                <!-- END: send_comm -->
			</tr>
			<!-- END: row -->
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->