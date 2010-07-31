<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if (! defined ( 'NV_IS_MOD_DOWNLOAD' ))
	die ( 'Stop!!!' );
$catid = $nv_Request->get_int ( 'id', 'get' );
global $configdownload;

$url_link = NV_BASE_SITEURL . "?"  . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&".NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=";

$sql = "SELECT cid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE cid =".$catid;
$result = $db->sql_query ( $sql ); 
list ($cid, $title_par) =  $db->sql_fetchrow ( $result );

$per_page = $configdownload['numfile'];
$page = $nv_Request->get_int ( 'page', 'get', 0 );
$sql = " SELECT count(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE catid =".$cid." AND active=1 ORDER BY `id` DESC LIMIT ".$page.",".$per_page;
list($numcat) = $db->sql_fetchrow ( $db->sql_query ( $sql ) );
$all_page = ($numcat > 1) ? $numcat : 1;
$base_url = NV_BASE_SITEURL . "?"  . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&".NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=viewcat&id=".$catid;

$title_sub = array(); 
$sql = "SELECT cid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid =".$cid ." LIMIT 0,4";
$result = $db->sql_query ( $sql ); 
while ( list ($cid1, $title_par1) =  $db->sql_fetchrow ( $result ) )
{
	$title_sub[] = array('link'=> $url_link."viewcat&id=".$cid1, 'title' => $title_par1); 
}

$sql = " SELECT `id`, `userid`, `title`, `catid`, `description` , `introtext` , `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`,`copyright`,`view`,`download` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE catid =".$cid." AND active=1 ORDER BY `id` DESC LIMIT ".$page.",".$per_page;
$result = $db->sql_query ( $sql );

$data_content = array();
while ( list ($fid, $fuserid, $ftitle, $fcatid, $fdescription,$fintrotext, $fuploadtime, $fauthor, $fauthoremail, $fhomepage, $ffileupload, $fversion, $flinkdirect, $ffilesize, $ffileimage, $ftags, $factive,$fcopyright,$view,$download) =  $db->sql_fetchrow ( $result ) )
{
	$data_content[] = array('id'=> $fid,'link_view' => $url_link."view&id=".$fid ,'link_dow' => $url_link."down&id=".$fid,'userid' => $fuserid,'title' => $ftitle,'description' => $fdescription, 'introtext' => $fintrotext,'uploadtime' => $fuploadtime, 'author' => $fauthor,'authoremail' => $fauthoremail,'homepage' => $fhomepage,'fileupload' => $ffileupload,'version' => $fversion,'linkdirect' => $flinkdirect,'filesize' => $ffilesize, 'fileimage' => $ffileimage,'tags' => $ftags, 'active' => $factive, 'copyright' => $fcopyright,'view' => $view ,'download' => $download );
}
$list_pages = nv_generate_page ( $base_url, $all_page, $per_page, $page );

$contents = call_user_func("viewcat",$data_content,$title_par,$list_pages,$title_sub);
include (NV_ROOTDIR . "/includes/header.php");
echo nv_site_theme ( $contents );
include (NV_ROOTDIR . "/includes/footer.php");
?>