<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES. All rights reserved
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_SEARCH' ) )
{
    die( 'Stop!!!' );
}

/**
 * main_theme()
 * 
 * @param mixed $key
 * @param mixed $checkss
 * @param mixed $array_modul
 * @param mixed $mod
 * @return
 */
function main_theme( $key, $checkss, $array_modul, $mod )
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $module_name, $my_head;

    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.validate.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\">\n";
    $my_head .= "$(document).ready(function(){
            $('#form_search').validate({
                submitHandler: function() { nv_send_search(" . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . "); },
                rules: {
                    q: {
                    required: true,
                    rangelength: [" . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . "]
                    }
                }
			});
          });";
    $my_head .= "  </script>\n";

    $xtpl = new XTemplate( "form.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );

    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
    $xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
    $xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
    $xtpl->assign( 'MODULE_NAME', $module_name );
    $xtpl->assign( 'BASE_URL_SITE', NV_BASE_SITEURL . "index.php" );
    $xtpl->assign( 'SEARCH_QUERY', $key );
    $xtpl->assign( 'CHECKSS', $checkss );
    $xtpl->assign( 'MY_DOMAIN', NV_MY_DOMAIN );
    $xtpl->assign( 'NV_MIN_SEARCH_LENGTH', NV_MIN_SEARCH_LENGTH );
    $xtpl->assign( 'NV_MAX_SEARCH_LENGTH', NV_MAX_SEARCH_LENGTH );
    if ( ! empty( $array_modul ) )
    {
        foreach ( $array_modul as $m_name => $m_info )
        {
            $m_info['value'] = $m_name;
            $m_info['selected'] = ( $m_name == $mod ) ? " selected=\"selected\"" : "";
            $xtpl->assign( 'MOD', $m_info );
            $xtpl->parse( 'main.select_option' );
        }
    }

    if ( ! empty( $key ) )
    {
        $xtpl->parse( 'main.is_key' );
    }
    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * result_theme()
 * 
 * @param mixed $result_array
 * @param mixed $mod
 * @param mixed $mod_custom_title
 * @param mixed $key
 * @param mixed $ss
 * @param mixed $is_generate_page
 * @param mixed $pages
 * @param mixed $limit
 * @param mixed $all_page
 * @return
 */
function result_theme( $result_array, $mod, $mod_custom_title, $key, $ss, $is_generate_page, $pages, $limit, $all_page )
{
    global $module_info, $module_file, $global_config, $lang_global, $lang_module, $db, $module_name;
    $xtpl = new XTemplate( "result.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'HIDDEN_KEY', $key );
    $xtpl->assign( 'SEARCH_RESULT_NUM', $all_page );
    $xtpl->assign( 'MODULE_CUSTOM_TITLE', $mod_custom_title );

    foreach ( $result_array as $result )
    {
        $xtpl->assign( 'RESULT', $result );
        $xtpl->parse( 'main.result' );
    }

    $base_url = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=adv&amp;search_query=" . rawurlencode( $key ) . "&amp;search_mod=" . $mod . "&amp;search_ss=" . $ss;

    if ( $is_generate_page )
    {
        $generate_page = nv_generate_page( $base_url, $all_page, $limit, $pages, true, true, 'nv_urldecode_ajax', 'search_result' );
        if ( ! empty( $generate_page ) )
        {
            $xtpl->assign( 'GENERATE_PAGE', $generate_page );
            $xtpl->parse( 'main.generate_page' );
        }
    }
    else
    {
        if ( $all_page > $limit )
        {
            $xtpl->assign( 'MORE', "nv_search_viewall('" . $mod . "', " . NV_MIN_SEARCH_LENGTH . ", " . NV_MAX_SEARCH_LENGTH . ")" );
            $xtpl->parse( 'main.more' );
        }
    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

?>