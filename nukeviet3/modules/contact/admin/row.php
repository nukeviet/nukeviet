<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2010 VINADES. All rights reserved
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

if ( $id )
{
    $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( empty( $numrows ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=list_row" );
        die();
    }
    $frow = $db->sql_fetchrow( $result );
    define( 'IS_EDIT', true );
    $page_title = $frow['full_name'];
    $action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
}
else
{
    $page_title = $lang_module['add_row_title'];
    $action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
}

$sql = "SELECT t1.admin_id as id, t1.lev as level, t2.username as admin_login, t2.email as admin_email, t2.full_name as admin_fullname FROM 
`" . NV_AUTHORS_GLOBALTABLE . "` AS t1 INNER JOIN  `" . NV_USERS_GLOBALTABLE . "` AS t2 ON t1.admin_id  = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0";
$result = $db->sql_query( $sql );
$adms = array();
while ( $row = $db->sql_fetchrow( $result ) )
{
    $adms[$row['id']] = array( //
        'login' => $row['admin_login'], //
        'fullname' => $row['admin_fullname'], //
        'email' => $row['admin_email'], //
        'level' => intval( $row['level'] ) //
        );
}

$error = "";

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if ( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
    $full_name = filter_text_input( 'full_name', 'post', '', 1 );
    $phone = filter_text_input( 'phone', 'post', '', 1 );
    $fax = filter_text_input( 'fax', 'post', '', 1 );
    $email = filter_text_input( 'email', 'post', '', 1 );
    $note = nv_editor_filter_textarea( 'note', '', NV_ALLOWED_HTML_TAGS );

    $view_level = $nv_Request->get_array( 'view_level', 'post', array() );
    $reply_level = $nv_Request->get_array( 'reply_level', 'post', array() );
    $obt_level = $nv_Request->get_array( 'obt_level', 'post', array() );

    $check_valid_email = nv_check_valid_email( $email );

    $admins = array();

    if ( ! empty( $view_level ) )
    {
        foreach ( $view_level as $admid )
        {
            $admins[$admid]['view_level'] = 1;
            $admins[$admid]['reply_level'] = 0;
            $admins[$admid]['obt_level'] = 0;
        }
    }

    if ( ! empty( $reply_level ) )
    {
        foreach ( $reply_level as $admid )
        {
            $admins[$admid]['view_level'] = 1;
            $admins[$admid]['reply_level'] = 1;
            $admins[$admid]['obt_level'] = 0;
        }
    }

    if ( ! empty( $obt_level ) )
    {
        foreach ( $obt_level as $admid )
        {
            $admins[$admid]['view_level'] = 1;
            if ( ! isset( $admins[$admid]['reply_level'] ) ) $admins[$admid]['reply_level'] = 0;
            $admins[$admid]['obt_level'] = 1;
        }
    }

    if ( empty( $full_name ) )
    {
        $error = $lang_module['err_part_row_title'];
    } elseif ( ! empty( $email ) and ! empty( $check_valid_email ) )
    {
        $error = $check_valid_email;
    }
    else
    {
        $note = nv_editor_nl2br( $note );

        $admins_list = array();
        foreach ( $adms as $admid => $values )
        {
            if ( $values['level'] === 1 )
            {
                $obt_level = ( isset( $admins[$admid] ) ) ? $admins[$admid]['obt_level'] : 0;
                $admins_list[] = $admid . '/1/1/' . $obt_level;
            }
            else
            {
                if ( isset( $admins[$admid] ) )
                {
                    $admins_list[] = $admid . '/' . $admins[$admid]['view_level'] . '/' . $admins[$admid]['reply_level'] . '/' . $admins[$admid]['obt_level'];
                }
            }
        }
        $admins_list = implode( ";", $admins_list );

        if ( defined( 'IS_EDIT' ) )
        {
            $query = "UPDATE`" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET 
            `full_name`=" . $db->dbescape( $full_name ) . ", `phone` =  " . $db->dbescape( $phone ) . ", 
            `fax`=" . $db->dbescape( $fax ) . ", `email`=" . $db->dbescape( $email ) . ", 
            `note`=" . $db->dbescape( $note ) . ", `admins`=" . $db->dbescape( $admins_list ) . " WHERE `id` =" . $id;
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_row', "rowid ".$id, $admin_info['userid'] );
        }
        else
        {
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` VALUES (
            NULL, " . $db->dbescape( $full_name ) . ", " . $db->dbescape( $phone ) . ", " . $db->dbescape( $fax ) . ", 
            " . $db->dbescape( $email ) . ", " . $db->dbescape( $note ) . ", " . $db->dbescape( $admins_list ) . ", 1);";
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_row', " ", $admin_info['userid'] );
        }

        $db->sql_query( $query );
        
        nv_del_moduleCache( $module_name );

        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=list_row" );
        die();
    }
}
else
{
    if ( defined( 'IS_EDIT' ) )
    {
        $full_name = $frow['full_name'];
        $phone = $frow['phone'];
        $fax = $frow['fax'];
        $email = $frow['email'];
        $note = nv_editor_br2nl( $frow['note'] );

        $admins_list = $frow['admins'];
        $admins_list = ! empty( $admins_list ) ? array_map( "trim", explode( ";", $admins_list ) ) : array();

        $view_level = $reply_level = $obt_level = array();

        if ( ! empty( $admins_list ) )
        {
            foreach ( $admins_list as $l )
            {
                if ( preg_match( "/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i", $l ) )
                {
                    $l2 = array_map( "intval", explode( "/", $l ) );
                    $admid = intval( $l2[0] );
                    if ( isset( $adms[$admid] ) )
                    {
                        if ( $adms[$admid]['level'] === 1 )
                        {
                            $view_level[] = $admid;
                            $reply_level[] = $admid;
                            if ( isset( $l2[3] ) and $l2[3] === 1 )
                            {
                                $obt_level[] = $admid;
                            }
                        }
                        else
                        {
                            if ( isset( $l2[1] ) and $l2[1] === 1 )
                            {
                                $view_level[] = $admid;
                            }
                            if ( isset( $l2[2] ) and $l2[2] === 1 )
                            {
                                $reply_level[] = $admid;
                            }
                            if ( isset( $l2[3] ) and $l2[3] === 1 )
                            {
                                $obt_level[] = $admid;
                            }
                        }
                    }
                }
            }
        }
    }
    else
    {
        $full_name = $phone = $fax = $email = $note = "";
        $view_level = $reply_level = $obt_level = array();
        foreach ( $adms as $admid => $values )
        {
            if ( $values['level'] === 1 )
            {
                $view_level[] = $admid;
                $reply_level[] = $admid;
            }
        }
    }
}

if ( ! empty( $note ) ) $note = nv_htmlspecialchars( $note );

if ( ! empty( $error ) )
{
    $contents .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $contents .= "<blockquote class=\"error\"><span>" . $error . "</span></blockquote>\n";
    $contents .= "</div>\n";
    $contents .= "<div class=\"clear\"></div>\n";
}

$contents .= "<form action=\"" . $action . "\" method=\"post\">\n";
$contents .= "<input name=\"save\" type=\"hidden\" value=\"1\" />\n";

$contents .= "<table summary=\"\" style=\"margin-top:8px;margin-bottom:8px;\">\n";
$contents .= "<col valign=\"top\" width=\"150px\" />\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['part_row_title'] . ":</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"full_name\" id=\"full_name\" type=\"text\" value=\"" . $full_name . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_global['phonenumber'] . ":</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"phone\" id=\"phone\" type=\"text\" value=\"" . $phone . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>Fax:</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"fax\" id=\"fax\" type=\"text\" value=\"" . $fax . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_global['email'] . ":</td>\n";
$contents .= "<td><input style=\"width:400px\" name=\"email\" id=\"email\" type=\"text\" value=\"" . $email . "\" maxlength=\"255\" /></td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">" . $lang_module['note_row_title'] . ":</td>\n";
$contents .= "</tr>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan=\"2\">\n";
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( "note", '750px', '150px', $note );
}
else
{
    $contents .= "<textarea style=\"width:750px;height:150px\" name=\"note\" id=\"note\">" . $note . "</textarea>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</table>\n";
$contents .= "<br>\n";
$contents .= "<div style=\"margin-top:8px;margin-bottom:8px;\">" . $lang_module['list_admin_row_title'] . "</div>\n";
$contents .= "<table summary=\"\" class=\"tab1\">\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['username_admin_row_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['name_admin_row_title'] . "</td>\n";
$contents .= "<td>" . $lang_global['email'] . "</td>\n";
$contents .= "<td>" . $lang_module['admin_view_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['admin_reply_title'] . "</td>\n";
$contents .= "<td>" . $lang_module['admin_send2mail_title'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";

$a = 0;
foreach ( $adms as $admid => $values )
{
    $class = ( $a % 2 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $values['login'] . "</td>\n";
    $contents .= "<td>" . $values['fullname'] . "</td>\n";
    $contents .= "<td>" . $values['email'] . "</td>\n";
    $contents .= "<td><input type=\"checkbox\" name=\"view_level[]\" value=\"" . $admid . "\"" . ( ( $values['level'] === 1 or ( ! empty( $view_level ) and in_array( $admid, $view_level ) ) ) ? " checked=\"checked\"" :
        "" ) . "" . ( $values['level'] === 1 ? " disabled=\"disabled\"" : "" ) . " /></td>\n";
    $contents .= "<td><input type=\"checkbox\" name=\"reply_level[]\" value=\"" . $admid . "\"" . ( ( $values['level'] === 1 or ( ! empty( $reply_level ) and in_array( $admid, $reply_level ) ) ) ?
        " checked=\"checked\"" : "" ) . "" . ( $values['level'] === 1 ? " disabled=\"disabled\"" : "" ) . " /></td>\n";
    $contents .= "<td><input type=\"checkbox\" name=\"obt_level[]\" value=\"" . $admid . "\"" . ( ( ! empty( $obt_level ) and in_array( $admid, $obt_level ) ) ? " checked=\"checked\"" : "" ) . " /></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $a++;
}

$contents .= "</table>\n";
$contents .= "<br>\n";
$contents .= "<div style=\"text-align:center\"><input name=\"submit1\" type=\"submit\" value=\"" . $lang_global['submit'] . "\" /></div>\n";
$contents .= "</form>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>