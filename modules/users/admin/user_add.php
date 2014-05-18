<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/05/2010
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['user_add'];

$groups_list = nv_groups_list();

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
$custom_fields = $nv_Request->get_array( 'custom_fields', 'post' );
if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' ;
}

$_user = array();
$error = '';
$userid = 0;
if( $nv_Request->isset_request( 'confirm', 'post' ) )
{
	$_user['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
	$_user['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	$_user['password1'] = $nv_Request->get_title( 'password1', 'post', '', 0 );
	$_user['password2'] = $nv_Request->get_title( 'password2', 'post', '', 0 );
	$_user['question'] = nv_substr( $nv_Request->get_title( 'question', 'post', '', 1 ), 0, 255 );
	$_user['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );
	$_user['full_name'] = nv_substr( $nv_Request->get_title( 'full_name', 'post', '', 1 ), 0, 255 );
	$_user['gender'] = nv_substr( $nv_Request->get_title( 'gender', 'post', '', 1 ), 0, 1 );
	$_user['view_mail'] = $nv_Request->get_int( 'view_mail', 'post', 0 );
	$_user['sig'] = $nv_Request->get_textarea( 'sig', '', NV_ALLOWED_HTML_TAGS );
	$_user['birthday'] = $nv_Request->get_title( 'birthday', 'post' );
	$_user['in_groups'] = $nv_Request->get_typed_array( 'group', 'post', 'int' );

	$md5username = nv_md5safe( $_user['username'] );

	// Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username' );
	$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
	$stmt->execute();
	$query_error_username = $stmt->fetchColumn();

	// Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email' );
	$stmt->bindParam( ':email', $_user['email'], PDO::PARAM_STR );
	$stmt->execute();
	$query_error_email = $stmt->fetchColumn();

	// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_reg  chưa.
	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email' );
	$stmt->bindParam( ':email', $_user['email'], PDO::PARAM_STR );
	$stmt->execute();
	$query_error_email_reg = $stmt->fetchColumn();

	// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_openid chưa.
	$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email' );
	$stmt->bindParam( ':email', $_user['email'], PDO::PARAM_STR );
	$stmt->execute();
	$query_error_email_openid = $stmt->fetchColumn();

	if( ( $error_username = nv_check_valid_login( $_user['username'], NV_UNICKMAX, NV_UNICKMIN ) ) != '' )
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
	elseif( $query_error_username )
	{
		$error = $lang_module['edit_error_username_exist'];
	}
	elseif( $query_error_email )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( $query_error_email_reg )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( $query_error_email_openid )
	{
		$error = $lang_module['edit_error_email_exist'];
	}
	elseif( ( $check_pass = nv_check_valid_pass( $_user['password1'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
	{
		$error = $check_pass;
	}
	elseif( $_user['password1'] != $_user['password2'] )
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
		$query_field = array( 'userid' => 0 );
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

			$in_groups = array();
			foreach ( $_user['in_groups'] as $_group_id )
			{
				if( $_group_id > 9 )
				{
					$in_groups[] = $_group_id;
				}
			}
			$_user['in_groups'] = array_intersect( $in_groups, array_keys( $groups_list ) );

			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
				username, md5username, password, email, full_name, gender, birthday, sig, regdate,
				question, answer, passlostkey, view_mail,
				remember, in_groups, active, checknum, last_login, last_ip, last_agent, last_openid, idsite)
				VALUES (
				:username,
				:md5_username,
				:password,
				:email,
				:full_name,
				:gender,
				" . $_user['birthday'] . ",
				:sig,
				" . NV_CURRENTTIME . ",
				:question,
				:answer,
				'',
				 " . $_user['view_mail'] . ",
				 1,
				 '" . implode( ',', $_user['in_groups'] ) . "', 1, '', 0, '', '', '', " . $global_config['idsite'] . ")";
			$data_insert = array();
			$data_insert['username'] = $_user['username'];
			$data_insert['md5_username'] = $md5username;
			$data_insert['password'] = $crypt->hash( $_user['password1'] );
			$data_insert['email'] = $_user['email'];
			$data_insert['full_name'] = $_user['full_name'];
			$data_insert['gender'] = $_user['gender'];
			$data_insert['sig'] = $_user['sig'];
			$data_insert['question'] = $_user['question'];
			$data_insert['answer'] = $_user['answer'];

			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( $userid )
			{
				$query_field['userid'] = $userid;
				$db->query( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' );

				nv_insert_logs( NV_LANG_DATA, $module_name, 'log_add_user', 'userid ' . $userid, $admin_info['userid'] );
				if( isset( $_FILES['photo'] ) and is_uploaded_file( $_FILES['photo']['tmp_name'] ) )
				{
					require_once NV_ROOTDIR . '/includes/class/upload.class.php' ;

					$upload = new upload( array( 'images' ), $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, 80, 80 );
					$upload_info = $upload->save_file( $_FILES['photo'], NV_UPLOADS_REAL_DIR . '/' . $module_name, false );

					@unlink( $_FILES['photo']['tmp_name'] );

					if( empty( $upload_info['error'] ) )
					{
						@chmod( $upload_info['name'], 0644 );

						$file_name = str_replace( NV_ROOTDIR . '/', '', $upload_info['name'] );

						$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET photo= :file_name WHERE userid=' . $userid );
						$stmt->bindParam( ':file_name', $file_name, PDO::PARAM_STR, strlen( $file_name) );
						$stmt->execute();
					}
				}

				if( ! empty( $_user['in_groups'] ) )
				{
					foreach( $_user['in_groups'] as $group_id )
					{
						nv_groups_add_user( $group_id, $userid );
					}
				}
				$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				exit();
			}
			$error = $lang_module['edit_add_error'];
		}
	}
}
else
{
	$_user['username'] = $_user['email'] = $_user['password1'] = $_user['password2'] = $_user['question'] = $_user['answer'] = '';
	$_user['full_name'] = $_user['gender'] = $_user['sig'] = $_user['birthday'] = '';
	$_user['view_mail'] = 0;
	$_user['in_groups'] = array();
}

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
			'checked' => ( ! empty( $_user['in_groups'] ) and in_array( $group_id, $_user['in_groups'] ) ) ? ' checked="checked"' : ''
		);
	}
}

$xtpl = new XTemplate( 'user_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $_user );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=user_add' );
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

	if( ! empty( $array_field_config ) )
	{
		foreach( $array_field_config as $row )
		{
			if( ( $row['show_register'] and $userid == 0 ) or $userid > 0 )
			{
				if( $userid == 0 and empty( $custom_fields ) )
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
					foreach( $row['field_choices'] as $key => $value )
					{
						$xtpl->assign( 'FIELD_CHOICES', array(
							'key' => $key,
							'selected' => ( $key == $row['value'] ) ? ' selected="selected"' : '',
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