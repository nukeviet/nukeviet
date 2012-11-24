<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$my_head = "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
$my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

/**
 * nv_save_file_banip()
 * 
 * @return
 */
function nv_save_file_banip()
{
    global $db, $db_config;
	
    $content_config_site = "";
    $content_config_admin = "";
	
    $sql = "SELECT `ip`, `mask`, `area`, `begintime`, `endtime` FROM `" . $db_config['prefix'] . "_banip`";
    $result = $db->sql_query( $sql );
	
    while ( list( $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
    {
        $dbendtime = intval( $dbendtime );
        $dbarea = intval( $dbarea );
		
        if ( $dbendtime == 0 or $dbendtime > NV_CURRENTTIME )
        {
            switch ( $dbmask )
            {
                case 3:
                    $ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$/";
                    break;
                case 2:
                    $ip_mask = "/\.[0-9]{1,3}.[0-9]{1,3}$/";
                    break;
                case 1:
                    $ip_mask = "/\.[0-9]{1,3}$/";
                    break;
                default:
                    $ip_mask = "//";
            }
			
            if ( $dbarea == 1 or $dbarea == 3 )
            {
                $content_config_site .= "\$array_banip_site['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            }
			
            if ( $dbarea == 2 or $dbarea == 3 )
            {
                $content_config_admin .= "\$array_banip_admin['" . $dbip . "'] = array( 'mask' => \"" . $ip_mask . "\", 'begintime' => " . $dbbegintime . ", 'endtime' => " . $dbendtime . " );\n";
            }
        }
    }
    
	if( ! $content_config_site and ! $content_config_admin )
	{
		nv_deletefile( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php" );
		return true;
	}
	
    $content_config = "<?php\n\n";
    $content_config .= NV_FILEHEAD . "\n\n";
    $content_config .= "if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );\n\n";
    $content_config .= "\$array_banip_site = array();\n";
    $content_config .= $content_config_site;
    $content_config .= "\n";
    $content_config .= "\$array_banip_admin = array();\n";
    $content_config .= $content_config_admin;
    $content_config .= "\n";
    $content_config .= "?>";
    
    $write =  file_put_contents( NV_ROOTDIR . "/" . NV_DATADIR . "/banip.php", $content_config, LOCK_EX );
	
	if( $write === false ) return $content_config;
	
	return true;
}

$xtpl = new XTemplate( "banip.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );

$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );

$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$error = array();
$contents = "";

$page_title = $lang_module['banip'];
$cid = $nv_Request->get_int( 'id', 'get' );
$del = $nv_Request->get_int( 'del', 'get' );

if ( ! empty( $del ) and ! empty( $cid ) )
{
    $db->sql_query( "DELETE FROM `" . $db_config['prefix'] . "_banip` WHERE id=" . $cid );
    nv_save_file_banip();
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $cid = $nv_Request->get_int( 'cid', 'post', 0 );
    $ip = filter_text_input( 'ip', 'post', '', 1 );
    $area = $nv_Request->get_int( 'area', 'post', 0 );
    $mask = $nv_Request->get_int( 'mask', 'post', 0 );
    $begintime = filter_text_input( 'begintime', 'post', 0, 1 );
    $endtime = filter_text_input( 'endtime', 'post', 0, 1 );
    
    if ( empty( $ip ) || ! $ips->nv_validip( $ip ) )
    {
        $error[] = $lang_module['banip_error_validip'];
    }
	
    if ( empty( $area ) )
    {
        $error[] = $lang_module['banip_error_area'];
    }
	
    if ( ! empty( $begintime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $begintime, $m ) )
    {
        $begintime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $begintime = NV_CURRENTTIME;
    }
	
    if ( ! empty( $endtime ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $endtime, $m ) )
    {
        $endtime = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $endtime = 0;
    }
	
    $notice = filter_text_input( 'notice', 'post', '', 1 );
	
    if ( empty( $error ) )
    {
        if ( $cid > 0 )
        {
            $db->sql_query( "UPDATE `" . $db_config['prefix'] . "_banip` SET `ip`=" . $db->dbescape( $ip ) . ", `mask`=" . $db->dbescape( $mask ) . ",`area`=" . $area . ",`begintime`=" . $begintime . ", `endtime`=" . $endtime . ", `notice`=" . $db->dbescape( $notice ) . "  WHERE `id`=" . $cid . "" );
        }
        else
        {
            $db->sql_query( "REPLACE INTO `" . $db_config['prefix'] . "_banip` VALUES (NULL, " . $db->dbescape( $ip ) . "," . $db->dbescape( $mask ) . ",$area,$begintime, $endtime," . $db->dbescape( $notice ) . " )" );
        }
		
        $save = nv_save_file_banip();
		
		if( $save !== true )
		{
			$xtpl->assign( 'MESSAGE', sprintf( $lang_module['banip_error_write'], NV_DATADIR, NV_DATADIR ) );
			$xtpl->assign( 'CODE', str_replace( array( "\n", "\t" ), array( "<br />", "&nbsp;&nbsp;&nbsp;&nbsp;" ), nv_htmlspecialchars( $save ) ) );
			$xtpl->parse( 'main.manual_save' );
		}
		else
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
			die();
		}
    }
    else
    {		
		$xtpl->assign( 'ERROR', implode( '<br/>', $error ) );
		$xtpl->parse( 'main.error' );
    }
}
else
{
    $id = $ip = $mask = $area = $begintime = $endtime = $notice = '';
}

$mask_text_array = array();
$mask_text_array[0] = "255.255.255.255";
$mask_text_array[3] = "255.255.255.xxx";
$mask_text_array[2] = "255.255.xxx.xxx";
$mask_text_array[1] = "255.xxx.xxx.xxx";

$banip_area_array = array();
$banip_area_array[0] = $lang_module['banip_area_select'];
$banip_area_array[1] = $lang_module['banip_area_front'];
$banip_area_array[2] = $lang_module['banip_area_admin'];
$banip_area_array[3] = $lang_module['banip_area_both'];

$sql = "SELECT `id`, `ip`, `mask`, `area`, `begintime`, `endtime` FROM `" . $db_config['prefix'] . "_banip` ORDER BY `ip` DESC";
$result = $db->sql_query( $sql );

if ( $db->sql_numrows( $result ) )
{    
    while ( list( $dbid, $dbip, $dbmask, $dbarea, $dbbegintime, $dbendtime ) = $db->sql_fetchrow( $result ) )
    {
		$xtpl->assign( 'ROW', array(
			'class' => ++ $i % 2 ? ' class="second"' : '',
			'dbip' => $dbip,
			'dbmask' => $mask_text_array[$dbmask],
			'dbarea' => $banip_area_array[$dbarea],
			'dbbegintime' => ! empty( $dbbegintime ) ? date( 'd.m.Y', $dbbegintime ) : '',
			'dbendtime' => ! empty( $dbendtime ) ? date( 'd.m.Y', $dbendtime ) : $lang_module['banip_nolimit'],
			'url_edit' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=banip&id=" . $dbid,
			'url_delete' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=banip&del=1&id=" . $dbid,
		) );
		
		$xtpl->parse( 'main.listip.loop' );
    }
	
	$xtpl->parse( 'main.listip' );
}

if ( ! empty( $cid ) )
{
    list( $id, $ip, $mask, $area, $begintime, $endtime, $notice ) = $db->sql_fetchrow( $db->sql_query( "SELECT `id`, `ip`, `mask`, `area`, `begintime`, `endtime`, `notice` FROM `" . $db_config['prefix'] . "_banip` WHERE `id`=$cid" ) );
    $lang_module['banip_add'] = $lang_module['banip_edit'];
}

$xtpl->assign( 'MASK_TEXT_ARRAY', $mask_text_array );
$xtpl->assign( 'BANIP_AREA_ARRAY', $banip_area_array );

$xtpl->assign( 'DATA', array(
	'cid' => $cid,
	'ip' => $ip,
	'selected3' => ( $mask == 3 ) ? ' selected="selected"' : '',
	'selected2' => ( $mask == 2 ) ? ' selected="selected"' : '',
	'selected1' => ( $mask == 1 ) ? ' selected="selected"' : '',
	'selected_area_1' => ( $area == 1 ) ? ' selected="selected"' : '',
	'selected_area_2' => ( $area == 2 ) ? ' selected="selected"' : '',
	'selected_area_3' => ( $area == 3 ) ? ' selected="selected"' : '',
	'begintime' => ! empty( $begintime ) ? date( 'd.m.Y', $begintime ) : '',
	'endtime' => ! empty( $endtime ) ? date( 'd.m.Y', $endtime ) : '',
	'endtime' => $notice,
) );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>