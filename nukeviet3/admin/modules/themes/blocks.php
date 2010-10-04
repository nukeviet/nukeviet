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

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );
if ( ! in_array( $selectthemes, $theme_array ) )
{
    $selectthemes = $global_config['site_theme'];
}
if ( $selectthemes_old != $selectthemes )
{
    $nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

$page_title = $lang_module['blocks'] . ':' . $selectthemes;
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td></td>\n";
$contents .= "<td>" . $lang_module['block_select_module'] . ":</td>\n";
$contents .= "<td colspan='5'>";
$contents .= "<select name='module'>";
$contents .= "<option value=''>" . $lang_module['block_select_module'] . "</option>";
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
    $contents .= "<option value='" . $m_title . "'>" . $m_custom_title . "</option>";
}
$contents .= "</select>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td></td>\n";
$contents .= "<td>" . $lang_module['block_pos'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_file'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_active'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_func_list'] . "</td>\n";
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


$result = $db->sql_query( "SELECT bid, groupbl, title, type, file_path, position, all_func, func_id, weight, exp_time, active FROM `" . NV_BLOCKS_TABLE . "` WHERE theme='" . $selectthemes . "' GROUP BY `groupbl` ORDER BY `position` ASC" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td><input type='checkbox' name='idlist' value='" . $row['groupbl'] . "'/></td>\n";
    $contents .= "<td>";
    $contents .= "<select name=\"listpos\" id='" . $row['groupbl'] . "'>\n";
    for ( $i = 0; $i < count( $positions ); $i ++ )
    {
        $sel = ( $row['position'] == $positions[$i]->tag ) ? ' selected' : '';
        $contents .= "<option value=\"" . $positions[$i]->tag . "\" " . $sel . "> " . $positions[$i]->name . '</option>';
    }
    $contents .= "</select>";
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
    elseif ( $row['type'] == 'rss' )
    {
        $contents .= "<strong>Rss:</strong> " . $row['file_path'];
    }
    else
    {
        $contents .= $row['file_path'];
    }
    $contents .= "</td>\n";
    $contents .= "<td>" . ( $row['active'] ? $lang_global['yes'] : $lang_global['no'] ) . "</td>\n";
    $contents .= "<td>";
    if ( $row['all_func'] == 1 )
    {
        $contents .= $lang_module['add_block_all_module'];
    }
    else
    {
        $result_func = $db->sql_query( "SELECT a.func_id,b.in_module, b.func_custom_name FROM `" . NV_BLOCKS_TABLE . "` a INNER JOIN `" . NV_MODFUNCS_TABLE . "` b ON a.func_id=b.func_id WHERE groupbl=" . $row['groupbl'] . "" );
        while ( list( $funcid_inlist, $func_inmodule, $funcname_inlist ) = $db->sql_fetchrow( $result_func ) )
        {
            $contents .= '<a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_list&func=' . $funcid_inlist . '&module=' . $func_inmodule . '"><span style="font-weight:bold">' . $func_inmodule . '</span>: ' . $funcname_inlist . '</a><br />';
        }
    }
    $contents .= "</td>\n";
    $contents .= "<td align=\"center\" width='50px'><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add&amp;bid=" . $row['bid'] . "#edit\">" . $lang_global['edit'] . "</a></span>\n";
    $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a class='delete' rel='" . $row['groupbl'] . "' href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span></td>\n";
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
	$("select[name=module]").change(function(){
		var module = $(this).val();
		window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_list&module="+module;
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
		        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_del_group",
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
        $.ajax({        
	        type: "POST",
	        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_pos",
	        data:"pos="+pos+"&groupbl="+id,
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