<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['sources'];
list( $sourceid, $title, $link, $logo, $error ) = array( 
    0, "", "http://", "", "" 
);
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if ( ! empty( $savecat ) )
{
    $sourceid = $nv_Request->get_int( 'sourceid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $link = strtolower( filter_text_input( 'link', 'post', '' ) );
    list( $logo_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT logo FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE `sourceid` =" . $sourceid . "" ) );
    
    $logo = filter_text_input( 'logo', 'post', '' );
    if ( ! nv_is_url( $logo ) and file_exists( NV_DOCUMENT_ROOT . $logo ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" );
        $logo = substr( $logo, $lu );
    }
    elseif ( ! nv_is_url( $logo ) )
    {
        $logo = $logo_old;
    }
    if ( $logo != $logo_old )
    {
        @unlink( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/source/" . $logo_old );
    }
    if ( $sourceid == 0 )
    {
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources`" ) );
        $weight = intval( $weight ) + 1;
        $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_sources` (`sourceid`, `title`, `link`, `logo`, `weight`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $link ) . ", " . $db->dbescape( $logo ) . ", " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
        if ( $db->sql_query_insert_id( $query ) )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_source', " " , $admin_info['userid'] );
        	$db->sql_freeresult();
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
    }
    else
    {
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_sources` SET `title`=" . $db->dbescape( $title ) . ", `link` =  " . $db->dbescape( $link ) . ", `logo`=" . $db->dbescape( $logo ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `sourceid` =" . $sourceid . "";
        $db->sql_query( $query );
        if ( $db->sql_affectedrows() > 0 )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_source', "sourceid ".$sourceid , $admin_info['userid'] );
            $db->sql_freeresult();
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "" );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
        $db->sql_freeresult();
    }
}
$contents = "<div id=\"module_show_list\">";
$contents .= nv_show_sources_list();
$contents .= "</div><br />\n";
$sourceid = $nv_Request->get_int( 'sourceid', 'get', 0 );
if ( $sourceid > 0 )
{
    list( $sourceid, $title, $link, $logo ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid`, `title`, `link`, `logo`  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` where `sourceid`=" . $sourceid . "" ) );
    $lang_module['add_sources'] = $lang_module['edit_sources'];
}
$contents .= "<a id=\"edit\"></a>";
if ( $error != "" )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}
$contents .= "<form enctype=\"multipart/form-data\" action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"sourceid\" value=\"" . $sourceid . "\" />";
$contents .= "<input name=\"savecat\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<caption>" . $lang_module['add_sources'] . "</caption>\n";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"title\" type=\"text\" value=\"" . $title . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['link'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"link\" type=\"text\" value=\"" . $link . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['source_logo'] . ": </strong></td>\n";
$contents .= "<td>";
if ( ! empty( $logo ) )
{
    $logo = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" . $logo;
}

$contents .= "<input style=\"width:500px\" type=\"text\" name=\"logo\" id=\"logo\" value=\"" . $logo . "\"/>";
$contents .= '<input style="width:100px" type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/>';
if ( ! empty( $logo ) )
{
    $contents .= "<br /><img src=\"" . $logo . "\"/></td>\n";
}
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "</table>";
$contents .= "<br /><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></center>\n";
$contents .= "</form>\n";

$contents .= "<script type=\"text/javascript\">\n//<![CDATA[\n";
$contents .= '$("input[name=selectimg]").click(function(){
						var area = "logo";
						var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/source";						
						var type= "image";
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});';

$contents .= "\n//]]></script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>