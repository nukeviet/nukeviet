<?php

/**
 * @Project WEBNHANH
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 26/5/2011, 23:28
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$area = $nv_Request->get_title( 'area', 'get', '' );
if ( empty( $area ) )
{
    nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'AREA', $area );
$xtpl->assign( 'NV_ASSETS_DIR', NV_ASSETS_DIR );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&area=" . $area );

$array = array();

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&amp;area=" . $area . "&amp;submit=1";

if ( $nv_Request->isset_request( 'submit', 'get' ) )
{
    $array_user = array();
    $generate_page = "";

    $orderid = $nv_Request->get_title( 'orderid', 'get', '' );
    $ordertitle = $nv_Request->get_title( 'ordertitle', 'get', '' );
    $ordercode = $nv_Request->get_title( 'ordercode', 'get', '' );
    $orderaddtime = $nv_Request->get_title( 'orderaddtime', 'get', '' );

    if ( $orderid != "DESC" and $orderid != "" ) $orderid = "ASC";
    if ( $ordertitle != "DESC" and $ordertitle != "" ) $ordertitle = "ASC";
    if ( $ordercode != "DESC" and $ordercode != "" ) $ordercode = "ASC";
    if ( $orderaddtime != "DESC" and $orderaddtime != "" ) $orderaddtime = "ASC";

    $array['title'] = $nv_Request->get_title( 'title', 'get', '' );
    $array['code'] = $nv_Request->get_title( 'code', 'get', '' );
    $array['pfrom'] = $nv_Request->get_title( 'pfrom', 'get', '' );
    $array['pto'] = $nv_Request->get_title( 'pto', 'get', '' );
    $array['efrom'] = $nv_Request->get_title( 'efrom', 'get', '' );
    $array['eto'] = $nv_Request->get_title( 'eto', 'get', '' );

    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['pfrom'], $m ) )
    {
        $array['pfrom1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $array['pfrom1'] = "";
    }

    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['pto'], $m ) )
    {
        $array['pto1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $array['pto1'] = "";
    }

    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['efrom'], $m ) )
    {
        $array['efrom1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $array['efrom1'] = "";
    }

    unset( $m );
    if ( preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $array['eto'], $m ) )
    {
        $array['eto1'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $array['eto1'] = "";
    }

    $sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_row WHERE id!=0";

    $is_null = true;
    foreach ( $array as $check )
    {
        if ( ! empty( $check ) )
        {
            $is_null = false;
            break;
        }
    }

    if ( ! $is_null )
    {
        if ( ! empty( $array['title'] ) )
        {
            $base_url .= "&amp;title=" . rawurlencode( $array['title'] );
            $sql .= " AND ( title LIKE '%" . $db->dblikeescape( $array['title'] ) . "%' )";
        }

        if ( ! empty( $array['code'] ) )
        {
            $base_url .= "&amp;code=" . rawurlencode( $array['code'] );
            $sql .= " AND ( code LIKE '%" . $db->dblikeescape( $array['code'] ) . "%' )";
        }

        if ( ! empty( $array['pfrom1'] ) )
        {
            $base_url .= "&amp;pfrom=" . rawurlencode( $array['pfrom'] );
            $sql .= " AND publtime>=" . $array['pfrom1'];
        }

        if ( ! empty( $array['pto1'] ) )
        {
            $base_url .= "&amp;pto=" . rawurlencode( $array['pto'] );
            $sql .= " AND publtime<=" . $array['pto1'];
        }

        if ( ! empty( $array['efrom1'] ) )
        {
            $base_url .= "&amp;efrom=" . rawurlencode( $array['efrom'] );
            $sql .= " AND exptime>=" . $array['efrom1'];
        }

        if ( ! empty( $array['eto1'] ) )
        {
            $base_url .= "&amp;eto=" . rawurlencode( $array['eto'] );
            $sql .= " AND exptime<=" . $array['eto1'];
        }

        $sql1 = "SELECT COUNT(*) " . $sql;
        $result1 = $db->query( $sql1 );
        $all_page = $result1->fetchColumn();

        // Order data
        $orderida = array( "url" => ( $orderid == "ASC" ) ? $base_url . "&amp;orderid=DESC" : $base_url . "&amp;orderid=ASC", //
            "class" => ( $orderid == "" ) ? "nooder" : strtolower( $orderid ) //
            );

        $ordertitlea = array( "url" => ( $ordertitle == "ASC" ) ? $base_url . "&amp;ordertitle=DESC" : $base_url . "&amp;ordertitle=ASC", "class" => ( $ordertitle == "" ) ? "nooder" : strtolower( $ordertitle ) //
            );

        $ordercodea = array( "url" => ( $ordercode == "ASC" ) ? $base_url . "&amp;ordercode=DESC" : $base_url . "&amp;ordercode=ASC", //
            "class" => ( $ordercode == "" ) ? "nooder" : strtolower( $ordercode ) //
            );

        $orderaddtimea = array( "url" => ( $orderaddtime == "ASC" ) ? $base_url . "&amp;orderaddtime=DESC" : $base_url . "&amp;orderaddtime=ASC", //
            "class" => ( $orderaddtime == "" ) ? "nooder" : strtolower( $orderaddtime ) //
            );

        // SQL data
        if ( ! empty( $orderid ) )
        {
            $base_url .= "&amp;orderid=" . $orderid;
            $sql .= " ORDER BY id " . $orderid . "";
        } elseif ( ! empty( $ordertitle ) )
        {
            $base_url .= "&amp;ordertitle=" . $ordertitle;
            $sql .= " ORDER BY title " . $ordertitle . "";
        } elseif ( ! empty( $ordercode ) )
        {
            $base_url .= "&amp;ordercode=" . $ordercode;
            $sql .= " ORDER BY code " . $ordercode . "";
        } elseif ( ! empty( $orderaddtime ) )
        {
            $base_url .= "&amp;orderaddtime=" . $orderaddtime;
            $sql .= " ORDER BY addtime " . $orderaddtime . "";
        }

        $page = $nv_Request->get_int( 'page', 'get', 0 );
        $per_page = 10;

        $sql2 = "SELECT id, title, code, addtime " . $sql . " LIMIT " . $page . ", " . $per_page;
        $query2 = $db->query( $sql2 );

        while ( $row = $query2->fetch() )
        {
            $array_user[$row['id']] = array(
				"id" => $row['id'], //
                "title" => $row['title'], //
                "code" => $row['code'], //
                "addtime" => nv_date( "d/m/Y H:i", $row['addtime'] ) //
			);
        }

        $generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
    }

    if ( ! empty( $array_user ) )
    {
        $xtpl->assign( 'ODER_ID', $orderida );
        $xtpl->assign( 'ODER_TITLE', $ordertitlea );
        $xtpl->assign( 'ODER_CODE', $ordercodea );
        $xtpl->assign( 'ODER_ADDTIME', $orderaddtimea );

        $a = 0;
        foreach ( $array_user as $row )
        {
            $xtpl->assign( 'CLASS', ( $a % 2 == 1 ) ? " class=\"second\"" : "" );
            $xtpl->assign( 'ROW', $row );
            $xtpl->parse( 'resultdata.data.row' );
            $a++;
        }

        if ( ! empty( $generate_page ) )
        {
            $xtpl->assign( 'GENERATE_PAGE', $generate_page );
            $xtpl->parse( 'resultdata.data.generate_page' );
        }

        $xtpl->parse( 'resultdata.data' );
    } elseif ( $nv_Request->isset_request( 'submit', 'get' ) )
    {
        $xtpl->parse( 'resultdata.nodata' );
    }

    $xtpl->parse( 'resultdata' );
    $contents = $xtpl->text( 'resultdata' );

    print_r( $contents );
    die();
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
exit();