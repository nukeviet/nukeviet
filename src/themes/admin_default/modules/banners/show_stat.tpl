<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col>
			<col class="w350">
			<col class="w100">
		</colgroup>
		<thead>
			<tr>
				<th colspan="2">{CONTENTS.0}</th>
				<th class="text-right">{CONTENTS.1}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td>
				<!-- BEGIN: t1 -->
				<a href="javascript:void(0);" onclick="{KEY}">{ROW.0}</a>
				<!-- END: t1 -->
				<!-- BEGIN: t2 -->
				{ROW.0}
				<!-- END: t2 -->
				</td>
				<td>
				<!-- BEGIN: t3 -->
				<div class="stat2">
					<div class="text-left"></div>
					<div class="text-center" style="width:{WIDTH}px;"></div>
					<div class="text-right"></div>
					<div class="text">
						{ROW.1}%
					</div>
				</div>
				<!-- END: t3 -->
				</td>
				<td class="text-right">{ROW.2}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->