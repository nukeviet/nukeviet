<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['block'];

$error = "";
$savecat = 0;
$data = array( 
    'bid' => 0, 'title' => '', 'alias' => '', 'description' => '', 'keywords' => '' 
);
$table_name = $db_config['prefix'] . "_" . $module_data . "_block_cat";
$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if ( ! empty( $savecat ) )
{
    $field_lang = nv_file_table( $table_name );
    $data['bid'] = $nv_Request->get_int( 'bid', 'post', 0 );
    $data['title'] = filter_text_input( 'title', 'post', '', 1 );
    $data['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
    $data['alias'] = filter_text_input( 'alias', 'post', '' );
    $data['description'] = $nv_Request->get_string( 'description', 'post', '' );
    $data['description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $data['description'] ) ), '<br />' );
    $data['alias'] = ( $data['alias'] == "" ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );
    if ( $data['bid'] == 0 )
    {
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $db_config['prefix'] . "_" . $module_data . "_block_cat`" ) );
        $weight = intval( $weight ) + 1;
        $listfield = "";
        $listvalue = "";
        foreach ( $field_lang as $field_lang_i )
        {
            list( $flang, $fname ) = $field_lang_i;
            $listfield .= ", `" . $flang . "_" . $fname . "`";
            if ( $flang == NV_LANG_DATA )
            {
                $listvalue .= ", " . $db->dbescape( $data[$fname] );
            }
            else
            {
                $listvalue .= ", " . $db->dbescape( $data[$fname] );
            }
        }
        $query = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` (`bid`, `adddefault`,`image`, `thumbnail`, `weight`, `add_time`, `edit_time` " . $listfield . ") VALUES (NULL, 0, '', '', " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ) " . $listvalue . ")";
        if ( $db->sql_query_insert_id( $query ) )
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
        $query = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` SET `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $data['title'] ) . ", `" . NV_LANG_DATA . "_alias` =  " . $db->dbescape( $data['alias'] ) . ", `" . NV_LANG_DATA . "_description`=" . $db->dbescape( $data['description'] ) . ", `" . NV_LANG_DATA . "_keywords`= " . $db->dbescape( $data['keywords'] ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `bid` =" . $data['bid'] . "";
        $db->sql_query( $query );
        if ( $db->sql_affectedrows() > 0 )
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
$contents .= nv_show_block_cat_list();
$contents .= "</div><br>\n";
$data['bid'] = $nv_Request->get_int( 'bid', 'get', 0 );
if ( $data['bid'] > 0 )
{
    list( $data['bid'], $data['title'], $data['alias'], $data['description'], $data['keywords'] ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `" . NV_LANG_DATA . "_title`, `" . NV_LANG_DATA . "_alias`, `" . NV_LANG_DATA . "_description`, `" . NV_LANG_DATA . "_keywords`  FROM `" . $db_config['prefix'] . "_" . $module_data . "_block_cat` where `bid`=" . $data['bid'] . "" ) );
    $lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}
$contents .= "<a id=\"edit\"></a>";
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
$contents .= "<input type=\"hidden\" name =\"bid\" value=\"" . $data['bid'] . "\" />";
$contents .= "<input name=\"savecat\" type=\"hidden\" value=\"1\" />\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<caption>" . $lang_module['add_block_cat'] . "</caption>\n";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['name'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"title\" type=\"text\" value=\"" . $data['title'] . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";

if ( $data['alias'] != "" )
{
    $contents .= "<tr>";
    $contents .= "<td valign=\"top\" align=\"right\"  width=\"100px\"><strong>" . $lang_module['alias'] . ": </strong></td>\n";
    $contents .= "<td><input style=\"width: 650px\" name=\"alias\" type=\"text\" value=\"" . $data['alias'] . "\" maxlength=\"255\" /></td>\n";
    $contents .= "</tr>";
}

$contents .= "<tbody class=\"second\">";
$contents .= "<tr>";
$contents .= "<td align=\"right\"><strong>" . $lang_module['keywords'] . ": </strong></td>\n";
$contents .= "<td><input style=\"width: 650px\" name=\"keywords\" type=\"text\" value=\"" . $data['keywords'] . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>";
$contents .= "</tbody>";

$contents .= "<tr>";
$contents .= "<td valign=\"top\" align=\"right\"  width=\"100px\"><br><strong>" . $lang_module['description'] . "</strong></td>\n";
$contents .= "<td>";
$contents .= "<textarea style=\"width: 650px\" name=\"description\" cols=\"100\" rows=\"5\">" . $data['description'] . "</textarea>";
$contents .= "</td>";
$contents .= "</tr>";

$contents .= "</table>";
$contents .= "<br><center><input name=\"submit1\" type=\"submit\" value=\"" . $lang_module['save'] . "\" /></center>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>