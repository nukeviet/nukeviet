<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$page_title = $lang_module['voting_list'];
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `vid` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
if ( $num > 0 )
{
    $contents .= "<table class=\"tab1\">\n";
    $contents .= "<thead>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_id'] . "</td>\n";
    $contents .= "<td>" . $lang_module['voting_title'] . "</td>\n";
    $contents .= "<td>" . $lang_module['voting_stat'] . "</td>\n";
    $contents .= "<td>" . $lang_module['voting_active'] . "</td>\n";
    $contents .= "<td>" . $lang_module['voting_func'] . "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</thead>\n";
    $a = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $message = ( $row['act'] == 1 ) ? $lang_module['voting_yes'] : $lang_module['voting_no'];
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $sql1 = "SELECT SUM(hitstotal) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `vid`='" . $row['vid'] . "'";
        $result1 = $db->sql_query( $sql1 );
        $totalvote = $db->sql_fetchrow( $result1 );
        
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td align=\"center\">" . $row['vid'] . "</td>\n";
        $contents .= "<td>" . $row['question'] . "</td>\n";
        $contents .= "<td>" . $totalvote[0] . $lang_module['voting_totalcm'] . "</td>\n";
        $contents .= "<td align=\"center\">" . $message . "</td>\n";
        $contents .= "<td align=\"center\"><span class=\"edit_icon\"><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content&amp;vid=" . $row['vid'] . "\">" . $lang_global['edit'] . "</a></span>\n";
        $contents .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_del_content(" . $row['vid'] . ", '" . md5( $row['vid'] . session_id() ) . "')\">" . $lang_global['delete'] . "</a></span></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
        $a ++;
    }
    $contents .= "</table>\n";
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=content" );
    die();
}
?>