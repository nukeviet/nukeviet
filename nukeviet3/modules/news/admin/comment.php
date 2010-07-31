<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['comment'];
$contents = "";
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr align=\"center\">\n";
$contents .= "<td></td>\n";
$contents .= "<td style=\"width:60px;\">" . $lang_module ['weight'] . "</td>\n";
$contents .= "<td>" . $lang_module ['comment_content'] . "</td>\n";
$contents .= "<td>" . $lang_module ['comment_topic'] . "</td>\n";
$contents .= "<td style=\"width:150px;\">" . $lang_module ['comment_email'] . "</td>\n";
$contents .= "<td>" . $lang_module ['comment_status'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$contents .= "<tfoot>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='6'>";
$contents .= "
		<span>
		<a name=\"checkall\" id=\"checkall\" href=\"javascript:void(0);\">" . $lang_module ['comment_checkall'] . "</a>
		&nbsp;&nbsp;<a name=\"uncheckall\" id=\"uncheckall\" href=\"javascript:void(0);\">" . $lang_module ['comment_uncheckall'] . "</a>&nbsp;&nbsp;
		</span><span style='width:100px;display:inline-block'>&nbsp;</span>
		<span class=\"edit_icon\">
			<a class='disable' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment\">" . $lang_module ['comment_disable'] . "</a> 
		</span>
		 - 
		<span class=\"add_icon\">
			<a class='enable' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment\">" . $lang_module ['comment_enable'] . "</a> 
		</span>
		 - 
		<span class=\"delete_icon\">
			<a class='delete' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_comment\">" . $lang_module ['comment_delete'] . "</a>
		</span>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tfoot>\n";
$sql = "SELECT a.cid, a.content, a.post_email, a.status, b.title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_rows` b ON a.id=b.id";
$result = $db->sql_query ( $sql );
$num = $db->sql_numrows ( $result );
while ( list ( $cid, $content, $email, $status, $title ) = $db->sql_fetchrow ( $result ) )
{
	$contents .= "<tbody>\n";
	$contents .= "<tr>\n";
	$contents .= "<td align=\"center\"><input name='commentid' type='checkbox' value='" . $cid . "'/></td>\n";
	$contents .= "<td align=\"center\">" . $cid . "</td>\n";
	$contents .= "<td>" . $content . "</td>\n";
	$contents .= "<td>" . $title . "</td>\n";
	$contents .= "<td>" . $email . "</td>\n";
	$status = ($status == 1) ? $lang_module ['comment_enable'] : $lang_module ['comment_disable'];
	$contents .= "<td align=\"center\">$status</td>\n";
	$contents .= "</tr>\n";
	$contents .= "</tbody>\n";
}
$contents .= "</table>\n";
$contents .= "
<script type='text/javascript'>
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
	        alert('" . $lang_module ['comment_nocheck'] . "');
	        return false;
        }	
        $.ajax({        
	        type: 'POST',
	        url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment',
	        data:'list='+list+'&active=1',
	        success: function(data){  
	            alert(data);
	            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment';
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
	        alert('" . $lang_module ['comment_nocheck'] . "');
	        return false;
        }	
        $.ajax({        
	        type: 'POST',
	        url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment',
	        data:'list='+list+'&active=0',
	        success: function(data){  
	            alert(data);
	            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment';
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
	        alert('" . $lang_module ['comment_nocheck'] . "');
	        return false;
        }
        if (confirm('" . $lang_module ['comment_delete_confirm'] . "')){	
	        $.ajax({        
		        type: 'POST',
		        url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_comment',
		        data:'list='+list,
		        success: function(data){  
		            alert(data);
		            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=comment';
		        }
	        });  
        }
		return false;
	});
</script>
";
include (NV_ROOTDIR . "/includes/header.php");
echo nv_admin_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>