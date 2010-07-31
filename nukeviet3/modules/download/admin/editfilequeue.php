<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['download_editfilequeue'];
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
// initial config data
$sql = "SELECT name,value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query( $sql );
while ( $data = $db->sql_fetch_assoc( $result ) )
{
    $configdownload[$data['name']] = $data['value'];
}

$title = "";
$parentid = "";
$description = "";
$author = "";
$authoremail = "";
$homepage = "";
$fileupload = "";
$taglist = "";
$version = "";
$linkdirect = "";
$filesize = "";
$fileimage = "";
$active = "";
$intro_text = "";
$copyright = "";
$msg_err = array();
$msg_err['title'] = "";
$msg_err['authoremail'] = "";
$msg_err['fileupload'] = "";
$id = $nv_Request->get_int( 'id', 'post,get' );
$row_tmp = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE id='$id'" ) );
if ( $id != "" && $nv_Request->get_string( 'confirm', 'post' ) == "" )
{
    $title = $row_tmp['title'];
    $parentid = $row_tmp['catid'];
    $description = $row_tmp['description'];
    $description = nv_editor_br2nl( $description ); // dung de lay data tu CSDL
    $description = nv_htmlspecialchars( $description ); // dung de dua vao editor
    $author = $row_tmp['author'];
    $authoremail = $row_tmp['authoremail'];
    $homepage = $row_tmp['homepage'];
    $fileupload = $row_tmp['fileupload'];
    $taglist = $row_tmp['tags'];
    $version = $row_tmp['version'];
    $linkdirect = $row_tmp['linkdirect'];
    $filesize = $row_tmp['filesize'];
    $fileimage = $row_tmp['fileimage'];
    $active = $row_tmp['active'];
    $intro_text = $row_tmp['introtext'];
    $copyright = $row_tmp['copyright'];
}
if ( $nv_Request->get_string( 'confirm', 'post' ) )
{
    $parentid = $nv_Request->get_int( 'parentid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    
    $description = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
    $description = nv_editor_nl2br( $description ); // dung de save vao CSDL
    

    $title = filter_text_input( 'title', 'post', '', 1 );
    $author = filter_text_input( 'author', 'post', '', 1 );
    $authoremail = filter_text_input( 'authoremail', 'post', '', 0 );
    $homepage = filter_text_input( 'homepage', 'post', '', 0 );
    $fileupload = filter_text_input( 'fileupload', 'post', '', 0 );
    $filesize = filter_text_input( 'filesize', 'post', '', 0 );
    $taglist = filter_text_input( 'taglist', 'post', '', 1 );
    $version = filter_text_input( 'version', 'post', '', 1 );
    $linkdirect = filter_text_input( 'linkdirect', 'post', '', 1 );
    $fileimage = filter_text_input( 'fileimage', 'post', '', 1 );
    
    $intro_text = filter_text_input( 'intro', 'post', '', 1 );
    $copyright = filter_text_input( 'copyright', 'post', '', 1 );
    
    $active = $nv_Request->get_int( 'active', 'post', 0 );
    
    $have_err = 0;
    ////////////////////////////////////////////////////////////////////////////
    if ( strlen( $title ) < 3 )
    {
        $msg_err['title'] = $lang_module['addfile_error_title'];
        $have_err = 1;
    }
    if ( $authoremail != "" )
    {
        if ( nv_check_valid_email( $authoremail ) )
        {
            $msg_err['authoremail'] = $lang_module['addfile_error_email'];
            $have_err = 1;
        }
    }
    
    if ( $linkdirect == "" )
    {
        if ( ! file_exists( NV_DOCUMENT_ROOT . $fileupload ) )
        {
            $msg_err['fileupload'] = $lang_module['addfile_not_file'];
            $have_err = 1;
        }
    }
    
    if ( $linkdirect == "" && $fileupload == "" )
    {
        $msg_err['fileupload'] = $lang_module['addfile_not_file'];
        $have_err = 1;
    }
    
    ////////////////////////////////////////////////////////////////////////////////
    if ( $have_err == 0 )
    {
        if ( ! nv_is_url( $fileimage ) and file_exists( NV_DOCUMENT_ROOT . $fileimage ) )
        {
            $fileimagenew = str_replace( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'], NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images", $fileimage );
            if ( $fileimage != $fileimagenew )
            {
                @rename( NV_DOCUMENT_ROOT . $fileimage, NV_DOCUMENT_ROOT . $fileimagenew );
            }
            $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" );
            $fileimagenew = substr( $fileimagenew, $lu );
        }
        
        if ( ! nv_is_url( $fileupload ) and file_exists( NV_DOCUMENT_ROOT . $fileupload ) )
        {
            $fileuploadnew = str_replace( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'], NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filedir'], $fileupload );
            if ( $fileuploadnew != $fileupload )
            {
                @rename( NV_DOCUMENT_ROOT . $fileupload, NV_DOCUMENT_ROOT . $fileuploadnew );
            }
            $fsize = filesize( NV_DOCUMENT_ROOT . $fileuploadnew );
            $filesize = nv_convertfromBytes( $fsize );
            
            $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filedir'] . "/" );
            $fileuploadnew = substr( $fileuploadnew, $lu );
        }
        
        $alias = change_alias( $title );
        $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` 
				(`id`, `userid`, `title`, `catid`, `alias`, `description`, `introtext`, `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`, `copyright`)
				VALUES (
					NULL
					, " . $row_tmp['userid'] . "
					, " . $db->dbescape( $title ) . "
					, " . $db->dbescape( $parentid ) . "
					, " . $db->dbescape( $alias ) . "
					, " . $db->dbescape( $description ) . "
					, " . $db->dbescape( $intro_text ) . "
					,UNIX_TIMESTAMP()
					, " . $db->dbescape( $author ) . "
					, " . $db->dbescape( $authoremail ) . "
					, " . $db->dbescape( $homepage ) . "
					, " . $db->dbescape( $fileuploadnew ) . "
					, " . $db->dbescape( $version ) . "
					, " . $db->dbescape( $linkdirect ) . "
					, " . $db->dbescape( $filesize ) . "
					, " . $db->dbescape( $fileimagenew ) . "	
					, " . $db->dbescape( $taglist ) . "	
					, '" . $active . "'
					, " . $db->dbescape( $copyright ) . ")";
        $result = $db->sql_query( $sql );
        
        $db->sql_fetchrow( $db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_tmp` WHERE id='$id'" ) );
        $url_link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=filequeue";
        Header( "Location:" . $url_link );
        exit();
    }
}
$description = nv_editor_br2nl( $description ); // dung de lay data tu CSDL
$description = nv_htmlspecialchars( $description ); // dung de dua vao editor


if ( ! empty( $fileupload ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'] . "/" . $fileupload ) )
{
    $fileupload = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'] . "/" . $fileupload;
}

if ( ! empty( $fileimage ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'] . "/" . $fileimage ) )
{
    $fileimage = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filetempdir'] . "/" . $fileimage;
}

$result = $db->sql_query( "SELECT name, value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $data[$row['name']] = $row['value'];
}
$contents .= "<form method='post' name='addfileform' action=''>\n";
$contents .= "<table class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">" . $lang_module['addfile_titlebox'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_title'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' name='title' style='width:400px' value =\"" . $title . "\"> *";
$contents .= $msg_err['title'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['addfile_cat'] . "</td>\n";
$contents .= "<td>";
$contents .= "<select name='parentid' value =\"" . $parentid . "\">";
$sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0 ORDER BY weight";
$result = $db->sql_query( $sql );
while ( $subrow = $db->sql_fetchrow( $result ) )
{
    $contents .= "<option value='" . $subrow['cid'] . "'>" . $subrow['title'] . "</option>";
    $contents .= getsubcat( $subrow['cid'], $i = '-' );
}
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_image'] . "</td>\n";
$contents .= "<td>";
$contents .= '	<input style="width:400px" type="text" name="fileimage" id="fileimage" value="' . $fileimage . '"/><input type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/>';
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['intro_title'] . "</td><td><textarea cols='70' rows='8' name=\"intro\">" . $intro_text . "</textarea></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=2>" . $lang_module['addfile_description'] . "\n";
$contents .= "<br/>";
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( 'description', '820px', '300px', $description );
}
else
{
    $contents .= "<textarea cols='70' rows='8' name=\"description\" id=\"description\">" . $row['description'] . "</textarea>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_author'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' name='author' style='width:290px' value =\"" . $author . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_email'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' name='authoremail' style='width:290px' value =\"" . $authoremail . "\">";
$contents .= $msg_err['authoremail'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_homepage'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' name='homepage' style='width:290px' value =\"" . $homepage . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_selectfile'] . "</td>\n";
$contents .= "<td>";
$contents .= '	<input style="width:400px" type="text" name="fileupload" id="fileupload" value="' . $fileupload . '"/><input type="button" value="' . $lang_global['browse_file'] . '" name="selectfile"/>';
$contents .= $msg_err['fileupload'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_linkfile'] . "</td>\n";
$contents .= "<td>";
$contents .= "<textarea cols='70' name='linkdirect' rows='5'>" . $linkdirect . "</textarea>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_version'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' name='version' style='width:150px' value =\"" . $version . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_size'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value='" . $filesize . "' name='filesize' style='width:150px'> " . $lang_module['addfile_sizeblank'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_tag'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value =\"" . $taglist . "\" name='taglist' style='width:350px'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_copyright'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type='text' value =\"" . $copyright . "\" name='copyright' style='width:350px'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['addfile_active'] . "</td>\n";
$contents .= "<td>";
$contents .= "<label><input type='checkbox' name='active' value='1' " . ( ( $active == 1 ) ? ' checked' : '' ) . ">  " . $lang_module['download_yes'] . "</label>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2' align ='center'>";
$contents .= "<input type='submit' name='confirm' value='" . $lang_module['addfile_save'] . "'>";
$contents .= "<span name='notice' style='float:right;padding-right:50px;color:red;font-weight:bold'></span>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "</table>\n";
$contents .= "</form>\n";

$contents .= '<script type="text/javascript">
	$("input[name=selectimg]").click(function(){
		var area = "fileimage";
		var type= "image";
		var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $configdownload['filetempdir'] . '";						
		nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$("input[name=selectfile]").click(function(){
		var area = "fileupload";
		var type= "file";
		var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $configdownload['filetempdir'] . '";						
		nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>';
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>