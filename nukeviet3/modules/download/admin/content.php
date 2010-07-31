<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['download_addfile'];
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$title = "";
$catparent = "";
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
$fsize = 0;
$id = $nv_Request->get_int( 'id', 'post,get', 0 );

$result = $db->sql_query( "SELECT name, value FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`" );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $configdownload[$row['name']] = $row['value'];
}

if ( $nv_Request->get_string( 'confirm', 'post' ) )
{
    $title = filter_text_input( 'title', 'post', '', 1 );
    $catparent = $nv_Request->get_int( 'parentid', 'post', 0 );
    $author = filter_text_input( 'author', 'post', '', 1 );
    $authoremail = filter_text_input( 'authoremail', 'post', '', 1 );
    $homepage = filter_text_input( 'homepage', 'post', '', 1 );
    $filesize = filter_text_input( 'filesize', 'post', '', 1 );
    
    $description = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
    $description = nv_editor_nl2br( $description ); // dung de save vao CSDL
    

    $fileupload = filter_text_input( 'fileupload', 'post', '', 0 );
    if ( $fileupload != "" )
    {
        if ( ! nv_is_url( $fileupload ) and file_exists( NV_DOCUMENT_ROOT . $fileupload ) )
        {
            $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filedir'] . "/" );
            $fileupload = substr( $fileupload, $lu );
        }
    }
    $fileimage = filter_text_input( 'fileimage', 'post', '', 0 );
    if ( ! nv_is_url( $fileimage ) and file_exists( NV_DOCUMENT_ROOT . $fileimage ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" );
        $fileimage = substr( $fileimage, $lu );
    }
    
    $taglist = filter_text_input( 'taglist', 'post', '', 1 );
    $version = filter_text_input( 'version', 'post', '', 1 );
    $linkdirect = filter_text_input( 'linkdirect', 'post', '', 1 );
    
    $active = $nv_Request->get_int( 'active', 'post', 0 );
    $intro_text = filter_text_input( 'intro', 'post', '', 1 );
    $copyright = filter_text_input( 'copyright', 'post', '', 1 );
    
    $have_err = 0;
    ////////////////////////////////////////////////////////////////////////////
    if ( strlen( $title ) < 3 )
    {
        $msg_err[] = $lang_module['addfile_error_title'];
        $have_err = 1;
    }
    if ( $authoremail != "" )
    {
        if ( nv_check_valid_email( $authoremail ) )
        {
            $msg_err[] = $lang_module['addfile_error_email'];
            $have_err = 1;
        }
    }
    if ( $linkdirect == "" && $fileupload == "" )
    {
        $msg_err[] = $lang_module['addfile_not_file'];
        $have_err = 1;
    }
    
    ////////////////////////////////////////////////////////////////////////////////
    if ( $have_err == 0 )
    {
        $filesize = nv_convertfromBytes( $fsize );
        $alias = change_alias( $title );
        if ( empty( $id ) )
        {
            $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` 
            		(`id`, `userid`, `title`, `catid`, `alias`, `description`, `introtext`, `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`, `copyright`)
				VALUES (
					NULL
					," . $admin_info['admin_id'] . "
					," . $db->dbescape_string( $title ) . "
					," . intval( $catparent ) . "
					, " . $db->dbescape_string( $alias ) . "
					, " . $db->dbescape_string( $description ) . "
					, " . $db->dbescape_string( $intro_text ) . "
					,UNIX_TIMESTAMP()
					, " . $db->dbescape_string( $author ) . "
					, " . $db->dbescape_string( $authoremail ) . "
					, " . $db->dbescape_string( $homepage ) . "
					, " . $db->dbescape_string( $fileupload ) . "
					, " . $db->dbescape_string( $version ) . "
					, " . $db->dbescape_string( $linkdirect ) . "
					, " . $db->dbescape_string( $filesize ) . "
					, " . $db->dbescape_string( $fileimage ) . "	
					, " . $db->dbescape_string( $taglist ) . "	
					, '" . $active . "'
					, " . $db->dbescape( $copyright ) . ")";
            if ( $db->sql_query_insert_id( $sql ) )
            {
                $url_link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
                Header( "Location:" . $url_link );
                exit();
            }
        }
        else
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "`
					SET title = " . $db->dbescape( $title ) . ",
						catid = " . $catparent . ",
						alias = " . $db->dbescape_string( $alias ) . ",
						description = " . $db->dbescape_string( $description ) . ",
						introtext = " . $db->dbescape_string( $intro_text ) . ",
						author = " . $db->dbescape_string( $author ) . ",
						authoremail = " . $db->dbescape_string( $authoremail ) . ",
						homepage = " . $db->dbescape_string( $homepage ) . ",
						fileupload = " . $db->dbescape_string( $fileupload ) . ",
						version = " . $db->dbescape_string( $version ) . ",
						linkdirect = " . $db->dbescape_string( $linkdirect ) . ",
						filesize = " . $db->dbescape_string( $filesize ) . ",
						fileimage = " . $db->dbescape_string( $fileimage ) . ",
						tags = " . $db->dbescape_string( $taglist ) . ",	
						active = '" . $active . "',
						copyright = " . $db->dbescape_string( $copyright ) . "
					WHERE id = " . $id . "	
					";
            
            if ( $db->sql_query( $sql ) )
            {
                $url_link = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
                Header( "Location:" . $url_link );
            }
        }
    }
}
else
{
    $row = $db->sql_fetchrow( $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE id='$id'" ) );
    $title = $row['title'];
    $catparent = $row['catid'];
    $description = $row['description'];
    $author = $row['author'];
    $authoremail = $row['authoremail'];
    $homepage = $row['homepage'];
    $fileupload = $row['fileupload'];
    $taglist = $row['tags'];
    $version = $row['version'];
    $linkdirect = $row['linkdirect'];
    $filesize = $row['filesize'];
    $fileimage = $row['fileimage'];
    $active = $row['active'];
    $intro_text = $row['introtext'];
    $copyright = $row['copyright'];
    unset( $row );
}
$description = nv_editor_br2nl( $description ); // dung de lay data tu CSDL
$description = nv_htmlspecialchars( $description ); // dung de dua vao editor


if ( ! empty( $fileimage ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/images/" . $fileimage ) )
{
    $fileimage = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/images/" . $fileimage;
}

if ( ! empty( $fileupload ) and file_exists( NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $configdownload['filedir'] . "/" . $fileupload ) )
{
    $fileupload = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $configdownload['filedir'] . "/" . $fileupload;
}

if ( ! empty( $msg_err ) )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . implode( "<br>", $msg_err ) . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}

$contents .= "<form method='post' name='addfileform' action=''>\n";
$contents .= "<input type=\"hidden\" name =\"id\" value=\"" . $id . "\" />";
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
$contents .= "<input type=\"text\" name='title' style='width:400px' value =\"" . $title . "\"> *";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['addfile_cat'] . "</td>\n";
$contents .= "<td>";
$contents .= "<select name='parentid'>";
$sql = "SELECT cid, title  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid=0 ORDER BY weight";
$result = $db->sql_query( $sql );
while ( $subrow = $db->sql_fetchrow( $result ) )
{
    $sel = ( $subrow['cid'] == $catparent ) ? ' selected' : '';
    $contents .= "<option value='" . $subrow['cid'] . "' " . $sel . ">" . $subrow['title'] . "</option>";
    $contents .= getsubcat( $subrow['cid'], $i = '-' );
}
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_image'] . "</td>\n";
$contents .= "<td>";
$contents .= '	<input style="width:400px" type="text" name="fileimage" id="fileimage" value="' . $fileimage . '"/><input type="button" value="' . $lang_global['browse_image'] . '" name="selectimg"/>';
$contents .= "<br/>";
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
    $contents .= nv_aleditor( 'description', '700px', '300px', $description );
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
$contents .= "<input type=\"text\" name='author' style='width:290px' value =\"" . $author . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_email'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type=\"text\" name='authoremail' style='width:290px' value =\"" . $authoremail . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_homepage'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type=\"text\" name='homepage' style='width:290px' value =\"" . $homepage . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_selectfile'] . "</td>\n";
$contents .= "<td>";
$contents .= '	<input style="width:400px" type="text" name="fileupload" id="fileupload" value="' . $fileupload . '"/><input type="button" value="' . $lang_global['browse_file'] . '" name="selectfile"/>';
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
$contents .= "<input type=\"text\" name='version' style='width:150px' value =\"" . $version . "\">";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_size'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type=\"text\" value='" . $filesize . "' name='filesize' style='width:150px'> " . $lang_module['addfile_sizeblank'];
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_tag'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type=\"text\" value=\"" . $taglist . "\" name='taglist' style='width:350px'>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td style='width:150px'>" . $lang_module['addfile_copyright'] . "</td>\n";
$contents .= "<td>";
$contents .= "<input type=\"text\" value =\"" . $copyright . "\" name='copyright' style='width:350px'>";
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
		var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/images";						
		nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
	$("input[name=selectfile]").click(function(){
		var area = "fileupload";
		var type= "file";
		var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $configdownload['filedir'] . '";						
		nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path+"&type="+type, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		return false;
	});
</script>';

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>