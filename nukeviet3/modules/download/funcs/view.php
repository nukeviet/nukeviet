<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */
if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$key_words = $module_info['keywords'];
$fileid = $nv_Request->get_int( 'id', 'get' );
$page = $nv_Request->get_int( 'page', 'get' );
$dow_err = '0';
$cap_err = '0';

$url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&op=';

$sql = " UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET view =view+1 WHERE id =" . $fileid;
$result = $db->sql_query( $sql );

$sql = " SELECT `id`, `userid`, `title`, `catid`, `description` , `introtext` , `uploadtime`, `author`, `authoremail`, `homepage`, `fileupload`, `version`, `linkdirect`, `filesize`, `fileimage`, `tags`, `active`,`copyright`,`view`,`download`,`comment` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE active=1 AND id =" . $fileid;
$result = $db->sql_query( $sql );

$data_content = array();
list( $fid, $fuserid, $ftitle, $fcatid, $fdescription, $fintrotext, $fuploadtime, $fauthor, $fauthoremail, $fhomepage, $ffileupload, $fversion, $flinkdirect, $ffilesize, $ffileimage, $ftags, $factive, $fcopyright, $view, $download, $comment ) = $db->sql_fetchrow( $result );
$data_content = array( 
    "id" => $fid, 'link_dow' => $url_link . "down&id=" . $fid, 'userid' => $fuserid, 'title' => $ftitle, 'description' => $fdescription, 'introtext' => $fintrotext, 'uploadtime' => $fuploadtime, 'author' => $fauthor, 'authoremail' => $fauthoremail, 'homepage' => $fhomepage, 'fileupload' => $ffileupload, 'version' => $fversion, 'linkdirect' => $flinkdirect, 'filesize' => $ffilesize, 'fileimage' => $ffileimage, 'tags' => $ftags, 'active' => $factive, 'copyright' => $fcopyright, 'view' => $view, 'download' => $download, 'comment' => $comment 
);

$page_title = $ftitle;

$sql = " SELECT `tid`, `lid`, `date`, `name`, `email`, `host_name`, `comment`, `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_comments` WHERE lid =" . $fid;
$result = $db->sql_query( $sql );

$data_comment = array();
while ( list( $ctid, $clid, $cdate, $cname, $cemail, $chost_name, $ccomment, $cstatus ) = $db->sql_fetchrow( $result ) )
{
    $data_comment[] = array( 
        'id' => $ctid, 'lid' => $clid, 'date' => $cdate, 'name' => $cname, 'email' => $cemail, 'host_name' => $chost_name, 'comment' => $ccomment, 'status' => $cstatus 
    );
}

$otherfile = array();
$result = $db->sql_query( "SELECT id, title,uploadtime FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE catid=" . $fcatid . " AND id!=" . $fileid . " AND active=1" );
while ( $other_file = $db->sql_fetchrow( $result ) )
{
    $other_file['uploadtime'] = date( 'd/m/Y h:i', $other_file['uploadtime'] );
    $other_file['url'] = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=view&id=" . $other_file['id'];
    $otherfile[] = $other_file;
}
$islink = 0;
global $configdownload, $user_info, $module_name;
if ( $nv_Request->get_int( 'atc', 'post' ) == '1' )
{
    $permission = false;
    if ( $configdownload['who_view1'] == 3 && nv_is_in_groups( $user_info['in_groups'], $configdownload['groups_view2'] ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view1'] == 0 )
    {
        $permission = true;
    }
    if ( $configdownload['who_view1'] == 1 && defined( 'NV_IS_USER' ) )
    {
        $permission = true;
    }
    if ( $configdownload['who_view1'] == 2 && defined( 'NV_IS_ADMIN' ) )
    {
        $permission = true;
    }
    if ( $permission )
    {
        $captcha = $nv_Request->get_string( 'captcha', 'post' );
        if ( ! nv_capcha_txt( $captcha, "downloadfile" ) && $configdownload['showcaptcha'] == 1 )
        {
            $cap_err = '1';
        }
        else
        {
            $path_filename = NV_DOCUMENT_ROOT . NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $ffileupload;
            if ( file_exists( $path_filename ) && is_file( $path_filename ) )
            {
                $_SESSION['down'] = 'ok';
                Header( "Location: " . $url_link . 'down&id=' . $fid );
                exit();
                $islink = 1;
            }
            else
            {
                $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET download=download+1 WHERE id=" . $fileid . "" );
                $islink = 2;
            }
            $cap_err = '0';
        }
    }
    else
    {
        $url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE . '=view&id=' . $fileid;
        $url_link1 = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode( $url_link );
        Header( "Location:" . $url_link1 );
        exit();
    }
}

$contents = call_user_func( "view_file", $data_content, $data_comment, $dow_err, $cap_err, $fileid, $page, $otherfile, $islink );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>