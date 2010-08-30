<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$contents = "";

$download_config = initial_config_data();

$today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
$yesterday = $today - 86400;

/**
 * nv_getImageInfo()
 * 
 * @return
 */
function nv_getImageInfo ( $fileimage, $thumb_width )
{
    global $module_name;
    
    $imageinfo = array();
    if ( ! empty( $fileimage ) )
    {
        $img = substr( $fileimage, strlen( NV_BASE_SITEURL ) );
        $img2 = @getimagesize( NV_ROOTDIR . '/' . $img );
        if ( $img2 )
        {
            $imageinfo['orig_src'] = $imageinfo['src'] = $fileimage;
            $imageinfo['orig_width'] = $imageinfo['width'] = $img2[0];
            $imageinfo['orig_height'] = $imageinfo['height'] = $img2[1];
            
            if ( $imageinfo['width'] > $thumb_width )
            {
                $basename = basename( $img );
                if ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/' . $thumb_width . '_' . $basename ) )
                {
                    $img2 = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/' . $thumb_width . '_' . $basename );
                    
                    $imageinfo['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/' . $thumb_width . '_' . $basename;
                    $imageinfo['width'] = $img2[0];
                    $imageinfo['height'] = $img2[1];
                }
                else
                {
                    require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
                    
                    $image = new image( NV_ROOTDIR . '/' . $img, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                    $image->resizeXY( $thumb_width );
                    $image->save( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb', $thumb_width . '_' . $basename );
                    $image_info = $image->create_Image_info;
                    
                    $imageinfo['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/' . $thumb_width . '_' . $basename;
                    $imageinfo['width'] = $image_info['width'];
                    $imageinfo['height'] = $image_info['height'];
                }
            
            }
        }
    }
    
    return $imageinfo;
}

//rating
if ( $nv_Request->isset_request( 'rating', 'post' ) )
{
    if ( ! empty( $list_cats ) )
    {
        $in = implode( ",", array_keys( $list_cats ) );
        
        $rating = $nv_Request->get_string( 'rating', 'post', '' );
        
        unset( $m );
        if ( preg_match( "/^([0-9]+)\_([1-5]+)$/", $rating, $m ) )
        {
            $id = ( int )$m[1];
            $point = ( int )$m[2];
            
            if ( $id and ( $point > 0 and $point < 6 ) )
            {
                $query = "SELECT `rating_detail` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id . " AND `catid` IN (" . $in . ") AND `status`=1";
                $result = $db->sql_query( $query );
                $numrows = $db->sql_numrows( $result );
                
                if ( $numrows )
                {
                    $total = $click = 0;
                    list( $rating_detail ) = $db->sql_fetchrow( $result );
                    if ( ! empty( $rating_detail ) )
                    {
                        $rating_detail = explode( "|", $rating_detail );
                        $total = ( int )$rating_detail[0];
                        $click = ( int )$rating_detail[1];
                    }
                    
                    $flrt = $nv_Request->get_string( 'flrt', 'session', '' );
                    $flrt = ! empty( $flrt ) ? unserialize( $flrt ) : array();
                    
                    if ( $id and ! in_array( $id, $flrt ) )
                    {
                        $flrt[] = $id;
                        $flrt = serialize( $flrt );
                        $nv_Request->set_Session( 'flrt', $flrt );
                        
                        $total = $total + $point;
                        $click ++;
                        
                        $rating_detail = $total . "|" . $click;
                        
                        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `rating_detail`=" . $db->dbescape( $rating_detail ) . " WHERE `id`=" . $id;
                        $db->sql_query( $sql );
                    }
                    
                    if ( $total and $click )
                    {
                        $round = round( $total / $click );
                        $content = sprintf( $lang_module['rating_string'], $lang_module['file_rating' . $round], $total, $click );
                    }
                    else
                    {
                        $content = $lang_module['file_rating0'];
                    }
                    
                    die( $content );
                }
            }
        }
    }
    
    die( $lang_module['rating_error1'] );
}

//Xem chi tiet
if ( ! empty( $filealias ) )
{
    if ( empty( $list_cats ) )
    {
        $page_title = $module_info['custom_title'];
        
        include ( NV_ROOTDIR . "/includes/header.php" );
        echo nv_site_theme( $contents );
        include ( NV_ROOTDIR . "/includes/footer.php" );
        exit();
    }
    
    if ( empty( $filealias ) or ! preg_match( "/^([a-z0-9\-\_\.]+)$/i", $filealias ) )
    {
        Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    
    $in = implode( ",", array_keys( $list_cats ) );
    
    $query = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $filealias ) . " AND `catid` IN (" . $in . ") AND `status`=1";
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 )
    {
        Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
    
    $row = $db->sql_fetch_assoc( $result );
    
    $row['cattitle'] = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "\">" . $list_cats[$row['catid']]['title'] . "</a>";
    
    $row['uploadtime'] = ( int )$row['uploadtime'];
    if ( $row['uploadtime'] >= $today )
    {
        $row['uploadtime'] = $lang_module['today'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    elseif ( $row['uploadtime'] >= $yesterday )
    {
        $row['uploadtime'] = $lang_module['yesterday'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    else
    {
        $row['uploadtime'] = nv_date( "d/m/Y H:i", $row['uploadtime'] );
    }
    
    $row['updatetime'] = ( int )$row['updatetime'];
    if ( $row['updatetime'] >= $today )
    {
        $row['updatetime'] = $lang_module['today'] . ", " . date( "H:i", $row['updatetime'] );
    }
    elseif ( $row['updatetime'] >= $yesterday )
    {
        $row['updatetime'] = $lang_module['yesterday'] . ", " . date( "H:i", $row['updatetime'] );
    }
    else
    {
        $row['updatetime'] = nv_date( "d/m/Y H:i", $row['updatetime'] );
    }
    
    if ( defined( 'NV_IS_MODADMIN' ) and ! empty( $row['user_id'] ) and ! empty( $row['user_name'] ) )
    {
        $row['user_name'] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&amp;userid=" . $row['user_id'] . "\">" . $row['user_name'] . "</a>";
    }
    if ( empty( $row['user_name'] ) ) $row['user_name'] = $lang_module['unknown'];
    
    if ( ! empty( $row['author_name'] ) )
    {
        if ( ! empty( $row['author_email'] ) )
        {
            $row['author_name'] .= " (" . nv_EncodeEmail( $row['author_email'] ) . ")";
        }
    }
    else
    {
        $row['author_name'] = $lang_module['unknown'];
    }
    
    if ( ! empty( $row['author_url'] ) )
    {
        $row['author_url'] = "<a href=\"" . $row['author_url'] . "\" onclick=\"this.target='_blank'\">" . $row['author_url'] . "</a>";
    }
    else
    {
        $row['author_url'] = $lang_module['unknown'];
    }
    
    if ( empty( $row['description'] ) )
    {
        $row['description'] = $row['introtext'];
    }
    
    if ( empty( $row['version'] ) )
    {
        $row['version'] = $lang_module['unknown'];
    }
    
    if ( empty( $row['copyright'] ) )
    {
        $row['copyright'] = $lang_module['unknown'];
    }
    
    $row['catname'] = $list_cats[$row['catid']]['name'];
    
    $row['is_download_allow'] = $list_cats[$row['catid']]['is_download_allow'];
    
    $session_files = array();
    $session_files['fileupload'] = array();
    $session_files['linkdirect'] = array();
    
    if ( $row['is_download_allow'] )
    {
        if ( ! empty( $row['fileupload'] ) )
        {
            $fileupload = explode( "[NV]", $row['fileupload'] );
            $row['fileupload'] = array();
            
            $a = 1;
            $count_file = count( $fileupload );
            foreach ( $fileupload as $file )
            {
                if ( ! empty( $file ) )
                {
                    $file2 = substr( $file, strlen( NV_BASE_SITEURL ) );
                    if ( file_exists( NV_ROOTDIR . '/' . $file2 ) and ( $filesize = filesize( NV_ROOTDIR . '/' . $file2 ) ) != 0 )
                    {
                        $new_name = str_replace( "-", "_", $filealias ) . ( $count_file > 1 ? "_part" . str_pad( $a, 2, '0', STR_PAD_LEFT ) : "" ) . "." . nv_getextension( $file );
                        $row['fileupload'][] = array(  //
                            'link' => NV_BASE_SITEURL . "files/" . $new_name, //
'title' => $new_name  //
                        );
                        $session_files['fileupload'][$new_name] = array(  //
                            'src' => NV_ROOTDIR . '/' . $file2, //
'id' => $row['id']  //
                        );
                        
                        $a ++;
                    }
                }
            }
        }
        else
        {
            $row['fileupload'] = array();
        }
        
        if ( ! empty( $row['linkdirect'] ) )
        {
            $linkdirect = explode( "[NV]", $row['linkdirect'] );
            $row['linkdirect'] = array();
            
            foreach ( $linkdirect as $links )
            {
                if ( ! empty( $links ) )
                {
                    $links = explode( "<br />", $links );
                    
                    $host = "";
                    $scheme = "";
                    
                    foreach ( $links as $link )
                    {
                        if ( ! empty( $link ) and nv_is_url( $link ) )
                        {
                            if ( empty( $host ) )
                            {
                                $host = @parse_url( $link );
                                $scheme = $host['scheme'];
                                $host = $host['host'];
                                $host = preg_replace( "/^www\./", "", $host );
                                
                                $row['linkdirect'][$host] = array();
                            }
                            
                            $code = md5( $link );
                            $row['linkdirect'][$host][] = array(  //
                                'link' => $link, //
'code' => $code, //
'name' => strlen( $link ) > 70 ? $scheme . "://" . $host . "..." . substr( $link, - ( 70 - strlen( $scheme . "://" . $host ) ) ) : $link  //
                            );
                            $session_files['linkdirect'][$code] = array(  //
                                'link' => $link, //
'id' => $row['id']  //
                            );
                        }
                    }
                }
            }
        }
        else
        {
            $row['linkdirect'] = array();
        }
        
        $row['download_info'] = "";
    }
    else
    {
        $row['fileupload'] = array();
        $row['linkdirect'] = array();
        $session_files = array();
        
        if ( $list_cats[$row['catid']]['who_download'] == 2 )
        {
            $row['download_info'] = $lang_module['download_not_allow_info2'];
        }
        else
        {
            $row['download_info'] = sprintf( $lang_module['download_not_allow_info1'], NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users", NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" );
        }
    }
    
    $session_files = serialize( $session_files );
    $nv_Request->set_Session( 'session_files', $session_files );
    
    $row['filesize'] = ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : $lang_module['unknown'];
    
    $row['fileimage'] = nv_getImageInfo( $row['fileimage'], 400 );
    
    $dfile = $nv_Request->get_string( 'dfile', 'session', '' );
    
    $dfile = ! empty( $dfile ) ? unserialize( $dfile ) : array();
    
    if ( ! in_array( $row['id'], $dfile ) )
    {
        $dfile[] = $row['id'];
        $dfile = serialize( $dfile );
        $nv_Request->set_Session( 'dfile', $dfile );
        
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `view_hits`=view_hits+1 WHERE `id`=" . $row['id'];
        $db->sql_query( $sql );
        $row['view_hits'] ++;
    }
    
    $row['is_comment_allow'] = $row['comment_allow'] ? nv_set_allow( $row['who_comment'], $row['groups_comment'] ) : false;
    
    $row['rating_point'] = 0;
    if ( ! empty( $row['rating_detail'] ) )
    {
        $row['rating_detail'] = explode( "|", $row['rating_detail'] );
        if ( $row['rating_detail'][1] )
        {
            $row['rating_point'] = round( ( int )$row['rating_detail'][0] / ( int )$row['rating_detail'][1] );
        }
    }
    $row['rating_string'] = $lang_module['file_rating' . $row['rating_point']];
    if ( $row['rating_point'] )
    {
        $row['rating_string'] = $lang_module['file_rating_note3'] . ": " . $row['rating_string'];
    }
    
    $flrt = $nv_Request->get_string( 'flrt', 'session', '' );
    $flrt = ! empty( $flrt ) ? unserialize( $flrt ) : array();
    $row['rating_disabled'] = ! in_array( $row['id'], $flrt ) ? false : true;
    
    $row['edit_link'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'];
    $row['del_link'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
    
    $row['disabled'] = "";
    $row['comment_uname'] = "";
    $row['comment_uemail'] = "";
    $row['comment_subject'] = $lang_module['file_comment_re'] . ": " . $row['title'];
    if ( defined( 'NV_IS_USER' ) )
    {
        $row['disabled'] = " disabled=\"disabled\"";
        $row['comment_uname'] = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
        $row['comment_uemail'] = $user_info['email'];
    }
    
    $page_title = $row['title'];
    $key_words = $module_info['keywords'];
    $mod_title = $list_cats[$row['catid']]['name'];
    $description = $list_cats[$row['catid']]['description'];
    
    $contents = view_file( $row, $download_config, $mod_title );
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

//Xem theo chu de
if ( empty( $list_cats ) )
{
    $page_title = $module_info['custom_title'];
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;

$array = array();
$subcats = array();
$generate_page = "";

if ( empty( $catalias ) )
{
    $in = array_keys( $list_cats );
    $in = implode( ",", $in );
    $in = "`catid` IN (" . $in . ")";
}
elseif ( is_numeric( $catalias ) )
{
    $cat = intval( $catalias );
    
    if ( isset( $list_cats[$cat] ) )
    {
        $page_title = $list_cats[$cat]['title'];
        $mod_title = $list_cats[$cat]['name'];
        $description = $list_cats[$cat]['description'];
        $subcats = $list_cats[$cat]['subcats'];
        
        $in = "`catid`=" . $cat;
        $base_url .= "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$cat]['alias'];
    }
    else
    {
        Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
}
elseif ( $catid > 0 )
{
    $in = "";
    $c = $list_cats[$catid];
    $page_title = $c['title'];
    $mod_title = $c['name'];
    $description = $c['description'];
    $subcats = $c['subcats'];
    
    $in .= "`catid`=" . $c['id'];
    $base_url .= "&amp;" . NV_OP_VARIABLE . "=" . $catalias;
}
else
{
    Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
    exit();
}

$sql = "FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE " . $in . " AND `status`=1";

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 15;

$sql1 = "SELECT COUNT(*) " . $sql;
$result = $db->sql_query( $sql1 );
list( $all_page ) = $db->sql_fetchrow( $result );

if ( ! $all_page )
{
    if ( $nv_Request->isset_request( 'page', 'get' ) )
    {
        Header( "Location: " . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
        exit();
    }
}

$sql2 = " SELECT `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, `author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` ";
$sql2 .= $sql . " ORDER BY `uploadtime` DESC LIMIT " . $page . ", " . $per_page;

$result = $db->sql_query( $sql2 );

while ( $row = $db->sql_fetchrow( $result ) )
{
    $cattitle = "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "\">" . $list_cats[$row['catid']]['title'] . "</a>";
    $more_link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'];
    
    $uploadtime = ( int )$row['uploadtime'];
    if ( $uploadtime >= $today )
    {
        $uploadtime = $lang_module['today'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    elseif ( $uploadtime >= $yesterday )
    {
        $uploadtime = $lang_module['yesterday'] . ", " . date( "H:i", $row['uploadtime'] );
    }
    else
    {
        $uploadtime = nv_date( "d/m/Y H:i", $row['uploadtime'] );
    }
    
    $imageinfo = nv_getImageInfo( $row['fileimage'], 400 );
    
    $array[$row['id']] = array(  //
        'id' => ( int )$row['id'], //
'title' => $row['title'], //
'cattitle' => $cattitle, //
'introtext' => $row['introtext'], //
'uploadtime' => $uploadtime, //
'author_name' => $row['author_name'], //
'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
'fileimage' => $imageinfo, //
'view_hits' => ( int )$row['view_hits'], //
'download_hits' => ( int )$row['download_hits'], //
'more_link' => $more_link, //
'edit_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'], //
'del_link' => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  //
    );
    
    if ( $row['comment_allow'] )
    {
        $array[$row['id']]['comment_hits'] = ( int )$row['comment_hits'];
    }
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$subs = array();
if ( ! empty( $subcats ) )
{
    foreach ( $subcats as $sub )
    {
        $subs[] = array(  //
            'title' => "<a href=\"" . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$sub]['alias'] . "\">" . $list_cats[$sub]['title'] . "</a>", //
'description' => $list_cats[$sub]['description']  //
        );
    }
}

$contents = theme_main_download( $array, $download_config, $subs, $mod_title, $generate_page );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>