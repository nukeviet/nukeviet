<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50">
			<col span="2">
			<col class="w150">
		</colgroup>
		<thead>
			<tr>
				<th>{LANG.weight}</th>
				<th>{LANG.name}</th>
				<th>{LANG.description}</th>
				<td class="text-center">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<select id="id_weight_{ROW.topicid}" onchange="nv_chang_topic('{ROW.topicid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select></td>
				<td><a href="{ROW.link}">{ROW.title}</a> (<a href="{ROW.linksite}">{ROW.numnews} {LANG.topic_num_news}</a>)</td>
				<td>{ROW.description}</td>
				<td class="text-center">
					<em class="fa fa-edit">&nbsp;</em> <a href="{ROW.url_edit}">{GLANG.edit}</a> &nbsp;
					<em class="fa fa-trash-o">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_del_topic({ROW.topicid})">{GLANG.delete}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->