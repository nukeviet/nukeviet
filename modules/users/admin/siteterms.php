<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

$page_title = $lang_module['siteterms'];

$sql = "SELECT `content` FROM `" . NV_USERS_GLOBALTABLE . "_config` WHERE `config`='siteterms_" . NV_LANG_DATA . "'";
$result = $db->sql_query( $sql );
$numrows = $db->sql_numrows( $result );
if ( $numrows )
{
    $mode = "edit";
    $row = $db->sql_fetchrow( $result );
}
else
{
    $mode = "add";
    $row = array( 'content' => '' );
}

$error = "";

if ( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $content = nv_editor_filter_textarea( 'content', '', NV_ALLOWED_HTML_TAGS );

    if ( empty( $content ) )
    {
        $error = $lang_module['error_content'];
    }
    else
    {
        $content = nv_editor_nl2br( $content );
        if ( $mode == "edit" )
        {
            $query = "UPDATE `" . NV_USERS_GLOBALTABLE . "_config` SET 
            `content`=" . $db->dbescape( $content ) . ", 
            `edit_time`='" . NV_CURRENTTIME . "' 
            WHERE `config` ='siteterms_" . NV_LANG_DATA . "'";
        }
        else
        {
            $query = "INSERT INTO `" . NV_USERS_GLOBALTABLE . "_config` VALUES( 
            'siteterms_" . NV_LANG_DATA . "', " . $db->dbescape( $content ) . ", " . NV_CURRENTTIME . ")";
        }

        $db->sql_query( $query );

        if ( $db->sql_affectedrows() > 0 )
        {
            $error = $lang_module['saveok'];
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
    }
}
else
{
    $content = nv_editor_br2nl( $row['content'] );
}

if ( ! empty( $content ) ) $content = nv_htmlspecialchars( $content );

$xtpl = new XTemplate( "siteterms.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );

if ( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
    $data = nv_aleditor( "content", '100%', '300px', $content );
}
else
{
    $data = "<textarea style=\"width: 100%\" name=\"content\" id=\"content\" cols=\"20\" rows=\"8\">" . $content . "</textarea>";
}

$xtpl->assign( 'DATA', $data );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>