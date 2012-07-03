<!-- BEGIN: main -->
<!-- BEGIN: cat_title -->
<div style="background:#eee;padding:10px">{CAT_TITLE}</div>
<!-- END: cat_title -->
<!-- BEGIN: data -->
<table class="tab1">
	<col style="white-space: nowrap;" />
	<col style="white-space: nowrap;" />
	<col style="white-space: nowrap;" />
	<col style="white-space: nowrap;" />
	<col style="white-space: nowrap;" />
	<col style="white-space: nowrap;" />
	<thead>
		<tr>
			<td align="center">{LANG.weight}</td>
			<td>{LANG.name}</td>
			<td align="center">{LANG.inhome}</td>
			<td>{LANG.viewcat_page}</td>
			<td align="center">{LANG.numlinks}</td>
			<td></td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center">
				<!-- BEGIN: stt -->{STT}<!-- END: stt -->
				<!-- BEGIN: weight -->
				<select id="id_weight_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','weight');">
					<!-- BEGIN: loop -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: weight -->
			</td>
			<td nowrap="nowrap"><a href="{ROW.link}"><strong>{ROW.title}</strong> <!-- BEGIN: numsubcat --><span style="color:#FF0101;">({NUMSUBCAT})</span><!-- END: numsubcat --></a></td>
			<td align="center">
				<!-- BEGIN: disabled_inhome -->{INHOME}<!-- END: disabled_inhome -->
				<!-- BEGIN: inhome -->
				<select id="id_inhome_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','inhome');">
					<!-- BEGIN: loop -->
					<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: inhome -->
			</td>
			<td align="left">
				<!-- BEGIN: disabled_viewcat -->{VIEWCAT}<!-- END: disabled_viewcat -->
				<!-- BEGIN: viewcat -->
				<select id="id_viewcat_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','viewcat');">
					<!-- BEGIN: loop -->
					<option value="{VIEWCAT.key}"{VIEWCAT.selected}>{VIEWCAT.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: viewcat -->
			</td>
			<td align="center">
				<!-- BEGIN: title_numlinks -->{NUMLINKS}<!-- END: title_numlinks -->
				<!-- BEGIN: numlinks -->
				<select id="id_numlinks_{ROW.catid}" onchange="nv_chang_cat('{ROW.catid}','numlinks');">
					<!-- BEGIN: loop -->
					<option value="{NUMLINKS.key}"{NUMLINKS.selected}>{NUMLINKS.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: numlinks -->
			</td>
			<td nowrap="nowrap" align="center">{ROW.adminfuncs}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: data -->
<!-- END: main -->