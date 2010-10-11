<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if (! defined ( 'NV_IS_FILE_ADMIN' ))
	die ( 'Stop!!!' );
$page_title = $lang_module ['addtotopics'];
$contents .= "<div id=\"add\">";
$id_array = array ();
$listid = $nv_Request->get_string ( 'listid', 'get,post', '' );
if ($nv_Request->isset_request ( 'topicsid', 'post' ))
{
	nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_topic', "listid ".$listid, $admin_info['userid'] );
	$topicsid = $nv_Request->get_int('topicsid','post');
	$listid = explode ( ',', $listid );
	foreach ( $listid as $value )
	{
		$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET topicid='$topicsid' WHERE id='$value'";
		$result = $db->sql_query ( $sql );
	}
	nv_del_moduleCache( $module_name );
	echo $lang_module['topic_update_success'];
} else
{
	if ($listid == "")
	{
		$sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `inhome`=1 ORDER BY `id` DESC LIMIT 0,20";
	} else
	{
		$id_array = array_map ( "intval", explode ( ",", $listid ) );
		$sql = "SELECT id, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` where `inhome`=1 AND `id` IN (" . implode ( ",", $id_array ) . ") ORDER BY `id` DESC";
	}
	$result = $db->sql_query ( $sql );
	if ($db->sql_numrows ( $result ))
	{
		$contents .= "<table class=\"tab1\">\n";
		$contents .= "<thead>\n";
		$contents .= "<tr>\n";
		$contents .= "<td></td>\n";
		$contents .= "<td>" . $lang_module ['name'] . "</td>\n";
		$contents .= "</tr>\n";
		$contents .= "</thead>\n";
		$a = 0;
		while ( list ( $id, $title ) = $db->sql_fetchrow ( $result ) )
		{
			$class = ($a % 2) ? " class=\"second\"" : "";
			$contents .= "<tbody" . $class . ">\n";
			$contents .= "<tr>\n";
			$contents .= "<td align=\"center\"><input type=\"checkbox\" value=\"" . $id . "\" name=\"idcheck\" " . (in_array ( $id, $id_array ) ? "checked" : "") . "></td>\n";
			$contents .= "<td>" . $title . "</td>\n";
			$contents .= "</tr>\n";
			$contents .= "</tbody>\n";
			$a ++;
		}
		$contents .= "<tfoot>\n";
		$contents .= "<tr align=\"left\">\n";
		$contents .= "<td align=\"center\" style='width:50px'><input name=\"checkall\" type=\"checkbox\"/></td>\n";
		$contents .= "<td>";
		$contents .= "<select name=\"topicsid\">\n";
		$result = $db->sql_query ( "SELECT topicid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_topics` ORDER BY `weight`" );
		while ( $row = $db->sql_fetchrow ( $result ) )
		{
			$contents .= "<option value='" . $row ['topicid'] . "'>" . $row ['title'] . "</option>";
		}
		$contents .= "</select>";
		$contents .= "<input name=\"update\" type=\"button\" value=\"" . $lang_module ['save'] . "\" />\n";
		$contents .= "</td>\n";
		$contents .= "</tr>\n";
		$contents .= "</tfoot>\n";
		$contents .= "</table>\n";
	}
	$contents .= "</div>";
	$contents .= "
<script type='text/javascript'>
$(function(){
	$('input[name=checkall]').toggle(function(){
		$('input:checkbox').each(function(){
			$(this).attr('checked','checked');
		});
	},function(){
		$('input:checkbox').each(function(){
			$(this).removeAttr('checked');
		});
	}
	);
	$('input[name=update]').click(function(){
        var listid = [];
        $('input[name=idcheck]:checked').each(function(){
        	listid.push($(this).val());
        });
        if (listid.length<1){
	        alert('" . $lang_module ['topic_nocheck'] . "');
	        return false;
        }
        var topic = $('select[name=topicsid]').val();
        $.ajax({        
	        type: 'POST',
	        url: 'index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=addtotopics',
	        data:'listid='+listid+'&topicsid='+topic,
	        success: function(data){  
	            alert(data);
	            window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=topics';
	        }
        });  
		return false;
	});
});	
</script>
";
	include (NV_ROOTDIR . "/includes/header.php");
	echo nv_admin_theme ( $contents );
	include (NV_ROOTDIR . "/includes/footer.php");
}
?>