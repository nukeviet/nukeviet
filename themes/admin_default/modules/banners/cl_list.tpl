<!-- BEGIN: main -->
<table class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<colgroup>
		<col span="4">
		<col class="w50">
		<col class="w250">
	</colgroup>
	<thead>
		<tr>
			<!-- BEGIN: thead -->
			<td>{THEAD}</td>
			<!-- END: thead -->
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: loop -->
		<tr>
			<td>{ROW.login}</td>
			<td>{ROW.full_name}</td>
			<td>{ROW.email}</td>
			<td>{ROW.reg_time}</td>
			<td class="center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
			<td>
				<i class="icon-search icon-large"></i> <a href="{ROW.view}">{CONTENTS.view}</a> &nbsp; 
				<i class="icon-edit icon-large"></i> <a href="{ROW.edit}">{CONTENTS.edit}</a> &nbsp; 
				<i class="icon-plus icon-large"></i> <a href="{ROW.add}">{CONTENTS.add}</a> &nbsp; 
				<i class="icon-trash icon-large"></i> <a href="javascript:void(0);" onclick="{ROW.del}">{CONTENTS.del}</a></td>
		</tr>
		<!-- END: loop -->
	</tbody>
</table>
<!-- END: main -->