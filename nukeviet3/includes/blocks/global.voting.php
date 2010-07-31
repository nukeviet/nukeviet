<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/25/2010 18:6
 */
if ( ! function_exists( 'nv_block_voting' ) )
{
    function nv_block_voting ( )
    {
        global $db, $my_head, $site_mods, $global_config;
        
        $content = "";
        
        if ( isset( $site_mods['voting'] ) )
        {
            $table = NV_PREFIXLANG . "_" . $site_mods['voting']['module_data'];
            $sql = "SELECT `vid`, `question`,`acceptcm` FROM `" . $table . "` WHERE `act`=1  ORDER BY rand() DESC LIMIT 1";
            $result = $db->sql_query( $sql );
            list( $vid, $question, $accept ) = $db->sql_fetchrow( $result );
            if ( $vid )
            {
                $table = $table . "_rows";
                $sql = "SELECT `id`, `title` FROM `" . $table . "` WHERE `vid` = " . $vid . "  ORDER BY `id` ASC";
                $result = $db->sql_query( $sql );
                if ( $db->sql_numrows( $result ) > 0 )
                {
                    $module_file = $site_mods['voting']['module_file'];
                    
                    include_once ( NV_ROOTDIR . "/modules/" . $module_file . "/language/" . NV_LANG_INTERFACE . ".php" );
                    
                    if ( file_exists( NV_ROOTDIR . "/themes/" . $global_config['site_theme'] . "/modules/" . $module_file . "/global.voting.tpl" ) )
                    {
                        $block_theme = $global_config['site_theme'];
                    }
                    else
                    {
                        $block_theme = "default";
                    }
                    
                    $my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
                    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/js/user.js\"></script>\n";
                    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
                    $my_head .= "<script type=\"text/javascript\">
				    	Shadowbox.init();
					</script>";
                    
                    $action = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=voting";
                    
                    $voting_array = array(  //
                        "checkss" => md5( $vid . session_id() . $global_config['sitekey'] ), //
						"accept" => $accept, //
						"errsm" => $accept > 1 ? sprintf( $lang_module['voting_warning_all'], $accept ) : $lang_module['voting_warning_accept1'], //
						"vid" => $vid, //
						"question" => $question, //
						"action" => $action, //
						"langresult" => $lang_module['voting_result'], //
						"langsubmit" => $lang_module['voting_hits'] 
                    ); //
                    $xtpl = new XTemplate( "global.voting.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
                    $xtpl->assign( 'VOTING', $voting_array );
                    while ( $row = $db->sql_fetchrow( $result ) )
                    {
                        $xtpl->assign( 'RESULT', $row );
                        if ( $accept > 1 )
                        {
                            $xtpl->parse( 'main.resultn' );
                        }
                        else
                        {
                            $xtpl->parse( 'main.result1' );
                        }
                    }
                    $xtpl->parse( 'main' );
                    $content = $xtpl->text( 'main' );
                }
            }
        }
        return $content;
    }
}

$content = nv_block_voting();

?>