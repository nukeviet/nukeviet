<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td style="width:50px;">{LANG.weight}</td>
			<td style="width:40px;">ID</td>
			<td>{LANG.name}</td>
			<td>{LANG.adddefaultblock}</td>
			<td style="width:100px;"></td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td align="center">
			<select id="id_weight_{ROW.bid}" onchange="nv_chang_block_cat('{ROW.bid}','weight');">
				<!-- BEGIN: weight -->
				<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
				<!-- END: weight -->
			</select></td>
			<td align="center"><b>{ROW.bid}</b></td>
			<td><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=block&amp;bid={ROW.bid}">{ROW.title}{ROW.numnews}</a></td>
			<td align="center">
			<select id="id_adddefault_{ROW.bid}" onchange="nv_chang_block_cat('{ROW.bid}','adddefault');">
				<!-- BEGIN: adddefault -->
				<option value="{ADDDEFAULT.key}"{ADDDEFAULT.selected}>{ADDDEFAULT.title}</option>
				<!-- END: adddefault -->
			</select></td>
			<td align="center"><em class="icon-edit icon-large">&nbsp;</em><a href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}&amp;bid={ROW.bid}#edit">{GLANG.edit}</a> &nbsp;-&nbsp;<em class="icon-trash icon-large">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_block_cat({ROW.bid})">{GLANG.delete}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->