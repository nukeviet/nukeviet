<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['categories'];
$error = $admins = "";
$savecat = 0;
list( $catid, $parentid, $title, $alias, $description, $keywords ) = array( 
    0, 0, "", "", "", "" 
);

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );

if ( ! empty( $savecat ) )
{
    $catid = $nv_Request->get_int( 'catid', 'post', 0 );
    list( $parentid_old ) = $db->sql_fetchrow( $db->sql_query( "SELECT `parentid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid` = '" . $catid . "'" ) );
    $parentid = $nv_Request->get_int( 'parentid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', "", 1, 100 );
    $catimage = filter_text_input( 'catimage', 'post' );
    $keywords = filter_text_input( 'keywords', 'post' );
    
    $alias = filter_text_input( 'alias', 'post' );
    $description = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
    
    $alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );
    if ( $catid == 0 and ! empty( $title ) )
    {
        $description = nv_nl2br( $description, '<br />' ); // 
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $parentid ) . "" ) );
        $weight = intval( $weight ) + 1;
        $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_cat` (`catid`, `parentid`, `title`, `catimage`, `alias`, `description`, `weight`, `inhome`, `numlinks`, `keywords`, `add_time`, `edit_time`) VALUES (NULL, " . $db->dbescape( $parentid ) . ", " . $db->dbescape( $title ) . ", " . $db->dbescape( $catimage ) . " , " . $db->dbescape( $alias ) . ", " . $db->dbescape( $description ) . ", " . $db->dbescape( $weight ) . ", '1', '3', " . $db->dbescape( $keywords ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP())";
        if ( $db->sql_query_insert_id( $query ) )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_cat', " ", $admin_info['userid'] );
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
    elseif ( $catid > 0 and ! empty( $title ) )
    {
        $check_exit = 0;
        if ( $parentid != $parentid_old )
        {
            list( $check_exit ) = $db->sql_fetchrow( $db->sql_query( "SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `catid` = '" . $catid . "'" ) );
        }
        if ( intval( $check_exit ) > 0 )
        {
            $error = "error delete cat";
        }
        else
        {
            $description = nv_nl2br( $description, '<br />' ); // 
            $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `parentid`=" . $db->dbescape( $parentid ) . ", `title`=" . $db->dbescape( $title ) . ", `catimage` =  " . $db->dbescape( $catimage ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`= " . $db->dbescape( $keywords ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `catid` =" . $catid . "";
            $db->sql_query( $query );
            if ( $db->sql_affectedrows() > 0 )
            {
                $db->sql_freeresult();
                if ( $parentid != $parentid_old )
                {
                    list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid`=" . $db->dbescape( $parentid ) . "" ) );
                    $weight = intval( $weight ) + 1;
                    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_cat` SET `weight`=" . $weight . " WHERE `catid`=" . intval( $catid );
                    $db->sql_query( $sql );
                    nv_fix_cat( $parentid );
                    nv_fix_cat( $parentid_old );
                    nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_cat', "catid ".$catid, $admin_info['userid'] );
                }
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
}

global $array_cat, $numcat;
$contents = "<div id=\"module_show_list\">";
$contents .= nv_show_cat_list( $array_cat, $numcat );
$contents .= "</div><br>\n";
$catid = ( isset( $_GET['catid'] ) ) ? intval( $_GET['catid'] ) : 0;
if ( $catid > 0 )
{
    list( $catid, $parentid, $title, $catimage, $alias, $description, $keywords ) = $db->sql_fetchrow( $db->sql_query( "SELECT `catid`, `parentid`, `title`, `catimage`, `alias`, `description`, `keywords`  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` where `catid`=" . $catid . "" ) );
    $caption = $lang_module['edit_cat'];
    $description = nv_br2nl( $description );
}
else
{
    $catimage = '';
    $caption = $lang_module['add_cat'];
    $parentid = 0;
}
$description = nv_htmlspecialchars( $description );
if ( $error != "" )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}
$contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
$contents .= "<input type=\"hidden\" name =\"catid\" value=\"" . $catid . "\" />";
$contents .= "<input type=\"hidden\" name =\"parentid_old\" value=\"" . $parentid . "\" />";
$contents .= "<input name=\"savecat\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<caption>" . $caption . "</caption>\n";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"title\" type=\"text\" value=\"" . $title . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['cat_sub'] . ": </strong></td>\n";
$contents .= "<td>";
$contents .= "<select name=\"parentid\" site=\"3\">\n";
if ( $parentid == 0 )
{
    $sl = " selected=\"selected\"";
}
$sl = "";
$contents .= "<option value=\"0\" " . $sl . ">" . $lang_module['cat_sub_sl'] . "</option>\n";
foreach ( $array_cat as $catid_i => $array_cat_i )
{
    if ( $catid_i == $parentid )
    {
        $sl = " selected=\"selected\"";
    }
    $contents .= "<option value=\"" . $catid_i . "\" " . $sl . ">" . $array_cat_i['title'] . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</td>";
$contents .= "</tr>";

$filelist = nv_scandir( NV_ROOTDIR . "/uploads/$module_name/cat", "/^([a-zA-Z0-9\-\_]+)\.(jpg|gif)$/" );
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['weblink_fileimage'] . ": </strong></td>\n";
$contents .= "<td>";
$contents .= "<div style='float:left'>";
$contents .= "<select name=\"catimage\" id='catimage'>\n";
foreach ( $filelist as $image )
{
    $selected = ( $catimage == $image ) ? ' selected' : '';
    $contents .= "<option value=\"" . $image . "\" " . $selected . ">" . $image . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</div>\n";
$contents .= "<div style='float:left;padding-left:30px' id='preview'></div>";
//show preview
$contents .= '
<script>
    $("#catimage").change(function () {
    	var image = $("#catimage").val();
    	$("#preview").html("<img src=' . NV_BASE_SITEURL . '/uploads/weblinks/cat/"+image+">");
    });
</script>
';
$contents .= "</td>";
$contents .= "</tr>";
if ( $alias != "" )
{
    $contents .= "<tr>";
    $contents .= "<td valign=\"top\" align=\"right\"  width=\"100px\"><strong>" . $lang_module['alias'] . ": </strong></td>\n";
    $contents .= "<td><input style=\"width: 650px\" name=\"alias\" type=\"text\" value=\"" . $alias . "\" maxlength=\"255\" /></td>\n";
    $contents .= "</tr>";
}
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['keywords'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"keywords\" type=\"text\" value=\"" . $keywords . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\"  width=\"100px\"><br><strong>" . $lang_module['description'] . " </strong></td>\n";
$contents .= "<td>";
$contents .= "<textarea style=\"width: 650px\" name=\"description\" cols=\"100\" rows=\"5\">" . $description . "</textarea>";
$contents .= "</td>";
$contents .= "</tr>";
$contents .= "</table>";
$contents .= "<br><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></center>\n";
$contents .= "</form>\n";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>