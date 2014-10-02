<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_law_block_newg' ) )
{
    function nv_law_block_newg ( $block_config )
    {
        global $module_info, $lang_module, $site_mods, $db, $my_head, $module_name;
        $module = $block_config['module'];
        $data = $site_mods[$module]['module_data'];
        $modfile = $site_mods[$module]['module_file'];
		
		$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $data . "_row WHERE status=1 ORDER BY addtime DESC LIMIT 0,10";
		$result = $db->query( $sql );
		$numrow = $result->rowCount();
		
        if ( ! empty( $numrow ) )
        {
            if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $modfile . "/block_new_law.tpl" ) )
            {
                $block_theme = $module_info['template'];
            }
            else
            {
                $block_theme = "default";
            }
			
            $xtpl = new XTemplate( "block_new_law.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $modfile );
			$title_length = 34;
			
			if( $module_name != $module )
			{
				$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/laws.css\" />";
				$temp_lang_module = $lang_module;
				$lang_module = array();
				include NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_INTERFACE . '.php' ;
				$lang_block_module = $lang_module;
				$lang_module = $temp_lang_module;
			}
			else
			{
				$lang_block_module = $lang_module;
			}
			
			$xtpl->assign( 'LANG', $lang_block_module );

			while( $row = $result->fetch() )
			{
				$link = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=detail/" . change_alias( $row['title'] . "-" . $row['id'] );
				$row['link'] = $link;
				$row['stitle'] = nv_clean60( $row['title'], $title_length );
				
				$xtpl->assign( 'ROW', $row );			
				$xtpl->parse( 'main.loop' );
            }
			
            $xtpl->parse( 'main' );
            return $xtpl->text( 'main' );
        }
    }
}

if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $module_name;
    $module = $block_config['module'];
    if ( isset( $site_mods[$module] ) )
    {
        $content = nv_law_block_newg( $block_config );
    }
}