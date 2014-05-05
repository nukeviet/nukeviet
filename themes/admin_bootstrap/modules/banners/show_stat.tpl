<!-- BEGIN: main -->
<table class="tab1">
	<colgroup>
		<col>
		<col class="w350">
		<col class="w100">
	</colgroup>
	<thead>
		<tr>
			<td colspan="2">{CONTENTS.0}</td>
			<td>{CONTENTS.1}</td>
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
				<div class="left"></div>
				<div class="center" style="width:{WIDTH}px;"></div>
				<div class="right"></div>
				<div class="text">
					{ROW.1}%
				</div>
			</div>
			<!-- END: t3 -->
			</td>
			<td class="right">{ROW.2}</td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->