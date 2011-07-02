<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr align="center">
			<td></td>
			<td>{LANG.comment_email}</td>
			<td>{LANG.comment_content}</td>
			<td>{LANG.comment_topic}</td>
			<td>{LANG.comment_status}</td>
			<td style="width:100px;">{LANG.comment_funcs}</td>
		</tr>
	</thead>
	<!-- BEGIN: loop -->
    <tbody{ROW.class}>
		<tr>
			<td align="center"><input name="commentid" type="checkbox" value="{ROW.cid}"/></td>
			<td>{ROW.email}</td>
			<td>{ROW.content}</td>
			<td align="left"><a target="_blank" href="{ROW.link}">{ROW.title}</a></td>
			<td align="center">{ROW.status}</td>
			<td align="center">
				<span class="edit_icon">
					<a class="edit" href="{ROW.linkedit}">{LANG.comment_edit}</a>
				</span>
				 - 	
				<span class="delete_icon">
					<a class="deleteone" href="{ROW.linkdelete}">{LANG.comment_delete}</a>
				</span>
			</td>
		</tr>
    </tbody>
	<!-- END: loop -->
	<tbody>
	<tr class="tfoot_box">
	<td colspan="3">
		<span>
		<a name="checkall" id="checkall" href="javascript:void(0);">{LANG.comment_checkall}</a>
		&nbsp;&nbsp;<a name="uncheckall" id="uncheckall" href="javascript:void(0);">{LANG.comment_uncheckall}</a>&nbsp;&nbsp;
		</span><span style="width:100px;display:inline-block">&nbsp;</span>
		<span class="edit_icon">
			<a class="disable" href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=active_comment">{LANG.comment_disable}</a> 
		</span>
		 - 
		<span class="add_icon">
			<a class="enable" href="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=active_comment">{LANG.comment_enable}</a> 
		</span>
		 - 
		<span class="delete_icon">
			<a class="delete" href="{NV_BASE_ADMINURL}index.php?" . NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}=del_comment">{LANG.comment_delete}</a>
		</span>
	</td>
	<td colspan="3" align="center"><!-- BEGIN: generate_page -->{GENERATE_PAGE}<!-- END: generate_page --></td>
	</tr>
	</tbody>
</table>
<script type="text/javascript">
//<![CDATA[
	$("#checkall").click(function(){
		$("input:checkbox").each(function(){
			$(this).attr("checked","checked");
		});
	});
	$("#uncheckall").click(function(){
		$("input:checkbox").each(function(){
			$(this).removeAttr("checked");
		});
	});
	$("a.enable").click(function(){
        var list = [];
        $("input[name=commentid]:checked").each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert("{LANG.comment_nocheck}");
	        return false;
        }	
        $.ajax({        
	        type: "POST",
	        url: "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment",
	        data:"list="+list+"&active=1",
	        success: function(data){  
	            alert(data);
	            window.location="index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
	        }
        });  
		return false;
	});
	$("a.disable").click(function(){
        var list = [];
        $("input[name=commentid]:checked").each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert("{LANG.comment_nocheck}");
	        return false;
        }	
        $.ajax({        
	        type: "POST",
	        url: "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=active_comment",
	        data:"list="+list+"&active=0",
	        success: function(data){  
	           alert(data);
	           window.location="index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
	        }
        });  
		return false;
	});
	$("a.delete").click(function(){
        var list = [];
        $("input[name=commentid]:checked").each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert("{LANG.comment_nocheck}");
	        return false;
        }
        if (confirm("{LANG.comment_delete_confirm}")){	
	        $.ajax({        
		        type: "POST",
		        url: "index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del_comment",
		        data:"list="+list,
		        success: function(data){  
		            alert(data);
		            window.location="index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
		        }
	        });  
        }
		return false;
	});
	$("a.deleteone").click(function(){
        if (confirm("{LANG.comment_delete_confirm}")){
        	var url = $(this).attr("href");	
	        $.ajax({        
		        type: "POST",
		        url: url,
		        data:"",
		        success: function(data){  
		            alert(data);
		            window.location="index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=comment";
		        }
	        });  
        }
		return false;
	});
//]]>
</script>
<!-- END: main -->