<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['sources'];
$table_name = $db_config['prefix'] . "_" . $module_data . "_sources";
list( $rowcontent['sourceid'], $title, $link, $logo, $error ) = array(
	0,
	"",
	"http://",
	"",
	"" );
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
$rowcontent = array(
	'sourceid' => 0,
	'link' => '',
	'logo' => '',
	'weight' => 0,
	'add_time' => 0,
	'edit_time' => 0,
	'title' => '' );
if( ! empty( $savecat ) )
{
	$field_lang = nv_file_table( $table_name );

	$rowcontent['sourceid'] = $nv_Request->get_int( 'sourceid', 'post', 0 );
	$rowcontent['title'] = filter_text_input( 'title', 'post', '', 1 );
	$rowcontent['link'] = strtolower( filter_text_input( 'link', 'post', '' ) );
	list( $logo_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT logo FROM `" . $table_name . "` WHERE `sourceid` =" . $rowcontent['sourceid'] . "" ) );

	$rowcontent['logo'] = filter_text_input( 'logo', 'post', '' );
	if( ! nv_is_url( $rowcontent['logo'] ) and file_exists( NV_DOCUMENT_ROOT . $rowcontent['logo'] ) )
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" );
		$rowcontent['logo'] = substr( $rowcontent['logo'], $lu );
	}
	elseif( ! nv_is_url( $rowcontent['logo'] ) )
	{
		$rowcontent['logo'] = $logo_old;
	}
	if( $rowcontent['logo'] != $logo_old )
	{
		@unlink( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/source/" . $logo_old );
	}
	if( $rowcontent['sourceid'] == 0 )
	{
		list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $table_name . "`" ) );
		$weight = intval( $weight ) + 1;
		$listfield = "";
		$listvalue = "";
		foreach( $field_lang as $field_lang_i )
		{
			list( $flang, $fname ) = $field_lang_i;
			$listfield .= ", `" . $flang . "_" . $fname . "`";
			if( $flang == NV_LANG_DATA )
			{
				$listvalue .= ", " . $db->dbescape( $rowcontent[$fname] );
			}
			else
			{
				$listvalue .= ", " . $db->dbescape( $rowcontent[$fname] );
			}
		}
		$query = "INSERT INTO `" . $table_name . "` (`sourceid`,`link`, `logo`, `weight`, `add_time`, `edit_time` " . $listfield . ") VALUES (NULL, " . $db->dbescape( $link ) . ", " . $db->dbescape( $logo ) . ", " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ) " . $listvalue . ")";
		if( $db->sql_query_insert_id( $query ) )
		{
			$db->sql_freeresult();
			nv_del_moduleCache( $module_name );
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
		$query = "UPDATE `" . $table_name . "` SET `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $rowcontent['title'] ) . ", `link` =  " . $db->dbescape( $rowcontent['link'] ) . ", `logo`=" . $db->dbescape( $rowcontent['logo'] ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `sourceid` =" . $rowcontent['sourceid'] . "";
		$db->sql_query( $query );
		if( $db->sql_affectedrows() > 0 )
		{
			$error = $lang_module['saveok'];
			$db->sql_freeresult();
			nv_del_moduleCache( $module_name );
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
$contents .= "</div><br>\n";
$rowcontent['sourceid'] = $nv_Request->get_int( 'sourceid', 'get', 0 );
if( $rowcontent['sourceid'] > 0 )
{
	list( $rowcontent['sourceid'], $rowcontent['title'], $rowcontent['link'], $rowcontent['logo'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `sourceid`, `" . NV_LANG_DATA . "_title`, `link`, `logo`  FROM `" . $db_config['prefix'] . "_" . $module_data . "_sources` where `sourceid`=" . $rowcontent['sourceid'] . "" ) );
	$lang_module['add_sources'] = $lang_module['edit_sources'];
}
$contents .= "<a id=\"edit\"></a>";
if( $error != "" )
{
	$contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
	$contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
	$contents .= "</div>\n";
	$contents .= "<div class=\"clear\"></div>\n";
}
$contents .= "<form enctype=\"multipart/form-data\" action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"sourceid\" value=\"" . $rowcontent['sourceid'] . "\" />";
$contents .= "<input name=\"savecat\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<caption>" . $lang_module['add_sources'] . "</caption>\n";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"title\" type=\"text\" value=\"" . $rowcontent['title'] . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['link'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"link\" type=\"text\" value=\"" . $rowcontent['link'] . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['source_logo'] . ": </strong></td>\n";
$contents .= "<td>";
if( ! empty( $rowcontent['logo'] ) )
{
	$rowcontent['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/source/" . $rowcontent['logo'];
}

$contents .= "<input style=\"width:500px\" type=\"text\" name=\"logo\" id=\"logo\" value=\"" . $rowcontent['logo'] . "\"/>";
$contents .= '<input style="width:100px" type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/>';
if( ! empty( $rowcontent['logo'] ) )
{
	$contents .= "<br /><img src=\"" . $rowcontent['logo'] . "\"/></td>\n";
}
$contents .= "</tr>";
$contents .= "</table>";
$contents .= "<br><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></center>\n";
$contents .= "</form>\n";

$contents .= "<script type=\"text/javascript\">\n";
$contents .= '$("input[name=selectimg]").click(function(){
						var area = "logo";
						var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/source";						
						var type= "image";
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});';
$contents .= "</script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>