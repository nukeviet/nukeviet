<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

global $module_data, $module_name, $module_file, $global_array_cat, $global_config, $lang_module;

$xtpl = new XTemplate( "block_newscenter.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'lang', $lang_module );

$sql = "SELECT id, catid, publtime, title, alias, hometext, homeimgthumb, homeimgfile FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `status`= 1 ORDER BY `publtime` DESC LIMIT 0 , 4";
$list = nv_db_cache( $sql, 'id', $module_name );

$i = 1;
foreach ( $list as $row )
{
    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $global_array_cat[$row['catid']]['alias'] . "/" . $row['alias'] . "-" . $row['id'];
    $row['hometext'] = nv_clean60( strip_tags( $row['hometext'] ), 360 );
    if ( $i == 1 )
    {
        $image = NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $row['homeimgfile'];
        
        if ( $row['homeimgfile'] != "" and file_exists( $image ) )
        {
            $width = 183;
            $height = 150;
            
            $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
            $imginfo = nv_is_image( $image );
            $basename = basename( $image );
            if ( $imginfo['width'] > $width or $imginfo['height'] > $height )
            {
                $basename = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', $module_name . '_' . $row['id'] . '_\1_' . $width . '-' . $height . '\2', $basename );
                if ( file_exists( NV_ROOTDIR . "/" . NV_TEMP_DIR . '/' . $basename ) )
                {
                    $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                }
                else
                {
                    require_once ( NV_ROOTDIR . "/includes/class/image.class.php" );
                    $_image = new image( $image, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                    $_image->resizeXY( $width, $height );
                    $_image->save( NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename );
                    if ( file_exists( NV_ROOTDIR . "/" . NV_TEMP_DIR . '/' . $basename ) )
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                    }
                }
            }
        }
        elseif ( nv_is_url( $row['homeimgfile'] ) )
        {
            $row['imgsource'] = $row['homeimgfile'];
        }
        else
        {
            $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
        }
        $xtpl->assign( 'main', $row );
        ++$i;
    }
    else
    {
        if ( ! empty( $row['homeimgthumb'] ) )
        {
            $array_img = explode( "|", $row['homeimgthumb'] );
        }
        else
        {
            $array_img = array( "", "" );
        }
        if ( $array_img[0] != "" and file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0] ) )
        {
            $row['imgsource'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $array_img[0];
        }
        elseif ( $row['homeimgfile'] != "" and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['homeimgfile'] ) )
        {
            $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['homeimgfile'];
        }
        elseif ( nv_is_url( $row['homeimgfile'] ) )
        {
            $row['imgsource'] = $row['homeimgfile'];
        }
        else
        {
            $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
        }
        $xtpl->assign( 'othernews', $row );
        $xtpl->parse( 'main.othernews' );
    }
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>