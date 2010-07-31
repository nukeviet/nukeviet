<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$id_cat =  $nv_Request->get_int( 'id', 'get',0);
$sql = "SELECT cid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid =" . $id_cat . " AND active = 1 ORDER BY cid DESC";
$result = $db->sql_query( $sql );
$data_content_par = array();
$data_content_chid = array();
$url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&op=';

while ( list( $cid, $title ) = $db->sql_fetchrow( $result ) )
{
    $sql2 = "SELECT cid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_categories` WHERE parentid =" . $cid . " ORDER BY cid DESC LIMIT 5";
    $result2 = $db->sql_query( $sql2 );
    $data_sub = array();
    while ( list( $cid2, $title2 ) = $db->sql_fetchrow( $result2 ) )
    {
        $data_sub[] = array( 
            'title' => $title2, 'link' => $url_link . "viewcat&id=" . $cid2 
        );
    }
    $data_content_chid[$title] = $data_sub;
    ///////////////////////////////////////////////////////////////////////////////
    $sql3 = " SELECT `id`, `userid`, `title`, `catid`, `description` , `introtext` , `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`,`copyright`,`view`,`download` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE catid =" . $cid . " AND active=1 ORDER BY `id` DESC LIMIT 3 ";
    $result3 = $db->sql_query( $sql3 );
    $data_content = array();
    while ( list( $fid, $fuserid, $ftitle, $fcatid, $fdescription, $fintrotext, $fuploadtime, $fauthor, $fauthoremail, $fhomepage, $ffileupload, $fversion, $flinkdirect, $ffilesize, $ffileimage, $ftags, $factive, $fcopyright, $view, $download ) = $db->sql_fetchrow( $result3 ) )
    {
        $data_content[] = array( 
            'id' => $fid, 'link_view' => $url_link . "view&id=" . $fid, 'link_dow' => $url_link . "down&id=" . $fid, 'userid' => $fuserid, 'title' => $ftitle, 'description' => $fdescription, 'introtext' => $fintrotext, 'uploadtime' => $fuploadtime, 'author' => $fauthor, 'authoremail' => $fauthoremail, 'homepage' => $fhomepage, 'fileupload' => $ffileupload, 'version' => $fversion, 'linkdirect' => $flinkdirect, 'filesize' => $ffilesize, 'fileimage' => $ffileimage, 'tags' => $ftags, 'active' => $factive, 'copyright' => $fcopyright, 'view' => $view, 'download' => $download 
        );
    }
    $data_content_par[$title] = array( 
        'content' => $data_content, 'link' => $url_link . "viewcat&id=" . $cid 
    );
}
$contents = theme_main_download( $data_content_par, $data_content_chid );
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>