<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! function_exists( 'nv_pro_group' ) )
{
	/**
	 * GetCatidInChild()
	 *
	 * @param mixed $catid
	 * @return
	 */
	function GetCatidInChild( $catid )
	{
		global $global_array_cat, $array_cat;
		$array_cat[] = $catid;
		if( ! empty( $global_array_cat[$catid]['parentid'] ) && ( $global_array_cat[$catid]['parentid'] > 0 ) )
		{
			$array_cat[] = $global_array_cat[$catid]['parentid'];
			$array_cat_temp = GetCatidInChild( $global_array_cat[$catid]['parentid'] );
			foreach( $array_cat_temp as $catid_i )
			{
				$array_cat[] = $catid_i;
			}
		}
		return array_unique( $array_cat );
	}
	/**
	 * getgroup_ckhtml()
	 *
	 * @param mixed $data_group
	 * @param mixed $pid
	 * @param integer $catid
	 * @return
	 */
	function getgroup_ckhtml( $data_group, $pid, $catid = 0 )
	{
		$contents_temp = "";
		if( ! empty( $data_group ) )
		{
			foreach( $data_group as $groupid_i => $groupinfo_i )
			{
				if( $groupinfo_i['parentid'] == $pid )
				{
					$xtitle_i = "";
					if( $groupinfo_i['lev'] > 0 )
					{
						for( $i = 1; $i <= $groupinfo_i['lev']; $i++ )
						{
							$xtitle_i .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						}
					}
					$contents_temp .= "<li><a href=\"" . $groupinfo_i['link'] . '/' . $catid . "\">" . $xtitle_i . "" . $groupinfo_i['title'] . " <span class=\"badge pull-right\">" . $groupinfo_i['numpro'] . "</span>" . "</a></li>";
					if( $groupinfo_i['numsubgroup'] > 0 )
					{
						$contents_temp .= getgroup_ckhtml( $data_group, $groupid_i );
					}
				}
			}
		}
		return $contents_temp;
	}

	/**
	 * nv_pro_group()
	 *
	 * @param mixed $block_config
	 * @return
	 */
	function nv_pro_group( $block_config )
	{
		global $site_mods, $db_config, $db, $global_array_group, $module_name, $module_info, $catid, $array_op;
		if( $catid == 0 )
		{
			$temp_id = isset( $array_op[2] ) ? $array_op[2] : "";
			if( ! empty( $temp_id ) )
			{
				$catid = intval( $temp_id );
			}
		}
		$module = $block_config['module'];
		$mod_data = $site_mods[$module]['module_data'];
		$mod_file = $site_mods[$module]['module_file'];
		$data_group = $global_array_group;

		if( $module != $module_name )
		{
			$sql = "SELECT groupid, parentid, cateid, lev, " . NV_LANG_DATA . "_title AS title, " . NV_LANG_DATA . "_alias AS alias, viewgroup, numsubgroup, subgroupid, numlinks, " . NV_LANG_DATA . "_description AS description, inhome, " . NV_LANG_DATA . "_keywords AS keywords, groups_view, numpro FROM " . $db_config['prefix'] . "_" . $mod_data . "_group ORDER BY sort ASC";

			$list = nv_db_cache( $sql, "", $module );
			foreach( $list as $row )
			{
				$data_group[$row['groupid']] = array(
					"groupid" => $row['groupid'],
					"parentid" => $row['parentid'],
					"cateid" => $row['cateid'],
					"title" => $row['title'],
					"alias" => $row['alias'],
					"link" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=group/" . $row['alias'],
					"viewgroup" => $row['viewgroup'],
					"numsubgroup" => $row['numsubgroup'],
					"subgroupid" => $row['subgroupid'],
					"numlinks" => $row['numlinks'],
					"description" => $row['description'],
					"inhome" => $row['inhome'],
					"keywords" => $row['keywords'],
					"groups_view" => $row['groups_view'],
					"lev" => $row['lev'],
					"numpro" => $row['numpro']
				);
			}
		}

		if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block.group.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}

		$xtpl = new XTemplate( "block.group.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file );
		$array_cat =  array();
		$array_cat = GetCatidInChild( $catid );
		$contents_temp_none = $contents_temp_chid = "";

		foreach( $data_group as $groupid_i => $groupinfo_i )
		{
			if( $groupinfo_i['parentid'] == 0 && $groupinfo_i['cateid'] == 0 )
			{
				$contents_temp_none .= "<ul class=\"nav nav-pills nav-stacked\">";
				$contents_temp_none .= "<li class=\"active\"><a href=\"" . $groupinfo_i['link'] . "\">" . $groupinfo_i['title'] . "</a></li>";
				if( $groupinfo_i['numsubgroup'] > 0 )
				{
					$contents_temp_none .= getgroup_ckhtml( $data_group, $groupid_i );
				}
				$contents_temp_none .= "</ul>";
			}
			elseif( $groupinfo_i['parentid'] == 0 && in_array( $groupinfo_i['cateid'], $array_cat ) && $catid > 0 )
			{
				$contents_temp_chid .= "<ul class=\"nav nav-pills nav-stacked\">";
				$contents_temp_chid .= "<li class=\"active\"><a href=\"" . $groupinfo_i['link'] . '/' . $catid . "\">" . $groupinfo_i['title'] . "</a></li>";
				if( $groupinfo_i['numsubgroup'] > 0 )
				{
					$contents_temp_chid .= getgroup_ckhtml( $data_group, $groupid_i, $catid );
				}
				$contents_temp_chid .= "</ul>";
			}
		}

		$xtpl->assign( 'content1', $contents_temp_none );
		$xtpl->assign( 'content2', $contents_temp_chid );

		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_pro_group( $block_config );
}