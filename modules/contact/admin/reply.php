<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (@) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'get', 0 );

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_send WHERE id=' . $id;
$row = $db->query( $sql )->fetch();

if( empty( $row ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$contact_allowed = nv_getAllowed();

if( ! isset( $contact_allowed['view'][$row['cid']] ) or ! isset( $contact_allowed['reply'][$row['cid']] ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$xtpl = new XTemplate( 'reply.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$is_read = intval( $row['is_read'] );
if( ! $is_read )
{
	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_read=1 WHERE id=' . $id );
	$is_read = 1;
}

$admin_name = $admin_info['full_name'];
if( empty( $admin_name ) ) $admin_name = $admin_info['username'];

$mess_content = $error = '';

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$mess_content = $nv_Request->get_editor( 'mess_content', '', NV_ALLOWED_HTML_TAGS );

	if( strip_tags( $mess_content ) != '' )
	{
		$from = $db->query( 'SELECT email FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $row['cid'] )->fetchColumn();
		if( nv_check_valid_email( $from ) != '' )
		{
			$from = $admin_info['email'];
		}

		$from = array( $admin_name, $from );

		$subject = 'Re: ' . $row['title'];

		if( nv_sendmail( $from, $row['sender_email'], $subject, $mess_content ) )
		{
			$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_reply (id, reply_content, reply_time, reply_aid) VALUES (' . $id . ', :reply_content, ' . NV_CURRENTTIME . ', ' . $admin_info['admin_id'] . ')' );
			$sth->bindParam( ':reply_content', $mess_content, PDO::PARAM_STR, strlen( $mess_content ) );
			$sth->execute();

			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_send SET is_reply=1 WHERE id=' . $id );

			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=view&id=' . $id );
			die();
		}
		else
		{
			$error = $lang_global['error_sendmail_admin'];
		}
	}
}
else
{
	$mess_content .= '<br /><br />----------<br />Best regards,<br /><br />' . $admin_name . '<br />';
	if( ! empty( $admin_info['position'] ) )
	{
		$mess_content .= $admin_info['position'] . '<br />';
	}
	$mess_content .= '<br />';
	$mess_content .= 'E-mail: ' . $admin_info['email'] . '<br />';
	$mess_content .= 'Website: ' . $global_config['site_name'] . '<br />' . $global_config['site_url'] . '<br /><br />';

	$mess_content .= '--------------------------------------------------------------------------------<br />';
	$mess_content .= '<strong>From:</strong> ' . $row['sender_name'] . ' [mailto:' . $row['sender_email'] . ']<br />';
	$mess_content .= '<strong>Sent:</strong> ' . date( 'r', $row['send_time'] ) . '<br />';
	$mess_content .= '<strong>To:</strong> ' . $contact_allowed['view'][$row['cid']] . '<br />';
	$mess_content .= '<strong>Subject:</strong> ' . $row['title'] . '<br /><br />';
	$mess_content .= $row['content'];
}

$mess_content = htmlspecialchars( nv_editor_br2nl( $mess_content ) );

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$mess_content = nv_aleditor( 'mess_content', '100%', '300px', $mess_content );
}
else
{
	$mess_content = '<textarea style="width:99%" name="mess_content" id="mess_content" cols="20" rows="8">' . $mess_content . '</textarea>';
}

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id );
$xtpl->assign( 'MESS_CONTENT', $mess_content );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $module_info['custom_title'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';