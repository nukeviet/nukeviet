<!-- BEGIN: main -->
<div id="module_show_list">
	<!-- BEGIN: data -->
	<table class="tab1">
		<thead>
			<tr>
				<td style="width:20px;"></td>
				<td>{LANG.name}</td>
				<td style="width:80px;"></td>
			</tr>
		</thead>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
		<tr>
			<td><input type="checkbox" name="newsid" value="{ROW.id}"/></td>
			<td align="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
			<td align="center">{ROW.delete}</td>
		</tr>
		</tbody>
		<!-- END: loop -->
		<tfoot>
			<tr>
				<td colspan="3">
					<span>
						<a name="checkall" id="checkall" href="javascript:void(0);">{LANG.comment_checkall}</a>&nbsp;&nbsp;
						<a name="uncheckall" id="uncheckall" href="javascript:void(0);">{LANG.comment_uncheckall}</a>&nbsp;&nbsp;
					</span>
					<span style="width:100px;display:inline-block">&nbsp;</span>
					<span class="delete_icon">
						<a class="delete" href="{URL_DELETE}">{LANG.topic_del}</a>
					</span>
				</td>
			</tr>
		</tfoot>
	</table>
	<!-- END: data -->
	<!-- BEGIN: empty -->
	<div class="quote" style="width:98%">
		<blockquote><span>{LANG.topic_nonews}</span></blockquote>
	</div>
	<!-- END: empty -->
</div>
<script type="text/javascript">
	$('#checkall').click(function(){
		$('input:checkbox').each(function(){
			$(this).attr('checked','checked');
		});
	});
	$('#uncheckall').click(function(){
		$('input:checkbox').each(function(){
			$(this).removeAttr('checked');
		});
	});
	$('a.delete').click(function(){
        var list = [];
        $('input[name=newsid]:checked').each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert('{LANG.topic_nocheck}');
	        return false;
        }
        if (confirm('{LANG.topic_delete_confirm}')){
	        $.ajax({        
		        type: 'POST',
		        url: 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicdelnews',
		        data:'list=' + list,
		        success: function(data){  
		            alert(data);
		            window.location='index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=topicsnews&topicid={TOPICID}';
		        }
	        });  
        }
		return false;
	});
</script>
<!-- END: main -->