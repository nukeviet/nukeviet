<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

function check_url ( $id, $url )
{
    global $db, $module_data;
    $sql = "SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` != '" . $id . "' AND `url` = '" . $url . "'";
    list( $numurl ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
    $msg = ( $numurl > 0 ) ? false : true;
    return $msg;
}

function check_title ( $title )
{
    global $db, $module_data;
    $sql = 'SELECT title FROM `' . NV_PREFIXLANG . '_' . $module_data . '_rows` WHERE title = "' . $title . '"';
    $numtitle = $db->sql_numrows( $db->sql_query( $sql ) );
    $msg = ( $numtitle > 0 ) ? false : true;
    return $msg;
}

$rowcat = array( 
    "id" => "", "catid" => "", "title" => "", "alias" => "", "url" => "", "urlimg" => "", "description" => "", "add_time" => "", "edit_time" => "", "hits_total" => "", "status" => 1 
);

$error = "";
$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$submit = $nv_Request->get_string( 'submit', 'post' );
if ( ! empty( $submit ) )
{
    $error = 0;
    $catid = $nv_Request->get_int( 'catid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $alias = filter_text_input( 'alias', 'post', '', 1 );
    $parentid = $nv_Request->get_int( 'parentid', 'post', 0 );
    $alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );
    $url = filter_text_input( 'url', 'post', '' );
    $image = filter_text_input( 'image', 'post', '' );
    if ( ! nv_is_url( $image ) and file_exists( NV_DOCUMENT_ROOT . $image ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" );
        if ( substr( $image, 0, $lu ) == NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" )
        {
            $image = substr( $image, $lu );
        }
    }
    $admin_phone = "";
    $admin_email = "";
    $note = "";
    $description = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
    $description = ( defined( 'NV_EDITOR' ) ) ? nv_editor_nl2br( $description ) : nv_nl2br( $description, '<br />' );
    
    $status = ( $nv_Request->get_int( 'status', 'post' ) == 1 ) ? 1 : 0;
    //check url
    if ( empty( $url ) || !nv_is_url( $url ) || !check_url( $id, $url ) || !nv_check_url( $url ) )
    {
        $error = $lang_module['error_url'];
    }
    elseif ( empty( $title ) )
    {
        $error = $lang_module['error_title'];
    }
    elseif ( strip_tags( $description ) == "" )
    {
        $error = $lang_module['error_description'];
    }
    else
    {
        if ( $id > 0 )
        {
            $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `catid`=" . $catid . ", `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `url` =  " . $db->dbescape( $url ) . ", `urlimg` =  " . $db->dbescape( $image ) . ", `description`=" . $db->dbescape( $description ) . ", `edit_time` = UNIX_TIMESTAMP(), `status`=" . $status . " WHERE `id` =" . $id . "";
            $db->sql_query( $query );
            if ( $db->sql_affectedrows() > 0 )
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weblink_edit_link'], $title , $admin_info['userid'] );
            	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
                die();
            }
            else
            {
                $error = $lang_module['errorsave'];
            }
            $db->sql_freeresult();
        }
        else
        {
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `catid`, `title`, `alias`, `url`, `urlimg`, `admin_phone`, `admin_email`, `note`, `description`, `add_time`, `edit_time`, `hits_total`, `status`) 
            VALUES (NULL, '" . $catid . "', " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $url ) . ", " . $db->dbescape( $image ) . ", '" . $admin_phone . "', '" . $admin_email . "', " . $db->dbescape( $note ) . ", " . $db->dbescape( $description ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '0', " . $status . ")";
            
            if ( $db->sql_query_insert_id( $query ) )
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weblink_add_link'], $title , $admin_info['userid'] );
            	$db->sql_freeresult();
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
                die();
            }
            else
            {
                $error = $lang_module['errorsave'];
            }
        }
    }
    $rowcat['id'] = $id;
    $rowcat['url'] = $url;
    $rowcat['title'] = $title;
    $rowcat['urlimg'] = $image;
    $rowcat['description'] = $description;
}
elseif ( $id > 0 )
{
    $query = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id . "" );
    $rowcat = $db->sql_fetchrow( $query );
    if ( $rowcat['id'] > 0 )
    {
        $page_title = $lang_module['weblink_edit_link'];
    }
}

if ( empty( $rowcat['id'] ) )
{
    $page_title = $lang_module['weblink_add_link'];
}

$rowcat['description'] = ( defined( 'NV_EDITOR' ) ) ? nv_editor_br2nl( $rowcat['description'] ) : nv_br2nl( $rowcat['description'] ); // dung de lay data tu CSDL
$rowcat['description'] = nv_htmlspecialchars( $rowcat['description'] ); // dung de dua vao editor


if ( ! empty( $rowcat['urlimg'] ) and ! nv_is_url( $rowcat['urlimg'] ) )
{
    $rowcat['urlimg'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $rowcat['urlimg'];
}

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
$contents = "";
if ( $error != "" )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}
$contents .= "<div id=\"list_mods\">";

$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
// post header parameter
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"id\" value=\"" . $id . "\" />";
$contents .= "<table class=\"tab1\" cellspacing=\"5\" cellpadding=\"5\">\n";
$contents .= "<tr>";
$contents .= "<td align=\"right\" style=\"width: 150px;\">" . $lang_module['weblink_add_title'] . ": </td>\n";
$contents .= "<td><input type=\"text\" name=\"title\" id=\"webtitle\" style=\"width:550px\" value=\"" . $rowcat['title'] . "\"/></td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\">" . $lang_module['weblink_add_url'] . ": </td>\n";
$contents .= "<td><input style=\"width: 550px\" name=\"url\" id= \"url\" type=\"text\" value=\"" . $rowcat['url'] . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\">" . $lang_module['weblink_add_parent'] . ": </td>\n";
$contents .= "<td>\n";
$contents .= "<select name=\"catid\">\n";
$querysubcat = $db->sql_query( "SELECT catid, parentid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `parentid`, `weight` ASC" );
$array_cat = array();
while ( $row = $db->sql_fetchrow( $querysubcat ) )
{
    $selected = ( intval( $row['catid'] ) == intval( $rowcat["catid"] ) ) ? 'selected' : '';
    $array_cat[$row['catid']] = $row['title'];
    $title = $row["title"];
    if ( intval( $row['parentid'] ) > 0 )
    {
        $title = $array_cat[$row['parentid']] . " ->" . $row["title"];
    }
    $contents .= "<option value=\"" . $row["catid"] . "\" " . $selected . ">" . $title . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</td>\n";
$contents .= "</tr>";
if ( $id > 0 )
{
    $contents .= "<tr>";
    $contents .= "<td valign=\"top\" align=\"right\">" . $lang_module['alias'] . ": </td>\n";
    $contents .= "<td><input style=\"width: 200px\" name=\"alias\" type=\"text\" value=\"" . $rowcat['alias'] . "\" maxlength=\"255\" /></td>\n";
    $contents .= "</tr>";
}
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\">" . $lang_module['weblink_add_image'] . ": </td>\n";
$contents .= "<td>";
$contents .= '<input style="width:400px" type="text" name="image" id="image" value="' . $rowcat['urlimg'] . '"/>';
$contents .= '<input type="button" value="Browse Server" name="selectimg"/>
				<script type="text/javascript">
					$("input[name=selectimg]").click(function(){
						var area = "image";
						var path= "' . NV_UPLOADS_DIR . '/' . $module_name . '";						
						nv_open_browse_file("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&area=" + area+"&path="+path, "NVImg", "850", "500","resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
						return false;
					});
				</script>	';
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td align=\"right\">" . $lang_module['weblink_description'] . ": </td>\n";
$contents .= "<td>";
if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( "description", '700px', '300px', $rowcat['description'] );
}
else
{
    $contents .= "<textarea style=\"width: 650px\" name=\"description\" id=\"description\" cols=\"20\" rows=\"8\">" . $rowcat['description'] . "</textarea>";
}
$contents .= "</td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\">" . $lang_module['weblink_inhome'] . ": </td>\n";
$checked = ( intval( $rowcat['status'] ) == 1 ) ? 'checked' : '';
$contents .= "<td><label><input name=\"status\" type=\"checkbox\" value=\"1\" checked=\"" . $checked . "\" />" . $lang_module['weblink_yes'] . "</label></td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td align=\"left\" colspan=\"2\"><input name=\"submit\" style=\"width:80px;margin-left:110px\" type=\"submit\" value=\"" . $lang_module['weblink_submit'] . "\" /></td>\n";
$contents .= "</tr>";
$contents .= "</table>";
$contents .= "</form>\n";
$contents .= "</div>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>