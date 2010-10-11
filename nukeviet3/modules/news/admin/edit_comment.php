<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['comment_edit_title'];
$cid = $nv_Request->get_int( 'cid', 'get' );
if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_comment', "id ".$cid , $admin_info['userid'] );
	$sql = "SELECT a.id, a.title, a.listcatid, a.alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_comments` b ON a.id=b.id WHERE b.cid='" . $cid . "'";
    
    list( $id, $title, $listcatid, $alias ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
    if ( $id > 0 )
    {
        $delete = $nv_Request->get_int( 'delete', 'post', 0 );
        if ( $delete )
        {
            $db->sql_query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_comments WHERE cid=' . $cid . '' );
        }
        else
        {
            $content = nv_nl2br(filter_text_textarea( 'content', '', NV_ALLOWED_HTML_TAGS ));
            $active = $nv_Request->get_int( 'active', 'post', 0 );
            $status = ( $status == 1 ) ? 1 : 0;
            $db->sql_query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_comments SET content=' . $db->dbescape( $content ) . ', status=' . $active . ' WHERE cid=' . $cid . '' );
        }
        
        // Cap nhat lai so luong comment duoc kich hoat
        $array_catid = explode( ",", $listcatid );
        list( $numf ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` where `id`= '" . $id . "' AND `status`=1" ) );
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
        $db->sql_query( $query );
        foreach ( $array_catid as $catid_i )
        {
            $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `hitscm`=" . $numf . " WHERE `id`=" . $id;
            $db->sql_query( $query );
        }
        // Het Cap nhat lai so luong comment duoc kich hoat
    }
    header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=comment' );
    die();
}
$contents = "<form action='' method='post'>";
$contents .= "<table class=\"tab1\" style='width:400px'>\n";
$contents .= "<thead>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['comment_edit_title'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</thead>\n";
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE cid=" . $cid . "";
$result = $db->sql_query( $sql );
$row = $db->sql_fetchrow( $result );
$contents .= "<tr>\n";
$contents .= "<td>\n";
$contents .= "<textarea name='content' style='width:600px;height:100px'>" . $row['content'] . "</textarea>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "<tbody class='second'>\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
$contents .= "<label><input type='checkbox' name='active' value='1' " . ( ( $row['status'] ) ? 'checked=checked' : '' ) . "/> " . $lang_module['comment_edit_active'] . "</label>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>\n";
$contents .= "<label><input type='checkbox' name='delete' value='1'/> " . $lang_module['comment_edit_delete'] . "</label>&nbsp;&nbsp;<input type='hidden' value='" . $cid . "' name='cid'/><input type='submit' name='submit' value='" . $lang_module['comment_delete_accept'] . "'/>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</table></form>\n";
$contents .= "
";
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>