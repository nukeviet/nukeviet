<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 06, 2011, 06:31:13 AM
 */

if ( ! defined( 'NV_IS_MOD_LAWS' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_law_block_search' ) )
{
    function nv_law_block_search ()
    {
        global $my_head, $lang_module, $module_info, $module_file, $nv_laws_listsubject, $nv_laws_listarea, $nv_laws_listcat, $module_name, $nv_Request, $module_data;
		
		if ( empty( $my_head ) or ! preg_match( "/\/popcalendar\.js[^>]+>/", $my_head ) )
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
		
        $xtpl = new XTemplate( "block_search.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
        $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
        $xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
        $xtpl->assign( 'TEMPLATE', $module_info['template'] );
        $xtpl->assign( 'MODULE_FILE', $module_file );
        $xtpl->assign( 'MODULE_NAME', $module_name );
        $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
        $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
        $xtpl->assign( 'FORM_ACTION', NV_BASE_SITEURL . "index.php?" );
		
		$skey = nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, NV_MAX_SEARCH_LENGTH);
		
		$sfrom = nv_substr( $nv_Request->get_title( 'sfrom', 'get', '', 1 ), 0, 10);
		$sto = nv_substr( $nv_Request->get_title( 'sto', 'get', '', 1 ), 0, 10);
		
		$xtpl->assign( 'FROM', $sfrom );
		$xtpl->assign( 'TO', $sto );
		
		$sarea = $nv_Request->get_int( 'area', 'get', 0 );
		$scat = $nv_Request->get_int( 'cat', 'get', 0 );
		$ssubject = $nv_Request->get_int( 'subject', 'get', 0 );
		$sstatus = $nv_Request->get_int( 'status', 'get', 0 );
		$ssigner = $nv_Request->get_int( 'signer', 'get', 0 );

		$nv_laws_listarea = array( 0 => array( "id" => 0, "title" => $lang_module['search_area'], "alias" => "" ) ) + $nv_laws_listarea;
		
		foreach( $nv_laws_listarea as $area )
		{
			$xtpl->assign( 'KEY', $area['id'] );	
			$xtpl->assign( 'TITLE', $area['title'] );
			
			$xtpl->assign( 'SELECTED', $area['id'] == $sarea ? " selected=\"selected\"" : "" );			
			$xtpl->parse( 'main.area' );
		}
		
		$nv_laws_listcat = array( 0 => array( "id" => 0, "title" => $lang_module['search_cat'], "alias" => "" ) ) + $nv_laws_listcat;
		
		foreach( $nv_laws_listcat as $area )
		{
			$xtpl->assign( 'KEY', $area['id'] );	
			$xtpl->assign( 'TITLE', $area['title'] );
			
			$xtpl->assign( 'SELECTED', $area['id'] == $scat ? " selected=\"selected\"" : "" );			
			$xtpl->parse( 'main.cat' );
		}
		
		$nv_laws_listsubject = array( 0 => array( "id" => 0, "title" => $lang_module['search_subject'], "alias" => "" ) ) + $nv_laws_listsubject;
		
		foreach( $nv_laws_listsubject as $area )
		{
			$xtpl->assign( 'KEY', $area['id'] );	
			$xtpl->assign( 'TITLE', $area['title'] );
			
			$xtpl->assign( 'SELECTED', $area['id'] == $ssubject ? " selected=\"selected\"" : "" );			
			$xtpl->parse( 'main.subject' );
		}
		
		$nv_list_status = array();
		$nv_list_status[] = array( "id" => 0, "title" => $lang_module['s_status_all'], "selected" => "" );
		$nv_list_status[] = array( "id" => 1, "title" => $lang_module['s_status_1'], "selected" => 1 == $sstatus ? " selected=\"selected\"" : "" );
		$nv_list_status[] = array( "id" => 2, "title" => $lang_module['s_status_2'], "selected" => 2 == $sstatus ? " selected=\"selected\"" : "" );
		
		foreach( $nv_list_status as $status )
		{
			$xtpl->assign( 'status', $status );	
			$xtpl->parse( 'main.status' );
		}
		
		$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_signer` ORDER BY `title` ASC";
		$list = array( 0 => array( "id" => 0, "title" => $lang_module['s_signer_all'], "alias" => "" ) ) + nv_db_cache( $sql, 'id', $module_name );
		foreach ( $list as $row )
		{
			$xtpl->assign( 'KEY', $row['id'] );	
			$xtpl->assign( 'TITLE', $row['title'] );
			
			$xtpl->assign( 'SELECTED', $row['id'] == $ssigner ? " selected=\"selected\"" : "" );			
			$xtpl->parse( 'main.signer' );
		}
		
        $xtpl->assign( 'Q', $skey );
		
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}

$content = nv_law_block_search();

?>