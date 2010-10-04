<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-2-2010 12:55
 */
if ( ! defined( 'NV_IS_FILE_THEMES' ) ) die( 'Stop!!!' );
$submit = 0;
$bid = $nv_Request->get_int( 'bid', 'get,post', 0 );
$functionid = $nv_Request->get_int( 'func', 'get' );
$blockredirect = $nv_Request->get_string( 'blockredirect', 'get' );
$select_options = array();
$contents_error = '';
$theme_array = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
foreach ( $theme_array as $themes_i )
{
    $select_options[NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add&selectthemes=" . $themes_i] = $themes_i;
}
$selectthemes = $global_config['site_theme'];
$page_title = $lang_module['blocks'] . ':' . $selectthemes;
if ( $nv_Request->isset_request( 'confirm', 'post' ) )
{
    $error = array();
    
    $title = filter_text_input( 'title', 'post', '', 1 );
    $groupbl = filter_text_input( 'groupbl', 'post', '', 1 );
    
    if ( empty( $title ) )
    {
        $error[] = $lang_module['block_error_title'];
    }
    $link = filter_text_input( 'link', 'post' );
    if ( ! empty( $link ) and ! nv_is_url( $link ) )
    {
        $error[] = $lang_module['error_invalid_url'];
    }
    $link = nv_htmlspecialchars( $link );
    
    $template = filter_text_input( 'template', 'post', "", 1 );
    $typeblock = filter_text_input( 'typeblock', 'post', "", 1 );
    $xmodule = filter_text_input( 'module', 'post', "", 1 );
    $xfile = filter_text_input( 'file', 'post', "", 1 );
    $xbanner = $nv_Request->get_int( 'banner', 'post' );
    $xrss = filter_text_input( 'xrss', 'post', "", 0 );
    
    $leavegroup = $nv_Request->get_int( 'leavegroup', 'post' );
    $xhtml = filter_text_textarea( 'htmlcontent', '', NV_ALLOWED_HTML_TAGS );
    $xhtml = defined( 'NV_EDITOR' ) ? nv_editor_nl2br( $xhtml ) : nv_nl2br( $xhtml, '<br />' );
    if ( $typeblock == "banner" )
    {
        $file_path = $xbanner;
    }
    elseif ( $typeblock == "html" )
    {
        $file_path = $xhtml;
    }
    elseif ( $typeblock == "rss" )
    {
        $file_path = $xrss;
        $template = filter_text_input( 'templaterss', 'post', "", 0 );
    }
    else
    {
        $file_path = $xfile;
    }
    if ( empty( $xfile ) && empty( $typeblock ) )
    {
        $error[] = $lang_module['error_empty_content'];
    }
    elseif ( $typeblock == "rss" and ! nv_is_url( $xrss ) )
    {
        $error[] = $lang_module['block_rss_url_error'];
    }
    
    $exp_time = filter_text_input( 'exp_time', 'post', "", 1 );
    if ( ! empty( $exp_time ) && preg_match( "/^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})$/", $exp_time ) )
    {
        $exp_time = explode( '.', $exp_time );
        $exp_time = mktime( 0, 0, 0, $exp_time[1], $exp_time[0], $exp_time[2] );
    }
    else
    {
        $exp_time = 0;
    }
    $position = filter_text_input( 'position', 'post', "", 0 );
    $active = $nv_Request->get_int( 'active', 'post', 0 );
    $who_view = $nv_Request->get_int( 'who_view', 'post', 0 );
    $all_func = ( $nv_Request->get_int( 'all_func', 'post' ) == 1 ) ? 1 : 0;
    $array_funcid = $nv_Request->get_array( 'func_id', 'post' );
    if ( ! empty( $error ) )
    {
        $contents_error .= "<div id='edit'></div>\n";
        $contents_error .= "<div class=\"quote\" style=\"width:740px;\">\n";
        $contents_error .= "<blockquote class='error'><span id='message'>" . implode( "<br>", $error ) . "</span></blockquote>\n";
        $contents_error .= "</div>\n";
        $row = array( 
            'bid' => $bid, 'title' => $title, 'link' => $link, 'xfile' => $xfile, 'xrss' => $xrss, 'xbanner' => $xbanner, 'xhtml' => $xhtml, 'template' => $template, 'type' => $typeblock, 'position' => $position, 'exp_time' => $exp_time, 'active' => $active, 'groups_view' => $who_view, 'all_func' => $all_func, 'func_id' => $array_funcid, 'module' => '' 
        );
        $submit = 1;
    }
    else
    {
        if ( $all_func )
        {
            $array_funcid = array();
            $func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' ORDER BY `in_module` ASC, `subweight` ASC" );
            while ( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
            {
                $array_funcid[] = $func_id_i;
            }
        }
        elseif ( ! empty( $bid ) && empty( $array_funcid ) )
        {
            $array_funcid = array();
            $func_result = $db->sql_query( "SELECT `func_id` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' AND `in_module`='" . $xmodule . "' ORDER BY `in_module` ASC, `subweight` ASC" );
            while ( list( $func_id_i ) = $db->sql_fetchrow( $func_result ) )
            {
                $array_funcid[] = $func_id_i;
            }
        }
        
        if ( is_array( $array_funcid ) )
        {
            $func_list = array();
            if ( $bid > 0 )
            {
                list( $groupbl, $weight ) = $db->sql_fetchrow( $db->sql_query( "SELECT groupbl, weight FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" ) );
                $result_func = $db->sql_query( "SELECT func_id FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . intval( $groupbl ) . " AND theme='" . $selectthemes . "'" );
                while ( list( $func_inlist ) = $db->sql_fetchrow( $result_func ) )
                {
                    $func_list[] = $func_inlist;
                }
            }
            list( $maxgroup ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(groupbl) FROM `" . NV_BLOCKS_TABLE . "`" ) );
            $newgroupbl = intval( $maxgroup ) + 1;
            if ( $leavegroup > 0 )
            {
                $sql = "UPDATE `" . NV_BLOCKS_TABLE . "` SET all_func='0' WHERE groupbl=" . $groupbl . " AND theme='" . $selectthemes . "'";
                $db->sql_query( $sql );
                
                #update all blocks and functions exist
                foreach ( $array_funcid as $func_id )
                {
                    if ( in_array( $func_id, $func_list ) )
                    {
                        $result = $db->sql_query( "SELECT bid FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . intval( $groupbl ) . " AND func_id=" . $func_id . " AND theme='" . $selectthemes . "'" );
                        while ( list( $bids ) = $db->sql_fetchrow( $result ) )
                        {
                            $sql = "UPDATE `" . NV_BLOCKS_TABLE . "` SET groupbl='" . $newgroupbl . "', title=" . $db->dbescape( $title ) . ", link =" . $db->dbescape( $link ) . ", type=" . $db->dbescape_string( $typeblock ) . ", file_path=" . $db->dbescape_string( $file_path ) . ", template=" . $db->dbescape( $template ) . ", exp_time=" . $db->dbescape( $exp_time ) . ",position=" . $db->dbescape( $position ) . ", active=" . $active . ", groups_view=" . $db->dbescape( $who_view ) . ", module=" . $db->dbescape( $xmodule ) . " WHERE bid=" . $bids . "";
                            $db->sql_query( $sql );
                        }
                    }
                    else
                    {
                        #insert if not exist in list
                        list( $maxweight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl='" . $groupbl . "' AND func_id='" . $func . "'" ) );
                        $sql = "INSERT INTO `" . NV_BLOCKS_TABLE . "` (`bid`, `groupbl`, `title` ,`link` ,`type` ,`file_path` ,`theme`, `template` ,`position` ,`exp_time` ,`active` , `groups_view`,`module`, `all_func`, `func_id` ,`weight`) VALUES (NULL, " . $db->dbescape( $newgroupbl ) . ", " . $db->dbescape( $title ) . ", " . $db->dbescape( $link ) . ", " . $db->dbescape( $typeblock ) . ", " . $db->dbescape_string( $file_path ) . ", " . $db->dbescape( $selectthemes ) . ", " . $db->dbescape( $template ) . "," . $db->dbescape( $position ) . ", " . $db->dbescape( $exp_time ) . "," . $active . ", " . $db->dbescape( $who_view ) . ", " . $db->dbescape( $xmodule ) . ", '0', " . $db->dbescape( $func_id ) . "," . $newgroupbl . ")";
                        $db->sql_query( $sql );
                    }
                }
            }
            else
            {
                if ( $bid > 0 )
                {
                    $array_funcid_old = array_diff( $func_list, $array_funcid );
                    foreach ( $array_funcid_old as $func_id )
                    {
                        $db->sql_query( "DELETE FROM " . NV_BLOCKS_TABLE . " WHERE groupbl='" . $groupbl . "' AND func_id='" . $func_id . "' AND theme='" . $selectthemes . "'" );
                    }
                    $sql = "UPDATE `" . NV_BLOCKS_TABLE . "` SET `title`=" . $db->dbescape( $title ) . ", `link` =" . $db->dbescape( $link ) . ", `type`=" . $db->dbescape_string( $typeblock ) . ", `file_path`=" . $db->dbescape_string( $file_path ) . ", `template`=" . $db->dbescape( $template ) . ", `exp_time`=" . $db->dbescape( $exp_time ) . ",`position`=" . $db->dbescape( $position ) . ", `active`=" . $active . ", `groups_view`=" . $db->dbescape( $who_view ) . ", `module`=" . $db->dbescape( $xmodule ) . ", `all_func`=" . $all_func . " WHERE `groupbl`=" . $groupbl . " AND theme='" . $selectthemes . "'";
                    $db->sql_query( $sql );
                }
                else
                {
                    $groupbl = $newgroupbl;
                }
                foreach ( $array_funcid as $func_id )
                {
                    if ( ! in_array( $func_id, $func_list ) )
                    {
                        #insert if not exist in list
                        list( $maxweight ) = $db->sql_fetchrow( $db->sql_query( "SELECT MAX(weight) FROM `" . NV_BLOCKS_TABLE . "` WHERE position='" . $position . "' AND func_id='" . $func_id . "'" ) );
                        $sql = "INSERT INTO `" . NV_BLOCKS_TABLE . "` (`bid`, `groupbl`, `title` ,`link` ,`type` ,`file_path` ,`theme`, `template` ,`position` ,`exp_time` ,`active` , `groups_view`,`module`,`all_func`, `func_id` ,`weight`) VALUES (NULL, " . $db->dbescape( $groupbl ) . ", " . $db->dbescape( $title ) . ", " . $db->dbescape( $link ) . ", " . $db->dbescape( $typeblock ) . ", " . $db->dbescape_string( $file_path ) . ", " . $db->dbescape( $selectthemes ) . ", " . $db->dbescape( $template ) . "," . $db->dbescape( $position ) . ", " . $db->dbescape( $exp_time ) . "," . $active . ", " . $db->dbescape( $who_view ) . ", " . $db->dbescape( $xmodule ) . ", " . $all_func . ", " . $db->dbescape( $func_id ) . "," . ( $maxweight + 1 ) . ")";
                        $db->sql_query( $sql );
                    }
                }
            }
        }
        if ( empty( $blockredirect ) )
        {
            $blockredirect = 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=blocks';
        }
        echo '<script type="text/javascript">
    			parent.location="' . nv_base64_decode( $blockredirect ) . '";
    		</script>';
        die();
    }
}
if ( $bid > 0 and $submit == 0 )
{
    $result = $db->sql_query( "SELECT * FROM `" . NV_BLOCKS_TABLE . "` WHERE bid=" . $bid . "" );
    if ( $db->sql_numrows( $result ) > 0 )
    {
        $row = $db->sql_fetchrow( $result );
        $row['xfile'] = ( $row['type'] == 'file' ) ? $row['file_path'] : "";
        $row['xbanner'] = ( $row['type'] == 'banner' ) ? $row['file_path'] : "";
        $row['xhtml'] = ( $row['type'] == 'html' ) ? $row['file_path'] : "";
        $row['xrss'] = ( $row['type'] == 'rss' ) ? $row['file_path'] : "";
        $submit = 1;
    }
}
if ( empty( $submit ) )
{
    $row = array( 
        'bid' => 0, 'title' => "", 'groupbl' => '', 'link' => "", 'xfile' => "", 'xbanner' => "", 'xhtml' => "", 'template' => "", 'type' => "", 'file_path' => "", 'position' => "", 'exp_time' => "", 'active' => 1, 'who_view' => "", 'groups_view' => "", 'all_func' => 1, 'func_id' => '', 'module' => '' 
    );
}

$sql = "SELECT `func_id` , `func_custom_name` , `in_module` FROM `" . NV_MODFUNCS_TABLE . "` WHERE `show_func` = '1' ORDER BY `in_module` ASC, `subweight` ASC";
$func_result = $db->sql_query( $sql );
$aray_mod_func = array();
while ( list( $id_i, $func_custom_name_i, $in_module_i ) = $db->sql_fetchrow( $func_result ) )
{
    $aray_mod_func[$in_module_i][] = array( 
        "id" => $id_i, "func_custom_name" => $func_custom_name_i 
    );
}

$contents .= "<link rel=\"StyleSheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/css/admin.css\" type=\"text/css\" />";
$contents .= "<link type='text/css' href='" . NV_BASE_SITEURL . "js/ui/jquery.ui.all.css' rel='stylesheet' />\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.min.js\"></script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery-ui-1.8.2.custom.js\"></script>\n";
$contents .= "<script type=\"text/javascript\">\n";
$contents .= "var nv_siteroot = '" . NV_BASE_SITEURL . "'\n";
$contents .= "</script>\n";
$contents .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/popcalendar/popcalendar.js\"></script>\n";
if ( $bid != 0 )
{
    $contents .= "<div class=\"quote\" style=\"width:740px;\">\n";
    $contents .= "<blockquote class='error'><span id='message'>" . $lang_module['block_group_notice'] . "</span></blockquote>\n";
    $contents .= "</div>\n";
}
$contents .= "" . $contents_error . "<div style='clear:both'></div>";
$contents .= "<form method='post' action='" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=themes&" . NV_OP_VARIABLE . "=front_add&blockredirect=" . $blockredirect . "'>";
$contents .= "<table class=\"tab1\" style=\"WIDTH:100%\">\n";
$contents .= "<col style=\"width: 160px; white-space: nowrap;\">";
$contents .= "<col style=\"width: 600px; white-space: nowrap;\">";
$contents .= "<tbody>\n";
$array_typeblock = array( 
    "file" => $lang_module['block_file'], "banner" => $lang_module['block_b_pl'], "html" => $lang_module['block_typehtml'], "rss" => $lang_module['block_typerss'] 
);
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_type'] . ":</td>\n";
$contents .= "<td>";
foreach ( $array_typeblock as $b_key => $b_value )
{
    $ck = ( $row['type'] == $b_key ) ? " checked" : "";
    $contents .= "<label><input type=\"radio\" name=\"typeblock\" value=\"" . $b_key . "\" " . $ck . " />  " . $b_value . "</label> ";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$showstype = ( $row['type'] == 'file' ) ? "" : " style='display:none' ";
$contents .= "<tbody " . $showstype . " id='file'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_file'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<select name='module'>";
$contents .= "<option value=\"\"> " . $lang_module['block_select_type'] . "</option>";
$contents .= "<option value=\"global\" " . ( ( $row['module'] == 'global' ) ? ' selected' : '' ) . "> " . $lang_module['block_type_global'] . "</option>";
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
    if ( isset( $aray_mod_func[$m_title] ) and count( $aray_mod_func[$m_title] ) > 0 )
    {
        $sel = ( $m_title == trim( $row['module'] ) ) ? ' selected' : '';
        $contents .= "<option value=\"" . $m_title . "\" " . $sel . "> " . $m_custom_title . "</option>";
    }
}
$contents .= "</select>";
$contents .= "<select name=\"file\"></select>\n";
$contents .= '
<script>
$("select[name=file]").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadblocks&type=' . $row['module'] . '&bid=' . $bid . '");
</script>
';
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$showstype = ( $row['type'] == 'banner' ) ? "" : " style='display:none' ";
$contents .= "<tbody " . $showstype . " id='banner'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_banners_pl'] . ":</td>\n";
$contents .= "<td><select name=\"banner\">\n";
$contents .= "<option value=\"\">" . $lang_module['block_filename'] . "</option>\n";
$query = "SELECT * FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` WHERE (`blang`='" . NV_LANG_DATA . "' OR `blang`='') ORDER BY `title` ASC";
$result = $db->sql_query( $query );
$banners_pl_list = array();
while ( $row_bpn = $db->sql_fetchrow( $result ) )
{
    $banners_pl_list[$row_bpn['id']] = $row_bpn;
}
foreach ( $banners_pl_list as $row_bpn )
{
    $value = $row_bpn['title'] . " (";
    $value .= ( ( ! empty( $row['blang'] ) and isset( $language_array[$row_bpn['blang']] ) ) ? $language_array[$row_bpn['blang']]['name'] : $lang_module['blang_all'] ) . ", ";
    $value .= $row_bpn['form'] . ", ";
    $value .= $row_bpn['width'] . "x" . $row_bpn['height'] . "px";
    $value .= ")";
    $sel = ( $row['xbanner'] == $row_bpn['id'] ) ? ' selected' : '';
    $contents .= "<option value=\"" . $row_bpn['id'] . "\" " . $sel . ">" . $value . "</option>\n";
}
$contents .= "</select></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$showstype = ( $row['type'] == 'html' ) ? "" : " style='display:none' ";
$contents .= "<tbody " . $showstype . " id='html'>\n";
$contents .= "<tr>\n";
$contents .= "<td colspan='2'>" . $lang_module['block_content'] . ":<br />\n";
if ( defined( 'NV_EDITOR' ) )
{
    require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}
$row['xhtml'] = ( defined( 'NV_EDITOR' ) ) ? nv_editor_br2nl( $row['xhtml'] ) : nv_br2nl( $row['xhtml'] );
$row['xhtml'] = nv_htmlspecialchars( $row['xhtml'] );
if ( defined( 'NV_EDITOR' ) and function_exists( 'nv_aleditor' ) )
{
    $contents .= nv_aleditor( "htmlcontent", '700px', '150px', $row['xhtml'] );
}
else
{
    $contents .= "<textarea style=\"width: 700px\" name=\"htmlcontent\" id=\"htmlcontent\" cols=\"20\" rows=\"8\">" . $row['xhtml'] . "</textarea>";
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$showstype = ( $row['type'] == 'rss' ) ? "" : " style='display:none' ";
$contents .= "<tbody " . $showstype . " id='rss'>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_rss_url'] . ":</td>\n";
$contents .= "<td><input name=\"xrss\" type=\"text\" value=\"" . $row['xrss'] . "\" style=\"width:500px\"/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_title'] . ":</td>\n";
$contents .= "<td><input name=\"title\" type=\"text\" value=\"" . $row['title'] . "\" style=\"width:300px\"/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_link'] . ":</td>\n";
$contents .= "<td><input name=\"link\" type=\"text\" value=\"" . $row['link'] . "\" style=\"width:300px\"/></td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_tpl'] . ":</td>\n";
$contents .= "<td>";

$showstype = ( $row['type'] != 'rss' ) ? "" : " style='display:none' ";
$contents .= "<select " . $showstype . " id=\"template\" name=\"template\">\n";
$contents .= "<option value=\"\">" . $lang_module['block_default'] . "</option>\n";
$templ_list = nv_scandir( NV_ROOTDIR . "/themes/" . $selectthemes . "/layout", "/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/" );
$templ_list = preg_replace( "/^block\.([a-zA-Z0-9\-\_]+)\.tpl$/", "\\1", $templ_list );
foreach ( $templ_list as $value )
{
    if ( ! empty( $value ) and $value != "default" )
    {
        $sel = ( $row['template'] == $value ) ? ' selected' : '';
        $contents .= "<option value=\"" . $value . "\" " . $sel . ">" . $value . "</option>\n";
    }
}
$contents .= "</select>";
$showstype = ( $row['type'] == 'rss' ) ? "" : " style='display:none' ";
$contents .= "<select " . $showstype . " id=\"templaterss\" name=\"templaterss\">\n";
$templ_list = nv_scandir( NV_ROOTDIR . "/themes/" . $selectthemes . "/layout", "/^block\.rss\.([a-zA-Z0-9\-\_]+)\.tpl$/" );
$templ_list = preg_replace( "/^block\.([a-zA-Z0-9\-\_\.]+)\.tpl$/", "\\1", $templ_list );
foreach ( $templ_list as $value )
{
    if ( ! empty( $value ) and $value != "default" )
    {
        $sel = ( $row['template'] == $value ) ? ' selected' : '';
        $contents .= "<option value=\"" . $value . "\" " . $sel . ">" . $value . "</option>\n";
    }
}
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_pos'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<select name='position'>";
$tag = $nv_Request->get_string( 'tag', 'get' );
#load position file
$xml = @simplexml_load_file( NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini' ) or nv_info_die( $lang_global['error_404_title'], $lang_module['block_error_fileconfig_title'], $lang_module['block_error_fileconfig_content'] );
;
$content = $xml->xpath( 'positions' ); //array
$positions = $content[0]->position; //object
for ( $i = 0; $i < count( $positions ); $i ++ )
{
    $sel = ( $tag == $positions[$i]->tag || $row['position'] == $positions[$i]->tag ) ? ' selected' : '';
    $contents .= "<option value=\"" . $positions[$i]->tag . "\" " . $sel . "> " . $positions[$i]->name . '</option>';
}
$contents .= "</select>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_exp_time'] . ":</td>\n";
$contents .= "<td>";
$contents .= "<input name=\"exp_time\" id=\"exp_time\" value=\"" . ( ( $row['exp_time'] > 0 ) ? date( 'd.m.Y', $row['exp_time'] ) : '' ) . "\" style=\"width: 90px;\" maxlength=\"10\" type=\"text\">\n";
$contents .= "<img src=\"" . NV_BASE_SITEURL . "images/calendar.jpg\" widht=\"18\" style=\"cursor: pointer; vertical-align: middle;\" onclick=\"popCalendar.show(this, 'exp_time', 'dd.mm.yyyy', false);\" alt=\"\" height=\"17\">\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody>\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_active'] . ":</td>\n";
$sel = ( intval( $row['active'] ) == 1 ) ? "checked=\"checked\"" : "";
$contents .= "<td><input type=\"checkbox\" name=\"active\" value=\"1\" " . $sel . " /> " . $lang_module['block_yes'] . "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['block_group'] . ":</td>\n";
$contents .= "<td>";
$array_who_view = array( 
    $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'], $lang_global['who_view3'] 
);
$groups_list = nv_groups_list();
$contents .= "<select name=\"who_view\" style=\"width: 250px;\">\n";
$row['groups_view'] = intval( $row['groups_view'] );
foreach ( $array_who_view as $k => $w )
{
    $contents .= "<option value=\"" . $k . "\" " . ( ( $k == $row['groups_view'] ) ? ' selected' : '' ) . ">" . $w . "</option>\n";
}
$contents .= "</select>\n";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

if ( $bid != 0 )
{
    $contents .= "<tbody>\n";
    $contents .= "<tr>\n";
    $contents .= "<td>" . $lang_module['block_groupbl'] . ":</td>\n";
    $contents .= "<td><span style='color:red;font-weight:bold'>" . $row['groupbl'] . "</span>";
    list( $blocks_num ) = $db->sql_fetchrow( $db->sql_query( "SELECT COUNT(bid) FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . $row['groupbl'] . "" ) );
    $contents .= "&nbsp;&nbsp;&nbsp;<label><input type='checkbox' value='1' name='leavegroup'/>  " . $lang_module['block_leavegroup'] . ' (' . $blocks_num . ' ' . $lang_module['block_count'] . ')</label>';
    $contents .= "</td>\n";
    $contents .= "</tr>\n";
    $contents .= "</tbody>\n";
}
$contents .= "<tbody class=\"second\">\n";
$contents .= "<tr>\n";
$contents .= "<td>" . $lang_module['add_block_module'] . ":</td>\n";
$contents .= "<td>";
$add_block_module = array( 
    1 => $lang_module['add_block_all_module'], 0 => $lang_module['add_block_select_module'] 
);
$i = 1;
foreach ( $add_block_module as $b_key => $b_value )
{
    $ck = ( $row['all_func'] == $b_key ) ? " checked" : "";
    $showsdisplay = ( $row['type'] == 'file' and $row['module'] != 'global' and $b_key == 1 ) ? " style='display:none'" : "";
    $contents .= "<label id='labelmoduletype" . $i . "' " . $showsdisplay . "><input type=\"radio\" name=\"all_func\" class='moduletype" . $i . "' value=\"" . $b_key . "\" " . $ck . " />  " . $b_value . "</label> ";
    $i ++;
}
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$shows_all_func = ( intval( $row['all_func'] ) ) ? " style='display:none' " : "";
$contents .= "<tbody " . $shows_all_func . " id='shows_all_func'>\n";
$contents .= "<tr>\n";
$contents .= "<td style='vertical-align:top'>" . $lang_module['block_function'] . ":<br><br><label><input type='button' name='checkmod' value='" . $lang_module['block_check'] . "'style='margin-bottom:5px;'/></label></td>\n";
$contents .= "<td>\n";
$contents .= "<div style=\"width: 600px; overflow: auto;\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"3\">";
$func_list = array();
if ( $bid )
{
    $result_func = $db->sql_query( "SELECT func_id FROM `" . NV_BLOCKS_TABLE . "` WHERE groupbl=" . $row['groupbl'] . "" );
    while ( list( $func_inlist ) = $db->sql_fetchrow( $result_func ) )
    {
        $func_list[] = $func_inlist;
    }
}
$sql = "SELECT title, custom_title FROM `" . NV_MODULES_TABLE . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
while ( list( $m_title, $m_custom_title ) = $db->sql_fetchrow( $result ) )
{
    if ( isset( $aray_mod_func[$m_title] ) and count( $aray_mod_func[$m_title] ) > 0 )
    {
        $contents .= "<tr class=\"funclist\" id=\"$m_title\"><td style=\"font-weight:bold\" nowrap=\"nowrap\"> " . $m_custom_title . "</td>";
        foreach ( $aray_mod_func[$m_title] as $aray_mod_func_i )
        {
            $sel = ( in_array( $aray_mod_func_i['id'], $func_list ) || $functionid == $aray_mod_func_i['id'] ) ? ' checked=checked' : '';
            $contents .= "<td nowrap=\"nowrap\"><label><input style type=\"checkbox\" " . $sel . " name=\"func_id[]\" value=\"" . $aray_mod_func_i['id'] . "\"> " . $aray_mod_func_i['func_custom_name'] . "</label></td>";
        }
        $contents .= "</tr>";
    }
}
$contents .= "</table></div>";
$contents .= "</td>\n";
$contents .= "</tr>\n";
$contents .= "</tbody>\n";

$contents .= "</table>\n";
$contents .= "<div style=\"PADDING-TOP:10px;text-align:center\">\n";
if ( $bid != 0 )
{
    $contents .= "<input type='hidden' name='bid' value='" . $bid . "'/>";
}
$contents .= "<input type=\"submit\" name='confirm' value=\"" . $lang_module['block_confirm'] . "\" />\n";
$contents .= "</div></form>\n";
$contents .= '
<script type="text/javascript">
$(function(){
	var typeinit = "' . $row['module'] . '";
	if (typeinit!=""){
		if (typeinit!="global"){
			$("tr.funclist").css({"display":"none"});
			$("tr#"+typeinit).css({"display":"block"});
		} else {
			$("tr.funclist").css({"display":"block"});
		}
	}
	$("select[name=module]").change(function(){
		var type = $("select[name=module]").val();
		if (type!=""){
			$("select[name=file]").load("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=loadblocks&type="+type);
			if (type!="global"){
				$("#labelmoduletype1").css({"display":"none"});		
				$("tr.funclist").css({"display":"none"});
				$("tr#"+type).css({"display":"block"});
				var $radios = $("input:radio[name=all_func]");
	        	$radios.filter("[value=0]").attr("checked", true);
	        	$("#shows_all_func").show();
			} else {
				$("tr.funclist").css({"display":"block"});
				$("#labelmoduletype1").css({"display":""});	
			}
		}
	});
	$("input[name=typeblock]").click(function(){
		var type = $(this).val();
    	if (type=="file"){
    		$("#file").show();
    		$("#banner").hide();
    		$("#html").hide();
    		var module = $("select[name=module]").val();
    		if (module!="global"){
    			$("#labelmoduletype1").css({"display":"none"});	
    		}
    		$("#rss").hide();
    		$("#template").show();
    		$("#templaterss").hide();
    	} else if (type=="banner"){
    		$("#banner").show();
    		$("#file").hide();
    		$("#html").hide();
			$("#labelmoduletype1").css({"display":""});
    		$("#rss").hide();
    		$("#template").show();
    		$("#templaterss").hide();
    	} else if (type=="rss"){
    		$("#banner").hide();
    		$("#file").hide();
    		$("#html").hide();
    		$("#labelmoduletype1").css({"display":""});
    		$("#rss").show();
    		$("#template").hide();
    		$("#templaterss").show();
    	} else {
    		$("#html").show();
    		$("#file").hide();
    		$("#banner").hide();
			$("#labelmoduletype1").css({"display":""});
    		$("#rss").hide();
    		$("#template").show();
    		$("#templaterss").hide();
    	}
	});
	
	$("input[name=all_func]").click(function(){
		var module = $("select[name=module]").val();
		var af = $(this).val();
    	if (af=="0" && module!="global"){
    		$("#shows_all_func").show();
    	} else if (module=="global" && af==0){
    		$("#shows_all_func").show();
    	} else if (af==1) {
    		$("#shows_all_func").hide();
    	}
	});

	$("input[name=leavegroup]").click(function(){
		var lv = $("input[name=\'leavegroup\']:checked").val();
		if(lv=="1"){
			var $radios = $("input:radio[name=all_func]");
        	$radios.filter("[value=0]").attr("checked", true);
        	$("#shows_all_func").show();
		}
	});	
		
	$("input[name=checkmod]").toggle(function(){
		$("input[name=func_id[]]:checkbox").each(function(){
			$("input[name=func_id[]]:visible").attr("checked","checked");			
		});
	},function(){
		$("input[name=func_id[]]:checkbox").each(function(){
			$("input[name=func_id[]]:visible").removeAttr("checked");
		});
		}
	);
	$("select[name=who_view]").change(function(){
		var groups = $("select[name=who_view]").val();
		if (groups==3){
			$("#groups_list").show();
		} else {
			$("#groups_list").hide();
		}
	});
	
	$("input[name=confirm]").click(function(){
		var leavegroup = $("input[name=leavegroup]").is(":checked")?1:0;
		var all_func = $("input[name=\'all_func\']:checked").val();
		if(all_func==0){
    		var funcid = [];
    		$("input[name=func_id[]]:checked").each(function(){
    			funcid.push($(this).val());
    		});
    		if (funcid.length<1){
    			alert("' . $lang_module['block_no_func'] . '");
    			return false;
    		}
		}
		var title = $("input[name=title]").val();
		if (title==""){
			alert("' . $lang_module['error_empty_title'] . '");
			$("input[name=title]").focus();
			return false;
		}
		var typeblock = $("input[name=typeblock]:checked").val();
		var templaterss = $("select[name=templaterss]").val();
			if (typeblock=="rss" && template==""){
			alert("' . $lang_module['block_rss_template_error'] . '");
			$("select[name=template]").focus();
			return false;
		}
		var who_view = $("select[name=who_view]").val();
		if (who_view==3){
	        var grouplist = [];
	        $("input[name=groups_view[]]:checked").each(function(){
	        	grouplist.push($(this).val());
	        });
	        if (grouplist.length<1){
		        alert("' . $lang_module['block_error_nogroup'] . '");
		        return false;
	        }
        }
	});
});
</script>
';
echo $contents;
?>