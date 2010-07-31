<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['question'];

//Sua cau hoi
if ( $nv_Request->isset_request( 'edit', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $qid = $nv_Request->get_int( 'qid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );

    if ( empty( $title ) )
    {
        die( "NO" );
    }
    $query = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `title`=" . $db->dbescape( $title ) . ", `edit_time`=" . NV_CURRENTTIME . " 
    WHERE `qid`=" . $qid . " AND `lang`='" . NV_LANG_DATA . "'";
    $db->sql_query( $query );
    if ( ! $db->sql_affectedrows() )
    {
        die( "NO" );
    }
    die( "OK" );
}

//Them cau hoi
if ( $nv_Request->isset_request( 'add', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $title = filter_text_input( 'title', 'post', '', 1 );
    if ( empty( $title ) )
    {
        die( "NO" );
    }

    $sql = "SELECT MAX(`weight`) FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "'";
    list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
    $weight = intval( $weight ) + 1;
    $query = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_question` (`qid`, `title`, `lang`, `weight`, `add_time`, `edit_time`) VALUES (
    NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( NV_LANG_DATA ) . ", " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
    if ( ! $db->sql_query_insert_id( $query ) )
    {
        die( "NO" );
    }
    die( "OK" );
}

//Chinh thu tu
if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $qid = $nv_Request->get_int( 'qid', 'post', 0 );
    $new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

    if ( empty( $qid ) ) die( "NO" );

    $query = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid . " AND `lang`='" . NV_LANG_DATA . "'";
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 ) die( 'NO' );

    $query = "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`!=" . $qid . " AND `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight++;
        if ( $weight == $new_vid ) $weight++;
        $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $weight . " WHERE `qid`=" . $row['qid'];
        $db->sql_query( $sql );
    }
    $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $new_vid . " WHERE `qid`=" . $qid;
    $db->sql_query( $sql );
    die( "OK" );
}

//Xoa cau hoi
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $qid = $nv_Request->get_int( 'qid', 'post', 0 );

    list( $qid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid ) );

    if ( $qid )
    {
        $query = "DELETE FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `qid`=" . $qid;
        if ( $db->sql_query( $query ) )
        {
            $db->sql_freeresult();
            nv_fix_question();
            die( "OK" );
        }
    }
    die( "NO" );
}

//Danh sach cau hoi
if ( $nv_Request->isset_request( 'qlist', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

    $contents = "";

    $sql = "SELECT * FROM `" . NV_USERS_GLOBALTABLE . "_question`  WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $num = $db->sql_numrows( $result );

    if ( $num )
    {
        $contents .= "<table class=\"tab1\">\n";
        $contents .= "<thead>\n";
        $contents .= "<tr align=\"center\">\n";
        $contents .= "<td style=\"width:60px;\">" . $lang_module['weight'] . "</td>\n";
        $contents .= "<td>" . $lang_module['question'] . "</td>\n";
        $contents .= "</tr>\n";
        $contents .= "</thead>\n";

        $a = 0;

        while ( $row = $db->sql_fetchrow( $result ) )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>\n";
            $contents .= "<td align=\"center\"><select id=\"id_weight_" . $row['qid'] . "\" onchange=\"nv_chang_question(" . $row['qid'] . ");\">\n";
            for ( $i = 1; $i <= $num; $i++ )
            {
                $contents .= "<option value=\"" . $i . "\"" . ( $i == $row['weight'] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
            }
            $contents .= "</select></td>\n";
            $contents .= "<td><input name=\"hidden_" . $row['qid'] . "\" id=\"hidden_" . $row['qid'] . "\" type=\"hidden\" value=\"" . $row['title'] . "\" />";
            $contents .= "<input type=\"text\" name=\"title_" . $row['qid'] . "\" id=\"title_" . $row['qid'] . "\" value=\"" . $row['title'] . "\" style=\"width:650px\" />\n";
            $contents .= "<span class=\"edit_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_save_title(" . $row['qid'] . ");\">" . $lang_module['save'] . "</a></span>\n";
            $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_question(" . $row['qid'] . ")\">" . $lang_global['delete'] . "</a></span></td>\n";
            $contents .= "</tr>\n";
            $contents .= "</tbody>\n";
            $a++;
        }

        $contents .= "</table>\n";
    }

    $contents .= "<div style=\"text-align:center; padding-top:15px;\"><strong>" . $lang_module['question'] . ":\n";
    $contents .= "<input style=\"width: 450px\" name=\"new_title\" id=\"new_title\" type=\"text\" maxlength=\"255\" />\n";
    $contents .= "<input name=\"Button1\" type=\"button\" value=\"" . $lang_module['addquestion'] . "\" onclick=\"nv_add_question();return;\" /></div>\n";

    include ( NV_ROOTDIR . "/includes/header.php" );
    echo $contents;
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit;
}

$contents = "";
$contents .= "<div id=\"module_show_list\"></div><br />\n";
$contents .= "<script type=\"text/javascript\">\n";
$contents .= "nv_show_list_question();\n";
$contents .= "</script>\n";

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>