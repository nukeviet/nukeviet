<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if ( ! defined( 'NV_IS_MOD_CONTACT' ) ) die( 'Stop!!!' );

//Danh sach cac bo phan
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department WHERE act=1 ORDER BY weight';
$array_department = nv_db_cache( $sql, 'id' );

$cats = array();
$cats[] = array( 0, '' );
$catsName = array();
$catsName[] = $lang_module['selectCat'];
$dpDefault = 0;
if ( ! empty( $array_department ) )
{
    foreach ( $array_department as $k => $department )
    {
        if ( ! empty( $department['cats'] ) )
        {
            $_cats = array_map( "trim", explode( "|", $department['cats'] ) );
            foreach ( $_cats as $_cats2 )
            {
                $cats[] = array( $department['id'], $_cats2 );
                $catsName[] = $_cats2;
            }
        }

        if ( $department['is_default'] ) $dpDefault = $department['id'];
    }
}

if ( empty( $dpDefault ) and ! empty( $array_department ) )
{
	$key_department= array_keys( $array_department );
	$dpDefault = $key_department[0];
}

$fname = '';
$femail = '';
$fphone = '';

if ( defined( 'NV_IS_USER' ) )
{
    $fname = ! empty( $user_info['full_name'] ) ? $user_info['full_name'] : $user_info['username'];
    $femail = $user_info['email'];
    $fphone = isset( $user_info['phone'] ) ? $user_info['phone'] : "";
}

/**
 * Nhan thong tin va gui den admin
 */
if ( $nv_Request->isset_request( 'checkss', 'post' ) )
{
    $checkss = $nv_Request->get_title( 'checkss', 'post', '' );
    if ( $checkss != md5( $client_info['session_id'] . $global_config['sitekey'] ) ) die();

    /**
     * Ajax
     */
    if ( $nv_Request->isset_request( 'loadForm', 'post' ) )
    {
        $array_content = array(
            'fname' => $fname,
            'femail' => $femail,
            'fphone' => $fphone );

        $checkss = md5( $client_info['session_id'] . $global_config['sitekey'] );

        $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

        $form = contact_form_theme( $array_content, $catsName, $base_url, $checkss );

        exit( $form );
    }

    if ( ! defined( 'NV_IS_USER' ) )
    {
        $fname = nv_substr( $nv_Request->get_title( 'fname', 'post', '', 1 ), 0, 100 );
        $femail = nv_substr( $nv_Request->get_title( 'femail', 'post', '', 1 ), 0, 100 );
    }

    if ( empty( $fname ) ) die( json_encode( array(
            'status' => 'error',
            'input' => 'fname',
            'mess' => $lang_module['error_fullname'] ) ) );

    if ( ( $check_valid_email = nv_check_valid_email( $femail ) ) != "" ) die( json_encode( array(
            'status' => 'error',
            'input' => 'femail',
            'mess' => $check_valid_email ) ) );

    if ( ( $ftitle = nv_substr( $nv_Request->get_title( 'ftitle', 'post', '', 1 ), 0, 255 ) ) == "" ) die( json_encode( array(
            'status' => 'error',
            'input' => 'ftitle',
            'mess' => $lang_module['error_title'] ) ) );
    if ( ( $fcon = $nv_Request->get_editor( 'fcon', '', NV_ALLOWED_HTML_TAGS ) ) == "" ) die( json_encode( array(
            'status' => 'error',
            'input' => 'fcon',
            'mess' => $lang_module['error_content'] ) ) );
    if ( ! nv_capcha_txt( $nv_Request->get_title( 'fcode', 'post', '' ) ) ) die( json_encode( array(
            'status' => 'error',
            'input' => 'fcode',
            'mess' => $lang_module['error_captcha'] ) ) );

    $fcat = $nv_Request->get_int( 'fcat', 'post', 0 );
    if ( isset( $cats[$fcat] ) )
    {
        $fpart = ( int )$cats[$fcat][0];
        $fcat = $cats[$fcat][1];
    }
    else
    {
        $fpart = ( int )$cats[0][0];
        $fcat = $cats[0][1];
    }

    if ( $fpart == 0 )
    {
        $fpart = $dpDefault;
        $fcat = '';
    }

    $fcon = nv_nl2br( $fcon );
    $fphone = nv_substr( $nv_Request->get_title( 'fphone', 'post', '', 1 ), 0, 100 );
    $sender_id = intval( defined( 'NV_IS_USER' ) ? $user_info['userid'] : 0 );

    $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_send
    (cid, cat, title, content, send_time, sender_id, sender_name, sender_email, sender_phone, sender_ip, is_read, is_reply) VALUES
    (" . $fpart . ", :cat, :title, :content, " . NV_CURRENTTIME . ", " . $sender_id . ", :sender_name, :sender_email, :sender_phone, :sender_ip, 0, 0)";
	$data_insert = array();
    $data_insert['cat'] = $fcat;
    $data_insert['title'] = $ftitle;
    $data_insert['content'] = $fcon;
    $data_insert['sender_name'] = $fname;
    $data_insert['sender_email'] = $femail;
    $data_insert['sender_phone'] = $fphone;
    $data_insert['sender_ip'] = $client_info['ip'];
	$row_id = $db->insert_id( $sql, 'id', $data_insert );
    if ( $row_id > 0 )
    {
        $xtpl = new XTemplate( 'sendcontact.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
        $xtpl->assign( 'LANG', $lang_module );
        $xtpl->assign( 'SITE_NAME', $global_config['site_name'] );
        $xtpl->assign( 'SITE_URL', $global_config['site_url'] );
        $xtpl->assign( 'FULLNAME', $fname );
        $xtpl->assign( 'EMAIL', $femail );
        $xtpl->assign( 'PHONE', $fphone );
        $xtpl->assign( 'IP', $client_info['ip'] );
        $xtpl->assign( 'CAT', $fcat );
        $xtpl->assign( 'PART', $array_department[$fpart]['full_name'] );
        $xtpl->assign( 'TITLE', $ftitle );
        $xtpl->assign( 'CONTENT', $fcon );

        $xtpl->parse( 'main' );
        $fcon = $xtpl->text( 'main' );

        $email_list = array();
        if ( ! empty( $array_department[$fpart]['email'] ) )
        {
            $_emails = array_map( "trim", explode( ",", $array_department[$fpart]['email'] ) );
            $email_list[] = $_emails[0];
        }

        if ( ! empty( $array_department[$fpart]['admins'] ) )
        {
            $admins = array_filter( array_map( 'trim', explode( ';', $array_department[$fpart]['admins'] ) ) );

            $a_l = array();
            foreach ( $admins as $adm )
            {
                unset( $adm2 );
                if ( preg_match( '/^([0-9]+)\/[0-1]{1}\/[0-1]{1}\/1$/', $adm, $adm2 ) ) $a_l[] = $adm2[1];
            }

            if ( ! empty( $a_l ) )
            {
                $a_l = implode( ',', $a_l );

                $sql = 'SELECT t2.email as admin_email FROM ' . NV_AUTHORS_GLOBALTABLE . ' t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.admin_id = t2.userid WHERE t1.lev!=0 AND t1.is_suspend=0 AND t1.admin_id IN (' . $a_l . ')';
                $result = $db->query( $sql );

                while ( $row = $result->fetch() )
                {
                    if ( nv_check_valid_email( $row['admin_email'] ) == '' )
                    {
                        $email_list[] = $row['admin_email'];
                    }
                }
            }
        }

        if ( ! empty( $email_list ) )
        {
            $from = array( $fname, $femail );
            $email_list = array_unique( $email_list );
            @nv_sendmail( $from, $email_list, $ftitle, $fcon );
        }

        nv_insert_notification( $module_name, 'contact_new', array( 'title' => $ftitle ), $row_id, 0, $sender_id, 1 );

        die( json_encode( array(
            'status' => 'ok',
            'input' => '',
            'mess' => $lang_module['sendcontactok'] ) ) );
    }

    die( json_encode( array(
        'status' => 'error',
        'input' => '',
        'mess' => $lang_module['sendcontactfailed'] ) ) );
}


$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$mod_title = isset( $lang_module['main_title'] ) ? $lang_module['main_title'] : $module_info['custom_title'];

$base_url = $base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );
if ( $_SERVER['REQUEST_URI'] == $base_url_rewrite )
{
    $canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
}
elseif ( NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite )
{
    Header( 'Location: ' . $base_url_rewrite );
    die();
}
else
{
    $canonicalUrl = $base_url_rewrite;
}

$array_content = array(
    'fname' => $fname,
    'femail' => $femail,
    'fphone' => $fphone );
$array_content['bodytext'] = ( isset( $module_config[$module_name]['bodytext'] ) ) ? nv_editor_br2nl( $module_config[$module_name]['bodytext'] ) : '';

$checkss = md5( $client_info['session_id'] . $global_config['sitekey'] );
$contents = contact_main_theme( $array_content, $array_department, $catsName, $base_url, $checkss );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents, 1 );
include NV_ROOTDIR . '/includes/footer.php';