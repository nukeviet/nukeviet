<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 10-5-2010 0:14
 */
if ( ! defined( 'NV_IS_MOD_NEWS' ) ) die( 'Stop!!!' );

function GetCatNews ( $lcatid, $module_name )
{
    global $db, $module_data;
    $catid = explode( ",", $lcatid ); // if list cat id have over 2
    $sql = "SELECT alias FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE catid = '" . $catid[0] . "'";
    $re = $db->sql_query( $sql );
    if ( list( $alias ) = $db->sql_fetchrow( $re ) )
    {
        return $alias;
    }
    return "";
}

function GetSourceNews ( $sourceid )
{
    global $db, $module_data;
    $sql = "SELECT title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_sources` WHERE sourceid = '" . $sourceid . "'";
    $re = $db->sql_query( $sql );
    if ( list( $title ) = $db->sql_fetchrow( $re ) )
    {
        return $title;
    }
    return "-/-";
}

function BoldKeywordInStr ( $str, $keyword )
{
    $str = nv_clean60( $str, 300 );
    $tmp = explode( " ", $keyword );
    foreach ( $tmp as $k )
    {
        $tp = strtolower( $k );
        $str = str_replace( $tp, "<span class=\"keyword\">" . $tp . "</span>", $str );
        $tp = strtoupper( $k );
        $str = str_replace( $tp, "<span class=\"keyword\">" . $tp . "</span>", $str );
        $k[0] = strtoupper( $k[0] );
        $str = str_replace( $k, "<span class=\"keyword\">" . $k . "</span>", $str );
    }
    return $str;
}

$key = filter_text_input( 'q', 'get', '', 1, 1000 );
$from_date = filter_text_input( 'from_date', 'get', '', 1, 1000 );
$to_date = filter_text_input( 'to_date', 'get', '', 1, 100 );
$check_num = filter_text_input( 'choose', 'get', 1, 1, 1 );
$pages = filter_text_input( 'page', 'get', 0, 1, 1000 );
$date_array['from_date'] = $from_date;
$date_array['to_date'] = $to_date;
$per_pages = 20;
if ( $key == '' ) $key = isset( $_SESSION["keyword"] ) ? $_SESSION["keyword"] : '';
$contents = call_user_func( "search_theme", $key, $check_num, $date_array );
$where = "";
$tbl_src = "";
if ( strlen($key) >= NV_MIN_SEARCH_LENGTH)
{
    if ( $check_num == 0 )
    {
        $tbl_src = " , `" . NV_PREFIXLANG . "_" . $module_data . "_sources` as tb2";
        $where = " AND ( tb1.title LIKE '%" . $key . "%' OR tb1.bodytext LIKE '%" . $key . "%' OR tb1.keywords LIKE '%" . $key . "%' ";
        $where .= " OR tb1.author LIKE '%" . $key . "%' OR tb2.title LIKE '%" . $key . "%' )";
        $where .= " AND ( tb1.sourceid =  tb2.sourceid ) ";
    }
    if ( $check_num == 1 )
    {
        $where = "AND ( tb1.title LIKE '%" . $key . "%' OR tb1.bodytext LIKE '%" . $key . "%' OR tb1.keywords LIKE '%" . $key . "%' ) ";
    }
    if ( $check_num == 2 )
    {
        $where = "AND ( tb1.author LIKE '%" . $key . "%' ) ";
    }
    if ( $check_num == 3 )
    {
        $tbl_src = " , `" . NV_PREFIXLANG . "_" . $module_data . "_sources` as tb2";
        $where = "AND ( tb1.sourceid =  tb2.sourceid ) AND (tb2.title LIKE '%" . $key . "%')";
    }
    if ( $to_date != "" )
    {
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $to_date, $m );
        $tdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $from_date, $m );
        $fdate = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
        $where .= " AND ( `publtime` < $fdate AND `publtime` >= $tdate  ) ";
    }
    ////////////////////////////////////////////////////////////////////////////////////
    $sql_all = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as tb1 " . $tbl_src . " 
	WHERE ( `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ) " . $where;
    //////////////////////////////////////////////
    list( $numRecord ) = $db->sql_fetchrow( $db->sql_query( $sql_all ) );
    
    $sql = " SELECT tb1.id,tb1.title,tb1.alias,tb1.listcatid,tb1.hometext,tb1.author,tb1.publtime,tb1.homeimgfile,tb1.sourceid
	FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` as tb1 " . $tbl_src . " 
	WHERE (`status`=1 AND `publtime` < " . NV_CURRENTTIME . " AND (`exptime`=0 OR `exptime`>" . NV_CURRENTTIME . ") ) " . $where . " ORDER BY ID DESC LIMIT " . $pages . "," . $per_pages;
    
    $result = $db->sql_query( $sql );
    
    $array_content = array();
    $url_link = NV_BASE_SITEURL . "?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name."&".NV_OP_VARIABLE."=";
    while ( list( $id, $title, $alias, $listcatid, $hometext, $author, $publtime, $homeimgfile, $sourceid ) = $db->sql_fetchrow( $result ) )
    {
        $array_content[] = array( 
            "id" => $id, "title" => $title, "alias" => $alias, "listcatid" => $listcatid, "hometext" => $hometext, "author" => $author, "publtime" => $publtime, "homeimgfile" => $homeimgfile, "sourceid" => $sourceid 
        );
    }
    $contents .= call_user_func( "search_result_theme", $key, $numRecord, $per_pages, $pages, $array_content, $url_link );
}
include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );
?>
