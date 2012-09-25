<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td></td>
			<td style="width:60px;">ID</td>
			<td>{LANG.comment_content}</td>
			<td>{LANG.comment_topic}</td>
			<td style="width:150px;">{LANG.comment_email}</td>
			<td>{LANG.comment_status}</td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="6">
				<span>
					<a name="checkall" id="checkall" href="javascript:void(0);">{LANG.comment_checkall}</a>
					&nbsp;&nbsp;<a name="uncheckall" id="uncheckall" href="javascript:void(0);">{LANG.comment_uncheckall}</a>&nbsp;&nbsp;
				</span>
				<span style="width:100px;display:inline-block">&nbsp;</span>
				<span class="edit_icon">
					<a class="disable" href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment">{LANG.comment_disable}</a> 
				</span>
				 - 
				<span class="add_icon">
					<a class="enable" href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment">{LANG.comment_enable}</a> 
				</span>
				 - 
				<span class="delete_icon">
					<a class="delete" href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del_comment">{LANG.comment_delete}</a>
				</span>
			</td>
		</tr>
	</tfoot>
	<!-- BEGIN: loop -->
	<tbody{ROW.class}>
		<tr>
			<td align="center"><input name="commentid" type="checkbox" value="{ROW.cid}"/></td>
			<td align="center">{ROW.cid}</td>
			<td>{ROW.content}</td>
			<td>{ROW.title}</td>
			<td>{ROW.email}</td>
			<td align="center">{ROW.status}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: generate_page -->
<table class="tab1">
	<tr><td>{GENERATE_PAGE}</td></tr>
</table>
<!-- END: generate_page -->
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
	$('a.enable').click(function(){
        var list = [];
        $('input[name=commentid]:checked').each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert('{LANG.comment_nocheck}');
	        return false;
        }	
        $.ajax({        
	        type: 'POST',
	        url: 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment',
	        data:'list='+list+'&active=1',
	        success: function(data){  
	            alert(data);
	            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment';
	        }
        });  
		return false;
	});
	$('a.disable').click(function(){
        var list = [];
        $('input[name=commentid]:checked').each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert('{LANG.comment_nocheck}');
	        return false;
        }	
        $.ajax({        
	        type: 'POST',
	        url: 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment',
	        data:'list='+list+'&active=0',
	        success: function(data){  
	            alert(data);
	            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment';
	        }
        });  
		return false;
	});
	$('a.delete').click(function(){
        var list = [];
        $('input[name=commentid]:checked').each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert('{LANG.comment_nocheck}');
	        return false;
        }
        if (confirm('{LANG.comment_delete_confirm}')){	
	        $.ajax({        
		        type: 'POST',
		        url: 'index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del_comment',
		        data:'list='+list,
		        success: function(data){  
		            alert(data);
		            window.location='index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment';
		        }
	        });  
        }
		return false;
	});
</script>
<!-- END: main -->