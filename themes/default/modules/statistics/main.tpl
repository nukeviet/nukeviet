<!-- BEGIN: main -->
<!-- BEGIN: hour -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody>
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M}
				<br />
				<img alt="Statistics image" src="{SRC}" height="{HEIGHT}" width="10" />
				<!-- END: img -->
				</td>
				<!-- END: loop -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<th class="text-center">
				<!-- BEGIN: h -->
				<strong><span class="label label-primary">{KEY}</span></strong>
				<!-- END: h -->
				<!-- BEGIN: h_o -->
				{KEY}
				<!-- END: h_o -->
				</th>
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td colspan="24" class="text-right"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: hour -->

<!-- BEGIN: day_k -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody class="second">
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M.count}
				<br />
				<img alt="Statistics image" src="{SRC}" height="{HEIGHT}" width="10" />
				<!-- END: img -->
				</td>
				<!-- END: loop -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<th class="text-center">
				<!-- BEGIN: dc -->
				<strong><span class="label label-primary">{M.fullname}</span></strong>
				<!-- END: dc -->
				<!-- BEGIN: dc_o -->
				{M.fullname}
				<!-- END: dc_o -->
				</th>
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td colspan="7" class="text-right"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: day_k -->

<!-- BEGIN: day_m -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody class="second">
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M}
				<br />
				<img alt="Statistics image" src="{SRC}" height="{HEIGHT}" width="10" />
				<!-- END: img -->
				</td>
				<!-- END: loop -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<th class="row1">
				<!-- BEGIN: dc -->
				<strong><span class="label label-primary">{KEY}</span></strong>
				<!-- END: dc -->
				<!-- BEGIN: dc_o -->
				{KEY}
				<!-- END: dc_o -->
				</th>
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td colspan="{CTS.numrows}" class="text-right"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: day_m -->

<!-- BEGIN: month -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody class="second">
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M.count}
				<br />
				<img alt="Statistics image" src="{SRC}" height="{HEIGHT}" width="10" />
				<!-- END: img -->
				</td>
				<!-- END: loop -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<th class="row1">
				<!-- BEGIN: mc -->
				<strong><span class="label label-primary">{M.fullname}</span></strong>
				<!-- END: mc -->
				<!-- BEGIN: mc_o -->
				{M.fullname}
				<!-- END: mc_o -->
				</th>
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td colspan="12" class="text-right"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: month -->

<!-- BEGIN: year -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody class="second">
			<tr>
				<!-- BEGIN: loop -->
				<td class="data">
				<!-- BEGIN: img -->
				{M}
				<br />
				<img alt="Statistics image" src="{SRC}" height="{HEIGHT}" width="10" />
				<!-- END: img -->
				</td>
				<!-- END: loop -->
			</tr>
			<tr>
				<!-- BEGIN: loop_1 -->
				<th class="row1">
				<!-- BEGIN: yc -->
				<strong><span class="label label-primary">{KEY}</span></strong>
				<!-- END: yc -->
				<!-- BEGIN: yc_o -->
				{KEY}
				<!-- END: yc_o -->
				</th>
				<!-- END: loop_1 -->
			</tr>
			<tr>
				<td colspan="12" class="text-right"> {CTS.total.0}: <strong>{CTS.total.1}</strong></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- END: year -->

<!-- BEGIN: ct -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody>
			<tr>
				<th colspan="2">{CTS.thead.0}</th>
				<th class="text-right">{CTS.thead.1}</th>
				<th></th>
				<th>{CTS.thead.2}</th>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td>{VALUE.0}</td>
				<td>{KEY}</td>
				<td class="text-right">{VALUE.1}</td>
				<td>
				<!-- BEGIN: img -->
				<img alt="Statistics image" src="{SRC}" height="10" width="{WIDTH}" />
				<!-- END: img -->
				</td>
				<td class="w250">{VALUE.2}</td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: ot -->
			<tr>
				<td>{CTS.others.0}</td>
				<td class="text-right">{CTS.others.1}</td>
				<td colspan="3"><a href="{URL}">{CTS.others.2}</a></td>
			</tr>
			<!-- END: ot -->
		</tbody>
	</table>
</div>
<!-- END: ct -->

<!-- BEGIN: br -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody>
			<tr>
				<th>{CTS.thead.0}</th>
				<th class="text-right">{CTS.thead.1}</th>
				<th></th>
				<th>{CTS.thead.2}</th>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td>{KEY}</td>
				<td class="text-right">{VALUE.0}</td>
				<td>
				<!-- BEGIN: img -->
				<img alt="Statistics image" src="{SRC}" height="10" width="{WIDTH}" />
				<!-- END: loop -->
				</td>
				<td class="w250">{VALUE.1}</td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: ot -->
			<tr>
				<td>{CTS.others.0}</td>
				<td class="text-right">{CTS.others.1}</td>
				<td colspan="2"><a href="{URL}">{CTS.others.2}</a></td>
			</tr>
			<!-- END: ot -->
		</tbody>
	</table>
</div>
<!-- END: br -->

<!-- BEGIN: os -->
<div class="statistics-responsive">
	<table summary="{CTS.caption}" class="table table-bordered table-striped statistics">
		<caption> {CTS.caption}</caption>
		<tbody>
			<tr>
				<th>{CTS.thead.0}</th>
				<th class="text-right">{CTS.thead.1}</th>
				<th></th>
				<th>{CTS.thead.2}</th>
			</tr>
			<!-- BEGIN: loop -->
			<tr>
				<td>{KEY}</td>
				<td class="text-right">{VALUE.0}</td>
				<td>
				<!-- BEGIN: img -->
				<img alt="Statistics image" src="{SRC}" height="10" width="{WIDTH}" />
				<!-- END: img -->
				</td>
				<td class="w250">{VALUE.1}</td>
			</tr>
			<!-- END: loop -->
			<!-- BEGIN: ot -->
			<tr>
				<td>{CTS.others.0}</td>
				<td class="text-right">{CTS.others.1}</td>
				<td colspan="2"><a href="{URL}">{CTS.others.2}</a></td>
			</tr>
			<!-- END: ot -->
		</tbody>
	</table>
</div>
<!-- END: os -->
<!-- END: main -->