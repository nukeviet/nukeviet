<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$contents = "";
$titlecat = "";
$catid = $nv_Request->get_int( 'catid', 'get', 0 );
if ( $catid > 0 )
{
    $sql = "SELECT catid," . NV_LANG_DATA . "_title as title, lev,numsubcat FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` WHERE catid = ".$catid;
    $result_cat = $db->sql_query( $sql );
    if ( $db->sql_numrows( $result_cat ) == 0 )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=".$op );
        die();
    }
    else {
    	$row =  $db->sql_fetchrow($result_cat);
    	$titlecat = $row['title'];
    }
}
$topid = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";
$data = array( 
    "topicid" => 0, "title" => "", 'alias' => "", 'description' => "", 'keywords' => "" 
);
$table_name = $db_config['prefix'] . "_" . $module_data . "_topics";
$save = $nv_Request->get_int( 'save', 'post', 0 );
if ( ! empty( $save ) )
{
    $field_lang = nv_file_table( $table_name );
    $data['title'] = filter_text_input( 'title', 'post', '', 1 );
    $data['keywords'] = filter_text_input( 'keywords', 'post', '', 1 );
    $data['alias'] = filter_text_input( 'alias', 'post', '' );
    $data['alias'] = ( $data['alias'] == "" ) ? change_alias( $data['title'] ) : change_alias( $data['alias'] );
    if ( $topid == 0 )
    {
        list( $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT max(`weight`) FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` WHERE `catid` = " . $catid . "" ) );
        $weight = intval( $weight ) + 1;
        $listfield = "";
        $listvalue = "";
        foreach ( $field_lang as $field_lang_i )
        {
            list( $flang, $fname ) = $field_lang_i;
            $listfield .= ", `" . $flang . "_" . $fname . "`";
            if ( $flang == NV_LANG_DATA )
            {
                $listvalue .= ", " . $db->dbescape( $data[$fname] );
            }
            else
            {
                $listvalue .= ", " . $db->dbescape( $data[$fname] );
            }
        }
        $query = "INSERT INTO `" . $db_config['prefix'] . "_" . $module_data . "_topics` (`topicid`,`catid` , `image`, `thumbnail`, `weight`, `add_time`, `edit_time` " . $listfield . ") VALUES (NULL,".intval($catid).",'', '', " . $db->dbescape( $weight ) . ", UNIX_TIMESTAMP( ), UNIX_TIMESTAMP( ) " . $listvalue . ")";
        if ( $db->sql_query_insert_id( $query ) )
        {
            $db->sql_freeresult();
            nv_del_moduleCache( $module_name );
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&catid=".$catid );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
    }
    else
    {
        $query = "UPDATE `" . $db_config['prefix'] . "_" . $module_data . "_topics` SET `" . NV_LANG_DATA . "_title`=" . $db->dbescape( $data['title'] ) . ", `" . NV_LANG_DATA . "_alias` =  " . $db->dbescape( $data['alias'] ) . ", `" . NV_LANG_DATA . "_description`=" . $db->dbescape( $data['description'] ) . ", `" . NV_LANG_DATA . "_keywords`= " . $db->dbescape( $data['keywords'] ) . ", `edit_time`=UNIX_TIMESTAMP( ) WHERE `topicid` =" . $topid . "";
        $db->sql_query( $query );
        if ( $db->sql_affectedrows() > 0 )
        {
            $error = $lang_module['saveok'];
            $db->sql_freeresult();
            nv_del_moduleCache( $module_name );
            Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&catid=".$catid );
            die();
        }
        else
        {
            $error = $lang_module['errorsave'];
        }
        $db->sql_freeresult();
    }
}
if ( $topid > 0)
{
	$sql = "SELECT topicid,	weight, " . NV_LANG_DATA . "_title as title , " . NV_LANG_DATA . "_alias as alias , " . NV_LANG_DATA . "_keywords as keywords FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` WHERE `topicid` = " . $topid . "";
	$result = $db->sql_query( $sql );
	$data = $db->sql_fetchrow( $result,2 );
	if (empty($data))
	{
		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&catid=".$catid );
	}
}
/*show data*/
$xtpl = new XTemplate( "topics.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
/** List catalogs **/
$sql = "SELECT catid," . NV_LANG_DATA . "_title, lev,numsubcat FROM `" . $db_config['prefix'] . "_" . $module_data . "_catalogs` ORDER BY `order` ASC";
$result_cat = $db->sql_query( $sql );
if ( $db->sql_numrows( $result_cat ) == 0 )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
    die();
}
while ( list( $catid_i, $title_i, $lev_i, $numsubcat_i ) = $db->sql_fetchrow( $result_cat ) )
{
    if ( $catid == 0 ) $catid = $catid_i;
    if ($titlecat == '') $titlecat = $title_i;
    $xtitle_i = "";
    if ( $lev_i > 0 )
    {
        for ( $i = 1; $i <= $lev_i; $i ++ )
        {
            $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
    }
    $select = ( $catid_i == $catid ) ? " selected=\"selected\"" : "";
    $xtpl->assign( 'xtitle_i', $xtitle_i );
    $xtpl->assign( 'title_i', $title_i );
    $xtpl->assign( 'catid_i', $catid_i );
    $xtpl->assign( 'select', $select );
    $xtpl->parse( 'main.rowscat' );
}
/** list topics**/
global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
$contents = "";
$sql = "SELECT topicid,	weight, " . NV_LANG_DATA . "_title as title , " . NV_LANG_DATA . "_keywords as keywords FROM `" . $db_config['prefix'] . "_" . $module_data . "_topics` WHERE `catid` = " . $catid . " ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );
if ( $num > 0 )
{
    while ( $row = $db->sql_fetchrow( $result, 2 ) )
    {
        $row['slect_weight'] = drawselect_number( 'topicid_' . $row['topicid'], 1, $num + 1, $row['weight'], "nv_chang_topics('" . $row['topicid'] . "',this,url_change_weight,url_back);" );
        $row['link_edit'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=". $row['topicid'];
        $row['link_del'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=del_topic&amp;id=". $row['topicid'];
        $xtpl->assign( 'ROW', $row );
        $xtpl->parse( 'main.listrow' );
    }
}
$xtpl->assign( 'LINK_CHANGE', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&catid=" );
$xtpl->assign( 'url_back', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&catid=" . $catid );
$xtpl->assign( 'url_change', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=change_topic&catid=" . $catid );
if ( $topid > 0 )
{
    $data['caption'] = $lang_module['edit_topics'];
}
else
{
    $data['caption'] = $lang_module['add_topics'] . " : ".$titlecat;
}
$xtpl->assign( 'DATA', $data );
$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );
$page_title = $lang_module['topics']." : ".$titlecat;

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>