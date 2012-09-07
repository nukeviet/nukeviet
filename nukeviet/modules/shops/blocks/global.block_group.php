<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 3/9/2010 23:25
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! function_exists( 'nv_pro_group' ) )
{
	function GetCatidInChild ( $catid )
	{
	    global $global_array_cat, $array_cat;
	    $array_cat[] = $catid;
	    if ( !empty($global_array_cat[$catid]['parentid']) && ($global_array_cat[$catid]['parentid']>0) )
	    {
	    	$array_cat[] = $global_array_cat[$catid]['parentid'];
	    	$array_cat_temp = GetCatidInChild( $global_array_cat[$catid]['parentid'] );
	        foreach ( $array_cat_temp as $catid_i )
	        {
	            $array_cat[] = $catid_i;
	        }
	    }
	    return array_unique( $array_cat );
	}
    function getgroup_ckhtml ( $data_group, $pid,$catid=0 )
    {
        $contents_temp = "";
        if ( ! empty( $data_group ) )
        {
            foreach ( $data_group as $groupid_i => $groupinfo_i )
            {
                if ( $groupinfo_i['parentid'] == $pid )
                {
                    $xtitle_i = "";
                    if ( $groupinfo_i['lev'] > 0 )
                    {
                        for ( $i = 1; $i <= $groupinfo_i['lev']; $i ++ )
                        {
                            $xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                    }
                    $contents_temp .= "<li><a href=\"" . $groupinfo_i['link'] . '/' . $catid . "\">" . $xtitle_i . "" . $groupinfo_i['title'] . " (" .$groupinfo_i['numpro']. ")". "</a></li>";
                    if ( $groupinfo_i['numsubgroup'] > 0 )
                    {
                        $contents_temp .= getgroup_ckhtml( $data_group, $groupid_i );
                    }
                }
            }
        }
        return $contents_temp;
    }

    function nv_pro_group ( $block_config )
    {
        global $site_mods, $db_config, $db, $global_array_group, $module_name, $module_info, $catid,$array_op,$global_array_cat;
		if ( $catid == 0 )
		{
			$temp_id = isset( $array_op[2] ) ? $array_op[2] : "";
			if ( ! empty( $temp_id ) )
			{
				$catid = intval($temp_id);
			}
		}
        $module = $block_config['module'];
        $mod_data = $site_mods[$module]['module_data'];
        $mod_file = $site_mods[$module]['module_file'];
        $data_group = $global_array_group;
        if ( $module != $module_name )
        {
            $sql = "SELECT groupid, parentid,cateid, lev," . NV_LANG_DATA . "_title, " . NV_LANG_DATA . "_alias, viewgroup, numsubgroup, subgroupid, numlinks, del_cache_time, " . NV_LANG_DATA . "_description, inhome, " . NV_LANG_DATA . "_keywords, who_view, groups_view, numpro FROM `" . $db_config['prefix'] . "_" . $mod_data . "_group` ORDER BY `order` ASC";
            $result = $db->sql_query( $sql );
            while ( list( $groupid_i, $parentid_i, $cateid_i, $lev_i, $title_i, $alias_i, $viewgroup_i, $numsubgroup_i, $subgroupid_i, $numlinks_i, $del_cache_time_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ,$numpro_i) = $db->sql_fetchrow( $result ) )
            {
                $link_i = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=group/" . $alias_i . "-" . $groupid_i;
                $data_group[$groupid_i] = array( 
                    "groupid" => $groupid_i, "parentid" => $parentid_i, "cateid" => $cateid_i, "title" => $title_i, "alias" => $alias_i, "link" => $link_i, "viewgroup" => $viewgroup_i, "numsubgroup" => $numsubgroup_i, "subgroupid" => $subgroupid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i, "lev" => $lev_i, "numpro" => $numpro_i 
                );
            }
        }
        if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block.group.tpl" ) )
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        $xtpl = new XTemplate( "block.group.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
        $array_cat = GetCatidInChild ( $catid );
        $contents_temp_none = $contents_temp_chid = "";
        foreach ( $data_group as $groupid_i => $groupinfo_i )
        {
            if ( $groupinfo_i['parentid'] == 0 && $groupinfo_i['cateid'] == 0 )
            {
            	$contents_temp_none .= "<ul>";
                $contents_temp_none .= "<li class=\"parent\"><a href=\"" . $groupinfo_i['link'] . "\">" . $groupinfo_i['title'] . "</a></li>";
                if ( $groupinfo_i['numsubgroup'] > 0 )
                {
                    $contents_temp_none .= getgroup_ckhtml( $data_group, $groupid_i );
                }
                $contents_temp_none .= "</ul>";
            }
            elseif ( $groupinfo_i['parentid'] == 0 && in_array($groupinfo_i['cateid'],$array_cat) && $catid > 0 )
            {
            	$contents_temp_chid .= "<ul>";
            	$contents_temp_chid .= "<li class=\"parent\"><a href=\"" . $groupinfo_i['link'] .'/'.$catid. "\">" . $groupinfo_i['title'] . "</a></li>";
                if ( $groupinfo_i['numsubgroup'] > 0 )
                {
                    $contents_temp_chid .= getgroup_ckhtml( $data_group, $groupid_i ,$catid);
                }
                $contents_temp_chid .= "</ul>";
            }
        }
        $xtpl->assign('content1',$contents_temp_none);
        $xtpl->assign('content2',$contents_temp_chid);
        $xtpl->parse( 'main' );
        return $xtpl->text( 'main' );
    }
}
if ( defined( 'NV_SYSTEM' ) )
{
    global $site_mods, $global_array_group, $module_name;
    $content = nv_pro_group( $block_config );
}

?>