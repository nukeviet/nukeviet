<?php
/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM
 */

if (!defined('NV_IS_MOD_OCHU')) {
	die('Stop!!!');
}

function nv_theme_samples_main($sql_data, $now_page) {
	global $db, $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $module_data;
	$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
	$xtpl->assign('LANG', $lang_module);

	$data = $db->query($sql_data);
	$i = 1;
	while (list($id, $title, $content, $key, $quession) = $data->fetch(3)) {
		$xtpl->assign('URL_VIEW', NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=view&amp;id=" . $id);

		$xtpl->assign('title', $title);
		$xtpl->assign('STT', $i);

		$xtpl->parse('main.loop');
		$i++;
	}
	$xtpl->parse('main');
	return $xtpl->text('main');
}

function nv_theme_onbai_test($key, $suggest, $data, $info, $do) {
	global $global_config, $module_name, $module_file, $lang_module, $module_config, $module_info, $module_data;
	$xtpl = new XTemplate("view.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
	$xtpl->assign('LANG', $lang_module);

	$xtpl->assign('URL_IMG', NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/ochu/arrow2.gif");

	// xu li key
	$i = 0;
	$max = $key[0];
	while ($i < count($key)) {
		if ($key[$i] > $max) {
			$max = $key[$i];
		}

		$i++;
	}

	foreach ($data as $stt => $tr) {
		// viet các td trong phia truoc
		$nullbefore = ($max + 1) - $key[$stt];
		$i = 1;
		while ($i <= $nullbefore) {
			// thong tin dung sai
			if (($i == $nullbefore) && ($do == "OK")) {
				if ($info[$stt] == true) {
					$xtpl->assign('INFO', "<img src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/ochu/yes.png\" border=\"0\" />");
				} else {
					$xtpl->assign('INFO', "<img src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/ochu/no.png\" border=\"0\" />");
				}

				$xtpl->parse('main.loop.null.info');
			}

			$xtpl->parse('main.loop.null');
			$i++;
		}
		// viet o hang ngang
		$td = explode(",", $tr);
		foreach ($td as $td_stt => $this_td) {
			// viet cac o chu
			if (($key[$stt] - 1) == $td_stt) {
				$xtpl->assign('KEYCLASS', 'key');
			} else {
				$xtpl->assign('KEYCLASS', 'cell');
			}

			$xtpl->assign('row', $this_td);
			$xtpl->assign('NAME', "tr_" . $stt . "_td_" . $td_stt);
			$xtpl->parse('main.loop.row');
		}
		$xtpl->assign('title', $tr);
		$xtpl->assign('suggest', $suggest[$stt]);
		$xtpl->parse('main.loop');
	}
	$i = 1;
	while ($i <= ($max + 1)) {
		if ($i == ($max + 1)) {
			$xtpl->assign('LASTINFO', "<a href=\"javascript:void(0);\" onMouseover=\"ddrivetip('" . $suggest[count($data)] . "')\"; onMouseout=\"hideddrivetip()\"><img src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/ochu/application.png\" border=\"0\" />");
		}

		$xtpl->parse('main.last');
		$i++;
	}
	$xtpl->parse('main');
	return $xtpl->text('main');

}
?>