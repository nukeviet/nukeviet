<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['download_editfile'];
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$id = $nv_Request->get_string( 'id', 'post,get' );
if ( $nv_Request->get_string( 'confirm', 'post' ) )
{
    list( $uploadtime ) = $db->sql_fetchrow( $db->sql_query( "SELECT uploadtime FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE id=$id" ) );
    $parentid = $nv_Request->get_int( 'catparent', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $description = filter_text_input( 'description', 'post', '', 1 );
    $author = filter_text_input( 'author', 'post', '', 1 );
    $authoremail = filter_text_input( 'authoremail', 'post', '', 1 );
    $homepage = filter_text_input( 'homepage', 'post', '', 1 );
    $fileupload = filter_text_input( 'fileupload', 'post', '', 1 );
    $taglist = filter_text_input( 'taglist', 'post', '', 1 );
    $version = filter_text_input( 'version', 'post', '', 1 );
    $linkdirect = filter_text_input( 'linkdirect', 'post', '', 1 );
    $filesize = filter_text_input( 'filesize', 'post', '', 1 );
    $fileimage = filter_text_input( 'fileimage', 'post', '', 1 );
    
    $active = $nv_Request->get_int( 'active', 'post', 0 );
    $sql = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
	" . $id . "
	," . $admin_info['admin_id'] . "
	," . $db->dbescape( $title ) . "
	," . $parentid . "
	, " . $db->dbescape( $description ) . "
	," . $uploadtime . "
	, " . $db->dbescape( $author ) . "
	, " . $db->dbescape( $authoremail ) . "
	, " . $db->dbescape( $homepage ) . "
	, " . $db->dbescape( $fileupload ) . "
	, " . $db->dbescape( $version ) . "
	, " . $db->dbescape( $linkdirect ) . "
	, " . $filesize . "
	, " . $db->dbescape( $fileimage ) . "
	, " . $db->dbescape( $taglist ) . "
	, '" . $active . "')";
    $result = $db->sql_query( $sql );
    $error = $db->sql_error();
    if ( $result ) $contents .= $lang_module['editfile_success'] . '<META HTTP-EQUIV=Refresh CONTENT="3; URL=' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=file">';
    else $contents .= $lang_module['editfile_unsuccess'] . $error['message'] . '<META HTTP-EQUIV=Refresh CONTENT="3; URL=' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&op=file">';
}
else
{
    $result = $db->sql_query( "SELECT name, value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`" );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $data[$row['name']] = $row['value'];
    }
    $row = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE id='$id'" ) );
    $contents .= "<form method='post' name='' action='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&op=editfile&id=" . $id . "'>\n";
    $contents .= "<table class=\"tab1\" style='width:800px'>\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan=\"2\">" . $lang_module['editfile_titlebox'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_title'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['title'] . "' name='title' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editfile_cat'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<select name='catparent'>";
    $sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0";
    $result = $db->sql_query( $sql );
    while ( $subrow = $db->sql_fetchrow( $result ) )
    {
        $catparent = $row['catid'];
        $sel = ( $subrow['cid'] == $row['catid'] ) ? ' selected' : '';
        $contents .= "<option value='" . $subrow['cid'] . "' " . $sel . ">" . $subrow['title'] . "</option>";
        $contents .= getsubcat( $subrow['cid'], $i = '-' );
    }
    $contents .= "</select>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_image'] . "</td>\n";
    $contents .= "<td>";
    $contents .= '	<input style="width:400px" type="text" name="fileimage" id="fileimage" value="' . $fileimage . '"/><input type="button" value="Browse Server" name="selectimg"/>
				<script type="text/javascript">
					$("input[name=selectimg]").click(function(){
						var area = "fileimage";
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});
				</script>	';
    $contents .= "<br/>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editfile_description'] . "</td>\n";
    $contents .= "<td>";
    if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
    {
        $description = str_replace( '%26', '&', $row['description'] );
        $contents .= nv_aleditor( 'description', '600px', '300px', $description );
    }
    else
    {
        $contents .= "<textarea cols='70' rows='8' name=\"description\" id=\"description\">" . $row['description'] . "</textarea>";
    }
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_author'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['author'] . "' name='author' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_email'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['authoremail'] . "' name='authoremail' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_homepage'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['homepage'] . "' name='homepage' style='width:290px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_selectfile'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' readonly value='" . $row['fileupload'] . "' name='fileupload' id='fileupload' style='width:290px'><input type=\"button\" value=\"Browse Server\" onclick=\"BrowseFile();\" />";
    if ( $row['fileupload'] != '' && file_exists( NV_DOCUMENT_ROOT . $row['fileupload'] ) )
    {
        $contents .= "<br /><a style='font-weight:bold' href='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_data . "&op=down&file=" . $row['fileupload'] . "'>" . $lang_module['editfile_downloadfile'] . "</a>";
    }
    $contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "" . NV_EDITORSDIR . "/ckfinder/ckfinder.js\"></script>\n";
    $contents .= "<script type=\"text/javascript\">\n";
    $contents .= "function BrowseFile()\n";
    $contents .= "{\n";
    $contents .= "	var finder = new CKFinder() ;\n";
    $contents .= "	finder.BasePath = '" . NV_BASE_SITEURL . "" . NV_EDITORSDIR . "/ckfinder/' ;	// The path for the installation of CKFinder (default = \"/ckfinder/\").\n";
    $contents .= "	finder.SelectFunction = SetFileUpload ;\n";
    $contents .= "	finder.StartupPath  = 'Files:/" . $module_name . "/" . $data['filedir'] . "/' ;\n";
    $contents .= "	finder.Popup() ;\n";
    $contents .= "}\n";
    $contents .= "\n";
    $contents .= "// This is a sample function which is called when a file is selected in CKFinder.\n";
    $contents .= "function SetFileUpload( fileUrl)\n";
    $contents .= "{\n";
    $contents .= "	$( '#fileupload' ).val(fileUrl) ;\n";
    $contents .= "}\n";
    $contents .= "</script>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_linkfile'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<textarea cols='70' rows='3' name='linkdirect'>" . $row['linkdirect'] . "</textarea>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_version'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['version'] . "' name='version' style='width:150px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_size'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['filesize'] . "' name='filesize' style='width:150px'> " . $lang_module['editfile_sizeblank'];
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td style='width:150px'>" . $lang_module['editfile_tag'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<input type='text' value='" . $row['tags'] . "' name='taglist' style='width:350px'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tbody class=\"second\">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['editfile_active'] . "</td>\n";
    $contents .= "<td>";
    $contents .= "<label><input type='checkbox' name='active' value='1' " . ( ( $row['active'] == 1 ) ? ' checked' : '' ) . ">  " . $lang_module['download_yes'] . "</label>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td colspan='2' style='padding-left:160px'>";
    $contents .= "<input type='submit' name='confirm' value='" . $lang_module['editfile_save'] . "'>";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</table>\n";
    $contents .= "</form>\n";
    $contents .= "
<script type='text/javascript'>
$(function(){
$('input[name=\"confirm\"]').click(function(){
	var title = $('input[name=\"title\"]').val();
	if (title==''){
		alert('" . $lang_module['editfile_error_title'] . "');
		$('input[name=\"title\"]').focus();
		return false;
	}
	var authoremail = $('input[name=\"authoremail\"]').val();
	if (!nv_email_check(authoremail)){
		alert('" . $lang_module['editfile_error_email'] . "');
		$('input[name=\"authoremail\"]').focus();
		return false;
	} 	
	var fileupload = $('input[name=\"fileupload\"]').val();
	if (fileupload=='' && linkdirect==''){
		alert('" . $lang_module['editfile_error_fileupload'] . "');
		$('textarea[name=\"fileupload\"]').focus();
		return false;
	}
	var filesize = $('input[name=\"filesize\"]').val();
	if (isNaN(filesize)){
		alert('" . $lang_module['editfile_error_filesize'] . "');
		$('input[name=\"filesize\"]').focus();
		return false;
	}
});

});
</script>
";
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>