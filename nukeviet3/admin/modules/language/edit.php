<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_LANG' ) ) die( 'Stop!!!' );
$select_options = array();

if ( $nv_Request->isset_request( 'idfile,savedata', 'post' ) and $nv_Request->get_string( 'savedata', 'post' ) == md5( session_id() ) )
{
    $numberfile = 0;
    $idfile = $nv_Request->get_int( 'idfile', 'post', 0 );
    $dirlang = filter_text_input( 'dirlang', 'post', '' );
    $lang_translator = $nv_Request->get_array( 'pozauthor', 'post', array() );
    $lang_translator_save = array();
    $langtype = isset( $lang_translator['langtype'] ) ? strip_tags( $lang_translator['langtype'] ) : "lang_module";
    $lang_translator_save['author'] = isset( $lang_translator['author'] ) ? nv_htmlspecialchars( strip_tags( $lang_translator['author'] ) ) : "VINADES.,JSC (contact@vinades.vn)";
    $lang_translator_save['createdate'] = isset( $lang_translator['createdate'] ) ? nv_htmlspecialchars( strip_tags( $lang_translator['createdate'] ) ) : date( "d/m/Y, H:i" );
    $lang_translator_save['copyright'] = isset( $lang_translator['copyright'] ) ? nv_htmlspecialchars( strip_tags( $lang_translator['copyright'] ) ) : "@Copyright (C) 2010 VINADES.,JSC. All rights reserved";
    $lang_translator_save['info'] = isset( $lang_translator['info'] ) ? nv_htmlspecialchars( strip_tags( $lang_translator['info'] ) ) : "";
    $lang_translator_save['langtype'] = $langtype;
    //$author = base64_encode( serialize( $lang_translator_save ) );
    $author = var_export( $lang_translator_save, true );
    
    $db->sql_query( "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "_file` SET `author_" . $dirlang . "`='" . $author . "' WHERE `idfile`=" . $idfile . "" );
    
    $pozlang = $nv_Request->get_array( 'pozlang', 'post', array() );
    if ( ! empty( $pozlang ) )
    {
        foreach ( $pozlang as $id => $lang_value )
        {
            $id = intval( $id );
            $lang_value = trim( strip_tags( $lang_value, NV_ALLOWED_HTML_LANG ) );
            $db->sql_query( "UPDATE `" . NV_LANGUAGE_GLOBALTABLE . "` SET `lang_" . $dirlang . "`='" . mysql_real_escape_string( $lang_value ) . "' WHERE `id`='" . $id . "'" );
        }
    }
    
    $pozlangkey = $nv_Request->get_array( 'pozlangkey', 'post', array() );
    $pozlangval = $nv_Request->get_array( 'pozlangval', 'post', array() );
    
    for ( $i = 0; $i < sizeof( $pozlangkey ); ++ $i )
    {
        $lang_key = strip_tags( $pozlangkey[$i] );
        $lang_value = strip_tags( $pozlangval[$i], NV_ALLOWED_HTML_LANG );
        if ( $lang_key != "" and $lang_value != "" )
        {
            $lang_value = nv_nl2br( $lang_value );
            $lang_value = str_replace( '<br  />', '<br />', $lang_value );
            $sql = "INSERT INTO `" . NV_LANGUAGE_GLOBALTABLE . "` (`id`, `idfile`, `lang_key`, `lang_" . $dirlang . "`) VALUES (NULL, '" . $idfile . "', '" . mysql_real_escape_string( $lang_key ) . "', '" . mysql_real_escape_string( $lang_value ) . "')";
            $db->sql_query_insert_id( $sql );
        }
    }
    
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&dirlang=" . $dirlang . "" );
    die();

}

$dirlang = filter_text_input( 'dirlang', 'get', '' );
$page_title = $lang_module['nv_admin_edit'] . ' -> ' . $language_array[$dirlang]['name'];

if ( $nv_Request->isset_request( 'idfile,checksess', 'get' ) and $nv_Request->get_string( 'checksess', 'get' ) == md5( $nv_Request->get_int( 'idfile', 'get' ) . session_id() ) )
{
    $idfile = $nv_Request->get_int( 'idfile', 'get' );
    list( $idfile, $module, $admin_file, $langtype, $author_lang ) = $db->sql_fetchrow( $db->sql_query( "SELECT `idfile`, `module`, `admin_file`, `langtype`, `author_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "_file` WHERE `idfile` ='" . $idfile . "'" ) );
    if ( ! empty( $dirlang ) and ! empty( $module ) )
    {
        if ( empty( $author_lang ) )
        {
            $array_translator = array();
            $array_translator['author'] = "";
            $array_translator['createdate'] = "";
            $array_translator['copyright'] = "";
            $array_translator['info'] = "";
            $array_translator['langtype'] = "";
        }
        else
        {
            eval( '$array_translator = ' . $author_lang . ';' );
        }
        //$array_translator = unserialize( base64_decode( $author_lang ) );
        $i = 1;
        $contents .= "<div class=\"quote\" style=\"width:98%;\">\n";
        $contents .= "<blockquote><span>" . $lang_module['nv_lang_note_edit'] . " : " . ALLOWED_HTML_LANG . "</span></blockquote>\n";
        $contents .= "</div>\n";
        $contents .= "<div class=\"clear\"></div>\n";
        
        $contents .= "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
        $contents .= "<input type=\"hidden\" name =\"pozauthor[langtype]\"value=\"" . $array_translator['langtype'] . "\" />";
        $contents .= "<table summary=\"\" class=\"tab1\">\n";
        $contents .= "<thead>";
        $contents .= "<tr>";
        $contents .= "<td>" . $lang_module['nv_lang_nb'] . "</td>";
        $contents .= "<td>" . $lang_module['nv_lang_key'] . "</td>";
        $contents .= "<td>" . $lang_module['nv_lang_value'] . "</td>";
        $contents .= "</tr>";
        $contents .= "</thead>";
        foreach ( $array_translator as $lang_key => $lang_value )
        {
            if ( $lang_key != "langtype" )
            {
                $i ++;
                $class = ( $i % 2 ) ? " class=\"second\"" : "";
                $contents .= "<tbody" . $class . ">\n";
                $contents .= "<tr>";
                $contents .= "<td></td>";
                $contents .= "<td>" . $lang_key . "</td>";
                $contents .= "<td><input type=\"text\" value='" . nv_htmlspecialchars( $lang_value ) . "' name=\"pozauthor[" . $lang_key . "]\" size=\"90\"/></td>";
                $contents .= "</tr>";
                $contents .= "</tbody>";
            }
        }
        for ( $a = 1; $a <= 2; $a ++ )
        {
            $i ++;
            $class = ( $i % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>";
            $contents .= "<td align=\"center\">" . $a . "</td>";
            $contents .= "<td align=\"right\"><input type=\"text\" value=\"\" name=\"pozlangkey[" . $a . "]\" size=\"10\" /></td>";
            $contents .= "<td align=\"left\"><input type=\"text\" value=\"\" name=\"pozlangval[" . $a . "]\" size=\"90\" /></td>";
            $contents .= "</tr>";
            $contents .= "</tbody>";
        }
        
        $query = "SELECT `id`, `lang_key`, `lang_" . $dirlang . "` FROM `" . NV_LANGUAGE_GLOBALTABLE . "` WHERE `idfile`='" . $idfile . "' ORDER BY `id` ASC";
        $result = $db->sql_query( $query );
        while ( list( $id, $lang_key, $lang_value ) = $db->sql_fetchrow( $result ) )
        {
            $i ++;
            $class = ( $i % 2 ) ? " class=\"second\"" : "";
            $contents .= "<tbody" . $class . ">\n";
            $contents .= "<tr>";
            $contents .= "<td align=\"center\">" . $i . "</td>";
            $contents .= "<td align=\"right\">" . $lang_key . "</td>";
            $contents .= "<td align=\"left\"><input type=\"text\" value=\"" . nv_htmlspecialchars( $lang_value ) . "\" name=\"pozlang[" . $id . "]\" size=\"90\" /></td>";
            $contents .= "</tr>";
            $contents .= "</tbody>";
        }
        $contents .= "</table>";
        $contents .= "<br>";
        $contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
        $contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
        $contents .= "<input type=\"hidden\" name =\"idfile\"value=\"" . $idfile . "\" />";
        $contents .= "<input type=\"hidden\" name =\"dirlang\"value=\"" . $dirlang . "\" />";
        $contents .= "<input type=\"hidden\" name =\"savedata\" value=\"" . md5( session_id() ) . "\" />";
        $contents .= "<center><input type=\"submit\" value=\"" . $lang_module['nv_admin_edit_save'] . "\" /></center>";
        $contents .= "</form>";
        $contents .= "<br>";
    }
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>