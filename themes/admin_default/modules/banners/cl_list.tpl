<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
		<colgroup>
			<col span="4">
			<col class="w100">
			<col class="w250">
		</colgroup>
		<thead>
			<tr>
				<!-- BEGIN: thead -->
				<th>{THEAD}</th>
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
				<td class="text-center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
				<td>
					<em class="fa fa-search fa-lg">&nbsp;</em> <a href="{ROW.view}">{CONTENTS.view}</a> &nbsp; 
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit}">{CONTENTS.edit}</a> &nbsp; 
					<em class="fa fa-plus-circle fa-lg fa-lg">&nbsp;</em> <a href="{ROW.add}">{CONTENTS.add}</a> &nbsp; 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.del}">{CONTENTS.del}</a></td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: main -->