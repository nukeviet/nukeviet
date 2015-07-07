<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if( ! defined( 'NV_IS_MOD_USER' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) or ! $global_config['allowuserlogin'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	die();
}

$sql = 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $user_info['userid'];
$query = $db->query( $sql );
$row = $query->fetch();
$hashpassword = $row['password'];
$oldquestion = $row['question'];
$oldanswer = $row['answer'];

$array_data = array();
$array_data['checkss'] = md5( $client_info['session_id'] . $global_config['sitekey'] );
$checkss = $nv_Request->get_title( 'checkss', 'post', '' );

$array_data['your_question'] = $oldquestion;
$array_data['answer'] = $oldanswer;
$array_data['nv_password'] = $nv_Request->get_title( 'nv_password', 'post', '' );
$array_data['send'] = $nv_Request->get_bool( 'send', 'post', false );

$step = 1;
$error = '';

if( empty( $hashpassword ) )
{
	$step = 2;
}
else
{
	if( $checkss == $array_data['checkss'] )
	{
		if( $crypt->validate_password( $array_data['nv_password'], $hashpassword ) )
		{
			$step = 2;
		}
		else
		{
			$step = 1;
			$error = $lang_global['incorrect_password'];
		}
	}
}

if( $step == 2 )
{
	if( $array_data['send'] )
	{
		$array_data['your_question'] = nv_substr( $nv_Request->get_title( 'your_question', 'post', '', 1 ), 0, 255 );
		$array_data['answer'] = nv_substr( $nv_Request->get_title( 'answer', 'post', '', 1 ), 0, 255 );

		if( empty( $array_data['your_question'] ) )
		{
			$error = $lang_module['your_question_empty'];
		}
		elseif( empty( $array_data['answer'] ) )
		{
			$error = $lang_module['answer_empty'];
		}
		else
		{
			$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . '
					SET question= :question, answer= :answer
					WHERE userid=' . $user_info['userid'] );
			$stmt->bindParam( ':question', $array_data['your_question'], PDO::PARAM_STR );
			$stmt->bindParam( ':answer', $array_data['answer'], PDO::PARAM_STR );
			$stmt->execute();

			$contents = user_info_exit( $lang_module['change_question_ok'] );
			$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

			include NV_ROOTDIR . '/includes/header.php';
			echo nv_site_theme( $contents );
			include NV_ROOTDIR . '/includes/footer.php';
			exit();
		}
	}
}

$array_data['step'] = $step;
$array_data['info'] = empty( $error ) ? $lang_module['changequestion_step' . $array_data['step']] : "<span style=\"color:#fb490b;\">" . $error . "</span>";

if( $step == 2 )
{
	$array_data['questions'] = array();
	$array_data['questions'][] = $lang_module['select_question'];
	$sql = "SELECT title FROM " . NV_USERS_GLOBALTABLE . "_question WHERE lang='" . NV_LANG_DATA . "' ORDER BY weight ASC";
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$array_data['questions'][$row['title']] = $row['title'];
	}
}

$page_title = $mod_title = $lang_module['change_question_pagetitle'];
$key_words = $module_info['keywords'];

$contents = user_changequestion( $array_data );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';