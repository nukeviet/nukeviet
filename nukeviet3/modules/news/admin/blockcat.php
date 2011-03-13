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
list( $bid, $title, $alias, $description, $keywords ) = array( 
    0, "", "", "", "" 
);

$savecat = $nv_Request->get_int( 'savecat', 'post', 0 );
if ( ! empty( $savecat ) )
{
    $bid = $nv_Request->get_int( 'bid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $keywords = filter_text_input( 'keywords', 'post', '', 1 );
    $alias = filter_text_input( 'alias', 'post', '' );
    $description = $nv_Request->get_string( 'description', 'post', '' );
    $description = nv_nl2br( nv_htmlspecialchars( strip_tags( $description ) ), '<br />' );
    $alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );
	
	if ( empty ( $title ) )
	{
		$error = $lang_module['error_name'];
	}
    elseif ( $bid == 0 )
    {
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat`" ) );
        $weight = intval( $weight ) + 1;
        $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` (`bid`, `adddefault`, `number`, `title`, `alias`, `description`, `image`, `thumbnail`, `weight`, `keywords`, `add_time`, `edit_time`) VALUES (NULL, 0, 4, " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $description ) . ", '', '', " . $db->dbescape( $weight ) . ", " . $db->dbescape( $keywords ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ))";
        if ( $db->sql_query_insert_id( $query ) )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_blockcat', " ", $admin_info['userid'] );
            $db->sql_freeresult();
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
        $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` SET `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `description`=" . $db->dbescape( $description ) . ", `keywords`= " . $db->dbescape( $keywords ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `bid` =" . $bid . "";
        $db->sql_query( $query );
        if ( $db->sql_affectedrows() > 0 )
        {
            nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_blockcat', "blockid " . $bid, $admin_info['userid'] );
            $db->sql_freeresult();
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

$bid = $nv_Request->get_int( 'bid', 'get', 0 );
if ( $bid > 0 )
{
    list( $bid, $title, $alias, $description, $keywords ) = $db->sql_fetchrow( $db->sql_query( "SELECT `bid`, `title`, `alias`, `description`, `keywords`  FROM `" . NV_PREFIXLANG . "_" . $module_data . "_block_cat` where `bid`=" . $bid . "" ) );
    $lang_module['add_block_cat'] = $lang_module['edit_block_cat'];
}

$xtpl = new XTemplate( "blockcat.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'BLOCK_CAT_LIST', nv_show_block_cat_list() );

$xtpl->assign( 'bid', $bid );
$xtpl->assign( 'title', $title );
$xtpl->assign( 'alias', $alias );
$xtpl->assign( 'keywords', $keywords );
$xtpl->assign( 'description', $description );

if ( ! empty( $error ) )
{
    $xtpl->assign( 'ERROR', $error );
    $xtpl->parse( 'main.error' );
}

if ( empty( $alias ) )
{
    $xtpl->parse( 'main.getalias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>