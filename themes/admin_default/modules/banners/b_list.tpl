<!-- BEGIN: main -->
<table summary="{CONTENTS.caption}" class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<!-- BEGIN: nv_banner_weight --><col style="white-space:nowrap" /><!-- END: nv_banner_weight -->
	<col span="5" style="white-space:nowrap" />
	<col style="width:50px;white-space:nowrap" />
	<col style="width:200px;white-space:nowrap" />
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
			<!-- BEGIN: nv_banner_weight --><td>{ROW.weight}</td><!-- END: nv_banner_weight -->
			<td>{ROW.title}</td>
			<td><a href="{ROW.pid.0}">{ROW.pid.1}</a></td>
			<!-- BEGIN: t1 --><td><a href="{ROW.clid.0}">{ROW.clid.1}</a></td><!-- END: t1 -->
			<!-- BEGIN: t2 --><td></td><!-- END: t2 -->
			<td>{ROW.publ_date}</td>
			<td>{ROW.exp_date}</td>
			<td class="center"><input name="{ROW.act.0}" id="{ROW.act.0}" type="checkbox" value="1" onclick="{ROW.act.2}"{ROW.checked}/></td>
			<td>
				<span class="search_icon"><a href="{ROW.view}">{CONTENTS.view}</a></span> |
				<span class="edit_icon"><a href="{ROW.edit}">{CONTENTS.edit}</a></span> |
				<span class="delete_icon"><a class="delfile" href="{ROW.delfile}">{CONTENTS.del}</a></span>
			</td>
		</tr>
		</tbody>
	<!-- END: loop -->
</table>
<script type="text/javascript">
//<![CDATA[
$(function(){
	$('a[class=delfile]').click(function(event){
		event.preventDefault();
		if (confirm('{LANG.file_del_confirm}')){
			var href= $(this).attr('href');
			$.ajax({	
				type: 'POST',
				url: href,
				data:'',
				success: function(data){
					alert(data);
					window.location='index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banners_list';
				}
			});
		}
	});
});
//]]>
</script>
<!-- END: main -->