<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );

$select_options = array();
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", array( $global_config['check_theme'], $global_config['check_theme_mobile'] ) );

foreach( $theme_array as $themes_i )
{
	$select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=blocks&amp;selectthemes=" . $themes_i] = $themes_i;
}

$selectthemes_old = $nv_Request->get_string( 'selectthemes', 'cookie', $global_config['site_theme'] );
$selectthemes = $nv_Request->get_string( 'selectthemes', 'get', $selectthemes_old );
if( ! in_array( $selectthemes, $theme_array ) )
{
	$selectthemes = $global_config['site_theme'];
}
if( $selectthemes_old != $selectthemes )
{
	$nv_Request->set_Cookie( 'selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME );
}

$page_title = $lang_module['blocks'] . ':' . $selectthemes;
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='8'>";
$contents .= $lang_module['block_select_module'] . " <select name='module'>";
$contents .= "<option value=''>" . $lang_module['block_select_module'] . "</option>";
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
	$contents .= "<option value='" . $m_title . "'>" . $m_custom_title . "</option>";
}
$contents .= "</select>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_sort'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_pos'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_file'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_active'] . "</td>\n";
$contents .= "<td>" . $lang_module['block_func_list'] . "</td>\n";
$contents .= "<td>" . $lang_module['functions'] . "</td>\n";
$contents .= "<td></td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$a = 0;

#load position file
$xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' );
$content = $xml->xpath( 'positions' ); //array
$positions = $content[0]->position; //object

$blocks_positions = array();
$result = $db->sql_query( "SELECT `position`, COUNT(*) FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $selectthemes . "' GROUP BY `position`" );
while( list( $position, $numposition ) = $db->sql_fetchrow( $result ) )
{
	$blocks_positions[$position] = $numposition;
}

$result = $db->sql_query( "SELECT * FROM `" . NV_BLOCKS_TABLE . "_groups` WHERE theme='" . $selectthemes . "' ORDER BY `position` ASC, `weight` ASC" );
while( $row = $db->sql_fetchrow( $result ) )
{
	$class = ( $a % 2 ) ? " class=\"second\"" : "";
	$contents .= "<tbody" . $class . ">\n";
	$contents .= "<tr>\n";
	$contents .= "<td>";
	$contents .= '<select class="order" title="' . $row['bid'] . '">';
	$numposition = $blocks_positions[$row['position']];
	for( $i = 1; $i <= $numposition; ++$i )
	{
		$sel = ( $row['weight'] == $i ) ? ' selected="selected"' : '';
		$contents .= '<option value="' . $i . '" ' . $sel . '>' . $i . '</option>';
	}
	$contents .= '</select>';
	$contents .= "</td>\n";

	$contents .= "<td>";
	$contents .= "<select name=\"listpos\" title='" . $row['bid'] . "'>\n";

	for( $i = 0, $count = sizeof( $positions ); $i < $count; ++$i )
	{
		$sel = ( $row['position'] == $positions[$i]->tag ) ? ' selected="selected"' : '';
		$contents .= "<option value=\"" . $positions[$i]->tag . "\" " . $sel . "> " . $positions[$i]->name . '</option>';
	}
	$contents .= "</select>";
	$contents .= "</td>\n";
	$contents .= "<td>" . $row['title'] . "</td>\n";
	$contents .= "<td>" . $row['module'] . " " . $row['file_name'] . "</td>\n";
	$contents .= "<td>" . ( $row['active'] ? $lang_global['yes'] : $lang_global['no'] ) . "</td>\n";
	$contents .= "<td>";
	if( $row['all_func'] == 1 )
	{
		$contents .= $lang_module['add_block_all_module'];
	}
	else
	{
		$result_func = $db->sql_query( "SELECT a.func_id, a.in_module, a.func_custom_name FROM `" . NV_MODFUNCS_TABLE . "` AS a INNER JOIN `" . NV_BLOCKS_TABLE . "_weight` AS b ON a.func_id=b.func_id WHERE b.bid=" . $row['bid'] . "" );
		while( list( $funcid_inlist, $func_inmodule, $funcname_inlist ) = $db->sql_fetchrow( $result_func ) )
		{
			$contents .= '<a href="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=blocks_func&amp;func=' . $funcid_inlist . '&amp;module=' . $func_inmodule . '"><span style="font-weight:bold">' . $func_inmodule . '</span>: ' . $funcname_inlist . '</a><br />';
		}
	}
	$contents .= "</td>\n";
	$contents .= "<td align=\"center\"><span class=\"edit_icon\"><a class=\"block_content\" title=\"" . $row['bid'] . "\" href=\"javascript:void(0);\">" . $lang_global['edit'] . "</a></span>\n";
	$contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a class=\"delete\" title=\"" . $row['bid'] . "\" href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span></td>\n";
	$contents .= "<td><input type='checkbox' name='idlist' value='" . $row['bid'] . "'/></td>\n";
	$contents .= "</tr>\n";
	$contents .= "</tbody>\n";
	++$a;
}
$contents .= "<tbody>\n";
$contents .= "<tr align=\"right\" class=\"tfoot_box\"><td colspan='8'>
<span class=\"edit_icon\"><a class=\"block_weight\" href=\"javascript:void(0);\">" . $lang_module['block_weight'] . "</a></span>&nbsp;&nbsp;&nbsp;&nbsp;	
<span class=\"add_icon\"><a class=\"block_content\" href=\"javascript:void(0);\">" . $lang_module['block_add'] . "</a></span>&nbsp;&nbsp;&nbsp;&nbsp;
<span class=\"delete_icon\"><a class=\"delete_group\" href=\"javascript:void(0);\">" . $lang_global['delete'] . "</a></span>
<span style='width:100px;display:inline-block'>&nbsp;</span>
<span>
<a name=\"checkall\" id=\"checkall\" href=\"javascript:void(0);\">" . $lang_module['block_checkall'] . "</a>&nbsp;&nbsp;
<a name=\"uncheckall\" id=\"uncheckall\" href=\"javascript:void(0);\">" . $lang_module['block_uncheckall'] . "</a>
</span>
</td></tr>";
$contents .= "</tbody>\n";
$contents .= "</table>\n";
$contents .= '<script type="text/javascript">
				//<![CDATA[
				$(function(){
					$("a.block_content").click(function(){
						var bid = parseInt($(this).attr("title"));
						Shadowbox.open(
					      {
					         content : "<iframe src=\"' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=block_content&bid="+bid+"&blockredirect=' . nv_base64_encode( $client_info['selfurl'] ) . '\" border=\"1\" frameborder=\"0\" style=\"width:780px;height:450px\"></iframe>",
					         player : "html",
					         height : 450,
					         width : 780
					      }
					      );
				    });
				     
					$("select.order").change(function(){
						$("select.order").attr({"disabled":""});
						var order = $(this).val();
						var bid = $(this).attr("title");
						$.ajax({	
							type: "POST",
							url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_order_group",
							data: "order="+order+"&bid="+bid,
							success: function(data){
								window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
							}
						});
					});
					
					$("select[name=module]").change(function(){
						var module = $(this).val();
						window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_func&module="+module;
					});
					
					$("a.delete").click(function(){
						var bid = parseInt($(this).attr("title"));
						if (bid > 0 && confirm(" ' . $lang_module['block_delete_per_confirm'] . '")){
							$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=blocks_del", "bid="+bid, function(theResponse){
								alert(theResponse);
						        window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
							});
						}			
					});
				
					$("a.block_weight").click(function(){
						if (confirm(" ' . $lang_module['block_weight_confirm'] . '")){
							$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=blocks_reset_order", "checkss=' . md5( $selectthemes . $global_config['sitekey'] . session_id() ) . '", function(theResponse){
								alert(theResponse);
						        window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
							});
						}			
					});
					
					
					$("a.delete_group").click(function(){
							var list = [];
					        $("input[name=idlist]:checked").each(function(){
					        	list.push($(this).val());
					        });
					        if (list.length<1){
						        alert(" ' . $lang_module['block_error_noblock'] . '");
						        return false;
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
						var bid = $(this).attr("title");
				        $.ajax({        
					        type: "POST",
					        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks_change_pos",
						    data:"bid="+bid+"&pos="+pos,
					        success: function(data){
					          	alert(data);  
					            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks";
					        }
				        }); 
					});
				});
				//]]>
				</script>';

if( ! defined( 'SHADOWBOX' ) )
{
	$my_head = "<link type=\"text/css\" rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
	$my_head .= "<script type=\"text/javascript\">Shadowbox.init();</script>";
	define( 'SHADOWBOX', true );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>