<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 2:29
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

/**
 * nv_CreateXML_bannerPlan()
 * 
 * @param mixed $id
 * @return
 */
function nv_CreateXML_bannerPlan ( )
{
    global $db, $global_config;
    
    $files = nv_scandir( NV_ROOTDIR . '/' . NV_DATADIR, "/^bpl\_([0-9]+)\.xml$/" );
    if ( ! empty( $files ) )
    {
        foreach ( $files as $file )
        {
            nv_deletefile( NV_ROOTDIR . '/' . NV_DATADIR . '/' . $file );
        }
    }
    
    include ( NV_ROOTDIR . '/includes/class/array2xml.class.php' );
    
    $query = "SELECT * FROM `" . NV_BANNERS_PLANS_GLOBALTABLE . "` 
        WHERE 
        `act` = 1";
    $result = $db->sql_query( $query );
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $id = intval( $row['id'] );
        
        $xmlfile = NV_ROOTDIR . '/' . NV_DATADIR . '/';
        $xmlfile .= 'bpl_' . $id . '.xml';
        
        $plan = array();
        $plan['id'] = $id;
        $plan['lang'] = $row['blang'];
        $plan['title'] = $row['title'];
        if ( ! empty( $row['description'] ) )
        {
            $plan['description'] = $row['description'];
        }
        $plan['form'] = $row['form'];
        $plan['width'] = $row['width'];
        $plan['height'] = $row['height'];
        
        $query2 = "SELECT * FROM `" . NV_BANNERS_ROWS_GLOBALTABLE . "` 
        WHERE 
        `pid` = " . $id . " 
        AND 
        (`exp_time` > " . NV_CURRENTTIME . " 
        OR
        `exp_time` = 0) 
        AND 
        `act` = 1";
        $result2 = $db->sql_query( $query2 );
        $numrows2 = $db->sql_numrows( $result2 );
        if ( empty( $numrows2 ) )
        {
            continue;
        }
        $plan['banners'] = array();
        while ( $row2 = $db->sql_fetchrow( $result2 ) )
        {
            $plan['banners'][] = array( 
                'id' => $row2['id'], //
                'title' => $row2['title'], //
                'clid' => $row2['clid'], //
                'file_name' => $row2['file_name'], //
                'file_ext' => $row2['file_ext'], //
                'file_mime' => $row2['file_mime'], //
                'file_width' => $row2['width'], //
                'file_height' => $row2['height'], //
                'file_alt' => $row2['file_alt'], //
                'file_click' => $row2['click_url']  //
            );
        }
        
        $array2XML = new Array2XML();
        $array2XML->saveXML( $plan, 'plan', $xmlfile, $encoding = $global_config['site_charset'] );
    }
}

$submenu['client_list'] = $lang_module['client_list'];
$submenu['add_client'] = $lang_module['add_client'];
$submenu['plans_list'] = $lang_module['plans_list'];
$submenu['add_plan'] = $lang_module['add_plan'];
$submenu['banners_list'] = $lang_module['banners_list'];
$submenu['add_banner'] = $lang_module['add_banner'];

$allow_func = array( 
    'main', 'client_list', 'cl_list', 'add_client', 'edit_client', 'del_client', 'change_act_client', 'info_client', 'info_cl', 'plans_list', 'plist', 'change_act_plan', 'add_plan', 'edit_plan', 'del_plan', 'info_plan', 'info_pl', 'banners_list', 'add_banner', 'edit_banner', 'b_list', 'change_act_banner', 'info_banner', 'show_stat', 'show_list_stat','del_banner' 
);

define( 'NV_IS_FILE_ADMIN', true );

function nv_add_client_theme ( $contents )
{
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:810px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    
    $return .= "<form method=\"post\" style=\"FLOAT:left;width:100%;margin-bottom:20px\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:100%\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:150px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['login'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['login'][1] . "\" id=\"" . $contents['login'][1] . "\" type=\"text\" value=\"" . $contents['login'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['login'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['pass'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['pass'][1] . "\" id=\"" . $contents['pass'][1] . "\" type=\"password\" autocomplete=\"off\" value=\"" . $contents['pass'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['pass'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['re_pass'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['re_pass'][1] . "\" id=\"" . $contents['re_pass'][1] . "\" type=\"password\" autocomplete=\"off\" value=\"" . $contents['re_pass'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['re_pass'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['email'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['email'][1] . "\" id=\"" . $contents['email'][1] . "\" type=\"text\" value=\"" . $contents['email'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['email'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['full_name'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['full_name'][1] . "\" id=\"" . $contents['full_name'][1] . "\" type=\"text\" value=\"" . $contents['full_name'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['full_name'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['website'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['website'][1] . "\" id=\"" . $contents['website'][1] . "\" type=\"text\" value=\"" . $contents['website'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['website'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['location'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['location'][1] . "\" id=\"" . $contents['location'][1] . "\" type=\"text\" value=\"" . $contents['location'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['location'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['yim'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['yim'][1] . "\" id=\"" . $contents['yim'][1] . "\" type=\"text\" value=\"" . $contents['yim'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['yim'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['phone'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['phone'][1] . "\" id=\"" . $contents['phone'][1] . "\" type=\"text\" value=\"" . $contents['phone'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['phone'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['fax'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['fax'][1] . "\" id=\"" . $contents['fax'][1] . "\" type=\"text\" value=\"" . $contents['fax'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['fax'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['mobile'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['mobile'][1] . "\" id=\"" . $contents['mobile'][1] . "\" type=\"text\" value=\"" . $contents['mobile'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['mobile'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['uploadtype'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><label><input name=\"" . $contents['uploadtype'][1] . "[]\" id=\"" . $contents['uploadtype'][1] . "\" type=\"checkbox\" value=\"images\"/>images</label>&nbsp;<label><input name=\"" . $contents['uploadtype'][1] . "[]\" id=\"" . $contents['uploadtype'][1] . "\" type=\"checkbox\" value=\"flash\"/>flash</label></td>\n";
    $return .= "</tr>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td></td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input type=\"submit\" value=\"" . $contents['submit'] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "</table>\n";
    $return .= "</div>\n";
    $return .= "</form>\n";
    return $return;
}

function nv_edit_client_theme ( $contents )
{
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:800px;\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:150px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['login'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['login'][1] . "\" id=\"" . $contents['login'][1] . "\" type=\"text\" value=\"" . $contents['login'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['login'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['email'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['email'][1] . "\" id=\"" . $contents['email'][1] . "\" type=\"text\" value=\"" . $contents['email'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['email'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['full_name'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['full_name'][1] . "\" id=\"" . $contents['full_name'][1] . "\" type=\"text\" value=\"" . $contents['full_name'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['full_name'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['website'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['website'][1] . "\" id=\"" . $contents['website'][1] . "\" type=\"text\" value=\"" . $contents['website'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['website'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['location'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['location'][1] . "\" id=\"" . $contents['location'][1] . "\" type=\"text\" value=\"" . $contents['location'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['location'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['yim'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['yim'][1] . "\" id=\"" . $contents['yim'][1] . "\" type=\"text\" value=\"" . $contents['yim'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['yim'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['phone'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['phone'][1] . "\" id=\"" . $contents['phone'][1] . "\" type=\"text\" value=\"" . $contents['phone'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['phone'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['fax'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['fax'][1] . "\" id=\"" . $contents['fax'][1] . "\" type=\"text\" value=\"" . $contents['fax'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['fax'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['mobile'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['mobile'][1] . "\" id=\"" . $contents['mobile'][1] . "\" type=\"text\" value=\"" . $contents['mobile'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['mobile'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['uploadtype'][0] . ":</td>\n";
    $return .= "<td></td>\n";
	$return .= "<td><label><input name=\"" . $contents['uploadtype'][1] . "[]\" id=\"" . $contents['uploadtype'][1] . "\" type=\"checkbox\" value=\"images\" ".$contents['uploadtype'][2]."/>images</label>&nbsp;<label><input name=\"" . $contents['uploadtype'][1] . "[]\" id=\"" . $contents['uploadtype'][1] . "\" type=\"checkbox\" value=\"flash\" ".$contents['uploadtype'][3]."/>flash</label></td>\n";
    $return .= "</tr>\n";
    
    $return .= "</table>\n";
    
    $return .= "<br />\n";
    
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:150px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['pass'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['pass'][1] . "\" id=\"" . $contents['pass'][1] . "\" type=\"password\" autocomplete=\"off\" value=\"" . $contents['pass'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['pass'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['re_pass'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['re_pass'][1] . "\" id=\"" . $contents['re_pass'][1] . "\" type=\"password\" autocomplete=\"off\" value=\"" . $contents['re_pass'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['re_pass'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "</table>\n";
    
    $return .= "<br />\n";
    
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:150px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td></td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input type=\"submit\" value=\"" . $contents['submit'] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "</table>\n";
    
    $return .= "</div>\n";
    $return .= "</form>\n";
    return $return;
}

function nv_client_list_theme ( $contents )
{
    $return = "<div id=\"" . $contents['containerid'] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['aj'] . "\n";
    $return .= "</script>\n";
    return $return;
}

function nv_cl_list_theme ( $contents )
{
    $return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col span=\"4\" style=\"white-space:nowrap\" />\n";
    $return .= "<col style=\"width:50px;white-space:nowrap\" />\n";
    $return .= "<col style=\"width:250px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $key => $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    $a = 0;
    if ( ! empty( $contents['rows'] ) )
    {
        foreach ( $contents['rows'] as $cl_id => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>" . $values['login'] . "</td>\n";
            $return .= "<td>" . $values['full_name'] . "</td>\n";
            $return .= "<td>" . $values['email'] . "</td>\n";
            $return .= "<td>" . $values['reg_time'] . "</td>\n";
            $return .= "<td><input name=\"" . $values['act'][0] . "\" id=\"" . $values['act'][0] . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][2] . "\"" . ( $values['act'][1] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['view'] . "\">" . $contents['view'] . "</a></span> | \n";
            $return .= "<span class=\"edit_icon\"><a href=\"" . $values['edit'] . "\">" . $contents['edit'] . "</a></span> | \n";
            $return .= "<span class=\"add_icon\"><a href=\"" . $values['add'] . "\">" . $contents['add'] . "</a></span> | \n";
            $return .= "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['del'] . "\">" . $contents['del'] . "</a></span></td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
    }
    
    $return .= "</table>\n";
    return $return;
}

function nv_info_client_theme ( $contents )
{
    $return = "<div id=\"" . $contents['containerid'][0] . "\"></div>\n";
    $return .= "<div id=\"" . $contents['containerid'][1] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['aj'][0] . "\n";
    $return .= $contents['aj'][1] . "\n";
    $return .= "</script>\n";
    return $return;
}

function nv_info_cl_theme ( $contents )
{
    $return = "<div style=\"HEIGHT:27px;MARGIN-TOP:3px;POSITION:absolute;RIGHT:10px;TEXT-ALIGN:right;\">\n";
    $return .= "<a class=\"button2\" href=\"" . $contents['edit'][0] . "\"><span><span>" . $contents['edit'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['act'][0] . "\"><span><span>" . $contents['act'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"" . $contents['add'][0] . "\"><span><span>" . $contents['add'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['del'][0] . "\"><span><span>" . $contents['del'][1] . "</span></span></a>\n";
    $return .= " </div>\n";
    
    $return .= " <table summary = \"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:50%;white-space:nowrap\" />\n";
    
    $a = 0;
    foreach ( $contents['rows'] as $row )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $row[0] . ":</td>\n";
        $return .= "<td>" . $row[1] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    
    $return .= "</table>\n";
    return $return;
}

function nv_banners_client_theme ( $contents )
{
    if ( ! empty( $contents['info'] ) )
    {
        $return = "<div class=\"quote\" style=\"width:800px;\">\n";
        $return .= "<blockquote><span>" . $contents['info'] . "</span></blockquote>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        return $return;
        exit();
    }
    $return = " <table summary = \"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    
    $return .= "</table>\n";
    return $return;
}

function nv_add_plan_theme ( $contents )
{
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:800px;\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:200px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['title'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['title'][1] . "\" id=\"" . $contents['title'][1] . "\" type=\"text\" value=\"" . $contents['title'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['size'] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td>" . $contents['width'][0] . ": <input name=\"" . $contents['width'][1] . "\" id=\"" . $contents['width'][1] . "\" type=\"text\" value=\"" . $contents['width'][2] . "\" style=\"width:50px\" maxlength=\"" . $contents['width'][3] . "\" />\n";
    $return .= " " . $contents['height'][0] . ": <input name=\"" . $contents['height'][1] . "\" id=\"" . $contents['height'][1] . "\" type=\"text\" value=\"" . $contents['height'][2] . "\" style=\"width:50px\" maxlength=\"" . $contents['height'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['blang'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['blang'][1] . "\" id=\"" . $contents['blang'][1] . "\">un";
    $return .= "<option value=\"\">" . $contents['blang'][2] . "</option>\n";
    foreach ( $contents['blang'][3] as $key => $blang )
    {
        $return .= "<option value=\"" . $key . "\"" . ( $key == $contents['blang'][4] ? " selected=\"selected\"" : "" ) . ">" . $blang['name'] . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['form'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['form'][1] . "\" id=\"" . $contents['form'][1] . "\">un";
    foreach ( $contents['form'][2] as $form )
    {
        $return .= "<option value=\"" . $form . "\"" . ( $form == $contents['form'][3] ? " selected=\"selected\"" : "" ) . ">" . $form . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td colspan=\"3\">" . $contents['description'][0] . ":</td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "</table>\n";
    
    $return .= "<div>\n";
    if ( $contents['description'][5] and function_exists( 'nv_aleditor' ) )
    {
        $return .= nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
    }
    else
    {
        $return .= "<textarea name=\"" . $contents['description'][1] . "\" id=\"" . $contents['description'][1] . "\" style=\"width:" . $contents['description'][3] . ";height:" . $contents['description'][4] . "\">" . $contents['description'][2] . "</textarea>\n";
    }
    $return .= "</div>\n";
    
    $return .= "<div style=\"PADDING-TOP:10px;\">\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" />\n";
    $return .= "</div>\n";
    
    $return .= "</div>\n";
    $return .= "</form>\n";
    return $return;
}

function nv_edit_plan_theme ( $contents )
{
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:800px;\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:200px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['title'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['title'][1] . "\" id=\"" . $contents['title'][1] . "\" type=\"text\" value=\"" . $contents['title'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['size'] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td>" . $contents['width'][0] . ": <input name=\"" . $contents['width'][1] . "\" id=\"" . $contents['width'][1] . "\" type=\"text\" value=\"" . $contents['width'][2] . "\" style=\"width:50px\" maxlength=\"" . $contents['width'][3] . "\" />\n";
    $return .= " " . $contents['height'][0] . ": <input name=\"" . $contents['height'][1] . "\" id=\"" . $contents['height'][1] . "\" type=\"text\" value=\"" . $contents['height'][2] . "\" style=\"width:50px\" maxlength=\"" . $contents['height'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['blang'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['blang'][1] . "\" id=\"" . $contents['blang'][1] . "\">un";
    $return .= "<option value=\"\">" . $contents['blang'][2] . "</option>\n";
    foreach ( $contents['blang'][3] as $key => $blang )
    {
        $return .= "<option value=\"" . $key . "\"" . ( $key == $contents['blang'][4] ? " selected=\"selected\"" : "" ) . ">" . $blang['name'] . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['form'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['form'][1] . "\" id=\"" . $contents['form'][1] . "\">un";
    foreach ( $contents['form'][2] as $form )
    {
        $return .= "<option value=\"" . $form . "\"" . ( $form == $contents['form'][3] ? " selected=\"selected\"" : "" ) . ">" . $form . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td colspan=\"3\">" . $contents['description'][0] . ":</td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "</table>\n";
    
    $return .= "<div>\n";
    if ( $contents['description'][5] and function_exists( 'nv_aleditor' ) )
    {
        $return .= nv_aleditor( $contents['description'][1], $contents['description'][3], $contents['description'][4], $contents['description'][2] );
    }
    else
    {
        $return .= "<textarea name=\"" . $contents['description'][1] . "\" id=\"" . $contents['description'][1] . "\" style=\"width:" . $contents['description'][3] . ";height:" . $contents['description'][4] . "\">" . $contents['description'][2] . "</textarea>\n";
    }
    $return .= "</div>\n";
    
    $return .= "<div style=\"PADDING-TOP:10px;\">\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" />\n";
    $return .= "</div>\n";
    
    $return .= "</div>\n";
    $return .= "</form>\n";
    return $return;
}

function nv_plans_list_theme ( $contents )
{
    $return = "<div id=\"" . $contents['containerid'] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['aj'] . "\n";
    $return .= "</script>\n";
    return $return;
}

function nv_plist_theme ( $contents )
{
    $return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col span=\"3\" style=\"white-space:nowrap\" />\n";
    $return .= "<col style=\"width:50px;white-space:nowrap\" />\n";
    $return .= "<col style=\"width:300px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $key => $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    $a = 0;
    if ( ! empty( $contents['rows'] ) )
    {
        foreach ( $contents['rows'] as $pl_id => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>" . $values['title'] . "</td>\n";
            $return .= "<td>" . $values['blang'] . "</td>\n";
            $return .= "<td>" . $values['size'] . "</td>\n";
            $return .= "<td><input name=\"" . $values['act'][0] . "\" id=\"" . $values['act'][0] . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][2] . "\"" . ( $values['act'][1] ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['view'] . "\">" . $contents['view'] . "</a></span> | \n";
            $return .= "<span class=\"edit_icon\"><a href=\"" . $values['edit'] . "\">" . $contents['edit'] . "</a></span> | \n";
            $return .= "<span class=\"add_icon\"><a href=\"" . $values['add'] . "\">" . $contents['add'] . "</a></span> | \n";
            $return .= "<span class=\"delete_icon\"><a href=\"javascript:void(0);\" onclick=\"" . $values['del'] . "\">" . $contents['del'] . "</a></span></td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
    }
    
    $return .= "</table>\n";
    return $return;
}

function nv_info_plan_theme ( $contents )
{
    $return = "<div id=\"" . $contents['containerid'][0] . "\"></div>\n";
    $return .= "<div id=\"" . $contents['containerid'][1] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['aj'][0] . "\n";
    $return .= $contents['aj'][1] . "\n";
    $return .= "</script>\n";
    return $return;
}

function nv_info_pl_theme ( $contents )
{
    $return = "<div style=\"HEIGHT:27px;MARGIN-TOP:3px;POSITION:absolute;RIGHT:10px;TEXT-ALIGN:right;\">\n";
    $return .= "<a class=\"button2\" href=\"" . $contents['edit'][0] . "\"><span><span>" . $contents['edit'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['act'][0] . "\"><span><span>" . $contents['act'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"" . $contents['add'][0] . "\"><span><span>" . $contents['add'][1] . "</span></span></a>\n";
    $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['del'][0] . "\"><span><span>" . $contents['del'][1] . "</span></span></a>\n";
    $return .= " </div>\n";
    
    $return .= " <table summary = \"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:50%;white-space:nowrap\" />\n";
    
    $a = 0;
    foreach ( $contents['rows'] as $key => $row )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        if ( $key != 'description' )
        {
            $return .= "<td>" . $row[0] . ":</td>\n";
            $return .= "<td>" . $row[1] . "</td>\n";
        }
        else
        {
            $return .= "<td colspan=\"2\">" . $row[0] . ":</td>\n";
        }
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    $return .= "</table>\n";
    
    if ( isset( $contents['rows']['description'] ) )
    {
        $return .= "<div style=\"BORDER:1px solid #DADADA;MARGIN:10px 0px 0px 0px;PADDING:10px 10px 10px 10px;\">\n";
        $return .= "<div>\n";
        $return .= $contents['rows']['description'][1];
        $return .= "</div>\n";
        $return .= "</div>\n";
    }
    return $return;
}

function nv_add_banner_theme ( $contents )
{
    if ( ! empty( $contents['upload_blocked'] ) )
    {
        $return = "<div class=\"quote\" style=\"width:800px;\">\n";
        $return .= "<blockquote><span>" . $contents['upload_blocked'] . "</span></blockquote>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        return $return;
        exit();
    }
    
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:800px;\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:250px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['title'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['title'][1] . "\" id=\"" . $contents['title'][1] . "\" type=\"text\" value=\"" . $contents['title'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['plan'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><select name=\"" . $contents['plan'][1] . "\" id=\"" . $contents['plan'][1] . "\">\n";
    foreach ( $contents['plan'][2] as $pid => $ptitle )
    {
        $return .= "<option value=\"" . $pid . "\"" . ( $pid == $contents['plan'][3] ? " selected=\"selected\"" : "" ) . ">" . $ptitle . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['client'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['client'][1] . "\" id=\"" . $contents['client'][1] . "\">\n";
    $return .= "<option value=\"\">&nbsp;</option>\n";
    foreach ( $contents['client'][2] as $clid => $clname )
    {
        $return .= "<option value=\"" . $clid . "\"" . ( $clid == $contents['client'][3] ? " selected=\"selected\"" : "" ) . ">" . $clname . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['upload'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['upload'][1] . "\" type=\"file\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['file_alt'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['file_alt'][1] . "\" id=\"" . $contents['file_alt'][1] . "\" type=\"text\" value=\"" . $contents['file_alt'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['file_alt'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['click_url'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['click_url'][1] . "\" id=\"" . $contents['click_url'][1] . "\" type=\"text\" value=\"" . $contents['click_url'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['click_url'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['publ_date'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['publ_date'][1] . "\" id=\"" . $contents['publ_date'][1] . "\" type=\"text\" value=\"" . $contents['publ_date'][2] . "\" style=\"width:278px\" maxlength=\"" . $contents['publ_date'][3] . "\" readonly=\"readonly\" />\n";
    $return .= "<img src=\"" . $contents['publ_date'][4] . "\" widht=\"" . $contents['publ_date'][5] . "\" height=\"" . $contents['publ_date'][6] . "\" style=\"cursor:pointer;vertical-align: middle;\" onclick=\"" . $contents['publ_date'][7] . "\" alt=\"\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['exp_date'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['exp_date'][1] . "\" id=\"" . $contents['exp_date'][1] . "\" type=\"text\" value=\"" . $contents['exp_date'][2] . "\" style=\"width:278px\" maxlength=\"" . $contents['exp_date'][3] . "\" readonly=\"readonly\" />\n";
    $return .= "<img src=\"" . $contents['exp_date'][4] . "\" widht=\"" . $contents['exp_date'][5] . "\" height=\"" . $contents['exp_date'][6] . "\" style=\"cursor:pointer;vertical-align: middle;\" onclick=\"" . $contents['exp_date'][7] . "\" alt=\"\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "</table>\n";
    
    $return .= "<div style=\"PADDING-TOP:10px;\">\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" />\n";
    $return .= "</div>\n";
    
    $return .= "</div>\n";
    $return .= "</form>\n";
    
    return $return;
}

function nv_edit_banner_theme ( $contents )
{
    if ( ! empty( $contents['upload_blocked'] ) )
    {
        $return = "<div class=\"quote\" style=\"width:800px;\">\n";
        $return .= "<blockquote><span>" . $contents['upload_blocked'] . "</span></blockquote>\n";
        $return .= "</div>\n";
        $return .= "<div class=\"clear\"></div>\n";
        return $return;
        exit();
    }
    
    $return = "";
    $class = $contents['is_error'] ? " class=\"error\"" : "";
    $return .= "<div class=\"quote\" style=\"width:780px;\">\n";
    $return .= "<blockquote" . $class . "><span>" . $contents['info'] . "</span></blockquote>\n";
    $return .= "</div>\n";
    $return .= "<div class=\"clear\"></div>\n";
    
    $return .= "<form method=\"post\" enctype=\"multipart/form-data\" action=\"" . $contents['action'] . "\">\n";
    $return .= "<div style=\"WIDTH:800px;\">\n";
    $return .= "<input type=\"hidden\" value=\"1\" name=\"save\" id=\"save\" />\n";
    $return .= "<table summary=\"" . $contents['info'] . "\" class=\"tab1\">\n";
    $return .= "<col style=\"width:250px;white-space:nowrap\" />\n";
    $return .= "<col valign=\"top\" width=\"10px\" />\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['title'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><input name=\"" . $contents['title'][1] . "\" id=\"" . $contents['title'][1] . "\" type=\"text\" value=\"" . $contents['title'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['title'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['plan'][0] . ":</td>\n";
    $return .= "<td><sup class=\"required\">&lowast;</sup></td>\n";
    $return .= "<td><select name=\"" . $contents['plan'][1] . "\" id=\"" . $contents['plan'][1] . "\">\n";
    foreach ( $contents['plan'][2] as $pid => $ptitle )
    {
        $return .= "<option value=\"" . $pid . "\"" . ( $pid == $contents['plan'][3] ? " selected=\"selected\"" : "" ) . ">" . $ptitle . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['client'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><select name=\"" . $contents['client'][1] . "\" id=\"" . $contents['client'][1] . "\">\n";
    $return .= "<option value=\"\">&nbsp;</option>\n";
    foreach ( $contents['client'][2] as $clid => $clname )
    {
        $return .= "<option value=\"" . $clid . "\"" . ( $clid == $contents['client'][3] ? " selected=\"selected\"" : "" ) . ">" . $clname . "</option>\n";
    }
    $return .= "</select></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['file_name'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    //$return .= "<td><a href=\"" . $contents['file_name'][1] . "\" onclick=\"" . $contents['file_name'][2] . "\"><img class=\"highslide\" alt=\"" . $contents['file_name'][4] . "\" src=\"" . $contents['file_name'][3] . "\" width=\"16\" height=\"16\" style=\"cursor: pointer\" /></a></td>\n";
    $return .= "<td><a href=\"" . $contents['file_name'][1] . "\" " . $contents['file_name'][2] . "><img alt=\"" . $contents['file_name'][4] . "\" src=\"" . $contents['file_name'][3] . "\" width=\"16\" height=\"16\" style=\"cursor: pointer\" /></a></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['upload'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['upload'][1] . "\" type=\"file\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['file_alt'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['file_alt'][1] . "\" id=\"" . $contents['file_alt'][1] . "\" type=\"text\" value=\"" . $contents['file_alt'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['file_alt'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['click_url'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['click_url'][1] . "\" id=\"" . $contents['click_url'][1] . "\" type=\"text\" value=\"" . $contents['click_url'][2] . "\" style=\"width:300px\" maxlength=\"" . $contents['click_url'][3] . "\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody>\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['publ_date'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['publ_date'][1] . "\" id=\"" . $contents['publ_date'][1] . "\" type=\"text\" value=\"" . $contents['publ_date'][2] . "\" style=\"width:278px\" maxlength=\"" . $contents['publ_date'][3] . "\" readonly=\"readonly\" />\n";
    $return .= "<img src=\"" . $contents['publ_date'][4] . "\" widht=\"" . $contents['publ_date'][5] . "\" height=\"" . $contents['publ_date'][6] . "\" style=\"cursor:pointer;vertical-align: middle;\" onclick=\"" . $contents['publ_date'][7] . "\" alt=\"\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['exp_date'][0] . ":</td>\n";
    $return .= "<td></td>\n";
    $return .= "<td><input name=\"" . $contents['exp_date'][1] . "\" id=\"" . $contents['exp_date'][1] . "\" type=\"text\" value=\"" . $contents['exp_date'][2] . "\" style=\"width:278px\" maxlength=\"" . $contents['exp_date'][3] . "\" readonly=\"readonly\" />\n";
    $return .= "<img src=\"" . $contents['exp_date'][4] . "\" widht=\"" . $contents['exp_date'][5] . "\" height=\"" . $contents['exp_date'][6] . "\" style=\"cursor:pointer;vertical-align: middle;\" onclick=\"" . $contents['exp_date'][7] . "\" alt=\"\" /></td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    
    $return .= "</table>\n";
    
    $return .= "<div style=\"PADDING-TOP:10px;\">\n";
    $return .= "<input type=\"submit\" value=\"" . $contents['submit'] . "\" />\n";
    $return .= "</div>\n";
    
    $return .= "</div>\n";
    $return .= "</form>\n";
    
    return $return;
}

function nv_banners_list_theme ( $contents )
{
    $return = "<div id=\"" . $contents['containerid'] . "\"></div>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= $contents['aj'] . "\n";
    $return .= "</script>\n";
    return $return;
}

function nv_b_list_theme ( $contents )
{
    global $lang_module,$module_name;
	$return = "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col span=\"5\" style=\"white-space:nowrap\" />\n";
    $return .= "<col style=\"width:50px;white-space:nowrap\" />\n";
    $return .= "<col style=\"width:200px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $key => $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    $a = 0;
    if ( ! empty( $contents['rows'] ) )
    {
        foreach ( $contents['rows'] as $b_id => $values )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>" . $values['title'] . "</td>\n";
            $return .= "<td><a href=\"" . $values['pid'][0] . "\">" . $values['pid'][1] . "</a></td>\n";
            if ( ! empty( $values['clid'] ) )
            {
                $return .= "<td><a href=\"" . $values['clid'][0] . "\">" . $values['clid'][1] . "</a></td>\n";
            }
            else
            {
                $return .= "<td></td>\n";
            }
            $return .= "<td>" . $values['publ_date'] . "</td>\n";
            $return .= "<td>" . $values['exp_date'] . "</td>\n";
            $return .= "<td><input name=\"" . $values['act'][0] . "\" id=\"" . $values['act'][0] . "\" type=\"checkbox\" value=\"1\" onclick=\"" . $values['act'][2] . "\"" . ( $values['act'][1] == '1' ? " checked=\"checked\"" : "" ) . " /></td>\n";
            $return .= "<td><span class=\"search_icon\"><a href=\"" . $values['view'] . "\">" . $contents['view'] . "</a></span> | \n";
            $return .= "<span class=\"edit_icon\"><a href=\"" . $values['edit'] . "\">" . $contents['edit'] . "</a></span> | \n";
            $return .= "<span class=\"delete_icon\"><a class='delfile' href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=del_banner&amp;id=" . $b_id . "\">" . $contents['del'] . "</a></span></td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
    }
    
    $return .= "</table>\n";
    $return .= "
				<script type='text/javascript'>
				$(function(){
					$('a[class=delfile]').click(function(event){
						event.preventDefault();
						if (confirm('".$lang_module['file_del_confirm']."'))
						{
							var href= $(this).attr('href');
							$.ajax({	
								type: 'POST',
								url: href,
								data:'',
								success: function(data){				
									alert(data);
									window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&".NV_OP_VARIABLE."=banners_list';
								}
							});
						}
					});
				});
				</script>
				";
    return $return;
}

function nv_info_b_theme ( $contents )
{
    global $lang_module,$module_name;
	$return = "<div style=\"HEIGHT:27px;MARGIN-TOP:3px;POSITION:absolute;RIGHT:10px;TEXT-ALIGN:right;\">\n";
    $return .= "<a class=\"button2\" href=\"" . $contents['edit'][0] . "\"><span><span>" . $contents['edit'][1] . "</span></span></a>\n";
    if ( isset( $contents['act'] ) ) $return .= "<a class=\"button2\" href=\"javascript:void(0);\" onclick=\"" . $contents['act'][0] . "\"><span><span>" . $contents['act'][1] . "</span></span></a>\n";
    $return .= " </div>\n";
    
    $return .= " <table summary = \"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:50%;white-space:nowrap\" />\n";
    
    $a = 0;
    foreach ( $contents['rows'] as $row )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        $return .= "<td>" . $row[0] . ":</td>\n";
        $return .= "<td>" . $row[1] . "</td>\n";
        $return .= "</tr>\n";
        $return .= "</tbody>\n";
        $a ++;
    }
    
    $return .= "</table>\n";
    $return .= " <table summary = \"" . $contents['stat'][0] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['stat'][0] . "</caption>\n";
    $return .= "<tbody class=\"second\">\n";
    $return .= "<tr>\n";
    $return .= "<td>" . $contents['stat'][1] . ": \n";
    $return .= "<select name=\"" . $contents['stat'][2] . "\" id=\"" . $contents['stat'][2] . "\">\n";
    foreach ( $contents['stat'][3] as $k => $v )
    {
        $return .= "<option value=\"" . $k . "\">" . $v . "</option>\n";
    }
    $return .= "</select>\n";
    $return .= "<select name=\"" . $contents['stat'][4] . "\" id=\"" . $contents['stat'][4] . "\">\n";
    foreach ( $contents['stat'][5] as $k => $v )
    {
        $return .= "<option value=\"" . $k . "\">" . $v . "</option>\n";
    }
    $return .= "</select>\n";
    $return .= "<input type=\"button\" value=\"" . $contents['stat'][6] . "\" id=\"" . $contents['stat'][7] . "\" onclick=\"" . $contents['stat'][8] . "\" />\n";
    $return .= "</td>\n";
    $return .= "</tr>\n";
    $return .= "</tbody>\n";
    $return .= "</table>\n";
    
    $return .= "<div id=\"" . $contents['containerid'] . "\"></div>\n";
    $return .= "
			<script type='text/javascript'>
			$(function(){
				$('a[class=delfile]').click(function(event){
					event.preventDefault();
					if (confirm('".$lang_module['file_del_confirm']."'))
					{
						var href= $(this).attr('href');
						$.ajax({	
							type: 'POST',
							url: href,
							data:'',
							success: function(data){				
								alert(data);
								window.location='index.php?" . NV_NAME_VARIABLE . "=" . $module_name."&amp;".NV_OP_VARIABLE."=banner_list';
							}
						});
					}
				});
			});
			</script>
			";
    return $return;
}

function nv_show_stat_theme ( $contents )
{
    $return = "";
    $return .= "</table>\n";
    $return .= "<table summary = \"" . $contents[0] . "\" class=\"tab1\">\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    $return .= "<td colspan=\"2\">" . $contents[0] . "</td>\n";
    $return .= "<td style=\"width:100px;text-align:right;\">" . $contents[1] . "</td>\n";
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    if ( ! empty( $contents[2] ) )
    {
        $a = 0;
        foreach ( $contents[2] as $key => $value )
        {
            $class = ( $a % 2 ) ? " class=\"second\"" : "";
            $return .= "<tbody" . $class . ">\n";
            $return .= "<tr>\n";
            $return .= "<td>";
            if ( ! preg_match( "/^[0-9]+$/", $key ) ) $return .= "<a href=\"javascript:void(0);\" onclick=\"" . $key . "\">" . $value[0] . "</a>";
            else $return .= $value[0];
            $return .= "</td>\n";
            $return .= "<td style=\"width:350px;\">\n";
            if ( ! empty( $value[1] ) )
            {
                $return .= "<div class=\"stat2\">\n";
                $return .= "<div class=\"left\"></div>\n";
                $return .= "<div class=\"center\" style=\"width:" . ( $value[1] * 3 ) . "px;\"></div>\n";
                $return .= "<div class=\"right\"></div>\n";
                $return .= "<div class=\"text\">" . $value[1] . "%</div>\n";
                $return .= "</div>\n";
            }
            $return .= "</td>\n";
            $return .= "<td style=\"width:100px;text-align:right;\">" . $value[2] . "</td>\n";
            $return .= "</tr>\n";
            $return .= "</tbody>\n";
            $a ++;
        }
    }
    $return .= "</table>\n";
    return $return;
}

function nv_show_list_stat_theme ( $contents )
{
    $return = "";
    $return .= "<table summary=\"" . $contents['caption'] . "\" class=\"tab1\">\n";
    $return .= "<caption>" . $contents['caption'] . "</caption>\n";
    $return .= "<col style=\"width:120px;white-space:nowrap\" />\n";
    $return .= "<col style=\"width:100px;white-space:nowrap\" />\n";
    $return .= "<col span=\"3\" style=\"white-space:nowrap\" />\n";
    $return .= "<col style=\"width:90px;white-space:nowrap\" />\n";
    $return .= "<thead>\n";
    $return .= "<tr>\n";
    foreach ( $contents['thead'] as $key => $thead )
    {
        $return .= "<td>" . $thead . "</td>\n";
    }
    $return .= "</tr>\n";
    $return .= "</thead>\n";
    
    $a = 0;
    foreach ( $contents['rows'] as $row )
    {
        $class = ( $a % 2 ) ? " class=\"second\"" : "";
        $return .= "<tbody" . $class . ">\n";
        $return .= "<tr>\n";
        foreach ( $row as $r )
        {
            $return .= "<td>" . $r . "</td>\n";
        }
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

function nv_main_theme ( $contents )
{
    $return = "";
    foreach ( $contents['containerid'] as $containerid )
    {
        $return .= "<div id=\"" . $containerid . "\"></div>\n";
    }
    $return .= "<script type=\"text/javascript\">\n";
    foreach ( $contents['aj'] as $aj )
    {
        $return .= $aj . "\n";
    }
    $return .= "</script>\n";
    return $return;
}

?>