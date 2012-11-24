<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['weblink_config'];

$submit = $nv_Request->get_string( 'submit', 'post' );
$data_content = array();
$error = 0;

if ( ! empty( $submit ) )
{
    $sort = ( $nv_Request->get_string( 'sort', 'post' ) == 'asc' ) ? 'asc' : 'des';
    $sortoption = nv_htmlspecialchars( $nv_Request->get_string( 'sortoption', 'post', 'byid' ) );
    $showlinkimage = $nv_Request->get_int( 'showlinkimage', 'post', 0 );
    $imgwidth = ( $nv_Request->get_int( 'imgwidth', 'post' ) >= 0 ) ? $nv_Request->get_int( 'imgwidth', 'post' ) : 100;
    $imgheight = ( $nv_Request->get_int( 'imgheight', 'post' ) >= 0 ) ? $nv_Request->get_int( 'imgheight', 'post' ) : 75;
    $per_page = ( $nv_Request->get_int( 'per_page', 'post' ) >= 0 ) ? $nv_Request->get_int( 'per_page', 'post' ) : 10;
	
    $sql = array();
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $sort . "' WHERE name='sort'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $sortoption . "' WHERE name='sortoption'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $showlinkimage . "' WHERE name='showlinkimage'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $imgwidth . "' WHERE name='imgwidth'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $imgheight . "' WHERE name='imgheight'";
    $sql[] = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_config` SET value='" . $per_page . "' WHERE name='per_page'";
    
	foreach ( $sql as $value )
    {
        if ( ! $db->sql_query( $value ) )
        {
            $error = 1;
            break;
        }
    }
}

$sql = "SELECT `name`, `value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
$result = $db->sql_query( $sql );
while ( $row = $db->sql_fetchrow( $result ) )
{
    $data_content[$row['name']] = $row['value'];
}

// Set data adv
$data_content['asc'] = $data_content['des'] = "";
( $data_content['sort'] == "asc" ) ? $data_content['asc'] = 'checked' : $data_content['des'] = 'checked';
$data_content['byid'] = ( $data_content['sortoption'] == 'byid' ) ? ' checked' : '';
$data_content['byrand'] = ( $data_content['sortoption'] == 'byrand' ) ? ' checked' : '';
$data_content['bytime'] = ( $data_content['sortoption'] == 'bytime' ) ? ' checked' : '';
$data_content['byhit'] = ( $data_content['sortoption'] == 'byhit' ) ? ' checked' : '';
$data_content['ck_showlinkimage'] = ( $data_content['showlinkimage'] == 1 ) ? ' checked' : '';

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data_content );

if ( ! empty( $submit ) )
{
    $msg_error = ( $error == 0 ) ? $lang_module['weblink_config_success'] : $lang_module['weblink_config_unsuccess'];
    $xtpl->assign( 'error', $msg_error );
    $xtpl->assign( 'redirect', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    $xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>