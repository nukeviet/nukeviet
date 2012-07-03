<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:60px;">{LANG.weight}</td>
			<td>{LANG.name}</td>
			<td>{LANG.description}</td>
			<td align="center" style="width:120px;"></td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">
				<select id="id_weight_{ROW.topicid}" onchange="nv_chang_topic('{ROW.topicid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select>
			</td>
			<td><a href="{ROW.link}">{ROW.title}</a>{ROW.numnews}</td>
			<td>{ROW.description}</td>
			<td align="center"><span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
			&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_del_topic({ROW.topicid})">{GLANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->