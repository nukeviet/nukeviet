<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['topic_page'];
$set_active_op = "topics";

$topicid = $nv_Request->get_int( 'topicid', 'get' );
$global_array_cat = array();
$sql = "SELECT catid, parentid, title, alias, viewcat, subcatid, numlinks, description, keywords, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `order` ASC";
$result = $db->sql_query( $sql );
while ( list( $catid_i, $parentid_i, $title_i, $alias_i, $viewcat_i, $subcatid_i, $numlinks_i, $description_i, $keywords_i, $lev_i ) = $db->sql_fetchrow( $result ) )
{
    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
    $global_array_cat[$catid_i] = array( 
        "catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "keywords" => $keywords_i 
    );
}

$sql = "SELECT id, catid, alias, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE topicid='" . $topicid . "' ORDER BY `id` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$contents = "<div id=\"module_show_list\">";
if ( $num > 0 )
{
    $contents .= "<table class=\"tab1\" style='width:100%'>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:20px;'></td>\n";
    $contents .= "<td>" . $lang_module['name'] . "</td>\n";
    $contents .= "<td style=\"width:80px;\"></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $a = 0;
    $array_inhome = array( 
        $lang_global['no'], $lang_global['yes'] 
    );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $catid_i = $row['catid'];
        $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$catid_i]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
        
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td><input type='checkbox' name='newsid' value='" . $row['id'] . "'/></td>\n";
        $contents .= "<td align=\"left\"><a target=\"_blank\" href=\"" . $link . "\">" . $row['title'] . "</a></td>\n";
        $contents .= "<td align=\"center\">";
        $contents .= "     " . nv_link_edit_page( $row['id'] ) . "\n";
        $contents .= "     </td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
        ++$a;
    }
    $contents .= "<tfoot>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan='3'>";
    $contents .= "
		<span>
		<a name=\"checkall\" id=\"checkall\" href=\"javascript:void(0);\">" . $lang_module['comment_checkall'] . "</a>
		&nbsp;&nbsp;<a name=\"uncheckall\" id=\"uncheckall\" href=\"javascript:void(0);\">" . $lang_module['comment_uncheckall'] . "</a>&nbsp;&nbsp;
		</span><span style='width:100px;display:inline-block'>&nbsp;</span>
		<span class=\"delete_icon\">
			<a class='delete' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicdelnews\">" . $lang_module['topic_del'] . "</a>
		</span>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tfoot>\n";
    $contents .= "</table>\n";
}
else
{
    $contents .= "<div class=\"quote\" style=\"width:810px;\">\n";
    $contents .= "<blockquote><span>" . $lang_module['topic_nonews'] . "</span></blockquote>\n";
    $contents .= "</div>\n";
}
$db->sql_freeresult();
$contents .= "</div>";
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
	$('a.delete').click(function(){
        var list = [];
        $('input[name=newsid]:checked').each(function(){
        	list.push($(this).val());
        });
        if (list.length<1){
	        alert('" . $lang_module['topic_nocheck'] . "');
	        return false;
        }
        if (confirm('" . $lang_module['topic_delete_confirm'] . "')){	
	        $.ajax({        
		        type: 'POST',
		        url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicdelnews',
		        data:'list='+list,
		        success: function(data){  
		            alert(data);
		            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topicsnews&topicid=" . $topicid . "';
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