<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_pro_group' ) )
{

    function nv_pro_group ( $block_config )
    {
        global $site_mods,$db_config,$db, $global_array_group,$module_name,$module_info;
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $array_group = $global_array_group;
        if ( $module != $module_name )
        {
        	$sql = "SELECT groupid, parentid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewgroup, numsubgroup, subgroupid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view FROM `" . $db_config['prefix'] . "_" . $mod_data . "_group` ORDER BY `order` ASC";
			$result = $db->sql_query( $sql );
			while ( list( $groupid_i, $parentid_i, $lev_i, $title_i, $alias_i, $viewgroup_i, $numsubgroup_i, $subgroupid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $db->sql_fetchrow( $result ) )
			{
			    $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=group/" . $alias_i . "-".$groupid_i;
			    $array_group[$groupid_i] = array( 
			        "group" => $groupid_i, "parentid" => $parentid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewgroup" => $viewgroup_i, "numsubgroup" => $numsubgroup_i, "subgroupid" => $subgroupid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, 'lev' => $lev_i 
			    );
			}
        }
    	if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/".$mod_file."/block.group.tpl" ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        $xtpl = new XTemplate( "block.group.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
        $cut_num = 24;
        $html = "";
        foreach ( $array_group as $groupid => $group_info )
        {
            if ( $group_info['inhome'] == '1' )
            {
                $group_info['xtitle'] = "";
                if ( $group_info['lev'] > 0 )
                {
                    for ( $i = 1; $i <= $group_info['lev']; ++$i )
                    {
                        $group_info['xtitle'] .= "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                $group_info['tag'] = "";
                $group_info['bolder'] = "class=\"bolder\"";
                if ( $group_info['parentid'] != 0 )
                {
                    $group_info['tag'] = "- ";
                    $group_info['bolder'] = "";
                }
                $xtpl->assign( 'ROW', $group_info );
                $xtpl->parse( 'main.loop' );
            }
        }
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}
if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods,$global_array_group,$module_name;
    $content = nv_pro_group($block_config);
}

?>