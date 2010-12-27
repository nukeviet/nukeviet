<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$select_options = array();
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );

foreach ( $theme_array as $themes_i )
{
    $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=blocks&selectthemes=" . $themes_i] = $themes_i;
}

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_name'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );
if ( ! in_array( $selectthemes, $theme_array ) )
{
    $selectthemes = $global_config['site_name'];
}
if ( $selectthemes_old != $selectthemes )
{
    $nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

$page_title = $lang_module['blocks'];
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td></td>\n";
$contents .= "<td>" . $lang_module['block_sort'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_pos'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_func'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_file'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_group_block'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_active'] . "</td>\n";
$contents .= "<td></td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;
$query = "SELECT * FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE (`blang`='" . NV_LANG_DATA . "' OR `blang`='') ORDER BY `title` ASC";
$result = $db->sql_query( $query );
$banners_pl_list = array();
while ( $row_bpn = $db->sql_fetchrow( $result ) )
{
    $banners_pl_list[$row_bpn['id']] = $row_bpn;
}

#load position file
$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini' );
$content = $xml->xpath( 'positions' ); //array
$positions = $content[0]->position; //object


//$result = $db->sql_query ( "SELECT bid, groupbl, title, type, file_path, position, func_id, weight, exp_time, active FROM `" . NV_BLOCKS_TABLE . "` ORDER BY `groupbl`,`func_id`,`position`,`weight` ASC" );
$result = $db->sql_query( "SELECT bid, groupbl, title, type, file_path, position, func_id, weight, exp_time, active FROM `" . NV_BLOCKS_TABLE . "` GROUP BY `groupbl` ASC" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td><input type='checkbox' name='idlist' value='" . $row['bid'] . "'/></td>\n";
    $contents .= "<td>";
    $contents .= '<select class="order" name="' . $row['bid'] . '">';
    $numsameblock = $db->sql_numrows( $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE func_id='" . $row['func_id'] . "' AND position='" . $row['position'] . "'" ) );
    for ( $i = 1; $i <= $numsameblock; $i ++ )
    {
        $sel = ( $row['weight'] == $i ) ? ' selected' : '';
        $contents .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
    }
    $contents .= '</select>';
    $contents .= "</td>\n";
    $contents .= "<td>";
    $contents .= "<select name=\"listpos\" class='" . $row['func_id'] . "' id='" . $row['bid'] . "'>\n";
    for ( $i = 0; $i < count( $positions ); $i ++ )
    {
        $sel = ( $row['position'] == $positions[$i]->tag ) ? ' selected' : '';
        $contents .= "<option value=\"" . $positions[$i]->tag . "\" " . $sel . "> " . $positions[$i]->name . '</option>';
    }
    $contents .= "</select>";
    $contents .= "</td>\n";
    list( $func_area, $in_module ) = $db->sql_fetchrow( $db->sql_query( "SELECT `func_custom_name` , `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE func_id='" . $row['func_id'] . "'" ) );
    
    $contents .= "<td>" . $in_module . ':';
    $contents .= "<select name='func_change' class='" . $row['position'] . "' id='" . $row['bid'] . "'>";
    $funcresult = $db->sql_query( "SELECT `func_id`,`func_custom_name` FROM `" . NV_MODFUNCS_TABLE . "` WHERE in_module='" . $in_module . "' AND show_func='1'" );
    while ( list( $func_id, $func_custom_name ) = $db->sql_fetchrow( $funcresult ) )
    {
        $sel = ( $func_id == $row['func_id'] ) ? ' selected' : '';
        $contents .= '<option value="' . $func_id . '" ' . $sel . '>' . $func_custom_name . '</option>';
    }
    $contents .= "</select>\n";
    $contents .= "</td>\n";
    $contents .= "<td>" . $row['title'] . "</td>\n";
    $contents .= "<td>";
    if ( $row['type'] == 'html' )
    {
        $contents .= $lang_module['block_typehtml'];
    }
    elseif ( $row['type'] == 'banner' )
    {
        $idbn = intval( $row['file_path'] );
        $contents .= "<strong>banner:</strong> " . $banners_pl_list[$idbn]['title'];
    }
    else
    {
        $contents .= $row['file_path'];
    }
    $contents .= "</td>\n";
    $contents .= "<td>" . $row['groupbl'] . "</td>\n";
    $contents .= "<td>" . ( $row['active'] ? $lang_global['yes'] : $lang_global['no'] ) . "</td>\n";
    $contents .= "<td align=\"center\" width='50px'><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add&amp;bid=" . $row['bid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
    $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a class='delete' rel='" . $row['bid'] . "' href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $a ++;
}
$contents .= "<tfoot><tr><td colspan='9'>
<span>
<a name=\"checkall\" id=\"checkall\" href=\"javascript:void(0);\">" . $lang_module['block_checkall'] . "</a>
&nbsp;&nbsp;<a name=\"uncheckall\" id=\"uncheckall\" href=\"javascript:void(0);\">" . $lang_module['block_uncheckall'] . "</a>&nbsp;&nbsp;
</span><span style='width:100px;display:inline-block'>&nbsp;</span>
<span class=\"delete_icon\"><a class='delete' href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span>
<span class=\"add_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add\">" . $lang_global['add'] . "</a></span>	
</td></tr></tfoot>";

$contents .= "</table>\n";
$contents .= '
<script type="text/javascript">
$(function(){
	$("select.order").change(function(){
		$("select.order").attr({"disabled":""});
		var order = $(this).val();
		var id = $(this).attr("name");
		$.ajax({	
			type: "POST",
			url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_order",
			data: "order="+order+"&bid="+id,
			success: function(data){				
				window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
			}
		});
	});
	$("a.delete").click(function(){
		var rel = $(this).attr("rel");
		if (rel!=0){
			list = rel;
		} else {
	        var list = [];
	        $("input[name=idlist]:checked").each(function(){
	        	list.push($(this).val());
	        });
	        if (list.length<1){
		        alert(" ' . $lang_module['block_error_noblock'] . '");
		        return false;
	        }
        }
        if (confirm(" ' . $lang_module['block_delete_confirm'] . '")){	
	        $.ajax({        
		        type: "POST",
		        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_del",
		        data:"list="+list,
		        success: function(data){  
		            alert(data);
		            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
		        }
	        });  
        }
		return false;
	});
	$("#checkall").click(function(){
		$("input[name=idlist]:checkbox").each(function(){
			$(this).attr("checked","checked");
		});
	});
	$("#uncheckall").click(function(){
		$("input[name=idlist]:checkbox").each(function(){
			$(this).removeAttr("checked");
		});
	});
	$("select[name=listpos]").change(function(){
		var pos = $(this).val();
		var id = $(this).attr("id");
		var func_id = $(this).attr("class");
        $.ajax({        
	        type: "POST",
	        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_pos",
	        data:"pos="+pos+"&bid="+id+"&func_id="+func_id,
	        success: function(data){  
	            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
	        }
        }); 
	});
	$("select[name=func_change]").change(function(){
		var newfunc = $(this).val();
		var blockid = $(this).attr("id");
		var position = $(this).attr("class");
        $.ajax({        
	        type: "POST",
	        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_func",
	        data:"newfunc="+newfunc+"&blockid="+blockid+"&position="+position,
	        success: function(data){  
	            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
	        }
        }); 
	});
});
</script>
';
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>