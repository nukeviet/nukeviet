<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['comment'];
$contents = "";
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr align=\"center\">\n";
$contents .= "<td></td>\n";
$contents .= "<td>" . $lang_module['comment_topic'] . "</td>\n";
$contents .= "<td>" . $lang_module['comment_content'] . "</td>\n";
$contents .= "<td>" . $lang_module['comment_email'] . "</td>\n";
$contents .= "<td>" . $lang_module['comment_status'] . "</td>\n";
$contents .= "<td style=\"width:100px;\">" . $lang_module['comment_funcs'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$contents .= "<tfoot>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='7'>";
$contents .= "
		<span>
		<a name=\"checkall\" id=\"checkall\" href=\"javascript:void(0);\">" . $lang_module['comment_checkall'] . "</a>
		&nbsp;&nbsp;<a name=\"uncheckall\" id=\"uncheckall\" href=\"javascript:void(0);\">" . $lang_module['comment_uncheckall'] . "</a>&nbsp;&nbsp;
		</span><span style='width:100px;display:inline-block'>&nbsp;</span>
		<span class=\"edit_icon\">
			<a class='disable' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment\">" . $lang_module['comment_disable'] . "</a> 
		</span>
		 - 
		<span class=\"add_icon\">
			<a class='enable' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=active_comment\">" . $lang_module['comment_enable'] . "</a> 
		</span>
		 - 
		<span class=\"delete_icon\">
			<a class='delete' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_comment\">" . $lang_module['comment_delete'] . "</a>
		</span>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tfoot>\n";

$global_array_cat = array();
$link_i = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=Other";
$global_array_cat[0] = array( 
    "catid" => 0, "parentid" => 0, "title" => "Other", "alias" => "Other", "link" => $link_i, "viewcat" => "viewcat_page_new", "subcatid" => 0, "numlinks" => 3, "description" => "", "keywords" => "" 
);

$sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, del_cache_time, description, keywords, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $del_cache_time_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "keywords" => $keywords_i 
    );
}

$sql = "SELECT a.cid, a.content, a.post_email, a.status, b.id, b.title, b.listcatid, b.alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_rows` b ON a.id=b.id";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$a = 0;
while ( list( $cid, $content, $email, $status, $id, $title, $listcatid, $alias ) = $db->sql_fetchrow( $result ) )
{
    $a ++;
    $catid_i = end( explode( ",", $listcatid ) );
    $link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $alias . "-" . $id;
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td align=\"center\"><input name='commentid' type='checkbox' value='" . $cid . "'/></td>\n";
    $contents .= "<td align=\"left\"><a target=\"_blank\" href=\"" . $link . "\">" . $title . "</a></td>\n";
    $contents .= "<td>" . $content . "</td>\n";
    $contents .= "<td>" . $email . "</td>\n";
    $status = ( $status == 1 ) ? $lang_module['comment_enable'] : $lang_module['comment_disable'];
    $contents .= "<td align=\"center\">$status</td>\n";
    $contents .= "<td align=\"center\">
		<span class=\"edit_icon\">
			<a class='edit' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=edit_comment&cid=" . $cid . "\">" . $lang_module['comment_edit'] . "</a>
		</span>
		 - 	
		<span class=\"delete_icon\">
			<a class='deleteone' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_comment&list=" . $cid . "\">" . $lang_module['comment_delete'] . "</a>
		</span></td>\n";
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
	        alert('" . $lang_module['comment_nocheck'] . "');
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
	        alert('" . $lang_module['comment_nocheck'] . "');
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
	        alert('" . $lang_module['comment_nocheck'] . "');
	        return false;
        }
        if (confirm('" . $lang_module['comment_delete_confirm'] . "')){	
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
	$('a.deleteone').click(function(){
        if (confirm('" . $lang_module['comment_delete_confirm'] . "')){
        	var url = $(this).attr('href');	
	        $.ajax({        
		        type: 'POST',
		        url: url,
		        data:'',
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
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>