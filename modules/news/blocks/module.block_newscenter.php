<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_news_block_newscenter' ) )
{
    function nv_block_config_news_newscenter( $module, $data_block, $lang_block )
    {
        $html = '<tr>';
        $html .= '<td>' . $lang_block['showtooltip'] . '</td>';
        $html .= '<td>';
        $html .= '<input type="checkbox" value="1" name="config_showtooltip" ' . ( $data_block['showtooltip'] == 1 ? 'checked="checked"' : '' ) . ' /><br /><br />';
        $tooltip_position = array(
            'top' => $lang_block['tooltip_position_top'],
            'bottom' => $lang_block['tooltip_position_bottom'],
            'left' => $lang_block['tooltip_position_left'],
            'right' => $lang_block['tooltip_position_right'] );
        $html .= '<span class="text-middle pull-left">' . $lang_block['tooltip_position'] . '&nbsp;</span><select name="config_tooltip_position" class="form-control w100 pull-left">';
        foreach ( $tooltip_position as $key => $value )
        {
            $html .= '<option value="' . $key . '" ' . ( $data_block['tooltip_position'] == $key ? 'selected="selected"' : '' ) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        $html .= '&nbsp;<span class="text-middle pull-left">' . $lang_block['tooltip_length'] . '&nbsp;</span><input type="text" class="form-control w100 pull-left" name="config_tooltip_length" size="5" value="' . $data_block['tooltip_length'] . '"/>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '	<td>' . $lang_block['width'] . '</td>';
        $html .= '	<td><input type="width" name="config_width" class="form-control w100 pull-left" value="' . $data_block['width'] . '"/>';
        $html .= '	<span class="text-middle pull-left">&nbsp; ' . $lang_block['height'] . '&nbsp; </span>';
        $html .= '	<input type="height" name="config_height" class="form-control w100 pull-left" value="' . $data_block['height'] . '"/></td>';
        $html .= '</tr>';
        return $html;
    }

    function nv_block_config_news_newscenter_submit( $module, $lang_block )
    {
        global $nv_Request;
        $return = array();
        $return['error'] = array();
        $return['config'] = array();
        $return['config']['showtooltip'] = $nv_Request->get_int( 'config_showtooltip', 'post', 0 );
        $return['config']['tooltip_position'] = $nv_Request->get_title( 'config_tooltip_position', 'post', 0 );
        $return['config']['tooltip_length'] = $nv_Request->get_int( 'config_tooltip_length', 'post', 0 );
        $return['config']['width'] = $nv_Request->get_int( 'config_width', 'post', '' );
        $return['config']['height'] = $nv_Request->get_int( 'config_height', 'post', '' );
        return $return;
    }

    function nv_news_block_newscenter( $block_config )
    {
        global $module_data, $module_name, $module_file, $module_upload, $global_array_cat, $global_config, $lang_module, $db, $module_config, $module_info;

        $db->sqlreset()->select( 'id, catid, publtime, title, alias, hometext, homeimgthumb, homeimgfile' )->from( NV_PREFIXLANG . '_' . $module_data . '_rows' )->where( 'status= 1' )->order( 'publtime DESC' )->limit( 5 );
        $list = nv_db_cache( $db->sql(), 'id', $module_name );
        $list = array_values( $list );
        if ( ! empty( $list ) )
        {
            $xtpl = new XTemplate( 'block_newscenter.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
            $xtpl->assign( 'lang', $lang_module );
            $xtpl->assign( 'TOOLTIP_POSITION', $block_config['tooltip_position'] );

            foreach ( $list as $i => $row )
            {
                $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_cat[$row['catid']]['alias'] . '/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'];
                $row['hometext'] = nv_clean60( strip_tags( $row['hometext'] ), 360 );
                $row['titleclean60'] = nv_clean60( $row['title'], 60 );

                if ( $i == 0 )
                {
                    $width = isset( $block_config['width'] ) ? $block_config['width'] : 400;
                    $height = isset( $block_config['height'] ) ? $block_config['height'] : 268;

                    if ( $row['homeimgfile'] != '' and ( $imginfo = nv_is_image( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'] ) ) != array() )
                    {
                        $image = NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];

                        if ( $imginfo['width'] <= $width and $imginfo['height'] <= $height )
                        {
                            $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                            $row['width'] = $imginfo['width'];
                        }
                        else
                        {
                            $basename = preg_replace( '/(.*)(\.[a-z]+)$/i', $module_name . '_' . $row['id'] . '_\1_' . $width . '-' . $height . '\2', basename( $image ) );
                            if ( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename ) )
                            {
                            	$imginfo = nv_is_image( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename );
                                $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                                $row['width'] = $imginfo['width'];
                            }
                            else
                            {
                                $_image = new image( $image, NV_MAX_WIDTH, NV_MAX_HEIGHT );
                                $_image->resizeXY( $width, $height );
                                $_image->save( NV_ROOTDIR . '/' . NV_TEMP_DIR, $basename );
                                if ( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $basename ) )
                                {
                                    $row['imgsource'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $basename;
                                    $row['width'] = $_image->create_Image_info['width'];
                                }
                            }
                        }
                    }
                    elseif ( nv_is_url( $row['homeimgfile'] ) )
                    {
                        $row['imgsource'] = $row['homeimgfile'];
                        $row['width'] = $width;
                    }
                    elseif ( ! empty( $module_config[$module_name]['show_no_image'] ) )
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
                        $row['width'] = $width;
                    }
                    else
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
                        $row['width'] = $width;
                    }
                    $xtpl->assign( 'main', $row );
                }
                else
                {
                    if ( $row['homeimgthumb'] == 1 )
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    }
                    elseif ( $row['homeimgthumb'] == 2 )
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['homeimgfile'];
                    }
                    elseif ( $row['homeimgthumb'] == 3 )
                    {
                        $row['imgsource'] = $row['homeimgfile'];
                    }
                    elseif ( ! empty( $module_config[$module_name]['show_no_image'] ) )
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . $module_config[$module_name]['show_no_image'];
                    }
                    else
                    {
                        $row['imgsource'] = NV_BASE_SITEURL . 'themes/' . $global_config['site_theme'] . '/images/no_image.gif';
                    }

                    $row['hometext'] = nv_clean60( $row['hometext'], $block_config['tooltip_length'], true );

                    $xtpl->assign( 'othernews', $row );

                    if ( ! $block_config['showtooltip'] )
                    {
                        $xtpl->assign( 'TITLE', 'title="' . $row['title'] . '"' );
                    }

                    if ( $block_config['showtooltip'] )
                    {
                        $xtpl->parse( 'main.othernews.tooltip' );
                    }

                    $xtpl->parse( 'main.othernews' );
                }
            }

            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    $module = $block_config['module'];
    $content = nv_news_block_newscenter( $block_config );
}