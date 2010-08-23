<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );

/**
 * nv_admin_write_lang()
 * 
 * @param mixed $dirlang
 * @param mixed $idfile
 * @return error write file
 */
global $file_lang_tran_no_comp;
$file_lang_tran_no_comp = array();
$array_lang_no_check = array();

$array_lang_exit = array();
$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file`" );
while ( $row = $db->sql_fetch_assoc( $result ) )
{
    if ( substr( $row['Field'], 0, 7 ) == "author_" )
    {
        $array_lang_exit[] .= trim( substr( $row['Field'], 7, 2 ) );
    }
}

function nv_admin_write_lang ( $dirlang, $idfile )
{
    global $module_name, $db, $language_array, $global_config, $include_lang, $lang_module, $file_lang_tran_no_comp, $array_lang_exit, $array_lang_no_check;
    list( $module, $admin_file, $langtype, $author_lang ) = $db->sql_fetchrow( $db->sql_query( "SELECT `module`, `admin_file`, `langtype`, `author_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` WHERE `idfile` ='" . intval( $idfile ) . "'" ) );
    if ( ! empty( $dirlang ) and ! empty( $module ) )
    {
        if ( empty( $author_lang ) )
        {
            $array_translator = array();
            $array_translator['author'] = "";
            $array_translator['createdate'] = "";
            $array_translator['copyright'] = "";
            $array_translator['info'] = "";
            $array_translator['langtype'] = "lang_module";
        }
        else
        {
            eval( '$array_translator = ' . $author_lang . ';' );
        }
        
        $admin_file = ( intval( $admin_file ) == 1 ) ? 1 : 0;
        $include_lang = "";
        $modules_exit = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
        
        if ( in_array( $module, $modules_exit ) and $admin_file == 1 )
        {
            $include_lang = NV_ROOTDIR . "/modules/" . $module . "/language/admin_" . $dirlang . ".php";
        }
        elseif ( in_array( $module, $modules_exit ) and $admin_file == 0 )
        {
            $include_lang = NV_ROOTDIR . "/modules/" . $module . "/language/" . $dirlang . ".php";
        }
        elseif ( $module == "global" and $admin_file == 1 )
        {
            $include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/admin_" . $module . ".php";
        }
        elseif ( $module == "global" and $admin_file == 0 )
        {
            $include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/" . $module . ".php";
        }
        elseif ( $module == "install" and $admin_file == 0 )
        {
            $include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/" . $module . ".php";
        }
        else
        {
            $admin_file = 1;
            $include_lang = NV_ROOTDIR . "/language/" . $dirlang . "/admin_" . $module . ".php";
        }
        
        if ( $include_lang == "" )
        {
            return $lang_module['nv_error_write_module'] . " : " . $module;
        
        }
        else
        {
            if ( ! file_exists( $include_lang ) )
            {
                @file_put_contents( $include_lang, "", LOCK_EX );
            }
            if ( ! is_writable( $include_lang ) )
            {
                $errfile = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
                return $lang_module['nv_error_write_file'] . " : " . $errfile;
            }
            $content_lang_no_tran = "";
            $content_lang = "<?php\n\n";
            $content_lang .= "/**\n";
            $content_lang .= "* @Project NUKEVIET 3.0\n";
            $content_lang .= "* @Author VINADES.,JSC (contact@vinades.vn)\n";
            $content_lang .= "* @Copyright (C) 2010 VINADES.,JSC. All rights reserved\n";
            $content_lang .= "* @Language " . $language_array[$dirlang]['name'] . "\n";
            $content_lang .= "* @Createdate " . date( "M d, Y, h:i:s A" ) . "\n";
            $content_lang .= "*/\n";
            
            if ( $admin_file )
            {
                $content_lang .= "\n if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE')){\n";
            }
            else
            {
                $content_lang .= "\n if (!defined( 'NV_MAINFILE' )) {\n";
            }
            
            $content_lang .= " die('Stop!!!');\n";
            $content_lang .= "}\n";
            $content_lang .= "\n";
            
            $array_translator['info'] = ( isset( $array_translator['info'] ) ) ? $array_translator['info'] : "";
            
            if ( $dirlang != "vi" and $dirlang != "en" and $array_translator['info'] == "" )
            {
                $array_translator['info'] = "Language translated by http://translate.google.com";
            }
            
            $content_lang .= "\$lang_translator['author'] =\"$array_translator[author]\";\n";
            $content_lang .= "\$lang_translator['createdate'] =\"$array_translator[createdate]\";\n";
            $content_lang .= "\$lang_translator['copyright'] =\"$array_translator[copyright]\";\n";
            $content_lang .= "\$lang_translator['info'] =\"$array_translator[info]\";\n";
            $content_lang .= "\$lang_translator['langtype'] =\"$array_translator[langtype]\";\n";
            $content_lang .= "\n";
            $content_lang_no_check = "";
            
            if ( in_array( "vi", $array_lang_exit ) and in_array( "en", $array_lang_exit ) and $dirlang != "vi" and $dirlang != "en" )
            {
                $query = "SELECT `lang_key`, `lang_vi`, `lang_en`, `lang_" . $dirlang . "`, `update_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE `idfile`='" . $idfile . "' ORDER BY `id` ASC";
                $result = $db->sql_query( $query );
                while ( list( $lang_key, $lang_value_vi, $lang_value_en, $lang_value, $update_time ) = $db->sql_fetchrow( $result ) )
                {
                    if ( $lang_value != "" )
                    {
                        $lang_value = nv_unhtmlspecialchars( $lang_value );
                        $lang_value = str_replace( '$', '\$', $lang_value );
                        $lang_value = str_replace( '"', '\"', $lang_value );
                        $lang_value = nv_nl2br( $lang_value );
                        $lang_value = str_replace( '<br  />', '<br />', $lang_value );
                        
                        $content_temp = "\$" . $langtype . "['" . $lang_key . "'] = \"$lang_value\";\n";
                        $content_temp .= "/*\n";
                        if ( $dirlang != "vi" )
                        {
                            $lang_value_vi = nv_unhtmlspecialchars( $lang_value_vi );
                            $lang_value_vi = str_replace( '$', '\$', $lang_value_vi );
                            $lang_value_vi = str_replace( '"', '\"', $lang_value_vi );
                            $lang_value_vi = nv_nl2br( $lang_value_vi );
                            $lang_value_vi = str_replace( '<br  />', '<br />', $lang_value_vi );
                            $content_temp .= "\t vietnam:\t  " . $lang_value_vi . "\n";
                        }
                        
                        if ( $dirlang != "en" )
                        {
                            $lang_value_en = nv_unhtmlspecialchars( $lang_value_en );
                            $lang_value_en = str_replace( '$', '\$', $lang_value_en );
                            $lang_value_en = str_replace( '"', '\"', $lang_value_en );
                            $lang_value_en = nv_nl2br( $lang_value_en );
                            $lang_value_en = str_replace( '<br  />', '<br />', $lang_value_en );
                            $content_temp .= "\t english:\t  " . $lang_value_en . "\n";
                        }
                        $content_temp .= "*/\n\n";
                        if ( $update_time > 0 )
                        {
                            $content_lang .= $content_temp;
                        }
                        else
                        {
                            $content_lang_no_check .= $content_temp;
                        }
                    }
                }
                
                if ( ! empty( $content_lang_no_check ) )
                {
                    $content_lang .= "\n\n/*---------------------------------------- language untested ----------------------------------------------*/\n";
                    $content_lang .= $content_lang_no_check;
                    $array_lang_no_check[] = $include_lang;
                }
            }
            else
            {
                $query = "SELECT `lang_key`, `lang_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE `idfile`='" . $idfile . "' ORDER BY `id` ASC";
                $result = $db->sql_query( $query );
                while ( list( $lang_key, $lang_value ) = $db->sql_fetchrow( $result ) )
                {
                    if ( $lang_value != "" )
                    {
                        $lang_value = nv_unhtmlspecialchars( $lang_value );
                        $lang_value = str_replace( '$', '\$', $lang_value );
                        $lang_value = str_replace( '"', '\"', $lang_value );
                        $lang_value = nv_nl2br( $lang_value );
                        $lang_value = str_replace( '<br  />', '<br />', $lang_value );
                        $content_lang .= "\$" . $langtype . "['" . $lang_key . "'] = \"$lang_value\";\n";
                    }
                }
            }
            $content_lang .= "\n";
            $content_lang .= "?>";
            file_put_contents( $include_lang, $content_lang, LOCK_EX );
        }
        return "";
    }
    else
    {
        return $lang_module['nv_error_exit_module'] . " : " . $module;
    }
}

$include_lang = "";
$page_title = $language_array[$dirlang]['name'];
if ( $nv_Request->isset_request( 'idfile,checksess', 'get' ) and $nv_Request->get_string( 'checksess', 'get' ) == md5( $nv_Request->get_int( 'idfile', 'get' ) . session_id() ) )
{
    $idfile = $nv_Request->get_int( 'idfile', 'get' );
    nv_mkdir( NV_ROOTDIR . "/language/", $dirlang );
    $contents = nv_admin_write_lang( $dirlang, $idfile );
    if ( empty( $contents ) )
    {
        $include_lang = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
        $contents = $lang_module['nv_lang_wite_ok'] . ": " . $include_lang;
        $contents .= "<META HTTP-EQUIV=\"refresh\" content=\"5;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main\">";
    }
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
elseif ( $nv_Request->isset_request( 'checksess', 'get' ) and $nv_Request->get_string( 'checksess', 'get' ) == md5( "writeallfile" . session_id() ) )
{
    $dirlang = $nv_Request->get_string( 'dirlang', 'get', '' );
    if ( $dirlang != "" )
    {
        nv_mkdir( NV_ROOTDIR . "/language/", $dirlang );
        $query = "SELECT `idfile`, `author_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` ORDER BY `idfile` ASC";
        $result = $db->sql_query( $query );
        $contents = "";
        $array_filename = array();
        while ( list( $idfile, $author_lang ) = $db->sql_fetchrow( $result ) )
        {
            $contents = nv_admin_write_lang( $dirlang, $idfile );
            if ( ! empty( $contents ) )
            {
                break;
            }
            else
            {
                $array_filename[] = str_replace( NV_ROOTDIR, "", str_replace( '\\', '/', $include_lang ) );
            
            }
        }
        
        if ( empty( $contents ) )
        {
            $contents = "<br><br><p align=\"center\"><strong>" . $lang_module['nv_lang_wite_ok'] . "</strong></p>";
            $contents .= implode( "<br>", $array_filename );
            $array_lang_no_check = array_unique( $array_lang_no_check );
            //$contents .= "<br><br><b>file lang no check</b><br>";
            //$contents .= implode ( "<br>", $array_lang_no_check );
            $contents .= "<META HTTP-EQUIV=\"refresh\" content=\"10000;URL=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=setting\">";
        }
    }
    else
    {
        $contents = $lang_module['nv_error_write_file'];
    }
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
}
else
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&dirlang=" . $dirlang . "" );
    die();
}

?>