<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright ? 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['edit_title'];

$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
$my_footer .= "<script type=\"text/javascript\">\n";
$my_footer .= "Shadowbox.init({\n";
$my_footer .= "});\n";
$my_footer .= "</script>\n";

$userid = $nv_Request->get_int( 'userid', 'get', 0 );

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid;
$row = $db->query( $sql )->fetch();
if( empty( $row ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$allow = false;

$sql = 'SELECT lev FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $userid;
$rowlev = $db->query( $sql )->fetch();
if( empty( $rowlev ) )
{
	$allow = true;
}
else
{
	if( $admin_info['admin_id'] == $userid or $admin_info['level'] < $rowlev['lev'] )
	{
		$allow = true;
	}
}

if( $global_config['idsite'] > 0 and $row['idsite'] != $global_config['idsite'] and $admin_info['admin_id'] != $userid )
{
	$allow = false;
}

if( ! $allow )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$_user = array();

$groups_list = nv_groups_list();

$array_old_groups = array();
$result_gru = $db->query( 'SELECT group_id FROM ' . NV_GROUPS_GLOBALTABLE . '_users WHERE userid=' . $userid );
while( $row_gru = $result_gru->fetch() )
{
	$array_old_groups[] = $row_gru['group_id'];
}

$array_field_config = array();
$result_field = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_field ORDER BY weight ASC' );
while( $row_field = $result_field->fetch() )
{
	$language = unserialize( $row_field['language'] );
	$row_field['title'] = ( isset( $language[NV_LANG_DATA] ) ) ? $language[NV_LANG_DATA][0] : $row['field'];
	$row_field['description'] = ( isset( $language[NV_LANG_DATA] ) ) ? nv_htmlspecialchars( $language[NV_LANG_DATA][1] ) : '';
	if( ! empty( $row_field['field_choices'] ) ) $row_field['field_choices'] = unserialize( $row_field['field_choices'] );
	elseif( ! empty( $row_field['sql_choices'] ) )
	{
		$row_field['sql_choices'] = explode( '|', $row_field['sql_choices'] );
		$query = 'SELECT ' . $row_field['sql_choices'][2] . ', ' . $row_field['sql_choices'][3] . ' FROM ' . $row_field['sql_choices'][1];
		$result = $db->query( $query );
		$weight = 0;
		while( list( $key, $val ) = $result->fetch( 3 ) )
		{
			$row_field['field_choices'][$key] = $val;
		}
	}
	$array_field_config[] = $row_field;
}

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;
}

$error = '';
$access_passus = ( isset( $access_admin['access_passus'][$admin_info['level']] ) and $access_admin['access_passus'][$admin_info['level']] == 1 ) ? true : false;

if( $nv_Request->isset_request( 'confirm', 'post' ) )
{
	$_user['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
	$_user['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	if( $access_passus )
	{
		$_user['password1'] = $nv_Request->get_title( 'password1', 'post', '', 0 );
		$_user['password2'] = $nv_Request->get_title( 'password2', 'post', '', 0 );
	}
	else
	{
		$_user['password1'] = $_user['password2'] = '';
	}
	$_user['question'] = nv_substr( $nv_Request->get_title( 'question', 'post', '', 1 ), 0, 255 );
	$_user['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );
	$_user['full_name'] = nv_substr( $nv_Request->get_title( 'full_name', 'post', '', 1 ), 0, 255 );
	$_user['gender'] = nv_substr( $nv_Request->get_title( 'gender', 'post', '', 1 ), 0, 1 );
	$_user['photo'] = nv_substr( $nv_Request->get_title( 'photo', 'post', '', 1 ), 0, 255 );
	$_user['view_mail'] = $nv_Request->get_int( 'view_mail', 'post', 0 );
	$_user['sig'] = $nv_Request->get_textarea( 'sig', '', NV_ALLOWED_HTML_TAGS );
	$_user['birthday'] = $nv_Request->get_title( 'birthday', 'post' );
	$_user['in_groups'] = $nv_Request->get_typed_array( 'group', 'post', 'int' );
	$_user['delpic'] = $nv_Request->get_int( 'delpic', 'post', 0 );

	$custom_fields = $nv_Request->get_array( 'custom_fields', 'post' );

	if( $_user['username'] != $row['username'] and ( $error_username = nv_check_valid_login( $_user['username'], NV_UNICKMAX, NV_UNICKMIN ) ) != '' )
	{
		$error = $error_username;
	}
	elseif( "'" . $_user['username'] . "'" != $db->quote( $_user['username'] ) )
	{
		$error = sprintf( $lang_module['account_deny_name'], '<strong>' . $_user['username'] . '</strong>' );
	}
	elseif( ( $error_xemail = nv_check_valid_email( $_user['email'] ) ) != '' )
	{
		$error = $error_xemail;
	}
	elseif( $db->query( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $userid . ' AND md5username=' . $db->quote( nv_md5safe( $_user['username'] ) ) )->fetchColumn() )
	{
		$error = $lang_module['edit_error_username_exist'];
	}
	elseif( $db->query( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid!=' . $userid . ' AND email=' . $db->quote( $_user['email'] ) )->fetchColumn() )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( $db->query( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email=' . $db->quote( $_user['email'] ) )->fetchColumn() )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( $db->query( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE userid!=' . $userid . ' AND email=' . $db->quote( $_user['email'] ) )->fetchColumn() )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( ! empty( $_user['password1'] ) and ( $check_pass = nv_check_valid_pass( $_user['password1'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
	{
		$error = $check_pass;
	}
	elseif( ! empty( $_user['password1'] ) and $_user['password1'] != $_user['password2'] )
	{
		$error = $lang_module['edit_error_password'];
	}
	elseif( empty( $_user['question'] ) )
	{
		$error = $lang_module['edit_error_question'];
	}
	elseif( empty( $_user['answer'] ) )
	{
		$error = $lang_module['edit_error_answer'];
	}
	else
	{
		$query_field = array();
		if( ! empty( $array_field_config ) )
		{
			require NV_ROOTDIR . '/modules/users/fields.check.php';
		}

		if( empty( $error ) )
		{
			$_user['sig'] = nv_nl2br( $_user['sig'], '<br />' );
			if( $_user['gender'] != 'M' and $_user['gender'] != 'F' )
			{
				$_user['gender'] = '';
			}

			if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_user['birthday'], $m ) )
			{
				$_user['birthday'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
			}
			else
			{
				$_user['birthday'] = 0;
			}

			$password = ! empty( $_user['password1'] ) ? $crypt->hash( $_user['password1'] ) : $row['password'];

			// Check photo
			if( $_user['delpic'] or empty( $photo ) )
			{
				if( ! empty( $_user['photo'] ) )
				{
					$tmp_photo = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $_user['photo'];

					if( ! file_exists( $tmp_photo ) )
					{
						$_user['photo'] = '';
					}
					else
					{
						$new_photo_name = $_user['photo'];
						$new_photo_path = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/';

						$new_photo_name2 = $new_photo_name;
						$i = 1;
						while( file_exists( $new_photo_path . $new_photo_name2 ) )
						{
							$new_photo_name2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $new_photo_name );
							++ $i;
						}
						$new_photo = $new_photo_path . $new_photo_name2;

						if( nv_copyfile( $tmp_photo, $new_photo ) )
						{
							$_user['photo'] = substr( $new_photo, strlen( NV_ROOTDIR . '/' ) );
						}
						else
						{
							$_user['photo'] = '';
						}

						nv_deletefile( $tmp_photo );
					}
				}

				// Delete old photo
				if( $_user['delpic'] and ! empty( $row['photo'] ) and file_exists( NV_ROOTDIR . '/' . $row['photo'] ) )
				{
					nv_deletefile( NV_ROOTDIR . '/' . $row['photo'] );
				}
			}
			else
			{
				$_user['photo'] = $row['photo'];

				if( ! empty( $_user['photo'] ) )
				{
					if( ! file_exists( NV_ROOTDIR . '/' . $_user['photo'] ) )
					{
						$_user['photo'] = '';
					}
				}
			}

			$in_groups = array();
			foreach ( $_user['in_groups'] as $_group_id )
			{
				if( $_group_id > 9 )
				{
					$in_groups[] = $_group_id;
				}
			}
			$in_groups = array_intersect( $in_groups, array_keys( $groups_list ) );
			$in_groups_hiden = array_diff( $array_old_groups, array_keys( $groups_list ) );
			$in_groups = array_unique( array_merge( $in_groups, $in_groups_hiden ) );

			$in_groups_del = array_diff( $array_old_groups, $in_groups );
			if( ! empty( $in_groups_del ) )
			{
				foreach( $in_groups_del as $gid )
				{
					nv_groups_del_user( $gid, $userid );
				}
			}

			$in_groups_add = array_diff( $in_groups, $array_old_groups );
			if( ! empty( $in_groups_add ) )
			{
				foreach( $in_groups_add as $gid )
				{
					nv_groups_add_user( $gid, $userid );
				}
			}

			$db->query( "UPDATE " . NV_USERS_GLOBALTABLE . " SET
				username=" . $db->quote( $_user['username'] ) . ",
				md5username='" . nv_md5safe( $_user['username'] ) . "',
				password=" . $db->quote( $password ) . ",
				email=" . $db->quote( $_user['email'] ) . ",
				full_name=" . $db->quote( $_user['full_name'] ) . ",
				gender=" . $db->quote( $_user['gender'] ) . ",
				photo=" . $db->quote( nv_unhtmlspecialchars( $_user['photo'] ) ) . ",
				birthday=" . $_user['birthday'] . ",
				sig=" . $db->quote( $_user['sig'] ) . ",
				question=" . $db->quote( $_user['question'] ) . ",
				answer=" . $db->quote( $_user['answer'] ) . ",
				view_mail=" . $_user['view_mail'] . ",
				in_groups='".implode( ',', $in_groups )."'
				WHERE userid=" . $userid );

			if( ! empty( $array_field_config ) )
			{
				$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . '_info SET ' . implode( ', ', $query_field ) . ' WHERE userid=' . $userid );
			}

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_edit_user', 'userid ' . $userid, $admin_info['userid'] );

			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
			exit();
		}
	}
}
else
{
	$_user = $row;
	$_user['password1'] = $_user['password2'] = '';
	$_user['birthday'] = ! empty( $_user['birthday'] ) ? date( 'd/m/Y', $_user['birthday'] ) : '';
	$_user['in_groups'] = $array_old_groups;
	if( ! empty( $_user['sig'] ) ) $_user['sig'] = nv_br2nl( $_user['sig'] );

	$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . '_info WHERE userid=' . $userid;
	$result = $db->query( $sql );
	$custom_fields = $result->fetch();
}

// Data gender
$genders = array(
	'N' => array(
		'key' => 'N',
		'title' => $lang_module['NA'],
		'selected' => ''
	),
	'M' => array(
		'key' => 'M',
		'title' => $lang_module['male'],
		'selected' => $_user['gender'] == 'M' ? ' selected="selected"' : ''
	),
	'F' => array(
		'key' => 'F',
		'title' => $lang_module['female'],
		'selected' => $_user['gender'] == 'F' ? ' selected="selected"' : ''
	)
);

$_user['view_mail'] = $_user['view_mail'] ? ' checked="checked"' : '';

if( ! empty( $_user['sig'] ) ) $_user['sig'] = nv_htmlspecialchars( $_user['sig'] );

$groups = array();
if( ! empty( $groups_list ) )
{
	foreach( $groups_list as $group_id => $grtl )
	{
		$groups[] = array(
			'id' => $group_id,
			'title' => $grtl,
			'checked' => ( in_array( $group_id, $_user['in_groups'] ) ) ? ' checked="checked"' : ''
		);
	}
}

$xtpl = new XTemplate( 'user_edit.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $_user );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $userid );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( defined( 'NV_IS_USER_FORUM' ) )
{
	$xtpl->parse( 'main.is_forum' );
}
else
{
	foreach( $genders as $gender )
	{
		$xtpl->assign( 'GENDER', $gender );
		$xtpl->parse( 'main.edit_user.gender' );
	}

	if( ! empty( $row['photo'] ) and file_exists( NV_ROOTDIR . '/' . $row['photo'] ) )
	{
		$size = @getimagesize( NV_ROOTDIR . '/' . $row['photo'] );
		$img = array(
			'src' => NV_BASE_SITEURL . $row['photo'],
			'height' => $size[1],
			'width' => $size[0]
		);
		$xtpl->assign( 'IMG', $img );
		$xtpl->parse( 'main.edit_user.photo' );
	}
	else
	{
		$xtpl->parse( 'main.edit_user.add_photo' );
	}

	$a = 0;
	foreach( $groups as $group )
	{
		if( $group['id'] > 9 )
		{
			$xtpl->assign( 'GROUP', $group );
			$xtpl->parse( 'main.edit_user.group.list' );
			++$a;
		}
	}
	if( $a > 0 )
	{
		$xtpl->parse( 'main.edit_user.group' );
	}

	if( $access_passus )
	{
		$xtpl->parse( 'main.edit_user.changepass' );
	}

	if( ! empty( $array_field_config ) )
	{
		foreach( $array_field_config as $row )
		{
			if( ( $row['show_register'] and $userid == 0 ) or $userid > 0 )
			{
				if( $userid == 0 and ! $nv_Request->isset_request( 'confirm', 'post' ) )
				{
					if( ! empty( $row['field_choices'] ) )
					{
						if( $row['field_type'] == 'date' )
						{
							$row['value'] = ( $row['field_choices']['current_date'] ) ? NV_CURRENTTIME : $row['default_value'];
						}
						elseif( $row['field_type'] == 'number' )
						{
							$row['value'] = $row['default_value'];
						}
						else
						{
							$temp = array_keys( $row['field_choices'] );
							$tempkey = intval( $row['default_value'] ) - 1;
							$row['value'] = ( isset( $temp[$tempkey] ) ) ? $temp[$tempkey] : '';
						}
					}
					else
					{
						$row['value'] = $row['default_value'];
					}
				}
				else
				{
					$row['value'] = ( isset( $custom_fields[$row['field']] ) ) ? $custom_fields[$row['field']] : $row['default_value'];
				}
				$row['required'] = ( $row['required'] ) ? 'required' : '';

				$xtpl->assign( 'FIELD', $row );
				if( $row['required'] )
				{
					$xtpl->parse( 'main.edit_user.field.loop.required' );
				}
				if( $row['field_type'] == 'textbox' or $row['field_type'] == 'number' )
				{
					$xtpl->parse( 'main.edit_user.field.loop.textbox' );
				}
				elseif( $row['field_type'] == 'date' )
				{
					$row['value'] = ( empty( $row['value'] ) ) ? '' : date( 'd/m/Y', $row['value'] );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.edit_user.field.loop.date' );
				}
				elseif( $row['field_type'] == 'textarea' )
				{
					$row['value'] = nv_htmlspecialchars( nv_br2nl( $row['value'] ) );
					$xtpl->assign( 'FIELD', $row );
					$xtpl->parse( 'main.edit_user.field.loop.textarea' );
				}
				elseif( $row['field_type'] == 'editor' )
				{
					$row['value'] = htmlspecialchars( nv_editor_br2nl( $row['value'] ) );
					if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
					{
						$array_tmp = explode( '@', $row['class'] );
						$edits = nv_aleditor( 'custom_fields[' . $row['field'] . ']', $array_tmp[0], $array_tmp[1], $row['value'] );
						$xtpl->assign( 'EDITOR', $edits );
						$xtpl->parse( 'main.edit_user.field.loop.editor' );
					}
					else
					{
						$row['class'] = '';
						$xtpl->assign( 'FIELD', $row );
						$xtpl->parse( 'main.edit_user.field.loop.textarea' );
					}
				}
				elseif( $row['field_type'] == 'select' )
				{
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( $key == $row['value'] ) ? ' selected="selected"' : '',
							'value' => $value
						) );
						$xtpl->parse( 'main.edit_user.field.loop.select.loop' );
					}
					$xtpl->parse( 'main.edit_user.field.loop.select' );
				}
				elseif( $row['field_type'] == 'radio' )
				{
					$number = 0;
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( $key == $row['value'] ) ? ' checked="checked"' : '',
							'value' => $value
						) );
						$xtpl->parse( 'main.edit_user.field.loop.radio' );
					}
				}
				elseif( $row['field_type'] == 'checkbox' )
				{
					$number = 0;
					$valuecheckbox = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'id' => $row['fid'] . '_' . $number++,
							'key' => $key,
							'checked' => ( in_array( $key, $valuecheckbox ) ) ? ' checked="checked"' : '',
							'value' => $value
						) );
						$xtpl->parse( 'main.edit_user.field.loop.checkbox' );
					}
				}
				elseif( $row['field_type'] == 'multiselect' )
				{
					$valueselect = ( ! empty( $row['value'] ) ) ? explode( ',', $row['value'] ) : array();
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( in_array( $key, $valueselect ) ) ? ' selected="selected"' : '',
							'value' => $value
						) );
						$xtpl->parse( 'main.edit_user.field.loop.multiselect.loop' );
					}
					$xtpl->parse( 'main.edit_user.field.loop.multiselect' );
				}
				$xtpl->parse( 'main.edit_user.field.loop' );
			}
		}
		$xtpl->parse( 'main.edit_user.field' );
	}

	$xtpl->parse( 'main.edit_user' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';