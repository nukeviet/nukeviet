<?php

	/**
	 * @Project NUKEVIET 3.0
	 * @Author VINADES.,JSC (contact@vinades.vn)
	 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
	 * @Createdate 2-10-2010 18:49
	 */

	if (!defined('NV_IS_FILE_ADMIN'))
		die('Stop!!!');

	$catid = $nv_Request->get_int('catid', 'post', 0);
	$contents = "NO_" . $catid;

	list($catid, $parentid, $title) = $db->sql_fetchrow($db->sql_query("SELECT `catid`, `parentid`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`=" . intval($catid) . ""));
	if ($catid > 0)
	{
		if ((defined('NV_IS_ADMIN_MODULE') or ($parentid > 0 and isset($array_cat_admin[$admin_id][$parentid]) and $array_cat_admin[$admin_id][$parentid]['admin'] == 1)))
		{
			$delallcheckss = $nv_Request->get_string('delallcheckss', 'post', "");
			list($check_parentid) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `parentid` = '" . $catid . "'"));
			if (intval($check_parentid) > 0)
			{
				$contents = "ERR_CAT_" . sprintf($lang_module['delcat_msg_cat'], $check_parentid);
			}
			else
			{
				list($check_rows) = $db->sql_fetchrow($db->sql_query("SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`"));
				if (intval($check_rows) > 0)
				{
					if ($delallcheckss == md5($catid . session_id() . $global_config['sitekey']))
					{
						$delcatandrows = $nv_Request->get_string('delcatandrows', 'post', "");
						$movecat = $nv_Request->get_string('movecat', 'post', "");
						$catidnews = $nv_Request->get_int('catidnews', 'post', 0);
						if (empty($delcatandrows) and empty($movecat))
						{
							$sql = "SELECT catid, title, lev FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid` !='" . $catid . "' ORDER BY `order` ASC";
							$result = $db->sql_query($sql);
							$array_cat_list = array();
							$array_cat_list[0] = "&nbsp;";
							while (list($catid_i, $title_i, $lev_i) = $db->sql_fetchrow($result))
							{
								$xtitle_i = "";
								if ($lev_i > 0)
								{
									$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
									for ($i = 1; $i <= $lev_i; ++$i)
									{
										$xtitle_i .= "---";
									}
									$xtitle_i .= ">&nbsp;";
								}
								$xtitle_i .= $title_i;
								$array_cat_list[$catid_i] = $xtitle_i;
							}

							$contents = "<form action=\"" . NV_BASE_ADMINURL . "index.php\" method=\"post\">";
							$contents .= "<input type=\"hidden\" name =\"" . NV_NAME_VARIABLE . "\"value=\"" . $module_name . "\" />";
							$contents .= "<input type=\"hidden\" name =\"" . NV_OP_VARIABLE . "\"value=\"" . $op . "\" />";
							$contents .= "<input type=\"hidden\" name =\"catid\" value=\"" . $catid . "\" />";
							$contents .= "<input type=\"hidden\" name =\"delallcheckss\" value=\"" . $delallcheckss . "\" />";
							$contents .= "<center>";
							$contents .= "<b>" . sprintf($lang_module['delcat_msg_rows_select'], $title, $check_rows) . "</b>";
							$contents .= "<br /><br /><input name=\"delcatandrows\" type=\"submit\" value=\"" . $lang_module['delcatandrows'] . "\" />";
							$contents .= "<br /><br /><b>" . $lang_module['delcat_msg_rows_move'] . "</b>: <select name=\"catidnews\">\n";
							while (list($catid_i, $title_i) = each($array_cat_list))
							{
								$contents .= "<option value=\"" . $catid_i . "\">" . $title_i . "</option>\n";
							}
							$contents .= "</select><input name=\"movecat\" type=\"submit\" value=\"" . $lang_module['action'] . "\"  onclick=\"return nv_check_movecat(this.form, '" . $lang_module['delcat_msg_rows_noselect'] . "')\">\n";
							$contents .= "</center>";
							$contents .= "</form>";
						}
						elseif (!empty($delcatandrows))
						{
							nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['delcatandrows'], $title, $admin_info['userid']);

							$query = $db->sql_query("SELECT id, catid, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`");
							while ($row = $db->sql_fetchrow($query))
							{
								if ($row['catid'] == $row['listcatid'])
								{
									nv_del_content_module($row['id']);
								}
								else
								{
									$arr_catid_old = explode(",", $row['listcatid']);
									$arr_catid_i = array($catid);
									$arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
									if ($catid == $row['catid'])
									{
										$row['catid'] = $arr_catid_news[0];
									}
									foreach ($arr_catid_news as $catid_i)
									{
										$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `catid`=" . $row['catid'] . ", `listcatid` = '" . implode(",", $arr_catid_news) . "' WHERE `id` =" . $row['id']);
									}
									$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `catid`=" . $row['catid'] . ", `listcatid` = '" . implode(",", $arr_catid_news) . "' WHERE `id` =" . $row['id']);
								}
							}
							$db->sql_query("DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`");
							$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`=" . $catid);
							$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_admins` WHERE `catid`=" . $catid);

							nv_fix_cat_order();
							nv_del_moduleCache($module_name);
							Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&parentid=" . $parentid . "");
							die();
						}
						elseif (!empty($movecat) and $catidnews > 0 and $catidnews != $catid)
						{
							list($catidnews, $newstitle) = $db->sql_fetchrow($db->sql_query("SELECT `catid`, `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat`  WHERE `catid` =" . $catidnews));
							if ($catidnews > 0)
							{
								nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['move'], $title . ' --> ' . $newstitle, $admin_info['userid']);

								$query = $db->sql_query("SELECT id, catid, listcatid FROM `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`");
								while ($row = $db->sql_fetchrow($query))
								{
									$arr_catid_old = explode(",", $row['listcatid']);
									$arr_catid_i = array($catid);
									$arr_catid_news = array_diff($arr_catid_old, $arr_catid_i);
									if (!in_array($catidnews, $arr_catid_news))
									{
										$db->sql_query("INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catidnews . "` SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_rows` WHERE `id`=" . $row['id'] . "");
										$arr_catid_news[] = $catidnews;
									}
									if ($catid == $row['catid'])
									{
										$row['catid'] = $catidnews;
									}
									foreach ($arr_catid_news as $catid_i)
									{
										$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . "` SET `catid`=" . $row['catid'] . ", `listcatid` = '" . implode(",", $arr_catid_news) . "' WHERE `id` =" . $row['id']);
									}
									$db->sql_query("UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_rows` SET `catid`=" . $row['catid'] . ", `listcatid` = '" . implode(",", $arr_catid_news) . "' WHERE `id` =" . $row['id']);
								}
								$db->sql_query("DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`");
								$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE `catid`=" . $catid);
								$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_admins` WHERE `catid`=" . $catid);

								nv_fix_cat_order();
								nv_del_moduleCache($module_name);
								Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&parentid=" . $parentid . "");
								die();
							}
						}
					}
					else
					{
						$contents = "ERR_ROWS_" . $catid . "_" . md5($catid . session_id() . $global_config['sitekey']) . "_" . sprintf($lang_module['delcat_msg_rows'], $check_rows);
					}
				}
			}
			if ($contents == "NO_" . $catid)
			{
				if ($delallcheckss == md5($catid . session_id()))
				{
					$query = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_cat` WHERE catid=" . $catid;
					if ($db->sql_query($query))
					{
						nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['delcatandrows'], $title, $admin_info['userid']);
						$db->sql_freeresult();
						nv_fix_cat_order();
						$db->sql_query("DROP TABLE `" . NV_PREFIXLANG . "_" . $module_data . "_" . $catid . "`");
						$contents = "OK_" . $parentid;
					}
					$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_admins` WHERE `catid`=" . $catid);
					nv_del_moduleCache($module_name);
				}
				else
				{
					$contents = "CONFIRM_" . $catid . "_" . md5($catid . session_id());
				}
			}
		}
		else
		{
			$contents = "ERR_CAT_" . $lang_module['delcat_msg_cat_permissions'];
		}
	}

	if (defined('NV_IS_AJAX'))
	{
		include (NV_ROOTDIR . "/includes/header.php");
		echo $contents;
		include (NV_ROOTDIR . "/includes/footer.php");
	}
	else
	{
		Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat");
		die();
	}
?>