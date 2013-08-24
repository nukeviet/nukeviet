<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:60px;">{LANG.weight}</td>
			<td>{LANG.name}</td>
			<td>{LANG.description}</td>
			<td class="center" style="width:120px;">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td class="center">
			<select id="id_weight_{ROW.topicid}" onchange="nv_chang_topic('{ROW.topicid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td><a href="{ROW.link}">{ROW.title}</a> (<a href="{ROW.linksite}">{ROW.numnews} {LANG.topic_num_news}</a>)</td>
			<td>{ROW.description}</td>
			<td class="center"><a class="edit_icon" href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;-&nbsp;<a class="delete_icon" href="javascript:void(0);" onclick="nv_del_topic({ROW.topicid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->