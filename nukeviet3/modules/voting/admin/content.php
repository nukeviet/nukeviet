<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */
if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['voting_edit'];
$error = '';
$vid = $nv_Request->get_int( 'vid', 'post,get' );
$submit = $nv_Request->get_string( 'submit', 'post' );
if ( ! empty( $submit ) )
{
    $question = filter_text_input( 'question', 'post', '', 1 );
    $who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
    $groups_view = $nv_Request->get_array( 'groups_view', 'post' );
    $groups_view = implode( ',', $groups_view );
    
    $publ_date = filter_text_input( 'publ_date', 'post', '' );
    $exp_date = filter_text_input( 'exp_date', 'post', '' );
    
    if ( ! empty( $publ_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date ) ) $publ_date = "";
    if ( ! empty( $exp_date ) and ! preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date ) ) $exp_date = "";
    
    if ( empty( $publ_date ) )
    {
        $begindate = NV_CURRENTTIME;
    }
    else
    {
        $phour = $nv_Request->get_int( 'phour', 'post', 0 );
        $pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
        unset( $m );
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $publ_date, $m );
        $begindate = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
    }
    
    if ( empty( $exp_date ) )
    {
        $enddate = 0;
    }
    else
    {
        $ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
        $emin = $nv_Request->get_int( 'emin', 'post', 0 );
        unset( $m );
        preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_date, $m );
        $enddate = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
    }
    //end Exprire date
    

    $answer = $nv_Request->get_array( 'answervote', 'post' );
    $maxoption = $nv_Request->get_int( 'maxoption', 'post', 1 );
    if ( empty( $vid ) )
    {
        $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` (`vid`, `question`, `acceptcm`, `admin_id`, `who_view`, `groups_view`, `publ_time`, `exp_time`, `act`) VALUES (NULL, " . $db->dbescape( $question ) . ", " . $maxoption . "," . $admin_info['admin_id'] . ", " . $who_view . ", " . $db->dbescape( $groups_view ) . ", 0,0,1)";
        $vid = $db->sql_query_insert_id( $query );
    }
    
    $maxoption_data = 0;
    foreach ( $answer as $id => $title )
    {
        $title = nv_htmlspecialchars( strip_tags( $title ) );
        if ( $title != "" )
        {
            $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `title` = " . $db->dbescape( $title ) . " WHERE `id` ='" . intval( $id ) . "' AND `vid` =" . $vid . "" );
            $maxoption_data ++;
        }
        else
        {
            $db->sql_query( "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id` ='" . intval( $id ) . "' AND `vid` =" . $vid . "" );
        }
    }
    $answervotenews = $nv_Request->get_array( 'answervotenews', 'post' );
    foreach ( $answervotenews as $key => $title )
    {
        $title = nv_htmlspecialchars( strip_tags( $title ) );
        if ( $title != "" )
        {
            $query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_rows` (`id`, `vid`, `title`, `hitstotal`) VALUES (NULL, " . $db->dbescape( $vid ) . ", " . $db->dbescape( $title ) . ", '0')";
            if ( $db->sql_query_insert_id( $query ) )
            {
                $maxoption_data ++;
            }
        }
    }
    if ( $maxoption > $maxoption_data )
    {
        $maxoption = $maxoption_data;
    }
    
    $query = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `question`=" . $db->dbescape( $question ) . ", `acceptcm` =  " . $maxoption . ", `admin_id` =  " . $admin_info['admin_id'] . ", `who_view`=" . $who_view . ", `groups_view` = " . $db->dbescape( $groups_view ) . ", `publ_time`=" . $begindate . ", `exp_time`=" . $enddate . " WHERE `vid` =" . $vid . "";
    if ( $db->sql_query( $query ) )
    {
        Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "" );
        die();
    }
}
else
{
    $j = 0;
    if ( $vid > 0 )
    {
        $queryvote = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE vid=" . $vid . "";
        $rowvote = $db->sql_fetchrow( $db->sql_query( $queryvote ) );
    }
    else
    {
        $rowvote = array( 
            "who_view" => 0, "groups_view" => "", "publ_time" => NV_CURRENTTIME, "exp_time" => "", "acceptcm" => 1, "question" => "", "alias" => "" 
        );
    }
    
    $my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
    
    $contents .= "<form method=\"post\" action=\"\">";
    $contents .= "<table class=\"tab1\" id=\"items\">\n";
    $j ++;
    $class = ( $j % 2 == 0 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_allowcm'] . "</td>\n";
    $contents .= "<td>";
    
    $array_who_view = array( 
        $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
    );
    $array_allowed_comm = array( 
        $lang_global['no'], $lang_global['who_view0'], $lang_global['who_view1'] 
    );
    
    $groups_list = nv_groups_list();
    $tdate = date( "d|m|Y|H|i" );
    list( $pday, $pmonth, $pyear, $phour, $pmin ) = explode( "|", $tdate );
    
    $emonth = $eday = $eyear = $emin = $ehour = 0;
    
    $contents .= "<select name=\"who_view\" id=\"who_view\" onchange=\"nv_sh('who_view','groups_list')\" style=\"width: 250px;\">\n";
    $who_view = $rowvote['who_view'];
    foreach ( $array_who_view as $k => $w )
    {
        $sel = ( $who_view == $k ) ? 'selected' : '';
        $contents .= "<option value=\"" . $k . "\" " . $sel . ">" . $w . "</option>\n";
    }
    $contents .= "</select><br>\n";
    $contents .= "<div id=\"groups_list\" style=\"" . ( $who_view == 3 ? "visibility:visible;display:block;" : "visibility:hidden;display:none;" ) . "\">\n";
    $contents .= "" . $lang_global['groups_view'] . ":\n";
    $contents .= "<table style=\"margin-bottom:8px;width:250px;\">\n";
    $contents .= "<col valign=\"top\" width=\"150px\" />\n";
    $contents .= "<tr>\n";
    $contents .= "<td>\n";
    $groups_view = explode( ',', $rowvote['groups_view'] );
    foreach ( $groups_list as $group_id => $grtl )
    {
        $sel = ( in_array( $group_id, $groups_view ) ) ? ' checked="yes"' : '';
        $contents .= "<p><input name=\"groups_view[]\" type=\"checkbox\" " . $sel . " value=\"" . $group_id . "\">" . $grtl . "</p>\n";
    }
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</table>\n";
    $contents .= "</div>\n";
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    
    $j ++;
    $class = ( $j % 2 == 0 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_time'] . "</td>\n";
    $contents .= "<td>";
    
    $tdate = date( "H|i", $rowvote['publ_time'] );
    $publ_date = date( "d.m.Y", $rowvote['publ_time'] );
    list( $phour, $pmin ) = explode( "|", $tdate );
    
    // Begin: thoi gian dang
    $contents .= "<input name=\"publ_date\" id=\"publ_date\" value=\"" . $publ_date . "\" style=\"width: 90px;\" maxlength=\"10\" readonly=\"readonly\" type=\"text\">\n";
    $contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'publ_date', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
    $contents .= "<select name=\"phour\">\n";
    for ( $i = 0; $i < 23; $i ++ )
    {
        $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
    }
    $contents .= "</select>:<select name=\"pmin\">\n";
    for ( $i = 0; $i < 60; $i ++ )
    {
        $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
    }
    $contents .= "</select>\n";
    // End: thoi gian dang
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    
    $j ++;
    $class = ( $j % 2 == 0 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_timeout'] . "</td>\n";
    $contents .= "<td>";
    
    // Begin: thoi gian ket thuc
    if ( $rowvote['exp_time'] > 0 )
    {
        $tdate = date( "H|i", $rowvote['exp_time'] );
        $exp_date = date( "d.m.Y", $rowvote['exp_time'] );
        list( $ehour, $emin ) = explode( "|", $tdate );
    }
    else
    {
        $emin = $ehour = 0;
        $exp_date = "";
    }
    $contents .= "<input name=\"exp_date\" id=\"exp_date\" value=\"" . $exp_date . "\" style=\"width: 90px;\" maxlength=\"10\" readonly=\"readonly\" type=\"text\">\n";
    $contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_date', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
    $contents .= "<select name=\"ehour\">\n";
    for ( $i = 0; $i < 23; $i ++ )
    {
        $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
    }
    $contents .= "</select>:<select name=\"emin\">\n";
    for ( $i = 0; $i < 60; $i ++ )
    {
        $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
    }
    $contents .= "</select>\n";
    // End: thoi gian ket thuc
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    
    $sql = "SELECT `id`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `vid`='" . $vid . "' ORDER BY `id` ASC";
    $result = $db->sql_query( $sql );
    $max_acceptcm = $db->sql_numrows( $result );
    $max_acceptcm = ( $max_acceptcm > 0 ) ? $max_acceptcm : 1;
    
    $j ++;
    $class = ( $j % 2 == 0 ) ? " class=\"second\"" : "";
    $contents .= "<tbody" . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_maxoption'] . "</td>\n";
    $contents .= "<td><select name=\"maxoption\">\n";
    for ( $i = 1; $i <= $max_acceptcm; $i ++ )
    {
        $contents .= "<option value=\"" . $i . "\"" . ( ( $i == $rowvote['acceptcm'] ) ? " selected=\"selected\"" : "" ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
    }
    $contents .= "</select></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['voting_question'] . "</td>\n";
    $contents .= "<td><input type=\"text\" name=\"question\" size=\"60\" value=\"" . $rowvote['question'] . "\"></td>\n";
    $contents .= "</tr>\n";
    
    $items = 0;
    while ( list( $id, $title ) = $db->sql_fetchrow( $result ) )
    {
        $j ++;
        $class = ( $j % 2 == 0 ) ? " class=\"second\"" : "";
        $contents .= "<tbody" . $class . ">\n";
        $contents .= "<tr>\n";
        $contents .= "<td style=\"text-align:right\">" . $lang_module['voting_question_num'] . ( ++ $items ) . "</td>\n";
        $contents .= "<td><input type=\"text\" value=\"" . $title . "\" name=\"answervote[$id]\" size=\"60\"></td>\n";
        $contents .= "</tr>\n";
        $contents .= "</tbody>\n";
    }
    $j ++;
    $class = ( $j % 2 == 0 ) ? " class=\"second additem\"" : " class=\"additem\"";
    
    $contents .= "<tbody " . $class . ">\n";
    $contents .= "<tr>\n";
    $contents .= "	<td style=\"text-align:right\">" . $lang_module['voting_question_num'] . ( ++ $items ) . "</td>\n";
    $contents .= "	<td><input type=\"text\" value=\"" . $title . "\" name=\"answervotenews[]\" size=\"60\"></td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
    $contents .= "</table>\n";
    $contents .= "<br><div style=\"text-align:center\"><input type=\"button\" value=\"" . $lang_module['add_answervote'] . "\" onclick=\"nv_vote_additem('" . $lang_module['voting_question_num'] . "');\"><input type=\"submit\" name=\"submit\" value=\"" . $lang_module['voting_confirm'] . "\"></div>\n";
    $contents .= "</form>\n";
    $contents .= "<script type=\"text/javascript\">
					var items=" . $items . ";
				   </script>";
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>