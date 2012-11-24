<!-- BEGIN: main -->
<div style="height:27px;margin-top:3px;position:absolute;right:10px;text-align:right;">
	<a class="button2" href="{CONTENTS.edit.0}"><span><span>{CONTENTS.edit.1}</span></span></a>
	<!-- BEGIN: act --><a class="button2" href="javascript:void(0);" onclick="{CONTENTS.act.0}"><span><span>{CONTENTS.act.1}</span></span></a><!-- END: act -->
</div>
<table summary="{CONTENTS.caption}" class="tab1">
	<caption>{CONTENTS.caption}</caption>
	<col style="width:50%;white-space:nowrap" />
	<!-- BEGIN: loop1 -->
	<tbody{ROW1.class}>
		<tr>
			<td>{ROW1.0}:</td>
			<td>{ROW1.1}</td>
		</tr>
	</tbody>
	<!-- END: loop1 -->
</table>
<table summary="{CONTENTS.stat.0}" class="tab1">
	<caption>{CONTENTS.stat.0}</caption>
	<tbody class="second">
		<tr>
			<td>
				{CONTENTS.stat.1}:
				<select name="{CONTENTS.stat.2}" id="{CONTENTS.stat.2}">
					<!-- BEGIN: stat1 -->
					<option value="{K}">{V}</option>
					<!-- END: stat1 -->
				</select>
				<select name="{CONTENTS.stat.4}" id="{CONTENTS.stat.4}">
					<!-- BEGIN: stat2 -->
					<option value="{K}">{V}</option>
					<!-- END: stat2 -->
				</select>
				<input type="button" value="{CONTENTS.stat.6}" id="{CONTENTS.stat.7}" onclick="{CONTENTS.stat.8}" />
			</td>
		</tr>
	</tbody>
</table>
<div id="{CONTENTS.containerid}"></div>
<script type="text/javascript">
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
					window.location='index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=banner_list';
				}
			});
		}
	});
});
</script>
<!-- END: main -->