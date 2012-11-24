<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3-6-2010 0:30
 */

if ( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

if ( empty( $list_cats ) )
{
    $page_title = $module_info['custom_title'];
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_site_theme( '' );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

$contents = "";

$download_config = nv_mod_down_config();

$today = mktime( 0, 0, 0, date( "n" ), date( "j" ), date( "Y" ) );
$yesterday = $today - 86400;

//rating
if ( $nv_Request->isset_request( 'rating', 'post' ) )
{
    $in = implode( ",", array_keys( $list_cats ) );
    
    $rating = $nv_Request->get_string( 'rating', 'post', '' );
    
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
                    ++$click;
                    
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
    
    die( $lang_module['rating_error1'] );
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// View cat
$new_page = 3;
$array_cats = array();
foreach ( $list_cats as $value )
{
    if ( empty( $value['parentid'] ) )
    {
        $catid_i = $value['id'];
        if ( empty( $value['subcats'] ) )
        {
            $in = "`catid`=" . $catid_i;
        }
        else
        {
            $in = $value['subcats'];
            $in[] = $catid_i;
            $in = implode( ",", $in );
            $in = "`catid` IN (" . $in . ")";
        }
        
        $sql = "SELECT SQL_CALC_FOUND_ROWS `id`, `catid`, `title`, `alias`, `introtext` , `uploadtime`, 
			`author_name`, `filesize`, `fileimage`, `view_hits`, `download_hits`, `comment_allow`, `comment_hits` 
			FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE " . $in . " AND `status`=1 
			ORDER BY `uploadtime` DESC LIMIT 0, " . $new_page;
        
        $result = $db->sql_query( $sql );
        $query = $db->sql_query( "SELECT FOUND_ROWS()" );
        list( $all_page ) = $db->sql_fetchrow( $query );
        if ( $all_page )
        {
            $array_item = array();
            while ( $row = $db->sql_fetchrow( $result ) )
            {
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
                
                $img = NV_UPLOADS_DIR . $row['fileimage'];
                $imageinfo = nv_ImageInfo( NV_ROOTDIR . '/' . $img, 300, true, NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb' );
                
                $array_item[$row['id']] = array(
                    'id' => ( int )$row['id'], //
					'title' => $row['title'], //
					'introtext' => $row['introtext'], //
					'uploadtime' => $uploadtime, //
					'author_name' => ! empty( $row['author_name'] ) ? $row['author_name'] : $lang_module['unknown'], //
					'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : "", //
					'fileimage' => $imageinfo, //
					'view_hits' => ( int )$row['view_hits'], //
					'download_hits' => ( int )$row['download_hits'], //
					'more_link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $list_cats[$row['catid']]['alias'] . "/" . $row['alias'], //
					'edit_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;edit=1&amp;id=" . ( int )$row['id'] : "", //
					'del_link' => ( defined( 'NV_IS_MODADMIN' ) ) ? NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name : "" 
                );

                if ( $row['comment_allow'] )
                {
                    $array_item[$row['id']]['comment_hits'] = ( int )$row['comment_hits'];
                }
            }
            
            $array_cats[$catid_i] = array();
            $array_cats[$catid_i]['id'] = $value['id'];
            $array_cats[$catid_i]['title'] = $value['title'];
            $array_cats[$catid_i]['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $value['alias'];
            $array_cats[$catid_i]['description'] = $list_cats[$value['id']]['description'];
            $array_cats[$catid_i]['subcats'] = $list_cats[$value['id']]['subcats'];
            $array_cats[$catid_i]['items'] = $array_item;
        }
    
    }
}

$contents = theme_main_download( $array_cats, $list_cats, $download_config );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>