<!-- BEGIN: main -->
<table summary="{CONTENTS.caption}" class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<col span="3" style="white-space:nowrap" />
	<col style="width:50px;white-space:nowrap" />
	<col style="width:300px;white-space:nowrap" />
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td>{ROW.title}</td>
			<td>{ROW.blang}</td>
			<td>{ROW.size}</td>
			<td class="center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
			<td>
				<span class="search_icon"><a href="{ROW.view}">{CONTENTS.view}</a></span> |
				<span class="edit_icon"><a href="{ROW.edit}">{CONTENTS.edit}</a></span> |
				<span class="add_icon"><a href="{ROW.add}">{CONTENTS.add}</a></span> |
				<span class="delete_icon"><a href="javascript:void(0);" onclick="{ROW.del}">{CONTENTS.del}</a></span>
			</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- END: main -->