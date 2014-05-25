<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 22, 2010 3:00:20 PM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post,get', 0 );

if( $id )
{
	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE id=' . $id;
	$frow = $db->query( $sql )->fetch();

	if( empty( $frow ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=department' );
		die();
	}

	$page_title = $frow['full_name'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
}
else
{
	$page_title = $lang_module['add_row_title'];
	$action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$xtpl = new XTemplate( 'row.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', $action );

$sql = 'SELECT t1.admin_id as id, t1.lev as lev, t2.username as admin_login, t2.email as admin_email, t2.full_name as admin_fullname
	FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1
	INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2
	ON t1.admin_id = t2.userid
	WHERE t1.lev!=0 AND t1.is_suspend=0';
$result = $db->query( $sql );

$adms = array();
while( $row = $result->fetch() )
{
	$adms[$row['id']] = array(
		'login' => $row['admin_login'],
		'fullname' => $row['admin_fullname'],
		'email' => $row['admin_email'],
		'level' => intval( $row['lev'] )
	);
}

$error = '';

if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

if( $nv_Request->get_int( 'save', 'post' ) == '1' )
{
	$full_name = $nv_Request->get_title( 'full_name', 'post', '', 1 );
	$phone = $nv_Request->get_title( 'phone', 'post', '', 1 );
	$fax = $nv_Request->get_title( 'fax', 'post', '', 1 );
	$email = $nv_Request->get_title( 'email', 'post', '', 1 );
    $yahoo = $nv_Request->get_title( 'yahoo', 'post', '', 1 );
    $skype = $nv_Request->get_title( 'skype', 'post', '', 1 );
	$note = $nv_Request->get_editor( 'note', '', NV_ALLOWED_HTML_TAGS );

	$view_level = $nv_Request->get_array( 'view_level', 'post', array() );
	$reply_level = $nv_Request->get_array( 'reply_level', 'post', array() );
	$obt_level = $nv_Request->get_array( 'obt_level', 'post', array() );

	$check_valid_email = nv_check_valid_email( $email );

	$admins = array();

	if( ! empty( $view_level ) )
	{
		foreach( $view_level as $admid )
		{
			$admins[$admid]['view_level'] = 1;
			$admins[$admid]['reply_level'] = 0;
			$admins[$admid]['obt_level'] = 0;
		}
	}

	if( ! empty( $reply_level ) )
	{
		foreach( $reply_level as $admid )
		{
			$admins[$admid]['view_level'] = 1;
			$admins[$admid]['reply_level'] = 1;
			$admins[$admid]['obt_level'] = 0;
		}
	}

	if( ! empty( $obt_level ) )
	{
		foreach( $obt_level as $admid )
		{
			$admins[$admid]['view_level'] = 1;
			if( ! isset( $admins[$admid]['reply_level'] ) ) $admins[$admid]['reply_level'] = 0;
			$admins[$admid]['obt_level'] = 1;
		}
	}

	if( empty( $full_name ) )
	{
		$error = $lang_module['err_part_row_title'];
	}
	elseif( ! empty( $email ) and ! empty( $check_valid_email ) )
	{
		$error = $check_valid_email;
	}
	else
	{
		$admins_list = array();
		foreach( $adms as $admid => $values )
		{
			if( $values['level'] === 1 )
			{
				$obt_level = ( isset( $admins[$admid] ) ) ? $admins[$admid]['obt_level'] : 0;
				$admins_list[] = $admid . '/1/1/' . $obt_level;
			}
			else
			{
				if( isset( $admins[$admid] ) )
				{
					$admins_list[] = $admid . '/' . $admins[$admid]['view_level'] . '/' . $admins[$admid]['reply_level'] . '/' . $admins[$admid]['obt_level'];
				}
			}
		}
		$admins_list = implode( ';', $admins_list );

		if( $id )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_department SET full_name=:full_name, phone = :phone, fax=:fax, email=:email, yahoo=:yahoo, skype=:skype, note=:note, admins=:admins WHERE id =' . $id;
			$name_key = 'log_edit_row';
			$note_action = 'id: ' . $id .' ' . $full_name;
		}
		else
		{
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_department (full_name, phone, fax, email, yahoo, skype, note, admins, act) VALUES (:full_name, :phone, :fax, :email, :yahoo, :skype, :note, :admins, 1)';
			$name_key = 'log_add_row';
			$note_action = $full_name;
		}
		$sth = $db->prepare( $sql);
		$sth->bindParam( ':full_name', $full_name, PDO::PARAM_STR );
		$sth->bindParam( ':phone', $phone, PDO::PARAM_STR );
		$sth->bindParam( ':fax', $fax, PDO::PARAM_STR );
		$sth->bindParam( ':email', $email, PDO::PARAM_STR );
        $sth->bindParam( ':yahoo', $yahoo, PDO::PARAM_STR );
        $sth->bindParam( ':skype', $skype, PDO::PARAM_STR );
		$sth->bindParam( ':note', $note, PDO::PARAM_STR );
		$sth->bindParam( ':admins', $admins_list, PDO::PARAM_STR );
		$sth->execute();
		if ($sth->rowCount() )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $name_key , $note_action, $admin_info['userid'] );
			nv_del_moduleCache( $module_name );
		}
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=department' );
		die();
	}
}
else
{
	if( $id )
	{
		$full_name = $frow['full_name'];
		$phone = $frow['phone'];
		$fax = $frow['fax'];
		$email = $frow['email'];
        $yahoo = $frow['yahoo'];
        $skype = $frow['skype'];
		$note = nv_editor_br2nl( $frow['note'] );

		$admins_list = $frow['admins'];
		$admins_list = ! empty( $admins_list ) ? array_map( 'trim', explode( ';', $admins_list ) ) : array();

		$view_level = $reply_level = $obt_level = array();

		if( ! empty( $admins_list ) )
		{
			foreach( $admins_list as $l )
			{
				if( preg_match( '/^([0-9]+)\/([0-1]{1})\/([0-1]{1})\/([0-1]{1})$/i', $l ) )
				{
					$l2 = array_map( 'intval', explode( '/', $l ) );
					$admid = intval( $l2[0] );

					if( isset( $adms[$admid] ) )
					{
						if( $adms[$admid]['level'] === 1 )
						{
							$view_level[] = $admid;
							$reply_level[] = $admid;

							if( isset( $l2[3] ) and $l2[3] === 1 )
							{
								$obt_level[] = $admid;
							}
						}
						else
						{
							if( isset( $l2[1] ) and $l2[1] === 1 )
							{
								$view_level[] = $admid;
							}

							if( isset( $l2[2] ) and $l2[2] === 1 )
							{
								$reply_level[] = $admid;
							}

							if( isset( $l2[3] ) and $l2[3] === 1 )
							{
								$obt_level[] = $admid;
							}
						}
					}
				}
			}
		}
	}
	else
	{
		$full_name = $phone = $fax = $email = $yahoo = $skype = $note = '';
		$view_level = $reply_level = $obt_level = array();

		foreach( $adms as $admid => $values )
		{
			if( $values['level'] === 1 )
			{
				$view_level[] = $admid;
				$reply_level[] = $admid;
			}
		}
	}
}

if( ! empty( $note ) ) $note = nv_htmlspecialchars( $note );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$note = nv_aleditor( 'note', '100%', '150px', $note );
}
else
{
	$note = '<textarea style="width:100%;height:150px" name="note" id="note">' . $note . '</textarea>';
}

$xtpl->assign( 'DATA', array(
	'full_name' => $full_name,
	'phone' => $phone,
	'fax' => $fax,
	'email' => $email,
    'yahoo' => $yahoo,
    'skype' => $skype,
	'note' => $note
) );

$a = 0;
foreach( $adms as $admid => $values )
{
	$xtpl->assign( 'ADMIN', array(
		'login' => $values['login'],
		'fullname' => $values['fullname'],
		'email' => $values['email'],
		'admid' => $admid,
		'view_level' => ( $values['level'] === 1 or ( ! empty( $view_level ) and in_array( $admid, $view_level ) ) ) ? ' checked="checked"' : '',
		'reply_level' => ( $values['level'] === 1 or ( ! empty( $reply_level ) and in_array( $admid, $reply_level ) ) ) ? ' checked="checked"' : '',
		'obt_level' => ( ! empty( $obt_level ) and in_array( $admid, $obt_level ) ) ? ' checked="checked"' : '',
		'disabled' => $values['level'] === 1 ? ' disabled="disabled"' : ''
	) );

	$xtpl->parse( 'main.admin' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';