<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

/**
 * check_url()
 * 
 * @param mixed $id
 * @param mixed $url
 * @return
 */
function check_url ( $id, $url )
{
    global $db, $module_data;
    $sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` != '" . $id . "' AND `url` = '" . $url . "'";
    list( $numurl ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
    $msg = ( $numurl > 0 ) ? false : true;
    return $msg;
}

/**
 * check_title()
 * 
 * @param mixed $title
 * @return
 */
function check_title ( $title )
{
    global $db, $module_data;
    $sql = 'SELECT `title` FROM `' . NV_PREFIXLANG . '_' . $module_data . '_rows` WHERE `title` = "' . $title . '"';
    $numtitle = $db->sql_numrows( $db->sql_query( $sql ) );
    $msg = ( $numtitle > 0 ) ? false : true;
    return $msg;
}

$data_content = array( 
    "id" => "", "catid" => "", "title" => "", "alias" => "", "url" => "", "urlimg" => "", "description" => "", "add_time" => "", "edit_time" => "", "hits_total" => "", "status" => 1 
);

$error = "";
$id = $nv_Request->get_int( 'id', 'post,get', 0 );
$submit = $nv_Request->get_string( 'submit', 'post' );
if ( ! empty( $submit ) )
{
    $error = 0;
    $catid = $nv_Request->get_int( 'catid', 'post', 0 );
    $title = filter_text_input( 'title', 'post', '', 1 );
    $alias = filter_text_input( 'alias', 'post', '', 1 );
    $alias = ( $alias == "" ) ? change_alias( $title ) : change_alias( $alias );
    $url = filter_text_input( 'url', 'post', '' );
    $image = filter_text_input( 'image', 'post', '' );
	
    if ( ! nv_is_url( $image ) and file_exists( NV_DOCUMENT_ROOT . $image ) )
    {
        $lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" );
        if ( substr( $image, 0, $lu ) == NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" )
        {
            $image = substr( $image, $lu );
        }
    }
	
    if ( ! empty( $url ) )
    {
        if ( ! preg_match( "#^(http|https|ftp|gopher)\:\/\/#", $url ) )
        {
            $url = "http://" . $url;
        }
    }
	
    $admin_phone = "";
    $admin_email = "";
    $note = "";
    $description = filter_text_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
    $description = ( defined( 'NV_EDITOR' ) ) ? nv_editor_nl2br( $description ) : nv_nl2br( $description, '<br />' );
    
    $status = ( $nv_Request->get_int( 'status', 'post' ) == 1 ) ? 1 : 0;
    // check url
    if ( empty( $url ) || ! nv_is_url( $url ) || ! check_url( $id, $url ) || ! nv_check_url( $url ) )
    {
        $error = $lang_module['error_url'];
    }
    elseif ( empty( $title ) )
    {
        $error = $lang_module['error_title'];
    }
    elseif ( strip_tags( $description ) == "" )
    {
        $error = $lang_module['error_description'];
    }
    else
    {
        if ( $id > 0 )
        {
            $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `catid`=" . $catid . ", `title`=" . $db->dbescape( $title ) . ", `alias` =  " . $db->dbescape( $alias ) . ", `url` =  " . $db->dbescape( $url ) . ", `urlimg` =  " . $db->dbescape( $image ) . ", `description`=" . $db->dbescape( $description ) . ", `edit_time` = UNIX_TIMESTAMP(), `status`=" . $status . " WHERE `id` =" . $id;
            $db->sql_query( $sql );
            if ( $db->sql_affectedrows() > 0 )
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weblink_edit_link'], $title, $admin_info['userid'] );
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
                die();
            }
            else
            {
                $error = $lang_module['errorsave'];
            }
            $db->sql_freeresult();
        }
        else
        {
            $sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `catid`, `title`, `alias`, `url`, `urlimg`, `admin_phone`, `admin_email`, `note`, `description`, `add_time`, `edit_time`, `hits_total`, `status`) 
            VALUES (NULL, '" . $catid . "', " . $db->dbescape( $title ) . ", " . $db->dbescape( $alias ) . ", " . $db->dbescape( $url ) . ", " . $db->dbescape( $image ) . ", '" . $admin_phone . "', '" . $admin_email . "', " . $db->dbescape( $note ) . ", " . $db->dbescape( $description ) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '0', " . $status . ")";
            if ( $db->sql_query_insert_id( $sql ) )
            {
                nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['weblink_add_link'], $title, $admin_info['userid'] );
                $db->sql_freeresult();
                Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name );
                die();
            }
            else
            {
                $error = $lang_module['errorsave'];
            }
        }
    }
    $data_content['id'] = $id;
    $data_content['url'] = $url;
    $data_content['title'] = $title;
    $data_content['urlimg'] = $image;
    $data_content['description'] = $description;
}
elseif ( $id > 0 )
{
    $sql = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $id );
    $data_content = $db->sql_fetchrow( $sql );
    if ( $data_content['id'] > 0 )
    {
        $page_title = $lang_module['weblink_edit_link'];
    }
}

if ( empty( $data_content['id'] ) )
{
    $page_title = $lang_module['weblink_add_link'];
}

// dung de lay data tu CSDL
$data_content['description'] = ( defined( 'NV_EDITOR' ) ) ? nv_editor_br2nl( $data_content['description'] ) : nv_br2nl( $data_content['description'] );

// dung de dua vao editor
$data_content['description'] = nv_htmlspecialchars( $data_content['description'] );

if ( ! empty( $data_content['urlimg'] ) and ! nv_is_url( $data_content['urlimg'] ) )
{
    $data_content['urlimg'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $data_content['urlimg'];
}

// Set editor
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
    $edits = nv_aleditor( 'description', '100%', '300px', $data_content['description'] );
}
else
{
    $edits = "<textarea style=\"width: 100%\" name=\"description\" id=\"bodytext\" cols=\"20\" rows=\"15\">" . $data_content['description'] . "</textarea>";
}

// Get catid
$querysubcat = $db->sql_query( "SELECT catid, parentid, title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` ORDER BY `parentid`, `weight` ASC" );
$array_cat = array();
while ( $row = $db->sql_fetchrow( $querysubcat ) )
{
    $array_cat[$row['catid']] = $row;
}

// Get template
$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $data_content );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'module_name', $module_name );
$xtpl->assign( 'NV_EDITOR', $edits );

// Get catid
if ( ! empty( $array_cat ) )
{
    foreach ( $array_cat as $cat )
    {
        $xtitle = "";
        if ( $cat['parentid'] != 0 ) $xtitle = getlevel( $cat['parentid'], $array_cat );
        $cat['title'] = $xtitle . $cat['title'];
        $cat['sl'] = ($cat['catid'] == $data_content['catid']) ? "selected=\"selected\"" : "";
        $xtpl->assign( 'CAT', $cat );
        $xtpl->parse( 'main.loopcat' );
    }
}

$xtpl->assign( 'PATH', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'id', $data_content['id'] );
$xtpl->assign( 'DATA', $data_content );

if ( ! empty( $error ) )
{
	$xtpl->assign( 'error', $error );	
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>