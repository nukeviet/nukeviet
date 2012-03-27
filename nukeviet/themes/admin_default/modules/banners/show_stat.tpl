<!-- BEGIN: main -->
<table summary = "{CONTENTS.0}" class="tab1">
	<thead>
		<tr>
			<td colspan="2">{CONTENTS.0}</td>
			<td style="width:100px;text-align:right;">{CONTENTS.1}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
		<td>
			<!-- BEGIN: t1 --><a href="javascript:void(0);" onclick="{KEY}">{ROW.0}</a><!-- END: t1 -->
			<!-- BEGIN: t2 -->{ROW.0}<!-- END: t2 -->
		</td>
		<td style="width:350px;">
			<!-- BEGIN: t3 -->
			<div class="stat2">
				<div class="left"></div>
				<div class="center" style="width:{WIDTH}px;"></div>
				<div class="right"></div>
				<div class="text">{ROW.1}%</div>
			</div>
			<!-- END: t3 -->
		</td>
		<td style="width:100px;text-align:right;">{ROW.2}</td>
		</tr>
		</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->