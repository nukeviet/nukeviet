<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td style="width:50px;">{LANG.weight}</td>
			<td align="center" style="width:40px;">ID</td>
			<td>{LANG.name}</td>
			<td align="center">{LANG.adddefaultblock}</td>
			<td align="center" style="width:90px;">{LANG.numlinks}</td>
			<td style="width:100px;"></td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">
				<select id="id_weight_{ROW.bid}" onchange="nv_chang_block_cat('{ROW.bid}','weight');">
					<!-- BEGIN: weight -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: weight -->
				</select>
			</td>
			<td align="center"><b>{ROW.bid}</b></td>
			<td><a href="{ROW.link}">{ROW.title}</a>{ROW.numnews}</td>
			<td align="center">
				<select id="id_adddefault_{ROW.bid}" onchange="nv_chang_block_cat('{ROW.bid}','adddefault');">
					<!-- BEGIN: adddefault -->
					<option value="{ADDDEFAULT.key}"{ADDDEFAULT.selected}>{ADDDEFAULT.title}</option>
					<!-- END: adddefault -->
				</select>
			</td>
			<td align="center">
				<select id="id_numlinks_{ROW.bid}" onchange="nv_chang_block_cat('{ROW.bid}','numlinks');">
					<!-- BEGIN: number -->
					<option value="{NUMBER.key}"{NUMBER.selected}>{NUMBER.title}</option>
					<!-- END: number -->
				</select>
			</td>
			<td align="center"><span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
			&nbsp;-&nbsp;<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_del_block_cat({ROW.bid})">{GLANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->