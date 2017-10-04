<!-- BEGIN: main -->

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col width="150" />
			<col width="125" />
			<col />
		</colgroup>
		<thead>
            <tr>
                <th class="text-center">{LANG.code}</th>
                <!-- BEGIN: publtime_title -->
                <th class="text-center">{LANG.publtime}</th>
                <!-- END: publtime_title -->
                <!-- BEGIN: comm_time -->
                <th class="text-center">{LANG.comm_time_title}</th>
                <!-- END: comm_time -->
                <th class="text-center">{LANG.trichyeu}</th>
            </tr>
        </thead>
			<!-- BEGIN: loop -->
			<tr>
				<td><a href="{ROW.url}" title="{ROW.code}">{ROW.code}</a></td>
				<!-- BEGIN: publtime -->
				<td>{ROW.publtime}</td>
				<!-- END: publtime -->
				<!-- BEGIN: comm_time -->
				<td class="text-center">{ROW.comm_time}</td>
				<!-- END: comm_time -->
				<td><a href="{ROW.url}" title="{ROW.introtext}">{ROW.introtext}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>

<!-- END: main -->