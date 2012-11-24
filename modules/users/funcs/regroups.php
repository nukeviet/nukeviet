<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 10/03/2010 10:51
 */

if ( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

$groups_list = nv_groups_list_pub();
$recomplete = false;

if ( $global_config['allowuserpublic'] == 0 )
{
    $contents = user_info_exit( $lang_module['no_act'] );
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
}
elseif ( empty( $groups_list ) )
{
    $contents = user_info_exit( $lang_module['no_set'] );
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true ) . "\" />";
}
else
{
    $groups = $in_group = $in = $gl = array();
    $sql = "SELECT `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $user_info['userid'];
    $result = $db->sql_query( $sql );
    list( $in_groups ) = $db->sql_fetchrow( $result );
    if ( $in_groups != "" ) $in_group = explode( ',', $in_groups );
    
	foreach ( $groups_list as $group_id => $grtl )
	{
		$groups[] = array( 'id' => $group_id, 'title' => $grtl, 'checked' => ( ! empty( $in_groups ) and in_array( $group_id, $in_group ) ) ? " checked=\"checked\"" : "" );
	}
    
    if ( $nv_Request->get_string( 'save', 'post' ) != "" )
    {
        $_user['in_groups'] = $nv_Request->get_typed_array( 'group', 'post', 'int' );
        $data_in_groups = ( ! empty( $_user['in_groups'] ) ) ? implode( ',', $_user['in_groups'] ) : '';
        
        foreach ( $groups_list as $key => $val ) $gl[] = $key;
        $sql = "SELECT `in_groups` FROM `" . NV_USERS_GLOBALTABLE . "` WHERE `userid`=" . $user_info['userid'];
        $result = $db->sql_query( $sql );
        list( $in_groups ) = $db->sql_fetchrow( $result );
        if ( $in_groups != "" ) $in_group = explode( ',', $in_groups );
        
        if ( ! empty( $in_group ) )
        {
            foreach ( $in_group as $g )
            {
                if ( in_array( $g, $gl ) == false )
                {
                    $in[] = $g;
                }
                
                $sql = "SELECT `users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $g . " AND `public`=1";
                $result = $db->sql_query( $sql );
                $numrows = $db->sql_numrows( $result );
                if ( $numrows > 0 )
                {
                    list( $u ) = $db->sql_fetchrow( $result );
                    $da = explode( ',', $u );
                    if ( in_array( $user_info['userid'], $da ) )
                    {
                        unset( $da[array_search( $user_info['userid'], $da )] );
                        $u = implode( ',', $da );
                        $sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users`=" . $db->dbescape_string( $u ) . " WHERE `group_id`=" . $g;
                        $db->sql_query( $sql );
                    }
                
                }
            }
        }
        
        foreach ( $_user['in_groups'] as $group_id_i )
        {
            $sql = "SELECT `users` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `group_id`=" . $group_id_i;
            $result = $db->sql_query( $sql );
            $numrows = $db->sql_numrows( $result );
            
            if ( $numrows )
            {
                $row_users = $db->sql_fetchrow( $result );
                $users = trim( $row_users['users'] );
                $users = ! empty( $users ) ? explode( ",", $users ) : array();
                $users = array_merge( $users, array( $user_info['userid'] ) );
                $users = array_unique( $users );
                sort( $users );
                $users = implode( ",", $users );
                
                $sql = "UPDATE `" . NV_GROUPS_GLOBALTABLE . "` SET `users`=" . $db->dbescape_string( $users ) . " WHERE `group_id`=" . $group_id_i;
                $db->sql_query( $sql );
            }
        }
        
        $us = array_merge( $in, $_user['in_groups'] );
        $us = array_unique( $us );
        sort( $us );
        $da_us = implode( ",", $us );
        
        $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "` SET `in_groups`=" . $db->dbescape( $da_us ) . " WHERE `userid`=" . $user_info['userid'];
        $db->sql_query( $sql );
        
        $recomplete = true;
    }
	
	$contents = nv_regroup_theme( $groups );
}
if ( $recomplete )
{
    $contents = user_info_exit( $lang_module['re_remove'] );
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op, true ) . "\" />";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>