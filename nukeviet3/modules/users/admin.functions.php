<?php

/**
 * @Project NUKEVIET CMS 3.0
 * @Author VINADES (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 
    'main', 'del', 'delconfirm', 'setactive', 'user_add', 'edit', 'user_waiting', 'delawaitinglist', 'question', 'siteterms', 'config', //
	'groups', 'groups_add', 'groups_edit', 'groups_del', 'groups_act', 'groups_users', 'groups_search_users', 'groups_add_user', 'groups_exclude_user' 
);
define( 'NV_IS_FILE_ADMIN', true );
$submenu['user_add'] = $lang_module['user_add'];
$submenu['user_waiting'] = $lang_module['member_wating'];
$submenu['groups'] =$lang_global['mod_groups'];
$submenu['groups_add'] = $lang_module['nv_admin_add'];
$submenu['question'] = $lang_module['question'];
$submenu['siteterms'] = $lang_module['siteterms'];
$submenu['config'] = $lang_module['config'];

function nv_fix_question ( )
{
    global $db;
    
    $query = "SELECT `qid` FROM `" . NV_USERS_GLOBALTABLE . "_question` WHERE `lang`='" . NV_LANG_DATA . "' ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $sql = "UPDATE `" . NV_USERS_GLOBALTABLE . "_question` SET `weight`=" . $weight . " WHERE `qid`=" . $row['qid'];
        $db->sql_query( $sql );
    }
    $db->sql_freeresult();
}

function nv_groups_add_theme ( $contents )
{
    $return = "";
    global $my_head;
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
    
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['caption'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<table class=\"tab1\" style=\"margin-bottom:8px\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['title'][0] . ": </td>\n";
    $return .= "<td>\n";
    $return .= "<input name=\"title\" id=\"title\" type=\"text\" value=\"" . $contents['title'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][2] . "\" /><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "</tr>\n";
	$return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['exp_time'][0] . ": </td>\n";
    $return .= "<td>" . $contents['exp_time'][6] . ":";
    $exp_time = "";
    if ( ! empty( $contents['exp_time'][1] ) and ! empty( $contents['exp_time'][2] ) and ! empty( $contents['exp_time'][3] ) )
    {
        $exp_time = mktime( 0, 0, 0, $contents['exp_time'][2], $contents['exp_time'][1], $contents['exp_time'][3] );
        $exp_time = date( "d.m.Y", $exp_time );
    }
    $return .= "<input name=\"exp_time\" id=\"exp_time\" value=\"" . $exp_time . "\" style=\"width: 90px;\" maxlength=\"10\" readonly=\"readonly\" type=\"text\" />\n";
    $return .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" width=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_time', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\" />\n";
    $return .= " " . $contents['exp_time'][9] . ": <select name=\"hour\">\n";
    for ( $i = 0; $i <= 23; $i ++ )
    {
        $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['exp_time'][4] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
    }
    $return .= "</select> " . $contents['exp_time'][10] . ": <select name=\"min\">\n";
    for ( $i = 0; $i <= 59; $i ++ )
    {
        $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['exp_time'][5] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
	$return .= "</tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['public'][0] . ": </td>\n";
    $return .= "<td><input type=\"checkbox\" value=\"1\" id=\"public\" name=\"public\"" . ( $contents['public'][1] ? " checked=\"checked\"" : "" ) . " /></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    $return .= "<table class=\"tab1\" style=\"margin-bottom:8px\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['content'][0] . ":</td>\n"; 
    $return .= "</tr>\n";
	$return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>\n";
	$return .= "<div style=\"background:#FFFFFF; padding:2px\">\n";
    if ( $contents['content'][4] and nv_function_exists( 'nv_aleditor' ) )
    {
        $return .= nv_aleditor( "content", $contents['content'][2], $contents['content'][3], $contents['content'][1] );
    }
    else
    {
        $return .= "<textarea name=\"content\" id=\"content\" style=\"width:" . $contents['content'][2] . ";height:" . $contents['content'][3] . "\">" . $contents['content'][1] . "</textarea>\n";
    }
	$return .= "</div>\n";
    $return .= "</td>\n";
    $return .= "</tr>\n";
	$return .= "</tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td><center><input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" name=\"submit1\" /></center></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    $return .= "</form>\n";	
    
    return $return;
}

function error_info_theme ( $content )
{
    $return = "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote class=\"error\"><span>" . $content . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    return $return;
}

function nv_admin_edit_theme ( $contents )
{
    global $my_head;
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
    
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['caption'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<table style=\"margin-bottom:8px;\" class=\"tab1\">\n";
    $return .= "<tr>\n";
    $return .= "<td width=\"120\">" . $contents['title'][0] . ": </td>\n";
    $return .= "<td><input name=\"title\" id=\"title\" type=\"text\" value=\"" . $contents['title'][1] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][2] . "\" /><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "</tr>\n";
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['exp_time'][0] . ": </td>\n";
    $return .= "<td>" . $contents['exp_time'][6] . ":";
    $exp_time = "";
    if ( ! empty( $contents['exp_time'][1] ) and ! empty( $contents['exp_time'][2] ) and ! empty( $contents['exp_time'][3] ) )
    {
        $exp_time = mktime( 0, 0, 0, $contents['exp_time'][2], $contents['exp_time'][1], $contents['exp_time'][3] );
        $exp_time = date( "d.m.Y", $exp_time );
    }
    $return .= "<input name=\"exp_time\" id=\"exp_time\" value=\"" . $exp_time . "\" style=\"width: 90px;\" maxlength=\"10\" readonly=\"readonly\" type=\"text\" />\n";
    $return .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" width=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_time', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
    $return .= $contents['exp_time'][9] . ": <select name=\"hour\">\n";
    for ( $i = 0; $i <= 23; $i ++ )
    {
        $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['exp_time'][4] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
    }
    $return .= "</select> " . $contents['exp_time'][10] . ": <select name=\"min\">\n";
    for ( $i = 0; $i <= 59; $i ++ )
    {
        $return .= "<option value=\"" . $i . "\"" . ( $i == $contents['exp_time'][5] ? " selected=\"selected\"" : "" ) . ">" . $i . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['public'][0] . ": </td>\n";
    $return .= "<td><input type=\"checkbox\" value=\"1\" id=\"public\" name=\"public\"" . ( $contents['public'][1] ? " checked=\"checked\"" : "" ) . " /></td>\n";
    $return .= "</tr>\n";
    $return .= "</table>\n";
    
    $return .= "<table style=\"margin-bottom:8px;\" class=\"tab1\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['content'][0] . ":</td>\n";
    $return .= "</tr>\n";
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>\n";
    if ( $contents['content'][4] and nv_function_exists( 'nv_aleditor' ) )
    {
        $return .= nv_aleditor( "content", $contents['content'][2], $contents['content'][3], $contents['content'][1] );
    }
    else
    {
        $return .= "<textarea name=\"content\" id=\"content\" style=\"width:" . $contents['content'][2] . ";height:" . $contents['content'][3] . "\">" . $contents['content'][1] . "</textarea>\n";
    }
    $return .= "</td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
	$return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td align=\"center\"><input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" name=\"submit1\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "</table>\n";
    return $return;
}

function main_theme ( $contents )
{
    $return = "";
    $return .= "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col span=\"6\" valign=\"top\" />\n";
    $return .= "<col style=\"width:110px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    $a = 0;
    foreach ( $contents['row'] as $id => $values )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['title'][1] . "\">" . $values['title'][0] . "</a></span></td>\n";
        $return .= "<td>" . $values['add_time'] . "</td>\n";
        $return .= "<td>" . $values['exp_time'] . "</td>\n";
        $return .= "<td>" . $values['public'] . "</td>\n";
        $return .= "<td><div id=\"count_users_" . $id . "\">" . $values['users'] . "</div></td>\n";
        $return .= "<td><input name=\"select_" . $id . "\" id=\"select_" . $id . "\" type=\"checkbox\" value=\"1\" onclick=\"nv_group_change_status(" . $id . ")\"" . ( $values['act'] ? " checked=\"checked\"" : "" ) . " /></td>\n";
        $return .= "<td><span class=\"edit_icon\"><a href=\"" . $values['actions'][1] . "\">" . $values['actions'][0] . "</a></span>\n";
        $return .= "&nbsp;-&nbsp;<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"nv_group_del(" . $id . ")\">" . $values['actions'][2] . "</a></span></td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    $return .= "</table>\n";
    
    if ( isset( $contents['add_user'] ) )
    {
        $return .= "<table summary=\"" . $contents['add_user']['caption'] . "\" class=\"tab1\">\n";
        $return .= "<caption>" . $contents['add_user']['caption'] . "</caption>\n";
        $return .= "<tr>\n";
        $return .= "<td><label>" . $contents['add_user']['form_search']['label0'] . "</label>: \n";
        $return .= "<select name=\"" . $contents['add_user']['form_search']['select']['name'] . "\" id=\"" . $contents['add_user']['form_search']['select']['name'] . "\">\n";
        foreach ( $contents['add_user']['form_search']['select']['options'] as $key => $val )
        {
            $return .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
        }
        $return .= "</select>\n";
        $return .= "&nbsp;&nbsp;&nbsp;<label>" . $contents['add_user']['form_search']['label1'] . "</label>: \n";
        $return .= "<input name=\"" . $contents['add_user']['form_search']['input_txt']['name'] . "\" id=\"" . $contents['add_user']['form_search']['input_txt']['name'] . "\" type=\"text\" maxlength=\"60\" style=\"width:200px\" />\n";
        $return .= "<input type=\"hidden\" value=\"0\" name=\"is_search\" id=\"is_search\" />\n";
        $return .= "<input type=\"button\" name=\"" . $contents['add_user']['form_search']['input_submit']['name'] . "\" id=\"" . $contents['add_user']['form_search']['input_submit']['name'] . "\" value=\"" . $contents['add_user']['form_search']['input_submit']['value'] . "\" onclick=\"" . $contents['add_user']['form_search']['input_submit']['onclick'] . "\" />\n";
        $return .= "</td>\n";
        $return .= "</tr>\n";
        $return .= "</table>\n";
        
        $return .= "<div id=\"" . $contents['add_user']['containerid'] . "\"></div>\n";
        
        $return .= "<div id=\"" . $contents['containerid'] . "\"></div>\n";
        
        $return .= "<script type=\"text/javascript\">\n";
        $return .= "nv_urldecode_ajax('" . $contents['users_list'] . "','" . $contents['containerid'] . "');\n";
        $return .= "</script>\n";
    }
    
    return $return;
}

function main_list_users_theme ( $contents )
{
    $return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:20px\" />\n";
    $return .= "<col valign=\"top\" style=\"white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "<td></td>\n";
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    $a = 1;
    foreach ( $contents['row'] as $id => $values )
    {
        
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $id . "</td>\n";
        $return .= "<td><span class=\"search_icon\"><a href=\"" . $contents['action'] . $id . "\">" . $values['username'] . "</a></span></td>\n";
        $return .= "<td>" . $values['full_name'] . "</td>\n";
        $return .= "<td>" . $values['email'] . "</td>\n";
        $return .= "<td>" . $values['regdate'] . "</td>\n";
        $return .= "<td>" . $values['last_login'] . "</td>\n";
        $return .= "<td><input name=\"exclude_user_" . $id . "\" id=\"exclude_user_" . $id . "\" type=\"checkbox\" value=\"1\" title=\"" . $values['onclick'][1] . "\" onclick=\"" . $values['onclick'][0] . "\" /></td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    $return .= "</table>\n";
    if ( ! empty( $contents['generate_page'] ) )
    {
        $return .= "<div class=\"generate_page\">\n";
        $return .= $contents['generate_page'];
        $return .= "</div>\n";
    }
    return $return;
}

function nv_admin_search_users_theme ( $contents )
{
    $return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:20px\" />\n";
    $return .= "<col valign=\"top\" style=\"white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "<td>&nbsp;</td>\n";
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    $a = 0;
    foreach ( $contents['row'] as $id => $values )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $id . "</td>\n";
        $return .= "<td><span class=\"search_icon\"><a href=\"" . $contents['action'] . $id . "\">" . $values['username'] . "</a></span></td>\n";
        $return .= "<td>" . $values['full_name'] . "</td>\n";
        $return .= "<td>" . $values['email'] . "</td>\n";
        $return .= "<td>" . $values['regdate'] . "</td>\n";
        $return .= "<td>" . $values['last_login'] . "</td>\n";
        $return .= "<td><input name=\"user_" . $id . "\" id=\"user_" . $id . "\" type=\"checkbox\" value=\"1\" title=\"" . $values['onclick'][1] . "\" onclick=\"" . $values['onclick'][0] . "\" /></td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    $return .= "</table>\n";
    if ( ! empty( $contents['generate_page'] ) )
    {
        $return .= "<div class=\"generate_page\">\n";
        $return .= $contents['generate_page'];
        $return .= "</div>\n";
    }
    return $return;
}

?>