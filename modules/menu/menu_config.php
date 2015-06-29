<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 21-04-2011 11:17
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_block_config_menu( $module, $data_block, $lang_block )
{
	$html = '';
	$html .= "<tr>";
	$html .= "	<td>" . $lang_block['menu'] . "</td>";
	$html .= "	<td><select name=\"menuid\" class=\"w300 form-control\">\n";

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_menu ORDER BY id DESC";
	$list = nv_db_cache( $sql, 'id', $module );
	foreach( $list as $l )
	{
		$sel = ( $data_block['menuid'] == $l['id'] ) ? ' selected' : '';
		$html .= "<option value=\"" . $l['id'] . "\" " . $sel . ">" . $l['title'] . "</option>\n";
	}

	$html .= "	</select></td>\n";
	$html .= "</tr>";
	$html .= "<tr>";
	$html .= "<td>";
	$html .= $lang_block['title_length'];
	$html .= "</td>";
	$html .= "<td>";
	$html .= "<input type=\"text\" class=\"form-control w100\" name=\"config_title_length\" value=\"" . $data_block['title_length'] . "\"/>";
	$html .= "</td>";
	$html .= "</tr>";

	return $html;
}

/**
 * nv_block_config_menu_submit()
 *
 * @param mixed $module
 * @param mixed $lang_block
 * @return
 */
function nv_block_config_menu_submit( $module, $lang_block )
{
	global $nv_Request;
	$return = array();
	$return['error'] = array();
	$return['config'] = array();
	$return['config']['menuid'] = $nv_Request->get_int( 'menuid', 'post', 0 );
	$return['config']['title_length'] = $nv_Request->get_int( 'config_title_length', 'post', 24 );
	return $return;
}