<!-- BEGIN: main -->
<table class="statistics" summary="{CTS.caption}">
	<caption>{CTS.caption}</caption>
	<tbody class="second">
		<tr>
			<!-- BEGIN: loop -->
			<td style="text-align: center; width: 35px; font-size: 8px; vertical-align: bottom;">
			<!-- BEGIN: img -->
				{M.count}
				<br/>
				<img width="10" height="{HEIGHT}" src="{SRC}" alt=""></td><!-- END: loop -->
				<!-- END: img -->
		</tr>
	</tbody>
	<tbody>
		<tr>
			<!-- BEGIN: loop_1 --><!-- BEGIN: m_c -->
			<th style="width: 35px; text-align: center;">
				<strong><span style="text-decoration: underline;">{M.fullname}</span></strong>
			</th>
			<!-- END: m_c --><!-- BEGIN: m_o -->
			<th style="width: 35px; text-align: center;">{M.fullname}</th>
			<!-- END: m_o --><!-- END: loop_1 -->
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td style="text-align: right;" colspan="12">
				{CTS.total.0}: <strong>{CTS.total.1}</strong>
			</td>
		</tr>
	</tbody>
</table>
<!-- END: main -->
