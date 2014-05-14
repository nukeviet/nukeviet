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
			<col class="w200">
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
				<td>{ROW.title}</td>
				<td><a href="{ROW.pid.0}">{ROW.pid.1}</a></td>
				<!-- BEGIN: t1 -->
				<td><a href="{ROW.clid.0}">{ROW.clid.1}</a></td>
				<!-- END: t1 -->
				<!-- BEGIN: t2 -->
				<td>&nbsp;</td>
				<!-- END: t2 -->
				<td>{ROW.publ_date}</td>
				<td>{ROW.exp_date}</td>
				<td class="text-center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
				<td>
					<em class="fa fa-search fa-lg">&nbsp;</em> <a href="{ROW.view}">{CONTENTS.view}</a> &nbsp; 
					<em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.edit}">{CONTENTS.edit}</a> &nbsp; 
					<em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{ROW.delfile}" id="delete_banners">{CONTENTS.del}</a>
				</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('a[id=delete_banners]').click(function(event) {
			event.preventDefault();
			if (confirm('{LANG.file_del_confirm}')) {
				var href = $(this).attr('href') + "&nocache=" + new Date().getTime();
				$.ajax({
					type : 'POST',
					url : href,
					data : '',
					success : function(data) {
						alert(data);
						window.location = 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banners_list';
					}
				});
			}
		});
	});
	//]]>
</script>
<!-- END: main -->