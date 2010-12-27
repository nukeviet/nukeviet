<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 17:30
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$mod = filter_text_input( 'mod', 'post' );
if ( empty( $mod ) or ! preg_match( $global_config['check_module'], $mod ) ) die( "NO_" . $mod );

$query = "SELECT `in_menu` FROM `" . NV_MODULES_TABLE . "` WHERE `title`=" . $db->dbescape( $mod );
$result = $db->sql_query( $query );
$numrows = $db->sql_numrows( $result );
if ( $numrows != 1 )
{
    die( 'NO_' . $mod );
}

$row = $db->sql_fetchrow( $result );
$in_menu = $row['in_menu'] ? 0 : 1;
$sql = "UPDATE `" . NV_MODULES_TABLE . "` SET `in_menu`=" . $in_menu . " WHERE `title`=" . $db->dbescape( $mod );
$db->sql_query( $sql );
nv_del_moduleCache( 'modules' );
include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $mod;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>