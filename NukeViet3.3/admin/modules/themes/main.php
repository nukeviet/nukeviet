<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */

if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$page_title = $lang_module['theme_manager'];
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>";
$contents .= "<tr>";
$contents .= "<td colspan='2'>" . $lang_module['theme_recent'] . "</td>";
$contents .= "</tr>";
$contents .= "</thead>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$theme_list = nv_scandir( NV_ROOTDIR . "/themes/", $global_config['check_theme'] );
$i = 1;
$number_theme = sizeof( $theme_list );
$errorconfig = array();
foreach ( $theme_list as $value )
{
    #load thumbnail image
    if ( ! @$xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $value . '/config.ini' ) )
    {
        $errorconfig[] = $value;
        continue;
    }
    //nv_info_die( $lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'] )
    $info = $xml->xpath( 'info' ); //array
    if ( $global_config['site_theme'] == $value )
    {
        $contents .= "<td style='padding-left:50px;width:50%;background-color:#FFDBB7'>";
    
    //$contents .= "<p style='color:red'>[" . $lang_module ['theme_created_current_use'] . "]</p>";
    }
    else
    {
        $contents .= "<td style='padding-left:50px;width:50%'>";
    }
    $contents .= "<p><b>" . $info[0]->name . "</b> " . $lang_module['theme_created_by'] . " <a href='" . $info[0]->website . "' title='" . $lang_module['theme_created_website'] . "' style='color:#3B5998' target='_blank'><b>" . $info[0]->author . "</b></a></p>";
    $contents .= "<p><img alt = '" . $info[0]->name . "' src='" . NV_BASE_SITEURL . "themes/" . $value . "/" . $info[0]->thumbnail . "' style='max-width:300px;max-height:200px'/></p>";
    $contents .= "<p style='font-size:13px;margin-top:10px;font-weight:bold'><a href='javascript:void(0);' class='activate' title='" . $value . "' style='color:#3B5998'>" . $lang_module['theme_created_activate'] . "</a> | <a href='javascript:void(0);' class='delete' title='" . $value . "' style='color:#3B5998'>" . $lang_module['theme_created_delete'] . "</a></p>";
    $contents .= "<p style='font-size:13px'>" . $info[0]->description . "</p>";
    $contents .= "<p style='font-size:13px;margin-top:10px'>" . $lang_module['theme_created_folder'] . " <span style='background-color:#E5F4FD'>/themes/" . $value . "/</span></p>";
    $contents .= "<p style='font-size:13px;margin-top:20px'>" . $lang_module['theme_created_position'] . " ";
    $position = $xml->xpath( 'positions' ); //array
    $positions = $position[0]->position; //object
    $pos = array();

    for ( $j = 0, $count = sizeof( $positions ); $j < $count; ++$j )
    {
        $pos[] = $positions[$j]->name;
    }
    $contents .= implode( ' | ', $pos );
    $contents .= "</p>";
    if ( $i % 2 == 0 and $i < $number_theme )
    {
        $contents .= "</td></tr>\n<tr>\n";
    }
    else
    {
        $contents .= "</td>\n";
    }
    ++$i;

}
$contents .= "</tr></tbody>";
$contents .= "</table>";

$errorconfig = ( ! empty( $errorconfig ) ) ? "<div id='edit'></div><div class=\"quote\" style=\"width:780px;\"><blockquote class='error'><span id='message'>ERROR! CONFIG FILE: " . implode( "<br />", $errorconfig ) . "</span></blockquote></div>\n" : '';

$contents .= '
<script type="text/javascript">
//<![CDATA[
$(function(){
	$("a.activate").click(function(){
		var theme = $(this).attr("title");
        $.ajax({        
	        type: "POST",
	        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=activatetheme",
	        data:"theme="+theme,
	        success: function(data){
	        	if(data!="OK_"+theme){
	        		alert(data); 
	        	}
	            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '";
	        }
        }); 		
	});
	$("a.delete").click(function(){
		var theme = $(this).attr("title");
		if (confirm("' . $lang_module['theme_created_delete_theme'] . '" + theme +" ?")){
	        $.ajax({        
		        type: "POST",
		        url: "index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=deletetheme",
		        data:"theme="+theme,
		        success: function(data){
		        	alert(data);  
		            window.location="index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '";
		        }
	        });
        }
	});	
});
//]]>
</script>';

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $errorconfig . $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>