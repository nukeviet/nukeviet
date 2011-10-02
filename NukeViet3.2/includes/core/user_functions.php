<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 1-27-2010 5:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * nv_site_mods()
 * 
 * @return
 */
function nv_site_mods ( )
{
    global $admin_info, $user_info, $global_config;
    
    if ( defined( "NV_IS_USER" ) )
    {
        $user_ops = array( 'main', 'changepass', 'openid', 'editinfo','regroups' );
        if ( ! defined( "NV_IS_ADMIN" ) )
        {
            $user_ops[] = 'logout';
        }
    }
    else
    {
        $user_ops = array( 'main', 'login', 'register', 'lostpass' );
        if ( $global_config['allowuserreg'] == 2 )
        {
            $user_ops[] = 'lostactivelink';
            $user_ops[] = 'active';
        }
    }
    
    $sql = "SELECT * FROM  `" . NV_MODULES_TABLE . "` AS m LEFT JOIN `" . NV_MODFUNCS_TABLE . "` AS f ON m.title=f.in_module WHERE m.act = 1 ORDER BY m.weight, f.subweight";
    
    $list = nv_db_cache( $sql, '', 'modules' );
    
    if ( empty( $list ) ) return array();
    
    $site_mods = array();
    foreach ( $list as $row )
    {
        $allowed = false;
        $is_modadmin = false;
        $groups_view = ( string )$row['groups_view'];
        if ( isset( $site_mods[$row['title']] ) )
        {
            $allowed = true;
            $is_modadmin = $site_mods[$row['title']]['is_modadmin'];
        }
        elseif ( defined( 'NV_IS_SPADMIN' ) )
        {
            $allowed = true;
            $is_modadmin = true;
        }
        elseif ( defined( 'NV_IS_ADMIN' ) and ! empty( $row['admins'] ) and ! empty( $admin_info['admin_id'] ) and in_array( $admin_info['admin_id'], explode( ",", $row['admins'] ) ) )
        {
            $allowed = true;
            $is_modadmin = true;
        }
        elseif ( $row['title'] == $global_config['site_home_module'] )
        {
            $allowed = true;
        }
        elseif ( $groups_view == "0" )
        {
            $allowed = true;
        }
        elseif ( $groups_view == "1" and defined( 'NV_IS_USER' ) )
        {
            $allowed = true;
        }
        elseif ( $groups_view == "2" and defined( 'NV_IS_ADMIN' ) )
        {
            $allowed = true;
        }
        elseif ( defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups_view ) )
        {
            $allowed = true;
        }
        
        if ( $allowed )
        {
            $m_title = $row['title'];
            if ( ! isset( $site_mods[$m_title] ) )
            {
                $site_mods[$m_title]['module_file'] = $row['module_file'];
                $site_mods[$m_title]['module_data'] = $row['module_data'];
                $site_mods[$m_title]['custom_title'] = $row['custom_title'];
                $site_mods[$m_title]['admin_file'] = $row['admin_file'];
                $site_mods[$m_title]['theme'] = $row['theme'];
                $site_mods[$m_title]['keywords'] = $row['keywords'];
                $site_mods[$m_title]['groups_view'] = $row['groups_view'];
                $site_mods[$m_title]['in_menu'] = $row['in_menu'];
                $site_mods[$m_title]['submenu'] = $row['submenu'];
                $site_mods[$m_title]['is_modadmin'] = $is_modadmin;
                $site_mods[$m_title]['rss'] = $row['rss'];
            }
            $func_name = $row['func_name'];
            if ( ! empty( $func_name ) and ( ( $m_title != "users" ) or ( $m_title == "users" and in_array( $func_name, $user_ops ) ) ) )
            {
                $site_mods[$m_title]['funcs'][$func_name]['func_id'] = $row['func_id'];
                $site_mods[$m_title]['funcs'][$func_name]['show_func'] = $row['show_func'];
                $site_mods[$m_title]['funcs'][$func_name]['func_custom_name'] = $row['func_custom_name'];
                $site_mods[$m_title]['funcs'][$func_name]['in_submenu'] = $row['in_submenu'];
                $site_mods[$m_title]['funcs'][$func_name]['layout'] = $row['layout'];
            }
        }
    }
    unset( $row, $allowed, $m_title, $func_name );
    return $site_mods;
}

/**
 * nv_create_submenu()
 * 
 * @return void
 */
function nv_create_submenu ( )
{
    global $nv_vertical_menu, $module_name, $module_info, $op;
    
    foreach ( $module_info['funcs'] as $key => $values )
    {
        if ( ! empty( $values['in_submenu'] ) )
        {
            $func_custom_name = trim( ! empty( $values['func_custom_name'] ) ? $values['func_custom_name'] : $key );
            $link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . ( $key != "main" ? "&amp;" . NV_OP_VARIABLE . "=" . $key : "" );
            $act = $key == $op ? 1 : 0;
            $nv_vertical_menu[] = array( $func_custom_name, $link, $act );
        }
    }
}

/**
 * nv_setBlockAllowed()
 * 
 * @param mixed $groups_view
 * @return
 */
function nv_setBlockAllowed( $groups_view )
{
    global $user_info;

    if ( defined( 'NV_IS_SPADMIN' ) ) return true;

    $groups_view = ( string )$groups_view;
    if ( $groups_view == "0" or ( $groups_view == "1" and defined( 'NV_IS_USER' ) ) or ( $groups_view == "2" and defined( 'NV_IS_MODADMIN' ) ) ) return true;
    if ( defined( 'NV_IS_USER' ) and nv_is_in_groups( $user_info['in_groups'], $groups_view ) ) return true;

    return false;
}

/**
 * nv_blocks_get_content()
 * 
 * @return
 */

function nv_blocks_content( $sitecontent )
{
    global $db, $module_info, $op, $global_config, $lang_global, $site_mods, $user_info;

    //Lay danh sach cac nhom block trong config.ini
    $xml = simplexml_load_file( NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/config.ini' );
    $_content = $xml->xpath( 'positions' ); //array
    $_position = $_content[0]->position; //object
    $_posAllowed = array();
    foreach ( $_position as $_pos )
    {
        $_pos = trim( ( string )$_pos->tag );
        unset( $matches );
        if ( preg_match( "/^\[([^\]]+)\]$/is", $_pos, $matches ) )
        {
            $_posAllowed[] = $matches[1];
        }
    }

    if ( empty( $_posAllowed ) ) return $sitecontent;

    //Tim trong noi dung trang cac doan ma phu hop voi cac nhom block tren
    $_posAllowed = implode( "|", array_map( "nv_preg_quote", $_posAllowed ) );
    preg_match_all( "/\[(" . $_posAllowed . ")(\d+)?\]()/", $sitecontent, $_posReal );

    if ( empty( $_posReal[0] ) ) return $sitecontent;

    $_posReal = array_combine( $_posReal[0], $_posReal[3] );

    //Lay ra cac block thuoc cac nhom tren
    $_in = implode( ",", array_map( array( $db, "dbescape_string" ), array_keys( $_posReal ) ) );
    $_sql = "SELECT * FROM `" . NV_BLOCKS_TABLE . "_groups` AS t1 
    INNER JOIN `" . NV_BLOCKS_TABLE . "_weight` AS t2 
    ON t1.bid = t2.bid 
    WHERE t1.position IN (" . $_in . ") 
    AND (t1.exp_time='0' OR t1.exp_time > " . NV_CURRENTTIME . ") 
    AND t2.func_id='" . $module_info['funcs'][$op]['func_id'] . "' 
    AND t1.theme ='" . $global_config['module_theme'] . "' 
    AND t1.active=1 
    ORDER BY t2.weight ASC";

    if ( ( $_result = $db->sql_query( $_sql ) ) !== false )
    {
        while ( $_row = $db->sql_fetch_assoc( $_result ) )
        {
            //Kiem tra quyen xem block
            if ( nv_setBlockAllowed( $_row['groups_view'] ) )
            {
                //tieu de block
                $blockTitle = ( ! empty( $_row['title'] ) and ! empty( $_row['link'] ) ) ? "<a href=\"" . $_row['link'] . "\">" . $_row['title'] . "</a>" : $_row['title'];

                //Noi dung block
                $content = "";

                //Cau hinh block
                $block_config = ( ! empty( $_row['config'] ) ) ? unserialize( $_row['config'] ) : array();
                $block_config['bid'] = $_row['bid'];
                $block_config['module'] = $_row['module'];
                $block_config['block_name'] = substr( $_row['file_name'], 0, -4 );

                if ( $_row['module'] == "global" and file_exists( NV_ROOTDIR . "/includes/blocks/" . $_row['file_name'] ) )
                {
                    include ( NV_ROOTDIR . "/includes/blocks/" . $_row['file_name'] );
                } elseif ( isset( $site_mods[$_row['module']]['module_file'] ) //
                and ! empty( $site_mods[$_row['module']]['module_file'] ) //
                    and file_exists( NV_ROOTDIR . "/modules/" . $site_mods[$_row['module']]['module_file'] . "/blocks/" . $_row['file_name'] ) )
                {
                    include ( NV_ROOTDIR . "/modules/" . $site_mods[$_row['module']]['module_file'] . "/blocks/" . $_row['file_name'] );
                }
                unset( $block_config, $_row['config'] );

                if ( ! empty( $content ) or defined( 'NV_IS_DRAG_BLOCK' ) )
                {
                    $xtpl = null;
                    $_row['template'] = ( empty( $_row['template'] ) ) ? "default" : $_row['template'];
                    if ( ! empty( $module_info['theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $module_info['theme'] . "/layout/block." . $_row['template'] . ".tpl" ) )
                    {
                        $xtpl = new XTemplate( "block." . $_row['template'] . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['theme'] . "/layout" );
                    } elseif ( ! empty( $global_config['module_theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/layout/block." . $_row['template'] . ".tpl" ) )
                    {
                        $xtpl = new XTemplate( "block." . $_row['template'] . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/layout" );
                    } elseif ( ! empty( $global_config['site_theme'] ) and file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout/block." . $_row['template'] . ".tpl" ) )
                    {
                        $xtpl = new XTemplate( "block." . $_row['template'] . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/layout" );
                    } elseif ( file_exists( NV_ROOTDIR . "/themes/default/layout/block." . $_row['template'] . ".tpl" ) )
                    {
                        $xtpl = new XTemplate( "block." . $_row['template'] . ".tpl", NV_ROOTDIR . "/themes/default/layout" );
                    }
                    if ( ! empty( $xtpl ) )
                    {
                        $xtpl->assign( 'BLOCK_TITLE', $blockTitle );
                        $xtpl->assign( 'BLOCK_CONTENT', $content );
                        $xtpl->parse( 'mainblock' );
                        $content = $xtpl->text( 'mainblock' );
                    }
                    else
                    {
                        $content = $blockTitle . "<br />" . $content . "<br />";
                    }

                    if ( defined( 'NV_IS_DRAG_BLOCK' ) )
                    {
                        $content = '<div class="portlet" id="bl_' . ( $_row['bid'] ) . '">
                            <p>
                            <a href="javascript:void(0)" class="block_content" name="' . $_row['bid'] . '">
                                <img style="border:none" src="' . NV_BASE_SITEURL . 'images/edit.png" alt="' . $lang_global['edit_block'] . '"/> ' . $lang_global['edit_block'] . '</a> | <a href="javascript:void(0)" class="delblock" name="' . $_row['bid'] . '">
                                <img style="border:none" src="' . NV_BASE_SITEURL . 'images/delete.png" alt="' . $lang_global['delete_block'] . '"/> ' . $lang_global['delete_block'] . '</a> | <a href="javascript:void(0)" class="outgroupblock" name="' . $_row['bid'] . '">
                                <img style="border:none" src="' . NV_BASE_SITEURL . 'images/outgroup.png" alt="' . $lang_global['outgroup_block'] . '"/> ' . $lang_global['outgroup_block'] . '</a>
                            </p>
                            ' . $content . '</div>';
                    }
                    $_posReal[$_row['position']] .= $content;
                }
            }
        }

        $db->sql_freeresult( $_result );
    }

    if ( defined( 'NV_IS_DRAG_BLOCK' ) )
    {
        foreach ( array_keys( $_posReal ) as $__pos )
        {
            $_posReal[$__pos] = '<div class="column" id="' . ( preg_replace( '#\[|\]#', '', $__pos ) ) . '">' . $_posReal[$__pos];
            $_posReal[$__pos] .= '	<span><a class="block_content" id="' . $__pos . '" href="javascript:void(0)"><img style="border:none" src="' . NV_BASE_SITEURL . 'images/add.png" alt="' . $lang_global['add_block'] . '"/> ' . $lang_global['add_block'] . '</a></span>';
            $_posReal[$__pos] .= '</div>';
        }
    }

    $sitecontent = str_replace( array_keys( $_posReal ), array_values( $_posReal ), $sitecontent );

    return $sitecontent;
}

/**
 * nv_html_meta_tags()
 * 
 * @return
 */
function nv_html_meta_tags ( )
{
    global $global_config, $lang_global, $key_words, $description, $module_info;
    
    $return = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . $global_config['site_charset'] . "\" />\n";
    $kw = array();
    if ( ! empty( $key_words ) ) $kw[] = $key_words;
    if ( ! empty( $module_info['keywords'] ) ) $kw[] = $module_info['keywords'];
    if ( ! empty( $global_config['site_keywords'] ) ) $kw[] = $global_config['site_keywords'];
    if ( ! empty( $kw ) )
    {
        $kw = implode( ",", $kw );
        $kw = preg_replace( "/\,\s+/", ",", $kw );
        $key_words = nv_strtolower( strip_tags( $kw ) );
        $return .= "<meta name=\"keywords\" content=\"" . $key_words . "\" />\n";
    }
    
    $ds = ( ! empty( $description ) ) ? $description : $global_config['site_description'];
    $return .= ( ! empty( $ds ) ) ? "<meta name=\"description\" content=\"" . strip_tags( $ds ) . "\" />\n" : "";
    
    $file_metatags = NV_ROOTDIR . "/" . NV_DATADIR . "/metatags.xml";
    if ( file_exists( $file_metatags ) )
    {
        $mt = file_get_contents( $file_metatags );
        $patters = array();
        $patters['/\{CONTENT\-LANGUAGE\}/'] = $lang_global['Content_Language'];
        $patters['/\{LANGUAGE\}/'] = $lang_global['LanguageName'];
        $patters['/\{SITE\_NAME\}/'] = $global_config['site_name'];
        $patters['/\{SITE\_EMAIL\}/'] = $global_config['site_email'];
        $mt = preg_replace( array_keys( $patters ), array_values( $patters ), $mt );
        $mt = preg_replace( "/\{(.*)\}/", "", $mt );
        $mt = simplexml_load_string( $mt );
        $mt = nv_object2array( $mt );
        if ( $mt['meta_item'] )
        {
            if ( isset( $mt['meta_item'][0] ) ) $metatags = $mt['meta_item'];
            else $metatags[] = $mt['meta_item'];
            
            foreach ( $metatags as $meta )
            {
                if ( ( $meta['group'] == "http-equiv" or $meta['group'] == "name" ) and preg_match( "/^[a-zA-Z0-9\-\_\.]+$/", $meta['value'] ) and preg_match( "/^([^\'\"]+)$/", $meta['content'] ) )
                {
                    $return .= "<meta " . $meta['group'] . "=\"" . $meta['value'] . "\" content=\"" . $meta['content'] . "\" />\n";
                }
            }
        }
    }
    $return .= "<meta name=\"generator\" content=\"NukeViet v3.x\" />\n";
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $return .= "<meta http-equiv=\"refresh\" content=\"" . NV_ADMIN_CHECK_PASS_TIME . "\" />\n";
    }
    return $return;
}

/**
 * nv_html_page_title()
 * 
 * @return
 */
function nv_html_page_title ( )
{
    global $home, $module_info, $op, $global_config, $page_title;
    $array_title = array();
    $array_title[] = $global_config['site_name'];
    if ( $home )
    {
        if ( ! empty( $global_config['site_description'] ) )
        {
            $array_title[] = $global_config['site_description'];
        }
    }
    else
    {
        //$array_title[] = $module_info['custom_title'];
        if ( ! empty( $page_title ) )
        {
            $array_title[] = $page_title;
        }
        elseif ( $op != "main" )
        {
            $array_title[] = $module_info['funcs'][$op]['func_custom_name'];
        }
        sort( $array_title, SORT_NUMERIC );
    }
    $defis = trim( NV_TITLEBAR_DEFIS );
    $defis = ! empty( $defis ) ? ' ' . urldecode( $defis ) . ' ' : ' - ';
    return "<title>" . strip_tags( implode( $defis, $array_title ) ) . "</title>\n";
}

/**
 * nv_html_css()
 * 
 * @return
 */
function nv_html_css ( )
{
    global $module_info, $module_file;
    $return = "";
    if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/css/" . $module_file . ".css" ) )
    {
        $return .= "<link rel=\"StyleSheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/css/" . $module_file . ".css\" type=\"text/css\" />\n";
    }
    return $return;
}

/**
 * nv_html_site_rss()
 * 
 * @return
 */
function nv_html_site_rss ( )
{
    global $rss, $lang_global, $global_config;
    $return = "";
    if ( ! empty( $rss ) )
    {
        foreach ( $rss as $rss_item )
        {
            $return .= "<link rel=\"alternate\" href=\"" . $rss_item['src'] . "\" title=\"" . strip_tags( $rss_item['title'] ) . "\" type=\"application/rss+xml\" />\n";
        }
    }
    return $return;
}

/**
 * nv_html_site_js()
 * 
 * @return
 */
function nv_html_site_js ( )
{
    global $global_config, $module_info, $module_name, $module_file, $lang_global, $op, $client_info;
    $return = "";
    $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/" . NV_LANG_INTERFACE . ".js\"></script>\n";
    $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.min.js\"></script>\n";
    $return .= "<script type=\"text/javascript\">\n";
    $return .= "var nv_siteroot = '" . NV_BASE_SITEURL . "';\n";
    $return .= "var nv_sitelang = '" . NV_LANG_INTERFACE . "';\n";
    $return .= "var nv_name_variable = '" . NV_NAME_VARIABLE . "';\n";
    $return .= "var nv_fc_variable = '" . NV_OP_VARIABLE . "';\n";
    $return .= "var nv_lang_variable = '" . NV_LANG_VARIABLE . "';\n";
    $return .= "var nv_module_name = '" . $module_name . "';\n";
    $return .= "var nv_my_ofs = " . round( NV_SITE_TIMEZONE_OFFSET / 3600 ) . ";\n";
    $return .= "var nv_my_abbr = '" . nv_date( "T", NV_CURRENTTIME ) . "';\n";
    $return .= "var nv_cookie_prefix = '" . $global_config['cookie_prefix'] . "';\n";
    $return .= "var nv_area_admin = 0;\n";
    $return .= "</script>\n";
    $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/global.js\"></script>\n";
    if ( defined( 'NV_IS_ADMIN' ) )
    {
        $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/admin.js\"></script>\n";
    }
    if ( file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/js/user.js" ) )
    {
        $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/user.js\"></script>\n";
    }
    if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_add_editor_js' ) )
    {
        $return .= nv_add_editor_js();
    }
    
    if ( defined( 'NV_IS_DRAG_BLOCK' ) )
    {
        if ( ! defined( 'SHADOWBOX' ) )
        {
            $return .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
            $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
            $return .= "<script type=\"text/javascript\">Shadowbox.init();</script>";
            define( 'SHADOWBOX', true );
        }
        $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
        $return .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.sortable.min.js\"></script>\n";
        $return .= '<script type="text/javascript">
        			//<![CDATA[
					var blockredirect = "' . nv_base64_encode( $client_info['selfurl'] ) . '";
					$(function() {				
						$("a.delblock").click(function(){
							var bid = $(this).attr("name");
							if (confirm("' . $lang_global['block_delete_confirm'] . '")){
								$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=blocks_del", "bid="+bid, function(theResponse){
									alert(theResponse);
									window.location.href = "' . $client_info['selfurl'] . '";
								});
							}
						});
						
						$("a.outgroupblock").click(function(){
							var bid = $(this).attr("name");
							if (confirm("' . $lang_global['block_outgroup_confirm'] . '")){
								$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=front_outgroup", "func_id=' . $module_info['funcs'][$op]['func_id'] . '&bid="+bid, function(theResponse){
									alert(theResponse);
								});
							}
						});
											
						$("a.block_content").click(function(){
							var bid = $(this).attr("name");
							var tag = $(this).attr("id");
							Shadowbox.open(
						      {
						         content : "<iframe src=\'' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=block_content&selectthemes=' . $global_config['module_theme'] . '&tag="+tag+"&bid="+bid+"&blockredirect="+blockredirect+"\' style=\'width:780px;height:450px\'></iframe>",
						         player : "html",
						         height : 450,
						         width : 780
						      }
						      );							
	            		});

	            		var func_id = ' . ( $module_info['funcs'][$op]['func_id'] ) . ';
	            		var post_order = false;
						$(".column").sortable({
							connectWith: \'.column\',
							opacity: 0.8, 
							cursor: \'move\',
							receive: function(){
									post_order = true;
									var position = $(this).attr("id");
									var order = $(this).sortable("serialize");
									$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=sort_order", order+"&position="+position+"&func_id="+func_id, function(theResponse){
										if(theResponse=="OK_"+func_id){
					    					$("div#toolbar>ul.info").html("<li><span style=\'color:#ff0000;padding-left:150px;font-weight:700;\'>' . $lang_global['blocks_saved'] . '</span></li>").fadeIn(1000);
										}
										else{
											alert("' . $lang_global['blocks_saved_error'] . '");
										}
									});
							},
							stop: function() {
								if(post_order == false){
									var order = $(this).sortable("serialize");
									$.post("' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=themes&' . NV_OP_VARIABLE . '=sort_order", order+"&func_id="+func_id, function(theResponse){
										if(theResponse=="OK_"+func_id){
					    					$("div#toolbar>ul.info").html("<span style=\'color:#ff0000;padding-left:150px;font-weight:700;\'>' . $lang_global['blocks_saved'] . '</span>").fadeIn(1000);
										}
										else{
											alert("' . $lang_global['blocks_saved_error'] . '");
										}
									});
								}
							}
						});	
						$(".column").disableSelection();
					});
					//]]>
					</script>';
    }
    return $return;
}

function nv_admin_menu()
{
    global $lang_global, $admin_info, $module_info, $module_name, $global_config, $db, $my_head;

    $block_theme = "default";

    $xtpl = new XTemplate( "admin_toolbar.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/system" );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'ADMIN_INFO', $admin_info );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
    $xtpl->assign( 'NV_ADMINDIR', NV_ADMINDIR );
    $xtpl->assign( 'URL_AUTHOR', NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=authors&amp;id=" . $admin_info['admin_id'] );

    if ( defined( 'NV_IS_SPADMIN' ) )
    {
    	if ( ! defined( 'SHADOWBOX' ) )
        {
            $my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
            $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
            $my_head .= "<script type=\"text/javascript\">Shadowbox.init();</script>";
            define( 'SHADOWBOX', true );
        }
        $new_drag_block = ( defined( 'NV_IS_DRAG_BLOCK' ) ) ? 0 : 1;
        $lang_drag_block = ( $new_drag_block ) ? $lang_global['drag_block'] : $lang_global['no_drag_block'];

        $xtpl->assign( 'URL_DBLOCK', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;drag_block=" . $new_drag_block );
        $xtpl->assign( 'LANG_DBLOCK', $lang_drag_block );

        $total_time = substr( ( array_sum( explode( " ", microtime() ) ) - NV_START_TIME + $db->time ), 0, 5 );
        $xtpl->assign( 'COUNT_SHOW_QUERIES', count( $db->query_strs ) . " / " . $total_time );
		
        foreach ( $db->query_strs as $key => $field )
        {
        	$data = array(
        		"class"=>( $key % 2 ) ? " highlight" : " normal",
        		"imgsrc"=> ($field[1]) ? NV_BASE_SITEURL . "themes/" . $block_theme . "/images/icons/good.png" : NV_BASE_SITEURL . "themes/" . $block_theme . "/images/icons/bad.png",
        		"imgalt"=> ($field[1]) ? $lang_global['ok'] : $lang_global['fail'],
        		"queries"=> nv_htmlspecialchars( $field[0] )
			);
    		$xtpl->assign( 'DATA', $data );
    		$xtpl->parse( 'main.is_spadadmin3.queries' );
        }	
        $xtpl->parse( 'main.is_spadadmin' );
        $xtpl->parse( 'main.is_spadadmin2' );
        $xtpl->parse( 'main.is_spadadmin3' );
    }

    if ( defined( 'NV_IS_MODADMIN' ) and ! empty( $module_info['admin_file'] ) )
    {
        $xtpl->assign( 'URL_MODULE', NV_BASE_SITEURL . NV_ADMINDIR . "/index.php?" . NV_NAME_VARIABLE . "=" . $module_name );

        $xtpl->parse( 'main.is_modadmin' );
    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

/**
 * nv_groups_list_pub()
 * 
 * @return
 */
function nv_groups_list_pub ( )
{
    global $db;
    $query = "SELECT `group_id`, `title` FROM `" . NV_GROUPS_GLOBALTABLE . "` WHERE `public`=1 AND `act`=1 AND (`exp_time` =0 OR `exp_time` >". NV_CURRENTTIME.") ORDER BY `weight`";
    $result = $db->sql_query( $query );
    $groups = array();
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $groups[$row['group_id']] = $row['title'];
    }   
    return $groups;
    
}
?>