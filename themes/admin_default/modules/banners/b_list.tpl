<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<caption><em class="fa fa-file-text-o">&nbsp;</em>{CONTENTS.caption}</caption>
		<!-- BEGIN: nv_banner_weight -->
		<col style="white-space:nowrap" />
		<!-- END: nv_banner_weight -->
		<colgroup>
			<col span="5">
			<col class="w100">
			<col class="w150">
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
				<!-- BEGIN: nv_banner_weight -->
				<td>{ROW.weight}</td>
				<!-- END: nv_banner_weight -->
				<td><a href="{ROW.view}">{ROW.title}</a></td>
				<td><a href="{ROW.pid.0}">{ROW.pid.1}</a></td>
				<td>
                    <!-- BEGIN: user -->
                    <a href="{USER.link}">{USER.username}</a>
                    <!-- END: user -->
                </td>
				<td>{ROW.publ_date}</td>
				<td>{ROW.exp_date}</td>
				<td class="text-center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
				<td>
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit}">{CONTENTS.edit}</a> &nbsp;
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="#" id="delete_banners" onclick="nv_delete_banner();">{CONTENTS.del}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
function nv_delete_banner() {
	var r = confirm('{LANG.file_del_confirm}');
	if (r == true) {
		var href = '{ROW.delfile}' + "&nocache=" + new Date().getTime();
		$.ajax({
			type : 'POST',
			url : href,
			data : '',
			success : function(data) {						
				alert(data);
				location.reload();
				//window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banners_list';
			}
		});
	}	
	else {
		return false;
	}
	return;
}
</script>
<!-- END: main -->