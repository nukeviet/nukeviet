<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (! defined ( 'NV_IS_MOD_RSS' ))
	die ( 'Stop!!!' );
$rssarray = array ();
$result2 = $db->sql_query ( "SELECT catid, parentid, title, alias FROM " . NV_PREFIXLANG . "_weblinks_cat ORDER BY weight" );
while ( list ( $catid, $parentid, $title, $alias ) = $db->sql_fetchrow ( $result2 ) ) {
	$numsubcat = $db->sql_numrows ( $db->sql_query ( 'SELECT catid FROM ' . NV_PREFIXLANG . '_weblinks_cat WHERE parentid=' . $catid . '' ) );
	$resultsubcat = $db->sql_query ( 'SELECT catid FROM ' . NV_PREFIXLANG . '_weblinks_cat WHERE parentid="' . $catid . '"' );
	$subcatid = array ();
	while ( list ( $cid ) = $db->sql_fetchrow ( $resultsubcat ) ) {
		$subcatid [] = $cid;
	}
	$subcatid = implode ( ',', $subcatid );
	$rssarray [$catid] = array ('catid' => $catid, 'parentid' => $parentid, 'title' => $title, 'alias' => $alias, 'numsubcat' => $numsubcat, 'subcatid' => $subcatid, 'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_title . "&" . NV_OP_VARIABLE . "=rss&catid=" . $catid );
}
?>